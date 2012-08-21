<?php


abstract class BaseBriefTemplate extends BaseObject  implements Persistent {


	
	protected static $peer;


	
	protected $id;


	
	protected $categorie;


	
	protected $brief_layout_id;


	
	protected $onderwerp;


	
	protected $naam;


	
	protected $type;


	
	protected $bestemmeling_classes;


	
	protected $html;


	
	protected $eenmalig_versturen;


	
	protected $systeemnaam;


	
	protected $systeemplaceholders;


	
	protected $gearchiveerd = 0;


	
	protected $created_by;


	
	protected $updated_by;


	
	protected $created_at;


	
	protected $updated_at;

	
	protected $aBriefLayout;

	
	protected $collBriefVerzondens;

	
	protected $lastBriefVerzondenCriteria = null;

	
	protected $collBriefBijlages;

	
	protected $lastBriefBijlageCriteria = null;

	
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

	
	public function getBriefLayoutId()
	{

		return $this->brief_layout_id;
	}

	
	public function getOnderwerp()
	{

		return $this->onderwerp;
	}

	
	public function getNaam()
	{

		return $this->naam;
	}

	
	public function getType()
	{

		return $this->type;
	}

	
	public function getBestemmelingClasses()
	{

		return $this->bestemmeling_classes;
	}

	
	public function getHtml()
	{

		return $this->html;
	}

	
	public function getEenmaligVersturen()
	{

		return $this->eenmalig_versturen;
	}

	
	public function getSysteemnaam()
	{

		return $this->systeemnaam;
	}

	
	public function getSysteemplaceholders()
	{

		return $this->systeemplaceholders;
	}

	
	public function getGearchiveerd()
	{

		return $this->gearchiveerd;
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
			$this->modifiedColumns[] = BriefTemplatePeer::ID;
		}

	} 
	
	public function setCategorie($v)
	{

		
		
		if ($v !== null && !is_int($v) && is_numeric($v)) {
			$v = (int) $v;
		}

		if ($this->categorie !== $v) {
			$this->categorie = $v;
			$this->modifiedColumns[] = BriefTemplatePeer::CATEGORIE;
		}

	} 
	
	public function setBriefLayoutId($v)
	{

		
		
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

	} 
	
	public function setOnderwerp($v)
	{

		
		
		if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->onderwerp !== $v) {
			$this->onderwerp = $v;
			$this->modifiedColumns[] = BriefTemplatePeer::ONDERWERP;
		}

	} 
	
	public function setNaam($v)
	{

		
		
		if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->naam !== $v) {
			$this->naam = $v;
			$this->modifiedColumns[] = BriefTemplatePeer::NAAM;
		}

	} 
	
	public function setType($v)
	{

		
		
		if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->type !== $v) {
			$this->type = $v;
			$this->modifiedColumns[] = BriefTemplatePeer::TYPE;
		}

	} 
	
	public function setBestemmelingClasses($v)
	{

		
		
		if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->bestemmeling_classes !== $v) {
			$this->bestemmeling_classes = $v;
			$this->modifiedColumns[] = BriefTemplatePeer::BESTEMMELING_CLASSES;
		}

	} 
	
	public function setHtml($v)
	{

		
		
		if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->html !== $v) {
			$this->html = $v;
			$this->modifiedColumns[] = BriefTemplatePeer::HTML;
		}

	} 
	
	public function setEenmaligVersturen($v)
	{

		if ($this->eenmalig_versturen !== $v) {
			$this->eenmalig_versturen = $v;
			$this->modifiedColumns[] = BriefTemplatePeer::EENMALIG_VERSTUREN;
		}

	} 
	
	public function setSysteemnaam($v)
	{

		
		
		if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->systeemnaam !== $v) {
			$this->systeemnaam = $v;
			$this->modifiedColumns[] = BriefTemplatePeer::SYSTEEMNAAM;
		}

	} 
	
	public function setSysteemplaceholders($v)
	{

		
		
		if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->systeemplaceholders !== $v) {
			$this->systeemplaceholders = $v;
			$this->modifiedColumns[] = BriefTemplatePeer::SYSTEEMPLACEHOLDERS;
		}

	} 
	
	public function setGearchiveerd($v)
	{

		
		
		if ($v !== null && !is_int($v) && is_numeric($v)) {
			$v = (int) $v;
		}

		if ($this->gearchiveerd !== $v || $v === 0) {
			$this->gearchiveerd = $v;
			$this->modifiedColumns[] = BriefTemplatePeer::GEARCHIVEERD;
		}

	} 
	
	public function setCreatedBy($v)
	{

		
		
		if ($v !== null && !is_int($v) && is_numeric($v)) {
			$v = (int) $v;
		}

		if ($this->created_by !== $v) {
			$this->created_by = $v;
			$this->modifiedColumns[] = BriefTemplatePeer::CREATED_BY;
		}

	} 
	
	public function setUpdatedBy($v)
	{

		
		
		if ($v !== null && !is_int($v) && is_numeric($v)) {
			$v = (int) $v;
		}

		if ($this->updated_by !== $v) {
			$this->updated_by = $v;
			$this->modifiedColumns[] = BriefTemplatePeer::UPDATED_BY;
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
			$this->modifiedColumns[] = BriefTemplatePeer::CREATED_AT;
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
			$this->modifiedColumns[] = BriefTemplatePeer::UPDATED_AT;
		}

	} 
	
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

			$this->created_by = $rs->getInt($startcol + 12);

			$this->updated_by = $rs->getInt($startcol + 13);

			$this->created_at = $rs->getTimestamp($startcol + 14, null);

			$this->updated_at = $rs->getTimestamp($startcol + 15, null);

			$this->resetModified();

			$this->setNew(false);

						return $startcol + 16; 
		} catch (Exception $e) {
			throw new PropelException("Error populating BriefTemplate object", $e);
		}
	}

	
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

	
	protected function doSave($con)
	{
		$affectedRows = 0; 		if (!$this->alreadyInSave) {
			$this->alreadyInSave = true;


												
			if ($this->aBriefLayout !== null) {
				if ($this->aBriefLayout->isModified()) {
					$affectedRows += $this->aBriefLayout->save($con);
				}
				$this->setBriefLayout($this->aBriefLayout);
			}


						if ($this->isModified()) {
				if ($this->isNew()) {
					$pk = BriefTemplatePeer::doInsert($this, $con);
					$affectedRows += 1; 										 										 
					$this->setId($pk);  
					$this->setNew(false);
				} else {
					$affectedRows += BriefTemplatePeer::doUpdate($this, $con);
				}
				$this->resetModified(); 			}

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

	
	public function getByName($name, $type = BasePeer::TYPE_PHPNAME)
	{
		$pos = BriefTemplatePeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
				return $this->getCreatedBy();
				break;
			case 13:
				return $this->getUpdatedBy();
				break;
			case 14:
				return $this->getCreatedAt();
				break;
			case 15:
				return $this->getUpdatedAt();
				break;
			default:
				return null;
				break;
		} 	}

	
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
			$keys[12] => $this->getCreatedBy(),
			$keys[13] => $this->getUpdatedBy(),
			$keys[14] => $this->getCreatedAt(),
			$keys[15] => $this->getUpdatedAt(),
		);
		return $result;
	}

	
	public function setByName($name, $value, $type = BasePeer::TYPE_PHPNAME)
	{
		$pos = BriefTemplatePeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
		return $this->setByPosition($pos, $value);
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
				$this->setCreatedBy($value);
				break;
			case 13:
				$this->setUpdatedBy($value);
				break;
			case 14:
				$this->setCreatedAt($value);
				break;
			case 15:
				$this->setUpdatedAt($value);
				break;
		} 	}

	
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
		if (array_key_exists($keys[12], $arr)) $this->setCreatedBy($arr[$keys[12]]);
		if (array_key_exists($keys[13], $arr)) $this->setUpdatedBy($arr[$keys[13]]);
		if (array_key_exists($keys[14], $arr)) $this->setCreatedAt($arr[$keys[14]]);
		if (array_key_exists($keys[15], $arr)) $this->setUpdatedAt($arr[$keys[15]]);
	}

	
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
		if ($this->isColumnModified(BriefTemplatePeer::CREATED_BY)) $criteria->add(BriefTemplatePeer::CREATED_BY, $this->created_by);
		if ($this->isColumnModified(BriefTemplatePeer::UPDATED_BY)) $criteria->add(BriefTemplatePeer::UPDATED_BY, $this->updated_by);
		if ($this->isColumnModified(BriefTemplatePeer::CREATED_AT)) $criteria->add(BriefTemplatePeer::CREATED_AT, $this->created_at);
		if ($this->isColumnModified(BriefTemplatePeer::UPDATED_AT)) $criteria->add(BriefTemplatePeer::UPDATED_AT, $this->updated_at);

		return $criteria;
	}

	
	public function buildPkeyCriteria()
	{
		$criteria = new Criteria(BriefTemplatePeer::DATABASE_NAME);

		$criteria->add(BriefTemplatePeer::ID, $this->id);

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

		$copyObj->setCreatedBy($this->created_by);

		$copyObj->setUpdatedBy($this->updated_by);

		$copyObj->setCreatedAt($this->created_at);

		$copyObj->setUpdatedAt($this->updated_at);


		if ($deepCopy) {
									$copyObj->setNew(false);

			foreach($this->getBriefVerzondens() as $relObj) {
				$copyObj->addBriefVerzonden($relObj->copy($deepCopy));
			}

			foreach($this->getBriefBijlages() as $relObj) {
				$copyObj->addBriefBijlage($relObj->copy($deepCopy));
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
			self::$peer = new BriefTemplatePeer();
		}
		return self::$peer;
	}

	
	public function setBriefLayout($v)
	{


		if ($v === null) {
			$this->setBriefLayoutId(NULL);
		} else {
			$this->setBriefLayoutId($v->getId());
		}


		$this->aBriefLayout = $v;
	}


	
	public function getBriefLayout($con = null)
	{
		if ($this->aBriefLayout === null && ($this->brief_layout_id !== null)) {
						include_once 'plugins/ttCommunicatiePlugin/lib/model/om/BaseBriefLayoutPeer.php';

			$this->aBriefLayout = BriefLayoutPeer::retrieveByPK($this->brief_layout_id, $con);

			
		}
		return $this->aBriefLayout;
	}

	
	public function initBriefVerzondens()
	{
		if ($this->collBriefVerzondens === null) {
			$this->collBriefVerzondens = array();
		}
	}

	
	public function getBriefVerzondens($criteria = null, $con = null)
	{
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
						if (!$this->isNew()) {
												

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

	
	public function countBriefVerzondens($criteria = null, $distinct = false, $con = null)
	{
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

	
	public function addBriefVerzonden(BriefVerzonden $l)
	{
		$this->collBriefVerzondens[] = $l;
		$l->setBriefTemplate($this);
	}

	
	public function initBriefBijlages()
	{
		if ($this->collBriefBijlages === null) {
			$this->collBriefBijlages = array();
		}
	}

	
	public function getBriefBijlages($criteria = null, $con = null)
	{
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
						if (!$this->isNew()) {
												

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

	
	public function countBriefBijlages($criteria = null, $distinct = false, $con = null)
	{
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


} 