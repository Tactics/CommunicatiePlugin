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

  private static $targets = array();

  /**
   *  Geeft alle templates terug van een bepaald object type
   *
   * @param string $objectType
   * @param boolean $inclusief
   *
   * @return array
   */
  public static function getObjectTemplates($object_class, $inclusief_archief = false)
  {
    $c = new Criteria();
    if(!$inclusief_archief)
    {
      $c->add(BriefTemplatePeer::GEARCHIVEERD, 0);
    }

    $c->add(BriefTemplatePeer::BESTEMMELING_CLASSES, '%|' . $object_class . '|%', Criteria::LIKE);
    return self::getSorted($c);
  }

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

    // templates met systeemnaam zijn nooit uit de dropdown te kiezen
    $c->add(self::SYSTEEMNAAM, null, Criteria::ISNULL);

    // categories enabled?
    if (sfConfig::get('sf_communicatie_enable_categories', false))
    {
      $user = sfContext::getInstance()->getUser();
      $c->add(self::CATEGORIE, $user->getTtCommunicatieCategory(), Criteria::EQUAL);
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
    // 2x vervangen, eerste keer kunnen placeholders vervangen worden door opnieuw placeholders
    $html = strtr($html, $newValues);
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
    return preg_replace('(%[A-Za-z0-9_:]+%)', '', $html);
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
   *
   * @return array
   */
  public static function filterByPrefix($prefix, $placeholders)
  {
    $subset = array();

    foreach($placeholders as $p)
    {
      if (strpos($p, $prefix . self::PLACEHOLDER_PREFIX_SEPARATOR) === 0)
      {
        $subset[] = substr($p, strlen($prefix) + strlen(self::PLACEHOLDER_PREFIX_SEPARATOR));
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
        '%image_dir%' => $emailverzenden ? 'cid:' : url_for('ttCommunicatie/showImage') . '/image/'
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

    return is_array($i18n['languages']) && (count($i18n['languages']) > 1);
  }

  /**
   * Haalt de verschillende talen op.
   *
   * @return array
   */
  public static function getTranslationLanguageArray()
  {
    $i18n = sfConfig::get('sf_communicatie_i18n');

    // default nl_BE
    if (! $i18n)
    {
      $i18n = array('languages' => array(
        array(
          'culture' => 'nl_BE',
          'label' => 'Nederlands',
          'default' => true
         )
      ));
    }


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

  /**
   *
   * @param string $systeemnaam
   * @param type $con
   * @return BriefTemplate
   */
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

  /**
   * geeft de culture terug waarin de brief/email verzonden moet worden
   *
   * @param ttCommunicatieBestemmeling $bestemmeling
   */
  public static function calculateCulture($bestemmeling)
  {
    $culture = $bestemmeling->getCulture();
    $cultures = self::getCultureLabelArray();

    if (! $culture)
    {
      $culture = sfContext::getInstance()->getUser()->getCulture();
    }

    if (! array_key_exists($culture, $cultures))
    {
      $culture = self::getDefaultCulture();
    }

    return $culture;
  }

  /**
   * verwerkt de ifstaments van de html
   *
   * @param string $html
   * @param ttCommunicatieBestemmeling $bestemmeling
   * @param bool $email
   * @param array $otherPlaceholders For example systeemplaceholders
   * @return string The html with parsed if statements
   */
  public static function parseIfStatements($html, $bestemmeling, $email = false, $otherPlaceholders = array())
  {
    $defaultPlaceholders = array_merge(self::getDefaultPlaceholders($bestemmeling, $email), $otherPlaceholders);

    while (preg_match_all('/{%\s*if\s+[^{]*\s*({% endif %})/', $html, $matches, PREG_OFFSET_CAPTURE))
    {
      $changeInOffset = 0;
      $ifBlocks = $matches[0];

      foreach ($ifBlocks as $index => $ifBlock)
      {
        if (preg_match('/^{%\s*if\s+([^{]*)\s+%}/', $ifBlock[0], $condition))
        {
          // special case for velden waar meerdere antwoorden mogelijk zijn
          if (preg_match("/(%[^%]+%)[^\s]+\s+has_selected\s+['\"]([^']+)['\"]$/", $condition[1], $matches2))
          {
            $placeholderValues = self::getObjectPlaceholderValues($condition[1], $bestemmeling);
            $placeholderValues = explode("\n", array_shift($placeholderValues));

            $condition[1] = in_array($matches2[2], $placeholderValues);
          }
          else
          {
            // nodige placeholders uit template halen
            $condition[1] = self::replacePlaceholdersFromObject($condition[1], $bestemmeling, $email);
          }

          // condition evalueren
          if (eval("return " . html_entity_decode($condition[1]) . ";"))
          {
            // {% endif %} er eerst uitknippen, want dat veranderd de offset van de if niet
            $offsetEndif = $matches[1][$index][1] - $changeInOffset;
            $lengthEndif = strlen($matches[1][$index][0]);
            $html = substr_replace($html, '', $offsetEndif, $lengthEndif);

            // {% if ... %} eruit knippen
            $offsetIf = $ifBlock[1] - $changeInOffset;
            $lengthIf = strlen($condition[0]);
            $html = substr_replace($html, '', $offsetIf, $lengthIf);

            // change in offset bijhouden
            $changeInOffset += $lengthIf + $lengthEndif;
          }
          else
          {
            // heel de ifblock uit de body knippen
            $offsetIfBlock = $ifBlock[1] - $changeInOffset;
            $lengthIfBlock = strlen($ifBlock[0]);
            $html = substr_replace($html, '', $offsetIfBlock, $lengthIfBlock);

            // change in offset bijhouden
            $changeInOffset += $lengthIfBlock;
          }
        }
      }
    }
    
    return $html;
  }

  /**
   * verwerkt de ifstaments van de html
   *
   * @param string $html
   * @param ttCommunicatieBestemmeling $bestemmeling
   * @param bool $email
   * @param array $otherPlaceholders For example systeemplaceholders
   * @return string The html with parsed if statements
   */
  public static function parseForeachStatements($html, $bestemmeling, $email = false, $otherPlaceholders = array())
  {
    $object = $bestemmeling->getObject();
    $defaultPlaceholders = array_merge(self::getDefaultPlaceholders($bestemmeling, $email), $otherPlaceholders);
    while (preg_match_all('/{%\s*foreach\s+[^{]*({% endforeach %})/', $html, $matches, PREG_OFFSET_CAPTURE))
    {
      $changeInOffset = 0;
      $foreachBlocks = $matches[0];
      foreach ($foreachBlocks as $index => $foreachBlock)
      {
        $content = $foreachBlock[0];
        $offset = $foreachBlock[1];
        if (preg_match('/^{%\s*foreach\s+([^{]*)\s+%}([^{]+){% endforeach %}/', $content, $result))
        {
          //$result[1] is the collection field
          //$result[2] is the html in the foreach statement
          list($formSysName, $veldSysName) = explode('][', trim(substr($result[1], 10), ']%'));
          if (!$formSysName || !$veldSysName || !($antwoord = $object->retrieveAntwoordBySysteemnamen($formSysName, $veldSysName)))
          {
            // clear the foreach
            $html = substr_replace($html, '', $offset + $changeInOffset, strlen($content));
            $changeInOffset += 0 - strlen($content);
            continue;
          }

          $nbrOfCollectionItems = $antwoord->get();
          $foreachHtml = '';
          for ($i=0; $i < $nbrOfCollectionItems; $i++)
          {
            $foreachHtml .= self::replacePlaceholdersFromObject(str_replace('[]', "[$i]", $result[2]), $bestemmeling, $email);
          }
          $html = substr_replace($html, $foreachHtml, $offset + $changeInOffset, strlen($content));
          $changeInOffset += strlen($foreachHtml) - strlen($content);
        }
      }
    }

    return $html;
  }

  /**
   * replaced de placeholders van de culture brief
   *
   * @param array $cultureBrieven
   * @param ttCommunicatieBestemmeling $bestemmeling
   * @param array $defaultPlaceholders
   * @return array The $cultureBrieven with replaced placeholders for the object culture
   */
  public static function replacePlaceholdersFromCultureBrieven($cultureBrieven, $bestemmeling, $default_placeholders = array())
  {
    $culture = self::calculateCulture($bestemmeling);

    $placeholders = array_merge(
      $default_placeholders,
      self::getObjectPlaceholderValues($cultureBrieven[$culture]['onderwerp'] . $cultureBrieven[$culture]['body'], $bestemmeling)
    );

    // eerst onderwerp placeholders vervangen omdat onderwerp zelf een placeholder is in de body
    $placeholders['onderwerp'] = self::replacePlaceholders($cultureBrieven[$culture]['onderwerp'], $placeholders);
    $cultureBrieven[$culture]['onderwerp'] = $placeholders['onderwerp'];
    $cultureBrieven[$culture]['body'] = self::replacePlaceholders($cultureBrieven[$culture]['body'], $placeholders);

    return $cultureBrieven;
  }

  /**
   * vervangt de placeholders in de html met de values
   *
   * @param string $html
   * @param ttCommunicatieBestemmeling $bestemmeling
   * @param bool $email
   * @return string The html with replaced placeholders
   */
  public static function replacePlaceholdersFromObject($html, $bestemmeling, $email = false)
  {
    $defaultPlaceholders = self::getDefaultPlaceholders($bestemmeling, $email);

    $placeholders = array_merge(
      self::getObjectPlaceholderValues($html, $bestemmeling),
      $defaultPlaceholders
    );

    return self::replacePlaceholders($html, $placeholders);
  }

  /**
   * geeft de gebruikte placeholder values terug van gegeven object
   *
   * @param string $html
   * @param ttCommunicatieBestemmeling $bestemmeling
   * @return array[placeholder] => placeholder_value
   */
  private static function getObjectPlaceholderValues($html, $bestemmeling)
  {
    $object = $bestemmeling->getObject();
    if (! self::isTarget($object))
    {
      return array();
    }

    $usedPlaceholders = array();
    if (preg_match_all('/\%([A-Za-z0-9_:\[\]]+)\%/', $html, $placeholderMatches)) {
        $usedPlaceholders = $placeholderMatches[1];
    }

    $culture = self::calculateCulture($bestemmeling);

    return $object->fillPlaceholders($usedPlaceholders, $culture);
  }

  /**
   * Geeft de default placeholers terug
   *
   * @param ttCommunicatieBestemmeling $bestemmeling
   * @param ttCommunicatiebestemmeling $bestemmeling
   * @param bool $generalPlaceholders met bv handtekeningen placeholders
   * @return array default placeholders
   */
  public static function getDefaultPlaceholders($bestemmeling = null, $email_verzenden = false, $via_email = false, $generalPlaceholders = false)
  {
    if (($via_email && $bestemmeling && ($bestemmeling->getObjectClass() == 'Persoon') && $bestemmeling->getWantsPublicity())
        && ($uitschrijvenInfo = sfConfig::get('sf_communicatie_uitschrijven', null))
       )
    {
      $persoon = $bestemmeling->getBestemmeling();      
      $url = str_replace(array('%uuid%', '%email%'), array($persoon->getUuid(), urlencode($persoon->getEmail())), $uitschrijvenInfo['url']);
      if (false !== preg_match('/(?P<begin>.*)(?P<placeholder>%.+%)(?P<einde>.*)/', $uitschrijvenInfo['tekst'], $result))
      {
        $uitschrijfLink = $result['begin'] . "<a href=\"$url\">" . trim($result['placeholder'], '%') . "</a>" . $result['einde'];
      }      
    }

    Misc::use_helper('Url');
    $vandaag = new myDate();
    $defaultPlaceholders = array(
      'datum' => $vandaag->format(),
      'datum_d_MMMM_yyyy' => $vandaag->format('d MMMM yyyy'),
      'image_dir' => $email_verzenden ? 'cid:' : url_for('ttCommunicatie/showImage') . '/image/',
      'pagebreak' => '<div style="page-break-before: always; margin-top: 80px;"></div>',
      'uitschrijven' => isset($uitschrijfLink) ? $uitschrijfLink : ''
    );

    if ($bestemmeling)
    {
      $defaultPlaceholders = array_merge(
        $defaultPlaceholders,
        array('bestemmeling_adres' => nl2br($bestemmeling->getAdres()))
      );
    }

    if ($generalPlaceholders)
    {
      if (@class_exists('Placeholder'))
      {
        $placeholder = new Placeholder();
        $defaultPlaceholders = array_merge($defaultPlaceholders, $placeholder->fillPlaceholders(null, BriefTemplatePeer::getDefaultCulture()));
      }
    }

    return $defaultPlaceholders;
  }

  /**
   * geeft terug of object een ttCommunicatie target is
   *
   * @param type $object
   * @return typegeeft ter
   */
  private static function isTarget($object)
  {
    if (empty(self::$targets))
    {
      foreach (sfConfig::get('sf_communicatie_targets') as $targetInfo)
      {
        self::$targets[] = $targetInfo['class'];
      }
    }

    return in_array(get_class($object), self::$targets);
  }

}
