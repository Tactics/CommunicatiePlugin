<?php


abstract class BaseBriefBijlage extends BaseObject  implements Persistent {


	
	protected static $peer;


	
	protected $id;


	
	protected $brief_template_id;


	
	protected $bijlage_node_id;


	
	protected $culture;


	
	protected $created_at;


	
	protected $updated_at;


	
	protected $created_by;


	
	protected $updated_by;

	
	protected $aBriefTemplate;

	
	protected $alreadyInSave = false;

	
	protected $alreadyInValidation = false;

	
	public function getId()
	{

		return $this->id;
	}

	
	public function getBriefTemplateId()
	{

		return $this->brief_template_id;
	}

	
	public function getBijlageNodeId()
	{

		return $this->bijlage_node_id;
	}

	
	public function getCulture()
	{

		return $this->culture;
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

	
	public function getCreatedBy()
	{

		return $this->created_by;
	}

	
	public function getUpdatedBy()
	{

		return $this->updated_by;
	}

	
	public function setId($v)
	{

						if ($v !== null && !is_int($v) && is_numeric($v)) {
			$v = (int) $v;
		}

		if ($this->id !== $v) {
			$this->id = $v;
			$this->modifiedColumns[] = BriefBijlagePeer::ID;
		}

	} 
	
	public function setBriefTemplateId($v)
	{

						if ($v !== null && !is_int($v) && is_numeric($v)) {
			$v = (int) $v;
		}

		if ($this->brief_template_id !== $v) {
			$this->brief_template_id = $v;
			$this->modifiedColumns[] = BriefBijlagePeer::BRIEF_TEMPLATE_ID;
		}

		if ($this->aBriefTemplate !== null && $this->aBriefTemplate->getId() !== $v) {
			$this->aBriefTemplate = null;
		}

	} 
	
	public function setBijlageNodeId($v)
	{

						if ($v !== null && !is_int($v) && is_numeric($v)) {
			$v = (int) $v;
		}

		if ($this->bijlage_node_id !== $v) {
			$this->bijlage_node_id = $v;
			$this->modifiedColumns[] = BriefBijlagePeer::BIJLAGE_NODE_ID;
		}

	} 
	
	public function setCulture($v)
	{

						if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->culture !== $v) {
			$this->culture = $v;
			$this->modifiedColumns[] = BriefBijlagePeer::CULTURE;
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
			$this->modifiedColumns[] = BriefBijlagePeer::CREATED_AT;
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
			$this->modifiedColumns[] = BriefBijlagePeer::UPDATED_AT;
		}

	} 
	
	public function setCreatedBy($v)
	{

						if ($v !== null && !is_int($v) && is_numeric($v)) {
			$v = (int) $v;
		}

		if ($this->created_by !== $v) {
			$this->created_by = $v;
			$this->modifiedColumns[] = BriefBijlagePeer::CREATED_BY;
		}

	} 
	
	public function setUpdatedBy($v)
	{

						if ($v !== null && !is_int($v) && is_numeric($v)) {
			$v = (int) $v;
		}

		if ($this->updated_by !== $v) {
			$this->updated_by = $v;
			$this->modifiedColumns[] = BriefBijlagePeer::UPDATED_BY;
		}

	} 
	
	public function hydrate(ResultSet $rs, $startcol = 1)
	{
		try {

			$this->id = $rs->getInt($startcol + 0);

			$this->brief_template_id = $rs->getInt($startcol + 1);

			$this->bijlage_node_id = $rs->getInt($startcol + 2);

			$this->culture = $rs->getString($startcol + 3);

			$this->created_at = $rs->getTimestamp($startcol + 4, null);

			$this->updated_at = $rs->getTimestamp($startcol + 5, null);

			$this->created_by = $rs->getInt($startcol + 6);

			$this->updated_by = $rs->getInt($startcol + 7);

			$this->resetModified();

			$this->setNew(false);

						return $startcol + 8; 
		} catch (Exception $e) {
			throw new PropelException("Error populating BriefBijlage object", $e);
		}
	}

	
	public function delete($con = null)
	{

    foreach (sfMixer::getCallables('BaseBriefBijlage:delete:pre') as $callable)
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
			$con = Propel::getConnection(BriefBijlagePeer::DATABASE_NAME);
		}

