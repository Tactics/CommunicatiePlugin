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
      $this->getRequest()->setError('bestemmelingen', 'Gelieve minstens één mogelijke bestemmeling in te geven.');
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
    $this->bestemmelingenPeer = $this->bestemmelingenClass . 'Peer';
    $this->brief_template = BriefTemplatePeer::retrieveByPK($this->getRequestParameter('template_id'));
  }

  /**
   * ajax load van de html van een template
   */
  public function executeTemplateHtml()
  {
    $this->preExecuteVersturen();

    if ($this->brief_template->getEenmaligVersturen())
    {
      // Aantal dat de brief reeds kreeg
      $this->criteria->add(BriefVerzondenPeer::BRIEF_TEMPLATE_ID, $this->brief_template->getId());
      $this->criteria->addJoin(BriefVerzondenPeer::OBJECT_ID, eval('return ' . $this->bestemmelingenPeer . '::ID;'));
      $this->criteria->add(BriefVerzondenPeer::OBJECT_CLASS, $this->bestemmelingenClass);

      $rs = $this->getRs();
    }

    echo json_encode(array(
      'html' => $this->brief_template->getHtml(),
      'eenmalig' => $this->brief_template->getEenmaligVersturen() ? ('ja (reeds ontvangen: ' . $rs->getRecordCount() . ')')  : 'nee',
      'onderwerp' => $this->brief_template->getOnderwerp()
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

    $c = new Criteria();
    $c->add(BriefTemplatePeer::BESTEMMELING_CLASSES, '%|' . $this->bestemmelingenClass . '|%', Criteria::LIKE);
    $this->brief_templates = BriefTemplatePeer::getSorted($c);

    $this->brief_template = reset($this->brief_templates);
    $this->onderwerp = $this->brief_template->getOnderwerp();
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
   * Afdrukken of e-mail verzenden
   */
  public function executePrint()
  {
    $this->preExecuteVersturen();

    $this->voorbeeld = (stripos($this->getRequestParameter('commit'), 'voorbeeld') !== false);
    $this->emailverzenden = (stripos($this->getRequestParameter('commit'), 'mail') !== false);

    // Voorbeeld is altijd op papier
    if ($this->voorbeeld)
    {
      $this->viaemail = false;
    }
    else
    {
      $this->viaemail = ($this->getRequestParameter('verzenden_via', false) == 'ja');
    }
    
    $this->onderwerp = $this->getRequestParameter('onderwerp');
    $this->html = $this->getRequestParameter('html');

    $this->templateFolder = SF_ROOT_DIR . DIRECTORY_SEPARATOR . 'files' . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR;

    $this->brief_template = BriefTemplatePeer::retrieveByPK($this->getRequestParameter('template_id'));    
    $this->forward404Unless($this->brief_template);

    $layout = $this->brief_template->getBriefLayout();

    if ($this->emailverzenden)
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
      @$stylesheet_css = $this->get_include_contents($stylesheet_bestand);
      if ($stylesheet_css)
      {
        $css .= $stylesheet_css;
      }
    }    

    // Haal layout op en pas deze toe
    $html_layout = $this->get_include_contents($layout_dir . $layout_bestand);

    if ($html_layout)
    {
      Misc::use_helper('Url');      
      $html = strtr($html_layout, array(
        '%stylesheet%' => $css,
        '%body%' => $this->html,
        '%image_dir%' => url_for('brief/showImage') . '/image/'
      ));
    }

    // Knip het resulterende document op in stukken zodat we meerdere
    // brieven kunnen afdrukken zonder foute HTML te genereren (meerdere HEAD / BODY blokken)

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

    $this->bericht_body = substr($html, $endOpenBodyTag + 1, $endBodyTag - $endOpenBodyTag - 1);


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

    $this->bericht_head = substr($html, $endOpenHeadTag + 1, $endHeadTag - $endOpenHeadTag - 1);

    if ($this->voorbeeld)
    {
      $this->criteria->setLimit(1);
    }

    $this->rs = $this->getRs();

    $vandaag = new myDate();

    $this->defaultPlaceholders = array(
      'datum' => $vandaag->format(),
      'user_naam' => $this->getUser()->getPersoon()->getNaam(),
      'user_telefoon' => $this->getUser()->getPersoon()->getTelefoon() ? $this->getUser()->getPersoon()->getTelefoon() : '-',
      'user_email' => $this->getUser()->getPersoon()->getEmail(),
    );

    if ($this->emailverzenden)
    {
      while ($this->rs->next())
      {
        $object = new $this->bestemmelingenClass();
        $object->hydrate($this->rs);

        // sommige brieven mogen slechts eenmalig naar een object_class/id gestuurd worden
        if ($this->brief_template->getEenmaligVersturen() && $this->brief_template->ReedsVerstuurdNaar($this->bestemmelingenClass, $object->getId()))
        {
          continue;
        }

        if ($object->getMailerPrefersEmail())
        {
          // replace the placeholders
          $values = array_merge($object->fillPlaceholders(), $this->defaultPlaceholders);
          $onderwerp = BriefTemplatePeer::replacePlaceholders($this->onderwerp, $values);
          $values['onderwerp'] = $onderwerp;

          $brief = BriefTemplatePeer::replacePlaceholders($this->bericht_body, $values);
          $brief = $this->bericht_head . $brief;
          $email = $object->getMailerRecipientMail();

          $attachements = array();

          if ($this->brief_template->getBriefBijlages())
          {
            foreach ($this->brief_template->getBriefBijlages() as $briefBijlage)
            {
              $node = DmsNodePeer::retrieveByPk($briefBijlage->getBijlageNodeId());

              if (! $node)
              {
                continue;
              }

              $tmpFile = tempnam(sys_get_temp_dir(), 'hrm_brief_bijlage');
              $node->saveToFile($tmpFile);

              $attachements[$node->getName()] = $tmpFile;
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
              $tmpFile = tempnam(sys_get_temp_dir(), 'hrm_brief_bijlage');
              move_uploaded_file($fileInfo['tmp_name'], $tmpFile);
              $attachements[$fileInfo['name']] = $tmpFile;
            }
          }
          
          try {
            BerichtPeer::verstuurEmail($email, $brief, array(
              'onderwerp' => $onderwerp,
              'skip_template' => true,
              'afzender' => 'noreply@stad.antwerpen.be',
              'attachements' => $attachements
            ));

            echo 'Bericht verzonden naar : ' . $email . '<br/>';

            // Log de brief
            $briefVerzonden = new BriefVerzonden();
            $briefVerzonden->setObjectClass($this->bestemmelingenClass);
            $briefVerzonden->setObjectId($object->getId());
            $briefVerzonden->setBriefTemplateId($this->brief_template->getId());
            $briefVerzonden->setMedium(BriefverzondenPeer::MEDIUM_MAIL);
            $briefVerzonden->setAdres($email);
            $briefVerzonden->setOnderwerp($onderwerp);
            $briefVerzonden->setHtml($brief);
            $briefVerzonden->save();
          }
          catch(sfException $e)
          {
            echo '<font color=red>E-mail kon niet verzonden worden naar ' . $email . '</font><br/>';
          }

          foreach($attachements as $tmpFile)
          {
            unlink($tmpFile);
          }
        }
      }

      echo '<br/><br/>Einde verzendlijst<br/><br/>';
      echo '<a href="#" onclick="window.close();">Klik hier om het venster te sluiten</a>';
      exit();
    }
    else
    {
      $this->type = 'brieven ' . strtolower($this->brief_template->getNaam());
      $this->setLayout(false);
      $this->getResponse()->setTitle($this->voorbeeld ? 'Voorbeeld afdrukken' : 'Afdrukken');
    }
    
  }

  /**
   * Bevestigen van afdruk
   */
  public function executeBevestigAfdrukken()
  {
    $this->preExecuteVersturen();

  	$brief_template = BriefTemplatePeer::retrieveByPK($this->getRequestParameter('template_id'));
    $html = $this->getRequestParameter('html');
    $onderwerp = $this->getRequestParameter('onderwerp');
    $object_ids = explode(',', $this->getRequestParameter('object_ids'));

    $c = new Criteria();
    $c->add(eval('return ' . $this->bestemmelingenPeer . '::ID;'), $object_ids, Criteria::IN);
    $rs = eval('return ' . $this->bestemmelingenPeer . '::doSelectRs($c);');

    $vandaag = new myDate();

    $defaultPlaceholders = array(
      'datum' => $vandaag->format(),
      'user_naam' => $this->getUser()->getPersoon()->getNaam(),
      'user_telefoon' => $this->getUser()->getPersoon()->getTelefoon() ? $this->getUser()->getPersoon()->getTelefoon() : '-',
      'user_email' => $this->getUser()->getPersoon()->getEmail(),
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
      $briefVerzonden->setBriefTemplateId($brief_template->getId());
      $briefVerzonden->setMedium(BriefverzondenPeer::MEDIUM_PRINT);
      $briefVerzonden->setAdres($object->getAdres());
      $briefVerzonden->setHtml($brief);
      $briefVerzonden->setOnderwerp($values['onderwerp']);
      $briefVerzonden->save();
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
   * Geeft details van Ã©Ã©n verzonden brief weer
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
  