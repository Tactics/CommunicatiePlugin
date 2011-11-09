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
}
