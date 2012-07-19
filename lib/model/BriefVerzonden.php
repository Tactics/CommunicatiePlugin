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
    $object = eval("return {$this->getObjectClass()}Peer::retrieveByPk({$this->getObjectId()});");
    return $object;
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

      // Log de brief
      $briefVerzonden = new BriefVerzonden();
      $briefVerzonden->setObjectClass($this->getObjectClass());
      $briefVerzonden->setObjectId($this->getObjectId());
      $briefVerzonden->setBriefTemplateId($this->getBriefTemplateId());
      $briefVerzonden->setMedium($this->getMedium());
      $briefVerzonden->setAdres($this->getAdres());
      $briefVerzonden->setOnderwerp($this->getOnderwerp());
      $briefVerzonden->setHtml($this->getHtml());
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
