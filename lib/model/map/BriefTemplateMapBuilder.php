<?php



class BriefTemplateMapBuilder {

	
	const CLASS_NAME = 'plugins.ttCommunicatiePlugin.lib.model.map.BriefTemplateMapBuilder';

	
	private $dbMap;

	
	public function isBuilt()
	{
		return ($this->dbMap !== null);
	}

	
	public function getDatabaseMap()
	{
		return $this->dbMap;
	}

	
	public function doBuild()
	{
		$this->dbMap = Propel::getDatabaseMap('propel');

		$tMap = $this->dbMap->addTable('brief_template');
		$tMap->setPhpName('BriefTemplate');

		$tMap->setUseIdGenerator(true);

		$tMap->addPrimaryKey('ID', 'Id', 'int', CreoleTypes::INTEGER, true, null);

		$tMap->addColumn('CATEGORIE', 'Categorie', 'int', CreoleTypes::INTEGER, false, null);

		$tMap->addForeignKey('BRIEF_LAYOUT_ID', 'BriefLayoutId', 'int', CreoleTypes::INTEGER, 'brief_layout', 'ID', true, null);

		$tMap->addColumn('ONDERWERP', 'Onderwerp', 'string', CreoleTypes::VARCHAR, false, 200);

		$tMap->addColumn('NAAM', 'Naam', 'string', CreoleTypes::VARCHAR, true, 45);

		$tMap->addColumn('TYPE', 'Type', 'string', CreoleTypes::VARCHAR, true, 45);

		$tMap->addColumn('BESTEMMELING_CLASSES', 'BestemmelingClasses', 'string', CreoleTypes::VARCHAR, false, 1000);

		$tMap->addColumn('HTML', 'Html', 'string', CreoleTypes::LONGVARCHAR, false, null);

		$tMap->addColumn('EENMALIG_VERSTUREN', 'EenmaligVersturen', 'boolean', CreoleTypes::BOOLEAN, true, null);

		$tMap->addColumn('SYSTEEMNAAM', 'Systeemnaam', 'string', CreoleTypes::VARCHAR, false, 45);

		$tMap->addColumn('SYSTEEMPLACEHOLDERS', 'Systeemplaceholders', 'string', CreoleTypes::VARCHAR, false, 1000);

		$tMap->addColumn('GEARCHIVEERD', 'Gearchiveerd', 'int', CreoleTypes::INTEGER, true, null);

		$tMap->addColumn('CREATED_BY', 'CreatedBy', 'int', CreoleTypes::INTEGER, false, null);

		$tMap->addColumn('UPDATED_BY', 'UpdatedBy', 'int', CreoleTypes::INTEGER, false, null);

		$tMap->addColumn('CREATED_AT', 'CreatedAt', 'int', CreoleTypes::TIMESTAMP, false, null);

		$tMap->addColumn('UPDATED_AT', 'UpdatedAt', 'int', CreoleTypes::TIMESTAMP, false, null);

	} 
} 