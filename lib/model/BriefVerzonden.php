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
  private $object;
  private $bestemmeling;
  
  /**
   * Geeft de instantie weer van het object gekoppeld aan de verzonden brief
   *
   * @return mixed|null
   */
  public function getObject()
  {
    if ($this->object === null && $this->object_class && $this->object_id)
    {
      $this->object = call_user_func($this->object_class . 'Peer::retrieveByPk', $this->object_id);
    }
  
    return $this->object;
  }
  
  /**
   * Geeft de instantie weer van de bestemmeling van de verzonden brief
   *
   * @return mixed|null
   */
  public function getBestemmeling()
  {
    if ($this->bestemmeling === null && $this->object_class_bestemmeling && $this->object_id_bestemmeling)
    {
      $this->bestemmeling = call_user_func($this->object_class_bestemmeling . 'Peer::retrieveByPk', $this->object_id_bestemmeling);
    }
    
    return $this->bestemmeling;
  }
  
  /**
   * Zet object_id en object_class
   * @param Persistent $object
   */
  public function setObject(Persistent $object)
  {
    $this->object = $object;
    $this->setObjectClass($object ? get_class($object) : null);
    $this->setObjectId($object ? $object->getPrimaryKey() : null);
  }
  
  /**
   * Zet object_class_bestemmeling en object_id_bestemmeling
   * @param Persistent $object
   */
  public function setBestemmeling(Persistent $bestemmeling)
  {
    $this->bestemmeling = $bestemmeling;
    $this->setObjectClassBestemmeling($bestemmeling ? get_class($bestemmeling) : null);
    $this->setObjectIdBestemmeling($bestemmeling ? $bestemmeling->getPrimaryKey() : null);
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
