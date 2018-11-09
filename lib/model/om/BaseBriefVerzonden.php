<?php

/**
 * Base class that represents a row from the 'brief_verzonden' table.
 *
 * 
 *
 * @package    plugins.ttCommunicatiePlugin.lib.model.om
 */
abstract class BaseBriefVerzonden extends BaseObject  implements Persistent {


	/**
	 * The Peer class.
	 * Instance provides a convenient way of calling static methods on a class
	 * that calling code may not be able to identify.
	 * @var        BriefVerzondenPeer
	 */
	protected static $peer;


	/**
	 * The value for the id field.
	 * @var        int
	 */
	protected $id;


	/**
	 * The value for the object_class field.
	 * @var        string
	 */
	protected $object_class;


	/**
	 * The value for the object_id field.
	 * @var        int
	 */
	protected $object_id;


	/**
	 * The value for the object_class_bestemmeling field.
	 * @var        string
	 */
	protected $object_class_bestemmeling;


	/**
	 * The value for the object_id_bestemmeling field.
	 * @var        int
	 */
	protected $object_id_bestemmeling;


	/**
	 * The value for the brief_template_id field.
	 * @var        int
	 */
	protected $brief_template_id;


	/**
	 * The value for the onderwerp field.
	 * @var        string
	 */
	protected $onderwerp;


	/**
	 * The value for the html field.
	 * @var        string
	 */
	protected $html;


	/**
	 * The value for the medium field.
	 * @var        string
	 */
	protected $medium;


	/**
	 * The value for the adres field.
	 * @var        string
	 */
	protected $adres;


	/**
	 * The value for the custom field.
	 * @var        boolean
	 */
	protected $custom = false;


	/**
	 * The value for the culture field.
	 * @var        string
	 */
	protected $culture;


	/**
	 * The value for the status field.
	 * @var        string
	 */
	protected $status;


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
	 * @var        BriefTemplate
	 */
	protected $aBriefTemplate;

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
	 * Get the [object_class] column value.
	 * 
	 * @return     string
	 */
	public function getObjectClass()
	{

		return $this->object_class;
	}

	/**
	 * Get the [object_id] column value.
	 * 
	 * @return     int
	 */
	public function getObjectId()
	{

		return $this->object_id;
	}

	/**
	 * Get the [object_class_bestemmeling] column value.
	 * 
	 * @return     string
	 */
	public function getObjectClassBestemmeling()
	{

		return $this->object_class_bestemmeling;
	}

	/**
	 * Get the [object_id_bestemmeling] column value.
	 * 
	 * @return     int
	 */
	public function getObjectIdBestemmeling()
	{

		return $this->object_id_bestemmeling;
	}

	/**
	 * Get the [brief_template_id] column value.
	 * 
	 * @return     int
	 */
	public function getBriefTemplateId()
	{

		return $this->brief_template_id;
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
	 * Get the [html] column value.
	 * 
	 * @return     string
	 */
	public function getHtml()
	{

		return $this->html;
	}

	/**
	 * Get the [medium] column value.
	 * 
	 * @return     string
	 */
	public function getMedium()
	{

		return $this->medium;
	}

	/**
	 * Get the [adres] column value.
	 * 
	 * @return     string
	 */
	public function getAdres()
	{

		return $this->adres;
	}

	/**
	 * Get the [custom] column value.
	 * 
	 * @return     boolean
	 */
	public function getCustom()
	{

		return $this->custom;
	}

	/**
	 * Get the [culture] column value.
	 * 
	 * @return     string
	 */
	public function getCulture()
	{

		return $this->culture;
	}

	/**
	 * Get the [status] column value.
	 * 
	 * @return     string
	 */
	public function getStatus()
	{

		return $this->status;
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
			$this->modifiedColumns[] = BriefVerzondenPeer::ID;
		}

	} // setId()

	/**
	 * Set the value of [object_class] column.
	 * 
	 * @param      string $v new value
	 * @return     void
	 */
	public function setObjectClass($v)
	{

		// Since the native PHP type for this column is string,
		// we will cast the input to a string (if it is not).
		if ($v !== null && !is_string($v)) {
			$v = (string) $v;
		}

		if ($this->object_class !== $v) {
			$this->object_class = $v;
			$this->modifiedColumns[] = BriefVerzondenPeer::OBJECT_CLASS;
		}

	} // setObjectClass()

