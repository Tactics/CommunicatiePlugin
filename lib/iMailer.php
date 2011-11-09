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
   * 
   * 
   * @return string E-mailaddress
   */  
  public function getMailerRecipientMail();
  
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
    
}

?>