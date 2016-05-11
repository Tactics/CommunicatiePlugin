<?php


/**
 * This class adds structure of 'brief_verzonden' table to 'propel' DatabaseMap object.
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
class BriefVerzondenMapBuilder {

	/**
	 * The (dot-path) name of this class
	 */
	const CLASS_NAME = 'plugins.ttCommunicatiePlugin.lib.model.map.BriefVerzondenMapBuilder';

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

		$tMap = $this->dbMap->addTable('brief_verzonden');
		$tMap->setPhpName('BriefVerzonden');

		$tMap->setUseIdGenerator(true);

		$tMap->addPrimaryKey('ID', 'Id', 'int', CreoleTypes::INTEGER, true, null);

		$tMap->addColumn('OBJECT_CLASS', 'ObjectClass', 'string', CreoleTypes::VARCHAR, true, 45);

		$tMap->addColumn('OBJECT_ID', 'ObjectId', 'int', CreoleTypes::INTEGER, true, null);

		$tMap->addColumn('OBJECT_CLASS_BESTEMMELING', 'ObjectClassBestemmeling', 'string', CreoleTypes::VARCHAR, false, 45);

		$tMap->addColumn('OBJECT_ID_BESTEMMELING', 'ObjectIdBestemmeling', 'int', CreoleTypes::INTEGER, false, null);

		$tMap->addForeignKey('BRIEF_TEMPLATE_ID', 'BriefTemplateId', 'int', CreoleTypes::INTEGER, 'brief_template', 'ID', false, null);

		$tMap->addColumn('ONDERWERP', 'Onderwerp', 'string', CreoleTypes::VARCHAR, false, 200);

		$tMap->addColumn('HTML', 'Html', 'string', CreoleTypes::LONGVARCHAR, false, null);

		$tMap->addColumn('MEDIUM', 'Medium', 'string', CreoleTypes::VARCHAR, false, 45);

		$tMap->addColumn('ADRES', 'Adres', 'string', CreoleTypes::VARCHAR, false, 1000);

		$tMap->addColumn('CC', 'Cc', 'string', CreoleTypes::VARCHAR, false, 1000);

		$tMap->addColumn('BCC', 'Bcc', 'string', CreoleTypes::VARCHAR, false, 1000);

		$tMap->addColumn('CUSTOM', 'Custom', 'boolean', CreoleTypes::BOOLEAN, true, null);

		$tMap->addColumn('CULTURE', 'Culture', 'string', CreoleTypes::VARCHAR, false, 45);

		$tMap->addColumn('STATUS', 'Status', 'string', CreoleTypes::VARCHAR, true, 45);

		$tMap->addColumn('UUID', 'Uuid', 'string', CreoleTypes::VARCHAR, false, 45);

		$tMap->addColumn('BOUNCE_MSG', 'BounceMsg', 'string', CreoleTypes::LONGVARCHAR, false, null);

		$tMap->addColumn('CREATED_BY', 'CreatedBy', 'int', CreoleTypes::INTEGER, false, null);

		$tMap->addColumn('UPDATED_BY', 'UpdatedBy', 'int', CreoleTypes::INTEGER, false, null);

		$tMap->addColumn('CREATED_AT', 'CreatedAt', 'int', CreoleTypes::TIMESTAMP, false, null);

		$tMap->addColumn('UPDATED_AT', 'UpdatedAt', 'int', CreoleTypes::TIMESTAMP, false, null);

	} // doBuild()

} // BriefVerzondenMapBuilder
