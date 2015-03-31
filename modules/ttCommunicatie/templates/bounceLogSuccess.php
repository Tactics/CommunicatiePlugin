<?php if (!$sf_request->isXmlHttpRequest()) : ?>
<ul id="breadcrumb" class="breadcrumb">
  <li><?php echo link_to("Home", "@homepage") ?></li>
  <li>Communicatie Log</li>
</ul>

<h2 class="pageblock">Zoeken</h2>
<div class="pageblock">

    <?php
    echo tt_form_remote_tag(array(
      'update'   => 'zoekresultaten',
      'url'      => 'ttCommunicatie/bounceLog',
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
        <th>Verzonden door:</th>
        <td><?php echo select_tag(BriefVerzondenPeer::CREATED_BY, options_for_select(array('' => 'Iedereen', $sf_user->getAccountId() => 'Mezelf'),$pager->get(BriefVerzondenPeer::CREATED_BY))); ?></td>
      </tr>
      <tr>
        <th>Verzonden op:</th>
        <td>Van <?php echo input_date_tag(BriefVerzondenPeer::CREATED_AT . '_van', $pager->get(BriefVerzondenPeer::CREATED_AT . '_van'), array('rich' => true, 'format' => 'dd/MM/yyyy', "calendar_button_img" => "calendar.gif")); ?>
          tot <?php echo input_date_tag(BriefVerzondenPeer::CREATED_AT . '_tot', $pager->get(BriefVerzondenPeer::CREATED_AT . '_tot'), array('rich' => true, 'format' => 'dd/MM/yyyy', "calendar_button_img" => "calendar.gif")); ?></td>
      </tr>
      <tbody>
    </table>

  <?php echo input_hidden_tag("reset", 1); ?>
  <br/>
  <?php echo submit_tag('Zoeken') ?>
  <?php echo button_to_function('Filter wissen', 'wisFormulier();'); ?>
  </form>
</div>


<h2 class="pageblock">Resultaten</h2>
<div id="eventlist" class="pageblock">
  <div id="zoekresultaten">
    <?php endif; // endif !ajaxrequest?>
    <div class="filter">
      &nbsp;<?php echo $pager->getNbResults() ?> resultaten gevonden, <strong><?php echo $pager->getFirstIndice() ?>-<?php echo $pager->getLastIndice() ?></strong> worden weergegeven.
    </div>

    <?php

    $table = new myTable(
      array(
        array("name" => BriefVerzondenPeer::MEDIUM, "text" => "Medium", 'sortable' => true),
        array("name" => BriefVerzondenPeer::ADRES, "text" => "Adres", 'sortable' => true),
        array("name" => BriefVerzondenPeer::CREATED_AT, "text" => "Tijdstip", 'sortable' => true),
        array("name" => BriefVerzondenPeer::ONDERWERP, "text" => "Onderwerp", 'sortable' => true),
        array("name" => "acties", "text" => "Acties", "align" => "center", "width" => "40")
      ), array(
        'sorturi' => "ttCommunicatie/bounceLog",
        'sorttarget' => 'zoekresultaten',
        'sortfield'  => $pager->getOrderBy(),
        'sortorder'  => $pager->getOrderAsc() ? "ASC" : "DESC",
      )
    );


    foreach($pager->getResults() as $briefVerzonden)
    {
      $table->addRow(
        array(
          $briefVerzonden->getMedium() ? $briefVerzonden->getMedium() : ' - ',
          $briefVerzonden->getAdres() ? $briefVerzonden->getAdres() : ' - ',
          format_date($briefVerzonden->getCreatedAt(), 'f'),
          $briefVerzonden->getOnderwerp(),
          link_to_function(image_tag('/ttCommunicatie/images/icons/zoom_16.gif', array('title' => 'communicatie bekijken')), 'showDetail(' . $briefVerzonden->getId() . ');') . '&nbsp;' .
          ($briefVerzonden->getMedium() == BriefVerzondenPeer::MEDIUM_MAIL && $briefVerzonden->getCustom() == false ?
            link_to_function(image_tag('/ttCommunicatie/images/icons/mail_16.gif', array('title' => 'Mail herzenden')), "herzendEmail({$briefVerzonden->getId()});") :
            ''
          )
        )
      );
    }

    echo $table;
    ?>

    <?php echo pager_navigation($pager, 'ttCommunicatie/bounceLog', 'eventlist') ?>

    <?php if (!$sf_request->isXmlHttpRequest()) : ?>
  </div> <!-- end zoekresultaten -->
  <br />

</div>

  <div id="log_event_detail"></div>

  <script type="text/javascript">
    function wisFormulier(form)
    {
      jQuery(':input', form).each(function()
      {
        var type = this.type;
        var tag = this.tagName.toLowerCase(); // normalize case
        // it's ok to reset the value attr of text inputs,
        // password inputs, and textareas
        if (type == 'text' || type == 'password' || tag == 'textarea')
          this.value = "";
        // checkboxes and radios need to have their checked state cleared
        // but should *not* have their 'value' changed
        else if (type == 'checkbox' || type == 'radio')
          this.checked = false;
        // select elements need to have their 'selectedIndex' property set to -1
        // (this works for both single and multiple select elements)
        // EDIT Gert : Da's niet waar -1 is ok voor multiple, voor andere willen
        // we het eerste item geselecteerd hebben.  Geen selectie kan namelijk
        // niet voor single selects.
        else if (tag == 'select')
        {
          this.selectedIndex = this.multiple ? -1 : 0;
        }

        // clear id field van autocompletes
        if (jQuery(this).hasClass('ac_input'))
        {
          var name = jQuery(this).attr('name');
          var id = name.substring(0, name.length - 5);
          jQuery('#' + id).val('');
        }
      });
    }
    
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