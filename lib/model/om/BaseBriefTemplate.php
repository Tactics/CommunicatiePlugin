<?php

/**
 * Base class that represents a row from the 'brief_template' table.
 *
 * 
 *
 * @package    plugins.ttCommunicatiePlugin.lib.model.om
 */
abstract class BaseBriefTemplate extends BaseObject  implements Persistent {


	/**
	 * The Peer class.
	 * Instance provides a convenient way of calling static methods on a class
	 * that calling code may not be able to identify.
	 * @var        BriefTemplatePeer
	 */
	protected static $peer;


	/**
	 * The value for the id field.
	 * @var        int
	 */
	protected $id;


	/**
	 * The value for the categorie field.
	 * @var        int
	 */
	protected $categorie;


	/**
	 * The value for the brief_layout_id field.
	 * @var        int
	 */
	protected $brief_layout_id;


	/**
	 * The value for the onderwerp field.
	 * @var        string
	 */
	protected $onderwerp;


	/**
	 * The value for the naam field.
	 * @var        string
	 */
	protected $naam;


	/**
	 * The value for the type field.
	 * @var        string
	 */
	protected $type;


	/**
	 * The value for the bestemmeling_classes field.
	 * @var        string
	 */
	protected $bestemmeling_classes;


	/**
	 * The value for the html field.
	 * @var        string
	 */
	protected $html;


	/**
	 * The value for the eenmalig_versturen field.
	 * @var        boolean
	 */
	protected $eenmalig_versturen;


	/**
	 * The value for the systeemnaam field.
	 * @var        string
	 */
	protected $systeemnaam;


	/**
	 * The value for the systeemplaceholders field.
	 * @var        string
	 */
	protected $systeemplaceholders;


	/**
	 * The value for the gearchiveerd field.
	 * @var        int
	 */
	protected $gearchiveerd = 0;


	/**
	 * The value for the bewerkbaar field.
	 * @var        int
	 */
	protected $bewerkbaar = 1;


	/**
	 * The value for the created_by field.
	 * @var        int
	 */
	protected $created_by;


	/**
	 * The value for the updated_by field.
	 * @var        int
	 */
	protected $updated_by;


	/**
	 * The value for the created_at field.
	 * @var        int
	 */
	protected $created_at;


	/**
	 * The value for the updated_at field.
	 * @var        int
	 */
	protected $updated_at;

	/**
	 * @var        BriefLayout
	 */
	protected $aBriefLayout;

	/**
	 * Collection to store aggregation of collBriefVerzondens.
	 * @var        array
	 */
	protected $collBriefVerzondens;

	/**
	 * The criteria used to select the current contents of collBriefVerzondens.
	 * @var        Criteria
	 */
	protected $lastBriefVerzondenCriteria = null;

	/**
	 * Collection to store aggregation of collBriefBijlages.
	 * @var        array
	 */
	protected $collBriefBijlages;

	/**
	 * The criteria used to select the current contents of collBriefBijlages.
	 * @var        Criteria
	 */
	protected $lastBriefBijlageCriteria = null;

	/**
	 * Flag to prevent endless save loop, if this object is referenced
	 * by another object which falls in this transaction.
	 * @var        boolean
	 */
	protected $alreadyInSave = false;

	/**
	 * Flag to prevent endless validation loop, if this object is referenced
	 * by another object which falls in this transaction.
	 * @var        boolean
	 */
	protected $alreadyInValidation = false;

	/**
	 * Get the [id] column value.
	 * 
	 * @return     int
	 */
	public function getId()
	{

		return $this->id;
	}

	/**
	 * Get the [categorie] column value.
	 * 
	 * @return     int
	 */
	public function getCategorie()
	{

		return $this->categorie;
	}

	/**
	 * Get the [brief_layout_id] column value.
	 * 
	 * @return     int
	 */
	public function getBriefLayoutId()
	{

		return $this->brief_layout_id;
	}

	/**
	 * Get the [onderwerp] column value.
	 * 
	 * @return     string
	 */
	public function getOnderwerp()
	{

		return $this->onderwerp;
	}

	/**
	 * Get the [naam] column value.
	 * 
	 * @return     string
	 */
	public function getNaam()
	{

		return $this->naam;
	}

	/**
	 * Get the [type] column value.
	 * 
	 * @return     string
	 */
	public function getType()
	{

		return $this->type;
	}

	/**
	 * Get the [bestemmeling_classes] column value.
	 * 
	 * @return     string
	 */
	public function getBestemmelingClasses()
	{

		return $this->bestemmeling_classes;
	}

	/**
	 * Get the [html] column value.
	 * 
	 * @return     string
	 */
	public function getHtml()
	{

		return $this->html;
	}

	/**
	 * Get the [eenmalig_versturen] column value.
	 * 
	 * @return     boolean
	 */
	public function getEenmaligVersturen()
	{

		return $this->eenmalig_versturen;
	}

	/**
	 * Get the [systeemnaam] column value.
	 * 
	 * @return     string
	 */
	public function getSysteemnaam()
	{

		return $this->systeemnaam;
	}

	/**
	 * Get the [systeemplaceholders] column value.
	 * 
	 * @return     string
	 */
	public function getSysteemplaceholders()
	{

		return $this->systeemplaceholders;
	}

	/**
	 * Get the [gearchiveerd] column value.
	 * 
	 * @return     int
	 */
	public function getGearchiveerd()
	{

		return $this->gearchiveerd;
	}

	/**
	 * Get the [bewerkbaar] column value.
	 * 
	 * @return     int
	 */
	public function getBewerkbaar()
	{

		return $this->bewerkbaar;
	}

	/**
	 * Get the [created_by] column value.
	 * 
	 * @return     int
	 */
	public function getCreatedBy()
	{

		return $this->created_by;
	}

	/**
	 * Get the [updated_by] column value.
	 * 
	 * @return     int
	 */
	public function getUpdatedBy()
	{

		return $this->updated_by;
	}

