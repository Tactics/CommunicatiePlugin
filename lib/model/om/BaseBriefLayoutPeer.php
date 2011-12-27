<?php


abstract class BaseBriefLayoutPeer {

	
	const DATABASE_NAME = 'propel';

	
	const TABLE_NAME = 'brief_layout';

	
	const CLASS_DEFAULT = 'plugins.ttCommunicatiePlugin.lib.model.BriefLayout';

	
	const NUM_COLUMNS = 11;

	
	const NUM_LAZY_LOAD_COLUMNS = 0;


	
	const ID = 'brief_layout.ID';

	
	const NAAM = 'brief_layout.NAAM';

	
	const PRINT_BESTAND = 'brief_layout.PRINT_BESTAND';

	
	const MAIL_BESTAND = 'brief_layout.MAIL_BESTAND';

	
	const PRINT_STYLESHEETS = 'brief_layout.PRINT_STYLESHEETS';

	
	const MAIL_STYLESHEETS = 'brief_layout.MAIL_STYLESHEETS';

	
	const VERTAALD = 'brief_layout.VERTAALD';

	
	const CREATED_BY = 'brief_layout.CREATED_BY';

	
	const UPDATED_BY = 'brief_layout.UPDATED_BY';

	
	const CREATED_AT = 'brief_layout.CREATED_AT';

	
	const UPDATED_AT = 'brief_layout.UPDATED_AT';

	
	private static $phpNameMap = null;


	
	private static $fieldNames = array (
		BasePeer::TYPE_PHPNAME => array ('Id', 'Naam', 'PrintBestand', 'MailBestand', 'PrintStylesheets', 'MailStylesheets', 'Vertaald', 'CreatedBy', 'UpdatedBy', 'CreatedAt', 'UpdatedAt', ),
		BasePeer::TYPE_COLNAME => array (BriefLayoutPeer::ID, BriefLayoutPeer::NAAM, BriefLayoutPeer::PRINT_BESTAND, BriefLayoutPeer::MAIL_BESTAND, BriefLayoutPeer::PRINT_STYLESHEETS, BriefLayoutPeer::MAIL_STYLESHEETS, BriefLayoutPeer::VERTAALD, BriefLayoutPeer::CREATED_BY, BriefLayoutPeer::UPDATED_BY, BriefLayoutPeer::CREATED_AT, BriefLayoutPeer::UPDATED_AT, ),
		BasePeer::TYPE_FIELDNAME => array ('id', 'naam', 'print_bestand', 'mail_bestand', 'print_stylesheets', 'mail_stylesheets', 'vertaald', 'created_by', 'updated_by', 'created_at', 'updated_at', ),
		BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, )
	);

	
	private static $fieldKeys = array (
		BasePeer::TYPE_PHPNAME => array ('Id' => 0, 'Naam' => 1, 'PrintBestand' => 2, 'MailBestand' => 3, 'PrintStylesheets' => 4, 'MailStylesheets' => 5, 'Vertaald' => 6, 'CreatedBy' => 7, 'UpdatedBy' => 8, 'CreatedAt' => 9, 'UpdatedAt' => 10, ),
		BasePeer::TYPE_COLNAME => array (BriefLayoutPeer::ID => 0, BriefLayoutPeer::NAAM => 1, BriefLayoutPeer::PRINT_BESTAND => 2, BriefLayoutPeer::MAIL_BESTAND => 3, BriefLayoutPeer::PRINT_STYLESHEETS => 4, BriefLayoutPeer::MAIL_STYLESHEETS => 5, BriefLayoutPeer::VERTAALD => 6, BriefLayoutPeer::CREATED_BY => 7, BriefLayoutPeer::UPDATED_BY => 8, BriefLayoutPeer::CREATED_AT => 9, BriefLayoutPeer::UPDATED_AT => 10, ),
		BasePeer::TYPE_FIELDNAME => array ('id' => 0, 'naam' => 1, 'print_bestand' => 2, 'mail_bestand' => 3, 'print_stylesheets' => 4, 'mail_stylesheets' => 5, 'vertaald' => 6, 'created_by' => 7, 'updated_by' => 8, 'created_at' => 9, 'updated_at' => 10, ),
		BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, )
	);

	
	public static function getMapBuilder()
	{
		include_once 'plugins/ttCommunicatiePlugin/lib/model/map/BriefLayoutMapBuilder.php';
		return BasePeer::getMapBuilder('plugins.ttCommunicatiePlugin.lib.model.map.BriefLayoutMapBuilder');
	}
	
	public static function getPhpNameMap()
	{
		if (self::$phpNameMap === null) {
			$map = BriefLayoutPeer::getTableMap();
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
		return str_replace(BriefLayoutPeer::TABLE_NAME.'.', $alias.'.', $column);
	}

	
	public static function addSelectColumns(Criteria $criteria)
	{

		$criteria->addSelectColumn(BriefLayoutPeer::ID);

		$criteria->addSelectColumn(BriefLayoutPeer::NAAM);

		$criteria->addSelectColumn(BriefLayoutPeer::PRINT_BESTAND);

		$criteria->addSelectColumn(BriefLayoutPeer::MAIL_BESTAND);

		$criteria->addSelectColumn(BriefLayoutPeer::PRINT_STYLESHEETS);

		$criteria->addSelectColumn(BriefLayoutPeer::MAIL_STYLESHEETS);

		$criteria->addSelectColumn(BriefLayoutPeer::VERTAALD);

		$criteria->addSelectColumn(BriefLayoutPeer::CREATED_BY);

		$criteria->addSelectColumn(BriefLayoutPeer::UPDATED_BY);

		$criteria->addSelectColumn(BriefLayoutPeer::CREATED_AT);

		$criteria->addSelectColumn(BriefLayoutPeer::UPDATED_AT);

	}

	const COUNT = 'COUNT(brief_layout.ID)';
	const COUNT_DISTINCT = 'COUNT(DISTINCT brief_layout.ID)';

	
	public static function doCount(Criteria $criteria, $distinct = false, $con = null)
	{
				$criteria = clone $criteria;

				$criteria->clearSelectColumns()->clearOrderByColumns();
		if ($distinct || in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->addSelectColumn(BriefLayoutPeer::COUNT_DISTINCT);
		} else {
			$criteria->addSelectColumn(BriefLayoutPeer::COUNT);
		}

				foreach($criteria->getGroupByColumns() as $column)
		{
			$criteria->addSelectColumn($column);
		}

		$rs = BriefLayoutPeer::doSelectRS($criteria, $con);
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
		$objects = BriefLayoutPeer::doSelect($critcopy, $con);
		if ($objects) {
			return $objects[0];
		}
		return null;
	}
	
	public static function doSelect(Criteria $criteria, $con = null)
	{
		return BriefLayoutPeer::populateObjects(BriefLayoutPeer::doSelectRS($criteria, $con));
	}
	
	public static function doSelectRS(Criteria $criteria, $con = null)
	{

    foreach (sfMixer::getCallables('BaseBriefLayoutPeer:addDoSelectRS:addDoSelectRS') as $callable)
    {
      call_user_func($callable, 'BaseBriefLayoutPeer', $criteria, $con);
    }


		if ($con === null) {
			$con = Propel::getConnection(self::DATABASE_NAME);
		}

		if (!$criteria->getSelectColumns()) {
			$criteria = clone $criteria;
			BriefLayoutPeer::addSelectColumns($criteria);
		}

				$criteria->setDbName(self::DATABASE_NAME);

						return BasePeer::doSelect($criteria, $con);
	}
	
	public static function populateObjects(ResultSet $rs)
	{
		$results = array();
	
				$cls = BriefLayoutPeer::getOMClass();
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
		return BriefLayoutPeer::CLASS_DEFAULT;
	}

	
	public static function doInsert($values, $con = null)
	{

    foreach (sfMixer::getCallables('BaseBriefLayoutPeer:doInsert:pre') as $callable)
    {
      $ret = call_user_func($callable, 'BaseBriefLayoutPeer', $values, $con);
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


				$criteria->setDbName(self::DATABASE_NAME);

		try {
									$con->begin();
			$pk = BasePeer::doInsert($criteria, $con);
			$con->commit();
		} catch(PropelException $e) {
			$con->rollback();
			throw $e;
		}

		
    foreach (sfMixer::getCallables('BaseBriefLayoutPeer:doInsert:post') as $callable)
    {
      call_user_func($callable, 'BaseBriefLayoutPeer', $values, $con, $pk);
    }

    return $pk;
	}

	
	public static function doUpdate($values, $con = null)
	{

    foreach (sfMixer::getCallables('BaseBriefLayoutPeer:doUpdate:pre') as $callable)
    {
      $ret = call_user_func($callable, 'BaseBriefLayoutPeer', $values, $con);
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
			$comparison = $criteria->getComparison(BriefLayoutPeer::ID);
			$selectCriteria->add(BriefLayoutPeer::ID, $criteria->remove(BriefLayoutPeer::ID), $comparison);

		} else { 			$criteria = $values->buildCriteria(); 			$selectCriteria = $values->buildPkeyCriteria(); 		}

				$criteria->setDbName(self::DATABASE_NAME);

		$ret = BasePeer::doUpdate($selectCriteria, $criteria, $con);
	

    foreach (sfMixer::getCallables('BaseBriefLayoutPeer:doUpdate:post') as $callable)
    {
      call_user_func($callable, 'BaseBriefLayoutPeer', $values, $con, $ret);
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
			$affectedRows += BasePeer::doDeleteAll(BriefLayoutPeer::TABLE_NAME, $con);
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
			$con = Propel::getConnection(BriefLayoutPeer::DATABASE_NAME);
		}

		if ($values instanceof Criteria) {
			$criteria = clone $values; 		} elseif ($values instanceof BriefLayout) {

			$criteria = $values->buildPkeyCriteria();
		} else {
						$criteria = new Criteria(self::DATABASE_NAME);
			$criteria->add(BriefLayoutPeer::ID, (array) $values, Criteria::IN);
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

	
	public static function doValidate(BriefLayout $obj, $cols = null)
	{
		$columns = array();

		if ($cols) {
			$dbMap = Propel::getDatabaseMap(BriefLayoutPeer::DATABASE_NAME);
			$tableMap = $dbMap->getTable(BriefLayoutPeer::TABLE_NAME);

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

		$res =  BasePeer::doValidate(BriefLayoutPeer::DATABASE_NAME, BriefLayoutPeer::TABLE_NAME, $columns);
    if ($res !== true) {
        $request = sfContext::getInstance()->getRequest();
        foreach ($res as $failed) {
            $col = BriefLayoutPeer::translateFieldname($failed->getColumn(), BasePeer::TYPE_COLNAME, BasePeer::TYPE_PHPNAME);
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

		$criteria = new Criteria(BriefLayoutPeer::DATABASE_NAME);

		$criteria->add(BriefLayoutPeer::ID, $pk);


		$v = BriefLayoutPeer::doSelect($criteria, $con);

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
			$criteria->add(BriefLayoutPeer::ID, $pks, Criteria::IN);
			$objs = BriefLayoutPeer::doSelect($criteria, $con);
		}
		return $objs;
	}

} 
if (Propel::isInit()) {
			try {
		BaseBriefLayoutPeer::getMapBuilder();
	} catch (Exception $e) {
		Propel::log('Could not initialize Peer: ' . $e->getMessage(), Propel::LOG_ERR);
	}
} else {
			require_once 'plugins/ttCommunicatiePlugin/lib/model/map/BriefLayoutMapBuilder.php';
	Propel::registerMapBuilder('plugins.ttCommunicatiePlugin.lib.model.map.BriefLayoutMapBuilder');
}
