<div class="pageblock">
  <header>
    <h2 class="pageblock">
      <?php echo link_to_function(image_tag('icons/close_16.gif'), "jQuery('div#log_event_detail').html('')"); ?>
      Verzonden brief #<?php echo sprintf('%010u', $briefVerzonden->getId()); ?>
    </h2>
  </header>
  <table style="width:100%;">
    <tr>
      <td style="width:50%;">
        <table class="objectdetails">
          <tr>
            <th><label class="label">Datum en tijd:</label></th>
            <td><label class="label"><?php echo format_date($briefVerzonden->getCreatedAt(), 'F'); ?></label></td>
          </tr>
          <tr>
            <th><label class="label">Medium:</label></th>
            <td><label class="label"><?php echo $briefVerzonden->getMedium() ? $briefVerzonden->getMedium() : ' - ' ?></label></td>
          </tr>
          <tr>
            <th><label class="label">Adres:</label></th>
            <td><label class="label"><?php echo $briefVerzonden->getAdres(); ?></label></td>
          </tr>
          <?php if ($briefVerzonden->getCc()) : ?>
            <tr>
              <th><label class="label">Cc:</label></th>
              <td><label class="label"><?php echo $briefVerzonden->getCc(); ?></label></td>
            </tr>
          <?php endif; ?>
          <?php if ($briefVerzonden->getBcc()) : ?>
            <tr>
              <th><label class="label">Bcc:</label></th>
              <td><label class="label"><?php echo $briefVerzonden->getBcc(); ?></label></td>
            </tr>
          <?php endif; ?>
          <tr>
            <th><label class="label">Body:</label></th>
            <td><label class="label"><?php echo $briefVerzonden->getHtml() ?></label></td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
</div>