	/**
	 * Set the value of [object_id] column.
	 * 
	 * @param      int $v new value
	 * @return     void
	 */
	public function setObjectId($v)
	{

		// Since the native PHP type for this column is integer,
		// we will cast the input value to an int (if it is not).
		if ($v !== null && !is_int($v) && is_numeric($v)) {
			$v = (int) $v;
		}

		if ($this->object_id !== $v) {
			$this->object_id = $v;
			$this->modifiedColumns[] = BriefVerzondenPeer::OBJECT_ID;
		}

	} // setObjectId()

	/**
	 * Set the value of [object_class_bestemmeling] column.
	 * 
	 * @param      string $v new value
	 * @return     void
	 */
	public function setObjectClassBestemmeling($v)
	{

		// Since the native PHP type for this column is string,
		// we will cast the input to a string (if it is not).
		if ($v !== null && !is_string($v)) {
			$v = (string) $v;
		}

		if ($this->object_class_bestemmeling !== $v) {
			$this->object_class_bestemmeling = $v;
			$this->modifiedColumns[] = BriefVerzondenPeer::OBJECT_CLASS_BESTEMMELING;
		}

	} // setObjectClassBestemmeling()

	/**
	 * Set the value of [object_id_bestemmeling] column.
	 * 
	 * @param      int $v new value
	 * @return     void
	 */
	public function setObjectIdBestemmeling($v)
	{

		// Since the native PHP type for this column is integer,
		// we will cast the input value to an int (if it is not).
		if ($v !== null && !is_int($v) && is_numeric($v)) {
			$v = (int) $v;
		}

		if ($this->object_id_bestemmeling !== $v) {
			$this->object_id_bestemmeling = $v;
			$this->modifiedColumns[] = BriefVerzondenPeer::OBJECT_ID_BESTEMMELING;
		}

	} // setObjectIdBestemmeling()

