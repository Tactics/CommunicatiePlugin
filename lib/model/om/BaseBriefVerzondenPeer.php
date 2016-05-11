<?php

/**
 * Base static class for performing query and update operations on the 'brief_verzonden' table.
 *
 * 
 *
 * @package    plugins.ttCommunicatiePlugin.lib.model.om
 */
abstract class BaseBriefVerzondenPeer {

	/** the default database name for this class */
	const DATABASE_NAME = 'propel';

	/** the table name for this class */
	const TABLE_NAME = 'brief_verzonden';

	/** A class that can be returned by this peer. */
	const CLASS_DEFAULT = 'plugins.ttCommunicatiePlugin.lib.model.BriefVerzonden';

	/** The total number of columns. */
	const NUM_COLUMNS = 21;

	/** The number of lazy-loaded columns. */
	const NUM_LAZY_LOAD_COLUMNS = 0;


	/** the column name for the ID field */
	const ID = 'brief_verzonden.ID';

	/** the column name for the OBJECT_CLASS field */
	const OBJECT_CLASS = 'brief_verzonden.OBJECT_CLASS';

	/** the column name for the OBJECT_ID field */
	const OBJECT_ID = 'brief_verzonden.OBJECT_ID';

	/** the column name for the OBJECT_CLASS_BESTEMMELING field */
	const OBJECT_CLASS_BESTEMMELING = 'brief_verzonden.OBJECT_CLASS_BESTEMMELING';

	/** the column name for the OBJECT_ID_BESTEMMELING field */
	const OBJECT_ID_BESTEMMELING = 'brief_verzonden.OBJECT_ID_BESTEMMELING';

	/** the column name for the BRIEF_TEMPLATE_ID field */
	const BRIEF_TEMPLATE_ID = 'brief_verzonden.BRIEF_TEMPLATE_ID';

	/** the column name for the ONDERWERP field */
	const ONDERWERP = 'brief_verzonden.ONDERWERP';

	/** the column name for the HTML field */
	const HTML = 'brief_verzonden.HTML';

	/** the column name for the MEDIUM field */
	const MEDIUM = 'brief_verzonden.MEDIUM';

	/** the column name for the ADRES field */
	const ADRES = 'brief_verzonden.ADRES';

	/** the column name for the CC field */
	const CC = 'brief_verzonden.CC';

	/** the column name for the BCC field */
	const BCC = 'brief_verzonden.BCC';

	/** the column name for the CUSTOM field */
	const CUSTOM = 'brief_verzonden.CUSTOM';

	/** the column name for the CULTURE field */
	const CULTURE = 'brief_verzonden.CULTURE';

	/** the column name for the STATUS field */
	const STATUS = 'brief_verzonden.STATUS';

	/** the column name for the UUID field */
	const UUID = 'brief_verzonden.UUID';

	/** the column name for the BOUNCE_MSG field */
	const BOUNCE_MSG = 'brief_verzonden.BOUNCE_MSG';

	/** the column name for the CREATED_BY field */
	const CREATED_BY = 'brief_verzonden.CREATED_BY';

	/** the column name for the UPDATED_BY field */
	const UPDATED_BY = 'brief_verzonden.UPDATED_BY';

	/** the column name for the CREATED_AT field */
	const CREATED_AT = 'brief_verzonden.CREATED_AT';

	/** the column name for the UPDATED_AT field */
	const UPDATED_AT = 'brief_verzonden.UPDATED_AT';

	/** The PHP to DB Name Mapping */
	private static $phpNameMap = null;