	/**
	 * Get the [optionally formatted] [created_at] column value.
	 * 
	 * @param      string $format The date/time format string (either date()-style or strftime()-style).
	 *							If format is NULL, then the integer unix timestamp will be returned.
	 * @return     mixed Formatted date/time value as string or integer unix timestamp (if format is NULL).
	 * @throws     PropelException - if unable to convert the date/time to timestamp.
	 */
	public function getCreatedAt($format = 'Y-m-d H:i:s')
	{

		if ($this->created_at === null || $this->created_at === '') {
			return null;
		} elseif (!is_int($this->created_at)) {
			// a non-timestamp value was set externally, so we convert it
			$ts = strtotime($this->created_at);
			if ($ts === -1 || $ts === false) { // in PHP 5.1 return value changes to FALSE
				throw new PropelException("Unable to parse value of [created_at] as date/time value: " . var_export($this->created_at, true));
			}
		} else {
			$ts = $this->created_at;
		}
		if ($format === null) {
			return $ts;
		} elseif (strpos($format, '%') !== false) {
			return strftime($format, $ts);
		} else {
			return date($format, $ts);
		}
	}

	/**
	 * Get the [optionally formatted] [updated_at] column value.
	 * 
	 * @param      string $format The date/time format string (either date()-style or strftime()-style).
	 *							If format is NULL, then the integer unix timestamp will be returned.
	 * @return     mixed Formatted date/time value as string or integer unix timestamp (if format is NULL).
	 * @throws     PropelException - if unable to convert the date/time to timestamp.
	 */
	public function getUpdatedAt($format = 'Y-m-d H:i:s')
	{

		if ($this->updated_at === null || $this->updated_at === '') {
			return null;
		} elseif (!is_int($this->updated_at)) {
			// a non-timestamp value was set externally, so we convert it
			$ts = strtotime($this->updated_at);
			if ($ts === -1 || $ts === false) { // in PHP 5.1 return value changes to FALSE
				throw new PropelException("Unable to parse value of [updated_at] as date/time value: " . var_export($this->updated_at, true));
			}
		} else {
			$ts = $this->updated_at;
		}
		if ($format === null) {
			return $ts;
		} elseif (strpos($format, '%') !== false) {
			return strftime($format, $ts);
		} else {
			return date($format, $ts);
		}
	}

	/**
	 * Set the value of [id] column.
	 * 
	 * @param      int $v new value
	 * @return     void
	 */
	public function setId($v)
	{

		// Since the native PHP type for this column is integer,
		// we will cast the input value to an int (if it is not).
		if ($v !== null && !is_int($v) && is_numeric($v)) {
			$v = (int) $v;
		}

		if ($this->id !== $v) {
			$this->id = $v;
			$this->modifiedColumns[] = BriefTemplatePeer::ID;
		}

	} // setId()

	/**
	 * Set the value of [categorie] column.
	 * 
	 * @param      int $v new value
	 * @return     void
	 */
	public function setCategorie($v)
	{

		// Since the native PHP type for this column is integer,
		// we will cast the input value to an int (if it is not).
		if ($v !== null && !is_int($v) && is_numeric($v)) {
			$v = (int) $v;
		}

		if ($this->categorie !== $v) {
			$this->categorie = $v;
			$this->modifiedColumns[] = BriefTemplatePeer::CATEGORIE;
		}

	} // setCategorie()

	/**
	 * Set the value of [brief_layout_id] column.
	 * 
	 * @param      int $v new value
	 * @return     void
	 */
	public function setBriefLayoutId($v)
	{

		// Since the native PHP type for this column is integer,
		// we will cast the input value to an int (if it is not).
		if ($v !== null && !is_int($v) && is_numeric($v)) {
			$v = (int) $v;
		}

		if ($this->brief_layout_id !== $v) {
			$this->brief_layout_id = $v;
			$this->modifiedColumns[] = BriefTemplatePeer::BRIEF_LAYOUT_ID;
		}

		if ($this->aBriefLayout !== null && $this->aBriefLayout->getId() !== $v) {
			$this->aBriefLayout = null;
		}

	} // setBriefLayoutId()

