<?php

/*
 * Dit bestand maakt deel uit van een applicatie voor Digipolis Antwerpen.
 * 
 * (c) <?php echo date('Y'); ?> Tactics BVBA
 *
 * Recht werd verleend om dit bestand te gebruiken als onderdeel van de genoemde
 * applicatie. Mag niet doorverkocht worden, noch rechtstreeks noch via een
 * derde partij. Meer informatie in het desbetreffende aankoopcontract. 
 */
 
/**
 * brieven acties.
 *
 * @package    kdv
 * @subpackage brieven
 * @author     Taco Orens
 */
class ttCommunicatieActions extends sfActions
{
  /**
   * geeft de resultset van objecten weer afh van de gegeven class en object_ids
   *
   * @return resultset $rs
   */
  private function preExecuteVersturen()
  {
    // fix voor ontbrekende autoload in criteria uit sessie
    ini_set('unserialize_callback_func', '__autoload');
    
    $this->md5hash = $this->getRequestParameter('hash');
    
    $this->criteria = clone $this->getUser()->getAttribute('bestemmelingen_criteria', new Criteria(), $this->md5hash); 
    $this->choose_template = $this->getUser()->getAttribute('choose_template', true, $this->md5hash);
    $this->edit_template = $this->getUser()->getAttribute('edit_template', true, $this->md5hash);
    $this->bestemmelingenClass = $this->getUser()->getAttribute('bestemmelingen_class', null, $this->md5hash);
    $this->bestemmelingenPeer = $this->bestemmelingenClass . 'Peer';
    $this->show_bestemmelingen = $this->getUser()->getAttribute('show_bestemmelingen', false, $this->md5hash);
    
    // indien object gegeven, wordt criteria en bestemmelingenClass/Peer enzo niet gebruikt.
    $this->bestemmelingen_object = $this->getUser()->getAttribute('bestemmelingen_object', null, $this->md5hash);
    
    if ($this->bestemmelingen_object)
    {
      $this->criteria = null;
      $this->bestemmelingenClass = null;
      $this->bestemmelingenPeer = '';
    }
    
    // om classes de in de criteria gebruikt worden te autoloaden
    $this->autoloadClasses = $this->getUser()->getAttribute('autoload_classes', array(), $this->md5hash);    
    
    // init van default waarden
    $this->brief_template = null;    
    $this->systeemplaceholders = array();
    if ($this->getUser()->getAttribute('template_id', null, $this->md5hash))
    {
      $this->brief_template = BriefTemplatePeer::retrieveByPK($this->getUser()->getAttribute('template_id', null, $this->md5hash));
      if (! $this->brief_template)
      {
        throw new sfException('Brieftemplate (id: ' . $this->getRequestParameter('template_id') . ') niet gevonden.');
      } 
      
      $this->choose_template = false; 
      
      if ($this->brief_template->isSysteemTemplate())
      {
        $this->systeemplaceholders = $this->getUser()->getAttribute('systeemplaceholders', array(), $this->md5hash);      
      }      
    }
    
    // indien geem template_id opgegeven en je er geen kan kiezen, dan kan edit niet
    if (! $this->brief_template && ! $this->choose_template)
    {
      $this->edit_template = false;
    }
  }
  
  /**
   * Standaard index actie
   */
  public function executeIndex()
  {
    $this->forward('ttCommunicatie', 'list');
  }

   /**
   * lijst van zelf gemaakte brief templates in de db
   */
  public function executeList()
  {
    $this->pager = new myFilteredPager('BriefTemplate', 'ttCommunicatie/list');
    
    // archief bekijken?
    if (!$this->getUser()->getAttribute('bekijk_archief', false))
    {
      $this->pager->getCriteria()->add(BriefTemplatePeer::GEARCHIVEERD, 0);
    }
    
    // indien enable_categories = true: alleen templates waartoe de user access heeft    
    if (sfConfig::get('sf_communicatie_enable_categories', false))
    {      
      $this->pager->add(BriefTemplatePeer::CATEGORIE, array('value' => $this->getUser()->getTtCommunicatieCategory()));
    }
    
    $this->pager->getCriteria()->add(BriefTemplatePeer::TYPE, BriefTemplatePeer::TYPE_DB);
    
    $this->pager->add(BriefTemplatePeer::NAAM, array('comparison' => Criteria::LIKE));
    $this->pager->add(BriefTemplatePeer::ONDERWERP, array('comparison' => Criteria::LIKE));
    
    if ($this->pager->add(BriefLayoutPeer::NAAM, array('comparison' => Criteria::LIKE)))
    {
      $this->pager->getCriteria()->addJoin(BriefTemplatePeer::BRIEF_LAYOUT_ID, BriefLayoutPeer::ID);
    }
    
    $this->pager->init();
  }

  /**
   * maak manueel nieuwe html brief template
   */
  public function executeCreate()
  {
    $this->brief_template = new BriefTemplate();
    
    if ($this->is_vertaalbaar = BriefTemplatePeer::isVertaalbaar())
    {
      $this->language_array = BriefTemplatePeer::getTranslationLanguageArray();
    }
    
    $this->systeemplaceholders = $this->brief_template->isSysteemtemplate() ? $this->brief_template->getSysteemplaceholdersArray() : array();
    $this->setTemplate('edit');
  }

