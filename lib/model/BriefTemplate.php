<?php

/**
 * Subclass for representing a row from the 'brief_template' table.
 *
 * 
 *
 * @package plugins.ttCommunicatiePlugin.lib.model
 */ 
class BriefTemplate extends BaseBriefTemplate implements iAutocomplete
{
  /**
   * geeft de string weergave van dit object terug
   */
  public function __toString()
  {
    $returnString = '';
    $returnString .= $this->getId() ? '(' . $this->getId() . ')' : '';
    $returnString .= $this->getNaam() ? ' ' . $this->getNaam() : '';
    return $returnString;
  }
  
  /**
   * Een BriefTemplate is een systeemtemplate wanneer parameter systeemnaam not null is
   * 
   * @return bool
   */
  public function isSysteemtemplate()
  {
    return $this->getSysteemnaam() != null;
  }

  /**
   * controleer of deze template reeds naar het gegeven object_class/id gestuurd is
   *
   * @param string $object_class
   * @param int $object_id
   *
   * @return boolean
   */
  public function ReedsVerstuurdNaar($object_class, $object_id)
  {
    $c = new Criteria();
    $c->add(BriefVerzondenPeer::OBJECT_CLASS, $object_class);
    $c->add(BriefVerzondenPeer::OBJECT_ID, $object_id);
    $c->add(BriefVerzondenPeer::BRIEF_TEMPLATE_ID, $this->getId());
    $c->add(BriefVerzondenPeer::STATUS, BriefVerzondenPeer::STATUS_VERZONDEN);

    return BriefVerzondenPeer::doCount($c) > 0;
  }

  /**
   * Zet de verschillende mogelijke classes die als bestemmelingen kunnen dienen
   *
   * @param array[]string $bestemmelingen
   */
  public function setBestemmelingArray($bestemmelingen)
  {
    $this->setBestemmelingClasses('|' . implode('|', $bestemmelingen) . '|');
  }

  /**
   * Geeft de verschillende classes die als bestemmeling kunnen dienen
   *
   * @return array[]string
   */
  public function getBestemmelingArray()
  {
    $b = $this->getBestemmelingClasses();

    return $b ? explode('|', trim($b, '|')) : array();
  }
  /**

   * Geeft de verschillende placeholders eigen aan de systeemtemplate
   *
   * @return array[]string
   */
  public function getSysteemplaceholdersArray()
  {
    $placeholders = $this->getSysteemplaceholders();

    return $placeholders ? explode('|', trim($placeholders, '|')) : array();
  }

  /**
   * Haalt de parentnode op uit de store (tabel dms_store), aangemaakt in punt 4.
   */
  public function getDmsStorageParentFolder($autoCreate = true)
  {
    return DmsStorePeer::retrieveByName('brieftemplates');
  }

  /**
   * subfolder naam (id van de sollicitatie in dit geval)
   */
  public function getDmsStorageFolderName()
  {
    return $this->getId();
  }
  
  /**
   * Html source ophalen
   * 
   * @param $language
   * @return string Source
   */
  public function getHtmlSource($culture)
  {   
    return 'brieftemplate_' . $this->getId() . '_html_' . $culture;    
  }
  
  /**
   * Onderwerp source ophalen
   * 
   * @param $language
   * @return string Source
   */
  public function getOnderwerpSource($culture)
  {
    return 'brieftemplate_' . $this->getId() . '_onderwerp_' . $culture;    
  }
  
  /**
   * Vertaling ophalen a.d.h.v source en taal
   * 
   * @param  string source
   * @return string vertaling
   */
  public function getVertaling($source)
  {
    $c = new Criteria();
    $c->add(TransUnitPeer::SOURCE, $source);
    $transUnit = TransUnitPeer::doSelectOne($c);
    
    return $transUnit ? $transUnit->getTarget() : '';
  }
  
  /**
   * Onderwerp ophalen a.d.h.v culture.
   * 
   * @param string $culture
   */
  public function getTranslatedOnderwerp($culture)
  {
    if ($culture === BriefTemplatePeer::getDefaultCulture())
    {
      $onderwerp = $this->getOnderwerp();
    }
    else
    {
      $catalogueName = 'brieven.'.$culture;
      $catalogue     = CataloguePeer::retrieveByName($catalogueName);
      if (! $catalogue)
      {
        throw new sfException('Catalogue not found: ' . $catalogueName);
      }

      $c = new Criteria();
      $c->add(TransUnitPeer::CATALOGUE_ID, $catalogue->getId());
      $c->add(TransUnitPeer::SOURCE, $this->getOnderwerpSource($culture));
      $transUnit = TransUnitPeer::doSelectOne($c);
      if (! $transUnit)
      {
        throw new sfException('TransUnit not found');
      }
      
      $onderwerp = $transUnit->getTarget();
    }  
    
    return $onderwerp;
  }
  
