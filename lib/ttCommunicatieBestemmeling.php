<?php

class ttCommunicatieBestemmeling
{
  protected $naam = '';
  protected $email_to = '';
  protected $email_cc = array();
  protected $email_bcc = array();
  protected $adres = '';
  protected $bestemmeling; // bestemmeling object
  protected $bestemmeling_id = null; // object_id van de bestemmeling
  protected $bestemmeling_class = null; // object_class van de bestemmeling
  protected $prefers_email = true;
  protected $wants_publicity = true;
  protected $culture = '';
  protected $object = null; //  te verzenden object
  protected $object_id = null; // object_id van het object
  protected $object_class = null; // object_class van het object
  protected $placeholders = array(); // placeholders voor deze bestemmeling
  
  public function __sleep()
  {
    $properties = get_class_vars(__CLASS__);
    unset($properties['object']);
    unset($properties['bestemmeling']);
    
    return array_keys($properties);
  }
  
  /**
   * @return array
   */
  public function getPlaceholders()
  {
    return $this->placeholders;
  }
  
  /**
   * @param array $placeholders
   */
  public function setPlaceholders($placeholders)
  {
    $this->placeholders = $placeholders;
  }
  
  /**
   * Geeft e-mail adres van de bestemmeling terug
   */
  public function getNaam()
  {
    return $this->naam;
  }
  
  /**
   * Geeft e-mail to adres van de bestemmeling terug
   */
  public function getEmailTo()
  {
    return $this->email_to;
  }

  /**
   * Geeft e-mail cc adressen van de bestemmeling terug
   * 
   * @param boolean $implode return cc addresses as array or string
   * @return mixed
   */
  public function getEmailCc($implode = false)
  {
    return $implode ? implode(';', $this->email_cc) : $this->email_cc;
  }

  /**
   * Geeft e-mail bcc adres van de bestemmeling terug
   *
   * @param boolean $implode return cc addresses as array or string
   * @return mixed
   */
  public function getEmailBcc($implode = false)
  {
    return $implode ? implode(';', $this->email_bcc) : $this->email_bcc;
  }

  /**
   * Geeft post adres van de bestemmeling terug
   */
  public function getAdres()
  {
    return $this->adres;
  }

  /**
   * Geeft culture van de bestemmeling terug
   */
  public function getCulture()
  {
    return $this->culture;
  }
  
  /**
   * Geeft de id van de bestemmeling terug
   * @return int
   */
  public function getBestemmelingId()
  {
    return $this->bestemmeling_id;
  }
  
  /**
   * Geeft de class van de bestemmeling terug
   * @return string
   */
  public function getBestemmelingClass()
  {
    return $this->bestemmeling_class;
  }

  /**
   * Geeft id van het te verzenden object terug
   * @retrun int
   */
  public function getObjectId()
  {
    return $this->object_id;
  }

  /**
   * Geeft class van het te verzenden object terug
   * @return string
   */
  public function getObjectClass()
  {
    return $this->object_class;
  }

  /**
   * Geeft te verzenden object terug
   * @return mixed|null
   */
  public function getObject()
  {
    if ($this->object === null && $this->object_class && $this->object_id)
    {
      $this->object = call_user_func_array($this->object_class . 'Peer::retrieveByPk', is_array($this->object_id) ? $this->object_id : array($this->object_id));
    }
    
    return $this->object;
  }
  
  /**
   * Geeft de bestemmeling terug
   * @return mixed|null
   */
  public function getBestemmeling()
  {
    if ($this->bestemmeling === null && $this->bestemmeling_class && $this->bestemmeling_id)
    {
      $this->bestemmeling = call_user_func_array($this->bestemmeling_class . 'Peer::retrieveByPk', is_array($this->bestemmeling_id) ? $this->bestemmeling_id : array($this->bestemmeling_id));
    }
    
    return $this->bestemmeling;
  }

  /**
   * Geeft terug of de bestemmeling e-mail verkiest
   * @return bool
   */
  public function getPrefersEmail()
  {
    return $this->prefers_email;
  }

  /**
   * Geeft terug of de bestemmeling publiciteit wil ontvangen
   * @return bool
   */
  public function getWantsPublicity()
  {
    return $this->wants_publicity;
  }
  
  /**
   * Zet naam van de bestemmeling
   *
   * @param string $naam
   */
  public function setNaam($naam)
  {
    $this->naam = $naam;
  }

  /**
   * Zet e-mail to adres van de bestemmeling
   *
   * @param string $email_to
   */
  public function setEmailTo($email_to)
  {
    $this->email_to = $email_to;
  }

  /**
   * Zet e-mail cc adres van de bestemmeling
   *
   * @param array $email_cc
   */
  public function setEmailCc($email_cc)
  {    
    $this->email_cc = $email_cc;
  }

  /**
   * Zet e-mail bcc adres van de bestemmeling
   *
   * @param array $email_bcc
   */
  public function setEmailBcc($email_bcc)
  {
    $this->email_bcc = $email_bcc;
  }

  /**
   * Zet post adres van de bestemmeling
   *
   * @param string $adres
   */
  public function setAdres($adres)
  {
    $this->adres = $adres;
  }

  /**
   * Zet id van de bestemmeling
   *
   * @param int $object_id
   */
  public function setObjectId($object_id)
  {
    $this->object_id = $object_id;
  }

  /**
   * Zet class van de bestemmeling
   *
   * @param string $object_class
   */
  public function setObjectClass($object_class)
  {
    $this->object_class = $object_class;
  }

  /**
   * zet de voorkeur ivm e-mail van de bestemmeling
   *
   * @param boolean $prefers_email
   */
  public function setPrefersEmail($prefers_email)
  {
    $this->prefers_email = $prefers_email;
  }

  /**
   * zet de voorkeur ivm ontvangen van publiciteit
   *
   * @param boolean $wants_publicity
   */
  public function setWantsPublicity($wants_publicity)
  {
    $this->wants_publicity = $wants_publicity;
  }

  /**
   * zet de culture van de bestemmeling
   *
   * @param string $culture
   */
  public function setCulture($culture)
  {
    $this->culture = $culture;
  }

  /**
   * zet het te verzenden object
   *
   * @param mixed $object
   */
  public function setObject($object)
  {
    $this->object_class = get_class($object);
    $this->object_id = $object->getPrimaryKey();
    $this->object = $object;
  }
  
  public function setBestemmeling($bestemmeling)
  {
    $this->bestemmeling_class = get_class($bestemmeling);
    $this->bestemmeling_id = $bestemmeling->getPrimaryKey();
    $this->bestemmeling = $bestemmeling;
  }
}