<?php if (!$sf_request->isXmlHttpRequest()) : ?>

  <?php
  if(! function_exists('__'))
    \Misc::use_helper('I18N');
?>

<?php if ($metFilter) : ?>
  <h2 class="pageblock"><?php echo __('Zoeken');?></h2>
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
          <th><?php echo __('Onderwerp');?>:</th>
          <td><?php echo input_tag(BriefVerzondenPeer::ONDERWERP, $pager->get(BriefVerzondenPeer::ONDERWERP)); ?></td>
        </tr>
        <tr>
          <th><?php echo __('Tekst');?>:</th>
          <td><?php echo input_tag(BriefVerzondenPeer::HTML, $pager->get(BriefVerzondenPeer::HTML)); ?></td>
        </tr>
      <tbody>
    </table>
    </div>

    <?php echo input_hidden_tag("reset", 1); ?>
    <br/>
    <?php echo submit_tag(__('Zoeken')) ?>
    <?php echo button_to_function(__('Filter wissen'), 'wisFormulier();'); ?>
    </form>
  </div>


<h2 class="pageblock"><?php echo __('Resultaten');?></h2>
<div id="eventlist" class="pageblock">
<?php endif; // endif met filter?>
  <div id="zoekresultaten">
<?php endif; // endif !ajaxrequest?>
  <div class="filter">
    &nbsp;<?php echo $pager->getNbResults() . ' ' . __('resultaten gevonden');?>, <strong><?php echo $pager->getFirstIndice() ?>-<?php echo $pager->getLastIndice() ?></strong> <?php echo __('worden weergegeven');?>.
  </div>

  <?php

    $table = new myTable(
                        array(
                          array("name" => "medium", "text" => __("Medium"), 'sortable' => true),
                          array("name" => "adres", "text" => __("Adres"), 'sortable' => true),
                          array("name" => "created_at", "text" => __("Tijdstip"), 'sortable' => true),
                          array("name" => "onderwerp", "text" => __("Onderwerp"), 'sortable' => true),
                          array("name" => "acties", "text" => __("Acties"), "align" => "center", "width" => "40")
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
        'bekijken' => link_to_function(image_tag('/ttCommunicatie/images/icons/zoom_16.gif', array('title' => __('communicatie bekijken'))), 'showDetail(' . $briefVerzonden->getId() . ');'),
      );
      if ($briefVerzonden->getMedium() == BriefVerzondenPeer::MEDIUM_MAIL && $briefVerzonden->getCustom() == false) {
        $actions['herzenden'] = $briefVerzonden->isWeergaveBeveiligd()
          ? image_tag('/ttCommunicatie/images/icons/email.verzenden.disabled.png', array('style' => 'height: 16px; width: 16px', 'title' => __('Mail met beveiligde inhoud kan niet worden herzonden')))
          : link_to_function(image_tag('/ttCommunicatie/images/icons/email.verzenden.png', array('style' => 'height: 16px; width: 16px', 'title' => __('Mail herzenden'))), "herzendEmail({$briefVerzonden->getId()});")
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

  <?php echo pager_navigation($pager, 'ttCommunicatie/objectCommunicatieLog?object_class=' . get_class($object) . '&object_id=' . $object->getId() . '&type=' . $type, 'zoekresultaten') ?>

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
    if (confirm(__('Bent u zeker dat u deze e-mail wil herzenden?')))
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