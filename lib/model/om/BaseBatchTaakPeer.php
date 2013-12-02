<?php


abstract class BaseBatchTaakPeer {

	
	const DATABASE_NAME = 'propel';

	
	const TABLE_NAME = 'batch_taak';

	
	const CLASS_DEFAULT = 'plugins.ttCommunicatiePlugin.lib.model.BatchTaak';

	
	const NUM_COLUMNS = 9;

	
	const NUM_LAZY_LOAD_COLUMNS = 0;


	
	const ID = 'batch_taak.ID';

	
	const OBJECT_CLASS = 'batch_taak.OBJECT_CLASS';

	
	const AANTAL = 'batch_taak.AANTAL';

	
	const STATUS = 'batch_taak.STATUS';

	
	const VERZENDEN_VANAF = 'batch_taak.VERZENDEN_VANAF';

	
	const CREATED_BY = 'batch_taak.CREATED_BY';

	
	const UPDATED_BY = 'batch_taak.UPDATED_BY';

	
	const CREATED_AT = 'batch_taak.CREATED_AT';

	
	const UPDATED_AT = 'batch_taak.UPDATED_AT';

	
	private static $phpNameMap = null;


	
	private static $fieldNames = array (
		BasePeer::TYPE_PHPNAME => array ('Id', 'ObjectClass', 'Aantal', 'Status', 'VerzendenVanaf', 'CreatedBy', 'UpdatedBy', 'CreatedAt', 'UpdatedAt', ),
		BasePeer::TYPE_COLNAME => array (BatchTaakPeer::ID, BatchTaakPeer::OBJECT_CLASS, BatchTaakPeer::AANTAL, BatchTaakPeer::STATUS, BatchTaakPeer::VERZENDEN_VANAF, BatchTaakPeer::CREATED_BY, BatchTaakPeer::UPDATED_BY, BatchTaakPeer::CREATED_AT, BatchTaakPeer::UPDATED_AT, ),
		BasePeer::TYPE_FIELDNAME => array ('id', 'object_class', 'aantal', 'status', 'verzenden_vanaf', 'created_by', 'updated_by', 'created_at', 'updated_at', ),
		BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, )
	);

	
	private static $fieldKeys = array (
		BasePeer::TYPE_PHPNAME => array ('Id' => 0, 'ObjectClass' => 1, 'Aantal' => 2, 'Status' => 3, 'VerzendenVanaf' => 4, 'CreatedBy' => 5, 'UpdatedBy' => 6, 'CreatedAt' => 7, 'UpdatedAt' => 8, ),
		BasePeer::TYPE_COLNAME => array (BatchTaakPeer::ID => 0, BatchTaakPeer::OBJECT_CLASS => 1, BatchTaakPeer::AANTAL => 2, BatchTaakPeer::STATUS => 3, BatchTaakPeer::VERZENDEN_VANAF => 4, BatchTaakPeer::CREATED_BY => 5, BatchTaakPeer::UPDATED_BY => 6, BatchTaakPeer::CREATED_AT => 7, BatchTaakPeer::UPDATED_AT => 8, ),
		BasePeer::TYPE_FIELDNAME => array ('id' => 0, 'object_class' => 1, 'aantal' => 2, 'status' => 3, 'verzenden_vanaf' => 4, 'created_by' => 5, 'updated_by' => 6, 'created_at' => 7, 'updated_at' => 8, ),
		BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, )
	);

	
	public static function getMapBuilder()
	{
		include_once 'plugins/ttCommunicatiePlugin/lib/model/map/BatchTaakMapBuilder.php';
		return BasePeer::getMapBuilder('plugins.ttCommunicatiePlugin.lib.model.map.BatchTaakMapBuilder');
	}
	
	public static function getPhpNameMap()
	{
		if (self::$phpNameMap === null) {
			$map = BatchTaakPeer::getTableMap();
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
		return str_replace(BatchTaakPeer::TABLE_NAME.'.', $alias.'.', $column);
	}

	
	public static function addSelectColumns(Criteria $criteria)
	{

		$criteria->addSelectColumn(BatchTaakPeer::ID);

		$criteria->addSelectColumn(BatchTaakPeer::OBJECT_CLASS);

		$criteria->addSelectColumn(BatchTaakPeer::AANTAL);

		$criteria->addSelectColumn(BatchTaakPeer::STATUS);

		$criteria->addSelectColumn(BatchTaakPeer::VERZENDEN_VANAF);

		$criteria->addSelectColumn(BatchTaakPeer::CREATED_BY);

		$criteria->addSelectColumn(BatchTaakPeer::UPDATED_BY);

		$criteria->addSelectColumn(BatchTaakPeer::CREATED_AT);

		$criteria->addSelectColumn(BatchTaakPeer::UPDATED_AT);

	}

	const COUNT = 'COUNT(batch_taak.ID)';
	const COUNT_DISTINCT = 'COUNT(DISTINCT batch_taak.ID)';

	
	public static function doCount(Criteria $criteria, $distinct = false, $con = null)
	{
				$criteria = clone $criteria;

				$criteria->clearSelectColumns()->clearOrderByColumns();
		if ($distinct || in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->addSelectColumn(BatchTaakPeer::COUNT_DISTINCT);
		} else {
			$criteria->addSelectColumn(BatchTaakPeer::COUNT);
		}

				foreach($criteria->getGroupByColumns() as $column)
		{
			$criteria->addSelectColumn($column);
		}

		$rs = BatchTaakPeer::doSelectRS($criteria, $con);
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
		$objects = BatchTaakPeer::doSelect($critcopy, $con);
		if ($objects) {
			return $objects[0];
		}
		return null;
	}
	
	public static function doSelect(Criteria $criteria, $con = null)
	{
		return BatchTaakPeer::populateObjects(BatchTaakPeer::doSelectRS($criteria, $con));
	}
	
	public static function doSelectRS(Criteria $criteria, $con = null)
	{

    foreach (sfMixer::getCallables('BaseBatchTaakPeer:addDoSelectRS:addDoSelectRS') as $callable)
    {
      call_user_func($callable, 'BaseBatchTaakPeer', $criteria, $con);
    }


		if ($con === null) {
			$con = Propel::getConnection(self::DATABASE_NAME);
		}

		if (!$criteria->getSelectColumns()) {
			$criteria = clone $criteria;
			BatchTaakPeer::addSelectColumns($criteria);
		}

				$criteria->setDbName(self::DATABASE_NAME);

						return BasePeer::doSelect($criteria, $con);
	}
	
	public static function populateObjects(ResultSet $rs)
	{
		$results = array();
	
				$cls = BatchTaakPeer::getOMClass();
		$cls = Propel::import($cls);
				while($rs->next()) {
		
			$obj = new $cls();
			$obj->hydrate($rs);
			$results[] = $obj;
			
		}
		return $results;
	}
	
	public static function getTableMap()
	{
		return Propel::getDatabaseMap(self::DATABASE_NAME)->getTable(self::TABLE_NAME);
	}

	
	public static function getOMClass()
	{
		return BatchTaakPeer::CLASS_DEFAULT;
	}

	
	public static function doInsert($values, $con = null)
	{

    foreach (sfMixer::getCallables('BaseBatchTaakPeer:doInsert:pre') as $callable)
    {
      $ret = call_user_func($callable, 'BaseBatchTaakPeer', $values, $con);
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

		$criteria->remove(BatchTaakPeer::ID); 

				$criteria->setDbName(self::DATABASE_NAME);

		try {
									$con->begin();
			$pk = BasePeer::doInsert($criteria, $con);
			$con->commit();
		} catch(PropelException $e) {
			$con->rollback();
			throw $e;
		}

		
    foreach (sfMixer::getCallables('BaseBatchTaakPeer:doInsert:post') as $callable)
    {
      call_user_func($callable, 'BaseBatchTaakPeer', $values, $con, $pk);
    }

    return $pk;
	}

	
	public static function doUpdate($values, $con = null)
	{

    foreach (sfMixer::getCallables('BaseBatchTaakPeer:doUpdate:pre') as $callable)
    {
      $ret = call_user_func($callable, 'BaseBatchTaakPeer', $values, $con);
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
			$comparison = $criteria->getComparison(BatchTaakPeer::ID);
			$selectCriteria->add(BatchTaakPeer::ID, $criteria->remove(BatchTaakPeer::ID), $comparison);

		} else { 			$criteria = $values->buildCriteria(); 			$selectCriteria = $values->buildPkeyCriteria(); 		}

				$criteria->setDbName(self::DATABASE_NAME);

		$ret = BasePeer::doUpdate($selectCriteria, $criteria, $con);
	

    foreach (sfMixer::getCallables('BaseBatchTaakPeer:doUpdate:post') as $callable)
    {
      call_user_func($callable, 'BaseBatchTaakPeer', $values, $con, $ret);
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
			$affectedRows += BasePeer::doDeleteAll(BatchTaakPeer::TABLE_NAME, $con);
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
			$con = Propel::getConnection(BatchTaakPeer::DATABASE_NAME);
		}

		if ($values instanceof Criteria) {
			$criteria = clone $values; 		} elseif ($values instanceof BatchTaak) {

			$criteria = $values->buildPkeyCriteria();
		} else {
						$criteria = new Criteria(self::DATABASE_NAME);
			$criteria->add(BatchTaakPeer::ID, (array) $values, Criteria::IN);
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

	
	public static function doValidate(BatchTaak $obj, $cols = null)
	{
		$columns = array();

		if ($cols) {
			$dbMap = Propel::getDatabaseMap(BatchTaakPeer::DATABASE_NAME);
			$tableMap = $dbMap->getTable(BatchTaakPeer::TABLE_NAME);

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

		$res =  BasePeer::doValidate(BatchTaakPeer::DATABASE_NAME, BatchTaakPeer::TABLE_NAME, $columns);
    if ($res !== true) {
        $request = sfContext::getInstance()->getRequest();
        foreach ($res as $failed) {
            $col = BatchTaakPeer::translateFieldname($failed->getColumn(), BasePeer::TYPE_COLNAME, BasePeer::TYPE_PHPNAME);
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

		$criteria = new Criteria(BatchTaakPeer::DATABASE_NAME);

		$criteria->add(BatchTaakPeer::ID, $pk);


		$v = BatchTaakPeer::doSelect($criteria, $con);

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
			$criteria->add(BatchTaakPeer::ID, $pks, Criteria::IN);
			$objs = BatchTaakPeer::doSelect($criteria, $con);
		}
		return $objs;
	}

} 
if (Propel::isInit()) {
			try {
		BaseBatchTaakPeer::getMapBuilder();
	} catch (Exception $e) {
		Propel::log('Could not initialize Peer: ' . $e->getMessage(), Propel::LOG_ERR);
	}
} else {
			require_once 'plugins/ttCommunicatiePlugin/lib/model/map/BatchTaakMapBuilder.php';
	Propel::registerMapBuilder('plugins.ttCommunicatiePlugin.lib.model.map.BatchTaakMapBuilder');
}
