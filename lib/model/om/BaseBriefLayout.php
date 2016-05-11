<?php

/**
 * Base class that represents a row from the 'brief_layout' table.
 *
 * 
 *
 * @package    plugins.ttCommunicatiePlugin.lib.model.om
 */
abstract class BaseBriefLayout extends BaseObject  implements Persistent {


	/**
	 * The Peer class.
	 * Instance provides a convenient way of calling static methods on a class
	 * that calling code may not be able to identify.
	 * @var        BriefLayoutPeer
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
	 * The value for the naam field.
	 * @var        string
	 */
	protected $naam;


	/**
	 * The value for the print_bestand field.
	 * @var        string
	 */
	protected $print_bestand;


	/**
	 * The value for the mail_bestand field.
	 * @var        string
	 */
	protected $mail_bestand;


	/**
	 * The value for the pdf_bestand field.
	 * @var        string
	 */
	protected $pdf_bestand;


	/**
	 * The value for the print_stylesheets field.
	 * @var        string
	 */
	protected $print_stylesheets;


	/**
	 * The value for the mail_stylesheets field.
	 * @var        string
	 */
	protected $mail_stylesheets;


	/**
	 * The value for the pdf_stylesheets field.
	 * @var        string
	 */
	protected $pdf_stylesheets;


	/**
	 * The value for the vertaald field.
	 * @var        boolean
	 */
	protected $vertaald = true;


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
	 * Collection to store aggregation of collBriefTemplates.
	 * @var        array
	 */
	protected $collBriefTemplates;

	/**
	 * The criteria used to select the current contents of collBriefTemplates.
	 * @var        Criteria
	 */
	protected $lastBriefTemplateCriteria = null;

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
	 * Get the [naam] column value.
	 * 
	 * @return     string
	 */
	public function getNaam()
	{

		return $this->naam;
	}

	/**
	 * Get the [print_bestand] column value.
	 * 
	 * @return     string
	 */
	public function getPrintBestand()
	{

		return $this->print_bestand;
	}

	/**
	 * Get the [mail_bestand] column value.
	 * 
	 * @return     string
	 */
	public function getMailBestand()
	{

		return $this->mail_bestand;
	}

	/**
	 * Get the [pdf_bestand] column value.
	 * 
	 * @return     string
	 */
	public function getPdfBestand()
	{

		return $this->pdf_bestand;
	}

	/**
	 * Get the [print_stylesheets] column value.
	 * 
	 * @return     string
	 */
	public function getPrintStylesheets()
	{

		return $this->print_stylesheets;
	}

	/**
	 * Get the [mail_stylesheets] column value.
	 * 
	 * @return     string
	 */
	public function getMailStylesheets()
	{

		return $this->mail_stylesheets;
	}

	/**
	 * Get the [pdf_stylesheets] column value.
	 * 
	 * @return     string
	 */
	public function getPdfStylesheets()
	{

		return $this->pdf_stylesheets;
	}

