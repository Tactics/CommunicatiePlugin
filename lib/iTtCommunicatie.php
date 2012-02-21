<?php

/**
 * mail actions.
 *
 * @package    Tactics App Interfaces
 * @subpackage Mailer 
 * @author     Taco Orens
 * @version    1.0
 */

interface ittCommunicatie 
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
  
  /**
   * @return array : array[placeholder] = value
   */
  public function fillPlaceholders($placeHoldersToFill, $culture);
  
  /**
   * @return array placeholders voor klasse.
   */  
  public static function getPlaceholders(); 
  
  /**
   * @return array array('brief_layout_id' => id, 'brief_template_id' => id)
   */
  public function getLayoutEnTemplateId();
  
  /**
   * geeft het adres terug naar waar de ttCommunicatiePlugin een brief voor deze aanvraag zal verzenden 
   * 
   * @return string adres
   */
  public function getAdres();
  
  /**
   * optional method notifyBriefVerzonden($briefVerzonden, $briefTemplateId)
   * indien deze method bestaat, wordt het object waarnaar een brief wordt verzonden op de hoogte gesteld.
   * dit kan handig zijn voor facturen zodat bijvoorbeeld de datum verzonden kan gezet worden
   */
  
  /**
   * optional method getBriefAttachments()
   * attachments eigen aan het object die mee verzonden moeten worden
   * vb. afbeelding van google map waar activiteit van de factuur doorgaat
   */
    
}

?>