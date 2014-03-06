<h2 class="pageblock">Logging</h2>
<div class="pageblock" style="height:100px;overflow:scroll;">
  <?php
  foreach ($logs as $log)
  {
    echo $log;
  }
  ?>
</div>

<h2 class="pageblock">Resultaat</h2>
<div class="pageblock">
  Einde verzendlijst<br/><br/>
  Totaal:<br/>
  Reeds verstuurd: <?php echo $counter['reedsverstuurd']; ?><br/>
  Niet toegestaan: <?php echo $counter['niettoegestaan']; ?><br/>
  Verstuurd: <?php echo $counter['verstuurd']; ?><br />
  Error: <?php echo $counter['error']; ?><br />
  Wensen geen mail: <?php echo $counter['wenstgeenmail']; ?><br />

  <?php echo form_tag('ttCommunicatie/print', array('name' => 'print', 'target' => '_blank'));?>
    <?php echo input_hidden_tag('hash', $md5hash); ?>
    <?php echo input_hidden_tag('template_id', $template_id); ?>
    <div style="display:none;">
      <?php
        foreach ($nietVerstuurden as $index => $nt)
        {
          $i = $nt['index'];
          echo checkbox_tag("bestemmelingen[".$nt['bestemmeling']->getObject()->getId()."][$i]", $i, true, array('class' => 'bestemmeling', 'name' => "bestemmelingen[".$nt['bestemmeling']->getObject()->getId()."][]"));
          echo '&nbsp;' . $nt['bestemmeling']->getNaam() . '<br />';
        }
      ?>
    </div>

  <?php echo submit_tag('Niet verstuurde brieven afdrukken', array('name'=>'commit', 'data-type' => 'brieven'));?>
  </form>
</div>