  /**
   * Kopiëer een sjabloon
   */
  public function executeCopy()
  {
    $origineel = BriefTemplatePeer::retrieveByPK($this->getRequestParameter('template_id'));
    
    $this->brief_template = $origineel->copy();
    $this->brief_template->setNaam("Kopie van " . $origineel->getNaam());
    
    if ($this->is_vertaalbaar = BriefTemplatePeer::isVertaalbaar())
    {
      $this->language_array = BriefTemplatePeer::getTranslationLanguageArray();
    }
    
    $this->systeemplaceholders = $this->brief_template->isSysteemtemplate() ? $this->brief_template->getSysteemplaceholdersArray() : array();
    $this->setTemplate('edit');
  }
  
  
  /**
   * aanpassen van manuele html brief template
   */
  public function executeEdit()
  {  
    $this->brief_template = BriefTemplatePeer::retrieveByPK($this->getRequestParameter('template_id'));
    $this->forward404Unless($this->brief_template);
    
    $this->systeemplaceholders = $this->brief_template->isSysteemtemplate() ? $this->brief_template->getSysteemplaceholdersArray() : array();
  }
  
  /**
   * Validatie bij updaten van een brief template
   */
  public function validateUpdate()
  {
    if ($this->getRequestParameter('template_id'))
    {
      $briefTemplate = BriefTemplatePeer::retrieveByPK($this->getRequestParameter('template_id'));
      $this->forward404Unless($briefTemplate);
      
      $systeem = $briefTemplate->getSysteemnaam() ? true : false;
    }
    else
    {
      $systeem = false;
    }
    
    if (! $systeem)
    {
      if (! $this->getRequestParameter('classes'))
      {
        $this->getRequest()->setError('bestemmelingen', 'Gelieve minstens één mogelijke bestemmeling in te geven.');
      }
    
      if (! $this->getRequestParameter('naam'))
      {
        $this->getRequest()->setError('naam', 'Gelieve een naam in te geven.');
      }  
    }
    
    if (! $this->getRequestParameter('brief_layout_id'))
    {
      $this->getRequest()->setError('brief_layout_id', 'Gelieve een layout te selecteren.');
    }
    
    BriefTemplatePeer::isVertaalbaar() ? $this->validateUpdateVertaalbaar() : $this->validateUpdateNietVertaalbaar();
    
    return !$this->getRequest()->hasErrors();
  }
  
  /**
   * Verwijderen van een brieftemplate 
   */
  public function executeDelete()
  {
    $template = BriefTemplatePeer::retrieveByPK($this->getRequestParameter('template_id'));
    $this->forward404Unless($template && $template->isVerwijderbaar());
    
    $template->delete();
    
    $this->redirect($this->getRequest()->getReferer());
  }
  
  /**
   * Validatie wanneer vertaling nodig is
   */
  private function validateUpdateVertaalbaar()
  {
    // Mogelijke vertalingen
    $cultures = BriefTemplatePeer::getCultureLabelArray();    
    // Array met body en onderwerp per culture.
    $onderwerpen = $this->getRequestParameter('onderwerp');
    $htmls       = $this->getRequestParameter('html');
    
    foreach ($cultures as $culture => $label)
    {
      if (! $onderwerpen[$culture])
      {
        $this->getRequest()->setError('onderwerp[' . $culture . ']', 'Gelieve een ' . $label . ' onderwerp in te geven.');
      }
      
      if (! $htmls[$culture])
      {
        $this->getRequest()->setError('html[' . $culture . ']', 'Gelieve een ' . $label . ' e-mail bericht in te geven.');
      }
    }
    
    return ! $this->getRequest()->hasErrors();
  }
  
  /**
   * Validatie wanneer vertaling niet nodig is
   */
  private function validateUpdateNietVertaalbaar()
  {
    if (! $this->getRequestParameter('onderwerp'))
    {
      $this->getRequest()->setError('onderwerp', 'Gelieve een onderwerp in te geven.');
    }
  }
  
