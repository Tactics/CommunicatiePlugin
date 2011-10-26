<?php
/**
 * brief componenten
 */

class briefComponents extends sfComponents
{
  public static function getCommunicatieLogPager($object_class, $object_id)
  {
    $pager = new myFilteredPager('BriefVerzonden', $object_class . $object_id . 'Communicatielog', null, BriefVerzondenPeer::ID, false);

    $pager->getCriteria()->add(BriefVerzondenPeer::OBJECT_CLASS, $object_class);
    $pager->getCriteria()->add(BriefVerzondenPeer::OBJECT_ID, $object_id);

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