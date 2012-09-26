<?php

/**
 * Subclass for representing a row from the 'brief_template' table.
 *
 * 
 *
 * @package plugins.ttCommunicatiePlugin.lib.model
 */ 
class BriefTemplate extends BaseBriefTemplate
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
   * @param iMailer interface $object
   * @param array $options
   */
  public function sendMailToObject(iMailer $object, $options = array())
  {
    $systeemvalues = isset($options['systeemvalues']) ? $options['systeemvalues'] : array();
    
    // Controleren of het mogelijk is deze brief_template te versturen naar $object.
    $b     = $this->getBestemmelingArray();
    $cName = get_class($object);
    if (! in_array($cName, $b))
    {
      throw new sfException("Unknown object: can't send mail to instance of class \"{$cName}\"");
    }
    
    // sommige brieven mogen slechts eenmalig naar een object_class/id gestuurd worden
    if ($this->getEenmaligVersturen() && $this->reedsVerstuurdNaar($cName, $object->getId()))
    {
      throw new sfException("Mail already sent.");
    }
    
    // default placeholders die in layout gebruikt kunnen worden
    $vandaag = new myDate();
    $defaultPlaceholders = array(
      'datum' => $vandaag->format(),
      'datum_d_MMMM_yyyy' => $vandaag->format('d MMMM yyyy'),     
      'image_dir' => 'cid:',
      'pagebreak' => '<div style="page-break-before: always; margin-top: 80px;"></div>'
    );
    
    $culture    = $object->getMailerCulture();
    $values     = $object->fillPlaceholders(null, $culture);
    $values     = array_merge($defaultPlaceholders, $values, $systeemvalues);

    $email       = $object->getMailerRecipientMail();   
    $onderwerp   = BriefTemplatePeer::replacePlaceholders($this->getTranslatedOnderwerp($culture), $values);
    $html        = $this->getTranslatedHtml($culture);
    $headAndBody = $this->getBriefLayout()->getHeadAndBody('mail', $culture, $html, true);
    
    $brief = BriefTemplatePeer::replacePlaceholders($headAndBody['head'] . $headAndBody['body'], $values);
    
    // Mail versturen
    BerichtPeer::verstuurEmail($email, $brief, array(
      'onderwerp' => $onderwerp,
      'skip_template' => true,
      'cc' => (method_exists($object, 'getMailerRecipientCC') ? $object->getMailerRecipientCC() : array()),
      'bcc' => (method_exists($object, 'getMailerRecipientBCC') ? $object->getMailerRecipientBCC() : array())
    ));

    // Mail loggen
    $briefVerzonden = new BriefVerzonden();
    $briefVerzonden->setObjectClass($cName);
    $briefVerzonden->setObjectId($object->getId());
    $briefVerzonden->setBriefTemplateId($this->getId());
    $briefVerzonden->setMedium(BriefverzondenPeer::MEDIUM_MAIL);
    $briefVerzonden->setAdres($email);
    $briefVerzonden->setOnderwerp($onderwerp);
    $briefVerzonden->setCulture($culture);
    $briefVerzonden->setHtml($brief);
    $briefVerzonden->save();
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

    // Bijlagen die worden bijgevoegd op moment van versturen
    if ($request)
    {
      foreach ($request->getFiles() as $fileId => $fileInfo)
      {
        // Controleren of bestand correct werd opgehaald.
        if ($request->getFileError($fileId) == UPLOAD_ERR_NO_FILE)
        {
          // doe niets
        }
        else if ($request->getFileError($fileId) != UPLOAD_ERR_OK)
        {
          switch ($request->getFileError($fileId))
          {
            case UPLOAD_ERR_INI_SIZE:
              echo  'Opgeladen bestand groter dan ' . ini_get('upload_max_filesize') . '.';
              break;
            case UPLOAD_ERR_PARTIAL:
              echo 'Bestand werd gedeeltelijk opgeladen.';
              break;
            case UPLOAD_ERR_NO_TMP_DIR:
              echo 'bestand', 'Systeem kon geen tijdelijke folder vinden.';
              break;
            case UPLOAD_ERR_CANT_WRITE:
              echo 'bestand', 'Systeem kon niet schrijven naar schijf.';
              break;
            case UPLOAD_ERR_EXTENSION:
              echo 'bestand', 'Incorrecte extensie.';
              break;
          }
          echo '<br /><a href="#" onclick="window.close();">Klik hier om het venster te sluiten</a>';
          exit();
        }
        else
        {
          if (function_exists('sys_get_temp_dir'))
          {
            $tmpFile = tempnam(sys_get_temp_dir(), 'brief_bijlage');
          }
          else
          {
            $tmpFile = tempnam('/tmp', 'brief_bijlage');
          }        
          move_uploaded_file($fileInfo['tmp_name'], $tmpFile);
          $attachments[$fileInfo['name']] = $tmpFile;
        }
      }
    }
    
    return $attachments;
  }
}
sfPropelBehavior::add('BriefTemplate', array('storage'));
sfPropelBehavior::add('BriefTemplate', array('updater_loggable'));
if (sfConfig::get('sf_communicatie_enable_event_log')) sfPropelBehavior::add('BriefTemplate', array('event_log'));
