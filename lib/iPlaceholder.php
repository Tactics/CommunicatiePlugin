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
}