		try {
			$con->begin();
			BriefBijlagePeer::doDelete($this, $con);
			$this->setDeleted(true);
			$con->commit();
		} catch (PropelException $e) {
			$con->rollback();
			throw $e;
		}
	

    foreach (sfMixer::getCallables('BaseBriefBijlage:delete:post') as $callable)
    {
      call_user_func($callable, $this, $con);
    }

  }
	
	public function save($con = null)
	{

    foreach (sfMixer::getCallables('BaseBriefBijlage:save:pre') as $callable)
    {
      $affectedRows = call_user_func($callable, $this, $con);
      if (is_int($affectedRows))
      {
        return $affectedRows;
      }
    }


    if ($this->isNew() && !$this->isColumnModified(BriefBijlagePeer::CREATED_AT))
    {
      $this->setCreatedAt(time());
    }

    if ($this->isModified() && !$this->isColumnModified(BriefBijlagePeer::UPDATED_AT))
    {
      $this->setUpdatedAt(time());
    }

		if ($this->isDeleted()) {
			throw new PropelException("You cannot save an object that has been deleted.");
		}

		if ($con === null) {
			$con = Propel::getConnection(BriefBijlagePeer::DATABASE_NAME);
		}

		try {
			$con->begin();
			$affectedRows = $this->doSave($con);
			$con->commit();
    foreach (sfMixer::getCallables('BaseBriefBijlage:save:post') as $callable)
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
					$pk = BriefBijlagePeer::doInsert($this, $con);
					$affectedRows += 1; 										 										 
					$this->setId($pk);  
					$this->setNew(false);
				} else {
					$affectedRows += BriefBijlagePeer::doUpdate($this, $con);
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


			if (($retval = BriefBijlagePeer::doValidate($this, $columns)) !== true) {
				$failureMap = array_merge($failureMap, $retval);
			}



			$this->alreadyInValidation = false;
		}

		return (!empty($failureMap) ? $failureMap : true);
	}

	
	public function getByName($name, $type = BasePeer::TYPE_PHPNAME)
	{
		$pos = BriefBijlagePeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
		return $this->getByPosition($pos);
	}

	
	public function getByPosition($pos)
	{
		switch($pos) {
			case 0:
				return $this->getId();
				break;
			case 1:
				return $this->getBriefTemplateId();
				break;
			case 2:
				return $this->getBijlageNodeId();
				break;
			case 3:
				return $this->getCulture();
				break;
			case 4:
				return $this->getCreatedAt();
				break;
			case 5:
				return $this->getUpdatedAt();
				break;
			case 6:
				return $this->getCreatedBy();
				break;
			case 7:
				return $this->getUpdatedBy();
				break;
			default:
				return null;
				break;
		} 	}

	
	public function toArray($keyType = BasePeer::TYPE_PHPNAME)
	{
		$keys = BriefBijlagePeer::getFieldNames($keyType);
		$result = array(
			$keys[0] => $this->getId(),
			$keys[1] => $this->getBriefTemplateId(),
			$keys[2] => $this->getBijlageNodeId(),
			$keys[3] => $this->getCulture(),
			$keys[4] => $this->getCreatedAt(),
			$keys[5] => $this->getUpdatedAt(),
			$keys[6] => $this->getCreatedBy(),
			$keys[7] => $this->getUpdatedBy(),
		);
		return $result;
	}

	
	public function setByName($name, $value, $type = BasePeer::TYPE_PHPNAME)
	{
		$pos = BriefBijlagePeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
		return $this->setByPosition($pos, $value);
	}

	
	public function setByPosition($pos, $value)
	{
		switch($pos) {
			case 0:
				$this->setId($value);
				break;
			case 1:
				$this->setBriefTemplateId($value);
				break;
			case 2:
				$this->setBijlageNodeId($value);
				break;
			case 3:
				$this->setCulture($value);
				break;
			case 4:
				$this->setCreatedAt($value);
				break;
			case 5:
				$this->setUpdatedAt($value);
				break;
			case 6:
				$this->setCreatedBy($value);
				break;
			case 7:
				$this->setUpdatedBy($value);
				break;
		} 	}

	
	public function fromArray($arr, $keyType = BasePeer::TYPE_PHPNAME)
	{
		$keys = BriefBijlagePeer::getFieldNames($keyType);

		if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
		if (array_key_exists($keys[1], $arr)) $this->setBriefTemplateId($arr[$keys[1]]);
		if (array_key_exists($keys[2], $arr)) $this->setBijlageNodeId($arr[$keys[2]]);
		if (array_key_exists($keys[3], $arr)) $this->setCulture($arr[$keys[3]]);
		if (array_key_exists($keys[4], $arr)) $this->setCreatedAt($arr[$keys[4]]);
		if (array_key_exists($keys[5], $arr)) $this->setUpdatedAt($arr[$keys[5]]);
		if (array_key_exists($keys[6], $arr)) $this->setCreatedBy($arr[$keys[6]]);
		if (array_key_exists($keys[7], $arr)) $this->setUpdatedBy($arr[$keys[7]]);
	}

	
	public function buildCriteria()
	{
		$criteria = new Criteria(BriefBijlagePeer::DATABASE_NAME);

		if ($this->isColumnModified(BriefBijlagePeer::ID)) $criteria->add(BriefBijlagePeer::ID, $this->id);
		if ($this->isColumnModified(BriefBijlagePeer::BRIEF_TEMPLATE_ID)) $criteria->add(BriefBijlagePeer::BRIEF_TEMPLATE_ID, $this->brief_template_id);
		if ($this->isColumnModified(BriefBijlagePeer::BIJLAGE_NODE_ID)) $criteria->add(BriefBijlagePeer::BIJLAGE_NODE_ID, $this->bijlage_node_id);
		if ($this->isColumnModified(BriefBijlagePeer::CULTURE)) $criteria->add(BriefBijlagePeer::CULTURE, $this->culture);
		if ($this->isColumnModified(BriefBijlagePeer::CREATED_AT)) $criteria->add(BriefBijlagePeer::CREATED_AT, $this->created_at);
		if ($this->isColumnModified(BriefBijlagePeer::UPDATED_AT)) $criteria->add(BriefBijlagePeer::UPDATED_AT, $this->updated_at);
		if ($this->isColumnModified(BriefBijlagePeer::CREATED_BY)) $criteria->add(BriefBijlagePeer::CREATED_BY, $this->created_by);
		if ($this->isColumnModified(BriefBijlagePeer::UPDATED_BY)) $criteria->add(BriefBijlagePeer::UPDATED_BY, $this->updated_by);

		return $criteria;
	}

	
	public function buildPkeyCriteria()
	{
		$criteria = new Criteria(BriefBijlagePeer::DATABASE_NAME);

		$criteria->add(BriefBijlagePeer::ID, $this->id);

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

		$copyObj->setBriefTemplateId($this->brief_template_id);

		$copyObj->setBijlageNodeId($this->bijlage_node_id);

		$copyObj->setCulture($this->culture);

		$copyObj->setCreatedAt($this->created_at);

		$copyObj->setUpdatedAt($this->updated_at);

		$copyObj->setCreatedBy($this->created_by);

		$copyObj->setUpdatedBy($this->updated_by);


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
			self::$peer = new BriefBijlagePeer();
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
    if (!$callable = sfMixer::getCallable('BaseBriefBijlage:'.$method))
    {
      throw new sfException(sprintf('Call to undefined method BaseBriefBijlage::%s', $method));
    }

    array_unshift($arguments, $this);

    return call_user_func_array($callable, $arguments);
  }


} 