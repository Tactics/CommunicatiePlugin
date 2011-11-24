<?php

interface iPlaceholder
{
  /**
   * @return array : array[placeholder] = value
   */
  public function fillPlaceholders();
  
  /**
   * @return array placeholders voor klasse.
   */  
  public static function getPlaceholders(); 
  
  /**
   * @return array array('brief_layout_id' => id, 'brief_template_id' => id)
   */
  public function getLayoutEnTemplateId();
  
  /**
   * optional method notifyBriefVerzonden($briefVerzonden)
   * indien deze method bestaat, wordt het object waarnaar een brief wordt verzonden op de hoogte gesteld.
   * dit kan handig zijn voor facturen zodat bijvoorbeeld de datum verzonden kan gezet worden
   */
}