  /**
   * update nieuwe html brief template
   */
  public function executeUpdate()
  {
    if (stripos($this->getRequestParameter('commit'), 'voorbeeld') !== false)
    {
      $this->forward('ttCommunicatie', 'voorbeeld');
    }
    
    if ($this->getRequestParameter('template_id'))
    {
      $brief_template = BriefTemplatePeer::retrieveByPK($this->getRequestParameter('template_id'));
      $this->forward404Unless($brief_template);
      
      $systeem = $brief_template->getSysteemnaam() ? true : false;
    }
    else
    {
      $brief_template = new BriefTemplate();
      $brief_template->setType(BriefTemplatePeer::TYPE_DB);
      
      // category goed zetten indien enabled
      if (sfConfig::get('sf_communicatie_enable_categories', false))
      {
        $brief_template->setCategorie($this->getUser()->getTtCommunicatieCategory());
      }      
      
      $systeem = false;
    }
    
    if (! $systeem)
    {
      $brief_template->setNaam($this->getRequestParameter('naam')); 
      $brief_template->setBestemmelingArray($this->getRequestParameter('classes'));
    }
    $brief_template->setBriefLayoutId($this->getRequestParameter('brief_layout_id'));
    $brief_template->setEenmaligVersturen($this->getRequestParameter('eenmalig_versturen', 0));
    
    $brief_template->save();
      
    $cultures = BriefTemplatePeer::getCultureLabelArray();
    $defaultCulture = BriefTemplatePeer::getDefaultCulture();

    // Array met body en onderwerp per culture.
    $onderwerpen = $this->getRequestParameter('onderwerp');
    $htmls       = $this->getRequestParameter('html');

    foreach ($cultures as $culture => $label)
    {       
      // Default culture opslaan in brief_template object
      if ($culture === $defaultCulture) 
      {
        $brief_template->setOnderwerp($onderwerpen[$culture]);
        $brief_template->setHtml($htmls[$culture]); 
        $brief_template->save(); 
      }
      else // Vertalingen opslaan in TransUnit object
      {          
        // @todo getCatalogueName method.
        $catalogueName = 'brieven.'.$culture;
        $htmlSource = $brief_template->getHtmlSource($culture);
        $onderwerpSource = $brief_template->getOnderwerpSource($culture);

        $this->updateOrCreateTransUnit($htmlSource, $htmls[$culture], null, $catalogueName);
        $this->updateOrCreateTransUnit($onderwerpSource, $onderwerpen[$culture], null, $catalogueName);
      }
    }

    foreach ($this->getRequest()->getFiles() as $fileId => $fileInfo)
    {
      if (! $this->getRequest()->hasFile($fileId))
      {
        continue;
      }

      // Controleren of bestand correct werd opgehaald.
      if ($this->getRequest()->getFileError($fileId) != UPLOAD_ERR_OK)
      {
        continue;
      }
     
      // bestand opslaan in dms
      $folder = $brief_template->getDmsStorageFolder();      
      $node = $folder->createNodeFromUpload($fileId);

      $briefBijlage = new BriefBijlage();
      $briefBijlage->setBriefTemplateId($brief_template->getId());
      $briefBijlage->setBijlageNodeId($node->getId());
      $briefBijlage->save();
    }
    
    $this->redirect('ttCommunicatie/list');
  }
  
  /**
   * Error handling bij updaten van een template
   */
  public function handleErrorUpdate()
  {
    if (!$this->getRequestParameter('template_id'))
    {
      $this->forward('ttCommunicatie', 'create');
    }
    else
    {
      $this->forward('ttCommunicatie', 'edit');
    }
  }
 
  /**
   * Updaten of aanmaken van een transunit.
   * Aanmaken: manueel
   * Updaten: via sfMessageSource_MSSQL class
   * 
   * @param type $catalogueName
   * @param type $tekst 
   */
  private function updateOrCreateTransUnit($source, $target, $comments, $catalogueName)
  {      
    $catalogue = CataloguePeer::retrieveByName($catalogueName);
    if (! $catalogue)
    {
      throw new sfException('Catalogue not found.');
    }    

    $c = new Criteria();
    $c->add(TransUnitPeer::CATALOGUE_ID, $catalogue->getId());
    $c->add(TransUnitPeer::SOURCE, $source);
    $transUnit = TransUnitPeer::doSelectOne($c);
    
    if ($transUnit)
    {
      $transUnit->setTarget($target);
    }
    else
    {
      $transUnit = new TransUnit();
      $transUnit->setCatalogueId($catalogue->getId());
      $transUnit->setSource($source);
      $transUnit->setTarget($target);
      $transUnit->setComments($comments);
    }
    
    $transUnit->save();
  }

  /**
   * ajax load van de html van een template
   */
  public function executeTemplateHtml()
  {
    $this->preExecuteVersturen();

    $briefTemplate = BriefTemplatePeer::retrieveByPK($this->getRequestParameter('template_id'));
    $this->forward404Unless($briefTemplate);
    
    if ($briefTemplate->getEenmaligVersturen())
    {
      // Aantal dat de brief reeds kreeg
      $this->criteria->add(BriefVerzondenPeer::BRIEF_TEMPLATE_ID, $briefTemplate->getId());
      $this->criteria->addJoin(BriefVerzondenPeer::OBJECT_ID, eval('return ' . $this->bestemmelingenPeer . '::ID;'));
      $this->criteria->add(BriefVerzondenPeer::OBJECT_CLASS, $this->bestemmelingenClass);

      $rs = $this->getRs();
    }
    
    $cultures = BriefTemplatePeer::getCultureLabelArray();
    $defaultCulture = BriefTemplatePeer::getDefaultCulture();
    
    $html = $briefTemplate->getHtmlCultureArr();
    $onderwerp = $briefTemplate->getOnderwerpCultureArr();
    $culture_arr = array_keys($cultures);
    
    echo json_encode(array(
      'html'      => $html,
      'eenmalig'  => $briefTemplate->getEenmaligVersturen() ? ('ja (reeds ontvangen: ' . $rs->getRecordCount() . ')')  : 'nee',
      'onderwerp' => $onderwerp,
      'cultures'  => $culture_arr
    ));

    exit();
  }
  
