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
      $c->add(self::CATEGORIE, $user->getTtCommunicatieCategory(), Criteria::EQUAL);
    }

    return self::doSelect($c);
  }
}
