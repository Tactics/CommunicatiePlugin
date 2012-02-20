<?php

/**
 * mail actions.
 *
 * @package    Tactics App Interfaces
 * @subpackage Mailer 
 * @author     Taco Orens
 * @version    1.0
 */

interface iMailer 
{
  /**
   * [OPTIONAL] Geeft verschillende types bestemmeling voor het object terug
   * 
   * @return array[string]string
   */
  // public function getBestemmelingTypes();
  
  
  /**
   * Geeft het e-mailadres van de recipient terug
   * 
   * @return string E-mailaddress
   */  
  //public function getMailerRecipientMail($bestemmeling = null);
  public function getMailerRecipientMail();

  
  /**
   * [OPTIONAL] Geeft een array van e-mailadressen van voor in CC
   *  
   * @return array[]string E-mailaddress
   */  
  // public function getMailerRecipientCC();
  
  /**
   * [OPTIONAL] Geeft een array van e-mailadressen van voor in BCC
   *  
   * @return array[]string E-mailaddress
   */  
  // public function getMailerRecipientBCC();
  
  
  /**
   * 
   * 
   * @return boolean 
   */  
  public function getMailerWantsMail($publicity);
    
  /**
   * 
   * 
   * @return boolean 
   */  
  public function getMailerPrefersEmail();    
  
  /**
   * 
   * 
   * @return string name
   */    
  public function getMailerRecipientName();
  
  /**
   * 
   * 
   * @return string URI
   */    
  public function getMailerURI();
  
  /**
   *  
   * 
   * @return string veld
   */  
  public static function getMailerSortField();
  
  /**
   * 
   * 
   * @return array placeholders
   */    
  public static function getMailerPlaceHolders();  
  
  /**
   * (For export adressen)
   * 
   * @return array mapping
   */    
  public function getMailerAdresMapping();    
  
  /**
   * @return string Culture
   */
  public function getMailerCulture();
    
}

?>