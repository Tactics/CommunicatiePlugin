<?php

/**
 * Subclass for representing a row from the 'brief_verzonden' table.
 *
 * 
 *
 * @package plugins.ttCommunicatiePlugin.lib.model
 */ 
class BriefVerzonden extends BaseBriefVerzonden
{
  /**
   * Geeft de instantie weer van het object gekoppeld aan de verzonden brief
   *
   * @return mixed
   */
  public function getObject()
  {
    return Misc::getObject($this->getObjectClass(), $this->getObjectId());
  }

  /**
   * Geeft de instantie weer van de bestemmeling gekoppeld aan de verzonden brief
   *
   * @return mixed
   */
  public function getBestemmeling()
  {
    return Misc::getObject($this->getObjectClassBestemmeling(), $this->getObjectIdBestemmeling());
  }

  /**
   * Verzend e-mails
   * 
   * @return int The number of successful recipients
   */
  public function verzendMail()
  {
    $options = array(
      'cc' => $this->getCc() ? explode(';', $this->getCc()) : array(),
      'bcc' => $this->getBcc() ? explode(';', $this->getBcc()) : array(),
      'onderwerp' => $this->getOnderwerp(),
      'skip_template' => true,
      'afzender' => sfConfig::get("sf_mail_sender"), //@todo: afzender bijhouden in briefverzonden
      'attachements' => $this->getAttachments(),
      'img_path' => array(
        array(
          'prefix' => 'cid:',
          'dir'    => sfConfig::get('sf_data_dir') . '/brieven/layouts/images/'
        ),
        array(
          'prefix' => '/images/brief_templates/',
          'dir'    => sfConfig::get('sf_web_dir') . '/images/brief_templates/'
        )
      )
    );
    
    return BerichtPeer::verstuurEmail($this->getAdres(), $this->getHtml(), $options);
  }

  /**
   * Opnieuw verzenden van een e-mail.
   * @return string
   */
  public function herzendEmail()
  {
    try {
      $this->verzendMail();      
    }
    catch (sfException $e) {
      return "Mail kon niet worden verzonden.";
    }

    // log een kopie, maar met aangepaste created_at
    $briefVerzonden = $this->copy();
    $briefVerzonden->setCreatedAt(time());
    $briefVerzonden->save();

    // optioneel ook voor dms link naar attachments
    if ($nodeRef = DmsObjectNodeRefPeer::retrieveByObject($this))
    {
      $nodeRef2 = $nodeRef->copy();
      $nodeRef2->setObjectId($briefVerzonden->getId());
      $nodeRef2->setCreatedAt(time());
      $nodeRef2->save();
    }

    return "Mail verzonden.";
  }

  /**
   * geeft alle attachments terug
   * (on-the-fly-, template- en objectattachments)
   * 
   * @return array
   */
  private function getAttachments()
  {
    // on-the-fly attachments, stored in dms
    $dmsAttachments = $this->getDmsAttachments();

    // brief attachments
    $briefTemplate = $this->getBriefTemplate();
    $templateAttachments = $briefTemplate->getAttachments();
    
    // object-eigen attachements
    $object = $this->getObject();
    $objectAttachments = array();
    if (method_exists($object, 'getBriefAttachments'))
    {
      $objectAttachments = $object->getBriefAttachments();
    }

    $attachments = array_merge(
      $dmsAttachments,
      $templateAttachments,
      $objectAttachments
    );
    
    return $attachments;
  }

  /**
   * returns on-the-fly attachments, stored in dms
   * @return array
   */
  private function getDmsAttachments()
  {
    if (!sfConfig::get('sf_communicatie_dms_store', ''))
    {
      return array();
    }

    $nodeRef = DmsObjectNodeRefPeer::retrieveByObject($this);

    $c = new Criteria();
    $c->add(DmsNodePeer::PARENT_ID, $nodeRef->getNodeId());
    $bijlageNodes = DmsNodePeer::doSelect($c);

    // on-the-fly attachments, stored in dms
    $attachments = array();
    foreach ($bijlageNodes as $bijlageNode)
    {
      if (function_exists('sys_get_temp_dir'))
      {
        $tmpFile = tempnam(sys_get_temp_dir(), 'email_bijlage');
      }
      else
      {
        $tmpFile = tempnam('/tmp', 'email_bijlage');
      }

      $bijlageNode->saveToFile($tmpFile);

      $attachments[$bijlageNode->getName()] = $tmpFile;
    }

    return $attachments;
  }
}

sfPropelBehavior::add('BriefVerzonden', array('updater_loggable'));
