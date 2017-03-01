<?php if (!$sf_request->isXmlHttpRequest()) : ?>

  <?php include_partial('breadcrumb', array('identifier' => 'Brievenlog')) ?>

    <h2 class="pageblock">Filter</h2>

  <?php echo tt_form_remote_tag(array(
    'update' => 'zoekresultaten',
    'url' => 'ttCommunicatie/listBriefVerzonden',
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
                    <td colspan="2">Overzicht van alle communicatie die verstuurd of afgedrukt is met vermelding van de objectclass naar waar deze is verstuurd en welk object (bv. Persoon / 5000), dwz dat de brief is verzonden naar persoon met nummer 5000. Bij objecten waar
                        de nummer niet raadpleegbaar is, zal een referentie waarde worden gebruikt bv. dossiernummer.
                    </td>
                </tr>
                <tr>
                    <td style="width: 50%">
                        <table class="formtable" width='100%'>
                            <tbody>
                            <tr>
                                <th style="width: 125px;">Object:</th>
                                <td><?php echo select_tag(BriefVerzondenPeer::OBJECT_CLASS, options_for_select($mailClasses, $pager->get(BriefVerzondenPeer::OBJECT_CLASS), 'include_blank=true')); ?></td>
                            </tr>
                            <tr>
                                <th>Verzonden vanaf:</th>
                                <td><?php echo input_date_tag('verstuurd_vanaf', myDateTools::cultureDateToPropelDate($pager->get('verstuurd_vanaf')), array('rich' => true, "calendar_button_img" => "calendar.gif", 'class' => 'mask_date', 'format' => 'dd/MM/yyyy' )); ?></td>
                            </tr>
                            <tr>
                                <th>Verzonden tot:</th>
                                <td><?php echo input_date_tag('verstuurd_tot', $totDatum, array('rich' => true, "calendar_button_img" => "calendar.gif", 'class' => 'mask_date', 'format' => 'dd/MM/yyyy' )); ?></td>
                            </tr>
                            </tbody>
                        </table>
                    </td>
                    <td style="width: 50%">
                        <table class="formtable" width='100%'>
                            <tbody>
                            <tr>
                                <th style="width: 125px;">Status:</th>
                                <td><?php echo select_tag(BriefVerzondenPeer::STATUS, options_for_select(BriefVerzondenPeer::getStatussen(), $pager->get(BriefVerzondenPeer::STATUS), 'include_blank=true')); ?></td>
                            </tr>
                            <tr>
                                <th>Template:</th>
                                <td><?php
                                  $c = new Criteria();
                                  $c->addAscendingOrderByColumn(BriefTemplatePeer::NAAM);
                                  $c->add(BriefTemplatePeer::GEARCHIVEERD, false);
                                  echo select_tag(BriefVerzondenPeer::BRIEF_TEMPLATE_ID, objects_for_select(BriefTemplatePeer::doSelect($c), 'getId', 'getNaam', $pager->get(BriefVerzondenPeer::BRIEF_TEMPLATE_ID), 'include_blank=true'));?></td>
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


    <h2 class="pageblock">Briefsjablonen</h2>
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
    array("name" => BriefVerzondenPeer::BRIEF_TEMPLATE_ID, "text" => "Template", "sortable" => true),
    array("name" => BriefVerzondenPeer::OBJECT_CLASS, "text" => "Object class", "sortable" => true),
    array("name" => BriefVerzondenPeer::OBJECT_ID, "text" => "Object id", "sortable" => true, 'width' => 70),
    array("name" => BriefVerzondenPeer::MEDIUM, "text" => "Medium", "sortable" => true, 'width' => 50),
    array("name" => BriefVerzondenPeer::STATUS, "text" => "Status", "sortable" => true, 'width' => 50),
    array("name" => BriefVerzondenPeer::CREATED_AT, "text" => "Verzonden op", "sortable" => true, 'width' => 100),
  ),
  array(
    "sortfield" => $pager->getOrderBy(),
    "sortorder" => $pager->getOrderAsc() ? "ASC" : "DESC",
    "sorturi" => 'ttCommunicatie/listBriefVerzonden',
    "sorttarget" => 'zoekresultaten',
  )
);

$results = $pager->getResults();

/** @var BriefVerzonden $briefVerzonden */
foreach ($results as $briefVerzonden) {

  $template = BriefTemplatePeer::retrieveCachedByPK($briefVerzonden->getBriefTemplateId());

  $object = eval('return ' . $briefVerzonden->getObjectClass() . 'Peer::retrieveByPK(' . $briefVerzonden->getObjectId() . ');');
  $refObjectId = $refObjectClass = '';
  if($object)
  {
      $refObjectId = $object->getId();
      $refObjectClass = get_class($object);
  }
  if ($object && method_exists($object, 'getMailerRefObjectId'))  $refObjectId = $object->getMailerRefObjectId();
  if ($object && method_exists($object, 'getMailerRefObjectClass'))  $refObjectClass = $object->getMailerRefObjectClass();

  $table->addRow(array(
    $template->__toString(),
    $refObjectClass,
    $refObjectId,
    $briefVerzonden->getMedium(),
    $briefVerzonden->getStatus(),
    $briefVerzonden->getCreatedAt('d/m/Y'),
  ));
}


echo $table;
?>
    <div style="text-align:center">
      <?php echo pager_navigation($pager, 'ttCommunicatie/listBriefVerzonden', "zoekresultaten") ?>
    </div>
<?php if (!$sf_request->isXmlHttpRequest()) : ?>
    </div>
    </div><!-- /.pageblock -->
    <?php echo button_to('Exporteren', 'ttCommunicatie/exportBriefVerzonden');?>
<?php endif; ?>


