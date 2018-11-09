<?php

/**
 * Base static class for performing query and update operations on the 'brief_template' table.
 *
 * 
 *
 * @package    plugins.ttCommunicatiePlugin.lib.model.om
 */
abstract class BaseBriefTemplatePeer {

	/** the default database name for this class */
	const DATABASE_NAME = 'propel';

	/** the table name for this class */
	const TABLE_NAME = 'brief_template';

	/** A class that can be returned by this peer. */
	const CLASS_DEFAULT = 'plugins.ttCommunicatiePlugin.lib.model.BriefTemplate';

	/** The total number of columns. */
	const NUM_COLUMNS = 18;

	/** The number of lazy-loaded columns. */
	const NUM_LAZY_LOAD_COLUMNS = 0;


	/** the column name for the ID field */
	const ID = 'brief_template.ID';

	/** the column name for the CATEGORIE field */
	const CATEGORIE = 'brief_template.CATEGORIE';

	/** the column name for the BRIEF_LAYOUT_ID field */
	const BRIEF_LAYOUT_ID = 'brief_template.BRIEF_LAYOUT_ID';

	/** the column name for the ONDERWERP field */
	const ONDERWERP = 'brief_template.ONDERWERP';

	/** the column name for the NAAM field */
	const NAAM = 'brief_template.NAAM';

	/** the column name for the TYPE field */
	const TYPE = 'brief_template.TYPE';

	/** the column name for the BESTEMMELING_CLASSES field */
	const BESTEMMELING_CLASSES = 'brief_template.BESTEMMELING_CLASSES';

	/** the column name for the HTML field */
	const HTML = 'brief_template.HTML';

	/** the column name for the EENMALIG_VERSTUREN field */
	const EENMALIG_VERSTUREN = 'brief_template.EENMALIG_VERSTUREN';

	/** the column name for the SYSTEEMNAAM field */
	const SYSTEEMNAAM = 'brief_template.SYSTEEMNAAM';

	/** the column name for the SYSTEEMPLACEHOLDERS field */
	const SYSTEEMPLACEHOLDERS = 'brief_template.SYSTEEMPLACEHOLDERS';

	/** the column name for the GEARCHIVEERD field */
	const GEARCHIVEERD = 'brief_template.GEARCHIVEERD';

	/** the column name for the BEWERKBAAR field */
	const BEWERKBAAR = 'brief_template.BEWERKBAAR';

	/** the column name for the WEERGAVE_BEVEILIGD field */
	const WEERGAVE_BEVEILIGD = 'brief_template.WEERGAVE_BEVEILIGD';

	/** the column name for the CREATED_BY field */
	const CREATED_BY = 'brief_template.CREATED_BY';

	/** the column name for the UPDATED_BY field */
	const UPDATED_BY = 'brief_template.UPDATED_BY';

	/** the column name for the CREATED_AT field */
	const CREATED_AT = 'brief_template.CREATED_AT';

	/** the column name for the UPDATED_AT field */
	const UPDATED_AT = 'brief_template.UPDATED_AT';

	/** The PHP to DB Name Mapping */
	private static $phpNameMap = null;


