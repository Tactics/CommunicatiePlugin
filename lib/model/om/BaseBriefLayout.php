<?php


abstract class BaseBriefLayout extends BaseObject  implements Persistent {


	
	protected static $peer;


	
	protected $id;


	
	protected $categorie;


	
	protected $naam;


	
	protected $print_bestand;


	
	protected $mail_bestand;


	
	protected $print_stylesheets;


	
	protected $mail_stylesheets;


	
	protected $vertaald = true;


	
	protected $created_by;


	
	protected $updated_by;


	
	protected $created_at;


	
	protected $updated_at;

	
	protected $collBriefTemplates;

	
	protected $lastBriefTemplateCriteria = null;

	
	protected $alreadyInSave = false;

	
	protected $alreadyInValidation = false;

	
	public function getId()
	{

		return $this->id;
	}

	
	public function getCategorie()
	{

		return $this->categorie;
	}

	
	public function getNaam()
	{

		return $this->naam;
	}

	
	public function getPrintBestand()
	{

		return $this->print_bestand;
	}

	
	public function getMailBestand()
	{

		return $this->mail_bestand;
	}

	
	public function getPrintStylesheets()
	{

		return $this->print_stylesheets;
	}

	
	public function getMailStylesheets()
	{

		return $this->mail_stylesheets;
	}

	
	public function getVertaald()
	{

		return $this->vertaald;
	}

	
	public function getCreatedBy()
	{

		return $this->created_by;
	}

	
	public function getUpdatedBy()
	{

		return $this->updated_by;
	}

	
	public function getCreatedAt($format = 'Y-m-d H:i:s')
	{

		if ($this->created_at === null || $this->created_at === '') {
			return null;
		} elseif (!is_int($this->created_at)) {
						$ts = strtotime($this->created_at);
			if ($ts === -1 || $ts === false) { 				throw new PropelException("Unable to parse value of [created_at] as date/time value: " . var_export($this->created_at, true));
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

	
	public function getUpdatedAt($format = 'Y-m-d H:i:s')
	{

		if ($this->updated_at === null || $this->updated_at === '') {
			return null;
		} elseif (!is_int($this->updated_at)) {
						$ts = strtotime($this->updated_at);
			if ($ts === -1 || $ts === false) { 				throw new PropelException("Unable to parse value of [updated_at] as date/time value: " . var_export($this->updated_at, true));
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

	
	public function setId($v)
	{

						if ($v !== null && !is_int($v) && is_numeric($v)) {
			$v = (int) $v;
		}

		if ($this->id !== $v) {
			$this->id = $v;
			$this->modifiedColumns[] = BriefLayoutPeer::ID;
		}

	} 
	
	public function setCategorie($v)
	{

						if ($v !== null && !is_int($v) && is_numeric($v)) {
			$v = (int) $v;
		}

		if ($this->categorie !== $v) {
			$this->categorie = $v;
			$this->modifiedColumns[] = BriefLayoutPeer::CATEGORIE;
		}

	} 
	
	public function setNaam($v)
	{

						if ($v !== null && !is_string($v)) {
			$v = (string) $v;
		}

		if ($this->naam !== $v) {
			$this->naam = $v;
			$this->modifiedColumns[] = BriefLayoutPeer::NAAM;
		}

	} 
	
	public function setPrintBestand($v)
	{

						if ($v !== null && !is_string($v)) {
			$v = (string) $v;
		}

		if ($this->print_bestand !== $v) {
			$this->print_bestand = $v;
			$this->modifiedColumns[] = BriefLayoutPeer::PRINT_BESTAND;
		}

	} 
	
	public function setMailBestand($v)
	{

						if ($v !== null && !is_string($v)) {
			$v = (string) $v;
		}

		if ($this->mail_bestand !== $v) {
			$this->mail_bestand = $v;
			$this->modifiedColumns[] = BriefLayoutPeer::MAIL_BESTAND;
		}

	} 
	
	public function setPrintStylesheets($v)
	{

						if ($v !== null && !is_string($v)) {
			$v = (string) $v;
		}

		if ($this->print_stylesheets !== $v) {
			$this->print_stylesheets = $v;
			$this->modifiedColumns[] = BriefLayoutPeer::PRINT_STYLESHEETS;
		}

	} 
	
	public function setMailStylesheets($v)
	{

						if ($v !== null && !is_string($v)) {
			$v = (string) $v;
		}

		if ($this->mail_stylesheets !== $v) {
			$this->mail_stylesheets = $v;
			$this->modifiedColumns[] = BriefLayoutPeer::MAIL_STYLESHEETS;
		}

	} 
	
	public function setVertaald($v)
	{

		if ($this->vertaald !== $v || $v === true) {
			$this->vertaald = $v;
			$this->modifiedColumns[] = BriefLayoutPeer::VERTAALD;
		}

	} 
	
	public function setCreatedBy($v)
	{

						if ($v !== null && !is_int($v) && is_numeric($v)) {
			$v = (int) $v;
		}

		if ($this->created_by !== $v) {
			$this->created_by = $v;
			$this->modifiedColumns[] = BriefLayoutPeer::CREATED_BY;
		}

	} 
	
	public function setUpdatedBy($v)
	{

						if ($v !== null && !is_int($v) && is_numeric($v)) {
			$v = (int) $v;
		}

		if ($this->updated_by !== $v) {
			$this->updated_by = $v;
			$this->modifiedColumns[] = BriefLayoutPeer::UPDATED_BY;
		}

	} 
	
	public function setCreatedAt($v)
	{

		if ($v !== null && !is_int($v)) {
			$ts = strtotime($v);
			if ($ts === -1 || $ts === false) { 				throw new PropelException("Unable to parse date/time value for [created_at] from input: " . var_export($v, true));
			}
		} else {
			$ts = $v;
		}
		if ($this->created_at !== $ts) {
			$this->created_at = $ts;
			$this->modifiedColumns[] = BriefLayoutPeer::CREATED_AT;
		}

	} 
	
	public function setUpdatedAt($v)
	{

		if ($v !== null && !is_int($v)) {
			$ts = strtotime($v);
			if ($ts === -1 || $ts === false) { 				throw new PropelException("Unable to parse date/time value for [updated_at] from input: " . var_export($v, true));
			}
		} else {
			$ts = $v;
		}
		if ($this->updated_at !== $ts) {
			$this->updated_at = $ts;
			$this->modifiedColumns[] = BriefLayoutPeer::UPDATED_AT;
		}

	} 
	
	public function hydrate(ResultSet $rs, $startcol = 1)
	{
		try {

			$this->id = $rs->getInt($startcol + 0);

			$this->categorie = $rs->getInt($startcol + 1);

			$this->naam = $rs->getString($startcol + 2);

			$this->print_bestand = $rs->getString($startcol + 3);

			$this->mail_bestand = $rs->getString($startcol + 4);

			$this->print_stylesheets = $rs->getString($startcol + 5);

			$this->mail_stylesheets = $rs->getString($startcol + 6);

			$this->vertaald = $rs->getBoolean($startcol + 7);

			$this->created_by = $rs->getInt($startcol + 8);

			$this->updated_by = $rs->getInt($startcol + 9);

			$this->created_at = $rs->getTimestamp($startcol + 10, null);

			$this->updated_at = $rs->getTimestamp($startcol + 11, null);

			$this->resetModified();

			$this->setNew(false);

			return $startcol + BriefLayoutPeer::NUM_COLUMNS - BriefLayoutPeer::NUM_LAZY_LOAD_COLUMNS;

		} catch (Exception $e) {
			throw new PropelException("Error populating BriefLayout object", $e);
		}
	}

	
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

	
	protected function doSave($con)
	{
		$affectedRows = 0; 		if (!$this->alreadyInSave) {
			$this->alreadyInSave = true;


						if ($this->isModified()) {
				if ($this->isNew()) {
					$pk = BriefLayoutPeer::doInsert($this, $con);
					$affectedRows += 1; 										 										 
					$this->setId($pk);  
					$this->setNew(false);
				} else {
					$affectedRows += BriefLayoutPeer::doUpdate($this, $con);
				}
				$this->resetModified(); 			}

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
	} 
	
	protected $validationFailures = array();

	
	public function getValidationFailures()
	{
		return $this->validationFailures;
	}

	
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

	
	public function getByName($name, $type = BasePeer::TYPE_PHPNAME)
	{
		$pos = BriefLayoutPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
		return $this->getByPosition($pos);
	}

	
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
				return $this->getPrintStylesheets();
				break;
			case 6:
				return $this->getMailStylesheets();
				break;
			case 7:
				return $this->getVertaald();
				break;
			case 8:
				return $this->getCreatedBy();
				break;
			case 9:
				return $this->getUpdatedBy();
				break;
			case 10:
				return $this->getCreatedAt();
				break;
			case 11:
				return $this->getUpdatedAt();
				break;
			default:
				return null;
				break;
		} 	}

	
	public function toArray($keyType = BasePeer::TYPE_PHPNAME)
	{
		$keys = BriefLayoutPeer::getFieldNames($keyType);
		$result = array(
			$keys[0] => $this->getId(),
			$keys[1] => $this->getCategorie(),
			$keys[2] => $this->getNaam(),
			$keys[3] => $this->getPrintBestand(),
			$keys[4] => $this->getMailBestand(),
			$keys[5] => $this->getPrintStylesheets(),
			$keys[6] => $this->getMailStylesheets(),
			$keys[7] => $this->getVertaald(),
			$keys[8] => $this->getCreatedBy(),
			$keys[9] => $this->getUpdatedBy(),
			$keys[10] => $this->getCreatedAt(),
			$keys[11] => $this->getUpdatedAt(),
		);
		return $result;
	}

	
	public function setByName($name, $value, $type = BasePeer::TYPE_PHPNAME)
	{
		$pos = BriefLayoutPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
		$this->setByPosition($pos, $value);
	}

	
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
				$this->setPrintStylesheets($value);
				break;
			case 6:
				$this->setMailStylesheets($value);
				break;
			case 7:
				$this->setVertaald($value);
				break;
			case 8:
				$this->setCreatedBy($value);
				break;
			case 9:
				$this->setUpdatedBy($value);
				break;
			case 10:
				$this->setCreatedAt($value);
				break;
			case 11:
				$this->setUpdatedAt($value);
				break;
		} 	}

	
	public function fromArray($arr, $keyType = BasePeer::TYPE_PHPNAME)
	{
		$keys = BriefLayoutPeer::getFieldNames($keyType);

		if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
		if (array_key_exists($keys[1], $arr)) $this->setCategorie($arr[$keys[1]]);
		if (array_key_exists($keys[2], $arr)) $this->setNaam($arr[$keys[2]]);
		if (array_key_exists($keys[3], $arr)) $this->setPrintBestand($arr[$keys[3]]);
		if (array_key_exists($keys[4], $arr)) $this->setMailBestand($arr[$keys[4]]);
		if (array_key_exists($keys[5], $arr)) $this->setPrintStylesheets($arr[$keys[5]]);
		if (array_key_exists($keys[6], $arr)) $this->setMailStylesheets($arr[$keys[6]]);
		if (array_key_exists($keys[7], $arr)) $this->setVertaald($arr[$keys[7]]);
		if (array_key_exists($keys[8], $arr)) $this->setCreatedBy($arr[$keys[8]]);
		if (array_key_exists($keys[9], $arr)) $this->setUpdatedBy($arr[$keys[9]]);
		if (array_key_exists($keys[10], $arr)) $this->setCreatedAt($arr[$keys[10]]);
		if (array_key_exists($keys[11], $arr)) $this->setUpdatedAt($arr[$keys[11]]);
	}

	
	public function buildCriteria()
	{
		$criteria = new Criteria(BriefLayoutPeer::DATABASE_NAME);

		if ($this->isColumnModified(BriefLayoutPeer::ID)) $criteria->add(BriefLayoutPeer::ID, $this->id);
		if ($this->isColumnModified(BriefLayoutPeer::CATEGORIE)) $criteria->add(BriefLayoutPeer::CATEGORIE, $this->categorie);
		if ($this->isColumnModified(BriefLayoutPeer::NAAM)) $criteria->add(BriefLayoutPeer::NAAM, $this->naam);
		if ($this->isColumnModified(BriefLayoutPeer::PRINT_BESTAND)) $criteria->add(BriefLayoutPeer::PRINT_BESTAND, $this->print_bestand);
		if ($this->isColumnModified(BriefLayoutPeer::MAIL_BESTAND)) $criteria->add(BriefLayoutPeer::MAIL_BESTAND, $this->mail_bestand);
		if ($this->isColumnModified(BriefLayoutPeer::PRINT_STYLESHEETS)) $criteria->add(BriefLayoutPeer::PRINT_STYLESHEETS, $this->print_stylesheets);
		if ($this->isColumnModified(BriefLayoutPeer::MAIL_STYLESHEETS)) $criteria->add(BriefLayoutPeer::MAIL_STYLESHEETS, $this->mail_stylesheets);
		if ($this->isColumnModified(BriefLayoutPeer::VERTAALD)) $criteria->add(BriefLayoutPeer::VERTAALD, $this->vertaald);
		if ($this->isColumnModified(BriefLayoutPeer::CREATED_BY)) $criteria->add(BriefLayoutPeer::CREATED_BY, $this->created_by);
		if ($this->isColumnModified(BriefLayoutPeer::UPDATED_BY)) $criteria->add(BriefLayoutPeer::UPDATED_BY, $this->updated_by);
		if ($this->isColumnModified(BriefLayoutPeer::CREATED_AT)) $criteria->add(BriefLayoutPeer::CREATED_AT, $this->created_at);
		if ($this->isColumnModified(BriefLayoutPeer::UPDATED_AT)) $criteria->add(BriefLayoutPeer::UPDATED_AT, $this->updated_at);

		return $criteria;
	}

	
	public function buildPkeyCriteria()
	{
		$criteria = new Criteria(BriefLayoutPeer::DATABASE_NAME);

		$criteria->add(BriefLayoutPeer::ID, $this->id);

		return $criteria;
	}

	
	public function getPrimaryKey()
	{
		return $this->getId();
	}

	
	public function setPrimaryKey($key)
	{
		$this->setId($key);
	}

	
	public function copyInto($copyObj, $deepCopy = false)
	{

		$copyObj->setCategorie($this->categorie);

		$copyObj->setNaam($this->naam);

		$copyObj->setPrintBestand($this->print_bestand);

		$copyObj->setMailBestand($this->mail_bestand);

		$copyObj->setPrintStylesheets($this->print_stylesheets);

		$copyObj->setMailStylesheets($this->mail_stylesheets);

		$copyObj->setVertaald($this->vertaald);

		$copyObj->setCreatedBy($this->created_by);

		$copyObj->setUpdatedBy($this->updated_by);

		$copyObj->setCreatedAt($this->created_at);

		$copyObj->setUpdatedAt($this->updated_at);


		if ($deepCopy) {
									$copyObj->setNew(false);

			foreach($this->getBriefTemplates() as $relObj) {
				$copyObj->addBriefTemplate($relObj->copy($deepCopy));
			}

		} 

		$copyObj->setNew(true);

		$copyObj->setId(NULL); 
	}

	
	public function copy($deepCopy = false)
	{
				$clazz = get_class($this);
		$copyObj = new $clazz();
		$this->copyInto($copyObj, $deepCopy);
		return $copyObj;
	}

	
	public function getPeer()
	{
		if (self::$peer === null) {
			self::$peer = new BriefLayoutPeer();
		}
		return self::$peer;
	}

	
	public function initBriefTemplates()
	{
		if ($this->collBriefTemplates === null) {
			$this->collBriefTemplates = array();
		}
	}

	
	public function getBriefTemplates($criteria = null, $con = null)
	{
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
						if (!$this->isNew()) {
												

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

	
	public function countBriefTemplates($criteria = null, $distinct = false, $con = null)
	{
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

	
	public function addBriefTemplate(BriefTemplate $l)
	{
		$this->collBriefTemplates[] = $l;
		$l->setBriefLayout($this);
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


} 