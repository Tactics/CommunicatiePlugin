<?php



class BriefBijlageMapBuilder {

	
	const CLASS_NAME = 'plugins.ttCommunicatiePlugin.lib.model.map.BriefBijlageMapBuilder';

	
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

		$tMap = $this->dbMap->addTable('brief_bijlage');
		$tMap->setPhpName('BriefBijlage');

		$tMap->setUseIdGenerator(true);

		$tMap->addPrimaryKey('ID', 'Id', 'int', CreoleTypes::INTEGER, true, null);

		$tMap->addForeignKey('BRIEF_TEMPLATE_ID', 'BriefTemplateId', 'int', CreoleTypes::INTEGER, 'brief_template', 'ID', true, null);

		$tMap->addColumn('BIJLAGE_NODE_ID', 'BijlageNodeId', 'int', CreoleTypes::INTEGER, false, null);

		$tMap->addColumn('CULTURE', 'Culture', 'string', CreoleTypes::VARCHAR, false, 45);

		$tMap->addColumn('CREATED_AT', 'CreatedAt', 'int', CreoleTypes::TIMESTAMP, false, null);

		$tMap->addColumn('UPDATED_AT', 'UpdatedAt', 'int', CreoleTypes::TIMESTAMP, false, null);

		$tMap->addColumn('CREATED_BY', 'CreatedBy', 'int', CreoleTypes::INTEGER, false, null);

		$tMap->addColumn('UPDATED_BY', 'UpdatedBy', 'int', CreoleTypes::INTEGER, false, null);

	} 
} 