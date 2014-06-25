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
  Wensen geen publiciteit: <?php echo $counter['wenstgeenpubliciteit']; ?><br />

  <?php echo form_tag('ttCommunicatie/print', array('name' => 'print', 'target' => '_blank'));?>
    <?php echo input_hidden_tag('hash', $md5hash); ?>
    <?php echo input_hidden_tag('template_id', $template_id); ?>
    <div style="display:none;">
      <?php
        foreach ($nietVerstuurden as $index => $nt)
        {
          $i = $nt['index'];
          $id = $nt['bestemmeling']->getObject()->getId();
          echo checkbox_tag("bestemmelingen[".$id."][$i]", $id.'_'.$i, true, array('class' => 'bestemmeling', 'name' => "bestemmelingen[]"));
          echo '&nbsp;' . $nt['bestemmeling']->getNaam() . '<br />';
        }
      ?>
    </div>

  <?php if(count($nietVerstuurden)): ?>
    <?php echo submit_tag('Niet verstuurde brieven afdrukken', array('name'=>'commit', 'data-type' => 'brieven'));?>
  <?php endif; ?>
  <?php echo button_to_function('Sluiten', "jQuery(this).parents('.ttBase-dialog-modal:first').overlay().close();") ?>
  </form>
</div>