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
-- 27/08/2013
-- Glenn Van Loock
--
-- bestemmeling toevoegen aan brief_verzonden
-- -----------------------------------------------------------------------------
CREATE TABLE `aanwezigheid`
(
	`inschrijving_id` INTEGER  NOT NULL,
	`activiteit_periode_id` INTEGER  NOT NULL,
	`aanwezig` INTEGER default 0,
	`betaald` INTEGER default 0,
	PRIMARY KEY (`inschrijving_id`,`activiteit_periode_id`),
	KEY `aanwezigheid_I_1`(`inschrijving_id`),
	KEY `aanwezigheid_I_2`(`activiteit_periode_id`),
	CONSTRAINT `fk_aanwezig_inschrijving`
		FOREIGN KEY (`inschrijving_id`)
		REFERENCES `inschrijving` (`id`),
	CONSTRAINT `fk_aanwezig_actPer`
		FOREIGN KEY (`activiteit_periode_id`)
		REFERENCES `activiteit_periode` (`id`)
)Engine=InnoDB;