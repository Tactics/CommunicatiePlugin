<?php

/**
 * Base static class for performing query and update operations on the 'brief_bijlage' table.
 *
 * 
 *
 * @package    plugins.ttCommunicatiePlugin.lib.model.om
 */
abstract class BaseBriefBijlagePeer {

	/** the default database name for this class */
	const DATABASE_NAME = 'propel';

	/** the table name for this class */
	const TABLE_NAME = 'brief_bijlage';

	/** A class that can be returned by this peer. */
	const CLASS_DEFAULT = 'plugins.ttCommunicatiePlugin.lib.model.BriefBijlage';

	/** The total number of columns. */
	const NUM_COLUMNS = 8;

	/** The number of lazy-loaded columns. */
	const NUM_LAZY_LOAD_COLUMNS = 0;


	/** the column name for the ID field */
	const ID = 'brief_bijlage.ID';

	/** the column name for the BRIEF_TEMPLATE_ID field */
	const BRIEF_TEMPLATE_ID = 'brief_bijlage.BRIEF_TEMPLATE_ID';

	/** the column name for the BIJLAGE_NODE_ID field */
	const BIJLAGE_NODE_ID = 'brief_bijlage.BIJLAGE_NODE_ID';

	/** the column name for the CULTURE field */
	const CULTURE = 'brief_bijlage.CULTURE';

	/** the column name for the CREATED_AT field */
	const CREATED_AT = 'brief_bijlage.CREATED_AT';

	/** the column name for the UPDATED_AT field */
	const UPDATED_AT = 'brief_bijlage.UPDATED_AT';

	/** the column name for the CREATED_BY field */
	const CREATED_BY = 'brief_bijlage.CREATED_BY';

	/** the column name for the UPDATED_BY field */
	const UPDATED_BY = 'brief_bijlage.UPDATED_BY';

	/** The PHP to DB Name Mapping */
	private static $phpNameMap = null;


