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
   * @return object
   */
  public function getObject()
  {
    return Misc::getObject($this->getObjectClass(), $this->getObjectId());
  }

    /**
   * Opnieuw verzenden van een e-mail.
   */
  public function herzendEmail()
  {
    $template = $this->getBriefTemplate();
    $object = $this->getObject();
    if (!$template || !$object)
    {
      return '<div class="alert alert-warning fade-in"><button data-dismiss="alert" class="close">×</button><i class="fa-fw fa fa-warning"></i>Mail is niet verzonden.</div>';
    }

    $attachements = array();
    if (method_exists($object, 'getBriefAttachments'))
    {
      $attachements = $object->getBriefAttachments();
    }
    try{
      $template->sendMailToObject($object, $object->getTtCommunicatieBestemmeling(), array(
        'img_path' => sfConfig::get('sf_data_dir') . DIRECTORY_SEPARATOR . 'brieven' . DIRECTORY_SEPARATOR . 'layouts' . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR,
        'attachements' => $attachements,
        'forceer_versturen' => true
      ));

      // log een kopie, maar met aangepaste created_at
      $briefVerzonden = $this->copy();
      $briefVerzonden->setCreatedAt(time());
      $briefVerzonden->save();

      return '<div class="alert alert-success fade-in"><button data-dismiss="alert" class="close">×</button><i class="fa-fw fa fa-check"></i>Mail is verzonden.</div>';
    }
    catch(sfException $e)
    {
      return sprintf('<div class="alert alert-danger fade-in"><button data-dismiss="alert" class="close">×</button><i class="fa-fw fa fa-times"></i>Mail kon niet worden verzonden: %s </div>', $e->getMessage());
    }


  }
}

sfPropelBehavior::add('BriefVerzonden', array('updater_loggable'));
