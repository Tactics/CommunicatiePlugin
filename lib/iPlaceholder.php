<?php

interface iPlaceholder
{
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
