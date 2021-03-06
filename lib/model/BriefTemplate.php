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
   * @return int The number of successful recipients
   * @throws Swift_ConnectionException If sending fails for any reason.
   */
  public function sendMailToObject(iMailer $object, $options = array())
  {
    $systeemvalues = isset($options['systeemvalues']) ? $options['systeemvalues'] : array();
    $afzender = isset($options['afzender']) ? $options['afzender'] : false;
    $email = isset($options['mailto']) ? $options['mailto'] : $object->getMailerRecipientMail();

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
    $defaultPlaceholders = BriefTemplatePeer::getDefaultPlaceholders($object, true);

    $culture    = BriefTemplatePeer::calculateCulture($object);
    $values     = $object->fillPlaceholders(null, $culture);
    $values     = array_merge($defaultPlaceholders, $values, $systeemvalues);
    $onderwerp   = BriefTemplatePeer::replacePlaceholders($this->getTranslatedOnderwerp($culture), $values);
    $values['onderwerp'] = $onderwerp;
    
    $html        = $this->getTranslatedHtml($culture);
    $headAndBody = $this->getBriefLayout()->getHeadAndBody('mail', $culture, $html, true);

    $brief = $headAndBody['head'] . $headAndBody['body'];
    $brief = BriefTemplatePeer::parseForeachStatements($brief, $object, true, $systeemvalues);
    $brief = BriefTemplatePeer::parseIfStatements($brief, $object, true, $systeemvalues);
    $brief = BriefTemplatePeer::replacePlaceholders($brief, $values);

    // Attachments van deze brieftemplate toevoegen
    // PS: Ja, de spelling "attachements" is verkeerd, maar dat is ook zo in BerichtPeer, en het zou te costly  zijn om te gaan checken waar het overal fout is gebruikt
    $attachments = isset($options['attachements']) && is_array($options['attachements']) ? $options['attachements'] : array();

    $options = array(
      'onderwerp' => $onderwerp,
      'skip_template' => true,
      'cc' => (method_exists($object, 'getMailerRecipientCC') ? $object->getMailerRecipientCC() : array()),
      'bcc' => (method_exists($object, 'getMailerRecipientBCC') ? $object->getMailerRecipientBCC() : array()),
      'reply-to' => (method_exists($object, 'getMailerRecipientReplyTo') ? $object->getMailerRecipientReplyTo() : array()),
      'img_path' => sfConfig::get('sf_data_dir') . DIRECTORY_SEPARATOR . 'brieven' . DIRECTORY_SEPARATOR . 'layouts' . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR,
      'attachements' => array_merge($attachments, $this->getAttachments()),
    );

    // Enkel parameter meegeven indien gezet
    if($afzender) $options['afzender']  = $afzender;

    // Mail versturen
    $mailSent = BerichtPeer::verstuurEmail($email, $brief, $options);

    $bestemmeling = null;
    if (method_exists($object, 'getBestemmeling'))
    {
      $bestemmeling = $object->getBestemmeling();
      // indien email_to overschreven werd naar een ander email, mag de bestemmeling niet gezet worden
      if ($bestemmeling && method_exists($bestemmeling, 'getEmail') && ($bestemmeling->getEmail() != $email))
      {
        $bestemmeling = null;
      }
    }

    // Mail loggen
    $briefVerzonden = new BriefVerzonden();
    $briefVerzonden->setObjectClass($cName);
    $briefVerzonden->setObjectId($object->getId());
    $briefVerzonden->setObjectClassBestemmeling(isset($bestemmeling) ? get_class($bestemmeling) : null);
    $briefVerzonden->setObjectIdBestemmeling(isset($bestemmeling) ? $bestemmeling->getId() : null);
    $briefVerzonden->setBriefTemplateId($this->getId());
    $briefVerzonden->setMedium(BriefverzondenPeer::MEDIUM_MAIL);
    $briefVerzonden->setAdres($email);
    $briefVerzonden->setOnderwerp($onderwerp);
    $briefVerzonden->setCulture($culture);
    $briefVerzonden->setStatus($mailSent ? Bericht::STATUS_VERZONDEN : Bericht::STATUS_MISLUKT);
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
}
sfPropelBehavior::add('BriefTemplate', array('storage'));
sfPropelBehavior::add('BriefTemplate', array('updater_loggable'));
if (sfConfig::get('sf_communicatie_enable_event_log')) sfPropelBehavior::add('BriefTemplate', array('event_log'));
