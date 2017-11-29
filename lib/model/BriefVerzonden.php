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
    if ($this->isWeergaveBeveiligd()) {
      return 'Mail met beveiligde inhoud kan niet opnieuw worden verzonden.';
    }

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

  /**
   * @param string $v
   */
  public function setHtml($v)
  {
    // Als de weergave van deze brief beveiligd is, gaan we de inhoud niet loggen
    if ($this->isWeergaveBeveiligd()) $v = BriefVerzondenPeer::WEERGAVE_BEVEILIGD_TEKST;
    parent::setHtml($v);
  }

  /**
   * @return bool
   */
  public function isWeergaveBeveiligd()
  {
    return $this->getBriefTemplateId()
      ? (bool) $this->getBriefTemplate()->getWeergaveBeveiligd()
      : false
    ;
  }
}

sfPropelBehavior::add('BriefVerzonden', array('updater_loggable'));
