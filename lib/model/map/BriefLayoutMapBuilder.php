<?php



class BriefLayoutMapBuilder {

	
	const CLASS_NAME = 'plugins.ttCommunicatiePlugin.lib.model.map.BriefLayoutMapBuilder';

	
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

		$tMap = $this->dbMap->addTable('brief_layout');
		$tMap->setPhpName('BriefLayout');

		$tMap->setUseIdGenerator(false);

		$tMap->addPrimaryKey('ID', 'Id', 'int', CreoleTypes::INTEGER, true, null);

		$tMap->addColumn('NAAM', 'Naam', 'string', CreoleTypes::VARCHAR, true, 255);

		$tMap->addColumn('PRINT_BESTAND', 'PrintBestand', 'string', CreoleTypes::VARCHAR, false, 100);

		$tMap->addColumn('MAIL_BESTAND', 'MailBestand', 'string', CreoleTypes::VARCHAR, false, 100);

		$tMap->addColumn('PRINT_STYLESHEETS', 'PrintStylesheets', 'string', CreoleTypes::VARCHAR, false, 255);

		$tMap->addColumn('MAIL_STYLESHEETS', 'MailStylesheets', 'string', CreoleTypes::VARCHAR, false, 255);

		$tMap->addColumn('CREATED_BY', 'CreatedBy', 'int', CreoleTypes::INTEGER, false, null);

		$tMap->addColumn('UPDATED_BY', 'UpdatedBy', 'int', CreoleTypes::INTEGER, false, null);

		$tMap->addColumn('CREATED_AT', 'CreatedAt', 'int', CreoleTypes::TIMESTAMP, false, null);

		$tMap->addColumn('UPDATED_AT', 'UpdatedAt', 'int', CreoleTypes::TIMESTAMP, false, null);

	} 
} 