<section id="widget-grid">
  <div class="row">
    <article class="col-sm-12 col-md-12 col-lg-12 sortable-grid ui-sortable">
      <div class="jarviswidget jarviswidget-sortable" id="wid-id-0" data-widget-colorbutton="false" data-widget-editbutton="false" data-widget-custombutton="false" role="widget">
        <header role="heading">
          <h2>Logging</h2>
        </header>
        <div role="content">
          <div class="widget-body" style="height:100px;overflow: scroll">
            <?php
            foreach ($logs as $log)
            {
              echo $log;
            }
            ?>
          </div>
        </div>
      </div>
    </article>

    <article class="col-sm-12 col-md-12 col-lg-12 sortable-grid ui-sortable">
      <div class="jarviswidget jarviswidget-sortable" id="wid-id-0" data-widget-colorbutton="false" data-widget-editbutton="false" data-widget-custombutton="false" role="widget">
        <header role="heading">
          <h2>Resultaat</h2>
        </header>
        <div role="content">
          <div class="widget-body">
            Einde verzendlijst<br/><br/>
            Totaal:<br/>
            Reeds verstuurd: <?php echo $counter['reedsverstuurd']; ?><br/>
            Niet toegestaan: <?php echo $counter['niettoegestaan']; ?><br/>
            Verstuurd: <?php echo $counter['verstuurd']; ?><br />
            Error: <?php echo $counter['error']; ?><br />
            Wensen geen mail: <?php echo $counter['wenstgeenmail']; ?><br />
            Wensen geen publiciteit: <?php echo $counter['wenstgeenpubliciteit']; ?><br />

            <?php echo form_tag('ttCommunicatie/print', array('name' => 'print', 'target' => '_blank', 'class' => 'smart-form'));?>
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
            <footer>
              <?php if(count($nietVerstuurden)): ?>
                <?php echo submit_tag('Niet verstuurde brieven afdrukken', array('name'=>'commit', 'data-type' => 'brieven', 'class' => 'btn btn-primary'));?>
              <?php endif; ?>
              <?php echo sfConfig::get('sf_style_smartadmin') ? null : button_to_function('Sluiten', "jQuery(this).parents('.ttBase-dialog-modal:first').overlay().close();", array('class' => 'btn btn-default')) ?>
            </footer>
           </form>
          </div>
        </div>
      </div>
    </article>
  </div>
</section>