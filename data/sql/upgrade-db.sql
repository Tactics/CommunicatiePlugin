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
-- 3/07/2014
-- Taco Orens
--
-- Toelaten dat sommige templates bewerkbaar zijn
-- -----------------------------------------------------------------------------
ALTER TABLE brief_template ADD COLUMN bewerkbaar INT NOT NULL DEFAULT 1;

-- -----------------------------------------------------------------------------
-- 29/11/2017
-- Tom De Roo
--
-- Toelaten dat de weergave van sommige templates beveiligd kan zijn (= body niet weergeven in show detail, bijvoorbeeld)
-- -----------------------------------------------------------------------------
ALTER TABLE brief_template ADD weergave_beveiligd INT NOT NULL DEFAULT 0;
