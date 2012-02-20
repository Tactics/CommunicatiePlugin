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
   * vervangt de placeholders in de html met de lege values
   *
   * @param string $html
   *
   * @return string
   */
  public static function clearPlaceholders($html)
  {    
    return preg_replace('(%[a-z_]*%)', '', $html);
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
   * Include een bestand en geef het resultaat terug als string
   *
   * http://php.net/manual/en/function.include.php
   * 
   * @param string $filename
   * @return string
   */
  private static function get_include_contents($filename)
  {
    if (is_file($filename))
    {
        ob_start();
        include $filename;
        $contents = ob_get_contents();
        ob_end_clean();
        return $contents;
    }

    return false;
  }
  
  /**
   * geeft bericht_html terug
   * 
   * @param BriefTemplate $briefTemplate 
   * @param boolean $emailLayout : mail of print layout
   * @param boolean $emailverzenden : mail versturen of display op scherm?
   * @param string $html
   * @param BriefLayout $briefLayout optional, default null
   * 
   * @return string $bericht_html
   */
  public static function getBerichtHtml($briefTemplate, $emailLayout, $emailverzenden, $html, $briefLayout = null)
  {    
    $layout = $briefLayout ? $briefLayout : $briefTemplate->getBriefLayout();

    if ($emailLayout)
    {
      $layout_bestand = $layout->getMailBestand();
      $layout_stylesheets = $layout->getMailStylesheets();
    }
    else
    {
      $layout_bestand = $layout->getPrintBestand();
      $layout_stylesheets = $layout->getPrintStylesheets();
    }
    
    $stylesheet_dir = sfConfig::get('sf_data_dir') . DIRECTORY_SEPARATOR . 'brieven' . DIRECTORY_SEPARATOR . 'stylesheets' . DIRECTORY_SEPARATOR;
    $layout_dir = sfConfig::get('sf_data_dir') . DIRECTORY_SEPARATOR . 'brieven' . DIRECTORY_SEPARATOR . 'layouts' . DIRECTORY_SEPARATOR;

    // Lees alle stylesheets in
    $css = '';
    foreach (explode(';', $layout_stylesheets) as $stylesheet)
    {
      $stylesheet_bestand = $stylesheet_dir . $stylesheet;
      @$stylesheet_css = self::get_include_contents($stylesheet_bestand);
      if ($stylesheet_css)
      {
        $css .= $stylesheet_css;
      }
    }    

    // Haal layout op en pas deze toe
    $html_layout = self::get_include_contents($layout_dir . $layout_bestand);

    if ($html_layout)
    {
      Misc::use_helper('Url');      
      $html = strtr($html_layout, array(
        '%stylesheet%' => $css,
        '%body%' => $html,
        '%image_dir%' => $emailverzenden ? 'cid:' : url_for('brief/showImage') . '/image/'
      ));
    }
    
    return $html;
  }
  
  /**
   * geeft bericht_body terug
   * 
   * @param string $html
   * 
   * @return string $bericht_body
   */
  public static function getBerichtBody($html)
  {    
    $startOpenBodyTag = stripos($html, '<body');
    $endOpenBodyTag = stripos($html, '>', $startOpenBodyTag);
    $endBodyTag = stripos($html, '</body>', $endOpenBodyTag);

    if (
      ($startOpenBodyTag === false)
      || ($endOpenBodyTag === false)
      || ($endBodyTag === false)
    )
    {
      throw new sfException('brief_layout "' . $layout_bestand . '" bevat geen geldige html body');
    }

    $bericht_body = substr($html, $endOpenBodyTag + 1, $endBodyTag - $endOpenBodyTag - 1);
    
    return $bericht_body;
  }
  
  /**
   * geeft bericht_head terug
   * 
   * @param string $html
   * 
   * @return string $bericht_head 
   */
  public static function getBerichtHead($html)
  {
    $startOpenHeadTag = stripos($html, '<head');
    $endOpenHeadTag = stripos($html, '>', $startOpenHeadTag);
    $endHeadTag = stripos($html, '</head>', $endOpenHeadTag);

    if (
      ($startOpenHeadTag === false)
      || ($endOpenHeadTag === false)
      || ($endHeadTag === false)
    )
    {
      throw new sfException('brief_layout "' . $layout_bestand . '" bevat geen geldige html head');
    }

    $bericht_head = substr($html, $endOpenHeadTag + 1, $endHeadTag - $endOpenHeadTag - 1);
    
    return $bericht_head;
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
   * Verschillende cultures & labels ophalen uit settings.yml
   * 
   * @return array
   */
  public static function getCultureLabelArray()
  {
    $languageArray = self::getTranslationLanguageArray();
    $cultureLabelArray = array();
    
    foreach ($languageArray as $language)
    {
      $cultureLabelArray[$language['culture']] = $language['label'];
    }
    
    return $cultureLabelArray;
  }
  
  /**
   * Default taal ophalen.
   * 
   * @return array
   */
  public static function getDefaultTranslationLanguage()
  {   
    foreach (self::getTranslationLanguageArray() as $languageArr)
    {
      if (self::isDefaultTranslationLanguage($languageArr))
      {
        return $languageArr;
      }
    }
    
    throw new sfException('Default language not found.');
  }
  
  /**
   * Default culture ophalen
   * 
   * @return string culture
   */
  public static function getDefaultCulture()
  {
    $languageArray = self::getDefaultTranslationLanguage();
    
    return $languageArray['culture'];
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
  
  /**
   * Catalogue name voor language array ophalen.
   * 
   * @param array $language
   * @return string Catalogue name
   */
  public static function getCatalogueName($language)
  {
    if (is_array($language) && !array_key_exists('culture', $language))
    {
      throw new sfException('Unknown language format.');
    }
    return 'brieven.'.self::getCulture($language);
  }

  /**
   * Catalogue name voor language array ophalen.
   * 
   * @param array $language
   * @return string Catalogue name
   */
  public static function getCulture($language)
  {
    if (is_array($language) && !array_key_exists('culture', $language))
    {
      throw new sfException('Unknown language format.');
    }
    
    return $language['culture'];
  }
 
  /**
   * Catalogue name voor language array ophalen.
   * 
   * @param array $language
   * @return string Catalogue name
   */
  public static function getLabel($language)
  {
    if (is_array($language) && !array_key_exists('label', $language))
    {
      throw new sfException('Unknown language format.');
    }
    
    return $language['label'];
  }
  
  public static function retrieveBySysteemnaam($systeemnaam, $con = null)
	{
		if ($con === null) {
			$con = Propel::getConnection(self::DATABASE_NAME);
		}

		$criteria = new Criteria(BriefTemplatePeer::DATABASE_NAME);

		$criteria->add(BriefTemplatePeer::SYSTEEMNAAM, $systeemnaam);


		$v = BriefTemplatePeer::doSelect($criteria, $con);

		return !empty($v) > 0 ? $v[0] : null;
	}
}
