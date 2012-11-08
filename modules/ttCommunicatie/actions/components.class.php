<?php
/**
 * brief componenten
 */

class ttCommunicatieComponents extends sfComponents
{
  /**
   * 
   * @param string $object_class
   * @param int $object_id
   * @param string $type default|bestemmeling
   * 
   * @return \myFilteredPager
   */
  public static function getCommunicatieLogPager($object_class, $object_id, $type = '' /* todo? $sqls */)
  {
    $pager = new myFilteredPager('BriefVerzonden', $object_class . $object_id . 'Communicatielog', null, BriefVerzondenPeer::CREATED_AT, false);

    if ($type == 'bestemmeling')
    {
      $pager->getCriteria()->add(BriefVerzondenPeer::OBJECT_CLASS_BESTEMMELING, $object_class);
      $pager->getCriteria()->add(BriefVerzondenPeer::OBJECT_ID_BESTEMMELING, $object_id);
    }
    else
    {
      $pager->getCriteria()->add(BriefVerzondenPeer::OBJECT_CLASS, $object_class);
      $pager->getCriteria()->add(BriefVerzondenPeer::OBJECT_ID, $object_id);
    }    
    
    /*
     TODO: BasePeer::createSelectSql(Criteria $criteria, &$params)
      
      $sql = 'SELECT * from (select * from ' . BriefVerzondenPeer::TABLE_NAME . ' where object_class = "' . $object_class . '" AND object_id = ' . $object_id . ')';

    foreach($sqls as $s)
    {
      $sql .= ' UNION ' . $s;
    }
    
    $sql .= ' ORDER BY '*/
     
    return $pager;
  }

  /**
   * Object communicatie log view list
   *
   * @param Object object
   * @param array options
   */
  public function executeObjectCommunicatieLog()
  {
    if ( ! $this->object)
    {
      throw new sfException('object communicatie log component verwacht een parameter "object" .');
    }
    
    $this->type = isset($this->type) ? $this->type : '';
    $this->metFilter = isset($this->metFilter) ? $this->metFilter : false;
    
    $this->pager = self::getCommunicatieLogPager(get_class($this->object), $this->object->getId(), $this->type);
    $this->pager->init();
  }
  
}