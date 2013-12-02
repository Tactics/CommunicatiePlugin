<?php

/**
 * Subclass for representing a row from the 'batch_taak' table.
 *
 * 
 *
 * @package plugins.ttCommunicatiePlugin.lib.model
 */ 
class BatchTaak extends BaseBatchTaak
{
  public function __toString()
  {
    return '('.$this->getId().') '.$this->getAantal().'x '.$this->getObjectClass();
  }
}
