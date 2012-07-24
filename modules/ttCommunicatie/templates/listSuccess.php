<?php if (! $sf_request->isXmlHttpRequest()) : ?>

<?php include_partial('breadcrumb', array('identifier' => 'Briefsjablonen')) ?>

<h2 class="pageblock">Filter</h2>

<?php echo form_remote_tag(array(
    'update'   => 'zoekresultaten',
    'url'      => 'ttCommunicatie/list',
    'script'   => true,
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
			  <th style="width: 125px;">Naam:</th>
			  <td><?php echo input_tag(BriefTemplatePeer::NAAM, $pager->get(BriefTemplatePeer::NAAM), array('style' => 'width: 180px;')); ?></td>
			</tr>
      <tr>
        <th>Onderwerp:</th>
        <td><?php echo input_tag(BriefTemplatePeer::ONDERWERP, $pager->get(BriefTemplatePeer::ONDERWERP), array('style' => 'width: 180px;')); ?></td>
      </tr>
    </tbody>
    </table>
  </td>
  <td>
    <table class="formtable" width='100%'>
		<tbody>
			<tr>
			  <th style="width: 125px;">Layout:</th>
        <td><?php echo input_tag(BriefLayoutPeer::NAAM, $pager->get(BriefLayoutPeer::NAAM), array('style' => 'width: 180px;')); ?></td>
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
    function formulierWissen()
    {
      jQuery("#zoekform :input[type=text]").val(""); 
    } 
  </script>

</div>

  
</form>


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
                            array("name" => BriefTemplatePeer::BESTEMMELING_CLASSES, "text" => "Bestemmeling(en)", "sortable" => false, 'width' => 120),
                            array("name" => BriefTemplatePeer::BRIEF_LAYOUT_ID, "text" => "Layout", "sortable" => true, 'width' => 120),
                            array("name" => BriefTemplatePeer::EENMALIG_VERSTUREN, "text" => "Versturen", "sortable" => true, 'width' => 60 ),
                            array("name" => BriefTemplatePeer::SYSTEEMNAAM, "text" => "Systeemnaam", 'width' => 30, 'align' => 'center'),
                            array("text" => "Acties", "width" => 60, "align" => "center"	),
                          ),
                          array(
                            "sortfield"  => $pager->getOrderBy(),
                            "sortorder"  => $pager->getOrderAsc() ? "ASC" : "DESC",
                            "sorturi"    => 'ttCommunicatie/list',
                            "sorttarget" => 'zoekresultaten',
                          )
                        );

      $results = $pager->getResults();

      foreach($results as $briefTemplate)
      {
        $table->addRow(array(
          link_to($briefTemplate->getId(),  "ttCommunicatie/edit?template_id=" . $briefTemplate->getId()),
          link_to_unless_empty($briefTemplate->getNaam(), "ttCommunicatie/edit?template_id=" . $briefTemplate->getId()),
          link_to_unless_empty($briefTemplate->getOnderwerp(), "ttCommunicatie/edit?template_id=" . $briefTemplate->getId()),
          trim($briefTemplate->getBestemmelingClasses(), '|'),
          $briefTemplate->getBriefLayoutId() ? $briefTemplate->getBriefLayout()->getNaam() : '-',
          $briefTemplate->getEenmaligVersturen() ? 'eenmaal' : 'meermaals',
          $briefTemplate->getSysteemnaam() ? 'Ja' : 'Nee',
          array("content" =>
              //link_to(image_tag('icons/zoom_16.gif'), 'ttCommunicatie/show?id=' . $briefTemplate->getId(), array('title' => 'Bekijken')) .
              link_to(image_tag("icons/document_write_16.gif", array('title' => 'Bewerken')), "ttCommunicatie/edit?template_id=" . $briefTemplate->getId()).
              link_to(image_tag("/ttCommunicatie/images/icons/copy_16.gif", array('title' => 'Kopiëren')), "ttCommunicatie/copy?template_id=" . $briefTemplate->getId()).
              ($briefTemplate->isVerwijderbaar() ? 
                link_to(
                  image_tag('icons/trash_16.gif', array('title' => 'Verwijderen')), 
                  'ttCommunicatie/delete?template_id=' . $briefTemplate->getId(),
                  array(
                    'confirm' => 'Bent u zeker dat u dit sjabloon wenst te verwijderen?'
                  )) :
                image_tag('icons/trash_disabled_16.gif', array('title' => 'Verwijderen'))
              )
          )
        ));
      }


      echo $table;
      ?>
  <div style="text-align:center">
    <?php echo pager_navigation($pager, 'ttCommunicatie/list', "zoekresultaten") ?>
  </div>
<?php if (! $sf_request->isXmlHttpRequest()) : ?>
  </div>
  <hr />
  <?php echo button_to("Nieuw sjabloon", 'ttCommunicatie/create'); ?>
</div><!-- /.pageblock -->
<?php endif; ?>