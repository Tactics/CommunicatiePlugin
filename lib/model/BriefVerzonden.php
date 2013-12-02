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
  public function verzendMail()
  {
    $brief_template = $this->getBriefTemplate();

    $briefAttachments = $brief_template->getAttachments($this->getRequest());
    $attachments = array_merge($tmpAttachments, $briefAttachments);

    // object-eigen attachements
    if (method_exists($this->getObject(), 'getBriefAttachments'))
    {
      $objectAttachments = $object->getBriefAttachments();
      $attachments = array_merge($attachments, $objectAttachments);
    }

    $nietVerstuurdReden = '';
    try {
      $options = array(
        'onderwerp' => $this->getOnderwerp(),
        'skip_template' => true,
        'afzender' => $this->afzender,
        'attachements' => $attachments,
        'img_path' => sfConfig::get('sf_data_dir') . DIRECTORY_SEPARATOR . 'brieven' . DIRECTORY_SEPARATOR . 'layouts' . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR
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

      BerichtPeer::verstuurEmail($this->getAdres(), $this->getHtml(), $options);

      $verstuurd = true;
      echo 'Bericht verzonden naar : ' . $this->getAdres();
      echo isset($options['cc']) ? ', cc: ' . implode(';', $options['cc']) : '';
      echo isset($options['bcc']) ? ', bcc: ' . implode(';', $options['bcc']) : '';
      echo '<br/>';
      $counter['verstuurd']++;

      $this->setStatus(BriefVerzondenPeer::STATUS_VERZONDEN);
      $this->save();
    }
    catch(Exception $e)
    {
      $nietVerstuurdReden = '<font color=red>E-mail kon niet verzonden worden naar ' . $this->getAdres() . '<br />Reden: ' . nl2br($e->getMessage()) . '</font><br/>';
      echo $nietVerstuurdReden;
      $counter['error']++;
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
