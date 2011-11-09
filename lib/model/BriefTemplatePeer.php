<?php

/**
 * Subclass for performing query and update operations on the 'brief_template' table.
 *
 * 
 *
 * @package plugins.ttCommunicatiePlugin.lib.model
 */ 
class BriefTemplatePeer extends BaseBriefTemplatePeer
{  
  const TYPE_FILE = 'file';
  const TYPE_DB = 'db';

  const PLACEHOLDER_PREFIX_SEPARATOR = '::';
  
  /**
   * Geeft alle templates terug (alfabetisch geordend)
   *
   * @return array[]BriefTemplate
   */
  public static function getSorted($c = null)
  {
    if (! $c)
    {
      $c = new Criteria();
    }
    
    $c->addAscendingOrderByColumn(self::NAAM);

    return self::doSelect($c);
  }

  /**
   * vervangt de placeholders in de html met de values
   *
   * @param string $html
   * @param array $values indexed by placeholder
   *
   * @return string
   */
  public static function replacePlaceholders($html, $values)
  {
    foreach ($values as $placeholder => $value)
    {
      $newValues["%$placeholder%"] = $value;      
    }
    return strtr($html, $newValues);
  }

  /**
   * Helper functie voor de implementatie van getPlaceholders() in peerclasses
   * Plaats een prefix recursief voor alle placeholders in de opgegeven array
   */
  public static function applyPrefix($prefix, $placeholders)
  {
    foreach($placeholders as $groupId => &$placeholderOfGroup)
    {
      if (is_array($placeholderOfGroup))
      {
        $placeholderOfGroup = self::applyPrefix($prefix, $placeholderOfGroup);
      }
      else
      {
        $placeholderOfGroup = $prefix . self::PLACEHOLDER_PREFIX_SEPARATOR . $placeholderOfGroup;
      }
    }

    return $placeholders;
  }

  /**
   * Helper functie voor de implementatie van getPlaceholders() in peerclasses
   * Plaats een prefix recursief voor alle placeholderskeys in de opgegeven array
   */
  public static function applyPrefixToKeys($prefix, $placeholders)
  {
    $result = array();

    foreach($placeholders as $placeHolder => &$value)
    {
      $result[$prefix . self::PLACEHOLDER_PREFIX_SEPARATOR . $placeHolder] = $value;
    }

    return $result;
  }

  /**
   * Helper functie die placeholders selecteert op basis van een prefix.
   * Het prefix wordt ook verwijderd.
   */
  public static function filterByPrefix($prefix, $placeholders)
  {
    $subset = array();

    foreach($placeholders as $p)
    {
      if (strpos($p, $prefix . self::PLACEHOLDER_PREFIX_SEPARATOR) === 0)
      {
        $subset[] = substr($p, strlen($p) + strlen(self::PLACEHOLDER_PREFIX_SEPARATOR));
      }
    }

    return $subset;
  }
}