	/**
	 * holds an array of fieldnames
	 *
	 * first dimension keys are the type constants
	 * e.g. self::$fieldNames[self::TYPE_PHPNAME][0] = 'Id'
	 */
	private static $fieldNames = array (
		BasePeer::TYPE_PHPNAME => array ('Id', 'ObjectClass', 'ObjectId', 'ObjectClassBestemmeling', 'ObjectIdBestemmeling', 'BriefTemplateId', 'Onderwerp', 'Html', 'Medium', 'Adres', 'Cc', 'Bcc', 'Custom', 'Culture', 'Status', 'Uuid', 'BounceMsg', 'CreatedBy', 'UpdatedBy', 'CreatedAt', 'UpdatedAt', ),
		BasePeer::TYPE_COLNAME => array (BriefVerzondenPeer::ID, BriefVerzondenPeer::OBJECT_CLASS, BriefVerzondenPeer::OBJECT_ID, BriefVerzondenPeer::OBJECT_CLASS_BESTEMMELING, BriefVerzondenPeer::OBJECT_ID_BESTEMMELING, BriefVerzondenPeer::BRIEF_TEMPLATE_ID, BriefVerzondenPeer::ONDERWERP, BriefVerzondenPeer::HTML, BriefVerzondenPeer::MEDIUM, BriefVerzondenPeer::ADRES, BriefVerzondenPeer::CC, BriefVerzondenPeer::BCC, BriefVerzondenPeer::CUSTOM, BriefVerzondenPeer::CULTURE, BriefVerzondenPeer::STATUS, BriefVerzondenPeer::UUID, BriefVerzondenPeer::BOUNCE_MSG, BriefVerzondenPeer::CREATED_BY, BriefVerzondenPeer::UPDATED_BY, BriefVerzondenPeer::CREATED_AT, BriefVerzondenPeer::UPDATED_AT, ),
		BasePeer::TYPE_FIELDNAME => array ('id', 'object_class', 'object_id', 'object_class_bestemmeling', 'object_id_bestemmeling', 'brief_template_id', 'onderwerp', 'html', 'medium', 'adres', 'cc', 'bcc', 'custom', 'culture', 'status', 'uuid', 'bounce_msg', 'created_by', 'updated_by', 'created_at', 'updated_at', ),
		BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, )
	);

	/**
	 * holds an array of keys for quick access to the fieldnames array
	 *
	 * first dimension keys are the type constants
	 * e.g. self::$fieldNames[BasePeer::TYPE_PHPNAME]['Id'] = 0
	 */
	private static $fieldKeys = array (
		BasePeer::TYPE_PHPNAME => array ('Id' => 0, 'ObjectClass' => 1, 'ObjectId' => 2, 'ObjectClassBestemmeling' => 3, 'ObjectIdBestemmeling' => 4, 'BriefTemplateId' => 5, 'Onderwerp' => 6, 'Html' => 7, 'Medium' => 8, 'Adres' => 9, 'Cc' => 10, 'Bcc' => 11, 'Custom' => 12, 'Culture' => 13, 'Status' => 14, 'Uuid' => 15, 'BounceMsg' => 16, 'CreatedBy' => 17, 'UpdatedBy' => 18, 'CreatedAt' => 19, 'UpdatedAt' => 20, ),
		BasePeer::TYPE_COLNAME => array (BriefVerzondenPeer::ID => 0, BriefVerzondenPeer::OBJECT_CLASS => 1, BriefVerzondenPeer::OBJECT_ID => 2, BriefVerzondenPeer::OBJECT_CLASS_BESTEMMELING => 3, BriefVerzondenPeer::OBJECT_ID_BESTEMMELING => 4, BriefVerzondenPeer::BRIEF_TEMPLATE_ID => 5, BriefVerzondenPeer::ONDERWERP => 6, BriefVerzondenPeer::HTML => 7, BriefVerzondenPeer::MEDIUM => 8, BriefVerzondenPeer::ADRES => 9, BriefVerzondenPeer::CC => 10, BriefVerzondenPeer::BCC => 11, BriefVerzondenPeer::CUSTOM => 12, BriefVerzondenPeer::CULTURE => 13, BriefVerzondenPeer::STATUS => 14, BriefVerzondenPeer::UUID => 15, BriefVerzondenPeer::BOUNCE_MSG => 16, BriefVerzondenPeer::CREATED_BY => 17, BriefVerzondenPeer::UPDATED_BY => 18, BriefVerzondenPeer::CREATED_AT => 19, BriefVerzondenPeer::UPDATED_AT => 20, ),
		BasePeer::TYPE_FIELDNAME => array ('id' => 0, 'object_class' => 1, 'object_id' => 2, 'object_class_bestemmeling' => 3, 'object_id_bestemmeling' => 4, 'brief_template_id' => 5, 'onderwerp' => 6, 'html' => 7, 'medium' => 8, 'adres' => 9, 'cc' => 10, 'bcc' => 11, 'custom' => 12, 'culture' => 13, 'status' => 14, 'uuid' => 15, 'bounce_msg' => 16, 'created_by' => 17, 'updated_by' => 18, 'created_at' => 19, 'updated_at' => 20, ),
		BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, )
	);

	/**
	 * @return     MapBuilder the map builder for this peer
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function getMapBuilder()
	{
		include_once 'plugins/ttCommunicatiePlugin/lib/model/map/BriefVerzondenMapBuilder.php';
		return BasePeer::getMapBuilder('plugins.ttCommunicatiePlugin.lib.model.map.BriefVerzondenMapBuilder');
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
	/**
	 * Translates a fieldname to another type
	 *
	 * @param      string $name field name
	 * @param      string $fromType One of the class type constants TYPE_PHPNAME,
	 *                         TYPE_COLNAME, TYPE_FIELDNAME, TYPE_NUM
	 * @param      string $toType   One of the class type constants
	 * @return     string translated name of the field.
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
	 * Returns an array of of field names.
	 *
	 * @param      string $type The type of fieldnames to return:
	 *                      One of the class type constants TYPE_PHPNAME,
	 *                      TYPE_COLNAME, TYPE_FIELDNAME, TYPE_NUM
	 * @return     array A list of field names
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
	 * @param      string $column The column name for current table. (i.e. BriefVerzondenPeer::COLUMN_NAME).
	 * @return     string
	 */
	public static function alias($alias, $column)
	{
		return str_replace(BriefVerzondenPeer::TABLE_NAME.'.', $alias.'.', $column);
	}

	/**
	 * Add all the columns needed to create a new object.
	 *
	 * Note: any columns that were marked with lazyLoad="true" in the
	 * XML schema will not be added to the select list and only loaded
	 * on demand.
	 *
	 * @param      criteria object containing the columns to add.
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function addSelectColumns(Criteria $criteria)
	{

		$criteria->addSelectColumn(BriefVerzondenPeer::ID);

		$criteria->addSelectColumn(BriefVerzondenPeer::OBJECT_CLASS);

		$criteria->addSelectColumn(BriefVerzondenPeer::OBJECT_ID);

		$criteria->addSelectColumn(BriefVerzondenPeer::OBJECT_CLASS_BESTEMMELING);

		$criteria->addSelectColumn(BriefVerzondenPeer::OBJECT_ID_BESTEMMELING);

		$criteria->addSelectColumn(BriefVerzondenPeer::BRIEF_TEMPLATE_ID);

		$criteria->addSelectColumn(BriefVerzondenPeer::ONDERWERP);

		$criteria->addSelectColumn(BriefVerzondenPeer::HTML);

		$criteria->addSelectColumn(BriefVerzondenPeer::MEDIUM);

		$criteria->addSelectColumn(BriefVerzondenPeer::ADRES);

		$criteria->addSelectColumn(BriefVerzondenPeer::CC);

		$criteria->addSelectColumn(BriefVerzondenPeer::BCC);

		$criteria->addSelectColumn(BriefVerzondenPeer::CUSTOM);

		$criteria->addSelectColumn(BriefVerzondenPeer::CULTURE);

		$criteria->addSelectColumn(BriefVerzondenPeer::STATUS);

		$criteria->addSelectColumn(BriefVerzondenPeer::UUID);

		$criteria->addSelectColumn(BriefVerzondenPeer::BOUNCE_MSG);

		$criteria->addSelectColumn(BriefVerzondenPeer::CREATED_BY);

		$criteria->addSelectColumn(BriefVerzondenPeer::UPDATED_BY);

		$criteria->addSelectColumn(BriefVerzondenPeer::CREATED_AT);

		$criteria->addSelectColumn(BriefVerzondenPeer::UPDATED_AT);

	}

	const COUNT = 'COUNT(brief_verzonden.ID)';
	const COUNT_DISTINCT = 'COUNT(DISTINCT brief_verzonden.ID)';

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
			$criteria->addSelectColumn(BriefVerzondenPeer::COUNT_DISTINCT);
		} else {
			$criteria->addSelectColumn(BriefVerzondenPeer::COUNT);
		}

		// just in case we're grouping: add those columns to the select statement
		foreach($criteria->getGroupByColumns() as $column)
		{
			$criteria->addSelectColumn($column);
		}

		$rs = BriefVerzondenPeer::doSelectRS($criteria, $con);
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
	 * @return     BriefVerzonden
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
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
	/**
	 * Method to do selects.
	 *
	 * @param      Criteria $criteria The Criteria object used to build the SELECT statement.
	 * @param      Connection $con
	 * @return     array Array of selected Objects
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doSelect(Criteria $criteria, $con = null)
	{
		return BriefVerzondenPeer::populateObjects(BriefVerzondenPeer::doSelectRS($criteria, $con));
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
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function populateObjects(ResultSet $rs)
	{
		$results = array();
	
		// set the class once to avoid overhead in the loop
		$cls = BriefVerzondenPeer::getOMClass();
		$cls = Propel::import($cls);
		// populate the object(s)
		while($rs->next()) {
		
			$obj = new $cls();
			$obj->hydrate($rs);
			$results[] = $obj;
			
		}
		return $results;
	}

	/**
	 * Returns the number of rows matching criteria, joining the related BriefTemplate table
	 *
	 * @param      Criteria $c
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
			$criteria->addSelectColumn(BriefVerzondenPeer::COUNT_DISTINCT);
		} else {
			$criteria->addSelectColumn(BriefVerzondenPeer::COUNT);
		}

		// just in case we're grouping: add those columns to the select statement
		foreach($criteria->getGroupByColumns() as $column)
		{
			$criteria->addSelectColumn($column);
		}

		$criteria->addJoin(BriefVerzondenPeer::BRIEF_TEMPLATE_ID, BriefTemplatePeer::ID);

		$rs = BriefVerzondenPeer::doSelectRS($criteria, $con);
		if ($rs->next()) {
			return $rs->getInt(1);
		} else {
			// no rows returned; we infer that means 0 matches.
			return 0;
		}
	}


	/**
	 * Selects a collection of BriefVerzonden objects pre-filled with their BriefTemplate objects.
	 *
	 * @return     array Array of BriefVerzonden objects.
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
				$temp_obj2 = $temp_obj1->getBriefTemplate(); //CHECKME
				if ($temp_obj2->getPrimaryKey() === $obj2->getPrimaryKey()) {
					$newObject = false;
					// e.g. $author->addBookRelatedByBookId()
					$temp_obj2->addBriefVerzonden($obj1); //CHECKME
					break;
				}
			}
			if ($newObject) {
				$obj2->initBriefVerzondens();
				$obj2->addBriefVerzonden($obj1); //CHECKME
			}
			$results[] = $obj1;
		}
		return $results;
	}


	/**
	 * Returns the number of rows matching criteria, joining all related tables
	 *
	 * @param      Criteria $c
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
			$criteria->addSelectColumn(BriefVerzondenPeer::COUNT_DISTINCT);
		} else {
			$criteria->addSelectColumn(BriefVerzondenPeer::COUNT);
		}

		// just in case we're grouping: add those columns to the select statement
		foreach($criteria->getGroupByColumns() as $column)
		{
			$criteria->addSelectColumn($column);
		}

		$criteria->addJoin(BriefVerzondenPeer::BRIEF_TEMPLATE_ID, BriefTemplatePeer::ID);

		$rs = BriefVerzondenPeer::doSelectRS($criteria, $con);
		if ($rs->next()) {
			return $rs->getInt(1);
		} else {
			// no rows returned; we infer that means 0 matches.
			return 0;
		}
	}


	/**
	 * Selects a collection of BriefVerzonden objects pre-filled with all related objects.
	 *
	 * @return     array Array of BriefVerzonden objects.
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


				// Add objects for joined BriefTemplate rows
	
			$omClass = BriefTemplatePeer::getOMClass();


			$cls = Propel::import($omClass);
			$obj2 = new $cls();
			$obj2->hydrate($rs, $startcol2);

			$newObject = true;
			for ($j=0, $resCount=count($results); $j < $resCount; $j++) {
				$temp_obj1 = $results[$j];
				$temp_obj2 = $temp_obj1->getBriefTemplate(); // CHECKME
				if ($temp_obj2->getPrimaryKey() === $obj2->getPrimaryKey()) {
					$newObject = false;
					$temp_obj2->addBriefVerzonden($obj1); // CHECKME
					break;
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
		return BriefVerzondenPeer::CLASS_DEFAULT;
	}

	/**
	 * Method perform an INSERT on the database, given a BriefVerzonden or Criteria object.
	 *
	 * @param      mixed $values Criteria or BriefVerzonden object containing data that is used to create the INSERT statement.
	 * @param      Connection $con the connection to use
	 * @return     mixed The new primary key.
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
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
			$criteria = clone $values; // rename for clarity
		} else {
			$criteria = $values->buildCriteria(); // build Criteria from BriefVerzonden object
		}

		$criteria->remove(BriefVerzondenPeer::ID); // remove pkey col since this table uses auto-increment


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

		
    foreach (sfMixer::getCallables('BaseBriefVerzondenPeer:doInsert:post') as $callable)
    {
      call_user_func($callable, 'BaseBriefVerzondenPeer', $values, $con, $pk);
    }

    return $pk;
	}

	/**
	 * Method perform an UPDATE on the database, given a BriefVerzonden or Criteria object.
	 *
	 * @param      mixed $values Criteria or BriefVerzonden object containing data that is used to create the UPDATE statement.
	 * @param      Connection $con The connection to use (specify Connection object to exert more control over transactions).
	 * @return     int The number of affected rows (if supported by underlying database driver).
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
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
			$criteria = clone $values; // rename for clarity

			$comparison = $criteria->getComparison(BriefVerzondenPeer::ID);
			$selectCriteria->add(BriefVerzondenPeer::ID, $criteria->remove(BriefVerzondenPeer::ID), $comparison);

		} else { // $values is BriefVerzonden object
			$criteria = $values->buildCriteria(); // gets full criteria
			$selectCriteria = $values->buildPkeyCriteria(); // gets criteria w/ primary key(s)
		}

		// set the correct dbName
		$criteria->setDbName(self::DATABASE_NAME);

		$ret = BasePeer::doUpdate($selectCriteria, $criteria, $con);
	

    foreach (sfMixer::getCallables('BaseBriefVerzondenPeer:doUpdate:post') as $callable)
    {
      call_user_func($callable, 'BaseBriefVerzondenPeer', $values, $con, $ret);
    }

    return $ret;
  }

	/**
	 * Method to DELETE all rows from the brief_verzonden table.
	 *
	 * @return     int The number of affected rows (if supported by underlying database driver).
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
			$affectedRows += BasePeer::doDeleteAll(BriefVerzondenPeer::TABLE_NAME, $con);
			$con->commit();
			return $affectedRows;
		} catch (PropelException $e) {
			$con->rollback();
			throw $e;
		}
	}

	/**
	 * Method perform a DELETE on the database, given a BriefVerzonden or Criteria object OR a primary key value.
	 *
	 * @param      mixed $values Criteria or BriefVerzonden object or primary key or array of primary keys
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
			$con = Propel::getConnection(BriefVerzondenPeer::DATABASE_NAME);
		}

		if ($values instanceof Criteria) {
			$criteria = clone $values; // rename for clarity
		} elseif ($values instanceof BriefVerzonden) {

			$criteria = $values->buildPkeyCriteria();
		} else {
			// it must be the primary key
			$criteria = new Criteria(self::DATABASE_NAME);
			$criteria->add(BriefVerzondenPeer::ID, (array) $values, Criteria::IN);
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
	 * Validates all modified columns of given BriefVerzonden object.
	 * If parameter $columns is either a single column name or an array of column names
	 * than only those columns are validated.
	 *
	 * NOTICE: This does not apply to primary or foreign keys for now.
	 *
	 * @param      BriefVerzonden $obj The object to validate.
	 * @param      mixed $cols Column name or array of column names.
	 *
	 * @return     mixed TRUE if all columns are valid or the error message of the first invalid column.
	 */
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

		return BasePeer::doValidate(BriefVerzondenPeer::DATABASE_NAME, BriefVerzondenPeer::TABLE_NAME, $columns);
	}

	/**
	 * Retrieve a single object by pkey.
	 *
	 * @param      mixed $pk the primary key.
	 * @param      Connection $con the connection to use
	 * @return     BriefVerzonden
	 */
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

	/**
	 * Retrieve multiple objects by pkey.
	 *
	 * @param      array $pks List of primary keys
	 * @param      Connection $con the connection to use
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
			$criteria->add(BriefVerzondenPeer::ID, $pks, Criteria::IN);
			$objs = BriefVerzondenPeer::doSelect($criteria, $con);
		}
		return $objs;
	}

} // BaseBriefVerzondenPeer

// static code to register the map builder for this Peer with the main Propel class
if (Propel::isInit()) {
	// the MapBuilder classes register themselves with Propel during initialization
	// so we need to load them here.
	try {
		BaseBriefVerzondenPeer::getMapBuilder();
	} catch (Exception $e) {
		Propel::log('Could not initialize Peer: ' . $e->getMessage(), Propel::LOG_ERR);
	}
} else {
	// even if Propel is not yet initialized, the map builder class can be registered
	// now and then it will be loaded when Propel initializes.
	require_once 'plugins/ttCommunicatiePlugin/lib/model/map/BriefVerzondenMapBuilder.php';
	Propel::registerMapBuilder('plugins.ttCommunicatiePlugin.lib.model.map.BriefVerzondenMapBuilder');
}
