<?php if (!$sf_request->isXmlHttpRequest()) : ?>

  <?php include_partial('breadcrumb', array('identifier' => 'Batchtaken')) ?>

  <h2 class="pageblock">Filter</h2>

  <?php echo tt_form_remote_tag(array(
      'update' => 'zoekresultaten',
      'url' => 'ttCommunicatieBatch/list',
      'script' => true,
    ),
    array(
      'id' => 'zoekform'
    )
  );
  ?>

  <div class="pageblock">
    <div class="smaller">

      <table width='100%'>
        <tr>
          <td style="width: 50%">
            <table class="formtable" width='100%'>
              <tbody>
              <tr>
                <th>Aantal:</th>
                <td>Van <?php echo input_tag("van_aantal", $pager->get('van_aantal')); ?>
                  tot <?php echo input_tag("tot_aantal", $pager->get('tot_aantal')); ?></td>
              </tr>
              <tr>
                <th>Status:</th>
                <td><?php echo select_tag(BatchTaakPeer::STATUS, options_for_select(BatchTaakPeer::getOptionsForSelect(), $pager->get(BatchTaakPeer::STATUS), array('include_blank' => true))); ?></td>
              </tr>
              </tbody>
            </table>
          </td>
          <td>
            <table class="formtable" width='100%'>
              <tbody>
              <tr>
                <th>Verzenden&nbsp;vanaf:</th>
                <td>
                  Van <?php echo input_date_tag("van_datum", $pager->get('van_datum') ? myDateTools::cultureDateToPropelDate($pager->get('van_datum')) : null, array('rich' => true, 'format' => 'dd/MM/yyyy', "calendar_button_img" => "calendar.gif")); ?>
                  tot <?php echo input_date_tag("tot_datum", $pager->get('tot_datum') ? myDateTools::cultureDateToPropelDate($pager->get('tot_datum')) : null, array('rich' => true, 'format' => 'dd/MM/yyyy', "calendar_button_img" => "calendar.gif")); ?></td>
              </tr>
              </tbody>
            </table>
          </td>
        </tr>
      </table>
    </div>


    <?php echo input_hidden_tag("reset", 1); ?>
    <br/>
    <?php echo submit_tag('Zoeken') ?>
    <?php echo button_to_function('Filter wissen', 'formulierWissen();'); ?>

    <script type="text/javascript">
      function formulierWissen() {
        jQuery("#zoekform :input[type=text]").val("");
      }
    </script>

  </div>


  </form>


<h2 class="pageblock">Batchtaken</h2>
<div class="pageblock">
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
              array('name' => BatchTaakPeer::ID, 'text' => 'Nr', 'align' => 'right', 'sortable' => true, 'width' => 60),
              array('name' => BatchTaakPeer::BRIEF_TEMPLATE_ID, 'text' => 'Brieftemplate', 'align' => 'left', 'sortable' => true),
              array('name' => BatchTaakPeer::AANTAL, 'text' => 'Aantal', 'align' => 'right', 'sortable' => true, 'width' => 80),
              array('name' => BatchTaakPeer::STATUS, 'text' => 'Status', 'align' => 'left', 'sortable' => true),
              array('name' => BatchTaakPeer::VERZENDEN_VANAF, 'text' => 'Verzenden vanaf', 'align' => 'left', 'sortable' => true),
              array('name' => 'acties', 'text' => 'Acties', 'align' => 'center', 'width' => 75),
          ),
          array(
            "sortfield" => $pager->getOrderBy(),
            "sortorder" => $pager->getOrderAsc() ? "ASC" : "DESC",
            "sorturi" => 'ttCommunicatieBatch/list',
            "sorttarget" => 'zoekresultaten',
          )
  );

  foreach($pager->getResults() as $batchtaak)
  {
    $actielink = $batchtaak->getStatus() == BatchTaakPeer::STATUS_PAUZE ? link_to(image_tag("icons/play.16.png"), 'ttCommunicatieBatch/play?id='.$batchtaak->getId(), array('title'=>'Start', 'confirm'=>'Bent u zeker dat u deze batchtaak wilt starten?')) : link_to(image_tag("icons/play.16.png"), 'ttCommunicatieBatch/pause?id='.$batchtaak->getId(), array('title'=>'Pauze', 'confirm'=>'Bent u zeker dat u deze batchtaak wilt pauzeren?'));
    $table->addRow(array(
      array('content' => link_to($batchtaak->getId(), 'ttCommunicatieBatch/show?id=' . $batchtaak->getId())),
      array('content' => $batchtaak->getBriefTemplate()->getNaam()),
      array('content' => $batchtaak->getAantal()),
      array('content' => $batchtaak->getStatus()),
      array("content" => $batchtaak->getVerzendenVanaf('d/m/Y H:i')),
      array("content" => link_to(image_tag("icons/document_zoom_16.gif"), "ttCommunicatieBatch/show?id=" . $batchtaak->getId(), 'title=Bekijk').'&nbsp;'.$actielink)
    ));
  }

  echo $table;
?>
<?php echo pager_navigation($pager, "organisatie/list", 'zoekresultaten') ?>
<?php if (!$sf_request->isXmlHttpRequest()): ?>
  </div> <!-- /#zoekresultaten -->
</div> <!-- /pageblock -->

<?php endif ?>