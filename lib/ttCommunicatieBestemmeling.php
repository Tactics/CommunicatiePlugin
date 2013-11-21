<?php

class ttCommunicatieBestemmeling
{
  protected $naam = '';
  protected $email_to = '';
  protected $email_cc = array();
  protected $email_bcc = array();
  protected $adres = '';
  protected $object_id = null; // object_id van de bestemmeling
  protected $object_class = null; // object_class van de bestemmeling
  protected $prefers_email = true;
  protected $wants_publicity = true;
  protected $culture = '';
  protected $object = null; //  te verzenden object

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
   * Geeft id van de bestemmeling terug
   */
  public function getObjectId()
  {
    return $this->object_id;
  }

  /**
   * Geeft class van de bestemmeling terug
   */
  public function getObjectClass()
  {
    return $this->object_class;
  }

  /**
   * Geeft te verzenden object terug
   */
  public function getObject()
  {
    return $this->object;
  }

  /**
   * Geeft terug of de bestemmeling e-mail verkiest
   */
  public function getPrefersEmail()
  {
    return $this->prefers_email;
  }

  /**
   * Geeft terug of de bestemmeling publiciteit wil ontvangen
   */
  public function getWantsPublicity()
  {
    return $this->wants_publicity;
  }

  /**
   * Geeft de bestemmeling terug
   * 
   * @return mixed Het object dat de communicatie gaat ontvangen
   */
  public function getBestemmeling()
  {
    if (!($this->object_class && $this->object_id) || !method_exists($this->object_class . 'Peer', 'retrieveByPK'))
    {
      return null;
    }

    return call_user_func($this->object_class . 'Peer::retrieveByPK', $this->object_id);
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
    $this->object = $object;
  }
}