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
   * @return array[]BriefLayout
   */
  public static function getSorted()
  {
    $c = new Criteria();
    $c->addAscendingOrderByColumn(self::NAAM);

    return self::doSelect($c);
  }
}
