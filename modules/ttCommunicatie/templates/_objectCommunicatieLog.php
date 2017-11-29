<?php if (!$sf_request->isXmlHttpRequest()) : ?>

<?php if ($metFilter) : ?>
  <h2 class="pageblock">Zoeken</h2>
  <div class="pageblock">

    <div class="smaller">
    <?php 
    echo tt_form_remote_tag(array(
      'update'   => 'zoekresultaten',
      'url'      => 'ttCommunicatie/objectCommunicatieLog?object_class=' . get_class($object) . '&object_id=' . $object->getId() . '&type=' . $type,
      'script'   => true,
    ), array(
      'id' => 'zoekform'
    )); 
    ?>

    <table class="formtable" width='100%'>
      <tbody>
        <tr>
          <th>Onderwerp:</th>
          <td><?php echo input_tag(BriefVerzondenPeer::ONDERWERP, $pager->get(BriefVerzondenPeer::ONDERWERP)); ?></td>
        </tr>
        <tr>
          <th>Tekst:</th>
          <td><?php echo input_tag(BriefVerzondenPeer::HTML, $pager->get(BriefVerzondenPeer::HTML)); ?></td>
        </tr>
      <tbody>
    </table>
    </div>

    <?php echo input_hidden_tag("reset", 1); ?>
    <br/>
    <?php echo submit_tag('Zoeken') ?>	  
    <?php echo button_to_function('Filter wissen', 'wisFormulier();'); ?>  
    </form>
  </div>


<h2 class="pageblock">Resultaten</h2>
<div id="eventlist" class="pageblock">
<?php endif; // endif met filter?>
  <div id="zoekresultaten">
<?php endif; // endif !ajaxrequest?>
  <div class="filter">
    &nbsp;<?php echo $pager->getNbResults() ?> resultaten gevonden, <strong><?php echo $pager->getFirstIndice() ?>-<?php echo $pager->getLastIndice() ?></strong> worden weergegeven.
  </div>

  <?php

    $table = new myTable(
                        array(
                          array("name" => "medium", "text" => "Medium", 'sortable' => true),
                          array("name" => "adres", "text" => "Adres", 'sortable' => true),
                          array("name" => "created_at", "text" => "Tijdstip", 'sortable' => true),
                          array("name" => "onderwerp", "text" => "Onderwerp", 'sortable' => true),
                          array("name" => "acties", "text" => "Acties", "align" => "center", "width" => "40")
                        ), array(
                            'sorturi' => "ttCommunicatie/objectCommunicatieLog?object_class=" . get_class($object) . '&object_id=' . $object->getId() . '&type=' . $type,
                            'sorttarget' => 'zoekresultaten',
                            'sortfield'  => $pager->getOrderBy(),
                            'sortorder'  => $pager->getOrderAsc() ? "ASC" : "DESC",
                        )
                      );

    /** @var BriefVerzonden $briefVerzonden */
  foreach($pager->getResults() as $briefVerzonden)
    {
      $actions = array(
        'bekijken' => link_to_function(image_tag('/ttCommunicatie/images/icons/zoom_16.gif', array('title' => 'communicatie bekijken')), 'showDetail(' . $briefVerzonden->getId() . ');'),
      );
      if ($briefVerzonden->getMedium() == BriefVerzondenPeer::MEDIUM_MAIL && $briefVerzonden->getCustom() == false) {
        $actions['herzenden'] = $briefVerzonden->isWeergaveBeveiligd()
          ? image_tag('/ttCommunicatie/images/icons/email.verzenden.disabled.png', array('style' => 'height: 16px; width: 16px', 'title' => 'Mail met beveiligde inhoud kan niet worden herzonden'))
          : link_to_function(image_tag('/ttCommunicatie/images/icons/email.verzenden.png', array('style' => 'height: 16px; width: 16px', 'title' => 'Mail herzenden')), "herzendEmail({$briefVerzonden->getId()});")
        ;
      }
      $table->addRow(
        array(
          $briefVerzonden->getMedium() ? $briefVerzonden->getMedium() : ' - ',
          $briefVerzonden->getAdres() ? $briefVerzonden->getAdres() : ' - ',
          format_date($briefVerzonden->getCreatedAt(), 'f'),
          $briefVerzonden->getOnderwerp(),
          implode('&nbsp;', $actions),
        )
      );
    }

    echo $table;
  ?>

  <?php echo pager_navigation($pager, 'ttCommunicatie/objectCommunicatieLog?object_class=' . get_class($object) . '&object_id=' . $object->getId() . '&type=' . $type, 'eventlist') ?>

<?php if (!$sf_request->isXmlHttpRequest()) : ?>
    </div> <!-- end zoekresultaten -->
  <br />
    
<?php if ($metFilter) : ?>
</div>
<?php endif; // endif metFilter ?>

<div id="log_event_detail"></div>

<script type="text/javascript">
  function showDetail(brief_verzonden_id)
  {
    jQuery.ajax({
      url: '<?php echo url_for('ttCommunicatie/showCommunicatieLog?view=compact&id=9999') ?>'.replace(9999, brief_verzonden_id),
      success: function(html) {
        jQuery('div#log_event_detail').html(html);
      }
    });
  }

  function herzendEmail(brief_verzonden_id)
  { 
    if (confirm('Bent u zeker dat u deze e-mail wil herzenden?'))
    {
      jQuery.ajax({
        url: '<?php echo url_for('ttCommunicatie/herzendEmail?id=9999') ?>'.replace(9999, brief_verzonden_id),
        success: function(msg) {
          alert(msg);
        }
      });
    }
  }
</script>
<?php endif; ?>