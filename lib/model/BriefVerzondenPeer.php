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

  const WEERGAVE_BEVEILIGD_TEKST = 'De inhoud van deze brief/mail bevat gevoelige informatie en wordt niet getoond';

  /**
   * @return array
   */
  public static function getStatussen()
  {
    return array(self::STATUS_VERZONDEN => self::STATUS_VERZONDEN, self::STATUS_NT_VERZONDEN => self::STATUS_NT_VERZONDEN);
  }

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

  /**
   * @param BriefTemplate $briefTemplate
   */
  public static function cleanHtmlForWeergaveBeveiligd(BriefTemplate $briefTemplate)
  {
    set_time_limit(0);
    $sql = "UPDATE " . self::TABLE_NAME . " SET " . self::HTML . " = '" . self::WEERGAVE_BEVEILIGD_TEKST
      . "' WHERE " . self::BRIEF_TEMPLATE_ID . " = " . $briefTemplate->getId()
    ;
    myDbTools::executeSql($sql);
  }

  /**
   * @param $templateId
   * @param $class
   * @param $objectId
   * @return BriefVerzonden
   *
   */
  public static function retrieveByTemplateAndClassAndId($templateId, $class, $objectId)
  {
    $c = new Criteria();
    $c->add(BriefVerzondenPeer::BRIEF_TEMPLATE_ID, $templateId, Criteria::EQUAL);
    $c->add(BriefVerzondenPeer::OBJECT_CLASS, $class, Criteria::EQUAL);
    $c->add(BriefVerzondenPeer::OBJECT_ID, $objectId, Criteria::EQUAL);
    $c->addDescendingOrderByColumn(BriefVerzondenPeer::ID);
    return BriefVerzondenPeer::doSelectOne($c);
  }
}
