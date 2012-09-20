<?php

/**
 * Subclass for performing query and update operations on the 'brief_layout' table.
 *
 * 
 *
 * @package plugins.ttCommunicatiePlugin.lib.model
 */ 
class BriefLayoutPeer extends BaseBriefLayoutPeer
{
  /**
   * Geeft alle brieflayouts terug
   *
   * @param myUser $user
   * 
   * @return array[]BriefLayout
   */
  public static function getSorted($c = null)
  {
    if (!$c)
    {
      $c = new Criteria();  
    }
    
    $c->addAscendingOrderByColumn(self::NAAM);
    
    // categories enabled?
    if (sfConfig::get('sf_communicatie_enable_categories', false))
    {
      $user = sfContext::getInstance()->getUser();
      $catCton = $c->getNewCriterion(self::CATEGORIE, $user->getTtCommunicatieCategory(), Criteria::EQUAL);
      $catCton->addOr($c->getNewCriterion(self::CATEGORIE, null, Criteria::ISNULL)); // generieke
      $c->add($catCton);
    }

    return self::doSelect($c);
  }
}
