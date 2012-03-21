<?php use_helper('Global') ?>
<div id="eventlist">
  <div class="filter">
    &nbsp;<?php echo $pager->getNbResults() ?> resultaten gevonden, <strong><?php echo $pager->getFirstIndice() ?>-<?php echo $pager->getLastIndice() ?></strong> worden weergegeven.
  </div>

  <?php

    $table = new myTable(
                        array(
                          array("name" => "medium", "text" => "Medium"),
                          array("name" => "adres", "text" => "Adres"),
                          array("name" => "created_at", "text" => "Tijdstip"),
                          array("name" => "onderwerp", "text" => "Onderwerp"),
                          array("name" => "acties", "text" => "Acties", "align" => "center", "width" => "40")
                        ), array()
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

  <?php echo pager_navigation($pager,  $object_class . $object_id . 'Communicatielog', 'eventlist') ?>

  <br />
  <div id="log_event_detail">
  </div>

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
</div>
