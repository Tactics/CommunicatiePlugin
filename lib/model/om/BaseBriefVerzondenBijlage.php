<?php


abstract class BaseBriefVerzondenBijlage extends BaseObject  implements Persistent {


	
	protected static $peer;


	
	protected $id;


	
	protected $brief_verzonden_id;


	
	protected $dms_node_id;

	
	protected $aBriefVerzonden;

	
	protected $aDmsNode;

	
	protected $alreadyInSave = false;

	
	protected $alreadyInValidation = false;

	
	public function getId()
	{

		return $this->id;
	}

	
	public function getBriefVerzondenId()
	{

		return $this->brief_verzonden_id;
	}

	
	public function getDmsNodeId()
	{

		return $this->dms_node_id;
	}

	
	public function setId($v)
	{

						if ($v !== null && !is_int($v) && is_numeric($v)) {
			$v = (int) $v;
		}

		if ($this->id !== $v) {
			$this->id = $v;
			$this->modifiedColumns[] = BriefVerzondenBijlagePeer::ID;
		}

	} 
	
	public function setBriefVerzondenId($v)
	{

						if ($v !== null && !is_int($v) && is_numeric($v)) {
			$v = (int) $v;
		}

		if ($this->brief_verzonden_id !== $v) {
			$this->brief_verzonden_id = $v;
			$this->modifiedColumns[] = BriefVerzondenBijlagePeer::BRIEF_VERZONDEN_ID;
		}

		if ($this->aBriefVerzonden !== null && $this->aBriefVerzonden->getId() !== $v) {
			$this->aBriefVerzonden = null;
		}

	} 
	
	public function setDmsNodeId($v)
	{

						if ($v !== null && !is_int($v) && is_numeric($v)) {
			$v = (int) $v;
		}

		if ($this->dms_node_id !== $v) {
			$this->dms_node_id = $v;
			$this->modifiedColumns[] = BriefVerzondenBijlagePeer::DMS_NODE_ID;
		}

		if ($this->aDmsNode !== null && $this->aDmsNode->getId() !== $v) {
			$this->aDmsNode = null;
		}

	} 
	
	public function hydrate(ResultSet $rs, $startcol = 1)
	{
		try {

			$this->id = $rs->getInt($startcol + 0);

			$this->brief_verzonden_id = $rs->getInt($startcol + 1);

			$this->dms_node_id = $rs->getInt($startcol + 2);

			$this->resetModified();

			$this->setNew(false);

			return $startcol + BriefVerzondenBijlagePeer::NUM_COLUMNS - BriefVerzondenBijlagePeer::NUM_LAZY_LOAD_COLUMNS;

		} catch (Exception $e) {
			throw new PropelException("Error populating BriefVerzondenBijlage object", $e);
		}
	}

	
	public function delete($con = null)
	{

    foreach (sfMixer::getCallables('BaseBriefVerzondenBijlage:delete:pre') as $callable)
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
			$con = Propel::getConnection(BriefVerzondenBijlagePeer::DATABASE_NAME);
		}

		try {
			$con->begin();
			BriefVerzondenBijlagePeer::doDelete($this, $con);
			$this->setDeleted(true);
			$con->commit();
		} catch (PropelException $e) {
			$con->rollback();
			throw $e;
		}
	

