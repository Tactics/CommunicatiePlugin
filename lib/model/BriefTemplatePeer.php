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
  
  
  /**
   * @return bool
   */
  public static function isVertaalbaar()
  {
    $i18n = sfConfig::get('sf_communicatie_i18n');
    
    return is_array($i18n['languages']) && (count($i18n['languages']) > 0);
  }
  
  /**
   * Haalt de verschillende talen op.
   * 
   * @return array 
   */
  public static function getTranslationLanguageArray()
  {
    $i18n = sfConfig::get('sf_communicatie_i18n');
    return $i18n['languages'];
  }
  
  /**
   * Default taal ophalen.
   * 
   * @return array
   */
  public static function getDefaultTranslationLanguage()
  {   
    foreach ($this->getTranslationLanguageArray() as $languageArr)
    {
      if ($this->isDefaultLanguage($languageArr))
      {
        return $languageArr;
      }
    }
    
    throw new sfException('Default language not found.');
  }
  
  /**
   * Controleren of taal default is.
   * 
   * @param array language array ('culture' => ..., 'label' => ..., 'default' => ...)
   * @return bool
   */
  public static function isDefaultTranslationLanguage($languageArray)
  {
    return array_key_exists('default', $languageArray) && ($languageArray['default'] == true);
  }
}
