<?php

/**
 * Subclass for representing a row from the 'brief_layout' table.
 *
 * 
 *
 * @package plugins.ttCommunicatiePlugin.lib.model
 */ 
class BriefLayout extends BaseBriefLayout
{
  /**
   * geef de string waarde van dit object
   *
   * @return string
   */
  public function __toString()
  {
    return $this->getNaam() ? $this->getNaam() : '';
  }
}
