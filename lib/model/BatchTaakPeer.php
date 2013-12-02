<?php

/**
 * Subclass for performing query and update operations on the 'batch_taak' table.
 *
 * 
 *
 * @package plugins.ttCommunicatiePlugin.lib.model
 */ 
class BatchTaakPeer extends BaseBatchTaakPeer
{
  const STATUS_WACHTRIJ = 'in wachtrij';
  const STATUS_PAUZE = 'gepauzeerd';
  const STATUS_VERZENDING = 'wordt verzonden';
  const STATUS_GEANNULEERD = 'geannuleerd';

  public static function getOptionsForSelect()
  {
    return array(
      self::STATUS_WACHTRIJ => self::STATUS_WACHTRIJ,
      self::STATUS_PAUZE => self::STATUS_PAUZE,
      self::STATUS_VERZENDING => self::STATUS_VERZENDING,
      self::STATUS_GEANNULEERD => self::STATUS_GEANNULEERD,
    );
  }
}
