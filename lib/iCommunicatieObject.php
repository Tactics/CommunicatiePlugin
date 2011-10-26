<?php

interface iCommunicatieObject
{
  /**
   * @return string e-mailadres van bestemmeling.
   */
  public function getMailerRecipientMail();
  
  /**
   * @return string naam van bestemmeling.
   */
  public function getMailerRecipientName();
  
  /**
   * @return array adresgegevens van bestemmeling.
   */
  public function getMailerAdresMapping();
}
