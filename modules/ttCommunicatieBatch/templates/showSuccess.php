<?php if (!$sf_request->isXmlHttpRequest()) : ?>
<?php include_partial('breadcrumb', array('identifier' => 'Batchtaken', 'object' => $batchtaak)); ?>
<h2 class='pageblock'>Batchtaak</h2>
<div class='pageblock'>
  <table width='100%' class='objectdetails'>
    <tbody>
    <tr>
      <td width='50%'>
        <table>
          <tr>
            <th>Object:</th>
            <td><?php echo $batchtaak->getObjectClass(); ?></td>
          </tr>
          <tr>
            <th>Verzenden vanaf:</th>
            <td><?php echo $batchtaak->getVerzendenVanaf('d/m/Y H:i'); ?></td>
          </tr>
        </table>
      </td>
      <td width='50%'>
        <table>
          <tr>
            <th>Status:</th>
            <td><?php echo $batchtaak->getStatus(); ?></td>
          </tr>
          <tr>
            <th>Aantal:</th>
            <td><?php echo $batchtaak->getAantal(); ?></td>
          </tr>
        </table>
      </td>
    </tr>
    </tbody>
  </table>
</div>

<h2 class='pageblock'>E-mails in batchtaak</h2>
<div class='pageblock'>
  <div id="zoekresultaten">
    <?php endif; ?>
    <div class="filter">
      &nbsp;<?php echo $pager->getNbResults() ?> resultaten gevonden,
      <strong><?php echo $pager->getLastIndice() ? $pager->getFirstIndice() : 0 ?>
        -<?php echo $pager->getLastIndice() ?></strong> worden weergegeven.
    </div>
  <?php
    $table = new myTable(
      array(
        array('name' => BriefVerzondenPeer::ONDERWERP, 'text' => 'Onderwerp', 'align' => 'left', 'sortable' => true),
        array('name' => BriefVerzondenPeer::OBJECT_CLASS_BESTEMMELING, 'text' => 'Bestemmeling', 'align' => 'left', 'sortable' => true),
        array('name' => BriefVerzondenPeer::ADRES, 'text' => 'E-mail', 'align' => 'left', 'sortable' => true),
        array('name' => BriefVerzondenPeer::CC, 'text' => 'E-mail CC', 'align' => 'left', 'sortable' => true),
        array('name' => BriefVerzondenPeer::BCC, 'text' => 'E-mail BCC', 'align' => 'left', 'sortable' => true),
        array('name' => 'acties', 'text' => 'Acties', 'align' => 'center', 'width' => 75),
      ),
      array(
        "sortfield" => $pager->getOrderBy(),
        "sortorder" => $pager->getOrderAsc() ? "ASC" : "DESC",
        "sorturi" => 'ttCommunicatieBatch/show',
        "sorttarget" => 'zoekresultaten',
      )
    );

  foreach($pager->getResults() as $briefTeVerzenden)
  {
    $bestemmeling = $briefTeVerzenden->getBestemmeling();
    $table->addRow(array(
      array('content' => $briefTeVerzenden->getOnderwerp()),
      array('content' => $bestemmeling->__toString()),
      array('content' => $briefTeVerzenden->getAdres()),
      array('content' => $briefTeVerzenden->getCc()),
      array("content" => $briefTeVerzenden->getBcc()),
      array("content" => tt_link_to_remote(image_tag('icons/email.voorbeeld.png', array('width' => 16, 'height' => 16)), array('url' => 'ttCommunicatieBatch/preview?brief_id='.$briefTeVerzenden->getId(), 'title' => 'Preview mail', 'update' => 'details')))
    ));

  }

  echo $table;
  ?>
<?php echo pager_navigation($pager, "ttCommunicatieBatch/show?id=".$batchtaak->getId(), 'zoekresultaten') ?>
<?php if (!$sf_request->isXmlHttpRequest()): ?>
</div> <!-- /#zoekresultaten -->
</div> <!-- /pageblock -->

<div id="details"></div>
<?php endif ?>