	/**
	 * holds an array of fieldnames
	 *
	 * first dimension keys are the type constants
	 * e.g. self::$fieldNames[self::TYPE_PHPNAME][0] = 'Id'
	 */
	private static $fieldNames = array (
		BasePeer::TYPE_PHPNAME => array ('Id', 'Categorie', 'BriefLayoutId', 'Onderwerp', 'Naam', 'Type', 'BestemmelingClasses', 'Html', 'EenmaligVersturen', 'Systeemnaam', 'Systeemplaceholders', 'Gearchiveerd', 'Bewerkbaar', 'WeergaveBeveiligd', 'CreatedBy', 'UpdatedBy', 'CreatedAt', 'UpdatedAt', ),
		BasePeer::TYPE_COLNAME => array (BriefTemplatePeer::ID, BriefTemplatePeer::CATEGORIE, BriefTemplatePeer::BRIEF_LAYOUT_ID, BriefTemplatePeer::ONDERWERP, BriefTemplatePeer::NAAM, BriefTemplatePeer::TYPE, BriefTemplatePeer::BESTEMMELING_CLASSES, BriefTemplatePeer::HTML, BriefTemplatePeer::EENMALIG_VERSTUREN, BriefTemplatePeer::SYSTEEMNAAM, BriefTemplatePeer::SYSTEEMPLACEHOLDERS, BriefTemplatePeer::GEARCHIVEERD, BriefTemplatePeer::BEWERKBAAR, BriefTemplatePeer::WEERGAVE_BEVEILIGD, BriefTemplatePeer::CREATED_BY, BriefTemplatePeer::UPDATED_BY, BriefTemplatePeer::CREATED_AT, BriefTemplatePeer::UPDATED_AT, ),
		BasePeer::TYPE_FIELDNAME => array ('id', 'categorie', 'brief_layout_id', 'onderwerp', 'naam', 'type', 'bestemmeling_classes', 'html', 'eenmalig_versturen', 'systeemnaam', 'systeemplaceholders', 'gearchiveerd', 'bewerkbaar', 'weergave_beveiligd', 'created_by', 'updated_by', 'created_at', 'updated_at', ),
		BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, )
	);

	/**
	 * holds an array of keys for quick access to the fieldnames array
	 *
	 * first dimension keys are the type constants
	 * e.g. self::$fieldNames[BasePeer::TYPE_PHPNAME]['Id'] = 0
	 */
	private static $fieldKeys = array (
		BasePeer::TYPE_PHPNAME => array ('Id' => 0, 'Categorie' => 1, 'BriefLayoutId' => 2, 'Onderwerp' => 3, 'Naam' => 4, 'Type' => 5, 'BestemmelingClasses' => 6, 'Html' => 7, 'EenmaligVersturen' => 8, 'Systeemnaam' => 9, 'Systeemplaceholders' => 10, 'Gearchiveerd' => 11, 'Bewerkbaar' => 12, 'WeergaveBeveiligd' => 13, 'CreatedBy' => 14, 'UpdatedBy' => 15, 'CreatedAt' => 16, 'UpdatedAt' => 17, ),
		BasePeer::TYPE_COLNAME => array (BriefTemplatePeer::ID => 0, BriefTemplatePeer::CATEGORIE => 1, BriefTemplatePeer::BRIEF_LAYOUT_ID => 2, BriefTemplatePeer::ONDERWERP => 3, BriefTemplatePeer::NAAM => 4, BriefTemplatePeer::TYPE => 5, BriefTemplatePeer::BESTEMMELING_CLASSES => 6, BriefTemplatePeer::HTML => 7, BriefTemplatePeer::EENMALIG_VERSTUREN => 8, BriefTemplatePeer::SYSTEEMNAAM => 9, BriefTemplatePeer::SYSTEEMPLACEHOLDERS => 10, BriefTemplatePeer::GEARCHIVEERD => 11, BriefTemplatePeer::BEWERKBAAR => 12, BriefTemplatePeer::WEERGAVE_BEVEILIGD => 13, BriefTemplatePeer::CREATED_BY => 14, BriefTemplatePeer::UPDATED_BY => 15, BriefTemplatePeer::CREATED_AT => 16, BriefTemplatePeer::UPDATED_AT => 17, ),
		BasePeer::TYPE_FIELDNAME => array ('id' => 0, 'categorie' => 1, 'brief_layout_id' => 2, 'onderwerp' => 3, 'naam' => 4, 'type' => 5, 'bestemmeling_classes' => 6, 'html' => 7, 'eenmalig_versturen' => 8, 'systeemnaam' => 9, 'systeemplaceholders' => 10, 'gearchiveerd' => 11, 'bewerkbaar' => 12, 'weergave_beveiligd' => 13, 'created_by' => 14, 'updated_by' => 15, 'created_at' => 16, 'updated_at' => 17, ),
		BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, )
	);

	/**
	 * @return     MapBuilder the map builder for this peer
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function getMapBuilder()
	{
		include_once 'plugins/ttCommunicatiePlugin/lib/model/map/BriefTemplateMapBuilder.php';
		return BasePeer::getMapBuilder('plugins.ttCommunicatiePlugin.lib.model.map.BriefTemplateMapBuilder');
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
	 * @param      string $column The column name for current table. (i.e. BriefTemplatePeer::COLUMN_NAME).
	 * @return     string
	 */
	public static function alias($alias, $column)
	{
		return str_replace(BriefTemplatePeer::TABLE_NAME.'.', $alias.'.', $column);
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
		  ? BriefTemplatePeer::alias($alias, BriefTemplatePeer::ID)
		  : BriefTemplatePeer::ID;
		$criteria->addSelectColumn($columnToSelect);

		$columnToSelect = $alias
		  ? BriefTemplatePeer::alias($alias, BriefTemplatePeer::CATEGORIE)
		  : BriefTemplatePeer::CATEGORIE;
		$criteria->addSelectColumn($columnToSelect);

		$columnToSelect = $alias
		  ? BriefTemplatePeer::alias($alias, BriefTemplatePeer::BRIEF_LAYOUT_ID)
		  : BriefTemplatePeer::BRIEF_LAYOUT_ID;
		$criteria->addSelectColumn($columnToSelect);

		$columnToSelect = $alias
		  ? BriefTemplatePeer::alias($alias, BriefTemplatePeer::ONDERWERP)
		  : BriefTemplatePeer::ONDERWERP;
		$criteria->addSelectColumn($columnToSelect);

		$columnToSelect = $alias
		  ? BriefTemplatePeer::alias($alias, BriefTemplatePeer::NAAM)
		  : BriefTemplatePeer::NAAM;
		$criteria->addSelectColumn($columnToSelect);

		$columnToSelect = $alias
		  ? BriefTemplatePeer::alias($alias, BriefTemplatePeer::TYPE)
		  : BriefTemplatePeer::TYPE;
		$criteria->addSelectColumn($columnToSelect);

		$columnToSelect = $alias
		  ? BriefTemplatePeer::alias($alias, BriefTemplatePeer::BESTEMMELING_CLASSES)
		  : BriefTemplatePeer::BESTEMMELING_CLASSES;
		$criteria->addSelectColumn($columnToSelect);

		$columnToSelect = $alias
		  ? BriefTemplatePeer::alias($alias, BriefTemplatePeer::HTML)
		  : BriefTemplatePeer::HTML;
		$criteria->addSelectColumn($columnToSelect);

		$columnToSelect = $alias
		  ? BriefTemplatePeer::alias($alias, BriefTemplatePeer::EENMALIG_VERSTUREN)
		  : BriefTemplatePeer::EENMALIG_VERSTUREN;
		$criteria->addSelectColumn($columnToSelect);

		$columnToSelect = $alias
		  ? BriefTemplatePeer::alias($alias, BriefTemplatePeer::SYSTEEMNAAM)
		  : BriefTemplatePeer::SYSTEEMNAAM;
		$criteria->addSelectColumn($columnToSelect);

		$columnToSelect = $alias
		  ? BriefTemplatePeer::alias($alias, BriefTemplatePeer::SYSTEEMPLACEHOLDERS)
		  : BriefTemplatePeer::SYSTEEMPLACEHOLDERS;
		$criteria->addSelectColumn($columnToSelect);

		$columnToSelect = $alias
		  ? BriefTemplatePeer::alias($alias, BriefTemplatePeer::GEARCHIVEERD)
		  : BriefTemplatePeer::GEARCHIVEERD;
		$criteria->addSelectColumn($columnToSelect);

		$columnToSelect = $alias
		  ? BriefTemplatePeer::alias($alias, BriefTemplatePeer::BEWERKBAAR)
		  : BriefTemplatePeer::BEWERKBAAR;
		$criteria->addSelectColumn($columnToSelect);

		$columnToSelect = $alias
		  ? BriefTemplatePeer::alias($alias, BriefTemplatePeer::WEERGAVE_BEVEILIGD)
		  : BriefTemplatePeer::WEERGAVE_BEVEILIGD;
		$criteria->addSelectColumn($columnToSelect);

		$columnToSelect = $alias
		  ? BriefTemplatePeer::alias($alias, BriefTemplatePeer::CREATED_BY)
		  : BriefTemplatePeer::CREATED_BY;
		$criteria->addSelectColumn($columnToSelect);

		$columnToSelect = $alias
		  ? BriefTemplatePeer::alias($alias, BriefTemplatePeer::UPDATED_BY)
		  : BriefTemplatePeer::UPDATED_BY;
		$criteria->addSelectColumn($columnToSelect);

		$columnToSelect = $alias
		  ? BriefTemplatePeer::alias($alias, BriefTemplatePeer::CREATED_AT)
		  : BriefTemplatePeer::CREATED_AT;
		$criteria->addSelectColumn($columnToSelect);

		$columnToSelect = $alias
		  ? BriefTemplatePeer::alias($alias, BriefTemplatePeer::UPDATED_AT)
		  : BriefTemplatePeer::UPDATED_AT;
		$criteria->addSelectColumn($columnToSelect);

	}

	const COUNT = 'COUNT(brief_template.ID)';
	const COUNT_DISTINCT = 'COUNT(DISTINCT brief_template.ID)';

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
			$criteria->addSelectColumn(BriefTemplatePeer::COUNT_DISTINCT);
		} else {
			$criteria->addSelectColumn(BriefTemplatePeer::COUNT);
		}

		// just in case we're grouping: add those columns to the select statement
		foreach($criteria->getGroupByColumns() as $column)
		{
			$criteria->addSelectColumn($column);
		}

		$rs = BriefTemplatePeer::doSelectRS($criteria, $con);
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
	 * @return     BriefTemplate
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
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
	/**
	 * Method to do selects.
	 *
	 * @param      Criteria $criteria The Criteria object used to build the SELECT statement.
	 * @param      Connection $con
	 * @return     BriefTemplate[] Array of selected Objects
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doSelect(Criteria $criteria, $con = null)
	{
		return BriefTemplatePeer::populateObjects(BriefTemplatePeer::doSelectRS($criteria, $con));
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
	 * @return     BriefTemplate[]
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function populateObjects(ResultSet $rs)
	{
		$results = array();
	
		// set the class once to avoid overhead in the loop
		$cls = BriefTemplatePeer::getOMClass();
		$cls = Propel::import($cls);
		// populate the object(s)
		while($rs->next()) {
		
			/** @var BriefTemplate $obj */
			$obj = new $cls();
			$obj->hydrate($rs);
			$results[] = $obj;
			
		}
		return $results;
	}

	/**
	 * Returns the number of rows matching criteria, joining the related BriefLayout table
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct Whether to select only distinct columns (You can also set DISTINCT modifier in Criteria).
	 * @param      Connection $con
	 * @return     int Number of matching rows.
	 */
	public static function doCountJoinBriefLayout(Criteria $criteria, $distinct = false, $con = null)
	{
		// we're going to modify criteria, so copy it first
		$criteria = clone $criteria;

		// clear out anything that might confuse the ORDER BY clause
		$criteria->clearSelectColumns()->clearOrderByColumns();
		if ($distinct || in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->addSelectColumn(BriefTemplatePeer::COUNT_DISTINCT);
		} else {
			$criteria->addSelectColumn(BriefTemplatePeer::COUNT);
		}

		// just in case we're grouping: add those columns to the select statement
		foreach($criteria->getGroupByColumns() as $column)
		{
			$criteria->addSelectColumn($column);
		}

		$criteria->addJoin(BriefTemplatePeer::BRIEF_LAYOUT_ID, BriefLayoutPeer::ID);

		$rs = BriefTemplatePeer::doSelectRS($criteria, $con);
		if ($rs->next()) {
			return $rs->getInt(1);
		} else {
			// no rows returned; we infer that means 0 matches.
			return 0;
		}
	}


	/**
	 * Selects a collection of BriefTemplate objects pre-filled with their BriefLayout objects.
	 *
	 * @param      Criteria $c
	 * @param      Connection $con
	 * @return     BriefTemplate[] array Array of BriefTemplate objects.
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doSelectJoinBriefLayout(Criteria $c, $con = null)
	{
		$c = clone $c;

		// Set the correct dbName if it has not been overridden
		if ($c->getDbName() == Propel::getDefaultDB()) {
			$c->setDbName(self::DATABASE_NAME);
		}

		BriefTemplatePeer::addSelectColumns($c);
		$startcol = (BriefTemplatePeer::NUM_COLUMNS - BriefTemplatePeer::NUM_LAZY_LOAD_COLUMNS) + 1;

		BriefLayoutPeer::addSelectColumns($c);

		$c->addJoin(BriefTemplatePeer::BRIEF_LAYOUT_ID, BriefLayoutPeer::ID, Criteria::JOIN);
		$rs = BasePeer::doSelect($c, $con);
		$results = array();

		while($rs->next()) {

			$omClass = BriefTemplatePeer::getOMClass();

			/** @var BriefTemplate $obj1 */
			$cls = Propel::import($omClass);
			$obj1 = new $cls();
			$obj1->hydrate($rs);

			$omClass = BriefLayoutPeer::getOMClass();

			/** @var BriefLayout $obj2 */
			$cls = Propel::import($omClass);
			$obj2 = new $cls();
			$obj2->hydrate($rs, $startcol);

			$newObject = true;
			/** @var BriefTemplate $temp_obj1 */
			foreach($results as $temp_obj1) {
				$temp_obj2 = $temp_obj1->getBriefLayout(); //CHECKME
				if ($temp_obj2->getPrimaryKey() === $obj2->getPrimaryKey()) {
					$newObject = false;
					// e.g. $author->addBookRelatedByBookId()
					$temp_obj2->addBriefTemplate($obj1); //CHECKME
					break;
				}
			}
			if ($newObject) {
				$obj2->initBriefTemplates();
				$obj2->addBriefTemplate($obj1); //CHECKME
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
			$criteria->addSelectColumn(BriefTemplatePeer::COUNT_DISTINCT);
		} else {
			$criteria->addSelectColumn(BriefTemplatePeer::COUNT);
		}

		// just in case we're grouping: add those columns to the select statement
		foreach($criteria->getGroupByColumns() as $column)
		{
			$criteria->addSelectColumn($column);
		}

		$criteria->addJoin(BriefTemplatePeer::BRIEF_LAYOUT_ID, BriefLayoutPeer::ID);

		$rs = BriefTemplatePeer::doSelectRS($criteria, $con);
		if ($rs->next()) {
			return $rs->getInt(1);
		} else {
			// no rows returned; we infer that means 0 matches.
			return 0;
		}
	}


	/**
	 * Selects a collection of BriefTemplate objects pre-filled with all related objects.
	 *
	 * @param      Criteria $c
	 * @param      Connection $con
	 * @return     BriefTemplate[] array Array of BriefTemplate objects.
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

		BriefTemplatePeer::addSelectColumns($c);
		$startcol2 = (BriefTemplatePeer::NUM_COLUMNS - BriefTemplatePeer::NUM_LAZY_LOAD_COLUMNS) + 1;

		BriefLayoutPeer::addSelectColumns($c);
		$startcol3 = $startcol2 + BriefLayoutPeer::NUM_COLUMNS;

		$c->addJoin(BriefTemplatePeer::BRIEF_LAYOUT_ID, BriefLayoutPeer::ID);

		$rs = BasePeer::doSelect($c, $con);
		$results = array();

		while($rs->next()) {

			$omClass = BriefTemplatePeer::getOMClass();

            /** @var BriefTemplate $obj1 */
			$cls = Propel::import($omClass);
			$obj1 = new $cls();
			$obj1->hydrate($rs);


				// Add objects for joined BriefLayout rows
	
			$omClass = BriefLayoutPeer::getOMClass();

            /** @var BriefLayout $obj2 */
			$cls = Propel::import($omClass);
			$obj2 = new $cls();
			$obj2->hydrate($rs, $startcol2);

			$newObject = true;
			for ($j=0, $resCount=count($results); $j < $resCount; $j++) {
			    /** @var BriefTemplate $temp_obj1 */
				$temp_obj1 = $results[$j];
				$temp_obj2 = $temp_obj1->getBriefLayout(); // CHECKME
				if ($temp_obj2->getPrimaryKey() === $obj2->getPrimaryKey()) {
					$newObject = false;
					$temp_obj2->addBriefTemplate($obj1); // CHECKME
					break;
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
		return BriefTemplatePeer::CLASS_DEFAULT;
	}

	/**
	 * Method perform an INSERT on the database, given a BriefTemplate or Criteria object.
	 *
	 * @param      mixed $values Criteria or BriefTemplate object containing data that is used to create the INSERT statement.
	 * @param      Connection $con the connection to use
	 * @return     mixed The new primary key.
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
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
			$criteria = clone $values; // rename for clarity
		} else {
			$criteria = $values->buildCriteria(); // build Criteria from BriefTemplate object
		}

		$criteria->remove(BriefTemplatePeer::ID); // remove pkey col since this table uses auto-increment


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

		
    foreach (sfMixer::getCallables('BaseBriefTemplatePeer:doInsert:post') as $callable)
    {
      call_user_func($callable, 'BaseBriefTemplatePeer', $values, $con, $pk);
    }

    return $pk;
	}

	/**
	 * Method perform an UPDATE on the database, given a BriefTemplate or Criteria object.
	 *
	 * @param      mixed $values Criteria or BriefTemplate object containing data that is used to create the UPDATE statement.
	 * @param      Connection $con The connection to use (specify Connection object to exert more control over transactions).
	 * @return     int The number of affected rows (if supported by underlying database driver).
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
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
			$criteria = clone $values; // rename for clarity

			$comparison = $criteria->getComparison(BriefTemplatePeer::ID);
			$selectCriteria->add(BriefTemplatePeer::ID, $criteria->remove(BriefTemplatePeer::ID), $comparison);

		} else { // $values is BriefTemplate object
			$criteria = $values->buildCriteria(); // gets full criteria
			$selectCriteria = $values->buildPkeyCriteria(); // gets criteria w/ primary key(s)
		}

		// set the correct dbName
		$criteria->setDbName(self::DATABASE_NAME);

		$ret = BasePeer::doUpdate($selectCriteria, $criteria, $con);
	

    foreach (sfMixer::getCallables('BaseBriefTemplatePeer:doUpdate:post') as $callable)
    {
      call_user_func($callable, 'BaseBriefTemplatePeer', $values, $con, $ret);
    }

    return $ret;
  }

	/**
	 * Method to DELETE all rows from the brief_template table.
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
			$affectedRows += BasePeer::doDeleteAll(BriefTemplatePeer::TABLE_NAME, $con);
			$con->commit();
			return $affectedRows;
		} catch (PropelException $e) {
			$con->rollback();
			throw $e;
		}
	}

	/**
	 * Method perform a DELETE on the database, given a BriefTemplate or Criteria object OR a primary key value.
	 *
	 * @param      mixed $values Criteria or BriefTemplate object or primary key or array of primary keys
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
			$con = Propel::getConnection(BriefTemplatePeer::DATABASE_NAME);
		}

		if ($values instanceof Criteria) {
			$criteria = clone $values; // rename for clarity
		} elseif ($values instanceof BriefTemplate) {

			$criteria = $values->buildPkeyCriteria();
		} else {
			// it must be the primary key
			$criteria = new Criteria(self::DATABASE_NAME);
			$criteria->add(BriefTemplatePeer::ID, (array) $values, Criteria::IN);
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
	 * Validates all modified columns of given BriefTemplate object.
	 * If parameter $columns is either a single column name or an array of column names
	 * than only those columns are validated.
	 *
	 * NOTICE: This does not apply to primary or foreign keys for now.
	 *
	 * @param      BaseBriefTemplate $obj The object to validate.
	 * @param      mixed $cols Column name or array of column names.
	 *
	 * @return     mixed TRUE if all columns are valid or the error message of the first invalid column.
	 */
	public static function doValidate(BaseBriefTemplate $obj, $cols = null)
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

		return BasePeer::doValidate(BriefTemplatePeer::DATABASE_NAME, BriefTemplatePeer::TABLE_NAME, $columns);
	}

	/**
	 * Retrieve a single object by pkey.
	 *
	 * @param      mixed $pk the primary key.
	 * @param      Connection $con the connection to use
	 * @return     BriefTemplate
	 */
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

	/**
	 * Retrieve multiple objects by pkey.
	 *
	 * @param      array $pks List of primary keys
	 * @param      Connection $con the connection to use
	 * @return     BriefTemplate[]
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
			$criteria->add(BriefTemplatePeer::ID, $pks, Criteria::IN);
			$objs = BriefTemplatePeer::doSelect($criteria, $con);
		}
		return $objs;
	}

} // BaseBriefTemplatePeer

// static code to register the map builder for this Peer with the main Propel class
if (Propel::isInit()) {
	// the MapBuilder classes register themselves with Propel during initialization
	// so we need to load them here.
	try {
		BaseBriefTemplatePeer::getMapBuilder();
	} catch (Exception $e) {
		Propel::log('Could not initialize Peer: ' . $e->getMessage(), Propel::LOG_ERR);
	}
} else {
	// even if Propel is not yet initialized, the map builder class can be registered
	// now and then it will be loaded when Propel initializes.
	require_once 'plugins/ttCommunicatiePlugin/lib/model/map/BriefTemplateMapBuilder.php';
	Propel::registerMapBuilder('plugins.ttCommunicatiePlugin.lib.model.map.BriefTemplateMapBuilder');
}
