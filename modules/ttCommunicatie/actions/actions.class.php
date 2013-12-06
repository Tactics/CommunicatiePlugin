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
  private $counter = array(
    'reedsverstuurd' => 0,
    'verstuurd' => 0,
    'error' => 0,
    'wenstgeenmail' => 0,
    'niettoegestaan' => 0,
    'batch' => 0
  );

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

    $this->objectClass = $this->getUser()->getAttribute('object_class', null, $this->md5hash);
    $this->objectPeer = $this->objectClass . 'Peer';
    $this->criteria = clone $this->getUser()->getAttribute('object_criteria', new Criteria(), $this->md5hash);
    // om classes de in de criteria gebruikt worden te autoloaden
    $this->autoloadClasses = $this->getUser()->getAttribute('autoload_classes', array(), $this->md5hash);
    $this->rs = $this->getRs();
    
    $this->choose_template = $this->getUser()->getAttribute('choose_template', true, $this->md5hash);
    $this->edit_template = $this->getUser()->getAttribute('edit_template', true, $this->md5hash);
        
    $this->bestemmelingen = $this->getUser()->getAttribute('bestemmelingen', null, $this->md5hash);
    if (isset($this->bestemmelingen))
    {
      $aantal = 0;
      foreach ($this->bestemmelingen as $objectId => $bestemmelingen)
      {
        $aantal += count($bestemmelingen);
      }

      $this->bestemmelingen_aantal = $aantal;
      
      if (1 == $aantal)
      {
        $this->bestemmeling = null;
        foreach ($this->bestemmelingen as $objectId => $bestemmelingen)
        {
          foreach ($bestemmelingen as $index => $bestemmeling)
          {
            $object = call_user_func($this->objectClass . 'Peer::retrieveByPK', $objectId);
            $bestemmeling->setObject($object);
            $this->bestemmeling = $bestemmeling;
          }
          
          if (isset($this->bestemmeling)) break;
        }
      }
    }
    else // bestemmelingen opbouwen adhv crit
    {
      $this->bestemmelingen = array();
      $this->bestemmelingen_aantal = $this->rs->getRecordCount();      
      while ($this->rs->next())
      {
        $object = new $this->objectClass();
        $object->hydrate($this->rs);

        if (!isset($this->bestemmelingen[$object->getId()]))
        {
          $this->bestemmelingen[$object->getId()] = array();
        }
        
        $bestemmeling = $object->getTtCommunicatieBestemmeling();
        if (1 == $this->bestemmelingen_aantal)
        {
          $bestemmeling->setObject($object);
          $this->bestemmeling = $bestemmeling;          
        }

        $this->bestemmelingen[$object->getId()][] = $bestemmeling;
      }
      $this->getUser()->setAttribute('bestemmelingen', $this->bestemmelingen, $this->md5hash);
    }

    $this->show_bestemmelingen = $this->getUser()->getAttribute('show_bestemmelingen', false, $this->md5hash);
 
    if (1 == $this->bestemmelingen_aantal)
    {
      $this->show_bestemmelingen = false;
    }

    $this->afzender = $this->getUser()->getAttribute('afzender', sfConfig::get("sf_mail_sender"), $this->md5hash);
    $this->forceer_versturen = $this->getUser()->getAttribute('forceer_versturen', false, $this->md5hash);

    // indien object gegeven, wordt criteria en objectClass/Peer enzo niet gebruikt.
    $this->object = $this->getUser()->getAttribute('object', null, $this->md5hash);
    
    if ($this->object)
    { 
      // voorbeelden hebben nog geen id
      if (!$this->object->getId())
      {
        $this->objectClass = null;
        $this->objectPeer = null;
        $this->criteria = null;        
      }
      else
      {
        $this->objectClass = get_class($this->object);
        $this->objectPeer = $this->objectClass . 'Peer';
        $this->criteria = new Criteria();
        $this->criteria->add(eval("return {$this->objectPeer}::ID;"), $this->object->getId());
      }

      if (isset($this->bestemmeling))
      {
        $bestemmeling->setObject($this->object);
      }
    }
    
    // init van default waarden
    $this->brief_template = null;    
    $this->systeemplaceholders = array();
    $sessionTemplateId = $this->getUser()->getAttribute('template_id', null, $this->md5hash);
    if ($templateId = $this->getRequestParameter('template_id', $sessionTemplateId))
    {
      $this->brief_template = BriefTemplatePeer::retrieveByPK($templateId);
      if (!$this->brief_template)
      {
        throw new ttCommunicatieException('Brieftemplate (id: ' . $templateId . ') niet gevonden.');
      } 
      
      $this->choose_template = false; 
      
      if ($this->brief_template->isSysteemTemplate())
      {
        $this->systeemplaceholders = $this->getUser()->getAttribute('systeemplaceholders', array(), $this->md5hash);      
      }      
    }
    
    // indien geem template_id opgegeven en je er geen kan kiezen, dan kan edit niet
    if (!$this->brief_template && !$this->choose_template)
    {
      $this->edit_template = false;
    }
    
    // check of bestemmeling class een communicatie target is volgend de config
    // indien geen target, worden invoegvelden e.d. niet weergegeven.
    $targets = array();
    foreach (sfConfig::get('sf_communicatie_targets') as $targetInfo)
    {
      $targets[] = $targetInfo['class'];
    }
    $this->is_target = in_array($this->objectClass, $targets);    
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
    $brief_template->setIsPubliciteit($this->getRequestParameter('is_publiciteit', 0));
    
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
      $this->criteria->addJoin(BriefVerzondenPeer::OBJECT_ID, eval('return ' . $this->objectPeer . '::ID;'));
      $this->criteria->add(BriefVerzondenPeer::OBJECT_CLASS, $this->objectClass);

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
   * $this->objectPeer en $this->criteria
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
        $rs = eval('return ' . $this->objectPeer . '::doSelectRs($this->criteria);');
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
        
    if ($this->choose_template)
    {      
      // indien bestemmeling een communicatie target is (zie settings.yml)
      $c = new Criteria();      
      $c->add(BriefTemplatePeer::GEARCHIVEERD, false);
      $objectClass = $this->is_target ? $this->objectClass : 'Algemeen';
      $c->add(BriefTemplatePeer::BESTEMMELING_CLASSES, "%|$objectClass|%", Criteria::LIKE);
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
   * uitvoeren van de print validatie
   *
   * @return boolean
   */
  public function validatePrint()
  {
    try {
     $this->validateAttachments();
    } catch (ttCommunicatieException $e) {
      $this->getRequest()->setError('bijlagen', $e->getMessage());
    }

    return !$this->getRequest()->hasErrors();
  }
  /**
   * uitvoeren van de print error handler
   */
  public function handleErrorPrint()
  {
    $this->forward('ttCommunicatie', 'opmaak');
  }
  
  /**
   * Afdrukken of e-mail verzenden
   */
  public function executePrint()
  {
    Misc::use_helper('Url');
    set_time_limit(0);
    
    $this->preExecuteVersturen();

    $voorbeeld = (stripos($this->getRequestParameter('commit'), 'voorbeeld') !== false);
    // moeten er effectief e-mails verzonden worden?
    $emailverzenden = (! $voorbeeld) && (stripos($this->getRequestParameter('commit'), 'mail') !== false);    
    // verzenden via email: liefst, altijd of nooit (nee)
    $verzenden_via = $this->getRequestParameter('verzenden_via', false);
    // ophalen van de brieftemplate met de correcte layout (email of brief)
    $emailLayout = (stripos($this->getRequestParameter('commit'), 'e-mail') !== false);
    // ?
    $viaemail = $voorbeeld
      ? stripos($this->getRequestParameter('commit'), 'e-mail') !== false
      : ($verzenden_via == 'liefst') || ($verzenden_via == 'altijd');    
    
    if ($voorbeeld && $this->criteria)
    {
      $this->criteria->setLimit(1);
    }

    if ($this->brief_template)
    {
      $cultureBrieven = $this->getCultureBrieven($this->brief_template, $emailLayout, false);
      $this->brief_template->setCultureBrieven($cultureBrieven);
    }

    if ($emailverzenden)
    {
      if ($this->show_bestemmelingen)
      {
        $selectedBestemmelingen = $this->getRequestParameter("bestemmelingen", array());
      }

      $rs = $this->getRs();
      $briefVerzondenIds = array();
      while ($rs->next())      
      {        
        $object = new $this->objectClass();
        $object->hydrate($rs);

        $bestemmelingen = isset($this->bestemmelingen[$object->getId()]) ? $this->bestemmelingen[$object->getId()] : array();
        if (empty($bestemmelingen))
        {
          continue;
        }       

        // start logging
        $this->startObjectLog($object);
        
        // geen brief_template => controleren of er aan het object zelf een template_id gekoppeld is
        $briefTemplate = $this->brief_template;
        if (!$briefTemplate)
        {
          try {
            $briefTemplate = BriefTemplatePeer::retrieveFromObject($object);
          }
          catch (ttCommunicatieException $e) {
            $this->log($e->getMessage());
            $this->counter['error']++;
            continue;
          }

          $cultureBrieven = $this->getCultureBrieven($briefTemplate, true, true);
          $briefTemplate->setCultureBrieven($cultureBrieven);
        }

        // check some business logic/rules
        if (!$this->sendingAllowed($object, $briefTemplate))
        {
          continue;
        }
        
        foreach ($bestemmelingen as $index => $bestemmeling)
        {
          // controle of bestemmeling afgevinkt is in lijst
          if (
            isset($selectedBestemmelingen)
            && (!isset($selectedBestemmelingen[$object->getId()]) || !in_array($index, $selectedBestemmelingen[$object->getId()]))
          ) {
            continue;          
          }         
          
          $bestemmeling->setObject($object);
          
          // indien $this->bestemmelingen_aantal = 1,
          // kan email_to/cc/bcc overschreven worden          
          if ($this->bestemmelingen_aantal == 1)
          {            
            $this->updateBestemmeling($bestemmeling);            
          }          
          
          $email = $bestemmeling->getEmailTo();
          $prefersEmail = $bestemmeling->getPrefersEmail();
          if (((($verzenden_via == 'liefst') && $prefersEmail) || ($verzenden_via == 'altijd')) && $email)
          {
            $briefVerzonden = BriefVerzondenPeer::createEmail($briefTemplate, $bestemmeling);
            $briefVerzondenIds[] = $briefVerzonden->getId();
            $this->log('E-mail klaar voor verzending.', false);
          }
          else
          {
            $nietVerstuurdReden = $email
              ? 'Communicatie via e-mail niet gewenst.'
              : 'Geen e-mail adres.';
            
            $this->log($nietVerstuurdReden);
            $this->counter['wenstgeenmail']++;
            if (method_exists($object, 'addLog'))
            {
              $log = "Brief {$briefTemplate->getNaam()} werd <b>niet</b> verzonden via e-mail.";
              $log .= " Reden: $nietVerstuurdReden";
              $object->addLog($log);
            }
          }
        } // endforeach objectbestemmeling
      }
      
      if (!empty($briefVerzondenIds))
      {        
        $batchVanaf = sfConfig::get('sf_communicatie_batchmailing_vanaf', false);
        $briefVerzondenRs = $this->getBriefVerzondenRs($briefVerzondenIds);        
        $this->storeAttachments($briefVerzondenRs);        
        if(($batchVanaf === false) || (count($briefVerzondenIds) < $batchVanaf))
        {          
          $this->counter['verstuurd'] = $this->sendEmails($briefVerzondenRs);
        }
        else
        {
          $this->counter['batch'] = $this->createBatchmailing($briefVerzondenRs);
        }
      } 

      echo '<br/><br/>Einde verzendlijst<br/><br/>';
      echo 'Totaal:<br/>';
      echo 'Reeds verstuurd: ' . $this->counter['reedsverstuurd'] . '<br/>';
      echo 'Niet toegestaan: ' . $this->counter['niettoegestaan'] . '<br/>';
      echo 'Wensen geen mail: ' . $this->counter['wenstgeenmail'] . '<br />';
      echo 'Verstuurd: ' . $this->counter['verstuurd'] . '<br />';
      echo 'Batch: ' . $this->counter['batch'] . '<br />';
      echo 'Error: ' . $this->counter['error'] . '<br />';
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
      $this->verzenden_via = $verzenden_via;
      $this->setLayout(false);
      $this->getResponse()->setTitle($voorbeeld ? 'Voorbeeld afdrukken' : 'Afdrukken');
    }
  }

  /**
   * Verzend mails
   */
  private function sendEmails($briefVerzondenRs)
  {
    $aantalVerstuurd = 0;
    $briefVerzondenRs->seek(0);
    while($briefVerzondenRs->next())
    {
      $briefVerzonden = new BriefVerzonden();
      $briefVerzonden->hydrate($briefVerzondenRs);
      $briefTemplate = new BriefTemplate();
      $briefTemplate->hydrate($briefVerzondenRs, BriefVerzondenPeer::NUM_COLUMNS + 1);
      $object = new $this->objectClass();
      $object->hydrate($briefVerzondenRs, BriefVerzondenPeer::NUM_COLUMNS + BriefTemplatePeer::NUM_COLUMNS + 1);

      $this->startObjectLog($object);

      $emailTo = $briefVerzonden->getAdres();

      try {
        $briefVerzonden->verzendMail();
      }
      catch (Exception $e){
        $this->counter['error']++;

        $log = 'E-mail <b>niet</b> verzonden naar ' . $emailTo . '<br />Reden: ' . nl2br($e->getMessage());
        $this->log($log);
        $this->addObjectLog($object, $log);
        continue;
      }

      $briefVerzonden->setStatus(BriefVerzondenPeer::STATUS_VERZONDEN);
      $briefVerzonden->save();
      $aantalVerstuurd++;

      $log = 'E-mail "' . $briefTemplate->getNaam() . '" verzonden naar : ' . $emailTo;
      $log .= $briefVerzonden->getCc() ? ', cc: ' . $briefVerzonden->getCc() : '';
      $log .= $briefVerzonden->getBcc() ? ', bcc: ' . $briefVerzonden->getBcc() : '';
      $this->log($log, false);
      $this->addObjectLog($object, $log, $briefVerzonden->getHtml());

      // notify object dat er een brief naar het object verzonden is
      if (method_exists($object, 'notifyBriefVerzonden'))
      {
        $object->notifyBriefVerzonden($briefVerzonden);
      }
    }

    return $aantalVerstuurd;
  }

  /**
  * Batchtaak aanmaken en event triggeren
  */
  private function createBatchmailing($briefVerzondenRs)
  {
    $batchtaak = new BatchTaak();
    $batchtaak->setAantal($briefVerzondenRs->getRecordCount());
    $batchtaak->setStatus(BatchTaakPeer::STATUS_PAUZE);
    $batchtaak->setVerzendenVanaf(time());
    $batchtaak->save();

    $first = true;
    while($briefVerzondenRs->next())
    {
      $briefVerzonden = new BriefVerzonden();
      $briefVerzonden->hydrate($briefVerzondenRs);
      if($first)
      {
        $batchtaak->setObjectClass($briefVerzonden->getObjectClass());
        $batchtaak->save();
        $first = false;
      }

      $briefVerzonden->setBatchtaakId($batchtaak->getId());
      $briefVerzonden->save();
    }

    $dispatcher = $this->getContainer()->get('event_dispatcher');
    $event = new TtCommunicatieBatchTaakEvent($batchtaak);
    $dispatcher->dispatch(TtCommunicatieEvents::TT_COMMUNICATIE_BATCH_TAAK_CREATED, $event);

    return $briefVerzondenRs->getRecordCount();
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
  
  /**
   * uitvoeren van de object communicatielog
   */
  public function executeObjectCommunicatieLog()
  {
    $objectClass = $this->getRequestParameter('object_class');
    $objectId = $this->getRequestParameter('object_id');
    $this->object = eval("return {$objectClass}Peer::retrieveByPK({$objectId});");
    $this->forward404Unless($this->object);    
    
    $this->type = $this->getRequestParameter('type');
  }

  /**
   * valideert de bijlagen   
   * @throws ttCommunicatieException
   */
  private function validateAttachments()
  {
    foreach ($this->getRequest()->getFiles() as $fileId => $fileInfo)
    {
      // Controleren of bestand correct werd opgehaald.
      if ($this->getRequest()->getFileError($fileId) == UPLOAD_ERR_NO_FILE)
      {
        continue;
      }

      $name = $fileInfo['name'];     
      if ($this->getRequest()->getFileError($fileId) != UPLOAD_ERR_OK)
      {
        switch ($this->getRequest()->getFileError($fileId))
        {
          case UPLOAD_ERR_INI_SIZE:
            throw new ttCommunicatieException("Bijlage $name groter dan " . ini_get('upload_max_filesize') . '.');
            break;
          case UPLOAD_ERR_PARTIAL:
            throw new ttCommunicatieException("Bijlage $name werd slechts gedeeltelijk opgeladen.");
            break;
          case UPLOAD_ERR_NO_TMP_DIR:
          case UPLOAD_ERR_CANT_WRITE:
            throw new ttCommunicatieException("Bijlage $name kon niet worden opgeslagen.");
            break;
          case UPLOAD_ERR_EXTENSION:
            throw new ttCommunicatieException("Bijlage $name heeft een incorrecte extensie.");
            break;
          default:
            throw new ttCommunicatieException("Ongekende fout voor bijlage $name");
            break;
        }
      }

      $maxSizeInMB = 10;
      $maxSize = $maxSizeInMB * 1024 * 1024;
      if ($fileInfo['size'] > $maxSize)
      {
        throw new ttCommunicatieException("Bestand $name groter dan {$maxSizeInMB}MB.");
      }
    }
  }
  
  /**
   * overschrijven van to, cc, bcc adhv request params
   * 
   * @param iTtCommunicatieBestemmeling $bestemmeling
   * @return \iTtCommunicatieBestemmeling
   */
  private function updateBestemmeling(ttCommunicatieBestemmeling $bestemmeling)
  {    
    $oldEmail = $bestemmeling->getEmailTo();

    $emailTo = str_replace(array(' ', ','), array('', ';'), trim($this->getRequestParameter('email_to', '')));
    $emailCc = ($cc = trim($this->getRequestParameter('email_cc', '')))
        ? explode(';', str_replace(array(' ', ','), array('', ';'), $cc))
        : array();
    $emailBcc = ($bcc = trim($this->getRequestParameter('email_bcc', '')))
        ? explode(';', str_replace(array(' ', ','), array('', ';'), $bcc))
        : array();

    
    $bestemmeling->setEmailTo($emailTo);
    $bestemmeling->setEmailCc($emailCc);
    $bestemmeling->setEmailBcc($emailBcc);
    $bestemmeling->setPrefersEmail(true);

    // indien emailto overschreven
    if ($oldEmail && (false === strpos($emailTo, $oldEmail)))
    {      
      // bestemmelingen class wijzigen naar object zelf
      // zodat de email daar wordt gelogd + naam en adres wissen
      $bestemmeling->setBestemmeling($bestemmeling->getObject());
      $bestemmeling->setNaam('');
      $bestemmeling->setAdres('');
    }
  }

  /**
   * geeft voor elke culture de brieven terug opgesplitst in
   * head, body en onderwerp
   * 
   * @param BriefTemplate $brief_template
   * @param boolean $email_layout
   * @param boolean $email_verzenden
   * @return array
   */
  private function getCultureBrieven(BriefTemplate $brief_template, $email_layout, $email_verzenden)
  {
    $onderwerpen = $this->edit_template
      ? $this->getRequestParameter('onderwerp')
      : $brief_template->getOnderwerpCultureArr();
    
    $htmls = $this->edit_template
      ? $this->getRequestParameter('html')
      : $brief_template->getHtmlCultureArr();

    $briefLayout = $brief_template->getBriefLayout();
    $cultureBrieven = array();
    foreach (BriefTemplatePeer::getCultureLabelArray() as $culture => $label)
    {
      $cultureBrieven[$culture] = $briefLayout->getHeadAndBody($email_layout ? 'mail' : 'brief', $culture, $htmls[$culture], $email_verzenden);
      $cultureBrieven[$culture]['onderwerp'] = $onderwerpen[$culture];
    }

    return $cultureBrieven;
  }

  /**
   * checks is sending of the object is allowed
   * according to some business logic
   * 
   * @param mixed $object
   * @param BriefTemplate $brief_template
   * @return boolean
   */
  private function sendingAllowed($object, BriefTemplate $brief_template)
  {
    if (method_exists($object, 'sendingTemplateAllowed') && !$object->sendingTemplateAllowed($brief_template))
    {
      echo 'Niet toegestaan.<br/>';
      $this->counter['niettoegestaan']++;
      return false;
    }

    // sommige brieven mogen slechts eenmalig naar een object_class/id gestuurd worden
    // @todo: fix this, voor elke bestemmeling checken? of oke zo?
    if (!$this->forceer_versturen && $brief_template->getEenmaligVersturen() && $brief_template->ReedsVerstuurdNaar(get_class($object), $object->getId()))
    {
      echo 'Reeds verstuurd.<br/>';
      $this->counter['reedsverstuurd']++;
      return false;
    }

    return true;
  }  

  /**
   * geeft de resultset terug van de te verzenden brieven
   * bevat ook de velden van het te verzenden object
   * 
   * @param array $brief_verzonden_ids
   * @return resource Resultset
   */
  private function getBriefVerzondenRs($brief_verzonden_ids)
  {
    $objectId = call_user_func($this->objectClass . 'Peer::translateFieldName', 'Id', BasePeer::TYPE_PHPNAME, BasePeer::TYPE_COLNAME);

    $c = new Criteria();    
    $c->addJoin(BriefVerzondenPeer::BRIEF_TEMPLATE_ID, BriefTemplatePeer::ID, Criteria::JOIN);
    $c->addJoin(BriefVerzondenPeer::OBJECT_ID, $objectId, Criteria::JOIN);
    $c->add(BriefVerzondenPeer::ID, $brief_verzonden_ids, Criteria::IN);
    $c->clearSelectColumns();
    BriefVerzondenPeer::addSelectColumns($c);
    BriefTemplatePeer::addSelectColumns($c);
    call_user_func($this->objectClass . 'Peer::addSelectColumns', $c);

    return BriefVerzondenPeer::doSelectRS($c);
  }

  /**
   * start logging van object
   *
   * @param mixed $object
   * @return string
   */
  private function startObjectLog($object)
  {
    $log = $this->objectClass . ' (' . (method_exists($object, '__toString')
      ? $object->__toString()
      : 'id: ' . $object->getId()) . '): ';

    echo $log;
  }

  /**
   * outputs the log
   * 
   * @param string $log
   * @param boolean $error color = red when true
   */
  private function log($log, $error = true)
  {
    echo $error ? '<font color="red">' . $log . '</font>' : $log;
    echo '<br/>';
  }

  /**
   * add log to object if addLog method exists
   *
   * @param string $log
   * @param mixed $object
   */
  private function addObjectLog($object, $log, $log_detail = null)
  {
    if (!method_exists($object, 'addLog'))
    {
      return;
    }

    $object->addLog($log, $log_detail);
  }

  /**
   * opslaan van bijlagen in dms
   */
  private function storeAttachments(Resultset $brief_verzonden_rs)
  {
    $storeName = sfConfig::get('sf_communicatie_dms_store', '');
    if (!$this->getRequest()->hasFiles() || $this->getRequest()->hasFileErrors()
        || !$storeName || !($dmsStore = DmsStorePeer::retrieveByName($storeName)))
    {      
      return;
    }

    $uuid = Misc::create_uuid();
    $node = new DmsNode();
    $node->setStoreId($dmsStore->getId());
    $node->setIsFolder(1);    
    $node->setName($uuid);
    $node->setDiskName($uuid);
    $node->save();

    foreach ($this->getRequest()->getFiles() as $fileId => $fileInfo)
    {
      $node->createNodeFromUpload($fileId, $fileInfo['name']);
    }

    $brief_verzonden_rs->seek(0);
    while ($brief_verzonden_rs->next())
    {
      $briefVerzonden = new BriefVerzonden();
      $briefVerzonden->hydrate($brief_verzonden_rs);
      $objectNodeRef = new DmsObjectNodeRef();
      $objectNodeRef->setObjectClass('BriefVerzonden');
      $objectNodeRef->setObjectId($briefVerzonden->getId());
      $objectNodeRef->setNodeId($node->getId());
      $objectNodeRef->save();
    }
  }
}
  