	/**
	 * holds an array of fieldnames
	 *
	 * first dimension keys are the type constants
	 * e.g. self::$fieldNames[self::TYPE_PHPNAME][0] = 'Id'
	 */
	private static $fieldNames = array (
		BasePeer::TYPE_PHPNAME => array ('Id', 'BriefTemplateId', 'BijlageNodeId', 'Culture', 'CreatedAt', 'UpdatedAt', 'CreatedBy', 'UpdatedBy', ),
		BasePeer::TYPE_COLNAME => array (BriefBijlagePeer::ID, BriefBijlagePeer::BRIEF_TEMPLATE_ID, BriefBijlagePeer::BIJLAGE_NODE_ID, BriefBijlagePeer::CULTURE, BriefBijlagePeer::CREATED_AT, BriefBijlagePeer::UPDATED_AT, BriefBijlagePeer::CREATED_BY, BriefBijlagePeer::UPDATED_BY, ),
		BasePeer::TYPE_FIELDNAME => array ('id', 'brief_template_id', 'bijlage_node_id', 'culture', 'created_at', 'updated_at', 'created_by', 'updated_by', ),
		BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, )
	);

	/**
	 * holds an array of keys for quick access to the fieldnames array
	 *
	 * first dimension keys are the type constants
	 * e.g. self::$fieldNames[BasePeer::TYPE_PHPNAME]['Id'] = 0
	 */
	private static $fieldKeys = array (
		BasePeer::TYPE_PHPNAME => array ('Id' => 0, 'BriefTemplateId' => 1, 'BijlageNodeId' => 2, 'Culture' => 3, 'CreatedAt' => 4, 'UpdatedAt' => 5, 'CreatedBy' => 6, 'UpdatedBy' => 7, ),
		BasePeer::TYPE_COLNAME => array (BriefBijlagePeer::ID => 0, BriefBijlagePeer::BRIEF_TEMPLATE_ID => 1, BriefBijlagePeer::BIJLAGE_NODE_ID => 2, BriefBijlagePeer::CULTURE => 3, BriefBijlagePeer::CREATED_AT => 4, BriefBijlagePeer::UPDATED_AT => 5, BriefBijlagePeer::CREATED_BY => 6, BriefBijlagePeer::UPDATED_BY => 7, ),
		BasePeer::TYPE_FIELDNAME => array ('id' => 0, 'brief_template_id' => 1, 'bijlage_node_id' => 2, 'culture' => 3, 'created_at' => 4, 'updated_at' => 5, 'created_by' => 6, 'updated_by' => 7, ),
		BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, )
	);

	/**
	 * @return     MapBuilder the map builder for this peer
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function getMapBuilder()
	{
		include_once 'plugins/ttCommunicatiePlugin/lib/model/map/BriefBijlageMapBuilder.php';
		return BasePeer::getMapBuilder('plugins.ttCommunicatiePlugin.lib.model.map.BriefBijlageMapBuilder');
	}
	/**
	 * Gets a map (hash) of PHP names to DB column names.
	 *
	 * @return     array The PHP to DB name map for this peer
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 * @deprecated Use the getFieldNames() and translateFieldName() methods instead of this.
	 */
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
	/**
	 * Translates a fieldname to another type
	 *
	 * @param      string $name field name
	 * @param      string $fromType One of the class type constants TYPE_PHPNAME,
	 *                         TYPE_COLNAME, TYPE_FIELDNAME, TYPE_NUM
	 * @param      string $toType   One of the class type constants
	 * @return     string translated name of the field.
	 * @throws     PropelException
	 */
	static public function translateFieldName($name, $fromType, $toType)
	{
		$toNames = self::getFieldNames($toType);
		$key = isset(self::$fieldKeys[$fromType][$name]) ? self::$fieldKeys[$fromType][$name] : null;
		if ($key === null) {
			throw new PropelException("'$name' could not be found in the field names of type '$fromType'. These are: " . print_r(self::$fieldKeys[$fromType], true));
		}
		return $toNames[$key];
	}

	/**
	 * Returns an array of field names.
	 *
	 * @param      string $type The type of fieldnames to return:
	 *                      One of the class type constants TYPE_PHPNAME,
	 *                      TYPE_COLNAME, TYPE_FIELDNAME, TYPE_NUM
	 * @return     mixed[string] A list of field names
	 * @throws     PropelException
	 */

	static public function getFieldNames($type = BasePeer::TYPE_PHPNAME)
	{
		if (!array_key_exists($type, self::$fieldNames)) {
			throw new PropelException('Method getFieldNames() expects the parameter $type to be one of the class constants TYPE_PHPNAME, TYPE_COLNAME, TYPE_FIELDNAME, TYPE_NUM. ' . $type . ' was given.');
		}
		return self::$fieldNames[$type];
	}

	/**
	 * Convenience method which changes table.column to alias.column.
	 *
	 * Using this method you can maintain SQL abstraction while using column aliases.
	 * <code>
	 *		$c->addAlias("alias1", TablePeer::TABLE_NAME);
	 *		$c->addJoin(TablePeer::alias("alias1", TablePeer::PRIMARY_KEY_COLUMN), TablePeer::PRIMARY_KEY_COLUMN);
	 * </code>
	 * @param      string $alias The alias for the current table.
	 * @param      string $column The column name for current table. (i.e. BriefBijlagePeer::COLUMN_NAME).
	 * @return     string
	 */
	public static function alias($alias, $column)
	{
		return str_replace(BriefBijlagePeer::TABLE_NAME.'.', $alias.'.', $column);
	}

	/**
	 * Add all the columns needed to create a new object.
	 *
	 * Note: any columns that were marked with lazyLoad="true" in the
	 * XML schema will not be added to the select list and only loaded
	 * on demand.
	 *
	 * @param      Criteria $criteria object containing the columns to add.
	 * @param      string $alias
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function addSelectColumns(Criteria $criteria, $alias = null)
	{

		$columnToSelect = $alias
		  ? BriefBijlagePeer::alias($alias, BriefBijlagePeer::ID)
		  : BriefBijlagePeer::ID;
		$criteria->addSelectColumn($columnToSelect);

		$columnToSelect = $alias
		  ? BriefBijlagePeer::alias($alias, BriefBijlagePeer::BRIEF_TEMPLATE_ID)
		  : BriefBijlagePeer::BRIEF_TEMPLATE_ID;
		$criteria->addSelectColumn($columnToSelect);

		$columnToSelect = $alias
		  ? BriefBijlagePeer::alias($alias, BriefBijlagePeer::BIJLAGE_NODE_ID)
		  : BriefBijlagePeer::BIJLAGE_NODE_ID;
		$criteria->addSelectColumn($columnToSelect);

		$columnToSelect = $alias
		  ? BriefBijlagePeer::alias($alias, BriefBijlagePeer::CULTURE)
		  : BriefBijlagePeer::CULTURE;
		$criteria->addSelectColumn($columnToSelect);

		$columnToSelect = $alias
		  ? BriefBijlagePeer::alias($alias, BriefBijlagePeer::CREATED_AT)
		  : BriefBijlagePeer::CREATED_AT;
		$criteria->addSelectColumn($columnToSelect);

		$columnToSelect = $alias
		  ? BriefBijlagePeer::alias($alias, BriefBijlagePeer::UPDATED_AT)
		  : BriefBijlagePeer::UPDATED_AT;
		$criteria->addSelectColumn($columnToSelect);

		$columnToSelect = $alias
		  ? BriefBijlagePeer::alias($alias, BriefBijlagePeer::CREATED_BY)
		  : BriefBijlagePeer::CREATED_BY;
		$criteria->addSelectColumn($columnToSelect);

		$columnToSelect = $alias
		  ? BriefBijlagePeer::alias($alias, BriefBijlagePeer::UPDATED_BY)
		  : BriefBijlagePeer::UPDATED_BY;
		$criteria->addSelectColumn($columnToSelect);

	}

	const COUNT = 'COUNT(brief_bijlage.ID)';
	const COUNT_DISTINCT = 'COUNT(DISTINCT brief_bijlage.ID)';

	/**
	 * Returns the number of rows matching criteria.
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct Whether to select only distinct columns (You can also set DISTINCT modifier in Criteria).
	 * @param      Connection $con
	 * @return     int Number of matching rows.
	 */
	public static function doCount(Criteria $criteria, $distinct = false, $con = null)
	{
		// we're going to modify criteria, so copy it first
		$criteria = clone $criteria;

		// clear out anything that might confuse the ORDER BY clause
		$criteria->clearSelectColumns()->clearOrderByColumns();
		if ($distinct || in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->addSelectColumn(BriefBijlagePeer::COUNT_DISTINCT);
		} else {
			$criteria->addSelectColumn(BriefBijlagePeer::COUNT);
		}

		// just in case we're grouping: add those columns to the select statement
		foreach($criteria->getGroupByColumns() as $column)
		{
			$criteria->addSelectColumn($column);
		}

		$rs = BriefBijlagePeer::doSelectRS($criteria, $con);
		if ($rs->next()) {
			return $rs->getInt(1);
		} else {
			// no rows returned; we infer that means 0 matches.
			return 0;
		}
	}
	/**
	 * Method to select one object from the DB.
	 *
	 * @param      Criteria $criteria object used to create the SELECT statement.
	 * @param      Connection $con
	 * @return     BriefBijlage
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
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
	/**
	 * Method to do selects.
	 *
	 * @param      Criteria $criteria The Criteria object used to build the SELECT statement.
	 * @param      Connection $con
	 * @return     BriefBijlage[] Array of selected Objects
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doSelect(Criteria $criteria, $con = null)
	{
		return BriefBijlagePeer::populateObjects(BriefBijlagePeer::doSelectRS($criteria, $con));
	}
	/**
	 * Prepares the Criteria object and uses the parent doSelect()
	 * method to get a ResultSet.
	 *
	 * Use this method directly if you want to just get the resultset
	 * (instead of an array of objects).
	 *
	 * @param      Criteria $criteria The Criteria object used to build the SELECT statement.
	 * @param      Connection $con the connection to use
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 * @return     ResultSet The resultset object with numerically-indexed fields.
	 * @see        BasePeer::doSelect()
	 */
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

		// Set the correct dbName
		$criteria->setDbName(self::DATABASE_NAME);

		// BasePeer returns a Creole ResultSet, set to return
		// rows indexed numerically.
		return BasePeer::doSelect($criteria, $con);
	}
	/**
	 * The returned array will contain objects of the default type or
	 * objects that inherit from the default.
	 *
	 * @param      Resultset $rs
	 * @return     BriefBijlage[]
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function populateObjects(ResultSet $rs)
	{
		$results = array();
	
		// set the class once to avoid overhead in the loop
		$cls = BriefBijlagePeer::getOMClass();
		$cls = Propel::import($cls);
		// populate the object(s)
		while($rs->next()) {
		
			/** @var BriefBijlage $obj */
			$obj = new $cls();
			$obj->hydrate($rs);
			$results[] = $obj;
			
		}
		return $results;
	}

	/**
	 * Returns the number of rows matching criteria, joining the related BriefTemplate table
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct Whether to select only distinct columns (You can also set DISTINCT modifier in Criteria).
	 * @param      Connection $con
	 * @return     int Number of matching rows.
	 */
	public static function doCountJoinBriefTemplate(Criteria $criteria, $distinct = false, $con = null)
	{
		// we're going to modify criteria, so copy it first
		$criteria = clone $criteria;

		// clear out anything that might confuse the ORDER BY clause
		$criteria->clearSelectColumns()->clearOrderByColumns();
		if ($distinct || in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->addSelectColumn(BriefBijlagePeer::COUNT_DISTINCT);
		} else {
			$criteria->addSelectColumn(BriefBijlagePeer::COUNT);
		}

		// just in case we're grouping: add those columns to the select statement
		foreach($criteria->getGroupByColumns() as $column)
		{
			$criteria->addSelectColumn($column);
		}

		$criteria->addJoin(BriefBijlagePeer::BRIEF_TEMPLATE_ID, BriefTemplatePeer::ID);

		$rs = BriefBijlagePeer::doSelectRS($criteria, $con);
		if ($rs->next()) {
			return $rs->getInt(1);
		} else {
			// no rows returned; we infer that means 0 matches.
			return 0;
		}
	}


	/**
	 * Selects a collection of BriefBijlage objects pre-filled with their BriefTemplate objects.
	 *
	 * @param      Criteria $c
	 * @param      Connection $con
	 * @return     BriefBijlage[] array Array of BriefBijlage objects.
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doSelectJoinBriefTemplate(Criteria $c, $con = null)
	{
		$c = clone $c;

		// Set the correct dbName if it has not been overridden
		if ($c->getDbName() == Propel::getDefaultDB()) {
			$c->setDbName(self::DATABASE_NAME);
		}

		BriefBijlagePeer::addSelectColumns($c);
		$startcol = (BriefBijlagePeer::NUM_COLUMNS - BriefBijlagePeer::NUM_LAZY_LOAD_COLUMNS) + 1;

		BriefTemplatePeer::addSelectColumns($c);

		$c->addJoin(BriefBijlagePeer::BRIEF_TEMPLATE_ID, BriefTemplatePeer::ID, Criteria::JOIN);
		$rs = BasePeer::doSelect($c, $con);
		$results = array();

		while($rs->next()) {

			$omClass = BriefBijlagePeer::getOMClass();

			/** @var BriefBijlage $obj1 */
			$cls = Propel::import($omClass);
			$obj1 = new $cls();
			$obj1->hydrate($rs);

			$omClass = BriefTemplatePeer::getOMClass();

			/** @var BriefTemplate $obj2 */
			$cls = Propel::import($omClass);
			$obj2 = new $cls();
			$obj2->hydrate($rs, $startcol);

			$newObject = true;
			/** @var BriefBijlage $temp_obj1 */
			foreach($results as $temp_obj1) {
				$temp_obj2 = $temp_obj1->getBriefTemplate(); //CHECKME
				if ($temp_obj2->getPrimaryKey() === $obj2->getPrimaryKey()) {
					$newObject = false;
					// e.g. $author->addBookRelatedByBookId()
					$temp_obj2->addBriefBijlage($obj1); //CHECKME
					break;
				}
			}
			if ($newObject) {
				$obj2->initBriefBijlages();
				$obj2->addBriefBijlage($obj1); //CHECKME
			}
			$results[] = $obj1;
		}
		return $results;
	}


	/**
	 * Returns the number of rows matching criteria, joining all related tables
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct Whether to select only distinct columns (You can also set DISTINCT modifier in Criteria).
	 * @param      Connection $con
	 * @return     int Number of matching rows.
	 */
	public static function doCountJoinAll(Criteria $criteria, $distinct = false, $con = null)
	{
		$criteria = clone $criteria;

		// clear out anything that might confuse the ORDER BY clause
		$criteria->clearSelectColumns()->clearOrderByColumns();
		if ($distinct || in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->addSelectColumn(BriefBijlagePeer::COUNT_DISTINCT);
		} else {
			$criteria->addSelectColumn(BriefBijlagePeer::COUNT);
		}

		// just in case we're grouping: add those columns to the select statement
		foreach($criteria->getGroupByColumns() as $column)
		{
			$criteria->addSelectColumn($column);
		}

		$criteria->addJoin(BriefBijlagePeer::BRIEF_TEMPLATE_ID, BriefTemplatePeer::ID);

		$rs = BriefBijlagePeer::doSelectRS($criteria, $con);
		if ($rs->next()) {
			return $rs->getInt(1);
		} else {
			// no rows returned; we infer that means 0 matches.
			return 0;
		}
	}


	/**
	 * Selects a collection of BriefBijlage objects pre-filled with all related objects.
	 *
	 * @param      Criteria $c
	 * @param      Connection $con
	 * @return     BriefBijlage[] array Array of BriefBijlage objects.
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doSelectJoinAll(Criteria $c, $con = null)
	{
		$c = clone $c;

		// Set the correct dbName if it has not been overridden
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

            /** @var BriefBijlage $obj1 */
			$cls = Propel::import($omClass);
			$obj1 = new $cls();
			$obj1->hydrate($rs);


				// Add objects for joined BriefTemplate rows
	
			$omClass = BriefTemplatePeer::getOMClass();

            /** @var BriefTemplate $obj2 */
			$cls = Propel::import($omClass);
			$obj2 = new $cls();
			$obj2->hydrate($rs, $startcol2);

			$newObject = true;
			for ($j=0, $resCount=count($results); $j < $resCount; $j++) {
			    /** @var BriefBijlage $temp_obj1 */
				$temp_obj1 = $results[$j];
				$temp_obj2 = $temp_obj1->getBriefTemplate(); // CHECKME
				if ($temp_obj2->getPrimaryKey() === $obj2->getPrimaryKey()) {
					$newObject = false;
					$temp_obj2->addBriefBijlage($obj1); // CHECKME
					break;
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

	/**
	 * Returns the TableMap related to this peer.
	 * This method is not needed for general use but a specific application could have a need.
	 * @return     TableMap
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function getTableMap()
	{
		return Propel::getDatabaseMap(self::DATABASE_NAME)->getTable(self::TABLE_NAME);
	}

	/**
	 * The class that the Peer will make instances of.
	 *
	 * This uses a dot-path notation which is tranalted into a path
	 * relative to a location on the PHP include_path.
	 * (e.g. path.to.MyClass -> 'path/to/MyClass.php')
	 *
	 * @return     string path.to.ClassName
	 */
	public static function getOMClass()
	{
		return BriefBijlagePeer::CLASS_DEFAULT;
	}

	/**
	 * Method perform an INSERT on the database, given a BriefBijlage or Criteria object.
	 *
	 * @param      mixed $values Criteria or BriefBijlage object containing data that is used to create the INSERT statement.
	 * @param      Connection $con the connection to use
	 * @return     mixed The new primary key.
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
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
			$criteria = clone $values; // rename for clarity
		} else {
			$criteria = $values->buildCriteria(); // build Criteria from BriefBijlage object
		}

		$criteria->remove(BriefBijlagePeer::ID); // remove pkey col since this table uses auto-increment


		// Set the correct dbName
		$criteria->setDbName(self::DATABASE_NAME);

		try {
			// use transaction because $criteria could contain info
			// for more than one table (I guess, conceivably)
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

	/**
	 * Method perform an UPDATE on the database, given a BriefBijlage or Criteria object.
	 *
	 * @param      mixed $values Criteria or BriefBijlage object containing data that is used to create the UPDATE statement.
	 * @param      Connection $con The connection to use (specify Connection object to exert more control over transactions).
	 * @return     int The number of affected rows (if supported by underlying database driver).
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
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
			$criteria = clone $values; // rename for clarity

			$comparison = $criteria->getComparison(BriefBijlagePeer::ID);
			$selectCriteria->add(BriefBijlagePeer::ID, $criteria->remove(BriefBijlagePeer::ID), $comparison);

		} else { // $values is BriefBijlage object
			$criteria = $values->buildCriteria(); // gets full criteria
			$selectCriteria = $values->buildPkeyCriteria(); // gets criteria w/ primary key(s)
		}

		// set the correct dbName
		$criteria->setDbName(self::DATABASE_NAME);

		$ret = BasePeer::doUpdate($selectCriteria, $criteria, $con);
	

    foreach (sfMixer::getCallables('BaseBriefBijlagePeer:doUpdate:post') as $callable)
    {
      call_user_func($callable, 'BaseBriefBijlagePeer', $values, $con, $ret);
    }

    return $ret;
  }

	/**
	 * Method to DELETE all rows from the brief_bijlage table.
	 *
	 * @param      Connection $con
	 * @return     int The number of affected rows (if supported by underlying database driver).
	 * @throws     PropelException
	 */
	public static function doDeleteAll($con = null)
	{
		if ($con === null) {
			$con = Propel::getConnection(self::DATABASE_NAME);
		}
		$affectedRows = 0; // initialize var to track total num of affected rows
		try {
			// use transaction because $criteria could contain info
			// for more than one table or we could emulating ON DELETE CASCADE, etc.
			$con->begin();
			$affectedRows += BasePeer::doDeleteAll(BriefBijlagePeer::TABLE_NAME, $con);
			$con->commit();
			return $affectedRows;
		} catch (PropelException $e) {
			$con->rollback();
			throw $e;
		}
	}

	/**
	 * Method perform a DELETE on the database, given a BriefBijlage or Criteria object OR a primary key value.
	 *
	 * @param      mixed $values Criteria or BriefBijlage object or primary key or array of primary keys
	 *              which is used to create the DELETE statement
	 * @param      Connection $con the connection to use
	 * @return     int 	The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
	 *				if supported by native driver or if emulated using Propel.
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	 public static function doDelete($values, $con = null)
	 {
		if ($con === null) {
			$con = Propel::getConnection(BriefBijlagePeer::DATABASE_NAME);
		}

		if ($values instanceof Criteria) {
			$criteria = clone $values; // rename for clarity
		} elseif ($values instanceof BriefBijlage) {

			$criteria = $values->buildPkeyCriteria();
		} else {
			// it must be the primary key
			$criteria = new Criteria(self::DATABASE_NAME);
			$criteria->add(BriefBijlagePeer::ID, (array) $values, Criteria::IN);
		}

		// Set the correct dbName
		$criteria->setDbName(self::DATABASE_NAME);

		$affectedRows = 0; // initialize var to track total num of affected rows

		try {
			// use transaction because $criteria could contain info
			// for more than one table or we could emulating ON DELETE CASCADE, etc.
			$con->begin();
			
			$affectedRows += BasePeer::doDelete($criteria, $con);
			$con->commit();
			return $affectedRows;
		} catch (PropelException $e) {
			$con->rollback();
			throw $e;
		}
	}

	/**
	 * Validates all modified columns of given BriefBijlage object.
	 * If parameter $columns is either a single column name or an array of column names
	 * than only those columns are validated.
	 *
	 * NOTICE: This does not apply to primary or foreign keys for now.
	 *
	 * @param      BaseBriefBijlage $obj The object to validate.
	 * @param      mixed $cols Column name or array of column names.
	 *
	 * @return     mixed TRUE if all columns are valid or the error message of the first invalid column.
	 */
	public static function doValidate(BaseBriefBijlage $obj, $cols = null)
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

	/**
	 * Retrieve a single object by pkey.
	 *
	 * @param      mixed $pk the primary key.
	 * @param      Connection $con the connection to use
	 * @return     BriefBijlage
	 */
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

	/**
	 * Retrieve multiple objects by pkey.
	 *
	 * @param      array $pks List of primary keys
	 * @param      Connection $con the connection to use
	 * @return     BriefBijlage[]
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
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

} // BaseBriefBijlagePeer

// static code to register the map builder for this Peer with the main Propel class
if (Propel::isInit()) {
	// the MapBuilder classes register themselves with Propel during initialization
	// so we need to load them here.
	try {
		BaseBriefBijlagePeer::getMapBuilder();
	} catch (Exception $e) {
		Propel::log('Could not initialize Peer: ' . $e->getMessage(), Propel::LOG_ERR);
	}
} else {
	// even if Propel is not yet initialized, the map builder class can be registered
	// now and then it will be loaded when Propel initializes.
	require_once 'plugins/ttCommunicatiePlugin/lib/model/map/BriefBijlageMapBuilder.php';
	Propel::registerMapBuilder('plugins.ttCommunicatiePlugin.lib.model.map.BriefBijlageMapBuilder');
}
