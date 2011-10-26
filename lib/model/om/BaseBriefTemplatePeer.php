<?php


abstract class BaseBriefTemplatePeer {

	
	const DATABASE_NAME = 'propel';

	
	const TABLE_NAME = 'brief_template';

	
	const CLASS_DEFAULT = 'plugins.ttCommunicatiePlugin.lib.model.BriefTemplate';

	
	const NUM_COLUMNS = 12;

	
	const NUM_LAZY_LOAD_COLUMNS = 0;


	
	const ID = 'brief_template.ID';

	
	const BRIEF_LAYOUT_ID = 'brief_template.BRIEF_LAYOUT_ID';

	
	const ONDERWERP = 'brief_template.ONDERWERP';

	
	const NAAM = 'brief_template.NAAM';

	
	const TYPE = 'brief_template.TYPE';

	
	const BESTEMMELING_CLASSES = 'brief_template.BESTEMMELING_CLASSES';

	
	const HTML = 'brief_template.HTML';

	
	const EENMALIG_VERSTUREN = 'brief_template.EENMALIG_VERSTUREN';

	
	const CREATED_BY = 'brief_template.CREATED_BY';

	
	const UPDATED_BY = 'brief_template.UPDATED_BY';

	
	const CREATED_AT = 'brief_template.CREATED_AT';

	
	const UPDATED_AT = 'brief_template.UPDATED_AT';

	
	private static $phpNameMap = null;


	
	private static $fieldNames = array (
		BasePeer::TYPE_PHPNAME => array ('Id', 'BriefLayoutId', 'Onderwerp', 'Naam', 'Type', 'BestemmelingClasses', 'Html', 'EenmaligVersturen', 'CreatedBy', 'UpdatedBy', 'CreatedAt', 'UpdatedAt', ),
		BasePeer::TYPE_COLNAME => array (BriefTemplatePeer::ID, BriefTemplatePeer::BRIEF_LAYOUT_ID, BriefTemplatePeer::ONDERWERP, BriefTemplatePeer::NAAM, BriefTemplatePeer::TYPE, BriefTemplatePeer::BESTEMMELING_CLASSES, BriefTemplatePeer::HTML, BriefTemplatePeer::EENMALIG_VERSTUREN, BriefTemplatePeer::CREATED_BY, BriefTemplatePeer::UPDATED_BY, BriefTemplatePeer::CREATED_AT, BriefTemplatePeer::UPDATED_AT, ),
		BasePeer::TYPE_FIELDNAME => array ('id', 'brief_layout_id', 'onderwerp', 'naam', 'type', 'bestemmeling_classes', 'html', 'eenmalig_versturen', 'created_by', 'updated_by', 'created_at', 'updated_at', ),
		BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, )
	);

	
	private static $fieldKeys = array (
		BasePeer::TYPE_PHPNAME => array ('Id' => 0, 'BriefLayoutId' => 1, 'Onderwerp' => 2, 'Naam' => 3, 'Type' => 4, 'BestemmelingClasses' => 5, 'Html' => 6, 'EenmaligVersturen' => 7, 'CreatedBy' => 8, 'UpdatedBy' => 9, 'CreatedAt' => 10, 'UpdatedAt' => 11, ),
		BasePeer::TYPE_COLNAME => array (BriefTemplatePeer::ID => 0, BriefTemplatePeer::BRIEF_LAYOUT_ID => 1, BriefTemplatePeer::ONDERWERP => 2, BriefTemplatePeer::NAAM => 3, BriefTemplatePeer::TYPE => 4, BriefTemplatePeer::BESTEMMELING_CLASSES => 5, BriefTemplatePeer::HTML => 6, BriefTemplatePeer::EENMALIG_VERSTUREN => 7, BriefTemplatePeer::CREATED_BY => 8, BriefTemplatePeer::UPDATED_BY => 9, BriefTemplatePeer::CREATED_AT => 10, BriefTemplatePeer::UPDATED_AT => 11, ),
		BasePeer::TYPE_FIELDNAME => array ('id' => 0, 'brief_layout_id' => 1, 'onderwerp' => 2, 'naam' => 3, 'type' => 4, 'bestemmeling_classes' => 5, 'html' => 6, 'eenmalig_versturen' => 7, 'created_by' => 8, 'updated_by' => 9, 'created_at' => 10, 'updated_at' => 11, ),
		BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, )
	);

	
	public static function getMapBuilder()
	{
		include_once 'plugins/ttCommunicatiePlugin/lib/model/map/BriefTemplateMapBuilder.php';
		return BasePeer::getMapBuilder('plugins.ttCommunicatiePlugin.lib.model.map.BriefTemplateMapBuilder');
	}
	
	public static function getPhpNameMap()
	{
		if (self::$phpNameMap === null) {
			$map = BriefTemplatePeer::getTableMap();
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
		return str_replace(BriefTemplatePeer::TABLE_NAME.'.', $alias.'.', $column);
	}

	
	public static function addSelectColumns(Criteria $criteria)
	{

		$criteria->addSelectColumn(BriefTemplatePeer::ID);

		$criteria->addSelectColumn(BriefTemplatePeer::BRIEF_LAYOUT_ID);

		$criteria->addSelectColumn(BriefTemplatePeer::ONDERWERP);

		$criteria->addSelectColumn(BriefTemplatePeer::NAAM);

		$criteria->addSelectColumn(BriefTemplatePeer::TYPE);

		$criteria->addSelectColumn(BriefTemplatePeer::BESTEMMELING_CLASSES);

		$criteria->addSelectColumn(BriefTemplatePeer::HTML);

		$criteria->addSelectColumn(BriefTemplatePeer::EENMALIG_VERSTUREN);

		$criteria->addSelectColumn(BriefTemplatePeer::CREATED_BY);

		$criteria->addSelectColumn(BriefTemplatePeer::UPDATED_BY);

		$criteria->addSelectColumn(BriefTemplatePeer::CREATED_AT);

		$criteria->addSelectColumn(BriefTemplatePeer::UPDATED_AT);

	}

	const COUNT = 'COUNT(brief_template.ID)';
	const COUNT_DISTINCT = 'COUNT(DISTINCT brief_template.ID)';

	
	public static function doCount(Criteria $criteria, $distinct = false, $con = null)
	{
				$criteria = clone $criteria;

				$criteria->clearSelectColumns()->clearOrderByColumns();
		if ($distinct || in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->addSelectColumn(BriefTemplatePeer::COUNT_DISTINCT);
		} else {
			$criteria->addSelectColumn(BriefTemplatePeer::COUNT);
		}

				foreach($criteria->getGroupByColumns() as $column)
		{
			$criteria->addSelectColumn($column);
		}

		$rs = BriefTemplatePeer::doSelectRS($criteria, $con);
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
		$objects = BriefTemplatePeer::doSelect($critcopy, $con);
		if ($objects) {
			return $objects[0];
		}
		return null;
	}
	
	public static function doSelect(Criteria $criteria, $con = null)
	{
		return BriefTemplatePeer::populateObjects(BriefTemplatePeer::doSelectRS($criteria, $con));
	}
	
	public static function doSelectRS(Criteria $criteria, $con = null)
	{

    foreach (sfMixer::getCallables('BaseBriefTemplatePeer:addDoSelectRS:addDoSelectRS') as $callable)
    {
      call_user_func($callable, 'BaseBriefTemplatePeer', $criteria, $con);
    }


		if ($con === null) {
			$con = Propel::getConnection(self::DATABASE_NAME);
		}

		if (!$criteria->getSelectColumns()) {
			$criteria = clone $criteria;
			BriefTemplatePeer::addSelectColumns($criteria);
		}

				$criteria->setDbName(self::DATABASE_NAME);

						return BasePeer::doSelect($criteria, $con);
	}
	
	public static function populateObjects(ResultSet $rs)
	{
		$results = array();
	
				$cls = BriefTemplatePeer::getOMClass();
		$cls = Propel::import($cls);
				while($rs->next()) {
		
			$obj = new $cls();
			$obj->hydrate($rs);
			$results[] = $obj;
			
		}
		return $results;
	}

	
	public static function doCountJoinBriefLayout(Criteria $criteria, $distinct = false, $con = null)
	{
				$criteria = clone $criteria;

				$criteria->clearSelectColumns()->clearOrderByColumns();
		if ($distinct || in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->addSelectColumn(BriefTemplatePeer::COUNT_DISTINCT);
		} else {
			$criteria->addSelectColumn(BriefTemplatePeer::COUNT);
		}

				foreach($criteria->getGroupByColumns() as $column)
		{
			$criteria->addSelectColumn($column);
		}

		$criteria->addJoin(BriefTemplatePeer::BRIEF_LAYOUT_ID, BriefLayoutPeer::ID);

		$rs = BriefTemplatePeer::doSelectRS($criteria, $con);
		if ($rs->next()) {
			return $rs->getInt(1);
		} else {
						return 0;
		}
	}


	
	public static function doSelectJoinBriefLayout(Criteria $c, $con = null)
	{
		$c = clone $c;

				if ($c->getDbName() == Propel::getDefaultDB()) {
			$c->setDbName(self::DATABASE_NAME);
		}

		BriefTemplatePeer::addSelectColumns($c);
		$startcol = (BriefTemplatePeer::NUM_COLUMNS - BriefTemplatePeer::NUM_LAZY_LOAD_COLUMNS) + 1;
		BriefLayoutPeer::addSelectColumns($c);

		$c->addJoin(BriefTemplatePeer::BRIEF_LAYOUT_ID, BriefLayoutPeer::ID);
		$rs = BasePeer::doSelect($c, $con);
		$results = array();

		while($rs->next()) {

			$omClass = BriefTemplatePeer::getOMClass();

			$cls = Propel::import($omClass);
			$obj1 = new $cls();
			$obj1->hydrate($rs);

			$omClass = BriefLayoutPeer::getOMClass();

			$cls = Propel::import($omClass);
			$obj2 = new $cls();
			$obj2->hydrate($rs, $startcol);

			$newObject = true;
			foreach($results as $temp_obj1) {
				$temp_obj2 = $temp_obj1->getBriefLayout(); 				if ($temp_obj2->getPrimaryKey() === $obj2->getPrimaryKey()) {
					$newObject = false;
										$temp_obj2->addBriefTemplate($obj1); 					break;
				}
			}
			if ($newObject) {
				$obj2->initBriefTemplates();
				$obj2->addBriefTemplate($obj1); 			}
			$results[] = $obj1;
		}
		return $results;
	}


	
	public static function doCountJoinAll(Criteria $criteria, $distinct = false, $con = null)
	{
		$criteria = clone $criteria;

				$criteria->clearSelectColumns()->clearOrderByColumns();
		if ($distinct || in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->addSelectColumn(BriefTemplatePeer::COUNT_DISTINCT);
		} else {
			$criteria->addSelectColumn(BriefTemplatePeer::COUNT);
		}

				foreach($criteria->getGroupByColumns() as $column)
		{
			$criteria->addSelectColumn($column);
		}

		$criteria->addJoin(BriefTemplatePeer::BRIEF_LAYOUT_ID, BriefLayoutPeer::ID);

		$rs = BriefTemplatePeer::doSelectRS($criteria, $con);
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

		BriefTemplatePeer::addSelectColumns($c);
		$startcol2 = (BriefTemplatePeer::NUM_COLUMNS - BriefTemplatePeer::NUM_LAZY_LOAD_COLUMNS) + 1;

		BriefLayoutPeer::addSelectColumns($c);
		$startcol3 = $startcol2 + BriefLayoutPeer::NUM_COLUMNS;

		$c->addJoin(BriefTemplatePeer::BRIEF_LAYOUT_ID, BriefLayoutPeer::ID);

		$rs = BasePeer::doSelect($c, $con);
		$results = array();

		while($rs->next()) {

			$omClass = BriefTemplatePeer::getOMClass();


			$cls = Propel::import($omClass);
			$obj1 = new $cls();
			$obj1->hydrate($rs);


					
			$omClass = BriefLayoutPeer::getOMClass();


			$cls = Propel::import($omClass);
			$obj2 = new $cls();
			$obj2->hydrate($rs, $startcol2);

			$newObject = true;
			for ($j=0, $resCount=count($results); $j < $resCount; $j++) {
				$temp_obj1 = $results[$j];
				$temp_obj2 = $temp_obj1->getBriefLayout(); 				if ($temp_obj2->getPrimaryKey() === $obj2->getPrimaryKey()) {
					$newObject = false;
					$temp_obj2->addBriefTemplate($obj1); 					break;
				}
			}

			if ($newObject) {
				$obj2->initBriefTemplates();
				$obj2->addBriefTemplate($obj1);
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
		return BriefTemplatePeer::CLASS_DEFAULT;
	}

	
	public static function doInsert($values, $con = null)
	{

    foreach (sfMixer::getCallables('BaseBriefTemplatePeer:doInsert:pre') as $callable)
    {
      $ret = call_user_func($callable, 'BaseBriefTemplatePeer', $values, $con);
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

		$criteria->remove(BriefTemplatePeer::ID); 

				$criteria->setDbName(self::DATABASE_NAME);

		try {
									$con->begin();
			$pk = BasePeer::doInsert($criteria, $con);
			$con->commit();
		} catch(PropelException $e) {
			$con->rollback();
			throw $e;
		}

		
    foreach (sfMixer::getCallables('BaseBriefTemplatePeer:doInsert:post') as $callable)
    {
      call_user_func($callable, 'BaseBriefTemplatePeer', $values, $con, $pk);
    }

    return $pk;
	}

	
	public static function doUpdate($values, $con = null)
	{

    foreach (sfMixer::getCallables('BaseBriefTemplatePeer:doUpdate:pre') as $callable)
    {
      $ret = call_user_func($callable, 'BaseBriefTemplatePeer', $values, $con);
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
			$comparison = $criteria->getComparison(BriefTemplatePeer::ID);
			$selectCriteria->add(BriefTemplatePeer::ID, $criteria->remove(BriefTemplatePeer::ID), $comparison);

		} else { 			$criteria = $values->buildCriteria(); 			$selectCriteria = $values->buildPkeyCriteria(); 		}

				$criteria->setDbName(self::DATABASE_NAME);

		$ret = BasePeer::doUpdate($selectCriteria, $criteria, $con);
	

    foreach (sfMixer::getCallables('BaseBriefTemplatePeer:doUpdate:post') as $callable)
    {
      call_user_func($callable, 'BaseBriefTemplatePeer', $values, $con, $ret);
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
			$affectedRows += BasePeer::doDeleteAll(BriefTemplatePeer::TABLE_NAME, $con);
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
			$con = Propel::getConnection(BriefTemplatePeer::DATABASE_NAME);
		}

		if ($values instanceof Criteria) {
			$criteria = clone $values; 		} elseif ($values instanceof BriefTemplate) {

			$criteria = $values->buildPkeyCriteria();
		} else {
						$criteria = new Criteria(self::DATABASE_NAME);
			$criteria->add(BriefTemplatePeer::ID, (array) $values, Criteria::IN);
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

	
	public static function doValidate(BriefTemplate $obj, $cols = null)
	{
		$columns = array();

		if ($cols) {
			$dbMap = Propel::getDatabaseMap(BriefTemplatePeer::DATABASE_NAME);
			$tableMap = $dbMap->getTable(BriefTemplatePeer::TABLE_NAME);

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

		$res =  BasePeer::doValidate(BriefTemplatePeer::DATABASE_NAME, BriefTemplatePeer::TABLE_NAME, $columns);
    if ($res !== true) {
        $request = sfContext::getInstance()->getRequest();
        foreach ($res as $failed) {
            $col = BriefTemplatePeer::translateFieldname($failed->getColumn(), BasePeer::TYPE_COLNAME, BasePeer::TYPE_PHPNAME);
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

		$criteria = new Criteria(BriefTemplatePeer::DATABASE_NAME);

		$criteria->add(BriefTemplatePeer::ID, $pk);


		$v = BriefTemplatePeer::doSelect($criteria, $con);

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
			$criteria->add(BriefTemplatePeer::ID, $pks, Criteria::IN);
			$objs = BriefTemplatePeer::doSelect($criteria, $con);
		}
		return $objs;
	}

} 
if (Propel::isInit()) {
			try {
		BaseBriefTemplatePeer::getMapBuilder();
	} catch (Exception $e) {
		Propel::log('Could not initialize Peer: ' . $e->getMessage(), Propel::LOG_ERR);
	}
} else {
			require_once 'plugins/ttCommunicatiePlugin/lib/model/map/BriefTemplateMapBuilder.php';
	Propel::registerMapBuilder('plugins.ttCommunicatiePlugin.lib.model.map.BriefTemplateMapBuilder');
}