    foreach (sfMixer::getCallables('BaseBriefVerzondenBijlage:delete:post') as $callable)
    {
      call_user_func($callable, $this, $con);
    }

  }
	
	public function save($con = null)
	{

    foreach (sfMixer::getCallables('BaseBriefVerzondenBijlage:save:pre') as $callable)
    {
      $affectedRows = call_user_func($callable, $this, $con);
      if (is_int($affectedRows))
      {
        return $affectedRows;
      }
    }


		if ($this->isDeleted()) {
			throw new PropelException("You cannot save an object that has been deleted.");
		}

		if ($con === null) {
			$con = Propel::getConnection(BriefVerzondenBijlagePeer::DATABASE_NAME);
		}

		try {
			$con->begin();
			$affectedRows = $this->doSave($con);
			$con->commit();
    foreach (sfMixer::getCallables('BaseBriefVerzondenBijlage:save:post') as $callable)
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


												
			if ($this->aBriefVerzonden !== null) {
				if ($this->aBriefVerzonden->isModified()) {
					$affectedRows += $this->aBriefVerzonden->save($con);
				}
				$this->setBriefVerzonden($this->aBriefVerzonden);
			}

			if ($this->aDmsNode !== null) {
				if ($this->aDmsNode->isModified()) {
					$affectedRows += $this->aDmsNode->save($con);
				}
				$this->setDmsNode($this->aDmsNode);
			}


						if ($this->isModified()) {
				if ($this->isNew()) {
					$pk = BriefVerzondenBijlagePeer::doInsert($this, $con);
					$affectedRows += 1; 										 										 
					$this->setId($pk);  
					$this->setNew(false);
				} else {
					$affectedRows += BriefVerzondenBijlagePeer::doUpdate($this, $con);
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


												
			if ($this->aBriefVerzonden !== null) {
				if (!$this->aBriefVerzonden->validate($columns)) {
					$failureMap = array_merge($failureMap, $this->aBriefVerzonden->getValidationFailures());
				}
			}

			if ($this->aDmsNode !== null) {
				if (!$this->aDmsNode->validate($columns)) {
					$failureMap = array_merge($failureMap, $this->aDmsNode->getValidationFailures());
				}
			}


			if (($retval = BriefVerzondenBijlagePeer::doValidate($this, $columns)) !== true) {
				$failureMap = array_merge($failureMap, $retval);
			}



			$this->alreadyInValidation = false;
		}

		return (!empty($failureMap) ? $failureMap : true);
	}

	
	public function getByName($name, $type = BasePeer::TYPE_PHPNAME)
	{
		$pos = BriefVerzondenBijlagePeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
		return $this->getByPosition($pos);
	}

	
	public function getByPosition($pos)
	{
		switch($pos) {
			case 0:
				return $this->getId();
				break;
			case 1:
				return $this->getBriefVerzondenId();
				break;
			case 2:
				return $this->getDmsNodeId();
				break;
			default:
				return null;
				break;
		} 	}

	
	public function toArray($keyType = BasePeer::TYPE_PHPNAME)
	{
		$keys = BriefVerzondenBijlagePeer::getFieldNames($keyType);
		$result = array(
			$keys[0] => $this->getId(),
			$keys[1] => $this->getBriefVerzondenId(),
			$keys[2] => $this->getDmsNodeId(),
		);
		return $result;
	}

	
	public function setByName($name, $value, $type = BasePeer::TYPE_PHPNAME)
	{
		$pos = BriefVerzondenBijlagePeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
		$this->setByPosition($pos, $value);
	}

	
	public function setByPosition($pos, $value)
	{
		switch($pos) {
			case 0:
				$this->setId($value);
				break;
			case 1:
				$this->setBriefVerzondenId($value);
				break;
			case 2:
				$this->setDmsNodeId($value);
				break;
		} 	}

	
	public function fromArray($arr, $keyType = BasePeer::TYPE_PHPNAME)
	{
		$keys = BriefVerzondenBijlagePeer::getFieldNames($keyType);

		if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
		if (array_key_exists($keys[1], $arr)) $this->setBriefVerzondenId($arr[$keys[1]]);
		if (array_key_exists($keys[2], $arr)) $this->setDmsNodeId($arr[$keys[2]]);
	}

	
	public function buildCriteria()
	{
		$criteria = new Criteria(BriefVerzondenBijlagePeer::DATABASE_NAME);

		if ($this->isColumnModified(BriefVerzondenBijlagePeer::ID)) $criteria->add(BriefVerzondenBijlagePeer::ID, $this->id);
		if ($this->isColumnModified(BriefVerzondenBijlagePeer::BRIEF_VERZONDEN_ID)) $criteria->add(BriefVerzondenBijlagePeer::BRIEF_VERZONDEN_ID, $this->brief_verzonden_id);
		if ($this->isColumnModified(BriefVerzondenBijlagePeer::DMS_NODE_ID)) $criteria->add(BriefVerzondenBijlagePeer::DMS_NODE_ID, $this->dms_node_id);

		return $criteria;
	}

	
	public function buildPkeyCriteria()
	{
		$criteria = new Criteria(BriefVerzondenBijlagePeer::DATABASE_NAME);

		$criteria->add(BriefVerzondenBijlagePeer::ID, $this->id);

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

		$copyObj->setBriefVerzondenId($this->brief_verzonden_id);

		$copyObj->setDmsNodeId($this->dms_node_id);


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
			self::$peer = new BriefVerzondenBijlagePeer();
		}
		return self::$peer;
	}

	
	public function setBriefVerzonden($v)
	{


		if ($v === null) {
			$this->setBriefVerzondenId(NULL);
		} else {
			$this->setBriefVerzondenId($v->getId());
		}


		$this->aBriefVerzonden = $v;
	}


	
	public function getBriefVerzonden($con = null)
	{
		if ($this->aBriefVerzonden === null && ($this->brief_verzonden_id !== null)) {
						include_once 'plugins/ttCommunicatiePlugin/lib/model/om/BaseBriefVerzondenPeer.php';

			$this->aBriefVerzonden = BriefVerzondenPeer::retrieveByPK($this->brief_verzonden_id, $con);

			
		}
		return $this->aBriefVerzonden;
	}

	
	public function setDmsNode($v)
	{


		if ($v === null) {
			$this->setDmsNodeId(NULL);
		} else {
			$this->setDmsNodeId($v->getId());
		}


		$this->aDmsNode = $v;
	}


	
	public function getDmsNode($con = null)
	{
		if ($this->aDmsNode === null && ($this->dms_node_id !== null)) {
						include_once 'plugins/ttDmsPlugin/lib/model/om/BaseDmsNodePeer.php';

			$this->aDmsNode = DmsNodePeer::retrieveByPK($this->dms_node_id, $con);

			
		}
		return $this->aDmsNode;
	}


  public function __call($method, $arguments)
  {
    if (!$callable = sfMixer::getCallable('BaseBriefVerzondenBijlage:'.$method))
    {
      throw new sfException(sprintf('Call to undefined method BaseBriefVerzondenBijlage::%s', $method));
    }

    array_unshift($arguments, $this);

    return call_user_func_array($callable, $arguments);
  }


} 