  /**
   * geeft de resultset terug op basis van
   * $this->bestemmelingenPeer en $this->criteria
   * 
   * @return $rs Resultset
   */
  private function getRs()
  {
    foreach ($this->autoloadClasses as $class)
    {      
      $classPeer = sfInflector::camelize($class) . 'Peer';        
      eval($classPeer . '::TABLE_NAME;'); // autoloaden van de peer gebeurt hier
    }
    
    while(true)
    {
      try
      {
        $rs = eval('return ' . $this->bestemmelingenPeer . '::doSelectRs($this->criteria);');
        break;
      }
      // load Peerclasses when not yet autoloaded
      catch(Exception $e)
      {
        $m = $e->getMessage();           
        if (strpos($m, 'Cannot fetch TableMap for undefined table') === false)
        {
          throw($e);
        }
        $table = substr($m, strpos($m, 'table:') + 7, -1);        
        $tablePeer = sfInflector::camelize($table) . 'Peer';        
        eval($tablePeer . '::TABLE_NAME;'); // autoloaden van de peer gebeurt hier
      }
    }
    return $rs;
  }
  
  /**
   * Weergave scherm om brief op te maken
   */
  public function executeOpmaak()
  {
    $this->preExecuteVersturen();
    
    set_time_limit(0);
    
    $this->rs = $this->getRs();
        
    if ($this->choose_template)
    {      
      $c = new Criteria();
      $c->add(BriefTemplatePeer::BESTEMMELING_CLASSES, '%|' . $this->bestemmelingenClass . '|%', Criteria::LIKE);      
      $this->brief_templates = BriefTemplatePeer::getSorted($c); 
    }
  }

