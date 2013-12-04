<?php
/*
 * Dit bestand maakt deel uit van een applicatie voor Digipolis Antwerpen.
 *
 * (c) <?php echo date('Y'); ?> Tactics BVBA
 *
 * Recht werd verleend om dit bestand te gebruiken als onderdeel van de genoemde
 * applicatie. Mag niet doorverkocht worden, noch rechtstreeks noch via een
 * derde partij. Meer informatie in het desbetreffende aankoopcontract.
 */

/**
 * brieven acties.
 *
 * @package    kdv
 * @subpackage brieven
 * @author     Glenn Van Loock
 */
class ttCommunicatieBatchActions extends sfActions
{
  /**
   * lijst van zelf batchtaken
   */
  public function executeList()
  {
    $this->pager = $this->getPager();
    $this->pager->init();
  }

  /**
   * pager aanmaken
   */
  private function getPager()
  {
    $pager = new myFilteredPager('BatchTaak', 'ttCommunicatieBatch/list');

    if(!$pager->addAndget(BatchTaakPeer::STATUS, array('addToCriteria' => false)))
    {
      $pager->getCriteria()->add(BatchTaakPeer::STATUS, BatchTaakPeer::STATUS_VERZONDEN, Criteria::NOT_EQUAL);
    }
    // filter: van/tot aantal
    $aantalVan = $pager->addAndGet('van_aantal', array('addToCriteria' => false));
    $aantalTot = $pager->addAndGet('tot_aantal', array('addToCriteria' => false));
    if ($aantalVan && $aantalTot)
    {
      $aantalVanCton = $pager->getCriteria()->getNewCriterion(BatchTaakPeer::AANTAL, $aantalVan, Criteria::GREATER_EQUAL);
      $aantalTotCton = $pager->getCriteria()->getNewCriterion(BatchTaakPeer::AANTAL, $aantalTot, Criteria::LESS_EQUAL);
      $pager->getCriteria()->add($aantalVanCton->addAnd($aantalTotCton));
    }
    else if ($aantalVan)
    {
      $pager->getCriteria()->add(BatchTaakPeer::AANTAL, $aantalVan, Criteria::GREATER_EQUAL);
    }
    else if ($aantalTot)
    {
      $pager->getCriteria()->add(BatchTaakPeer::AANTAL, $aantalTot, Criteria::LESS_EQUAL);
    }

    // filter: van/tot aantal
    $datumVan = $pager->addAndGet('van_datum', array('addToCriteria' => false));
    $datumTot = $pager->addAndGet('tot_datum', array('addToCriteria' => false));
    if ($datumVan && $datumTot)
    {
      $datumVanCton = $pager->getCriteria()->getNewCriterion(BatchTaakPeer::VERZENDEN_VANAF, $datumVan, Criteria::GREATER_EQUAL);
      $datumTotCton = $pager->getCriteria()->getNewCriterion(BatchTaakPeer::VERZENDEN_VANAF, $datumTot, Criteria::LESS_EQUAL);
      $pager->getCriteria()->add($datumVanCton->addAnd($datumTotCton));
    }
    else if ($datumVan)
    {
      $pager->getCriteria()->add(BatchTaakPeer::VERZENDEN_VANAF, $datumVan, Criteria::GREATER_EQUAL);
    }
    else if ($datumTot)
    {
      $pager->getCriteria()->add(BatchTaakPeer::VERZENDEN_VANAF, $datumTot, Criteria::LESS_EQUAL);
    }

    $pager->add(BatchTaakPeer::STATUS);

    return $pager;
  }

  /**
   * Toont details van een batchtaak + lijst van verzonden brieven
   */
  public function executeShow()
  {
    $this->batchtaak = BatchTaakPeer::retrieveByPK($this->getRequestParameter('id'));
    $this->forward404Unless($this->batchtaak);

    $this->pager = new myFilteredPager('BriefVerzonden', 'ttCommunicatieBatch/show', 10);
    $this->pager->getCriteria()->add(BriefVerzondenPeer::BATCHTAAK_ID, $this->getRequestParameter('id'));

    $this->pager->init();
  }

  public function executeChangeStatus()
  {
    $batchTaak = BatchTaakPeer::retrieveByPK($this->getRequestParameter('id'));
    $this->forward404Unless($batchTaak);

    $batchTaak->setStatus($this->getRequestParameter('status'));
    $batchTaak->save();

    $this->redirect('ttCommunicatieBatch/list');
  }  

  /**
   * Echo html van de mail als preview
   */
  public function executePreview()
  {
    $this->briefVerzonden = BriefVerzondenPeer::retrieveByPK($this->getRequestParameter('brief_id'));
  }
}