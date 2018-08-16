<?php

/**
 * mail actions.
 *
 * @package    Tactics App Interfaces
 * @subpackage Mailer 
 * @author     Taco Orens
 * @version    1.0
 */

interface iTtCommunicatie extends iMailer
{
  /**
   * @return string Culture
   */
  public function getMailerCulture();
  
  /**
   * [OPTIONAL] Geeft verschillende types bestemmeling voor het object terug
   * 
   * @return array[string]string
   */
  // public function getBestemmelingTypes();

  
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
   * optional method notifyBriefVerzonden($briefVerzonden)
   * indien deze method bestaat, wordt het object waarnaar een brief wordt verzonden op de hoogte gesteld.
   * dit kan handig zijn voor facturen zodat bijvoorbeeld de datum verzonden kan gezet worden
   */
  
  /**
   * optional method getBriefAttachments()
   * attachments eigen aan het object die mee verzonden moeten worden
   * vb. afbeelding van google map waar activiteit van de factuur doorgaat
   */

  /**
   * optional method printAttachments()
   * Indien TRUE zullen de attachements mee afgedrukt worden in een aparte tab
   * Een betere oplossing zou zijn deze volledig in PDF af te drukken maar dit is nog niet voorzien
   */

  /**
   * optional method getBestemmeling()
   * geeft het object terug naarwaar deze brief/email verzonden werd
   * gebruikt om object_class/id_bestemmeling van brief_verzonden in te vullen
   */

  /**
   * optional method getDetailLink()
   * Geeft de link terug dat kan gebruikt worden om de detail te tonen van object
   * Wordt gebruikt bij het tonen van de emails die verzonden werden.
   */
}

?>