	/**
	 * Get the [vertaald] column value.
	 * 
	 * @return     boolean
	 */
	public function getVertaald()
	{

		return $this->vertaald;
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
			$this->modifiedColumns[] = BriefLayoutPeer::ID;
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
			$this->modifiedColumns[] = BriefLayoutPeer::CATEGORIE;
		}

	} // setCategorie()

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
			$this->modifiedColumns[] = BriefLayoutPeer::NAAM;
		}

	} // setNaam()

	/**
	 * Set the value of [print_bestand] column.
	 * 
	 * @param      string $v new value
	 * @return     void
	 */
	public function setPrintBestand($v)
	{

		// Since the native PHP type for this column is string,
		// we will cast the input to a string (if it is not).
		if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->print_bestand !== $v) {
			$this->print_bestand = $v;
			$this->modifiedColumns[] = BriefLayoutPeer::PRINT_BESTAND;
		}

	} // setPrintBestand()

	/**
	 * Set the value of [mail_bestand] column.
	 * 
	 * @param      string $v new value
	 * @return     void
	 */
	public function setMailBestand($v)
	{

		// Since the native PHP type for this column is string,
		// we will cast the input to a string (if it is not).
		if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->mail_bestand !== $v) {
			$this->mail_bestand = $v;
			$this->modifiedColumns[] = BriefLayoutPeer::MAIL_BESTAND;
		}

	} // setMailBestand()

	/**
	 * Set the value of [pdf_bestand] column.
	 * 
	 * @param      string $v new value
	 * @return     void
	 */
	public function setPdfBestand($v)
	{

		// Since the native PHP type for this column is string,
		// we will cast the input to a string (if it is not).
		if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->pdf_bestand !== $v) {
			$this->pdf_bestand = $v;
			$this->modifiedColumns[] = BriefLayoutPeer::PDF_BESTAND;
		}

	} // setPdfBestand()

	/**
	 * Set the value of [print_stylesheets] column.
	 * 
	 * @param      string $v new value
	 * @return     void
	 */
	public function setPrintStylesheets($v)
	{

		// Since the native PHP type for this column is string,
		// we will cast the input to a string (if it is not).
		if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->print_stylesheets !== $v) {
			$this->print_stylesheets = $v;
			$this->modifiedColumns[] = BriefLayoutPeer::PRINT_STYLESHEETS;
		}

	} // setPrintStylesheets()

	/**
	 * Set the value of [mail_stylesheets] column.
	 * 
	 * @param      string $v new value
	 * @return     void
	 */
	public function setMailStylesheets($v)
	{

		// Since the native PHP type for this column is string,
		// we will cast the input to a string (if it is not).
		if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->mail_stylesheets !== $v) {
			$this->mail_stylesheets = $v;
			$this->modifiedColumns[] = BriefLayoutPeer::MAIL_STYLESHEETS;
		}

	} // setMailStylesheets()

	/**
	 * Set the value of [pdf_stylesheets] column.
	 * 
	 * @param      string $v new value
	 * @return     void
	 */
	public function setPdfStylesheets($v)
	{

		// Since the native PHP type for this column is string,
		// we will cast the input to a string (if it is not).
		if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->pdf_stylesheets !== $v) {
			$this->pdf_stylesheets = $v;
			$this->modifiedColumns[] = BriefLayoutPeer::PDF_STYLESHEETS;
		}

	} // setPdfStylesheets()

	/**
	 * Set the value of [vertaald] column.
	 * 
	 * @param      boolean $v new value
	 * @return     void
	 */
	public function setVertaald($v)
	{

		if ($this->vertaald !== $v || $v === true) {
			$this->vertaald = $v;
			$this->modifiedColumns[] = BriefLayoutPeer::VERTAALD;
		}

	} // setVertaald()

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
			$this->modifiedColumns[] = BriefLayoutPeer::CREATED_BY;
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
			$this->modifiedColumns[] = BriefLayoutPeer::UPDATED_BY;
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
			$this->modifiedColumns[] = BriefLayoutPeer::CREATED_AT;
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
			$this->modifiedColumns[] = BriefLayoutPeer::UPDATED_AT;
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

			$this->naam = $rs->getString($startcol + 2);

			$this->print_bestand = $rs->getString($startcol + 3);

			$this->mail_bestand = $rs->getString($startcol + 4);

			$this->pdf_bestand = $rs->getString($startcol + 5);

			$this->print_stylesheets = $rs->getString($startcol + 6);

			$this->mail_stylesheets = $rs->getString($startcol + 7);

			$this->pdf_stylesheets = $rs->getString($startcol + 8);

			$this->vertaald = $rs->getBoolean($startcol + 9);

			$this->created_by = $rs->getInt($startcol + 10);

			$this->updated_by = $rs->getInt($startcol + 11);

			$this->created_at = $rs->getTimestamp($startcol + 12, null);

			$this->updated_at = $rs->getTimestamp($startcol + 13, null);

			$this->resetModified();

			$this->setNew(false);

			// FIXME - using NUM_COLUMNS may be clearer.
			return $startcol + 14; // 14 = BriefLayoutPeer::NUM_COLUMNS - BriefLayoutPeer::NUM_LAZY_LOAD_COLUMNS).

		} catch (Exception $e) {
			throw new PropelException("Error populating BriefLayout object", $e);
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

    foreach (sfMixer::getCallables('BaseBriefLayout:delete:pre') as $callable)
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
			$con = Propel::getConnection(BriefLayoutPeer::DATABASE_NAME);
		}

		try {
			$con->begin();
			BriefLayoutPeer::doDelete($this, $con);
			$this->setDeleted(true);
			$con->commit();
		} catch (PropelException $e) {
			$con->rollback();
			throw $e;
		}
	

    foreach (sfMixer::getCallables('BaseBriefLayout:delete:post') as $callable)
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

    foreach (sfMixer::getCallables('BaseBriefLayout:save:pre') as $callable)
    {
      $affectedRows = call_user_func($callable, $this, $con);
      if (is_int($affectedRows))
      {
        return $affectedRows;
      }
    }


    if ($this->isNew() && !$this->isColumnModified(BriefLayoutPeer::CREATED_AT))
    {
      $this->setCreatedAt(time());
    }

    if ($this->isModified() && !$this->isColumnModified(BriefLayoutPeer::UPDATED_AT))
    {
      $this->setUpdatedAt(time());
    }

		if ($this->isDeleted()) {
			throw new PropelException("You cannot save an object that has been deleted.");
		}

		if ($con === null) {
			$con = Propel::getConnection(BriefLayoutPeer::DATABASE_NAME);
		}

		try {
			$con->begin();
			$affectedRows = $this->doSave($con);
			$con->commit();
    foreach (sfMixer::getCallables('BaseBriefLayout:save:post') as $callable)
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


			// If this object has been modified, then save it to the database.
			if ($this->isModified()) {
				if ($this->isNew()) {
					$pk = BriefLayoutPeer::doInsert($this, $con);
					$affectedRows += 1; // we are assuming that there is only 1 row per doInsert() which
										 // should always be true here (even though technically
										 // BasePeer::doInsert() can insert multiple rows).

					$this->setId($pk);  //[IMV] update autoincrement primary key

					$this->setNew(false);
				} else {
					$affectedRows += BriefLayoutPeer::doUpdate($this, $con);
				}
				$this->resetModified(); // [HL] After being saved an object is no longer 'modified'
			}

			if ($this->collBriefTemplates !== null) {
				foreach($this->collBriefTemplates as $referrerFK) {
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


			if (($retval = BriefLayoutPeer::doValidate($this, $columns)) !== true) {
				$failureMap = array_merge($failureMap, $retval);
			}


				if ($this->collBriefTemplates !== null) {
					foreach($this->collBriefTemplates as $referrerFK) {
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
		$pos = BriefLayoutPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
				return $this->getNaam();
				break;
			case 3:
				return $this->getPrintBestand();
				break;
			case 4:
				return $this->getMailBestand();
				break;
			case 5:
				return $this->getPdfBestand();
				break;
			case 6:
				return $this->getPrintStylesheets();
				break;
			case 7:
				return $this->getMailStylesheets();
				break;
			case 8:
				return $this->getPdfStylesheets();
				break;
			case 9:
				return $this->getVertaald();
				break;
			case 10:
				return $this->getCreatedBy();
				break;
			case 11:
				return $this->getUpdatedBy();
				break;
			case 12:
				return $this->getCreatedAt();
				break;
			case 13:
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
		$keys = BriefLayoutPeer::getFieldNames($keyType);
		$result = array(
			$keys[0] => $this->getId(),
			$keys[1] => $this->getCategorie(),
			$keys[2] => $this->getNaam(),
			$keys[3] => $this->getPrintBestand(),
			$keys[4] => $this->getMailBestand(),
			$keys[5] => $this->getPdfBestand(),
			$keys[6] => $this->getPrintStylesheets(),
			$keys[7] => $this->getMailStylesheets(),
			$keys[8] => $this->getPdfStylesheets(),
			$keys[9] => $this->getVertaald(),
			$keys[10] => $this->getCreatedBy(),
			$keys[11] => $this->getUpdatedBy(),
			$keys[12] => $this->getCreatedAt(),
			$keys[13] => $this->getUpdatedAt(),
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
		$pos = BriefLayoutPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
				$this->setNaam($value);
				break;
			case 3:
				$this->setPrintBestand($value);
				break;
			case 4:
				$this->setMailBestand($value);
				break;
			case 5:
				$this->setPdfBestand($value);
				break;
			case 6:
				$this->setPrintStylesheets($value);
				break;
			case 7:
				$this->setMailStylesheets($value);
				break;
			case 8:
				$this->setPdfStylesheets($value);
				break;
			case 9:
				$this->setVertaald($value);
				break;
			case 10:
				$this->setCreatedBy($value);
				break;
			case 11:
				$this->setUpdatedBy($value);
				break;
			case 12:
				$this->setCreatedAt($value);
				break;
			case 13:
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
		$keys = BriefLayoutPeer::getFieldNames($keyType);

		if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
		if (array_key_exists($keys[1], $arr)) $this->setCategorie($arr[$keys[1]]);
		if (array_key_exists($keys[2], $arr)) $this->setNaam($arr[$keys[2]]);
		if (array_key_exists($keys[3], $arr)) $this->setPrintBestand($arr[$keys[3]]);
		if (array_key_exists($keys[4], $arr)) $this->setMailBestand($arr[$keys[4]]);
		if (array_key_exists($keys[5], $arr)) $this->setPdfBestand($arr[$keys[5]]);
		if (array_key_exists($keys[6], $arr)) $this->setPrintStylesheets($arr[$keys[6]]);
		if (array_key_exists($keys[7], $arr)) $this->setMailStylesheets($arr[$keys[7]]);
		if (array_key_exists($keys[8], $arr)) $this->setPdfStylesheets($arr[$keys[8]]);
		if (array_key_exists($keys[9], $arr)) $this->setVertaald($arr[$keys[9]]);
		if (array_key_exists($keys[10], $arr)) $this->setCreatedBy($arr[$keys[10]]);
		if (array_key_exists($keys[11], $arr)) $this->setUpdatedBy($arr[$keys[11]]);
		if (array_key_exists($keys[12], $arr)) $this->setCreatedAt($arr[$keys[12]]);
		if (array_key_exists($keys[13], $arr)) $this->setUpdatedAt($arr[$keys[13]]);
	}

	/**
	 * Build a Criteria object containing the values of all modified columns in this object.
	 *
	 * @return     Criteria The Criteria object containing all modified values.
	 */
	public function buildCriteria()
	{
		$criteria = new Criteria(BriefLayoutPeer::DATABASE_NAME);

		if ($this->isColumnModified(BriefLayoutPeer::ID)) $criteria->add(BriefLayoutPeer::ID, $this->id);
		if ($this->isColumnModified(BriefLayoutPeer::CATEGORIE)) $criteria->add(BriefLayoutPeer::CATEGORIE, $this->categorie);
		if ($this->isColumnModified(BriefLayoutPeer::NAAM)) $criteria->add(BriefLayoutPeer::NAAM, $this->naam);
		if ($this->isColumnModified(BriefLayoutPeer::PRINT_BESTAND)) $criteria->add(BriefLayoutPeer::PRINT_BESTAND, $this->print_bestand);
		if ($this->isColumnModified(BriefLayoutPeer::MAIL_BESTAND)) $criteria->add(BriefLayoutPeer::MAIL_BESTAND, $this->mail_bestand);
		if ($this->isColumnModified(BriefLayoutPeer::PDF_BESTAND)) $criteria->add(BriefLayoutPeer::PDF_BESTAND, $this->pdf_bestand);
		if ($this->isColumnModified(BriefLayoutPeer::PRINT_STYLESHEETS)) $criteria->add(BriefLayoutPeer::PRINT_STYLESHEETS, $this->print_stylesheets);
		if ($this->isColumnModified(BriefLayoutPeer::MAIL_STYLESHEETS)) $criteria->add(BriefLayoutPeer::MAIL_STYLESHEETS, $this->mail_stylesheets);
		if ($this->isColumnModified(BriefLayoutPeer::PDF_STYLESHEETS)) $criteria->add(BriefLayoutPeer::PDF_STYLESHEETS, $this->pdf_stylesheets);
		if ($this->isColumnModified(BriefLayoutPeer::VERTAALD)) $criteria->add(BriefLayoutPeer::VERTAALD, $this->vertaald);
		if ($this->isColumnModified(BriefLayoutPeer::CREATED_BY)) $criteria->add(BriefLayoutPeer::CREATED_BY, $this->created_by);
		if ($this->isColumnModified(BriefLayoutPeer::UPDATED_BY)) $criteria->add(BriefLayoutPeer::UPDATED_BY, $this->updated_by);
		if ($this->isColumnModified(BriefLayoutPeer::CREATED_AT)) $criteria->add(BriefLayoutPeer::CREATED_AT, $this->created_at);
		if ($this->isColumnModified(BriefLayoutPeer::UPDATED_AT)) $criteria->add(BriefLayoutPeer::UPDATED_AT, $this->updated_at);

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
		$criteria = new Criteria(BriefLayoutPeer::DATABASE_NAME);

		$criteria->add(BriefLayoutPeer::ID, $this->id);

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
	 * @param      object $copyObj An object of BriefLayout (or compatible) type.
	 * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
	 * @throws     PropelException
	 */
	public function copyInto($copyObj, $deepCopy = false)
	{

		$copyObj->setCategorie($this->categorie);

		$copyObj->setNaam($this->naam);

		$copyObj->setPrintBestand($this->print_bestand);

		$copyObj->setMailBestand($this->mail_bestand);

		$copyObj->setPdfBestand($this->pdf_bestand);

		$copyObj->setPrintStylesheets($this->print_stylesheets);

		$copyObj->setMailStylesheets($this->mail_stylesheets);

		$copyObj->setPdfStylesheets($this->pdf_stylesheets);

		$copyObj->setVertaald($this->vertaald);

		$copyObj->setCreatedBy($this->created_by);

		$copyObj->setUpdatedBy($this->updated_by);

		$copyObj->setCreatedAt($this->created_at);

		$copyObj->setUpdatedAt($this->updated_at);


		if ($deepCopy) {
			// important: temporarily setNew(false) because this affects the behavior of
			// the getter/setter methods for fkey referrer objects.
			$copyObj->setNew(false);

			foreach($this->getBriefTemplates() as $relObj) {
				$copyObj->addBriefTemplate($relObj->copy($deepCopy));
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
	 * @return     BriefLayout Clone of current object.
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
	 * @return     BriefLayoutPeer
	 */
	public function getPeer()
	{
		if (self::$peer === null) {
			self::$peer = new BriefLayoutPeer();
		}
		return self::$peer;
	}

	/**
	 * Temporary storage of collBriefTemplates to save a possible db hit in
	 * the event objects are add to the collection, but the
	 * complete collection is never requested.
	 * @return     void
	 */
	public function initBriefTemplates()
	{
		if ($this->collBriefTemplates === null) {
			$this->collBriefTemplates = array();
		}
	}

	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this BriefLayout has previously
	 * been saved, it will retrieve related BriefTemplates from storage.
	 * If this BriefLayout is new, it will return
	 * an empty collection or the current collection, the criteria
	 * is ignored on a new object.
	 *
	 * @param      Connection $con
	 * @param      Criteria $criteria
	 * @throws     PropelException
	 */
	public function getBriefTemplates($criteria = null, $con = null)
	{
		// include the Peer class
		include_once 'plugins/ttCommunicatiePlugin/lib/model/om/BaseBriefTemplatePeer.php';
		if ($criteria === null) {
			$criteria = new Criteria();
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collBriefTemplates === null) {
			if ($this->isNew()) {
			   $this->collBriefTemplates = array();
			} else {

				$criteria->add(BriefTemplatePeer::BRIEF_LAYOUT_ID, $this->getId());

				BriefTemplatePeer::addSelectColumns($criteria);
				$this->collBriefTemplates = BriefTemplatePeer::doSelect($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return the collection.


				$criteria->add(BriefTemplatePeer::BRIEF_LAYOUT_ID, $this->getId());

				BriefTemplatePeer::addSelectColumns($criteria);
				if (!isset($this->lastBriefTemplateCriteria) || !$this->lastBriefTemplateCriteria->equals($criteria)) {
					$this->collBriefTemplates = BriefTemplatePeer::doSelect($criteria, $con);
				}
			}
		}
		$this->lastBriefTemplateCriteria = $criteria;
		return $this->collBriefTemplates;
	}

	/**
	 * Returns the number of related BriefTemplates.
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct
	 * @param      Connection $con
	 * @throws     PropelException
	 */
	public function countBriefTemplates($criteria = null, $distinct = false, $con = null)
	{
		// include the Peer class
		include_once 'plugins/ttCommunicatiePlugin/lib/model/om/BaseBriefTemplatePeer.php';
		if ($criteria === null) {
			$criteria = new Criteria();
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		$criteria->add(BriefTemplatePeer::BRIEF_LAYOUT_ID, $this->getId());

		return BriefTemplatePeer::doCount($criteria, $distinct, $con);
	}

	/**
	 * Method called to associate a BriefTemplate object to this object
	 * through the BriefTemplate foreign key attribute
	 *
	 * @param      BriefTemplate $l BriefTemplate
	 * @return     void
	 * @throws     PropelException
	 */
	public function addBriefTemplate(BriefTemplate $l)
	{
		$this->collBriefTemplates[] = $l;
		$l->setBriefLayout($this);
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this BriefLayout is new, it will return
	 * an empty collection; or if this BriefLayout has previously
	 * been saved, it will retrieve related BriefTemplates from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in BriefLayout.
	 */
	public function getBriefTemplatesJoinBriefTemplateRelatedByPdfTemplateId($criteria = null, $con = null)
	{
		// include the Peer class
		include_once 'plugins/ttCommunicatiePlugin/lib/model/om/BaseBriefTemplatePeer.php';
		if ($criteria === null) {
			$criteria = new Criteria();
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collBriefTemplates === null) {
			if ($this->isNew()) {
				$this->collBriefTemplates = array();
			} else {

				$criteria->add(BriefTemplatePeer::BRIEF_LAYOUT_ID, $this->getId());

				$this->collBriefTemplates = BriefTemplatePeer::doSelectJoinBriefTemplateRelatedByPdfTemplateId($criteria, $con);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(BriefTemplatePeer::BRIEF_LAYOUT_ID, $this->getId());

			if (!isset($this->lastBriefTemplateCriteria) || !$this->lastBriefTemplateCriteria->equals($criteria)) {
				$this->collBriefTemplates = BriefTemplatePeer::doSelectJoinBriefTemplateRelatedByPdfTemplateId($criteria, $con);
			}
		}
		$this->lastBriefTemplateCriteria = $criteria;

		return $this->collBriefTemplates;
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this BriefLayout is new, it will return
	 * an empty collection; or if this BriefLayout has previously
	 * been saved, it will retrieve related BriefTemplates from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in BriefLayout.
	 */
	public function getBriefTemplatesJoinPersoon($criteria = null, $con = null)
	{
		// include the Peer class
		include_once 'plugins/ttCommunicatiePlugin/lib/model/om/BaseBriefTemplatePeer.php';
		if ($criteria === null) {
			$criteria = new Criteria();
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collBriefTemplates === null) {
			if ($this->isNew()) {
				$this->collBriefTemplates = array();
			} else {

				$criteria->add(BriefTemplatePeer::BRIEF_LAYOUT_ID, $this->getId());

				$this->collBriefTemplates = BriefTemplatePeer::doSelectJoinPersoon($criteria, $con);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(BriefTemplatePeer::BRIEF_LAYOUT_ID, $this->getId());

			if (!isset($this->lastBriefTemplateCriteria) || !$this->lastBriefTemplateCriteria->equals($criteria)) {
				$this->collBriefTemplates = BriefTemplatePeer::doSelectJoinPersoon($criteria, $con);
			}
		}
		$this->lastBriefTemplateCriteria = $criteria;

		return $this->collBriefTemplates;
	}


  public function __call($method, $arguments)
  {
    if (!$callable = sfMixer::getCallable('BaseBriefLayout:'.$method))
    {
      throw new sfException(sprintf('Call to undefined method BaseBriefLayout::%s', $method));
    }

    array_unshift($arguments, $this);

    return call_user_func_array($callable, $arguments);
  }


} // BaseBriefLayout
