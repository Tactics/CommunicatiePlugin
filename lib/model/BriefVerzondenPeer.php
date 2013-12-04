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
   *
   * @param BriefTemplate $brief_template
   * @param ttCommunicatieBestemmeling $bestemmeling
   * @return \BriefVerzonden
   */
  public static function createEmail(BriefTemplate $brief_template, ttCommunicatieBestemmeling $bestemmeling)
  {
    $cultureBrieven = $brief_template->getCultureBrieven();
    $culture = BriefTemplatePeer::calculateCulture($bestemmeling);

    // parse If statements
    $cultureBrieven[$culture]['body'] = BriefTemplatePeer::parseForeachStatements($cultureBrieven[$culture]['body'], $bestemmeling, true);
    $cultureBrieven[$culture]['body'] = BriefTemplatePeer::parseIfStatements($cultureBrieven[$culture]['body'], $bestemmeling, true);

    // replace placeholders
    $defaultPlaceholders = BriefTemplatePeer::getDefaultPlaceholders($bestemmeling, true, true, true);
    if (!$brief_template->getIsPubliciteit() && isset($defaultPlaceholders['uitschrijven']))
    {
      unset($defaultPlaceholders['uitschrijven']);
    }

    $cultureBrieven = BriefTemplatePeer::replacePlaceholdersFromCultureBrieven($cultureBrieven, $bestemmeling, $defaultPlaceholders);
    $head = $cultureBrieven[$culture]['head'];
    $onderwerp = $cultureBrieven[$culture]['onderwerp'];
    $body = $cultureBrieven[$culture]['body'];
    $brief = $head . $body;


    $briefVerzonden = new BriefVerzonden();
    // object dat verzonden wordt bv factuur
    $object = $bestemmeling->getObject();
    $briefVerzonden->setObjectClass(get_class($object));
    $briefVerzonden->setObjectId($object->getId());
    // eindbestemmeling naar waar effectief verzonden wordt bv debiteurr
    $briefVerzonden->setObjectClassBestemmeling($bestemmeling->getObjectClass());
    $briefVerzonden->setObjectIdBestemmeling($bestemmeling->getObjectId());
    $briefVerzonden->setBriefTemplateId($brief_template->getId());
    $briefVerzonden->setMedium(BriefverzondenPeer::MEDIUM_MAIL);

    $briefVerzonden->setAdres($bestemmeling->getEmailTo());
    $cc = $bestemmeling->getEmailCc();
    $briefVerzonden->setCc(!empty($cc) ? $bestemmeling->getEmailCc(true) : null);
    $bcc = $bestemmeling->getEmailBcc();
    $briefVerzonden->setBcc(!empty($bcc) ? $bestemmeling->getEmailBcc(true) : null);
    $briefVerzonden->setOnderwerp($onderwerp);
    $briefVerzonden->setCulture($culture);
    $briefVerzonden->setHtml(BriefTemplatePeer::clearPlaceholders($brief));
    $briefVerzonden->setStatus(BriefVerzondenPeer::STATUS_NT_VERZONDEN);
    $briefVerzonden->save();

    return $briefVerzonden;
  }
}
