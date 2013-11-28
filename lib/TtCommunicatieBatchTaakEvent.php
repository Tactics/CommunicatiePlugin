<?php

use Symfony\Component\EventDispatcher\Event;

class TtCommunicatieBatchTaakEvent extends Event
{
  protected $batch_taak;

  public function __construct(BatchTaak $batch_taak)
  {
    $this->batch_taak = $batch_taak;
  }

  public function getBatchTaak()
  {
    return $this->batch_taak;
  }
}