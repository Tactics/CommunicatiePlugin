-- -----------------------------------------------------------------------------
-- 20/08/2012
-- Joris Hontelé
--
-- mogelijkheid archiveren van brief_templates
-- linken van brief_templates aan een categorie
-- -----------------------------------------------------------------------------
ALTER TABLE `brief_template` ADD `gearchiveerd` INT NOT NULL DEFAULT 0 AFTER `systeemplaceholders`;
ALTER TABLE `brief_template` ADD `categorie` INT NULL AFTER `id`;
ALTER TABLE `brief_layout` ADD `categorie` INT NULL AFTER `id`;
ALTER TABLE `brief_layout` CHANGE `id` `id` INT( 11 ) NOT NULL AUTO_INCREMENT;

-- -----------------------------------------------------------------------------
-- 8/11/2012
-- Joris Hontelé
--
-- bestemmeling toevoegen aan brief_verzonden
-- -----------------------------------------------------------------------------
ALTER TABLE brief_verzonden ADD object_class_bestemmeling VARCHAR(45) NULL DEFAULT NULL AFTER object_id;
ALTER TABLE brief_verzonden ADD object_id_bestemmeling INT NULL DEFAULT NULL AFTER object_class_bestemmeling;

-- -----------------------------------------------------------------------------
-- 18/07/2013
-- Joris Hontelé
--
-- cc en bcc toevoegen aan brief_verzonden
-- -----------------------------------------------------------------------------
ALTER TABLE `brief_verzonden` 
ADD `cc` VARCHAR( 1000 ) NULL DEFAULT NULL AFTER `adres` ,
ADD `bcc` VARCHAR( 1000 ) NULL DEFAULT NULL AFTER `cc` ;

-- -----------------------------------------------------------------------------
-- 19/11/2013
-- Glenn Van Loock
--
-- is_publiciteit toevoegen aan template
-- -----------------------------------------------------------------------------
ALTER TABLE `brief_template` ADD `is_publiciteit` INT NOT NULL DEFAULT '0' AFTER `eenmalig_versturen`;

-- -----------------------------------------------------------------------------
-- 02/12/2013
-- Joris Hontelé
--
-- lokale images toevoegen aan briefsjablonen
-- -----------------------------------------------------------------------------
!!!!folder aanmaken: brief_templates in de web/images dir!!!