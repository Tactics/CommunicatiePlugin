<?php if (!$sf_request->isXmlHttpRequest()) : ?>

<section id="widget-grid">
  <div class="row">
<?php if ($metFilter) : ?>
    <article class="col-sm-12 col-md-12 col-lg-12 sortable-grid ui-sortable">
      <div class="jarviswidget jarviswidget-sortable" id="wid-id-0" data-widget-colorbutton="false" data-widget-editbutton="false" data-widget-custombutton="false" role="widget">
        <header role="heading">
          <div class="jarviswidget-ctrls" role="menu">
            <a href="javascript:void(0);" class="button-icon jarviswidget-toggle-btn" rel="tooltip" title="" data-placement="bottom" data-original-title="Collapse">
              <i class="fa fa-minus"></i>
            </a>
          </div>
          <h2>Zoeken</h2>
          <span class="jarviswidget-loader">
              <i class="fa fa-refresh fa-spin"></i>
            </span>
        </header>
<!--    <div class="smaller">-->
        <div role="content">
          <div class="widget-body no-padding">
            <?php
            echo tt_form_remote_tag(array(
              'update'   => 'zoekresultaten',
              'url'      => 'ttCommunicatie/objectCommunicatieLog?object_class=' . get_class($object) . '&object_id=' . $object->getId() . '&type=' . $type,
              'script'   => true,
            ), array(
              'id' => 'zoekform',
              'class' => 'smart-form'
            ));
            ?>
            <fieldset>
              <div class="row">
                <section class="col col-6">
                  <label class="label">Onderwerp:</label>
                  <label class="input"><?php echo input_tag(BriefVerzondenPeer::ONDERWERP, $pager->get(BriefVerzondenPeer::ONDERWERP)); ?></label>
                </section>
                <section class="col col-6">
                  <label class="label">Tekst:</label>
                  <label class="input"><?php echo input_tag(BriefVerzondenPeer::HTML, $pager->get(BriefVerzondenPeer::HTML)); ?></label>
                </section>
              </div>
            </fieldset>
            <footer>
              <?php echo input_hidden_tag("reset", 1); ?>
              <?php echo submit_tag('Zoeken', array('class' => 'btn btn-primary')) ?>
              <?php echo button_to_function('Filter wissen', 'wisFormulier();', array('class' => 'btn btn-default')); ?>
            </footer>
            </form>
          </div>
        </div>
      </div>
    </article>

    <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12 sortable-grid ui-sortable">
      <div class="jarviswidget jarviswidget-sortable" id="wid-id-1" data-widget-colorbutton="false" data-widget-editbutton="false" data-widget-custombutton="false" role="widget">
        <header role="heading">
          <div class="jarviswidget-ctrls" role="menu">
            <a href="javascript:void(1);" class="button-icon jarviswidget-toggle-btn" rel="tooltip" title="" data-placement="bottom" data-original-title="Collapse">
              <i class="fa fa-minus"></i>
            </a>
          </div>
          <h2>Resultaten</h2>
          <span class="jarviswidget-loader">
            <i class="fa fa-refresh fa-spin"></i>
          </span>
        </header>
        <div role="content">
          <div id="zoekresultaten">
<?php endif; // endif met filter?>
<?php endif; // endif !ajaxrequest?>
            <div class="widget-body no-padding">
              <div id="dt_basic_wrapper" class="dataTables_wrapper form-inline no-footer">
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
                <div class="dt-toolbar-footer">
                  <div class="col-sm-6 col-xs-12 hidden-xs">
                    <div class="dataTables_info" id="dt_basic_info" role="status" aria-live="polite">
                      &nbsp;<span class="text-primary" style="font-weight:bold"><?php echo $pager->getNbResults() ?></span> resultaten gevonden, <span class="txt-color-darken" style="font-weight:bold"><?php echo $pager->getLastIndice() ? $pager->getFirstIndice() : 0 ?></span>-<span class="txt-color-darken" style="font-weight:bold"><?php echo $pager->getLastIndice() ?></span> worden weergegeven.
                    </div>
                  </div>
                  <div class="col-xs-12 col-sm-6">
                    <div class="dataTables_paginate paging_simple_numbers" style="text-align:center">
                      <?php echo pager_navigation($pager, 'ttCommunicatie/objectCommunicatieLog?object_class=' . get_class($object) . '&object_id=' . $object->getId() . '&type=' . $type, 'eventlist', sfConfig::get('sf_style_smartadmin')) ?>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
  <?php if (! $sf_request->isXmlHttpRequest()) : ?>
        </div>
      </div>
    </article>
  </div>
</section>

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
        success: function(html) {
          jQuery('div#content').prepend(html);
        }
      });
    }
  }
</script>
<?php endif; ?>