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
}
