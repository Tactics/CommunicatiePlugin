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
   */
  public function verzendMail($attachments = array())
  {
    $briefTemplate = $this->getBriefTemplate();
    $attachments = array_merge($attachments, $briefTemplate->getAttachments());

    $object = $this->getObject();
    // object-eigen attachements
    if (method_exists($object, 'getBriefAttachments'))
    {      
      $attachments = array_merge($attachments, $object->getBriefAttachments());
    }
    
    $nietVerstuurdReden = '';
    $options = array(
      'onderwerp' => $this->getOnderwerp(),
      'skip_template' => true,
      //'afzender' => $this->afzender, ????????????
      'attachements' => $attachments,
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

    $cc = $this->getCc();
    if (!empty($cc))
    {
      $options['cc'] = $cc;
    }

    $bcc = $this->getBcc();
    if (!empty($bcc))
    {
      $options['bcc'] = $bcc;
    }

    $verstuurd = false;
    $email = $this->getAdres();
    try {
      BerichtPeer::verstuurEmail($email, $this->getHtml(), $options);

      $verstuurd = true;
      echo 'Bericht verzonden naar : ' . $email;
      echo isset($options['cc']) ? ', cc: ' . implode(';', $options['cc']) : '';
      echo isset($options['bcc']) ? ', bcc: ' . implode(';', $options['bcc']) : '';
      echo '<br/>';
      //$counter['verstuurd']++;

      $this->setStatus(BriefVerzondenPeer::STATUS_VERZONDEN);
      $this->save();

      // notify object dat er een brief naar het object verzonden is
      if (method_exists($object, 'notifyBriefVerzonden'))
      {
        $object->notifyBriefVerzonden($this);
      }      
    }
    catch(Exception $e)
    {
      $nietVerstuurdReden = '<font color=red>E-mail kon niet verzonden worden naar ' . $email . '<br />Reden: ' . nl2br($e->getMessage()) . '</font><br/>';
      echo $nietVerstuurdReden;
      //$counter['error']++;
    }

    // add log
    if (method_exists($object, 'addLog'))
    {
      $log = "Brief '" . $briefTemplate->getNaam() . "' werd " . ($verstuurd ? "" : "<b>niet</b> ") . "verstuurd via mail naar " . $email . '.';
      $log .= $verstuurd ? '' : '  Reden: ' . $nietVerstuurdReden;
      $object->addLog($log, $verstuurd ? $this->getHtml() : null);
    }
  }


  /**
   * Opnieuw verzenden van een e-mail.
   */
  public function herzendEmail()
  {
    try {
      BerichtPeer::verstuurEmail($this->getAdres(), $this->getHtml(), array(
        'onderwerp' => $this->getOnderwerp(),
        'skip_template' => true,         
        'afzender' => sfConfig::get("sf_mail_sender"),
        'img_path' => sfConfig::get('sf_data_dir') . DIRECTORY_SEPARATOR . 'brieven' . DIRECTORY_SEPARATOR . 'layouts' . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR
      ));

      // log een kopie, maar met aangepaste created_at
      $briefVerzonden = $this->copy();
      $briefVerzonden->setCreatedAt(time());
      $briefVerzonden->save();

      return "Mail verzonden.";
    }
    catch(sfException $e)
    {
      return "Mail kon niet worden verzonden.";
    }
  }
}

sfPropelBehavior::add('BriefVerzonden', array('updater_loggable'));
