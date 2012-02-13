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
    $this->setTemplate('edit');
  }

  /**
   * aanpassen van manuele html brief template
   */
  public function executeEdit()
  {
    $this->brief_template = BriefTemplatePeer::retrieveByPK($this->getRequestParameter('template_id'));
  }

  /**
   * Validatie bij updaten van een brief template
   */
  public function validateUpdate()
  {
    if (! $this->getRequestParameter('classes'))
    {
      $this->getRequest()->setError('bestemmelingen', 'Gelieve minstens ��n mogelijke bestemmeling in te geven.');
    }
    
    return !$this->getRequest()->hasErrors();
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
    }
    else
    {
      $brief_template = new BriefTemplate();
      $brief_template->setType(BriefTemplatePeer::TYPE_DB);
    }
    
    $brief_template->setNaam($this->getRequestParameter('naam'));
    $brief_template->setOnderwerp($this->getRequestParameter('onderwerp'));
    $brief_template->setHtml($this->getRequestParameter('html'));
    $brief_template->setBriefLayoutId($this->getRequestParameter('brief_layout_id'));
    $brief_template->setEenmaligVersturen($this->getRequestParameter('eenmalig_versturen', 0));
    $brief_template->setBestemmelingArray($this->getRequestParameter('classes'));
    $brief_template->save();

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
   * geeft de resultset van objecten weer afh van de gegeven class en object_ids
   *
   * @return resultset $rs
   */
  private function preExecuteVersturen()
  {
    // fix voor ontbrekende autoload in criteria uit sessie
    ini_set('unserialize_callback_func', '__autoload');
    
    $this->criteria = clone $this->getUser()->getAttribute('bestemmelingen_criteria', null, 'brieven');
    $this->bestemmelingenClass = $this->getUser()->getAttribute('bestemmelingen_class', null, 'brieven');
    $this->autoloadClasses = $this->getUser()->getAttribute('autoload_classes', array(), 'brieven');
    $this->bestemmelingenPeer = $this->bestemmelingenClass . 'Peer';    
    $this->choose_template = $this->getUser()->getAttribute('choose_template', true, 'brieven');
    if ($this->getUser()->getAttribute('template_id', null, 'brieven'))
    {
      $this->brief_template = BriefTemplatePeer::retrieveByPK($this->getUser()->getAttribute('template_id', null, 'brieven'));
      $this->forward404Unless($this->brief_template);
    }
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

      $rs = eval('return ' . $this->bestemmelingenPeer . '::doSelectRs($this->criteria);');
    }

    echo json_encode(array(
      'html' => $briefTemplate->getHtml(),
      'eenmalig' => $briefTemplate->getEenmaligVersturen() ? ('ja (reeds ontvangen: ' . $rs->getRecordCount() . ')')  : 'nee',
      'onderwerp' => $briefTemplate->getOnderwerp()
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
    
    if ($chooseTemplate)
    {      
      $briefTemplate = BriefTemplatePeer::retrieveByPK($this->getRequestParameter('template_id'));    
      $this->forward404Unless($briefTemplate);
      
      $onderwerp = $this->getRequestParameter('onderwerp');            
      $html = BriefTemplatePeer::getBerichtHtml($briefTemplate, $emailLayout, $emailverzenden, $this->getRequestParameter('html'));
      
      // Knip het resulterende document op in stukken zodat we meerdere
      // brieven kunnen afdrukken zonder foute HTML te genereren (meerdere HEAD / BODY blokken)
      $berichtHead = BriefTemplatePeer::getBerichtHead($html);
      $berichtBody = BriefTemplatePeer::getBerichtBody($html);
    } 

    // default placeholders die in layout gebruikt kunnen worden
    $vandaag = new myDate();
    $defaultPlaceholders = array(
      'datum' => $vandaag->format(),
      'datum_d_MMMM_yyyy' => $vandaag->format('d MMMM yyyy'),
      'user_naam' => $this->getUser()->getPersoon()->getNaam(),
      'user_telefoon' => $this->getUser()->getPersoon()->getTelefoon() ? $this->getUser()->getPersoon()->getTelefoon() : '-',
      'user_email' => $this->getUser()->getPersoon()->getEmail(),
      'pagebreak' => '<div style="page-break-before: always; margin-top: 80px;"></div>'
    );
    
    if ($voorbeeld)
    {
      $this->criteria->setLimit(1);
    }    
    $rs = $this->getRs();

    if ($emailverzenden)
    { 
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

        // sommige brieven mogen slechts eenmalig naar een object_class/id gestuurd worden
        if ($briefTemplate->getEenmaligVersturen() && $briefTemplate->ReedsVerstuurdNaar($this->bestemmelingenClass, $object->getId()))
        {
          continue;
        }

        $verstuurd = false;
        $email = $object->getMailerRecipientMail();
        if (((($verzenden_via == 'liefst') && $object->getMailerPrefersEmail()) || ($verzenden_via == 'altijd')) && $email)
        {
          // replace the placeholders
          $values = array_merge($object->fillPlaceholders(), $defaultPlaceholders);
          $onderwerp = BriefTemplatePeer::replacePlaceholders($onderwerp, $values);
          $values['onderwerp'] = $onderwerp;

          $brief = BriefTemplatePeer::replacePlaceholders($berichtBody, $values);
          $brief = BriefTemplatePeer::clearPlaceholders($brief);          
          $brief = $berichtHead . $brief;          

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
              'afzender' => 'noreply@stad.antwerpen.be',
              'attachements' => $attachments,
              'img_path' => sfConfig::get('sf_data_dir') . DIRECTORY_SEPARATOR . 'brieven' . DIRECTORY_SEPARATOR . 'layouts' . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR
            ));

            $verstuurd = true;
            echo 'Bericht verzonden naar : ' . $email . '<br/>';

            // Log de brief
            $briefVerzonden = new BriefVerzonden();
            $briefVerzonden->setObjectClass($this->bestemmelingenClass);
            $briefVerzonden->setObjectId($object->getId());
            $briefVerzonden->setBriefTemplateId($briefTemplate->getId());
            $briefVerzonden->setMedium(BriefverzondenPeer::MEDIUM_MAIL);
            $briefVerzonden->setAdres($email);
            $briefVerzonden->setOnderwerp($onderwerp);
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
          }

          foreach($tmpAttachments as $tmpFile)
          {
            unlink($tmpFile);
          }
        }      
        else
        {      
          $email = $object->getMailerRecipientMail();
          if ($email)
          {
            $nietVerstuurdReden = "<font color=red>E-mail werd niet verzonden naar $email, reden: communicatie via e-mail niet gewenst.</font><br/>";
            echo $nietVerstuurdReden;
          }
          else
          {
            $nietVerstuurdReden = "<font color=red>E-mail werd niet verzonden naar " . strtolower(get_class($object)) . " met id: " . $object->getId() . ", reden: geen e-mail adres.</font><br/>";
            echo $nietVerstuurdReden;
          }       
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
    $html = $this->getRequestParameter('html');
    $onderwerp = $this->getRequestParameter('onderwerp');
    $object_ids = explode(',', $this->getRequestParameter('object_ids'));
    
    $c = new Criteria();
    $c->add(eval('return ' . $this->bestemmelingenPeer . '::ID;'), $object_ids, Criteria::IN);
    $rs = eval('return ' . $this->bestemmelingenPeer . '::doSelectRs($c);');

    $vandaag = new myDate();

    $defaultPlaceholders = array(
      'datum' => $vandaag->format(),
      'datum_d_MMMM_yyyy' => $vandaag->format('d MMMM yyyy'),
      'user_naam' => $this->getUser()->getPersoon()->getNaam(),
      'user_telefoon' => $this->getUser()->getPersoon()->getTelefoon() ? $this->getUser()->getPersoon()->getTelefoon() : '-',
      'user_email' => $this->getUser()->getPersoon()->getEmail(),
      'pagebreak' => '<div style="page-break-before: always; margin-top: 80px;" />'
    );

    while ($rs->next())
    {
      $object = new $this->bestemmelingenClass();
      $object->hydrate($rs);

      // replace the placeholders
      $values = array_merge($object->fillPlaceholders(), $defaultPlaceholders);
      $values['onderwerp'] = BriefTemplatePeer::replacePlaceholders($onderwerp, $values);
      
      $brief = BriefTemplatePeer::replacePlaceholders($html, $values);

      $briefVerzonden = new BriefVerzonden();
      $briefVerzonden->setObjectClass($this->bestemmelingenClass);
      $briefVerzonden->setObjectId($object->getId());
      //$briefVerzonden->setBriefTemplateId($brief_template->getId());
      $briefVerzonden->setMedium(BriefverzondenPeer::MEDIUM_PRINT);
      $briefVerzonden->setAdres($object->getAdres());
      $briefVerzonden->setHtml($brief);
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
  