<?php slot('breadcrumb');
include_partial('breadcrumb', array('identifier' => 'Briefsjablonen'));
end_slot();
?>
<?php if (! $sf_request->isXmlHttpRequest()) : ?>

  <section id="widget-grid">
    <div class="row">
      <article class="col-sm-12 col-md-12 col-lg-12 sortable-grid ui-sortable">
        <div class="jarviswidget jarviswidget-sortable" id="wid-id-0" data-widget-colorbutton="false" data-widget-editbutton="false" data-widget-custombutton="false" role="widget">
          <header role="heading">
            <div class="jarviswidget-ctrls" role="menu">
              <a href="javascript:void(0);" class="button-icon jarviswidget-toggle-btn" rel="tooltip" title="" data-placement="bottom" data-original-title="Collapse">
                <i class="fa fa-minus"></i>
              </a>
            </div>
            <h2>Filter</h2>
            <span class="jarviswidget-loader">
              <i class="fa fa-refresh fa-spin"></i>
            </span>
          </header>
          <div role="content">
            <div class="widget-body no-padding">
              <?php echo tt_form_remote_tag(array(
                  'update'   => 'zoekresultaten',
                  'url'      => 'ttCommunicatie/list',
                  'script'   => true,
                ),
                array(
                  'id' => 'zoekform',
                  'class' => 'smart-form'
                )
              );
              ?>
              <fieldset>
                <div class="row">
                  <section class="col col-6">
                    <label class="label">Naam:</label>
                    <label class="input">
                      <?php echo input_tag(BriefTemplatePeer::NAAM, $pager->get(BriefTemplatePeer::NAAM), array('style' => 'width:400px', 'class' => 'input-sm')); ?>
                    </label>
                  </section>
                  <section class="col col-6">
                    <label class="label">Onderwerp:</label>
                    <label class="input">
                      <?php echo input_tag(BriefTemplatePeer::ONDERWERP, $pager->get(BriefTemplatePeer::ONDERWERP), array('style' => 'width:400px', 'class' => 'input-sm')); ?>
                    </label>
                  </section>
                  <section class="col col-6">
                    <label class="label">Bestemmeling:</label>
                    <label class="input">
                      <?php echo input_tag(BriefTemplatePeer::BESTEMMELING_CLASSES, $pager->get(BriefTemplatePeer::BESTEMMELING_CLASSES), array('style' => 'width:400px', 'class' => 'input-sm')); ?>
                    </label>
                  </section>
                </div>
              </fieldset>
              <footer>
                <?php echo input_hidden_tag("reset", 1); ?>
                <?php echo submit_tag('Zoeken', array('class' => 'btn btn-primary')) ?>
                <?php echo button_to_function('Filter wissen', 'formulierWissen();', array('class' => 'btn btn-default')); ?>
                <?php echo button_to("Nieuw sjabloon", 'ttCommunicatie/create', array('class' => 'btn btn-primary')); ?>
              </footer>
              </form>
            </div>
          </div>
        </div>
      </article>
  
  <script type="text/javascript">
    function formulierWissen()
    {
      jQuery("#zoekform :input[type=text]").val(""); 
    } 
  </script>