  /**
   * Include een bestand en geef het resultaat terug als string
   *
   * http://php.net/manual/en/function.include.php
   * 
   * @param string $filename
   * @return string
   */
  private function get_include_contents($filename)
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
   * geeft een array terug met attachments uit
   * a) de template
   * b) on-the-fly toegevoegd
   * 
   * @param BriefTemplate $briefTemplate
   * 
   * @return array
   */
  private function getAttachments($briefTemplate)
  {
    $attachments = array();
    if ($briefTemplate->getBriefBijlages())
    {
      foreach ($briefTemplate->getBriefBijlages() as $briefBijlage)
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
    foreach ($this->getRequest()->getFiles() as $fileId => $fileInfo)
    {
      // Controleren of bestand correct werd opgehaald.
      if ($this->getRequest()->getFileError($fileId) == UPLOAD_ERR_NO_FILE)
      {
        // doe niets
      }
      else if ($this->getRequest()->getFileError($fileId) != UPLOAD_ERR_OK)
      {
        switch ($this->getRequest()->getFileError($fileId))
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
    return $attachments;
  }
  
  /**
   * Afdrukken of e-mail verzenden
   */
  public function executePrint()
  {
    Misc::use_helper('Url');
    set_time_limit(0);
    
    $this->preExecuteVersturen();
    
    if ($this->show_bestemmelingen)
    {
      $this->criteria->addAnd(eval('return ' . $this->bestemmelingenPeer . '::ID;'), $this->getRequestParameter('niet_verzenden_naar', array()), Criteria::NOT_IN);
    }    

    $voorbeeld = (stripos($this->getRequestParameter('commit'), 'voorbeeld') !== false);
    // moeten er effectief e-mails verzonden worden?
    $emailverzenden = (! $voorbeeld) && (stripos($this->getRequestParameter('commit'), 'mail') !== false);    
    // verzenden via email: liefst, altijd of nooit (nee)
    $verzenden_via = $this->getRequestParameter('verzenden_via', false);
    // ophalen van de brieftemplate met de correcte layout (email of brief)
    $emailLayout = (stripos($this->getRequestParameter('commit'), 'e-mail') !== false);

    // Voorbeeld is altijd op papier?not
    if ($voorbeeld)
    {
      $viaemail = (stripos($this->getRequestParameter('commit'), 'e-mail') !== false);
    }
    else
    {      
      $viaemail = (($verzenden_via == 'liefst') || ($verzenden_via == 'altijd'));

    }    
    
    if ($voorbeeld && $this->criteria)
    {
      $this->criteria->setLimit(1);
    }    
         
    // template_id ?
    if ($this->getRequestParameter('template_id'))
    {
      $this->brief_template = BriefTemplatePeer::retrieveByPK($this->getRequestParameter('template_id')); 
      if (! $this->brief_template)
      {
        throw new sfException('Brieftemplate (id: ' . $this->getRequestParameter('template_id') . ') niet gevonden.');
      }      
    }

    if ($this->brief_template)
    {
      // brief layout ophalen
      $this->brief_layout = $this->brief_template->getBriefLayout();

      // onderwerp en tekst ophalen
      $onderwerpen = $this->edit_template ? $this->getRequestParameter('onderwerp') : ($this->brief_template ? $this->brief_template->getOnderwerpCultureArr() : null);
      $htmls = $this->edit_template ? $this->getRequestParameter('html') : ($this->brief_template ? $this->brief_template->getHtmlCultureArr() : null);

      $this->cultureBrieven = array();
      foreach (BriefTemplatePeer::getCultureLabelArray() as $culture => $label)
      {
        $this->cultureBrieven[$culture] = $this->brief_layout->getHeadAndBody($emailLayout ? 'mail' : 'brief', $culture, $htmls[$culture], $emailverzenden);
        $this->cultureBrieven[$culture]['onderwerp'] = $onderwerpen[$culture];      
      } 
    }   
    
    // default placeholders die in layout gebruikt kunnen worden
    $vandaag = new myDate();
    $defaultPlaceholders = array(
      'datum' => $vandaag->format(),
      'datum_d_MMMM_yyyy' => $vandaag->format('d MMMM yyyy'),     
      'image_dir' => $emailverzenden ? 'cid:' : url_for('ttCommunicatie/showImage') . '/image/',
      'pagebreak' => '<div style="page-break-before: always; margin-top: 80px;"></div>'
    );
    
    if (class_exists('Placeholder'))
    {
      $placeholder = new Placeholder();
      $defaultPlaceholders = array_merge($defaultPlaceholders, $placeholder->fillPlaceholders());
    }

    if ($emailverzenden)
    { 
      $counter = array('reedsverstuurd' => 0, 'verstuurd' => 0, 'error' => 0, 'wenstgeenmail' => 0);
      
      $rs = $this->getRs();
      while ($rs->next())      
      {
        $object = new $this->bestemmelingenClass();
        $object->hydrate($rs);
        
        // geen brief_template => controleren of er aan het object zelf een template_id gekoppeld is
        if (! $this->brief_template)
        {         
          if (method_exists($object, 'getLayoutEnTemplateId'))
          {
            // template ophalen
            $layoutEnTemplateId = $object->getLayoutEnTemplateId();
            if (isset($layoutEnTemplateId['brief_template_id']) && $layoutEnTemplateId['brief_template_id'])
            {
              $brief_template = BriefTemplatePeer::retrieveByPK($layoutEnTemplateId['brief_template_id']);          
              if (! $brief_template)
              {
                echo '<font color="red">' . get_class($object) . '&rarr;getLayoutEnTemplateId(): brief_template_id ' . $layoutEnTemplateId['brief_template_id'] . ' niet gevonden.</font><br/>';
                continue;                
              } 
            }
            else
            {
              echo '<font color="red">' . get_class($object) . '&rarr;getLayoutEnTemplateId(): brief_template_id niet opgegeven.</font><br/>';
              continue;  
            }

            // layout ophalen       
            if (isset($layoutEnTemplateId['brief_layout_id']) && $layoutEnTemplateId['brief_layout_id'])
            {
              $this->brief_layout = BriefLayoutPeer::retrieveByPK($layoutEnTemplateId['brief_layout_id']);
              if (! $this->brief_layout)
              {
                echo '<font color="red">' . get_class($object) . '&rarr;getLayoutEnTemplateId(): brief_layout_id ' . $layoutEnTemplateId['brief_layout_id'] . ' niet gevonden.</font><br/>';
                continue;
              } 
            }  
            else
            {
              echo '<font color="red">' . get_class($object) . '&rarr;getLayoutEnTemplateId(): brief_layout_id niet opgegeven.</font><br/>';
              continue;  
            }
          }
          else
          {
            echo '<font color="red">' . get_class($object) . '&rarr;getLayoutEnTemplateId(): method niet gevonden.</font><br/>';
            continue;            
          }  

          // onderwerp en tekst ophalen
          $onderwerpen = $brief_template->getOnderwerpCultureArr();
          $htmls = $brief_template->getHtmlCultureArr();

          $this->cultureBrieven = array();
          foreach (BriefTemplatePeer::getCultureLabelArray() as $culture => $label)
          {
            $this->cultureBrieven[$culture] = $this->brief_layout->getHeadAndBody($emailLayout ? 'mail' : 'brief', $culture, $htmls[$culture], $emailverzenden);
            $this->cultureBrieven[$culture]['onderwerp'] = $onderwerpen[$culture];      
          } 
        } 
        else
        {
          $brief_template = $this->brief_template;
        }

        echo get_class($object) . ' (id ' . $object->getId() . '): ';
        
        // sommige brieven mogen slechts eenmalig naar een object_class/id gestuurd worden
        if ($brief_template->getEenmaligVersturen() && $brief_template->ReedsVerstuurdNaar($this->bestemmelingenClass, $object->getId()))
        {
          echo 'Reeds verstuurd.<br/>';
          $counter['reedsverstuurd']++;
          continue;
        }        

        $verstuurd = false;
        $email = $object->getMailerRecipientMail();
        
        if (((($verzenden_via == 'liefst') && $object->getMailerPrefersEmail()) || ($verzenden_via == 'altijd')) && $email)
        {
          $culture = BriefTemplatePeer::calculateCulture($object);
          
          // replace the placeholders
          $placeholders = array_merge($object->fillPlaceholders(null, $culture), $defaultPlaceholders);
          $onderwerp = BriefTemplatePeer::replacePlaceholders($this->cultureBrieven[$culture]['onderwerp'], $placeholders);    
          $placeholders['onderwerp'] = $onderwerp; // nodig om placeholder %onderwerp% in body te vervangen
          $body = BriefTemplatePeer::replacePlaceholders($this->cultureBrieven[$culture]['body'], $placeholders);
          $brief = $this->cultureBrieven[$culture]['head'] . $body;          

          $tmpAttachments = $this->getAttachments($brief_template);
          $attachments = $tmpAttachments;
          
          // object-eigen attachements
          if (method_exists($object, 'getBriefAttachments'))
          {
            $objectAttachments = $object->getBriefAttachments();
            $attachments = array_merge($attachments, $objectAttachments);            
          }                   
          
          $nietVerstuurdReden = '';
          try {
            $options = array(
              'onderwerp' => $onderwerp,
              'skip_template' => true,
              'afzender' => sfConfig::get("sf_mail_sender"),
              'attachements' => $attachments,
              'img_path' => sfConfig::get('sf_data_dir') . DIRECTORY_SEPARATOR . 'brieven' . DIRECTORY_SEPARATOR . 'layouts' . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR
            );
            
            // getMailerRecipientCC is optionele methode.
            if (method_exists($object, 'getMailerRecipientCC') && $object->getMailerRecipientCC())
            {
              $options['cc'] = $object->getMailerRecipientCc();
            }
            
            // getMailerRecipientBCC is optionele methode.
            if (method_exists($object, 'getMailerRecipientBCC') && $object->getMailerRecipientBCC())
            {
              $options['bcc'] = $object->getMailerRecipientBCC();
            }
            
            BerichtPeer::verstuurEmail($email, BriefTemplatePeer::clearPlaceholders($brief), $options);

            $verstuurd = true;
            echo 'Bericht verzonden naar : ' . $email . '<br/>';
            $counter['verstuurd']++;

            // Log de brief
            $briefVerzonden = new BriefVerzonden();
            $briefVerzonden->setObjectClass($this->bestemmelingenClass);
            $briefVerzonden->setObjectId($object->getId());
            $briefVerzonden->setBriefTemplate($brief_template);
            $briefVerzonden->setMedium(BriefverzondenPeer::MEDIUM_MAIL);
            $briefVerzonden->setAdres($email);
            $briefVerzonden->setOnderwerp($onderwerp);
            $briefVerzonden->setCulture($culture);
            $briefVerzonden->setHtml($body);
            $briefVerzonden->setStatus(BriefVerzondenPeer::STATUS_VERZONDEN);
            $briefVerzonden->save();
            
            // notify object dat er een brief naar het object verzonden is
            if (method_exists($object, 'notifyBriefVerzonden'))
            {
              $object->notifyBriefVerzonden($briefVerzonden);
            }
          }
          catch(Exception $e)
          {
            $nietVerstuurdReden = '<font color=red>E-mail kon niet verzonden worden naar ' . $email . '<br />Reden: ' . nl2br($e->getMessage()) . '</font><br/>';
            echo $nietVerstuurdReden;
            $counter['error']++;
          }

          foreach($tmpAttachments as $tmpFile)
          {
            unlink($tmpFile);
          }
        }      
        else
        {      
          if (! $email)
          {
            $nietVerstuurdReden = "<font color=red>E-mail werd niet verzonden, reden: geen e-mail adres.</font><br/>";
          }
          else
          {
            $nietVerstuurdReden = "<font color=red>E-mail werd niet verzonden naar $email, reden: communicatie via e-mail niet gewenst.</font><br/>";
          }
          
          echo $nietVerstuurdReden;      
          $counter['wenstgeenmail']++;
        }
        
        if (method_exists($object, 'addLog'))
        {           
          $log = "Brief '" . $brief_template->getNaam() . "' werd " . ($verstuurd ? "" : "<b>niet</b> ") . "verstuurd via mail naar " . $email . '.';
          $log .= $verstuurd ? '' : '  Reden: ' . $nietVerstuurdReden;           
          $object->addLog($log, $verstuurd ? $body : null);
        }        
      }
      
      echo '<br/><br/>Einde verzendlijst<br/><br/>';
      echo 'Totaal:<br/>';
      echo 'Reeds verstuurd: ' . $counter['reedsverstuurd'] . '<br/>';
      echo 'Verstuurd: ' . $counter['verstuurd'] . '<br />';
      echo 'Error: ' . $counter['error'] . '<br />';
      echo 'Wensen geen mail: ' . $counter['wenstgeenmail'] . '<br />';
      echo '<a href="#" onclick="window.close();">Klik hier om het venster te sluiten</a>';
      exit();
    }
    else
    { 
      if ($this->criteria)
      {
        $this->rs = $this->getRs();
      }
      $this->voorbeeld = $voorbeeld;
      $this->emailverzenden = $emailverzenden;
      $this->viaemail = $viaemail;
      $this->emailLayout = $emailLayout;
      $this->defaultPlaceholders = $defaultPlaceholders;
      $this->verzenden_via = $verzenden_via;
      $this->setLayout(false);
      $this->getResponse()->setTitle($voorbeeld ? 'Voorbeeld afdrukken' : 'Afdrukken');
    }    
  }

  /**
   * Bevestigen van afdruk
   */
  public function executeBevestigAfdrukken()
  {
    $this->preExecuteVersturen();
    
    $brief_verzonden_ids = strpos($this->getRequestParameter('brief_verzonden_ids'), ',') !== false ? 
                            explode(',', $this->getRequestParameter('brief_verzonden_ids')) : 
                            array($this->getRequestParameter('brief_verzonden_ids'));
    
    $c = new Criteria();
    $c->add(BriefVerzondenPeer::ID, $brief_verzonden_ids, Criteria::IN);
    $c->add(BriefVerzondenPeer::STATUS, BriefVerzondenPeer::STATUS_NT_VERZONDEN);
    
    $rs = BriefVerzondenPeer::doSelectRs($c);
    while ($rs->next())
    {     
      $briefVerzonden = new BriefVerzonden();
      $briefVerzonden->hydrate($rs);
      
      $briefVerzonden->setStatus(BriefVerzondenPeer::STATUS_VERZONDEN);
      $briefVerzonden->save();
     
      $object = eval("return {$briefVerzonden->getObjectClass()}Peer::retrieveByPk({$briefVerzonden->getObjectId()});");    
      
      // notify object dat er een brief naar het object verzonden is
      if (method_exists($object, 'notifyBriefVerzonden'))
      {
        $object->notifyBriefVerzonden($briefVerzonden);
      }
      
      if (method_exists($object, 'addLog'))
      {        
        $object->addLog("Brief &ldquo;" . $briefVerzonden->getBriefTemplate()->getNaam() . "&rdquo; werd afgedrukt.", $briefVerzonden->getHtml());    
      }      
    }

 		return sfView::NONE;
  }

  /**
   * output een image
   */
  public function executeShowImage()
  {
    $imageName = $this->getRequestParameter('image');
    $this->forward404If(strstr($imageName, '..'));
    $image_dir = sfConfig::get('sf_data_dir') . DIRECTORY_SEPARATOR . 'brieven' . DIRECTORY_SEPARATOR . 'layouts' . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR;

    if (file_exists($image_dir . $imageName))
    {
      //header('Content-Type: image/jpeg');
      flush();
      readfile($image_dir . $imageName);
    }
    else
    {
      echo 'image "' . $image_dir . $imageName . '" not found';
    }
    exit();
  }

  /**
   * E-mail herzenden
   */
  public function executeHerzendEmail()
  {
    $briefVerzonden = BriefVerzondenPeer::retrieveByPk($this->getRequestParameter('id'));
    $this->forward404Unless($briefVerzonden && $briefVerzonden->getMedium() == BriefVerzondenPeer::MEDIUM_MAIL);
    
    echo $briefVerzonden->herzendEmail();
    exit();
  }

  /**
   * Geeft details van één verzonden brief weer
   */
  public function executeShowCommunicatieLog()
  {
    $this->briefVerzonden = BriefVerzondenPeer::retrieveByPK($this->getRequestParameter('id'));
    $this->view = $this->getRequestParameter('view', 'Success');

    return ucfirst($this->view);
  }
  
  /**
   * Aanmaken van een BriefVerzonden object
   * 
   */
  public function executeCreateBriefVerzonden()
  {
    $this->object = eval("return {$this->getRequestParameter('object_class')}Peer::retrieveByPk({$this->getRequestParameter('object_id')});");
    $this->forward404Unless($this->object);
    
    // ewww dirty, need to find better solution.
    $this->contactFields = array('adres', 'email', 'telefoon', 'gsm');

    $this->brief_verzonden = new BriefVerzonden();
    $this->setTemplate('editBriefVerzonden');
    return sfView::SUCCESS;
  }

  /**
   * Bewerken van een BriefVerzonden object
   *
   */
  public function executeEditBriefVerzonden()
  {
    $this->object = eval("return {$this->getRequestParameter('object_class')}Peer::retrieveByPk({$this->getRequestParameter('object_id')});");
    $this->brief_verzonden = BriefVerzondenPeer::retrieveByPk($this->getRequestParameter('brief_verzonden_id'));
    $this->forward404Unless($this->object && $this->brief_verzonden);

    // ewww dirty, need to find better solution.
    $this->contactFields = array('adres', 'email', 'telefoon', 'gsm');
  }

  /**
   * Updaten van een BriefVerzonden object
   *
   */
  public function executeUpdateBriefVerzonden()
  {
    if ($this->getRequestParameter('brief_verzonden_id'))
    {
      $briefVerzonden = BriefVerzondenPeer::retrieveByPk($this->getRequestParameter('brief_verzonden_id'));
      $this->forward404Unless($briefVerzonden);
    }
    else
    {
      $briefVerzonden = new BriefVerzonden();
    }

    $briefVerzonden->setObjectClass($this->getRequestParameter('object_class'));
    $briefVerzonden->setObjectId($this->getRequestParameter('object_id'));
    $briefVerzonden->setMedium($this->getRequestParameter('medium'));
    $briefVerzonden->setAdres($this->getRequestParameter('adres'));
    $briefVerzonden->setOnderwerp($this->getRequestParameter('onderwerp'));
    $briefVerzonden->setHtml($this->getRequestParameter('html'));
    $briefVerzonden->setCustom(true);
    $briefVerzonden->setStatus(BriefVerzondenPeer::STATUS_VERZONDEN);
    $briefVerzonden->save();

    $this->redirect(strtolower($briefVerzonden->getObjectClass()) . '/showCommunicatieLog?id=' . $briefVerzonden->getObjectId());
  }

  /**
   * Error handling bij het updaten van een BriefVerzonden object
   *
   */
  public function handleErrorUpdateBriefVerzonden()
  {
    if ($this->getRequestParameter('brief_verzonden_id'))
    {
      $this->forward('ttCommunicatie', 'editBriefVerzonden');
    }
    else
    {
      $this->forward('ttCommunicatie', 'createBriefVerzonden');
    }
  }

  /**
   * Downloaden van sollicitatiebijlagen
   */
  public function executeDownloadAttachment()
  {
    $node = DmsNodePeer::retrieveByPk($this->getRequestParameter('file_id'));
    $this->forward404Unless($node);

    $this->getResponse()->clearHttpHeaders();
    $this->response->setHttpheader('Pragma: public', true);
    $this->response->addCacheControlHttpHeader('Cache-Control', 'must-revalidate');
    $this->response->setHttpHeader('Expires', gmdate("D, d M Y H:i:s", time()) . " GMT");
    $this->response->setHttpHeader('Content-Description', 'File Transfer');

    if ($mime_type = $node->getMimeType())
    {
      $this->response->setContentType($mime_type, true);
    }

    $this->response->setHttpHeader('Content-Length', (string)($node->getSize()));

    $this->response->setHttpHeader('Last-modified', gmdate("D, d M Y H:i:s", $node->getUpdatedAt(null)) . " GMT");
    $this->response->setHttpHeader('Content-Disposition', 'attachment; filename="' . $node->getName() . '"');
    $this->getResponse()->sendHttpHeaders();

    $node->output();
    exit();
  }

  /**
   * Verwijderen van een BriefBijlage object
   * 
   */
  public function executeDeleteAttachment()
  {
    $node = DmsNodePeer::retrieveByPk($this->getRequestParameter('file_id'));
    $briefBijlage = BriefBijlagePeer::retrieveByPK($this->getRequestParameter('brief_bijlage_id'));
    $this->forward404Unless($node && $briefBijlage);


    $node->delete();
    $briefTemplateId = $briefBijlage->getBriefTemplateId();
    $briefBijlage->delete();

    $this->redirect('ttCommunicatie/edit?template_id=' . $briefTemplateId);
  }
  
  /**
   * Voorbeeld van brief of e-mail bekijken.
   * Bekijken van voorbeelden op moment van verzenden in executePrint()
   */
  public function executeVoorbeeld()
  {
    $culture =  array_search($this->getRequestParameter('language_label'), BriefTemplatePeer::getCultureLabelArray());
    $brief_layout = BriefLayoutPeer::retrieveByPK($this->getRequestParameter('brief_layout_id'));
    $this->forward404Unless($culture && $brief_layout);
    
    $emailLayout = (stripos($this->getRequestParameter('commit'), 'e-mail') !== false);
        
    $htmlArr = $this->getRequestParameter('html');
    
    $briefArr = $brief_layout->getHeadAndBody($emailLayout ? 'mail' : 'brief', $culture, $htmlArr[$culture]);

    $this->head = $briefArr['head'];
    $this->body = $briefArr['body'];
    
    $this->setLayout(false);    
  }
  
  /**
   * uitvoeren van voorbeeldbrief van een gegeven bestemmelingen object 
   * op deze manier kan de opmaak gebypassed worden
   * resultaat moet zelfde zijn als opvragen van voorbeeldbrief bij opmaak
   */
  public function executeVoorbeeldBrief()
  {
    // verzenden_via = alles afdrukken op papier
    $this->getRequest()->setParameter('verzenden_via', 'nee');
    
    // doen alsof er in opmaak op voorbeeld brief gedrukt is
    $this->getRequest()->setParameter('commit', 'Voorbeeld brief');
    
    $this->forward('ttCommunicatie', 'print');
  }
  
  /**
	 * (De)Archiveert een sjabloon
	 */
	public function executeArchiveer()
  {
	  $template = BriefTemplatePeer::retrieveByPk($this->getRequestParameter('id'));
    $this->forward404Unless($template);

    $template->setGearchiveerd($this->hasRequestParameter('archiveer') ? $this->getRequestParameter('archiveer') : ! $template->getGearchiveerd());    
    $template->save();
    
    return $this->redirect('ttCommunicatie/list');
	}
}
  