	/**
	 * Set the value of [brief_template_id] column.
	 * 
	 * @param      int $v new value
	 * @return     void
	 */
	public function setBriefTemplateId($v)
	{

		// Since the native PHP type for this column is integer,
		// we will cast the input value to an int (if it is not).
		if ($v !== null && !is_int($v) && is_numeric($v)) {
			$v = (int) $v;
		}

		if ($this->brief_template_id !== $v) {
			$this->brief_template_id = $v;
			$this->modifiedColumns[] = BriefVerzondenPeer::BRIEF_TEMPLATE_ID;
		}

		if ($this->aBriefTemplate !== null && $this->aBriefTemplate->getId() !== $v) {
			$this->aBriefTemplate = null;
		}

	} // setBriefTemplateId()

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
			$this->modifiedColumns[] = BriefVerzondenPeer::ONDERWERP;
		}

	} // setOnderwerp()

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
			$this->modifiedColumns[] = BriefVerzondenPeer::HTML;
		}

	} // setHtml()

	/**
	 * Set the value of [medium] column.
	 * 
	 * @param      string $v new value
	 * @return     void
	 */
	public function setMedium($v)
	{

		// Since the native PHP type for this column is string,
		// we will cast the input to a string (if it is not).
		if ($v !== null && !is_string($v)) {
			$v = (string) $v;
		}

		if ($this->medium !== $v) {
			$this->medium = $v;
			$this->modifiedColumns[] = BriefVerzondenPeer::MEDIUM;
		}

	} // setMedium()

	/**
	 * Set the value of [adres] column.
	 * 
	 * @param      string $v new value
	 * @return     void
	 */
	public function setAdres($v)
	{

		// Since the native PHP type for this column is string,
		// we will cast the input to a string (if it is not).
		if ($v !== null && !is_string($v)) {
			$v = (string) $v;
		}

		if ($this->adres !== $v) {
			$this->adres = $v;
			$this->modifiedColumns[] = BriefVerzondenPeer::ADRES;
		}

	} // setAdres()

	/**
	 * Set the value of [custom] column.
	 * 
	 * @param      boolean $v new value
	 * @return     void
	 */
	public function setCustom($v)
	{

		if ($this->custom !== $v || $v === false) {
			$this->custom = $v;
			$this->modifiedColumns[] = BriefVerzondenPeer::CUSTOM;
		}

	} // setCustom()

	/**
	 * Set the value of [culture] column.
	 * 
	 * @param      string $v new value
	 * @return     void
	 */
	public function setCulture($v)
	{

		// Since the native PHP type for this column is string,
		// we will cast the input to a string (if it is not).
		if ($v !== null && !is_string($v)) {
			$v = (string) $v;
		}

		if ($this->culture !== $v) {
			$this->culture = $v;
			$this->modifiedColumns[] = BriefVerzondenPeer::CULTURE;
		}

	} // setCulture()

	/**
	 * Set the value of [status] column.
	 * 
	 * @param      string $v new value
	 * @return     void
	 */
	public function setStatus($v)
	{

		// Since the native PHP type for this column is string,
		// we will cast the input to a string (if it is not).
		if ($v !== null && !is_string($v)) {
			$v = (string) $v;
		}

		if ($this->status !== $v) {
			$this->status = $v;
			$this->modifiedColumns[] = BriefVerzondenPeer::STATUS;
		}

	} // setStatus()

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
			$this->modifiedColumns[] = BriefVerzondenPeer::CREATED_BY;
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
			$this->modifiedColumns[] = BriefVerzondenPeer::UPDATED_BY;
		}

	} // setUpdatedBy()

	/**
	 * Set the value of [created_at] column.
	 * 
	 * @param      int $v new value
	 * @return     void
     * @throws     PropelException
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
			$this->modifiedColumns[] = BriefVerzondenPeer::CREATED_AT;
		}

	} // setCreatedAt()

	/**
	 * Set the value of [updated_at] column.
	 * 
	 * @param      int $v new value
	 * @return     void
     * @throws     PropelException
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
			$this->modifiedColumns[] = BriefVerzondenPeer::UPDATED_AT;
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

			$this->object_class = $rs->getString($startcol + 1);

			$this->object_id = $rs->getInt($startcol + 2);

			$this->object_class_bestemmeling = $rs->getString($startcol + 3);

			$this->object_id_bestemmeling = $rs->getInt($startcol + 4);

			$this->brief_template_id = $rs->getInt($startcol + 5);

			$this->onderwerp = $rs->getString($startcol + 6);

			$this->html = $rs->getString($startcol + 7);

			$this->medium = $rs->getString($startcol + 8);

			$this->adres = $rs->getString($startcol + 9);

			$this->custom = $rs->getBoolean($startcol + 10);

			$this->culture = $rs->getString($startcol + 11);

			$this->status = $rs->getString($startcol + 12);

			$this->created_by = $rs->getInt($startcol + 13);

			$this->updated_by = $rs->getInt($startcol + 14);

			$this->created_at = $rs->getTimestamp($startcol + 15, null);

			$this->updated_at = $rs->getTimestamp($startcol + 16, null);

			$this->resetModified();

			$this->setNew(false);

			return $startcol + BriefVerzondenPeer::NUM_COLUMNS - BriefVerzondenPeer::NUM_LAZY_LOAD_COLUMNS;

		} catch (Exception $e) {
			throw new PropelException("Error populating BriefVerzonden object", $e);
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

    foreach (sfMixer::getCallables('BaseBriefVerzonden:delete:pre') as $callable)
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
			$con = Propel::getConnection(BriefVerzondenPeer::DATABASE_NAME);
		}

		try {
			$con->begin();
			BriefVerzondenPeer::doDelete($this, $con);
			$this->setDeleted(true);
			$con->commit();
		} catch (PropelException $e) {
			$con->rollback();
			throw $e;
		}
	

    foreach (sfMixer::getCallables('BaseBriefVerzonden:delete:post') as $callable)
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

    foreach (sfMixer::getCallables('BaseBriefVerzonden:save:pre') as $callable)
    {
      $affectedRows = call_user_func($callable, $this, $con);
      if (is_int($affectedRows))
      {
        return $affectedRows;
      }
    }


    if ($this->isNew() && !$this->isColumnModified(BriefVerzondenPeer::CREATED_AT))
    {
      $this->setCreatedAt(time());
    }

    if ($this->isModified() && !$this->isColumnModified(BriefVerzondenPeer::UPDATED_AT))
    {
      $this->setUpdatedAt(time());
    }

		if ($this->isDeleted()) {
			throw new PropelException("You cannot save an object that has been deleted.");
		}

		if ($con === null) {
			$con = Propel::getConnection(BriefVerzondenPeer::DATABASE_NAME);
		}

		try {
			$con->begin();
			$affectedRows = $this->doSave($con);
			$con->commit();
    foreach (sfMixer::getCallables('BaseBriefVerzonden:save:post') as $callable)
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

			if ($this->aBriefTemplate !== null) {
				if ($this->aBriefTemplate->isModified()) {
					$affectedRows += $this->aBriefTemplate->save($con);
				}
				$this->setBriefTemplate($this->aBriefTemplate);
			}


			// If this object has been modified, then save it to the database.
			if ($this->isModified()) {
				if ($this->isNew()) {
					$pk = BriefVerzondenPeer::doInsert($this, $con);
					$affectedRows += 1; // we are assuming that there is only 1 row per doInsert() which
										 // should always be true here (even though technically
										 // BasePeer::doInsert() can insert multiple rows).

					$this->setId($pk);  //[IMV] update autoincrement primary key

					$this->setNew(false);
				} else {
					$affectedRows += BriefVerzondenPeer::doUpdate($this, $con);
				}
				$this->resetModified(); // [HL] After being saved an object is no longer 'modified'
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

			if ($this->aBriefTemplate !== null) {
				if (!$this->aBriefTemplate->validate($columns)) {
					$failureMap = array_merge($failureMap, $this->aBriefTemplate->getValidationFailures());
				}
			}


			if (($retval = BriefVerzondenPeer::doValidate($this, $columns)) !== true) {
				$failureMap = array_merge($failureMap, $retval);
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
		$pos = BriefVerzondenPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
				return $this->getObjectClass();
				break;
			case 2:
				return $this->getObjectId();
				break;
			case 3:
				return $this->getObjectClassBestemmeling();
				break;
			case 4:
				return $this->getObjectIdBestemmeling();
				break;
			case 5:
				return $this->getBriefTemplateId();
				break;
			case 6:
				return $this->getOnderwerp();
				break;
			case 7:
				return $this->getHtml();
				break;
			case 8:
				return $this->getMedium();
				break;
			case 9:
				return $this->getAdres();
				break;
			case 10:
				return $this->getCustom();
				break;
			case 11:
				return $this->getCulture();
				break;
			case 12:
				return $this->getStatus();
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
	 * @return     mixed[string] an associative array containing the field names (as keys) and field values
	 */
	public function toArray($keyType = BasePeer::TYPE_PHPNAME)
	{
		$keys = BriefVerzondenPeer::getFieldNames($keyType);
		$result = array(
			$keys[0] => $this->getId(),
			$keys[1] => $this->getObjectClass(),
			$keys[2] => $this->getObjectId(),
			$keys[3] => $this->getObjectClassBestemmeling(),
			$keys[4] => $this->getObjectIdBestemmeling(),
			$keys[5] => $this->getBriefTemplateId(),
			$keys[6] => $this->getOnderwerp(),
			$keys[7] => $this->getHtml(),
			$keys[8] => $this->getMedium(),
			$keys[9] => $this->getAdres(),
			$keys[10] => $this->getCustom(),
			$keys[11] => $this->getCulture(),
			$keys[12] => $this->getStatus(),
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
		$pos = BriefVerzondenPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
		$this->setByPosition($pos, $value);
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
				$this->setObjectClass($value);
				break;
			case 2:
				$this->setObjectId($value);
				break;
			case 3:
				$this->setObjectClassBestemmeling($value);
				break;
			case 4:
				$this->setObjectIdBestemmeling($value);
				break;
			case 5:
				$this->setBriefTemplateId($value);
				break;
			case 6:
				$this->setOnderwerp($value);
				break;
			case 7:
				$this->setHtml($value);
				break;
			case 8:
				$this->setMedium($value);
				break;
			case 9:
				$this->setAdres($value);
				break;
			case 10:
				$this->setCustom($value);
				break;
			case 11:
				$this->setCulture($value);
				break;
			case 12:
				$this->setStatus($value);
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
		$keys = BriefVerzondenPeer::getFieldNames($keyType);

		if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
		if (array_key_exists($keys[1], $arr)) $this->setObjectClass($arr[$keys[1]]);
		if (array_key_exists($keys[2], $arr)) $this->setObjectId($arr[$keys[2]]);
		if (array_key_exists($keys[3], $arr)) $this->setObjectClassBestemmeling($arr[$keys[3]]);
		if (array_key_exists($keys[4], $arr)) $this->setObjectIdBestemmeling($arr[$keys[4]]);
		if (array_key_exists($keys[5], $arr)) $this->setBriefTemplateId($arr[$keys[5]]);
		if (array_key_exists($keys[6], $arr)) $this->setOnderwerp($arr[$keys[6]]);
		if (array_key_exists($keys[7], $arr)) $this->setHtml($arr[$keys[7]]);
		if (array_key_exists($keys[8], $arr)) $this->setMedium($arr[$keys[8]]);
		if (array_key_exists($keys[9], $arr)) $this->setAdres($arr[$keys[9]]);
		if (array_key_exists($keys[10], $arr)) $this->setCustom($arr[$keys[10]]);
		if (array_key_exists($keys[11], $arr)) $this->setCulture($arr[$keys[11]]);
		if (array_key_exists($keys[12], $arr)) $this->setStatus($arr[$keys[12]]);
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
		$criteria = new Criteria(BriefVerzondenPeer::DATABASE_NAME);

		if ($this->isColumnModified(BriefVerzondenPeer::ID)) $criteria->add(BriefVerzondenPeer::ID, $this->id);
		if ($this->isColumnModified(BriefVerzondenPeer::OBJECT_CLASS)) $criteria->add(BriefVerzondenPeer::OBJECT_CLASS, $this->object_class);
		if ($this->isColumnModified(BriefVerzondenPeer::OBJECT_ID)) $criteria->add(BriefVerzondenPeer::OBJECT_ID, $this->object_id);
		if ($this->isColumnModified(BriefVerzondenPeer::OBJECT_CLASS_BESTEMMELING)) $criteria->add(BriefVerzondenPeer::OBJECT_CLASS_BESTEMMELING, $this->object_class_bestemmeling);
		if ($this->isColumnModified(BriefVerzondenPeer::OBJECT_ID_BESTEMMELING)) $criteria->add(BriefVerzondenPeer::OBJECT_ID_BESTEMMELING, $this->object_id_bestemmeling);
		if ($this->isColumnModified(BriefVerzondenPeer::BRIEF_TEMPLATE_ID)) $criteria->add(BriefVerzondenPeer::BRIEF_TEMPLATE_ID, $this->brief_template_id);
		if ($this->isColumnModified(BriefVerzondenPeer::ONDERWERP)) $criteria->add(BriefVerzondenPeer::ONDERWERP, $this->onderwerp);
		if ($this->isColumnModified(BriefVerzondenPeer::HTML)) $criteria->add(BriefVerzondenPeer::HTML, $this->html);
		if ($this->isColumnModified(BriefVerzondenPeer::MEDIUM)) $criteria->add(BriefVerzondenPeer::MEDIUM, $this->medium);
		if ($this->isColumnModified(BriefVerzondenPeer::ADRES)) $criteria->add(BriefVerzondenPeer::ADRES, $this->adres);
		if ($this->isColumnModified(BriefVerzondenPeer::CUSTOM)) $criteria->add(BriefVerzondenPeer::CUSTOM, $this->custom);
		if ($this->isColumnModified(BriefVerzondenPeer::CULTURE)) $criteria->add(BriefVerzondenPeer::CULTURE, $this->culture);
		if ($this->isColumnModified(BriefVerzondenPeer::STATUS)) $criteria->add(BriefVerzondenPeer::STATUS, $this->status);
		if ($this->isColumnModified(BriefVerzondenPeer::CREATED_BY)) $criteria->add(BriefVerzondenPeer::CREATED_BY, $this->created_by);
		if ($this->isColumnModified(BriefVerzondenPeer::UPDATED_BY)) $criteria->add(BriefVerzondenPeer::UPDATED_BY, $this->updated_by);
		if ($this->isColumnModified(BriefVerzondenPeer::CREATED_AT)) $criteria->add(BriefVerzondenPeer::CREATED_AT, $this->created_at);
		if ($this->isColumnModified(BriefVerzondenPeer::UPDATED_AT)) $criteria->add(BriefVerzondenPeer::UPDATED_AT, $this->updated_at);

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
		$criteria = new Criteria(BriefVerzondenPeer::DATABASE_NAME);

		$criteria->add(BriefVerzondenPeer::ID, $this->id);

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
	 * @param      object $copyObj An object of BriefVerzonden (or compatible) type.
	 * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
	 * @throws     PropelException
	 */
	public function copyInto($copyObj, $deepCopy = false)
	{

		$copyObj->setObjectClass($this->object_class);

		$copyObj->setObjectId($this->object_id);

		$copyObj->setObjectClassBestemmeling($this->object_class_bestemmeling);

		$copyObj->setObjectIdBestemmeling($this->object_id_bestemmeling);

		$copyObj->setBriefTemplateId($this->brief_template_id);

		$copyObj->setOnderwerp($this->onderwerp);

		$copyObj->setHtml($this->html);

		$copyObj->setMedium($this->medium);

		$copyObj->setAdres($this->adres);

		$copyObj->setCustom($this->custom);

		$copyObj->setCulture($this->culture);

		$copyObj->setStatus($this->status);

		$copyObj->setCreatedBy($this->created_by);

		$copyObj->setUpdatedBy($this->updated_by);

		$copyObj->setCreatedAt($this->created_at);

		$copyObj->setUpdatedAt($this->updated_at);


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
	 * @return     BriefVerzonden Clone of current object.
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
	 * @return     BriefVerzondenPeer
	 */
	public function getPeer()
	{
		if (self::$peer === null) {
			self::$peer = new BriefVerzondenPeer();
		}
		return self::$peer;
	}

	/**
	 * Declares an association between this object and a BriefTemplate object.
	 *
	 * @param      BaseBriefTemplate $v
	 * @return     void
	 * @throws     PropelException
	 */
	public function setBriefTemplate($v)
	{


		if ($v === null) {
			$this->setBriefTemplateId(NULL);
		} else {
			$this->setBriefTemplateId($v->getId());
		}


		$this->aBriefTemplate = $v;
	}


	/**
	 * Get the associated BriefTemplate object
	 *
	 * @param      Connection $con Optional Connection object.
	 * @return     BriefTemplate The associated BriefTemplate object.
	 * @throws     PropelException
	 */
	public function getBriefTemplate($con = null)
	{
		if ($this->aBriefTemplate === null && ($this->brief_template_id !== null)) {
			// include the related Peer class
			include_once 'plugins/ttCommunicatiePlugin/lib/model/om/BaseBriefTemplatePeer.php';

			$this->aBriefTemplate = BriefTemplatePeer::retrieveByPK($this->brief_template_id, $con);

			/* The following can be used instead of the line above to
			   guarantee the related object contains a reference
			   to this object, but this level of coupling
			   may be undesirable in many circumstances.
			   As it can lead to a db query with many results that may
			   never be used.
			   $obj = BriefTemplatePeer::retrieveByPK($this->brief_template_id, $con);
			   $obj->addBriefTemplates($this);
			 */
		}
		return $this->aBriefTemplate;
	}


  public function __call($method, $arguments)
  {
    if (!$callable = sfMixer::getCallable('BaseBriefVerzonden:'.$method))
    {
      throw new sfException(sprintf('Call to undefined method BaseBriefVerzonden::%s', $method));
    }

    array_unshift($arguments, $this);

    return call_user_func_array($callable, $arguments);
  }


} // BaseBriefVerzonden
