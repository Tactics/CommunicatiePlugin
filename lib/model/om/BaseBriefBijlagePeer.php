<?php


abstract class BaseBriefBijlagePeer {

	
	const DATABASE_NAME = 'propel';

	
	const TABLE_NAME = 'brief_bijlage';

	
	const CLASS_DEFAULT = 'plugins.ttCommunicatiePlugin.lib.model.BriefBijlage';

	
	const NUM_COLUMNS = 8;

	
	const NUM_LAZY_LOAD_COLUMNS = 0;


	
	const ID = 'brief_bijlage.ID';

	
	const BRIEF_TEMPLATE_ID = 'brief_bijlage.BRIEF_TEMPLATE_ID';

	
	const BIJLAGE_NODE_ID = 'brief_bijlage.BIJLAGE_NODE_ID';

	
	const CULTURE = 'brief_bijlage.CULTURE';

	
	const CREATED_AT = 'brief_bijlage.CREATED_AT';

	
	const UPDATED_AT = 'brief_bijlage.UPDATED_AT';

	
	const CREATED_BY = 'brief_bijlage.CREATED_BY';

	
	const UPDATED_BY = 'brief_bijlage.UPDATED_BY';

	
	private static $phpNameMap = null;


	
	private static $fieldNames = array (
		BasePeer::TYPE_PHPNAME => array ('Id', 'BriefTemplateId', 'BijlageNodeId', 'Culture', 'CreatedAt', 'UpdatedAt', 'CreatedBy', 'UpdatedBy', ),
		BasePeer::TYPE_COLNAME => array (BriefBijlagePeer::ID, BriefBijlagePeer::BRIEF_TEMPLATE_ID, BriefBijlagePeer::BIJLAGE_NODE_ID, BriefBijlagePeer::CULTURE, BriefBijlagePeer::CREATED_AT, BriefBijlagePeer::UPDATED_AT, BriefBijlagePeer::CREATED_BY, BriefBijlagePeer::UPDATED_BY, ),
		BasePeer::TYPE_FIELDNAME => array ('id', 'brief_template_id', 'bijlage_node_id', 'culture', 'created_at', 'updated_at', 'created_by', 'updated_by', ),
		BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, )
	);

	
	private static $fieldKeys = array (
		BasePeer::TYPE_PHPNAME => array ('Id' => 0, 'BriefTemplateId' => 1, 'BijlageNodeId' => 2, 'Culture' => 3, 'CreatedAt' => 4, 'UpdatedAt' => 5, 'CreatedBy' => 6, 'UpdatedBy' => 7, ),
		BasePeer::TYPE_COLNAME => array (BriefBijlagePeer::ID => 0, BriefBijlagePeer::BRIEF_TEMPLATE_ID => 1, BriefBijlagePeer::BIJLAGE_NODE_ID => 2, BriefBijlagePeer::CULTURE => 3, BriefBijlagePeer::CREATED_AT => 4, BriefBijlagePeer::UPDATED_AT => 5, BriefBijlagePeer::CREATED_BY => 6, BriefBijlagePeer::UPDATED_BY => 7, ),
		BasePeer::TYPE_FIELDNAME => array ('id' => 0, 'brief_template_id' => 1, 'bijlage_node_id' => 2, 'culture' => 3, 'created_at' => 4, 'updated_at' => 5, 'created_by' => 6, 'updated_by' => 7, ),
		BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, )
	);

	
	public static function getMapBuilder()
	{
		include_once 'plugins/ttCommunicatiePlugin/lib/model/map/BriefBijlageMapBuilder.php';
		return BasePeer::getMapBuilder('plugins.ttCommunicatiePlugin.lib.model.map.BriefBijlageMapBuilder');
	}
	
	public static function getPhpNameMap()
	{
		if (self::$phpNameMap === null) {
			$map = BriefBijlagePeer::getTableMap();
			$columns = $map->getColumns();
			$nameMap = array();
			foreach ($columns as $column) {
				$nameMap[$column->getPhpName()] = $column->getColumnName();
			}
			self::$phpNameMap = $nameMap;
		}
		return self::$phpNameMap;
	}
	
	static public function translateFieldName($name, $fromType, $toType)
	{
		$toNames = self::getFieldNames($toType);
		$key = isset(self::$fieldKeys[$fromType][$name]) ? self::$fieldKeys[$fromType][$name] : null;
		if ($key === null) {
			throw new PropelException("'$name' could not be found in the field names of type '$fromType'. These are: " . print_r(self::$fieldKeys[$fromType], true));
		}
		return $toNames[$key];
	}

	

	static public function getFieldNames($type = BasePeer::TYPE_PHPNAME)
	{
		if (!array_key_exists($type, self::$fieldNames)) {
			throw new PropelException('Method getFieldNames() expects the parameter $type to be one of the class constants TYPE_PHPNAME, TYPE_COLNAME, TYPE_FIELDNAME, TYPE_NUM. ' . $type . ' was given.');
		}
		return self::$fieldNames[$type];
	}

	
	public static function alias($alias, $column)
	{
		return str_replace(BriefBijlagePeer::TABLE_NAME.'.', $alias.'.', $column);
	}

	
	public static function addSelectColumns(Criteria $criteria)
	{

		$criteria->addSelectColumn(BriefBijlagePeer::ID);

		$criteria->addSelectColumn(BriefBijlagePeer::BRIEF_TEMPLATE_ID);

		$criteria->addSelectColumn(BriefBijlagePeer::BIJLAGE_NODE_ID);

		$criteria->addSelectColumn(BriefBijlagePeer::CULTURE);

		$criteria->addSelectColumn(BriefBijlagePeer::CREATED_AT);

		$criteria->addSelectColumn(BriefBijlagePeer::UPDATED_AT);

		$criteria->addSelectColumn(BriefBijlagePeer::CREATED_BY);

		$criteria->addSelectColumn(BriefBijlagePeer::UPDATED_BY);

	}

	const COUNT = 'COUNT(brief_bijlage.ID)';
	const COUNT_DISTINCT = 'COUNT(DISTINCT brief_bijlage.ID)';

	
	public static function doCount(Criteria $criteria, $distinct = false, $con = null)
	{
				$criteria = clone $criteria;

				$criteria->clearSelectColumns()->clearOrderByColumns();
		if ($distinct || in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->addSelectColumn(BriefBijlagePeer::COUNT_DISTINCT);
		} else {
			$criteria->addSelectColumn(BriefBijlagePeer::COUNT);
		}

				foreach($criteria->getGroupByColumns() as $column)
		{
			$criteria->addSelectColumn($column);
		}

		$rs = BriefBijlagePeer::doSelectRS($criteria, $con);
		if ($rs->next()) {
			return $rs->getInt(1);
		} else {
						return 0;
		}
	}
	
	public static function doSelectOne(Criteria $criteria, $con = null)
	{
		$critcopy = clone $criteria;
		$critcopy->setLimit(1);
		$objects = BriefBijlagePeer::doSelect($critcopy, $con);
		if ($objects) {
			return $objects[0];
		}
		return null;
	}
	
	public static function doSelect(Criteria $criteria, $con = null)
	{
		return BriefBijlagePeer::populateObjects(BriefBijlagePeer::doSelectRS($criteria, $con));
	}
	
	public static function doSelectRS(Criteria $criteria, $con = null)
	{

    foreach (sfMixer::getCallables('BaseBriefBijlagePeer:addDoSelectRS:addDoSelectRS') as $callable)
    {
      call_user_func($callable, 'BaseBriefBijlagePeer', $criteria, $con);
    }


		if ($con === null) {
			$con = Propel::getConnection(self::DATABASE_NAME);
		}

		if (!$criteria->getSelectColumns()) {
			$criteria = clone $criteria;
			BriefBijlagePeer::addSelectColumns($criteria);
		}

				$criteria->setDbName(self::DATABASE_NAME);

						return BasePeer::doSelect($criteria, $con);
	}
	
	public static function populateObjects(ResultSet $rs)
	{
		$results = array();
	
				$cls = BriefBijlagePeer::getOMClass();
		$cls = Propel::import($cls);
				while($rs->next()) {
		
			$obj = new $cls();
			$obj->hydrate($rs);
			$results[] = $obj;
			
		}
		return $results;
	}

	
	public static function doCountJoinBriefTemplate(Criteria $criteria, $distinct = false, $con = null)
	{
				$criteria = clone $criteria;

				$criteria->clearSelectColumns()->clearOrderByColumns();
		if ($distinct || in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->addSelectColumn(BriefBijlagePeer::COUNT_DISTINCT);
		} else {
			$criteria->addSelectColumn(BriefBijlagePeer::COUNT);
		}

				foreach($criteria->getGroupByColumns() as $column)
		{
			$criteria->addSelectColumn($column);
		}

		$criteria->addJoin(BriefBijlagePeer::BRIEF_TEMPLATE_ID, BriefTemplatePeer::ID);

		$rs = BriefBijlagePeer::doSelectRS($criteria, $con);
		if ($rs->next()) {
			return $rs->getInt(1);
		} else {
						return 0;
		}
	}


	
	public static function doSelectJoinBriefTemplate(Criteria $c, $con = null)
	{
		$c = clone $c;

				if ($c->getDbName() == Propel::getDefaultDB()) {
			$c->setDbName(self::DATABASE_NAME);
		}

		BriefBijlagePeer::addSelectColumns($c);
		$startcol = (BriefBijlagePeer::NUM_COLUMNS - BriefBijlagePeer::NUM_LAZY_LOAD_COLUMNS) + 1;
		BriefTemplatePeer::addSelectColumns($c);

		$c->addJoin(BriefBijlagePeer::BRIEF_TEMPLATE_ID, BriefTemplatePeer::ID);
		$rs = BasePeer::doSelect($c, $con);
		$results = array();

		while($rs->next()) {

			$omClass = BriefBijlagePeer::getOMClass();

			$cls = Propel::import($omClass);
			$obj1 = new $cls();
			$obj1->hydrate($rs);

			$omClass = BriefTemplatePeer::getOMClass();

			$cls = Propel::import($omClass);
			$obj2 = new $cls();
			$obj2->hydrate($rs, $startcol);

			$newObject = true;
			foreach($results as $temp_obj1) {
				$temp_obj2 = $temp_obj1->getBriefTemplate(); 				if ($temp_obj2->getPrimaryKey() === $obj2->getPrimaryKey()) {
					$newObject = false;
										$temp_obj2->addBriefBijlage($obj1); 					break;
				}
			}
			if ($newObject) {
				$obj2->initBriefBijlages();
				$obj2->addBriefBijlage($obj1); 			}
			$results[] = $obj1;
		}
		return $results;
	}


	
	public static function doCountJoinAll(Criteria $criteria, $distinct = false, $con = null)
	{
		$criteria = clone $criteria;

				$criteria->clearSelectColumns()->clearOrderByColumns();
		if ($distinct || in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->addSelectColumn(BriefBijlagePeer::COUNT_DISTINCT);
		} else {
			$criteria->addSelectColumn(BriefBijlagePeer::COUNT);
		}

				foreach($criteria->getGroupByColumns() as $column)
		{
			$criteria->addSelectColumn($column);
		}

		$criteria->addJoin(BriefBijlagePeer::BRIEF_TEMPLATE_ID, BriefTemplatePeer::ID);

		$rs = BriefBijlagePeer::doSelectRS($criteria, $con);
		if ($rs->next()) {
			return $rs->getInt(1);
		} else {
						return 0;
		}
	}


	
	public static function doSelectJoinAll(Criteria $c, $con = null)
	{
		$c = clone $c;

				if ($c->getDbName() == Propel::getDefaultDB()) {
			$c->setDbName(self::DATABASE_NAME);
		}

		BriefBijlagePeer::addSelectColumns($c);
		$startcol2 = (BriefBijlagePeer::NUM_COLUMNS - BriefBijlagePeer::NUM_LAZY_LOAD_COLUMNS) + 1;

		BriefTemplatePeer::addSelectColumns($c);
		$startcol3 = $startcol2 + BriefTemplatePeer::NUM_COLUMNS;

		$c->addJoin(BriefBijlagePeer::BRIEF_TEMPLATE_ID, BriefTemplatePeer::ID);

		$rs = BasePeer::doSelect($c, $con);
		$results = array();

		while($rs->next()) {

			$omClass = BriefBijlagePeer::getOMClass();


			$cls = Propel::import($omClass);
			$obj1 = new $cls();
			$obj1->hydrate($rs);


					
			$omClass = BriefTemplatePeer::getOMClass();


			$cls = Propel::import($omClass);
			$obj2 = new $cls();
			$obj2->hydrate($rs, $startcol2);

			$newObject = true;
			for ($j=0, $resCount=count($results); $j < $resCount; $j++) {
				$temp_obj1 = $results[$j];
				$temp_obj2 = $temp_obj1->getBriefTemplate(); 				if ($temp_obj2->getPrimaryKey() === $obj2->getPrimaryKey()) {
					$newObject = false;
					$temp_obj2->addBriefBijlage($obj1); 					break;
				}
			}

			if ($newObject) {
				$obj2->initBriefBijlages();
				$obj2->addBriefBijlage($obj1);
			}

			$results[] = $obj1;
		}
		return $results;
	}

	
	public static function getTableMap()
	{
		return Propel::getDatabaseMap(self::DATABASE_NAME)->getTable(self::TABLE_NAME);
	}

	
	public static function getOMClass()
	{
		return BriefBijlagePeer::CLASS_DEFAULT;
	}

	
	public static function doInsert($values, $con = null)
	{

    foreach (sfMixer::getCallables('BaseBriefBijlagePeer:doInsert:pre') as $callable)
    {
      $ret = call_user_func($callable, 'BaseBriefBijlagePeer', $values, $con);
      if (false !== $ret)
      {
        return $ret;
      }
    }


		if ($con === null) {
			$con = Propel::getConnection(self::DATABASE_NAME);
		}

		if ($values instanceof Criteria) {
			$criteria = clone $values; 		} else {
			$criteria = $values->buildCriteria(); 		}

		$criteria->remove(BriefBijlagePeer::ID); 

				$criteria->setDbName(self::DATABASE_NAME);

		try {
									$con->begin();
			$pk = BasePeer::doInsert($criteria, $con);
			$con->commit();
		} catch(PropelException $e) {
			$con->rollback();
			throw $e;
		}

		
    foreach (sfMixer::getCallables('BaseBriefBijlagePeer:doInsert:post') as $callable)
    {
      call_user_func($callable, 'BaseBriefBijlagePeer', $values, $con, $pk);
    }

    return $pk;
	}

	
	public static function doUpdate($values, $con = null)
	{

    foreach (sfMixer::getCallables('BaseBriefBijlagePeer:doUpdate:pre') as $callable)
    {
      $ret = call_user_func($callable, 'BaseBriefBijlagePeer', $values, $con);
      if (false !== $ret)
      {
        return $ret;
      }
    }


		if ($con === null) {
			$con = Propel::getConnection(self::DATABASE_NAME);
		}

		$selectCriteria = new Criteria(self::DATABASE_NAME);

		if ($values instanceof Criteria) {
			$criteria = clone $values; 
			$comparison = $criteria->getComparison(BriefBijlagePeer::ID);
			$selectCriteria->add(BriefBijlagePeer::ID, $criteria->remove(BriefBijlagePeer::ID), $comparison);

		} else { 			$criteria = $values->buildCriteria(); 			$selectCriteria = $values->buildPkeyCriteria(); 		}

				$criteria->setDbName(self::DATABASE_NAME);

		$ret = BasePeer::doUpdate($selectCriteria, $criteria, $con);
	

    foreach (sfMixer::getCallables('BaseBriefBijlagePeer:doUpdate:post') as $callable)
    {
      call_user_func($callable, 'BaseBriefBijlagePeer', $values, $con, $ret);
    }

    return $ret;
  }

	
	public static function doDeleteAll($con = null)
	{
		if ($con === null) {
			$con = Propel::getConnection(self::DATABASE_NAME);
		}
		$affectedRows = 0; 		try {
									$con->begin();
			$affectedRows += BasePeer::doDeleteAll(BriefBijlagePeer::TABLE_NAME, $con);
			$con->commit();
			return $affectedRows;
		} catch (PropelException $e) {
			$con->rollback();
			throw $e;
		}
	}

	
	 public static function doDelete($values, $con = null)
	 {
		if ($con === null) {
			$con = Propel::getConnection(BriefBijlagePeer::DATABASE_NAME);
		}

		if ($values instanceof Criteria) {
			$criteria = clone $values; 		} elseif ($values instanceof BriefBijlage) {

			$criteria = $values->buildPkeyCriteria();
		} else {
						$criteria = new Criteria(self::DATABASE_NAME);
			$criteria->add(BriefBijlagePeer::ID, (array) $values, Criteria::IN);
		}

				$criteria->setDbName(self::DATABASE_NAME);

		$affectedRows = 0; 
		try {
									$con->begin();
			
			$affectedRows += BasePeer::doDelete($criteria, $con);
			$con->commit();
			return $affectedRows;
		} catch (PropelException $e) {
			$con->rollback();
			throw $e;
		}
	}

	
	public static function doValidate(BriefBijlage $obj, $cols = null)
	{
		$columns = array();

		if ($cols) {
			$dbMap = Propel::getDatabaseMap(BriefBijlagePeer::DATABASE_NAME);
			$tableMap = $dbMap->getTable(BriefBijlagePeer::TABLE_NAME);

			if (! is_array($cols)) {
				$cols = array($cols);
			}

			foreach($cols as $colName) {
				if ($tableMap->containsColumn($colName)) {
					$get = 'get' . $tableMap->getColumn($colName)->getPhpName();
					$columns[$colName] = $obj->$get();
				}
			}
		} else {

		}

		return BasePeer::doValidate(BriefBijlagePeer::DATABASE_NAME, BriefBijlagePeer::TABLE_NAME, $columns);
	}

	
	public static function retrieveByPK($pk, $con = null)
	{
		if ($con === null) {
			$con = Propel::getConnection(self::DATABASE_NAME);
		}

		$criteria = new Criteria(BriefBijlagePeer::DATABASE_NAME);

		$criteria->add(BriefBijlagePeer::ID, $pk);


		$v = BriefBijlagePeer::doSelect($criteria, $con);

		return !empty($v) > 0 ? $v[0] : null;
	}

	
	public static function retrieveByPKs($pks, $con = null)
	{
		if ($con === null) {
			$con = Propel::getConnection(self::DATABASE_NAME);
		}

		$objs = null;
		if (empty($pks)) {
			$objs = array();
		} else {
			$criteria = new Criteria();
			$criteria->add(BriefBijlagePeer::ID, $pks, Criteria::IN);
			$objs = BriefBijlagePeer::doSelect($criteria, $con);
		}
		return $objs;
	}

} 
if (Propel::isInit()) {
			try {
		BaseBriefBijlagePeer::getMapBuilder();
	} catch (Exception $e) {
		Propel::log('Could not initialize Peer: ' . $e->getMessage(), Propel::LOG_ERR);
	}
} else {
			require_once 'plugins/ttCommunicatiePlugin/lib/model/map/BriefBijlageMapBuilder.php';
	Propel::registerMapBuilder('plugins.ttCommunicatiePlugin.lib.model.map.BriefBijlageMapBuilder');
}
