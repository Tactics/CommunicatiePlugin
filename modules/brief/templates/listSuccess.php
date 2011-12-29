<?php if (! $sf_request->isXmlHttpRequest()) : ?>

<?php include_partial('global/breadcrumb', array('identifier' => 'Briefsjablonen')) ?>

<h2 class="pageblock">Briefsjablonen</h2>
<div class="pageblock">
  <div id="zoekresultaten">
 <?php endif; ?>
    <div class="filter">
      &nbsp;<?php echo $pager->getNbResults() ?> resultaten gevonden, <strong><?php echo $pager->getLastIndice() ? $pager->getFirstIndice() : 0 ?>-<?php echo $pager->getLastIndice() ?></strong> worden weergegeven.
    </div>
    <?php
      $table = new myTable(
                          array(
                            array("name" => BriefTemplatePeer::ID, "text" => "Nr", "align" => "right", "width" => 50, "sortable" => true),
                            array("name" => BriefTemplatePeer::NAAM, "text" => "Naam", "sortable" => true ),
                            array("name" => BriefTemplatePeer::ONDERWERP, "text" => "Onderwerp", "sortable" => true ),
                            array("name" => BriefTemplatePeer::BRIEF_LAYOUT_ID, "text" => "Layout", "sortable" => true, 'width' => 120),
                            array("name" => BriefTemplatePeer::EENMALIG_VERSTUREN, "text" => "Versturen", "sortable" => true, 'width' => 60 ),
                            array("name" => BriefTemplatePeer::SYSTEEMNAAM, "text" => "Systeemnaam", 'width' => 30, 'align' => 'center'),
                            array("text" => "Acties", "width" => 30, "align" => "center"	),
                          ),
                          array(
                            "sortfield"  => $pager->getOrderBy(),
                            "sortorder"  => $pager->getOrderAsc() ? "ASC" : "DESC",
                            "sorturi"    => 'brief/list',
                            "sorttarget" => 'zoekresultaten',
                          )
                        );

      $results = $pager->getResults();

      foreach($results as $briefTemplate)
      {
        $table->addRow(array(
          link_to($briefTemplate->getId(),  "brief/edit?template_id=" . $briefTemplate->getId()),
          link_to_unless_empty($briefTemplate->getNaam(), "brief/edit?template_id=" . $briefTemplate->getId()),
          link_to_unless_empty($briefTemplate->getOnderwerp(), "brief/edit?template_id=" . $briefTemplate->getId()),
          $briefTemplate->getBriefLayoutId() ? $briefTemplate->getBriefLayout()->getNaam() : '-',
          $briefTemplate->getEenmaligVersturen() ? 'eenmaal' : 'meermaals',
          $briefTemplate->getSysteemnaam() ? 'Ja' : 'Nee',
          array("content" =>
              //link_to(image_tag('icons/zoom_16.gif'), 'brief/show?id=' . $briefTemplate->getId(), array('title' => 'Bekijken')) .
              link_to(image_tag("icons/document_write_16.gif", array('title' => 'Bewerken')), "brief/edit?template_id=" . $briefTemplate->getId())
          )
        ));
      }


      echo $table;
      ?>
  <div style="text-align:center">
    <?php echo pager_navigation($pager, 'brief/list', "zoekresultaten") ?>
  </div>
<?php if (! $sf_request->isXmlHttpRequest()) : ?>
  </div>
  <hr />
  <?php echo button_to("Nieuw sjabloon", 'brief/create'); ?>
</div><!-- /.pageblock -->
<?php endif; ?>