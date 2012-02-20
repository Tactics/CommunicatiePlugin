<style>
  table.objectdetails th
  {
    width: 100px;
  }

  table.grid tbody td > div {
      height: 17px;
      line-height: 17px;
      overflow: hidden;
  }
</style>

<h2 class="pageblock">Verzonden brief #<?php echo sprintf('%010u', $briefVerzonden->getId()); ?></h2>
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
  <tr>
    <th>Body:</th>
    <td><?php echo nl2br($briefVerzonden->getHtml()) ?></td>
  </tr>
</table>

</td>
</tr>
</table>

</div>
