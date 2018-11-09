<?php


/**
 * This class adds structure of 'brief_layout' table to 'propel' DatabaseMap object.
 *
 *
 *
 * These statically-built map classes are used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 * @package    plugins.ttCommunicatiePlugin.lib.model.map
 */
class BriefLayoutMapBuilder {

	/**
	 * The (dot-path) name of this class
	 */
	const CLASS_NAME = 'plugins.ttCommunicatiePlugin.lib.model.map.BriefLayoutMapBuilder';

	/**
	 * The database map.
	 */
	private $dbMap;

	/**
	 * Tells us if this DatabaseMapBuilder is built so that we
	 * don't have to re-build it every time.
	 *
	 * @return     boolean true if this DatabaseMapBuilder is built, false otherwise.
	 */
	public function isBuilt()
	{
		return ($this->dbMap !== null);
	}

	/**
	 * Gets the databasemap this map builder built.
	 *
	 * @return     the databasemap
	 */
	public function getDatabaseMap()
	{
		return $this->dbMap;
	}

	/**
	 * The doBuild() method builds the DatabaseMap
	 *
	 * @return     void
	 * @throws     PropelException
	 */
	public function doBuild()
	{
		$this->dbMap = Propel::getDatabaseMap('propel');

		$tMap = $this->dbMap->addTable('brief_layout');
		$tMap->setPhpName('BriefLayout');

		$tMap->setUseIdGenerator(true);

		$tMap->addPrimaryKey('ID', 'Id', 'int', CreoleTypes::INTEGER, true, null);

		$tMap->addColumn('CATEGORIE', 'Categorie', 'int', CreoleTypes::INTEGER, false, null);

		$tMap->addColumn('NAAM', 'Naam', 'string', CreoleTypes::VARCHAR, true, 255);

		$tMap->addColumn('PRINT_BESTAND', 'PrintBestand', 'string', CreoleTypes::VARCHAR, false, 100);

		$tMap->addColumn('MAIL_BESTAND', 'MailBestand', 'string', CreoleTypes::VARCHAR, false, 100);

		$tMap->addColumn('PRINT_STYLESHEETS', 'PrintStylesheets', 'string', CreoleTypes::VARCHAR, false, 255);

		$tMap->addColumn('MAIL_STYLESHEETS', 'MailStylesheets', 'string', CreoleTypes::VARCHAR, false, 255);

		$tMap->addColumn('VERTAALD', 'Vertaald', 'boolean', CreoleTypes::BOOLEAN, false, null);

		$tMap->addColumn('CREATED_BY', 'CreatedBy', 'int', CreoleTypes::INTEGER, false, null);

		$tMap->addColumn('UPDATED_BY', 'UpdatedBy', 'int', CreoleTypes::INTEGER, false, null);

		$tMap->addColumn('CREATED_AT', 'CreatedAt', 'int', CreoleTypes::TIMESTAMP, false, null);

		$tMap->addColumn('UPDATED_AT', 'UpdatedAt', 'int', CreoleTypes::TIMESTAMP, false, null);

	} // doBuild()

} // BriefLayoutMapBuilder
