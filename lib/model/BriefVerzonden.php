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

    $attachements = [];
    $verzondenBijlages = $this->getBriefVerzondenBijlages();
    /** @var BriefVerzondenBijlage[] $verzondenBijlages */
    foreach ($verzondenBijlages as $verzondenBijlage)
    {
      /** @var DmsNode $node */
      $node = $verzondenBijlage->getDmsNode();
      $tmpFilename = tempnam(sys_get_temp_dir(), $node->getName());
      $handle = fopen($tmpFilename, "w");
      fwrite($handle, $node->read());
      fclose($handle);
      $attachements[$node->getName()] = $tmpFilename;
    }

    $verzondenBestemmeling = Misc::getObject($this->getObjectClassBestemmeling(), $this->getObjectIdBestemmeling());

    try{
      $template->sendMailToObject($object, $verzondenBestemmeling ? $verzondenBestemmeling->getTtCommunicatieBestemmeling() : $object->getTtCommunicatieBestemmeling(), array(
        'img_path' => sfConfig::get('sf_data_dir') . DIRECTORY_SEPARATOR . 'brieven' . DIRECTORY_SEPARATOR . 'layouts' . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR,
        'attachements' => $attachements,
        'forceer_versturen' => true,
        'briefVerzondenId' => $this->getId()
      ));

      foreach ($attachements as $attachement)
      {
        unlink($attachement);
      }

      return '<div class="alert alert-success fade-in"><button data-dismiss="alert" class="close">×</button><i class="fa-fw fa fa-check"></i>Mail is verzonden.</div>';
    }
    catch(sfException $e)
    {
      foreach ($attachements as $attachement)
      {
        unlink($attachement);
      }

      return sprintf('<div class="alert alert-danger fade-in"><button data-dismiss="alert" class="close">×</button><i class="fa-fw fa fa-times"></i>Mail kon niet worden verzonden: %s </div>', $e->getMessage());
    }


  }

  /**
   * Geeft de parentfolder waarin dit dossier opgeslagen moet worden
   */
  public function getDmsStorageParentFolder($autoCreate = true)
  {
    return DmsStorePeer::retrieveByName('verzonden_bijlages');
  }

  /**
   * Geeft de naam van de folder waarin dit dossier opgeslagen moet worden (onder de parent folder)
   */
  public function getDmsStorageFolderName()
  {
    return 'briefVerzonden_' . sprintf('%05u', $this->getId());
  }
}

sfPropelBehavior::add('BriefVerzonden', array('updater_loggable'));
sfPropelBehavior::add('BriefVerzonden', array('storage'));