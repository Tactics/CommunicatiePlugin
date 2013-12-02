<h2 class="pageblock">
  <div style="float:right; padding-right: 3px; margin-top: -3px;">
    <?php echo link_to_function(image_tag('icons/close_16.gif'), "jQuery('div#details').html('')"); ?>
  </div>
  Details
</h2>
<div class="pageblock">
<?php
echo preg_replace("/<style\\b[^>]*>(.*?)<\\/style>/s", "", $briefVerzonden->getHtml());
?>
</div>