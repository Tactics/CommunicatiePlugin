<?php

/**
 * Subclass for performing query and update operations on the 'brief_verzonden' table.
 *
 * 
 *
 * @package plugins.ttCommunicatiePlugin.lib.model
 */ 
class BriefVerzondenPeer extends BaseBriefVerzondenPeer
{
  const MEDIUM_PRINT = 'print';
  const MEDIUM_MAIL = 'e-mail';
  const MEDIUM_PERSOONLIJK = 'persoonlijk';
  const MEDIUM_TELEFOON = 'telefoon';
  
  const STATUS_VERZONDEN = 'verzonden';
  const STATUS_NT_VERZONDEN = 'niet verzonden';
  const STATUS_BOUNCED = 'bounced';

  /**
   * Geeft de verschillende media weer in een formaat voor
   * de options_for_select functie.
   *
   * @return array
   */
  public static function getMediaOptionsForSelect()
  {
    return array(
      self::MEDIUM_PRINT => self::MEDIUM_PRINT,
      self::MEDIUM_MAIL => self::MEDIUM_MAIL,
      self::MEDIUM_PERSOONLIJK => self::MEDIUM_PERSOONLIJK,
      self::MEDIUM_TELEFOON => self::MEDIUM_TELEFOON
    );
  }
}
