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
    
    $culture    = $object->getMailerCulture();
    $values     = $object->fillPlaceholders(null, $culture);
    // @todo isSysteemTemplate functie ?
    if ($this->getSysteemnaam())
    {
      $values = array_merge($values, $systeemvalues);
    }
    $email      = $object->getMailerRecipientMail();   
    
    $onderwerp   = BriefTemplatePeer::replacePlaceholders($this->getTranslatedOnderwerp($culture), $values);
    $html        = BriefTemplatePeer::replacePlaceholders($this->getTranslatedHtml($culture), $values);
    
    $headAndBody = $this->getBriefLayout()->getHeadAndBody('mail', $culture, $html);
    
    $brief = $headAndBody['head'] . $headAndBody['body'];
    
    // Mail versturen
    BerichtPeer::verstuurEmail($email, $brief, array(
      'onderwerp' => $onderwerp,
      'skip_template' => true
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
}
sfPropelBehavior::add('BriefTemplate', array('storage'));