<?php


abstract class BaseBriefVerzondenPeer {

	
	const DATABASE_NAME = 'propel';

	
	const TABLE_NAME = 'brief_verzonden';

	
	const CLASS_DEFAULT = 'plugins.ttCommunicatiePlugin.lib.model.BriefVerzonden';

	
	const NUM_COLUMNS = 14;

	
	const NUM_LAZY_LOAD_COLUMNS = 0;


	
	const ID = 'brief_verzonden.ID';

	
	const OBJECT_CLASS = 'brief_verzonden.OBJECT_CLASS';

	
	const OBJECT_ID = 'brief_verzonden.OBJECT_ID';

	
	const BRIEF_TEMPLATE_ID = 'brief_verzonden.BRIEF_TEMPLATE_ID';

	
	const ONDERWERP = 'brief_verzonden.ONDERWERP';

	
	const HTML = 'brief_verzonden.HTML';

	
	const MEDIUM = 'brief_verzonden.MEDIUM';

	
	const ADRES = 'brief_verzonden.ADRES';

	
	const CUSTOM = 'brief_verzonden.CUSTOM';

	
	const TAAL = 'brief_verzonden.TAAL';

	
	const CREATED_BY = 'brief_verzonden.CREATED_BY';

	
	const UPDATED_BY = 'brief_verzonden.UPDATED_BY';

	
	const CREATED_AT = 'brief_verzonden.CREATED_AT';

	
	const UPDATED_AT = 'brief_verzonden.UPDATED_AT';

	
	private static $phpNameMap = null;


	
	private static $fieldNames = array (
		BasePeer::TYPE_PHPNAME => array ('Id', 'ObjectClass', 'ObjectId', 'BriefTemplateId', 'Onderwerp', 'Html', 'Medium', 'Adres', 'Custom', 'Taal', 'CreatedBy', 'UpdatedBy', 'CreatedAt', 'UpdatedAt', ),
		BasePeer::TYPE_COLNAME => array (BriefVerzondenPeer::ID, BriefVerzondenPeer::OBJECT_CLASS, BriefVerzondenPeer::OBJECT_ID, BriefVerzondenPeer::BRIEF_TEMPLATE_ID, BriefVerzondenPeer::ONDERWERP, BriefVerzondenPeer::HTML, BriefVerzondenPeer::MEDIUM, BriefVerzondenPeer::ADRES, BriefVerzondenPeer::CUSTOM, BriefVerzondenPeer::TAAL, BriefVerzondenPeer::CREATED_BY, BriefVerzondenPeer::UPDATED_BY, BriefVerzondenPeer::CREATED_AT, BriefVerzondenPeer::UPDATED_AT, ),
		BasePeer::TYPE_FIELDNAME => array ('id', 'object_class', 'object_id', 'brief_template_id', 'onderwerp', 'html', 'medium', 'adres', 'custom', 'taal', 'created_by', 'updated_by', 'created_at', 'updated_at', ),
		BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, )
	);

	
	private static $fieldKeys = array (
		BasePeer::TYPE_PHPNAME => array ('Id' => 0, 'ObjectClass' => 1, 'ObjectId' => 2, 'BriefTemplateId' => 3, 'Onderwerp' => 4, 'Html' => 5, 'Medium' => 6, 'Adres' => 7, 'Custom' => 8, 'Taal' => 9, 'CreatedBy' => 10, 'UpdatedBy' => 11, 'CreatedAt' => 12, 'UpdatedAt' => 13, ),
		BasePeer::TYPE_COLNAME => array (BriefVerzondenPeer::ID => 0, BriefVerzondenPeer::OBJECT_CLASS => 1, BriefVerzondenPeer::OBJECT_ID => 2, BriefVerzondenPeer::BRIEF_TEMPLATE_ID => 3, BriefVerzondenPeer::ONDERWERP => 4, BriefVerzondenPeer::HTML => 5, BriefVerzondenPeer::MEDIUM => 6, BriefVerzondenPeer::ADRES => 7, BriefVerzondenPeer::CUSTOM => 8, BriefVerzondenPeer::TAAL => 9, BriefVerzondenPeer::CREATED_BY => 10, BriefVerzondenPeer::UPDATED_BY => 11, BriefVerzondenPeer::CREATED_AT => 12, BriefVerzondenPeer::UPDATED_AT => 13, ),
		BasePeer::TYPE_FIELDNAME => array ('id' => 0, 'object_class' => 1, 'object_id' => 2, 'brief_template_id' => 3, 'onderwerp' => 4, 'html' => 5, 'medium' => 6, 'adres' => 7, 'custom' => 8, 'taal' => 9, 'created_by' => 10, 'updated_by' => 11, 'created_at' => 12, 'updated_at' => 13, ),
		BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, )
	);

	
	public static function getMapBuilder()
	{
		include_once 'plugins/ttCommunicatiePlugin/lib/model/map/BriefVerzondenMapBuilder.php';
		return BasePeer::getMapBuilder('plugins.ttCommunicatiePlugin.lib.model.map.BriefVerzondenMapBuilder');
	}
	
	public static function getPhpNameMap()
	{
		if (self::$phpNameMap === null) {
			$map = BriefVerzondenPeer::getTableMap();
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
		return str_replace(BriefVerzondenPeer::TABLE_NAME.'.', $alias.'.', $column);
	}

	
	public static function addSelectColumns(Criteria $criteria)
	{

		$criteria->addSelectColumn(BriefVerzondenPeer::ID);

		$criteria->addSelectColumn(BriefVerzondenPeer::OBJECT_CLASS);

		$criteria->addSelectColumn(BriefVerzondenPeer::OBJECT_ID);

		$criteria->addSelectColumn(BriefVerzondenPeer::BRIEF_TEMPLATE_ID);

		$criteria->addSelectColumn(BriefVerzondenPeer::ONDERWERP);

		$criteria->addSelectColumn(BriefVerzondenPeer::HTML);

		$criteria->addSelectColumn(BriefVerzondenPeer::MEDIUM);

		$criteria->addSelectColumn(BriefVerzondenPeer::ADRES);

		$criteria->addSelectColumn(BriefVerzondenPeer::CUSTOM);

		$criteria->addSelectColumn(BriefVerzondenPeer::TAAL);

		$criteria->addSelectColumn(BriefVerzondenPeer::CREATED_BY);

		$criteria->addSelectColumn(BriefVerzondenPeer::UPDATED_BY);

		$criteria->addSelectColumn(BriefVerzondenPeer::CREATED_AT);

		$criteria->addSelectColumn(BriefVerzondenPeer::UPDATED_AT);

	}

	const COUNT = 'COUNT(brief_verzonden.ID)';
	const COUNT_DISTINCT = 'COUNT(DISTINCT brief_verzonden.ID)';

	
	public static function doCount(Criteria $criteria, $distinct = false, $con = null)
	{
				$criteria = clone $criteria;

				$criteria->clearSelectColumns()->clearOrderByColumns();
		if ($distinct || in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->addSelectColumn(BriefVerzondenPeer::COUNT_DISTINCT);
		} else {
			$criteria->addSelectColumn(BriefVerzondenPeer::COUNT);
		}

				foreach($criteria->getGroupByColumns() as $column)
		{
			$criteria->addSelectColumn($column);
		}

		$rs = BriefVerzondenPeer::doSelectRS($criteria, $con);
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
		$objects = BriefVerzondenPeer::doSelect($critcopy, $con);
		if ($objects) {
			return $objects[0];
		}
		return null;
	}
	
	public static function doSelect(Criteria $criteria, $con = null)
	{
		return BriefVerzondenPeer::populateObjects(BriefVerzondenPeer::doSelectRS($criteria, $con));
	}
	
	public static function doSelectRS(Criteria $criteria, $con = null)
	{

    foreach (sfMixer::getCallables('BaseBriefVerzondenPeer:addDoSelectRS:addDoSelectRS') as $callable)
    {
      call_user_func($callable, 'BaseBriefVerzondenPeer', $criteria, $con);
    }


		if ($con === null) {
			$con = Propel::getConnection(self::DATABASE_NAME);
		}

		if (!$criteria->getSelectColumns()) {
			$criteria = clone $criteria;
			BriefVerzondenPeer::addSelectColumns($criteria);
		}

				$criteria->setDbName(self::DATABASE_NAME);

						return BasePeer::doSelect($criteria, $con);
	}
	
	public static function populateObjects(ResultSet $rs)
	{
		$results = array();
	
				$cls = BriefVerzondenPeer::getOMClass();
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
			$criteria->addSelectColumn(BriefVerzondenPeer::COUNT_DISTINCT);
		} else {
			$criteria->addSelectColumn(BriefVerzondenPeer::COUNT);
		}

				foreach($criteria->getGroupByColumns() as $column)
		{
			$criteria->addSelectColumn($column);
		}

		$criteria->addJoin(BriefVerzondenPeer::BRIEF_TEMPLATE_ID, BriefTemplatePeer::ID);

		$rs = BriefVerzondenPeer::doSelectRS($criteria, $con);
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

		BriefVerzondenPeer::addSelectColumns($c);
		$startcol = (BriefVerzondenPeer::NUM_COLUMNS - BriefVerzondenPeer::NUM_LAZY_LOAD_COLUMNS) + 1;
		BriefTemplatePeer::addSelectColumns($c);

		$c->addJoin(BriefVerzondenPeer::BRIEF_TEMPLATE_ID, BriefTemplatePeer::ID);
		$rs = BasePeer::doSelect($c, $con);
		$results = array();

		while($rs->next()) {

			$omClass = BriefVerzondenPeer::getOMClass();

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
										$temp_obj2->addBriefVerzonden($obj1); 					break;
				}
			}
			if ($newObject) {
				$obj2->initBriefVerzondens();
				$obj2->addBriefVerzonden($obj1); 			}
			$results[] = $obj1;
		}
		return $results;
	}


	
	public static function doCountJoinAll(Criteria $criteria, $distinct = false, $con = null)
	{
		$criteria = clone $criteria;

				$criteria->clearSelectColumns()->clearOrderByColumns();
		if ($distinct || in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->addSelectColumn(BriefVerzondenPeer::COUNT_DISTINCT);
		} else {
			$criteria->addSelectColumn(BriefVerzondenPeer::COUNT);
		}

				foreach($criteria->getGroupByColumns() as $column)
		{
			$criteria->addSelectColumn($column);
		}

		$criteria->addJoin(BriefVerzondenPeer::BRIEF_TEMPLATE_ID, BriefTemplatePeer::ID);

		$rs = BriefVerzondenPeer::doSelectRS($criteria, $con);
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

		BriefVerzondenPeer::addSelectColumns($c);
		$startcol2 = (BriefVerzondenPeer::NUM_COLUMNS - BriefVerzondenPeer::NUM_LAZY_LOAD_COLUMNS) + 1;

		BriefTemplatePeer::addSelectColumns($c);
		$startcol3 = $startcol2 + BriefTemplatePeer::NUM_COLUMNS;

		$c->addJoin(BriefVerzondenPeer::BRIEF_TEMPLATE_ID, BriefTemplatePeer::ID);

		$rs = BasePeer::doSelect($c, $con);
		$results = array();

		while($rs->next()) {

			$omClass = BriefVerzondenPeer::getOMClass();


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
					$temp_obj2->addBriefVerzonden($obj1); 					break;
				}
			}

			if ($newObject) {
				$obj2->initBriefVerzondens();
				$obj2->addBriefVerzonden($obj1);
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
		return BriefVerzondenPeer::CLASS_DEFAULT;
	}

	
	public static function doInsert($values, $con = null)
	{

    foreach (sfMixer::getCallables('BaseBriefVerzondenPeer:doInsert:pre') as $callable)
    {
      $ret = call_user_func($callable, 'BaseBriefVerzondenPeer', $values, $con);
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

		$criteria->remove(BriefVerzondenPeer::ID); 

				$criteria->setDbName(self::DATABASE_NAME);

		try {
									$con->begin();
			$pk = BasePeer::doInsert($criteria, $con);
			$con->commit();
		} catch(PropelException $e) {
			$con->rollback();
			throw $e;
		}

		
    foreach (sfMixer::getCallables('BaseBriefVerzondenPeer:doInsert:post') as $callable)
    {
      call_user_func($callable, 'BaseBriefVerzondenPeer', $values, $con, $pk);
    }

    return $pk;
	}

	
	public static function doUpdate($values, $con = null)
	{

    foreach (sfMixer::getCallables('BaseBriefVerzondenPeer:doUpdate:pre') as $callable)
    {
      $ret = call_user_func($callable, 'BaseBriefVerzondenPeer', $values, $con);
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
			$comparison = $criteria->getComparison(BriefVerzondenPeer::ID);
			$selectCriteria->add(BriefVerzondenPeer::ID, $criteria->remove(BriefVerzondenPeer::ID), $comparison);

		} else { 			$criteria = $values->buildCriteria(); 			$selectCriteria = $values->buildPkeyCriteria(); 		}

				$criteria->setDbName(self::DATABASE_NAME);

		$ret = BasePeer::doUpdate($selectCriteria, $criteria, $con);
	

    foreach (sfMixer::getCallables('BaseBriefVerzondenPeer:doUpdate:post') as $callable)
    {
      call_user_func($callable, 'BaseBriefVerzondenPeer', $values, $con, $ret);
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
			$affectedRows += BasePeer::doDeleteAll(BriefVerzondenPeer::TABLE_NAME, $con);
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
			$con = Propel::getConnection(BriefVerzondenPeer::DATABASE_NAME);
		}

		if ($values instanceof Criteria) {
			$criteria = clone $values; 		} elseif ($values instanceof BriefVerzonden) {

			$criteria = $values->buildPkeyCriteria();
		} else {
						$criteria = new Criteria(self::DATABASE_NAME);
			$criteria->add(BriefVerzondenPeer::ID, (array) $values, Criteria::IN);
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

	
	public static function doValidate(BriefVerzonden $obj, $cols = null)
	{
		$columns = array();

		if ($cols) {
			$dbMap = Propel::getDatabaseMap(BriefVerzondenPeer::DATABASE_NAME);
			$tableMap = $dbMap->getTable(BriefVerzondenPeer::TABLE_NAME);

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

		$res =  BasePeer::doValidate(BriefVerzondenPeer::DATABASE_NAME, BriefVerzondenPeer::TABLE_NAME, $columns);
    if ($res !== true) {
        $request = sfContext::getInstance()->getRequest();
        foreach ($res as $failed) {
            $col = BriefVerzondenPeer::translateFieldname($failed->getColumn(), BasePeer::TYPE_COLNAME, BasePeer::TYPE_PHPNAME);
            $request->setError($col, $failed->getMessage());
        }
    }

    return $res;
	}

	
	public static function retrieveByPK($pk, $con = null)
	{
		if ($con === null) {
			$con = Propel::getConnection(self::DATABASE_NAME);
		}

		$criteria = new Criteria(BriefVerzondenPeer::DATABASE_NAME);

		$criteria->add(BriefVerzondenPeer::ID, $pk);


		$v = BriefVerzondenPeer::doSelect($criteria, $con);

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
			$criteria->add(BriefVerzondenPeer::ID, $pks, Criteria::IN);
			$objs = BriefVerzondenPeer::doSelect($criteria, $con);
		}
		return $objs;
	}

} 
if (Propel::isInit()) {
			try {
		BaseBriefVerzondenPeer::getMapBuilder();
	} catch (Exception $e) {
		Propel::log('Could not initialize Peer: ' . $e->getMessage(), Propel::LOG_ERR);
	}
} else {
			require_once 'plugins/ttCommunicatiePlugin/lib/model/map/BriefVerzondenMapBuilder.php';
	Propel::registerMapBuilder('plugins.ttCommunicatiePlugin.lib.model.map.BriefVerzondenMapBuilder');
}
