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
}
sfPropelBehavior::add('BriefTemplate', array('storage'));