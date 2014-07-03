<?php


abstract class BaseBriefVerzonden extends BaseObject  implements Persistent {


	
	protected static $peer;


	
	protected $id;


	
	protected $object_class;


	
	protected $object_id;


	
	protected $object_class_bestemmeling;


	
	protected $object_id_bestemmeling;


	
	protected $brief_template_id;


	
	protected $onderwerp;


	
	protected $html;


	
	protected $medium;


	
	protected $adres;


	
	protected $custom = false;


	
	protected $culture;


	
	protected $status;


	
	protected $created_by;


	
	protected $updated_by;


	
	protected $created_at;


	
	protected $updated_at;

	
	protected $aBriefTemplate;

	
	protected $alreadyInSave = false;

	
	protected $alreadyInValidation = false;

	
	public function getId()
	{

		return $this->id;
	}

	
	public function getObjectClass()
	{

		return $this->object_class;
	}

	
	public function getObjectId()
	{

		return $this->object_id;
	}

	
	public function getObjectClassBestemmeling()
	{

		return $this->object_class_bestemmeling;
	}

	
	public function getObjectIdBestemmeling()
	{

		return $this->object_id_bestemmeling;
	}

	
	public function getBriefTemplateId()
	{

		return $this->brief_template_id;
	}

	
	public function getOnderwerp()
	{

		return $this->onderwerp;
	}

	
	public function getHtml()
	{

		return $this->html;
	}

	
	public function getMedium()
	{

		return $this->medium;
	}

	
	public function getAdres()
	{

		return $this->adres;
	}

	
	public function getCustom()
	{

		return $this->custom;
	}

	
	public function getCulture()
	{

		return $this->culture;
	}

	
	public function getStatus()
	{

		return $this->status;
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
			$this->modifiedColumns[] = BriefVerzondenPeer::ID;
		}

	} 
	
	public function setObjectClass($v)
	{

						if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->object_class !== $v) {
			$this->object_class = $v;
			$this->modifiedColumns[] = BriefVerzondenPeer::OBJECT_CLASS;
		}

	} 
	
	public function setObjectId($v)
	{

						if ($v !== null && !is_int($v) && is_numeric($v)) {
			$v = (int) $v;
		}

		if ($this->object_id !== $v) {
			$this->object_id = $v;
			$this->modifiedColumns[] = BriefVerzondenPeer::OBJECT_ID;
		}

	} 
	
	public function setObjectClassBestemmeling($v)
	{

						if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->object_class_bestemmeling !== $v) {
			$this->object_class_bestemmeling = $v;
			$this->modifiedColumns[] = BriefVerzondenPeer::OBJECT_CLASS_BESTEMMELING;
		}

	} 
	
	public function setObjectIdBestemmeling($v)
	{

						if ($v !== null && !is_int($v) && is_numeric($v)) {
			$v = (int) $v;
		}

		if ($this->object_id_bestemmeling !== $v) {
			$this->object_id_bestemmeling = $v;
			$this->modifiedColumns[] = BriefVerzondenPeer::OBJECT_ID_BESTEMMELING;
		}

	} 
	
	public function setBriefTemplateId($v)
	{

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

	} 
	
	public function setOnderwerp($v)
	{

						if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->onderwerp !== $v) {
			$this->onderwerp = $v;
			$this->modifiedColumns[] = BriefVerzondenPeer::ONDERWERP;
		}

	} 
	
	public function setHtml($v)
	{

						if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->html !== $v) {
			$this->html = $v;
			$this->modifiedColumns[] = BriefVerzondenPeer::HTML;
		}

	} 
	
	public function setMedium($v)
	{

						if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->medium !== $v) {
			$this->medium = $v;
			$this->modifiedColumns[] = BriefVerzondenPeer::MEDIUM;
		}

	} 
	
	public function setAdres($v)
	{

						if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->adres !== $v) {
			$this->adres = $v;
			$this->modifiedColumns[] = BriefVerzondenPeer::ADRES;
		}

	} 
	
	public function setCustom($v)
	{

		if ($this->custom !== $v || $v === false) {
			$this->custom = $v;
			$this->modifiedColumns[] = BriefVerzondenPeer::CUSTOM;
		}

	} 
	
	public function setCulture($v)
	{

						if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->culture !== $v) {
			$this->culture = $v;
			$this->modifiedColumns[] = BriefVerzondenPeer::CULTURE;
		}

	} 
	
	public function setStatus($v)
	{

						if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->status !== $v) {
			$this->status = $v;
			$this->modifiedColumns[] = BriefVerzondenPeer::STATUS;
		}

	} 
	
	public function setCreatedBy($v)
	{

						if ($v !== null && !is_int($v) && is_numeric($v)) {
			$v = (int) $v;
		}

		if ($this->created_by !== $v) {
			$this->created_by = $v;
			$this->modifiedColumns[] = BriefVerzondenPeer::CREATED_BY;
		}

	} 
	
	public function setUpdatedBy($v)
	{

						if ($v !== null && !is_int($v) && is_numeric($v)) {
			$v = (int) $v;
		}

		if ($this->updated_by !== $v) {
			$this->updated_by = $v;
			$this->modifiedColumns[] = BriefVerzondenPeer::UPDATED_BY;
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
			$this->modifiedColumns[] = BriefVerzondenPeer::CREATED_AT;
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
			$this->modifiedColumns[] = BriefVerzondenPeer::UPDATED_AT;
		}

	} 
	
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

						return $startcol + 17; 
		} catch (Exception $e) {
			throw new PropelException("Error populating BriefVerzonden object", $e);
		}
	}

	
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

	
	protected function doSave($con)
	{
		$affectedRows = 0; 		if (!$this->alreadyInSave) {
			$this->alreadyInSave = true;


												
			if ($this->aBriefTemplate !== null) {
				if ($this->aBriefTemplate->isModified()) {
					$affectedRows += $this->aBriefTemplate->save($con);
				}
				$this->setBriefTemplate($this->aBriefTemplate);
			}


						if ($this->isModified()) {
				if ($this->isNew()) {
					$pk = BriefVerzondenPeer::doInsert($this, $con);
					$affectedRows += 1; 										 										 
					$this->setId($pk);  
					$this->setNew(false);
				} else {
					$affectedRows += BriefVerzondenPeer::doUpdate($this, $con);
				}
				$this->resetModified(); 			}

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

	
	public function getByName($name, $type = BasePeer::TYPE_PHPNAME)
	{
		$pos = BriefVerzondenPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
		return $this->getByPosition($pos);
	}

	
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
		} 	}

	
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

	
	public function setByName($name, $value, $type = BasePeer::TYPE_PHPNAME)
	{
		$pos = BriefVerzondenPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
		return $this->setByPosition($pos, $value);
	}

	
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
		} 	}

	
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

	
	public function buildPkeyCriteria()
	{
		$criteria = new Criteria(BriefVerzondenPeer::DATABASE_NAME);

		$criteria->add(BriefVerzondenPeer::ID, $this->id);

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
			self::$peer = new BriefVerzondenPeer();
		}
		return self::$peer;
	}

	
	public function setBriefTemplate($v)
	{


		if ($v === null) {
			$this->setBriefTemplateId(NULL);
		} else {
			$this->setBriefTemplateId($v->getId());
		}


		$this->aBriefTemplate = $v;
	}


	
	public function getBriefTemplate($con = null)
	{
		if ($this->aBriefTemplate === null && ($this->brief_template_id !== null)) {
						include_once 'plugins/ttCommunicatiePlugin/lib/model/om/BaseBriefTemplatePeer.php';

			$this->aBriefTemplate = BriefTemplatePeer::retrieveByPK($this->brief_template_id, $con);

			
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


} 