<?php endif; ?>
  <?php
  $table = new myTable(
    array(
      //array("name" => BriefTemplatePeer::ID, "text" => "Nr", "align" => "right", "width" => 50, "sortable" => true),
      array("name" => BriefTemplatePeer::NAAM, "text" => "Naam", "sortable" => true),
      array("name" => BriefTemplatePeer::ONDERWERP, "text" => "Onderwerp", "sortable" => true ),
      array("name" => BriefTemplatePeer::BESTEMMELING_CLASSES, "text" => "Bestemmeling(en)", "sortable" => false, 'width' => 120),
      array("name" => BriefTemplatePeer::BRIEF_LAYOUT_ID, "text" => "Layout", "sortable" => true, 'width' => 120),
      array("name" => BriefTemplatePeer::EENMALIG_VERSTUREN, "text" => "Versturen", "sortable" => true, 'width' => 60 ),
      array("name" => BriefTemplatePeer::SYSTEEMNAAM, "text" => "Systeemnaam", 'width' => 30, 'align' => 'center'),
      array("text" => "Acties", "width" => 85, "align" => "center"	),
    ),
    array(
      "sortfield"  => $pager->getOrderBy(),
      "sortorder"  => $pager->getOrderAsc() ? "ASC" : "DESC",
      "sorturi"    => 'ttCommunicatie/list',
      "sorttarget" => 'zoekresultaten',
      "smartadmin" => sfConfig::get("sf_style_smartadmin")
    )
  );

  $results = $pager->getResults();

  foreach($results as $briefTemplate)
  {
    $options = array();
    if ($briefTemplate->getGearchiveerd()) $options["rowClass"] = "gearchiveerd";

    $table->addRow(array(
      //link_to($briefTemplate->getId(),  "ttCommunicatie/edit?template_id=" . $briefTemplate->getId()),
      link_to_unless_empty($briefTemplate->getNaam(), "ttCommunicatie/edit?template_id=" . $briefTemplate->getId()),
      link_to_unless_empty($briefTemplate->getOnderwerp(), "ttCommunicatie/edit?template_id=" . $briefTemplate->getId()),
      trim($briefTemplate->getBestemmelingClasses(), '|'),
      $briefTemplate->getBriefLayoutId() ? $briefTemplate->getBriefLayout()->getNaam() : '-',
      $briefTemplate->getEenmaligVersturen() ? 'eenmaal' : 'meermaals',
      $briefTemplate->getSysteemnaam() ? 'Ja' : 'Nee',
      array("content" =>
      //link_to(image_tag('icons/zoom_16.gif'), 'ttCommunicatie/show?id=' . $briefTemplate->getId(), array('title' => 'Bekijken')) .
        link_to("<i class='fa fa-pencil' title='Bewerken'></i>", "ttCommunicatie/edit?template_id=" . $briefTemplate->getId()). ' ' .
        link_to_unless($briefTemplate->isSysteemtemplate(),  "<i class='fa fa-copy' title='Kopiëren'></i>", "ttCommunicatie/copy?template_id=" . $briefTemplate->getId(), array('confirm' => 'Bent u zeker dat u een kopie wil maken van dit briefsjabloon?')). ' ' .
        link_to_unless($briefTemplate->isSysteemtemplate(), ($briefTemplate->getGearchiveerd() ? "<i class='fa fa-file-text' title='Dearchiveren'></i>" : "<i class='fa fa-archive' title='Archiveren'></i>"),  'ttCommunicatie/archiveer?id=' . $briefTemplate->getId(), array(
          'confirm' => $briefTemplate->getGearchiveerd() ? "Bent u zeker dat u dit sjabloon uit het archief wenst te halen?" : "Bent u zeker dat u dit sjabloon wenst te archiveren?"
        )) . ' ' .
        ($sf_user->isSuperAdmin() ?
          ($briefTemplate->isVerwijderbaar() ?
            link_to(
              "<i class='fa fa-trash-o' title='Verwijderen'></i>",
              'ttCommunicatie/delete?template_id=' . $briefTemplate->getId(),
              array(
                'confirm' => 'Bent u zeker dat u dit sjabloon wenst te verwijderen?'
              )) :
            "<i class='fa fa-trash-o disabled' ></i>"
          ) : ''
        )
      )
    ), $options);
  }
?>
  <?php if (! $sf_request->isXmlHttpRequest()) : ?>
    <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12 sortable-grid ui-sortable">
      <div class="jarviswidget jarviswidget-sortable" id="wid-id-1" data-widget-colorbutton="false" data-widget-editbutton="false" data-widget-custombutton="false" role="widget">
        <header role="heading">
          <div class="jarviswidget-ctrls" role="menu">
            <a href="javascript:void(1);" class="button-icon jarviswidget-toggle-btn" rel="tooltip" title="" data-placement="bottom" data-original-title="Collapse">
              <i class="fa fa-minus"></i>
            </a>
          </div>
          <h2>Briefsjablonen</h2>
          <span class="jarviswidget-loader">
            <i class="fa fa-refresh fa-spin"></i>
          </span>
        </header>
        <div role="content">
          <div id="zoekresultaten">
<?php endif; ?>
          <div class="widget-body no-padding">
            <div id="dt_basic_wrapper" class="dataTables_wrapper form-inline no-footer">
              <?php echo $table; ?>
              <div class="dt-toolbar-footer">
                <div class="col-sm-6 col-xs-12 hidden-xs">
                  <div class="dataTables_info" id="dt_basic_info" role="status" aria-live="polite">
                    &nbsp;<span class="text-primary" style="font-weight:bold"><?php echo $pager->getNbResults() ?></span> resultaten gevonden, <span class="txt-color-darken" style="font-weight:bold"><?php echo $pager->getLastIndice() ? $pager->getFirstIndice() : 0 ?></span>-<span class="txt-color-darken" style="font-weight:bold"><?php echo $pager->getLastIndice() ?></span> worden weergegeven.
                  </div>
                </div>
                <div class="col-xs-12 col-sm-6">
                  <div class="dataTables_paginate paging_simple_numbers" style="text-align:center">
                    <?php echo pager_navigation($pager, 'ttCommunicatie/list', "zoekresultaten", sfConfig::get("sf_style_smartadmin")) ?>
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
<?php endif; ?>