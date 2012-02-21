<?php
/**
 * brief componenten
 */

class ttCommunicatieComponents extends sfComponents
{
  public static function getCommunicatieLogPager($object_class, $object_id/* todo? $sqls */)
  {
    $pager = new myFilteredPager('BriefVerzonden', $object_class . $object_id . 'Communicatielog', null, BriefVerzondenPeer::ID, false);

    $pager->getCriteria()->add(BriefVerzondenPeer::OBJECT_CLASS, $object_class);
    $pager->getCriteria()->add(BriefVerzondenPeer::OBJECT_ID, $object_id);
    
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

    $this->object_class = get_class($this->object);
    $this->object_id = $this->object->getId();

    $this->pager = self::getCommunicatieLogPager($this->object_class, $this->object_id);
    $this->pager->init();
  }
  
}