  /**
   * Html ophalen a.d.h.v culture.
   * 
   * @param string $culture
   */
  public function getTranslatedHtml($culture)
  {
    if ($culture === BriefTemplatePeer::getDefaultCulture())
    {
      $html = $this->getHtml();
    }
    else
    {
      $catalogueName = 'brieven.'.$culture;
      $catalogue     = CataloguePeer::retrieveByName($catalogueName);
      if (! $catalogue)
      {
        throw new sfException('Catalogue not found');
      }

      $c = new Criteria();
      $c->add(TransUnitPeer::CATALOGUE_ID, $catalogue->getId());
      $c->add(TransUnitPeer::SOURCE, $this->getHtmlSource($culture));
      $transUnit = TransUnitPeer::doSelectOne($c);
      if (! $transUnit)
      {
        throw new sfException('TransUnit not found');
      }
      
      $html = $transUnit->getTarget();
    }  
    
    return $html;
  }
  
  
  /**
   * Mail versturen naar object
   *
   * @param mixed $object
   * @param ttCommunicatieBestemmeling $bestemmeling
   * @param array $options
   * @return int The number of successful recipients
   * @throws Swift_ConnectionException If sending fails for any reason.
   */
  public function sendMailToObject($object, ttCommunicatieBestemmeling $bestemmeling, $options = array())
  {
    $systeemvalues = isset($options['systeemvalues']) ? $options['systeemvalues'] : array();
    
    // sommige brieven mogen slechts eenmalig naar een object_class/id gestuurd worden
    if ($this->getEenmaligVersturen() && $this->reedsVerstuurdNaar($cName, $object->getId()))
    {
      throw new sfException("Mail already sent.");
    }
    
    // default placeholders die in layout gebruikt kunnen worden
    $defaultPlaceholders = BriefTemplatePeer::getDefaultPlaceholders($bestemmeling, true);
    
    $culture    = $bestemmeling->getCulture();
    $values     = $object->fillPlaceholders(null, $culture);
    $values     = array_merge($defaultPlaceholders, $values, $systeemvalues);
    $onderwerp   = BriefTemplatePeer::replacePlaceholders($this->getTranslatedOnderwerp($culture), $values);
    $values['onderwerp'] = $onderwerp;
    
    $email       = $bestemmeling->getEmailTo();
    $html        = $this->getTranslatedHtml($culture);
    $headAndBody = $this->getBriefLayout()->getHeadAndBody('mail', $culture, $html, true);
    
    $brief = $headAndBody['head'] . $headAndBody['body'];
    $brief = BriefTemplatePeer::parseForeachStatements($brief, $bestemmeling, true, $systeemvalues);
    $brief = BriefTemplatePeer::parseIfStatements($brief, $bestemmeling, true, $systeemvalues);
    $brief = BriefTemplatePeer::replacePlaceholders($brief, $values);
    
    // Mail versturen
    $mailSent = BerichtPeer::verstuurEmail($email, $brief, array(
      'onderwerp' => $onderwerp,
      'skip_template' => true,
      'cc' => $bestemmeling->getEmailCc(),
      'bcc' => $bestemmeling->getEmailBcc()
    ));

    // Mail loggen
    $briefVerzonden = new BriefVerzonden();
    $briefVerzonden->setObjectClass(get_class($object));
    $briefVerzonden->setObjectId($object->getId());
    $briefVerzonden->setObjectClassBestemmeling($bestemmeling->getObjectClass());
    $briefVerzonden->setObjectIdBestemmeling($bestemmeling->getObjectId());    
    $briefVerzonden->setBriefTemplateId($this->getId());
    $briefVerzonden->setMedium(BriefverzondenPeer::MEDIUM_MAIL);
    $briefVerzonden->setAdres($email);
    $briefVerzonden->setOnderwerp($onderwerp);
    $briefVerzonden->setCulture($culture);
    $briefVerzonden->setHtml($brief);
    $briefVerzonden->save();
    
    return $mailSent;
  }
  
  /**
   * geeft een array terug van htmls, geindexeerd op culture
   * 
   * @return array[culture] = html
   */
  public function getHtmlCultureArr()
  {
    $cultures = BriefTemplatePeer::getCultureLabelArray();
    $defaultCulture = BriefTemplatePeer::getDefaultCulture();
    
    $html = array();    
    foreach ($cultures as $culture => $label)
    {     
      if ($culture === $defaultCulture)
      {
        $html[$culture] = $this->getHtml();        
      }
      else
      {
        $html[$culture] = $this->getVertaling($this->getHtmlSource($culture));        
      }
    }
    
    return $html;
  }
  
