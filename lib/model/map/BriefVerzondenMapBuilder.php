<?php



class BriefVerzondenMapBuilder {

	
	const CLASS_NAME = 'plugins.ttCommunicatiePlugin.lib.model.map.BriefVerzondenMapBuilder';

	
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

		$tMap->addColumn('CREATED_BY', 'CreatedBy', 'int', CreoleTypes::INTEGER, false, null);

		$tMap->addColumn('UPDATED_BY', 'UpdatedBy', 'int', CreoleTypes::INTEGER, false, null);

		$tMap->addColumn('CREATED_AT', 'CreatedAt', 'int', CreoleTypes::TIMESTAMP, false, null);

		$tMap->addColumn('UPDATED_AT', 'UpdatedAt', 'int', CreoleTypes::TIMESTAMP, false, null);

	} 
} 