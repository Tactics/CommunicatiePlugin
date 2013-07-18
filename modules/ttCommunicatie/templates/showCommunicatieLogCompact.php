<h2 class="pageblock">  
  <div style="float:right; padding-right: 3px; margin-top: -3px;">
    <?php echo link_to_function(image_tag('icons/close_16.gif'), "jQuery('div#log_event_detail').html('')"); ?>
  </div>
  Verzonden brief #<?php echo sprintf('%010u', $briefVerzonden->getId()); ?>
</h2>
<div class="pageblock">

<table style="width:100%;"><tr>
<td style="width:50%;">

<table class="objectdetails">
  <tr>
    <th>Datum en tijd:</th>
    <td><?php echo format_date($briefVerzonden->getCreatedAt(), 'F'); ?></td>
  </tr>
  <tr>
    <th>Medium:</th>
    <td><?php echo $briefVerzonden->getMedium() ? $briefVerzonden->getMedium() : ' - ' ?></td>
  </tr>
  <tr>
    <th>Adres:</th>
    <td><?php echo $briefVerzonden->getAdres(); ?></td>
  </tr>
  <?php if ($briefVerzonden->getCc()) : ?>
    <tr>
      <th>Cc:</th>
      <td><?php echo $briefVerzonden->getCc(); ?></td>
    </tr>
  <?php endif; ?>
  <?php if ($briefVerzonden->getBcc()) : ?>
    <tr>
      <th>Bcc:</th>
      <td><?php echo $briefVerzonden->getBcc(); ?></td>
    </tr>
  <?php endif; ?>
  <tr>
    <th>Body:</th>
    <td><?php echo nl2br($briefVerzonden->getHtml()) ?></td>
  </tr>
</table>

</td>
</tr>
</table>

</div>