  /**
   * geeft een array terug van htmls, geindexeerd op culture
   * 
   * @return array[culture] = html
   */
  public function getOnderwerpCultureArr()
  {
    $cultures = BriefTemplatePeer::getCultureLabelArray();
    $defaultCulture = BriefTemplatePeer::getDefaultCulture();
    
    $onderwerp = array();    
    foreach ($cultures as $culture => $label)
    {     
      if ($culture === $defaultCulture)
      {
        $onderwerp[$culture] = $this->getOnderwerp();        
      }
      else
      {
        $onderwerp[$culture] = $this->getVertaling($this->getOnderwerpSource($culture));        
      }
    }
    
    return $onderwerp;
  }
  
  /**
   * Een briefTemplate is verwijderbaar wanneer:
   * - er geen BriefVerzonden objecten aan gekoppeld zijn. 
   * - het geen systeemtemplate is
   * 
   * @return boolean
   */
  public function isVerwijderbaar()
  {
    return ($this->countBriefVerzondens() === 0) && (! $this->isSysteemtemplate());
  }
  
  /**
   * geeft een array terug met attachments uit
   * a) de template
   * b) on-the-fly toegevoegd
   * 
   * @param sfWebRequest $request
   * 
   * @return array
   */
  public function getAttachments($request = null)
  {
    $attachments = array();
    if ($this->countBriefBijlages())
    {
      foreach ($this->getBriefBijlages() as $briefBijlage)
      {
        $node = DmsNodePeer::retrieveByPk($briefBijlage->getBijlageNodeId());

        if (! $node)
        {
          continue;
        }
        
        if (function_exists('sys_get_temp_dir'))
        {
          $tmpFile = tempnam(sys_get_temp_dir(), 'brief_bijlage');
        }
        else
        {
          $tmpFile = tempnam('/tmp', 'brief_bijlage');
        }
        
        $node->saveToFile($tmpFile);

        $attachments[$node->getName()] = $tmpFile;
      }
    }

    return $attachments;
  }

  /**
   * Geef het criterion terug voor het object (BriefTemplate)
   * @param \Criteria $criteria
   * @param string $keyword
   * @return Criterion
   */
  public static function getAutocompleteCriterion(&$criteria, $keyword)
  {
    $sfUser = sfContext::getInstance()->getUser();
    $bedrijfCton = $criteria->getNewCriterion(BriefTemplatePeer::CATEGORIE, $sfUser->getBedrijfId());
    $bedrijfCton->addOr($criteria->getNewCriterion(BriefTemplatePeer::CATEGORIE, NULL, Criteria::ISNULL));
    $criteria->add($bedrijfCton);
    $criteria->add(BriefTemplatePeer::BESTEMMELING_CLASSES, '%|Dossier|%', Criteria::LIKE);

    $cton1 = $criteria->getNewCriterion(BriefTemplatePeer::NAAM, '%' . $keyword . '%', Criteria::LIKE);

    if (is_numeric($keyword))
    {
      $cton1->addOr($criteria->getNewCriterion(BriefTemplatePeer::ID, intval($keyword)));
    }

    $cton1->addOr($criteria->getNewCriterion(BriefTemplatePeer::ONDERWERP, '%' . $keyword . '%', Criteria::LIKE));

    // Indien tonen van archief aan, laten we ook de niet actief personen zien
    if (! sfContext::getInstance()->getUser()->getAttribute('bekijk_archief', false))
    {
      $cton2 = $criteria->getNewCriterion(BriefTemplatePeer::GEARCHIVEERD, 0);
      $cton1 = $cton1->addAnd($cton2);
    }

    return $cton1;
  }

  /**
   * Geef de details terug voor de autocomplete
   *
   * @return string
   */
  public function getAutocompleteDetail()
  {
    Misc::use_helper('Date','Global', 'Url');

    $detail = 'Nr: ' . link_to($this->getId(), 'ttCommunicatie/edit/?template_id=' . $this->getId());
    $detail .= $this->getGearchiveerd() ? ' (gearchiveerd)' : '';
    $detail .= '<br/>';

    $detail .= $this->getNaam() . '<br />';
    $detail .= $this->getOnderwerp();

    return $detail;
  }

  /**
   * Geef de naam terug voor de autocomplete
   *
   * @return string
   */
  public function getAutocompleteName()
  {
    return $this->getNaam();
  }

  /**
   * Geeft een array met opties voor de class-specifieke configuratie
   * van de autocomplete
   *
   * @return array
   */
  public static function getAutocompleteConfig()
  {
    return array(
      'editUri' => 'ttCommunicatie/edit?return=%return_uri%&id=%id%',
      'createUri' => 'ttCommunicatie/create?return=%return_uri%',
    );
  }
}
sfPropelBehavior::add('BriefTemplate', array('storage'));
sfPropelBehavior::add('BriefTemplate', array('updater_loggable'));
if (sfConfig::get('sf_communicatie_enable_event_log')) sfPropelBehavior::add('BriefTemplate', array('event_log'));
