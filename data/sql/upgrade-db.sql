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