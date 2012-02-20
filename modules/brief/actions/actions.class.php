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
class briefActions extends sfActions
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
    
    $this->criteria = clone $this->getUser()->getAttribute('bestemmelingen_criteria', new Criteria(), 'brieven');
    $this->bestemmelingenClass = $this->getUser()->getAttribute('bestemmelingen_class', null, 'brieven');
    $this->bestemmelingenPeer = $this->bestemmelingenClass . 'Peer';
    $this->autoloadClasses = $this->getUser()->getAttribute('autoload_classes', array(), 'brieven');
    $this->choose_template = $this->getUser()->getAttribute('choose_template', true, 'brieven');
    
    // Some hacking om het mogelijk te maken om ook systeemtemplates te versturen,
    // momenteel enkel mogelijk naar single bestemmeling.
    if ($this->getUser()->getAttribute('systeem_template_id', null, 'brieven'))
    {
      $this->brief_template = BriefTemplatePeer::retrieveByPK($this->getUser()->getAttribute('systeem_template_id', null, 'brieven'));
    }
    else
    {
      $this->brief_template = BriefTemplatePeer::retrieveByPK($this->getRequestParameter('template_id')); 
    }
    
    
    var_dump($this->brief_template);
    die();
    
    // is_systeemtemplate var meegeven aan view om makkelijker te werken.
    $this->is_systeemtemplate = $this->brief_template && $this->brief_template->isSysteemtemplate();   
   
    // Het is onmogelijk om "systeemplaceholders" te gebruiken in de opmaakSuccess template,
    // systeemplaceholders automatisch vervangen.
    $this->systeemvalues = $this->getUser()->getAttribute('systeemvalues', array(), 'brieven');

    // "%" rond keys zetten zodat deze correct vervangen worden (in template zelf).
    if (! empty($this->systeemvalues))
    {      
      $keys = array_keys($this->systeemvalues);
      foreach ($keys as $key => $val)
      {
        $keys[$key] = "%{$val}%";
      }
      $this->systeemvalues = array_combine($keys, $this->systeemvalues);
    }
  }
  
  /**
   * Standaard index actie
   */
  public function executeIndex()
  {
    $this->forward('brief', 'list');
  }

   /**
   * lijst van zelf gemaakte brief templates in de db
   */
  public function executeList()
  {
    $this->pager = new myFilteredPager('BriefTemplate', 'brief/list');
    $this->pager->getCriteria()->add(BriefTemplatePeer::TYPE, BriefTemplatePeer::TYPE_DB);
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
        
    $this->setTemplate('edit');
  }

  /**
   * aanpassen van manuele html brief template
   */
  public function executeEdit()
  {  
    $this->preExecuteVersturen();
    
    $this->brief_template = BriefTemplatePeer::retrieveByPK($this->getRequestParameter('template_id'));
    
    if ($this->is_vertaalbaar = BriefTemplatePeer::isVertaalbaar())
    {
      $this->language_array = BriefTemplatePeer::getTranslationLanguageArray();
    }
    
    if ($this->systeemnaam = $this->brief_template->getSysteemnaam())
    {
      $this->systeemplaceholders = $this->brief_template->getSysteemplaceholdersArray();
    }
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
        $this->getRequest()->setError('bestemmelingen', 'Gelieve minstens ��n mogelijke bestemmeling in te geven.');
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
      
      $systeem = false;
    }
    
    if (! $systeem)
    {
      $brief_template->setNaam($this->getRequestParameter('naam')); 
      $brief_template->setBestemmelingArray($this->getRequestParameter('classes'));
    }
    $brief_template->setBriefLayoutId($this->getRequestParameter('brief_layout_id'));
    $brief_template->setEenmaligVersturen($this->getRequestParameter('eenmalig_versturen', 0));
    
    if (BriefTemplatePeer::isVertaalbaar())
    {
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
    }
    else
    {
      $brief_template->setOnderwerp($this->getRequestParameter('onderwerp'));
      $brief_template->setHtml($this->getRequestParameter('html')); 
      $brief_template->save(); 
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
    $this->redirect('brief/list');
  }
  
  /**
   * Error handling bij updaten van een template
   */
  public function handleErrorUpdate()
  {
    if (!$this->getRequestParameter('template_id'))
    {
      $this->forward('brief', 'create');
    }
    else
    {
      $this->forward('brief', 'edit');
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
    
    $cultures       = BriefTemplatePeer::getCultureLabelArray();
    $defaultCulture = BriefTemplatePeer::getDefaultCulture();
    
    $html        = array();
    $onderwerp   = array();
    $culture_arr = array();
    
    foreach ($cultures as $culture => $label)
    {
      $culture_arr[] = $culture;
      
      if ($culture === $defaultCulture)
      {
        $html[$culture]      = $this->brief_template->getHtml();
        $onderwerp[$culture] = $this->brief_template->getOnderwerp();
      }
      else
      {
        $html[$culture]      = $this->brief_template->getVertaling($this->brief_template->getHtmlSource($culture));
        $onderwerp[$culture] = $this->brief_template->getVertaling($this->brief_template->getOnderwerpSource($culture));
      }
    }
    
    echo json_encode(array(
      'html'      => $html,
      'eenmalig'  => $this->brief_template->getEenmaligVersturen() ? ('ja (reeds ontvangen: ' . $rs->getRecordCount() . ')')  : 'nee',
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
    
    $this->is_vertaalbaar = BriefTemplatePeer::isVertaalbaar();
    $this->language_array = BriefTemplatePeer::getTranslationLanguageArray();
    
    $rs = $this->getRs();
    $this->aantalBestemmelingen = $rs->getRecordCount();

    if ($this->choose_template)
    {
      if ($this->brief_template)
      {
        $this->brief_templates = array($this->brief_template);        
      }
      else
      {
        $c = new Criteria();
        $c->add(BriefTemplatePeer::BESTEMMELING_CLASSES, '%|' . $this->bestemmelingenClass . '|%', Criteria::LIKE);      
        $this->brief_templates = BriefTemplatePeer::getSorted($c);
        $this->brief_template = reset($this->brief_templates);
      }
      
      $this->onderwerp = $this->brief_template->getOnderwerp();
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
  private function calculateCulture($object)
  {
    $culture = $object->getMailerCulture();
    $cultures = BriefTemplatePeer::getCultureLabelArray();
    
    if (! $culture)
    {
      $culture = $this->getUser()->getCulture();
    }
    
    if (! array_key_exists($culture, $cultures))
    {
      $culture = BriefTemplatePeer::getCulture(BriefTemplatePeer::getDefaultCulture());
    }
    
    return $culture;
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
          $tmpFile = tempnam(sys_get_temp_dir(), 'hrm_brief_bijlage');
        }
        else
        {
          $tmpFile = tempnam('/tmp', 'hrm_brief_bijlage');
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
          $tmpFile = tempnam(sys_get_temp_dir(), 'hrm_brief_bijlage');
        }
        else
        {
          $tmpFile = tempnam('/tmp', 'hrm_brief_bijlage');
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
    $this->preExecuteVersturen();

    $chooseTemplate = $this->getRequestParameter('choose_template', true);
    $voorbeeld = (stripos($this->getRequestParameter('commit'), 'voorbeeld') !== false);
    // moeten er effectief e-mails verzonden worden?
    $emailverzenden = (! $voorbeeld) && (stripos($this->getRequestParameter('commit'), 'mail') !== false);    
    // verzenden via email: liefst, altijd of nooit (nee)
    $verzenden_via = $this->getRequestParameter('verzenden_via', false);
    // ophalen van de brieftemplate met de correcte layout (email of brief)
    $emailLayout = (stripos($this->getRequestParameter('commit'), 'e-mail') !== false);

    // Voorbeeld is altijd op papier
    if ($voorbeeld)
    {
      $viaemail = (stripos($this->getRequestParameter('commit'), 'e-mail') !== false);
    }
    else
    {      
      $viaemail = (($verzenden_via == 'liefst') || ($verzenden_via == 'altijd'));

    }    
    
    if ($voorbeeld)
    {
      $this->criteria->setLimit(1);
    }
    
         
    $this->brief_template = BriefTemplatePeer::retrieveByPK($this->getRequestParameter('template_id'));    
    $this->forward404Unless($this->brief_template);

    $onderwerp = $this->getRequestParameter('onderwerp');   
    $html = $this->getRequestParameter('html');
    
    $this->cultureBrieven = array();
    foreach (BriefTemplatePeer::getCultureLabelArray() as $culture => $label)
    {
      $this->cultureBrieven[$culture] = $this->brief_template->getBriefLayout()->getHeadAndBody($emailLayout ? 'mail' : 'brief', $culture, $html[$culture], $emailverzenden);
      $this->cultureBrieven[$culture]['onderwerp'] = $onderwerp[$culture];      
    } 

    $rs = $this->getRs();

    // default placeholders die in layout gebruikt kunnen worden
    $vandaag = new myDate();
    $defaultPlaceholders = array(
      'datum' => $vandaag->format(),
      'datum_d_MMMM_yyyy' => $vandaag->format('d MMMM yyyy'),
      //'user_naam' => $this->getUser()->getPersoon()->getNaam(),
      //'user_telefoon' => $this->getUser()->getPersoon()->getTelefoon() ? $this->getUser()->getPersoon()->getContactTelefoon() : '-',
      //'user_email' => $this->getUser()->getPersoon()->getContactEmail(),
      'pagebreak' => '<div style="page-break-before: always; margin-top: 80px;"></div>'
    );

    if ($emailverzenden)
    { 
      $counter = array('reedsverstuurd' => 0, 'verstuurd' => 0, 'error' => 0, 'wenstgeenmail' => 0);
      
      while ($rs->next())      
      {
        $object = new $this->bestemmelingenClass();
        $object->hydrate($rs);
        
        if (! isset($briefTemplate))
        {
          $layoutEnTemplateId = $object->getLayoutEnTemplateId();
          if (! $layoutEnTemplateId)
          {
            echo "<font color=red>brief_layout_id en brief_template_id niet gevonden voor {$this->bestemmelingenClass} (id: {$object->getId()})</font><br/>";
            continue;
          }
          $briefTemplate = BriefTemplatePeer::retrieveByPK($layoutEnTemplateId['brief_template_id']);
          if (! $briefTemplate)
          {
            echo "<font color=red>{$this->bestemmelingenClass} (id: {$object->getId()}): BriefTemplate (id: {$layoutEnTemplateId['brief_template_id']}) niet gevonden.</font><br/>";
            continue;
          }
          
          $briefLayout = BriefLayoutPeer::retrieveByPK($layoutEnTemplateId['brief_layout_id']);
          
          $onderwerp = $briefTemplate->getOnderwerp();         
          $html = BriefTemplatePeer::getBerichtHtml($briefTemplate, $emailLayout, $emailverzenden, $briefTemplate->getHtml(), $briefLayout);

          // Knip het resulterende document op in stukken zodat we meerdere
          // brieven kunnen afdrukken zonder foute HTML te genereren (meerdere HEAD / BODY blokken)
          $berichtHead = BriefTemplatePeer::getBerichtHead($html);
          $berichtBody = BriefTemplatePeer::getBerichtBody($html);
        }

        echo $object->__toString() . ': ';
        
        // sommige brieven mogen slechts eenmalig naar een object_class/id gestuurd worden
        if ($briefTemplate->getEenmaligVersturen() && $briefTemplate->ReedsVerstuurdNaar($this->bestemmelingenClass, $object->getId()))
        {
          echo 'Reeds verstuurd.<br/>';
          $counter['reedsverstuurd']++;
          continue;
        }        

        $verstuurd = false;
        $email = $object->getMailerRecipientMail();
        
        if (((($verzenden_via == 'liefst') && $object->getMailerPrefersEmail()) || ($verzenden_via == 'altijd')) && $email)
        {
          $culture = $this->calculateCulture($object);
          
          // replace the placeholders
          $values = array_merge($object->fillPlaceholders(null, $culture), $this->defaultPlaceholders);
          $onderwerp = BriefTemplatePeer::replacePlaceholders($this->cultureBrieven[$culture]['onderwerp'], $values);
          $values['onderwerp'] = $onderwerp;

          $brief = BriefTemplatePeer::replacePlaceholders($this->cultureBrieven[$culture]['body'], $values);
          $brief = $this->cultureBrieven[$culture]['head'] . $brief;
          $email = $object->getMailerRecipientMail();

          $tmpAttachments = $this->getAttachments($briefTemplate);
          $attachments = $tmpAttachments;
          
          if (method_exists($object, 'getBriefAttachments'))
          {
            $objectAttachments = $object->getBriefAttachments();
            $attachments = array_merge($attachments, $objectAttachments);            
          }                   
          
          $nietVerstuurdReden = '';
          try {            
            BerichtPeer::verstuurEmail($email, $brief, array(
              'onderwerp' => $onderwerp,
              'skip_template' => true,
              'afzender' => sfConfig::get("sf_mail_sender"),
              'attachements' => $attachments,
              'img_path' => sfConfig::get('sf_data_dir') . DIRECTORY_SEPARATOR . 'brieven' . DIRECTORY_SEPARATOR . 'layouts' . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR
            ));

            $verstuurd = true;
            echo 'Bericht verzonden naar : ' . $email . '<br/>';
            $counter['verstuurd']++;

            // Log de brief
            $briefVerzonden = new BriefVerzonden();
            $briefVerzonden->setObjectClass($this->bestemmelingenClass);
            $briefVerzonden->setObjectId($object->getId());
            $briefVerzonden->setBriefTemplateId($briefTemplate->getId());
            $briefVerzonden->setMedium(BriefverzondenPeer::MEDIUM_MAIL);
            $briefVerzonden->setAdres($email);
            $briefVerzonden->setOnderwerp($onderwerp);
            $briefVerzonden->setCulture($culture);
            $briefVerzonden->setHtml($brief);
            $briefVerzonden->save();
            
            // notify object dat er een brief naar het object verzonden is
            if (method_exists($object, 'notifyBriefVerzonden'))
            {
              $object->notifyBriefVerzonden($briefVerzonden, isset($this->brief_template) ? $this->brief_template->getId() : null);
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
          $nietVerstuurdReden = "<font color=red>E-mail werd niet verzonden naar $email, reden: communicatie via e-mail niet gewenst.</font><br/>";
          echo $nietVerstuurdReden;      
          $counter['wenstgeenmail']++;
        }
        
        if (method_exists($object, 'addLog') && isset($this->brief_template))
        {        
          $email = $object->getMailerRecipientMail();
          $log = "Brief '" . $this->brief_template->getNaam() . "' werd " . ($verstuurd ? "" : "<b>niet</b> ") . "verstuurd via mail naar " . $email . '.';
          $log .= $verstuurd ? '' : '  Reden: ' . $nietVerstuurdReden;           
          $object->addLog($log, $html);     
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
      $this->type = 'brieven';
      if (isset($briefTemplate) && ($briefTemplate instanceof BriefTemplate))
      { 
        $this->brief_template = $briefTemplate;        
        $this->type .= ' ' . strtolower($briefTemplate->getNaam());
        $this->html = $html;
      }
   
      $this->rs = $rs;
      $this->voorbeeld = $voorbeeld;
      $this->emailverzenden = $emailverzenden;
      $this->viaemail = $viaemail;
      $this->emailLayout = $emailLayout;
      $this->defaultPlaceholders = $defaultPlaceholders;
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
    
  	//$brief_template = BriefTemplatePeer::retrieveByPK($this->getRequestParameter('template_id'));
    $html = unserialize($this->getRequestParameter('html'));
    $onderwerp = unserialize($this->getRequestParameter('onderwerp'));
    $object_ids = explode(',', $this->getRequestParameter('object_ids'));
    
    $c = new Criteria();
    $c->add(eval('return ' . $this->bestemmelingenPeer . '::ID;'), $object_ids, Criteria::IN);
    $rs = eval('return ' . $this->bestemmelingenPeer . '::doSelectRs($c);');

    $vandaag = new myDate();

    $defaultPlaceholders = array(
      'datum' => $vandaag->format(),
      'datum_d_MMMM_yyyy' => $vandaag->format('d MMMM yyyy'),
      //'user_naam' => $this->getUser()->getPersoon()->getNaam(),
      //'user_telefoon' => $this->getUser()->getPersoon()->getTelefoon() ? $this->getUser()->getPersoon()->getTelefoon() : '-',
      //'user_email' => $this->getUser()->getPersoon()->getEmail(),
      'pagebreak' => '<div style="page-break-before: always; margin-top: 80px;" />'
    );

    while ($rs->next())
    {
      $object = new $this->bestemmelingenClass();
      $object->hydrate($rs);
      
      $culture = $this->calculateCulture($object);

      // replace the placeholders
      $values = array_merge($object->fillPlaceholders(), $defaultPlaceholders);
      $values['onderwerp'] = BriefTemplatePeer::replacePlaceholders($onderwerp[$culture], $values);
      
      $brief = BriefTemplatePeer::replacePlaceholders($html[$culture], $values);

      $briefVerzonden = new BriefVerzonden();
      $briefVerzonden->setObjectClass($this->bestemmelingenClass);
      $briefVerzonden->setObjectId($object->getId());
      //$briefVerzonden->setBriefTemplateId($brief_template->getId());
      $briefVerzonden->setMedium(BriefverzondenPeer::MEDIUM_PRINT);
      $briefVerzonden->setAdres($object->getAdres());
      $briefVerzonden->setHtml($brief);
      $briefVerzonden->setCulture($culture);
      $briefVerzonden->setOnderwerp($values['onderwerp']);
      $briefVerzonden->save();
            
      // notify object dat er een brief naar het object verzonden is
      if (method_exists($object, 'notifyBriefVerzonden'))
      {
        $object->notifyBriefVerzonden($briefVerzonden, isset($this->brief_template) ? $this->brief_template->getId() : null);
      }
      
      if (method_exists($object, 'addLog') && isset($this->brief_template))
      {        
        $object->addLog("Brief '" . $this->brief_template->getNaam() . "' werd afgedrukt.", $html);        
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
      $this->forward('brief', 'editBriefVerzonden');
    }
    else
    {
      $this->forward('brief', 'createBriefVerzonden');
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

    $this->redirect('brief/edit?template_id=' . $briefTemplateId);
  }
}
  