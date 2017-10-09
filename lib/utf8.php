<?php

/**
 * Created by PhpStorm.
 * User: Michiel
 * Date: 09/10/2017
 * Time: 14:55
 */
class utf8
{

  /**
   * @param $array
   * @return array
   */
  public static function thisArray($array)
  {
    $utf8Array = [];
    foreach ($array as $key => $value)
    {
      if (is_array($value))
      {
        $encoded = self::thisArray($value);
      } elseif (is_string($value))
      {
        $encoded = utf8_encode($value);
      } else
      {
        $encoded = $value;
      }
      $utf8Array[$key] = $encoded;
    }

    return $utf8Array;
  }

}