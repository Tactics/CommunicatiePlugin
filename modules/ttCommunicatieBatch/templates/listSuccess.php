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
              array('name' => BatchTaakPeer::OBJECT_CLASS, 'text' => 'Object', 'align' => 'left', 'sortable' => true),
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
    $cssClass = $batchtaak->getStatus() == BatchTaakPeer::STATUS_VERZENDING ? 'verzending' : '';

    $actieLinks = '';
    if ($batchtaak->getStatus() == BatchTaakPeer::STATUS_PAUZE)
    {
      $actieLinks .= link_to(image_tag("icons/play.16.png"), '@changeStatus?id=' . $batchtaak->getId() . '&status=' . BatchTaakPeer::STATUS_WACHTRIJ, array(
        'title'=>'Start',
        'confirm'=>'Bent u zeker dat u deze batchtaak wilt starten?')
      );
    }
    if ($batchtaak->getStatus() == BatchTaakPeer::STATUS_WACHTRIJ)
    {
      $actieLinks .= link_to(image_tag("icons/pause.16.png"), '@changeStatus?id=' . $batchtaak->getId() . '&status=' . BatchTaakPeer::STATUS_PAUZE, array(
        'title'=>'Pauze',
        'confirm'=>'Bent u zeker dat u deze batchtaak wilt pauzeren?')
      );
    }
    if (!in_array($batchtaak->getStatus(), array(BatchTaakPeer::STATUS_GEANNULEERD, BatchTaakPeer::STATUS_VERZONDEN)))
    {
      $actieLinks .= link_to(image_tag("icons/cancel_16.gif"), '@changeStatus?id=' . $batchtaak->getId() . '&status=' . BatchTaakPeer::STATUS_GEANNULEERD, array(
        'title'=>'Annuleren',
        'confirm'=>'Bent u zeker dat u deze batchtaak wilt annuleren?')
      );
    }
    
    $table->addRow(
      array(
        link_to($batchtaak->getId(), 'ttCommunicatieBatch/show?id=' . $batchtaak->getId()),
        $batchtaak->getObjectClass(),
        $batchtaak->getAantal(),
        $batchtaak->getStatus(),
        $batchtaak->getVerzendenVanaf('d/m/Y H:i'),
        link_to(image_tag("icons/document_zoom_16.gif"), "ttCommunicatieBatch/show?id=" . $batchtaak->getId(), 'title=Bekijk') . '&nbsp;' . $actieLinks
      ),
      array(
        'rowClass' => $cssClass
      )
    );
  }

  echo $table;
?>
<?php echo pager_navigation($pager, "ttCommunicatieBatch/list", 'zoekresultaten') ?>
<?php if (!$sf_request->isXmlHttpRequest()): ?>
  </div> <!-- /#zoekresultaten -->
</div> <!-- /pageblock -->


<script type="text/javascript">
  function refreshList()
  {
    jQuery.get(
      '<?php echo url_for('ttCommunicatieBatch/list'); ?>',      
      function(html){
        jQuery('#zoekresultaten').html(html);
      }
    );
  }

  jQuery(function($){
    setInterval(refreshList, 5000);
  });
</script>

<?php endif ?>