<?php
if(! function_exists('__'))
  \Misc::use_helper('I18N');
?>


<h2 class="pageblock">
  <div style="float:right; padding-right: 3px; margin-top: -3px;">
    <?php echo link_to_function(image_tag('icons/close_16.gif'), "jQuery('div#log_event_detail').html('')"); ?>
  </div>
  <?php echo __('Verzonden brief') . ' #' . sprintf('%010u', $briefVerzonden->getId()); ?>
</h2>
<div class="pageblock">

<table style="width:100%;"><tr>
<td style="width:50%;">

<table class="objectdetails">
  <tr>
    <th><?php echo __('Datum en tijd');?>:</th>
    <td><?php echo format_date($briefVerzonden->getCreatedAt(), 'F'); ?></td>
  </tr>
  <tr>
    <th><?php echo __('Medium');?>:</th>
    <td><?php echo $briefVerzonden->getMedium() ? $briefVerzonden->getMedium() : ' - ' ?></td>
  </tr>
  <tr>
    <th><?php echo __('Adres');?>:</th>
    <td><?php echo $briefVerzonden->getAdres(); ?></td>
  </tr>
  <tr>
    <th><?php echo __('Body');?>:</th>
    <td><?php echo nl2br($briefVerzonden->getHtml()) ?></td>
  </tr>
</table>

</td>
</tr>
</table>

</div>
