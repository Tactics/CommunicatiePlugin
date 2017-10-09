<?php



class BriefVerzondenBijlageMapBuilder {

	
	const CLASS_NAME = 'plugins.ttCommunicatiePlugin.lib.model.map.BriefVerzondenBijlageMapBuilder';

	
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

		$tMap = $this->dbMap->addTable('brief_verzonden_bijlage');
		$tMap->setPhpName('BriefVerzondenBijlage');

		$tMap->setUseIdGenerator(true);

		$tMap->addPrimaryKey('ID', 'Id', 'int', CreoleTypes::INTEGER, true, null);

		$tMap->addForeignKey('BRIEF_VERZONDEN_ID', 'BriefVerzondenId', 'int', CreoleTypes::INTEGER, 'brief_verzonden', 'ID', true, null);

		$tMap->addForeignKey('DMS_NODE_ID', 'DmsNodeId', 'int', CreoleTypes::INTEGER, 'dms_node', 'ID', true, null);

	} 
} 