	/**
	 * Set the value of [onderwerp] column.
	 * 
	 * @param      string $v new value
	 * @return     void
	 */
	public function setOnderwerp($v)
	{

		// Since the native PHP type for this column is string,
		// we will cast the input to a string (if it is not).
		if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->onderwerp !== $v) {
			$this->onderwerp = $v;
			$this->modifiedColumns[] = BriefTemplatePeer::ONDERWERP;
		}

	} // setOnderwerp()

	/**
	 * Set the value of [naam] column.
	 * 
	 * @param      string $v new value
	 * @return     void
	 */
	public function setNaam($v)
	{

		// Since the native PHP type for this column is string,
		// we will cast the input to a string (if it is not).
		if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->naam !== $v) {
			$this->naam = $v;
			$this->modifiedColumns[] = BriefTemplatePeer::NAAM;
		}

	} // setNaam()

	/**
	 * Set the value of [type] column.
	 * 
	 * @param      string $v new value
	 * @return     void
	 */
	public function setType($v)
	{

		// Since the native PHP type for this column is string,
		// we will cast the input to a string (if it is not).
		if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->type !== $v) {
			$this->type = $v;
			$this->modifiedColumns[] = BriefTemplatePeer::TYPE;
		}

	} // setType()

	/**
	 * Set the value of [bestemmeling_classes] column.
	 * 
	 * @param      string $v new value
	 * @return     void
	 */
	public function setBestemmelingClasses($v)
	{

		// Since the native PHP type for this column is string,
		// we will cast the input to a string (if it is not).
		if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->bestemmeling_classes !== $v) {
			$this->bestemmeling_classes = $v;
			$this->modifiedColumns[] = BriefTemplatePeer::BESTEMMELING_CLASSES;
		}

	} // setBestemmelingClasses()

	/**
	 * Set the value of [html] column.
	 * 
	 * @param      string $v new value
	 * @return     void
	 */
	public function setHtml($v)
	{

		// Since the native PHP type for this column is string,
		// we will cast the input to a string (if it is not).
		if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->html !== $v) {
			$this->html = $v;
			$this->modifiedColumns[] = BriefTemplatePeer::HTML;
		}

	} // setHtml()

	/**
	 * Set the value of [eenmalig_versturen] column.
	 * 
	 * @param      boolean $v new value
	 * @return     void
	 */
	public function setEenmaligVersturen($v)
	{

		if ($this->eenmalig_versturen !== $v) {
			$this->eenmalig_versturen = $v;
			$this->modifiedColumns[] = BriefTemplatePeer::EENMALIG_VERSTUREN;
		}

	} // setEenmaligVersturen()

	/**
	 * Set the value of [systeemnaam] column.
	 * 
	 * @param      string $v new value
	 * @return     void
	 */
	public function setSysteemnaam($v)
	{

		// Since the native PHP type for this column is string,
		// we will cast the input to a string (if it is not).
		if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->systeemnaam !== $v) {
			$this->systeemnaam = $v;
			$this->modifiedColumns[] = BriefTemplatePeer::SYSTEEMNAAM;
		}

	} // setSysteemnaam()

	/**
	 * Set the value of [systeemplaceholders] column.
	 * 
	 * @param      string $v new value
	 * @return     void
	 */
	public function setSysteemplaceholders($v)
	{

		// Since the native PHP type for this column is string,
		// we will cast the input to a string (if it is not).
		if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->systeemplaceholders !== $v) {
			$this->systeemplaceholders = $v;
			$this->modifiedColumns[] = BriefTemplatePeer::SYSTEEMPLACEHOLDERS;
		}

	} // setSysteemplaceholders()

	/**
	 * Set the value of [gearchiveerd] column.
	 * 
	 * @param      int $v new value
	 * @return     void
	 */
	public function setGearchiveerd($v)
	{

		// Since the native PHP type for this column is integer,
		// we will cast the input value to an int (if it is not).
		if ($v !== null && !is_int($v) && is_numeric($v)) {
			$v = (int) $v;
		}

		if ($this->gearchiveerd !== $v || $v === 0) {
			$this->gearchiveerd = $v;
			$this->modifiedColumns[] = BriefTemplatePeer::GEARCHIVEERD;
		}

	} // setGearchiveerd()

	/**
	 * Set the value of [bewerkbaar] column.
	 * 
	 * @param      int $v new value
	 * @return     void
	 */
	public function setBewerkbaar($v)
	{

		// Since the native PHP type for this column is integer,
		// we will cast the input value to an int (if it is not).
		if ($v !== null && !is_int($v) && is_numeric($v)) {
			$v = (int) $v;
		}

		if ($this->bewerkbaar !== $v || $v === 1) {
			$this->bewerkbaar = $v;
			$this->modifiedColumns[] = BriefTemplatePeer::BEWERKBAAR;
		}

	} // setBewerkbaar()

	/**
	 * Set the value of [created_by] column.
	 * 
	 * @param      int $v new value
	 * @return     void
	 */
	public function setCreatedBy($v)
	{

		// Since the native PHP type for this column is integer,
		// we will cast the input value to an int (if it is not).
		if ($v !== null && !is_int($v) && is_numeric($v)) {
			$v = (int) $v;
		}

		if ($this->created_by !== $v) {
			$this->created_by = $v;
			$this->modifiedColumns[] = BriefTemplatePeer::CREATED_BY;
		}

	} // setCreatedBy()

	/**
	 * Set the value of [updated_by] column.
	 * 
	 * @param      int $v new value
	 * @return     void
	 */
	public function setUpdatedBy($v)
	{

		// Since the native PHP type for this column is integer,
		// we will cast the input value to an int (if it is not).
		if ($v !== null && !is_int($v) && is_numeric($v)) {
			$v = (int) $v;
		}

		if ($this->updated_by !== $v) {
			$this->updated_by = $v;
			$this->modifiedColumns[] = BriefTemplatePeer::UPDATED_BY;
		}

	} // setUpdatedBy()

	/**
	 * Set the value of [created_at] column.
	 * 
	 * @param      int $v new value
	 * @return     void
	 */
	public function setCreatedAt($v)
	{

		if ($v !== null && !is_int($v)) {
			$ts = strtotime($v);
			if ($ts === -1 || $ts === false) { // in PHP 5.1 return value changes to FALSE
				throw new PropelException("Unable to parse date/time value for [created_at] from input: " . var_export($v, true));
			}
		} else {
			$ts = $v;
		}
		if ($this->created_at !== $ts) {
			$this->created_at = $ts;
			$this->modifiedColumns[] = BriefTemplatePeer::CREATED_AT;
		}

	} // setCreatedAt()

	/**
	 * Set the value of [updated_at] column.
	 * 
	 * @param      int $v new value
	 * @return     void
	 */
	public function setUpdatedAt($v)
	{

		if ($v !== null && !is_int($v)) {
			$ts = strtotime($v);
			if ($ts === -1 || $ts === false) { // in PHP 5.1 return value changes to FALSE
				throw new PropelException("Unable to parse date/time value for [updated_at] from input: " . var_export($v, true));
			}
		} else {
			$ts = $v;
		}
		if ($this->updated_at !== $ts) {
			$this->updated_at = $ts;
			$this->modifiedColumns[] = BriefTemplatePeer::UPDATED_AT;
		}

	} // setUpdatedAt()

	/**
	 * Hydrates (populates) the object variables with values from the database resultset.
	 *
	 * An offset (1-based "start column") is specified so that objects can be hydrated
	 * with a subset of the columns in the resultset rows.  This is needed, for example,
	 * for results of JOIN queries where the resultset row includes columns from two or
	 * more tables.
	 *
	 * @param      ResultSet $rs The ResultSet class with cursor advanced to desired record pos.
	 * @param      int $startcol 1-based offset column which indicates which restultset column to start with.
	 * @return     int next starting column
	 * @throws     PropelException  - Any caught Exception will be rewrapped as a PropelException.
	 */
	public function hydrate(ResultSet $rs, $startcol = 1)
	{
		try {

			$this->id = $rs->getInt($startcol + 0);

			$this->categorie = $rs->getInt($startcol + 1);

			$this->brief_layout_id = $rs->getInt($startcol + 2);

			$this->onderwerp = $rs->getString($startcol + 3);

			$this->naam = $rs->getString($startcol + 4);

			$this->type = $rs->getString($startcol + 5);

			$this->bestemmeling_classes = $rs->getString($startcol + 6);

			$this->html = $rs->getString($startcol + 7);

			$this->eenmalig_versturen = $rs->getBoolean($startcol + 8);

			$this->systeemnaam = $rs->getString($startcol + 9);

			$this->systeemplaceholders = $rs->getString($startcol + 10);

			$this->gearchiveerd = $rs->getInt($startcol + 11);

			$this->bewerkbaar = $rs->getInt($startcol + 12);

			$this->created_by = $rs->getInt($startcol + 13);

			$this->updated_by = $rs->getInt($startcol + 14);

			$this->created_at = $rs->getTimestamp($startcol + 15, null);

			$this->updated_at = $rs->getTimestamp($startcol + 16, null);

			$this->resetModified();

			$this->setNew(false);

			// FIXME - using NUM_COLUMNS may be clearer.
			return $startcol + 17; // 17 = BriefTemplatePeer::NUM_COLUMNS - BriefTemplatePeer::NUM_LAZY_LOAD_COLUMNS).

		} catch (Exception $e) {
			throw new PropelException("Error populating BriefTemplate object", $e);
		}
	}

	/**
	 * Removes this object from datastore and sets delete attribute.
	 *
	 * @param      Connection $con
	 * @return     void
	 * @throws     PropelException
	 * @see        BaseObject::setDeleted()
	 * @see        BaseObject::isDeleted()
	 */
	public function delete($con = null)
	{

    foreach (sfMixer::getCallables('BaseBriefTemplate:delete:pre') as $callable)
    {
      $ret = call_user_func($callable, $this, $con);
      if ($ret)
      {
        return;
      }
    }


		if ($this->isDeleted()) {
			throw new PropelException("This object has already been deleted.");
		}

		if ($con === null) {
			$con = Propel::getConnection(BriefTemplatePeer::DATABASE_NAME);
		}

		try {
			$con->begin();
			BriefTemplatePeer::doDelete($this, $con);
			$this->setDeleted(true);
			$con->commit();
		} catch (PropelException $e) {
			$con->rollback();
			throw $e;
		}
	

    foreach (sfMixer::getCallables('BaseBriefTemplate:delete:post') as $callable)
    {
      call_user_func($callable, $this, $con);
    }

  }
	/**
	 * Stores the object in the database.  If the object is new,
	 * it inserts it; otherwise an update is performed.  This method
	 * wraps the doSave() worker method in a transaction.
	 *
	 * @param      Connection $con
	 * @return     int The number of rows affected by this insert/update and any referring fk objects' save() operations.
	 * @throws     PropelException
	 * @see        doSave()
	 */
	public function save($con = null)
	{

    foreach (sfMixer::getCallables('BaseBriefTemplate:save:pre') as $callable)
    {
      $affectedRows = call_user_func($callable, $this, $con);
      if (is_int($affectedRows))
      {
        return $affectedRows;
      }
    }


    if ($this->isNew() && !$this->isColumnModified(BriefTemplatePeer::CREATED_AT))
    {
      $this->setCreatedAt(time());
    }

    if ($this->isModified() && !$this->isColumnModified(BriefTemplatePeer::UPDATED_AT))
    {
      $this->setUpdatedAt(time());
    }

		if ($this->isDeleted()) {
			throw new PropelException("You cannot save an object that has been deleted.");
		}

		if ($con === null) {
			$con = Propel::getConnection(BriefTemplatePeer::DATABASE_NAME);
		}

		try {
			$con->begin();
			$affectedRows = $this->doSave($con);
			$con->commit();
    foreach (sfMixer::getCallables('BaseBriefTemplate:save:post') as $callable)
    {
      call_user_func($callable, $this, $con, $affectedRows);
    }

			return $affectedRows;
		} catch (PropelException $e) {
			$con->rollback();
			throw $e;
		}
	}

	/**
	 * Stores the object in the database.
	 *
	 * If the object is new, it inserts it; otherwise an update is performed.
	 * All related objects are also updated in this method.
	 *
	 * @param      Connection $con
	 * @return     int The number of rows affected by this insert/update and any referring fk objects' save() operations.
	 * @throws     PropelException
	 * @see        save()
	 */
	protected function doSave($con)
	{
		$affectedRows = 0; // initialize var to track total num of affected rows
		if (!$this->alreadyInSave) {
			$this->alreadyInSave = true;


			// We call the save method on the following object(s) if they
			// were passed to this object by their coresponding set
			// method.  This object relates to these object(s) by a
			// foreign key reference.

			if ($this->aBriefLayout !== null) {
				if ($this->aBriefLayout->isModified()) {
					$affectedRows += $this->aBriefLayout->save($con);
				}
				$this->setBriefLayout($this->aBriefLayout);
			}


			// If this object has been modified, then save it to the database.
			if ($this->isModified()) {
				if ($this->isNew()) {
					$pk = BriefTemplatePeer::doInsert($this, $con);
					$affectedRows += 1; // we are assuming that there is only 1 row per doInsert() which
										 // should always be true here (even though technically
										 // BasePeer::doInsert() can insert multiple rows).

					$this->setId($pk);  //[IMV] update autoincrement primary key

					$this->setNew(false);
				} else {
					$affectedRows += BriefTemplatePeer::doUpdate($this, $con);
				}
				$this->resetModified(); // [HL] After being saved an object is no longer 'modified'
			}

			if ($this->collBriefVerzondens !== null) {
				foreach($this->collBriefVerzondens as $referrerFK) {
					if (!$referrerFK->isDeleted()) {
						$affectedRows += $referrerFK->save($con);
					}
				}
			}

			if ($this->collBriefBijlages !== null) {
				foreach($this->collBriefBijlages as $referrerFK) {
					if (!$referrerFK->isDeleted()) {
						$affectedRows += $referrerFK->save($con);
					}
				}
			}

			$this->alreadyInSave = false;
		}
		return $affectedRows;
	} // doSave()

	/**
	 * Array of ValidationFailed objects.
	 * @var        array ValidationFailed[]
	 */
	protected $validationFailures = array();

	/**
	 * Gets any ValidationFailed objects that resulted from last call to validate().
	 *
	 *
	 * @return     array ValidationFailed[]
	 * @see        validate()
	 */
	public function getValidationFailures()
	{
		return $this->validationFailures;
	}

	/**
	 * Validates the objects modified field values and all objects related to this table.
	 *
	 * If $columns is either a column name or an array of column names
	 * only those columns are validated.
	 *
	 * @param      mixed $columns Column name or an array of column names.
	 * @return     boolean Whether all columns pass validation.
	 * @see        doValidate()
	 * @see        getValidationFailures()
	 */
	public function validate($columns = null)
	{
		$res = $this->doValidate($columns);
		if ($res === true) {
			$this->validationFailures = array();
			return true;
		} else {
			$this->validationFailures = $res;
			return false;
		}
	}

	/**
	 * This function performs the validation work for complex object models.
	 *
	 * In addition to checking the current object, all related objects will
	 * also be validated.  If all pass then <code>true</code> is returned; otherwise
	 * an aggreagated array of ValidationFailed objects will be returned.
	 *
	 * @param      array $columns Array of column names to validate.
	 * @return     mixed <code>true</code> if all validations pass; array of <code>ValidationFailed</code> objets otherwise.
	 */
	protected function doValidate($columns = null)
	{
		if (!$this->alreadyInValidation) {
			$this->alreadyInValidation = true;
			$retval = null;

			$failureMap = array();


			// We call the validate method on the following object(s) if they
			// were passed to this object by their coresponding set
			// method.  This object relates to these object(s) by a
			// foreign key reference.

			if ($this->aBriefLayout !== null) {
				if (!$this->aBriefLayout->validate($columns)) {
					$failureMap = array_merge($failureMap, $this->aBriefLayout->getValidationFailures());
				}
			}


			if (($retval = BriefTemplatePeer::doValidate($this, $columns)) !== true) {
				$failureMap = array_merge($failureMap, $retval);
			}


				if ($this->collBriefVerzondens !== null) {
					foreach($this->collBriefVerzondens as $referrerFK) {
						if (!$referrerFK->validate($columns)) {
							$failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
						}
					}
				}

				if ($this->collBriefBijlages !== null) {
					foreach($this->collBriefBijlages as $referrerFK) {
						if (!$referrerFK->validate($columns)) {
							$failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
						}
					}
				}


			$this->alreadyInValidation = false;
		}

		return (!empty($failureMap) ? $failureMap : true);
	}

	/**
	 * Retrieves a field from the object by name passed in as a string.
	 *
	 * @param      string $name name
	 * @param      string $type The type of fieldname the $name is of:
	 *                     one of the class type constants TYPE_PHPNAME,
	 *                     TYPE_COLNAME, TYPE_FIELDNAME, TYPE_NUM
	 * @return     mixed Value of field.
	 */
	public function getByName($name, $type = BasePeer::TYPE_PHPNAME)
	{
		$pos = BriefTemplatePeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
		return $this->getByPosition($pos);
	}

	/**
	 * Retrieves a field from the object by Position as specified in the xml schema.
	 * Zero-based.
	 *
	 * @param      int $pos position in xml schema
	 * @return     mixed Value of field at $pos
	 */
	public function getByPosition($pos)
	{
		switch($pos) {
			case 0:
				return $this->getId();
				break;
			case 1:
				return $this->getCategorie();
				break;
			case 2:
				return $this->getBriefLayoutId();
				break;
			case 3:
				return $this->getOnderwerp();
				break;
			case 4:
				return $this->getNaam();
				break;
			case 5:
				return $this->getType();
				break;
			case 6:
				return $this->getBestemmelingClasses();
				break;
			case 7:
				return $this->getHtml();
				break;
			case 8:
				return $this->getEenmaligVersturen();
				break;
			case 9:
				return $this->getSysteemnaam();
				break;
			case 10:
				return $this->getSysteemplaceholders();
				break;
			case 11:
				return $this->getGearchiveerd();
				break;
			case 12:
				return $this->getBewerkbaar();
				break;
			case 13:
				return $this->getCreatedBy();
				break;
			case 14:
				return $this->getUpdatedBy();
				break;
			case 15:
				return $this->getCreatedAt();
				break;
			case 16:
				return $this->getUpdatedAt();
				break;
			default:
				return null;
				break;
		} // switch()
	}

	/**
	 * Exports the object as an array.
	 *
	 * You can specify the key type of the array by passing one of the class
	 * type constants.
	 *
	 * @param      string $keyType One of the class type constants TYPE_PHPNAME,
	 *                        TYPE_COLNAME, TYPE_FIELDNAME, TYPE_NUM
	 * @return     an associative array containing the field names (as keys) and field values
	 */
	public function toArray($keyType = BasePeer::TYPE_PHPNAME)
	{
		$keys = BriefTemplatePeer::getFieldNames($keyType);
		$result = array(
			$keys[0] => $this->getId(),
			$keys[1] => $this->getCategorie(),
			$keys[2] => $this->getBriefLayoutId(),
			$keys[3] => $this->getOnderwerp(),
			$keys[4] => $this->getNaam(),
			$keys[5] => $this->getType(),
			$keys[6] => $this->getBestemmelingClasses(),
			$keys[7] => $this->getHtml(),
			$keys[8] => $this->getEenmaligVersturen(),
			$keys[9] => $this->getSysteemnaam(),
			$keys[10] => $this->getSysteemplaceholders(),
			$keys[11] => $this->getGearchiveerd(),
			$keys[12] => $this->getBewerkbaar(),
			$keys[13] => $this->getCreatedBy(),
			$keys[14] => $this->getUpdatedBy(),
			$keys[15] => $this->getCreatedAt(),
			$keys[16] => $this->getUpdatedAt(),
		);
		return $result;
	}

	/**
	 * Sets a field from the object by name passed in as a string.
	 *
	 * @param      string $name peer name
	 * @param      mixed $value field value
	 * @param      string $type The type of fieldname the $name is of:
	 *                     one of the class type constants TYPE_PHPNAME,
	 *                     TYPE_COLNAME, TYPE_FIELDNAME, TYPE_NUM
	 * @return     void
	 */
	public function setByName($name, $value, $type = BasePeer::TYPE_PHPNAME)
	{
		$pos = BriefTemplatePeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
		return $this->setByPosition($pos, $value);
	}

	/**
	 * Sets a field from the object by Position as specified in the xml schema.
	 * Zero-based.
	 *
	 * @param      int $pos position in xml schema
	 * @param      mixed $value field value
	 * @return     void
	 */
	public function setByPosition($pos, $value)
	{
		switch($pos) {
			case 0:
				$this->setId($value);
				break;
			case 1:
				$this->setCategorie($value);
				break;
			case 2:
				$this->setBriefLayoutId($value);
				break;
			case 3:
				$this->setOnderwerp($value);
				break;
			case 4:
				$this->setNaam($value);
				break;
			case 5:
				$this->setType($value);
				break;
			case 6:
				$this->setBestemmelingClasses($value);
				break;
			case 7:
				$this->setHtml($value);
				break;
			case 8:
				$this->setEenmaligVersturen($value);
				break;
			case 9:
				$this->setSysteemnaam($value);
				break;
			case 10:
				$this->setSysteemplaceholders($value);
				break;
			case 11:
				$this->setGearchiveerd($value);
				break;
			case 12:
				$this->setBewerkbaar($value);
				break;
			case 13:
				$this->setCreatedBy($value);
				break;
			case 14:
				$this->setUpdatedBy($value);
				break;
			case 15:
				$this->setCreatedAt($value);
				break;
			case 16:
				$this->setUpdatedAt($value);
				break;
		} // switch()
	}

	/**
	 * Populates the object using an array.
	 *
	 * This is particularly useful when populating an object from one of the
	 * request arrays (e.g. $_POST).  This method goes through the column
	 * names, checking to see whether a matching key exists in populated
	 * array. If so the setByName() method is called for that column.
	 *
	 * You can specify the key type of the array by additionally passing one
	 * of the class type constants TYPE_PHPNAME, TYPE_COLNAME, TYPE_FIELDNAME,
	 * TYPE_NUM. The default key type is the column's phpname (e.g. 'authorId')
	 *
	 * @param      array  $arr     An array to populate the object from.
	 * @param      string $keyType The type of keys the array uses.
	 * @return     void
	 */
	public function fromArray($arr, $keyType = BasePeer::TYPE_PHPNAME)
	{
		$keys = BriefTemplatePeer::getFieldNames($keyType);

		if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
		if (array_key_exists($keys[1], $arr)) $this->setCategorie($arr[$keys[1]]);
		if (array_key_exists($keys[2], $arr)) $this->setBriefLayoutId($arr[$keys[2]]);
		if (array_key_exists($keys[3], $arr)) $this->setOnderwerp($arr[$keys[3]]);
		if (array_key_exists($keys[4], $arr)) $this->setNaam($arr[$keys[4]]);
		if (array_key_exists($keys[5], $arr)) $this->setType($arr[$keys[5]]);
		if (array_key_exists($keys[6], $arr)) $this->setBestemmelingClasses($arr[$keys[6]]);
		if (array_key_exists($keys[7], $arr)) $this->setHtml($arr[$keys[7]]);
		if (array_key_exists($keys[8], $arr)) $this->setEenmaligVersturen($arr[$keys[8]]);
		if (array_key_exists($keys[9], $arr)) $this->setSysteemnaam($arr[$keys[9]]);
		if (array_key_exists($keys[10], $arr)) $this->setSysteemplaceholders($arr[$keys[10]]);
		if (array_key_exists($keys[11], $arr)) $this->setGearchiveerd($arr[$keys[11]]);
		if (array_key_exists($keys[12], $arr)) $this->setBewerkbaar($arr[$keys[12]]);
		if (array_key_exists($keys[13], $arr)) $this->setCreatedBy($arr[$keys[13]]);
		if (array_key_exists($keys[14], $arr)) $this->setUpdatedBy($arr[$keys[14]]);
		if (array_key_exists($keys[15], $arr)) $this->setCreatedAt($arr[$keys[15]]);
		if (array_key_exists($keys[16], $arr)) $this->setUpdatedAt($arr[$keys[16]]);
	}

	/**
	 * Build a Criteria object containing the values of all modified columns in this object.
	 *
	 * @return     Criteria The Criteria object containing all modified values.
	 */
	public function buildCriteria()
	{
		$criteria = new Criteria(BriefTemplatePeer::DATABASE_NAME);

		if ($this->isColumnModified(BriefTemplatePeer::ID)) $criteria->add(BriefTemplatePeer::ID, $this->id);
		if ($this->isColumnModified(BriefTemplatePeer::CATEGORIE)) $criteria->add(BriefTemplatePeer::CATEGORIE, $this->categorie);
		if ($this->isColumnModified(BriefTemplatePeer::BRIEF_LAYOUT_ID)) $criteria->add(BriefTemplatePeer::BRIEF_LAYOUT_ID, $this->brief_layout_id);
		if ($this->isColumnModified(BriefTemplatePeer::ONDERWERP)) $criteria->add(BriefTemplatePeer::ONDERWERP, $this->onderwerp);
		if ($this->isColumnModified(BriefTemplatePeer::NAAM)) $criteria->add(BriefTemplatePeer::NAAM, $this->naam);
		if ($this->isColumnModified(BriefTemplatePeer::TYPE)) $criteria->add(BriefTemplatePeer::TYPE, $this->type);
		if ($this->isColumnModified(BriefTemplatePeer::BESTEMMELING_CLASSES)) $criteria->add(BriefTemplatePeer::BESTEMMELING_CLASSES, $this->bestemmeling_classes);
		if ($this->isColumnModified(BriefTemplatePeer::HTML)) $criteria->add(BriefTemplatePeer::HTML, $this->html);
		if ($this->isColumnModified(BriefTemplatePeer::EENMALIG_VERSTUREN)) $criteria->add(BriefTemplatePeer::EENMALIG_VERSTUREN, $this->eenmalig_versturen);
		if ($this->isColumnModified(BriefTemplatePeer::SYSTEEMNAAM)) $criteria->add(BriefTemplatePeer::SYSTEEMNAAM, $this->systeemnaam);
		if ($this->isColumnModified(BriefTemplatePeer::SYSTEEMPLACEHOLDERS)) $criteria->add(BriefTemplatePeer::SYSTEEMPLACEHOLDERS, $this->systeemplaceholders);
		if ($this->isColumnModified(BriefTemplatePeer::GEARCHIVEERD)) $criteria->add(BriefTemplatePeer::GEARCHIVEERD, $this->gearchiveerd);
		if ($this->isColumnModified(BriefTemplatePeer::BEWERKBAAR)) $criteria->add(BriefTemplatePeer::BEWERKBAAR, $this->bewerkbaar);
		if ($this->isColumnModified(BriefTemplatePeer::CREATED_BY)) $criteria->add(BriefTemplatePeer::CREATED_BY, $this->created_by);
		if ($this->isColumnModified(BriefTemplatePeer::UPDATED_BY)) $criteria->add(BriefTemplatePeer::UPDATED_BY, $this->updated_by);
		if ($this->isColumnModified(BriefTemplatePeer::CREATED_AT)) $criteria->add(BriefTemplatePeer::CREATED_AT, $this->created_at);
		if ($this->isColumnModified(BriefTemplatePeer::UPDATED_AT)) $criteria->add(BriefTemplatePeer::UPDATED_AT, $this->updated_at);

		return $criteria;
	}

	/**
	 * Builds a Criteria object containing the primary key for this object.
	 *
	 * Unlike buildCriteria() this method includes the primary key values regardless
	 * of whether or not they have been modified.
	 *
	 * @return     Criteria The Criteria object containing value(s) for primary key(s).
	 */
	public function buildPkeyCriteria()
	{
		$criteria = new Criteria(BriefTemplatePeer::DATABASE_NAME);

		$criteria->add(BriefTemplatePeer::ID, $this->id);

		return $criteria;
	}

	/**
	 * Returns the primary key for this object (row).
	 * @return     int
	 */
	public function getPrimaryKey()
	{
		return $this->getId();
	}

	/**
	 * Generic method to set the primary key (id column).
	 *
	 * @param      int $key Primary key.
	 * @return     void
	 */
	public function setPrimaryKey($key)
	{
		$this->setId($key);
	}

	/**
	 * Sets contents of passed object to values from current object.
	 *
	 * If desired, this method can also make copies of all associated (fkey referrers)
	 * objects.
	 *
	 * @param      object $copyObj An object of BriefTemplate (or compatible) type.
	 * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
	 * @throws     PropelException
	 */
	public function copyInto($copyObj, $deepCopy = false)
	{

		$copyObj->setCategorie($this->categorie);

		$copyObj->setBriefLayoutId($this->brief_layout_id);

		$copyObj->setOnderwerp($this->onderwerp);

		$copyObj->setNaam($this->naam);

		$copyObj->setType($this->type);

		$copyObj->setBestemmelingClasses($this->bestemmeling_classes);

		$copyObj->setHtml($this->html);

		$copyObj->setEenmaligVersturen($this->eenmalig_versturen);

		$copyObj->setSysteemnaam($this->systeemnaam);

		$copyObj->setSysteemplaceholders($this->systeemplaceholders);

		$copyObj->setGearchiveerd($this->gearchiveerd);

		$copyObj->setBewerkbaar($this->bewerkbaar);

		$copyObj->setCreatedBy($this->created_by);

		$copyObj->setUpdatedBy($this->updated_by);

		$copyObj->setCreatedAt($this->created_at);

		$copyObj->setUpdatedAt($this->updated_at);


		if ($deepCopy) {
			// important: temporarily setNew(false) because this affects the behavior of
			// the getter/setter methods for fkey referrer objects.
			$copyObj->setNew(false);

			foreach($this->getBriefVerzondens() as $relObj) {
				$copyObj->addBriefVerzonden($relObj->copy($deepCopy));
			}

			foreach($this->getBriefBijlages() as $relObj) {
				$copyObj->addBriefBijlage($relObj->copy($deepCopy));
			}

		} // if ($deepCopy)


		$copyObj->setNew(true);

		$copyObj->setId(NULL); // this is a pkey column, so set to default value

	}

	/**
	 * Makes a copy of this object that will be inserted as a new row in table when saved.
	 * It creates a new object filling in the simple attributes, but skipping any primary
	 * keys that are defined for the table.
	 *
	 * If desired, this method can also make copies of all associated (fkey referrers)
	 * objects.
	 *
	 * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
	 * @return     BriefTemplate Clone of current object.
	 * @throws     PropelException
	 */
	public function copy($deepCopy = false)
	{
		// we use get_class(), because this might be a subclass
		$clazz = get_class($this);
		$copyObj = new $clazz();
		$this->copyInto($copyObj, $deepCopy);
		return $copyObj;
	}

	/**
	 * Returns a peer instance associated with this om.
	 *
	 * Since Peer classes are not to have any instance attributes, this method returns the
	 * same instance for all member of this class. The method could therefore
	 * be static, but this would prevent one from overriding the behavior.
	 *
	 * @return     BriefTemplatePeer
	 */
	public function getPeer()
	{
		if (self::$peer === null) {
			self::$peer = new BriefTemplatePeer();
		}
		return self::$peer;
	}

	/**
	 * Declares an association between this object and a BriefLayout object.
	 *
	 * @param      BriefLayout $v
	 * @return     void
	 * @throws     PropelException
	 */
	public function setBriefLayout($v)
	{


		if ($v === null) {
			$this->setBriefLayoutId(NULL);
		} else {
			$this->setBriefLayoutId($v->getId());
		}


		$this->aBriefLayout = $v;
	}


	/**
	 * Get the associated BriefLayout object
	 *
	 * @param      Connection Optional Connection object.
	 * @return     BriefLayout The associated BriefLayout object.
	 * @throws     PropelException
	 */
	public function getBriefLayout($con = null)
	{
		if ($this->aBriefLayout === null && ($this->brief_layout_id !== null)) {
			// include the related Peer class
			include_once 'plugins/ttCommunicatiePlugin/lib/model/om/BaseBriefLayoutPeer.php';

			$this->aBriefLayout = BriefLayoutPeer::retrieveByPK($this->brief_layout_id, $con);

			/* The following can be used instead of the line above to
			   guarantee the related object contains a reference
			   to this object, but this level of coupling
			   may be undesirable in many circumstances.
			   As it can lead to a db query with many results that may
			   never be used.
			   $obj = BriefLayoutPeer::retrieveByPK($this->brief_layout_id, $con);
			   $obj->addBriefLayouts($this);
			 */
		}
		return $this->aBriefLayout;
	}

	/**
	 * Temporary storage of collBriefVerzondens to save a possible db hit in
	 * the event objects are add to the collection, but the
	 * complete collection is never requested.
	 * @return     void
	 */
	public function initBriefVerzondens()
	{
		if ($this->collBriefVerzondens === null) {
			$this->collBriefVerzondens = array();
		}
	}

	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this BriefTemplate has previously
	 * been saved, it will retrieve related BriefVerzondens from storage.
	 * If this BriefTemplate is new, it will return
	 * an empty collection or the current collection, the criteria
	 * is ignored on a new object.
	 *
	 * @param      Connection $con
	 * @param      Criteria $criteria
	 * @throws     PropelException
	 */
	public function getBriefVerzondens($criteria = null, $con = null)
	{
		// include the Peer class
		include_once 'plugins/ttCommunicatiePlugin/lib/model/om/BaseBriefVerzondenPeer.php';
		if ($criteria === null) {
			$criteria = new Criteria();
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collBriefVerzondens === null) {
			if ($this->isNew()) {
			   $this->collBriefVerzondens = array();
			} else {

				$criteria->add(BriefVerzondenPeer::BRIEF_TEMPLATE_ID, $this->getId());

				BriefVerzondenPeer::addSelectColumns($criteria);
				$this->collBriefVerzondens = BriefVerzondenPeer::doSelect($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return the collection.


				$criteria->add(BriefVerzondenPeer::BRIEF_TEMPLATE_ID, $this->getId());

				BriefVerzondenPeer::addSelectColumns($criteria);
				if (!isset($this->lastBriefVerzondenCriteria) || !$this->lastBriefVerzondenCriteria->equals($criteria)) {
					$this->collBriefVerzondens = BriefVerzondenPeer::doSelect($criteria, $con);
				}
			}
		}
		$this->lastBriefVerzondenCriteria = $criteria;
		return $this->collBriefVerzondens;
	}

	/**
	 * Returns the number of related BriefVerzondens.
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct
	 * @param      Connection $con
	 * @throws     PropelException
	 */
	public function countBriefVerzondens($criteria = null, $distinct = false, $con = null)
	{
		// include the Peer class
		include_once 'plugins/ttCommunicatiePlugin/lib/model/om/BaseBriefVerzondenPeer.php';
		if ($criteria === null) {
			$criteria = new Criteria();
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		$criteria->add(BriefVerzondenPeer::BRIEF_TEMPLATE_ID, $this->getId());

		return BriefVerzondenPeer::doCount($criteria, $distinct, $con);
	}

	/**
	 * Method called to associate a BriefVerzonden object to this object
	 * through the BriefVerzonden foreign key attribute
	 *
	 * @param      BriefVerzonden $l BriefVerzonden
	 * @return     void
	 * @throws     PropelException
	 */
	public function addBriefVerzonden(BriefVerzonden $l)
	{
		$this->collBriefVerzondens[] = $l;
		$l->setBriefTemplate($this);
	}

	/**
	 * Temporary storage of collBriefBijlages to save a possible db hit in
	 * the event objects are add to the collection, but the
	 * complete collection is never requested.
	 * @return     void
	 */
	public function initBriefBijlages()
	{
		if ($this->collBriefBijlages === null) {
			$this->collBriefBijlages = array();
		}
	}

	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this BriefTemplate has previously
	 * been saved, it will retrieve related BriefBijlages from storage.
	 * If this BriefTemplate is new, it will return
	 * an empty collection or the current collection, the criteria
	 * is ignored on a new object.
	 *
	 * @param      Connection $con
	 * @param      Criteria $criteria
	 * @throws     PropelException
	 */
	public function getBriefBijlages($criteria = null, $con = null)
	{
		// include the Peer class
		include_once 'plugins/ttCommunicatiePlugin/lib/model/om/BaseBriefBijlagePeer.php';
		if ($criteria === null) {
			$criteria = new Criteria();
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collBriefBijlages === null) {
			if ($this->isNew()) {
			   $this->collBriefBijlages = array();
			} else {

				$criteria->add(BriefBijlagePeer::BRIEF_TEMPLATE_ID, $this->getId());

				BriefBijlagePeer::addSelectColumns($criteria);
				$this->collBriefBijlages = BriefBijlagePeer::doSelect($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return the collection.


				$criteria->add(BriefBijlagePeer::BRIEF_TEMPLATE_ID, $this->getId());

				BriefBijlagePeer::addSelectColumns($criteria);
				if (!isset($this->lastBriefBijlageCriteria) || !$this->lastBriefBijlageCriteria->equals($criteria)) {
					$this->collBriefBijlages = BriefBijlagePeer::doSelect($criteria, $con);
				}
			}
		}
		$this->lastBriefBijlageCriteria = $criteria;
		return $this->collBriefBijlages;
	}

	/**
	 * Returns the number of related BriefBijlages.
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct
	 * @param      Connection $con
	 * @throws     PropelException
	 */
	public function countBriefBijlages($criteria = null, $distinct = false, $con = null)
	{
		// include the Peer class
		include_once 'plugins/ttCommunicatiePlugin/lib/model/om/BaseBriefBijlagePeer.php';
		if ($criteria === null) {
			$criteria = new Criteria();
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		$criteria->add(BriefBijlagePeer::BRIEF_TEMPLATE_ID, $this->getId());

		return BriefBijlagePeer::doCount($criteria, $distinct, $con);
	}

	/**
	 * Method called to associate a BriefBijlage object to this object
	 * through the BriefBijlage foreign key attribute
	 *
	 * @param      BriefBijlage $l BriefBijlage
	 * @return     void
	 * @throws     PropelException
	 */
	public function addBriefBijlage(BriefBijlage $l)
	{
		$this->collBriefBijlages[] = $l;
		$l->setBriefTemplate($this);
	}


  public function __call($method, $arguments)
  {
    if (!$callable = sfMixer::getCallable('BaseBriefTemplate:'.$method))
    {
      throw new sfException(sprintf('Call to undefined method BaseBriefTemplate::%s', $method));
    }

    array_unshift($arguments, $this);

    return call_user_func_array($callable, $arguments);
  }


} // BaseBriefTemplate
