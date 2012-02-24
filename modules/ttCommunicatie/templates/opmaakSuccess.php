<?php include_partial('breadcrumb'); ?>

<?php echo form_tag('ttCommunicatie/print', array('target' => '_blank', 'multipart' => true)); ?>
  <?php echo input_hidden_tag('hash', $md5hash); ?>  
  <?php if ($brief_template) : ?>
    <?php echo input_hidden_tag('template_id', $brief_template->getId()); ?>
  <?php endif; ?>
  <h2 class="pageblock">Brief opmaken</h2>
  <div class="pageblock">
    <table class="formtable">
      <?php if ($choose_template) : ?>
        <tr>
          <th>Sjabloon:</th>
          <td><?php echo select_tag('template_id', objects_for_select($brief_templates, 'getId', 'getNaam')); ?></td>
        </tr>      
      <?php endif; ?>
      
      <tr>
        <th>Aantal bestemmelingen:</th>
        <td><?php echo $aantalBestemmelingen; ?></td>
      </tr>
      <tr>
        <th>Verzenden via e-mail:</th>
        <td>
          <?php echo select_tag('verzenden_via', options_for_select(array(
              'liefst' => 'Ja, indien gewenst',
              'altijd' => 'Ja, altijd',
              'nee' => 'Nee, alles afdrukkken op papier'
            ), 'ja'), array('onchange' => 'jQuery(this).val() != "nee" ? jQuery(".emailonly").show() : jQuery(".emailonly").hide();'))?>
        </td>
      </tr>
      
      <?php if ($brief_template) : ?>
        <tr>
          <th>Eenmalig verzenden:</th>
          <td id="sjabloon_eenmalig"><?php echo $brief_template->getEenmaligVersturen() ? 'Ja' : 'Nee'; ?></td>
        </tr>
      <?php endif; ?>      
        
      <?php if ($edit_template) : ?>
        <tr>
          <th>&nbsp;</th>
          <td>&nbsp;</td>
        </tr>
        <tr class="required">
          <th>Onderwerp/tekst:</th>
          <td>
            <?php 
            include_partial('brief_text_area_vertaalbaar', array(
              'brief_template'      => $brief_template,                                 
              'systeemplaceholders' => $systeemplaceholders,
              'choose_template'     => $choose_template
            ));
            ?>
          </td>
          <td>
            <h2 class="pageblock" style="margin-left: 20px; width: 300px;">Invoegvelden</h2>
            <div id="placeholders" class="pageblock" style="overflow: auto; height: 500px; width: 295px; margin-left: 20px;">
            <ul>
            <?php
              $placeHolders = eval("return $bestemmelingenClass::getPlaceholders();");
              foreach($placeHolders as $group_id => $placeHolderOfGroup)
              {
                if (is_array($placeHolderOfGroup))
                {
                  echo '<li>' . $group_id . '</li>';
                  echo '<ul>';
                  foreach($placeHolderOfGroup as $group_id => $placeHolderOfGroup)
                  {
                    if (is_array($placeHolderOfGroup))
                    {
                      echo '<li>' . $group_id . '</li>';
                      echo '<ul>';
                      foreach($placeHolderOfGroup as $group_id => $placeHolderOfGroup)
                      {
                        if (is_array($placeHolderOfGroup))
                        {
                          echo '<li>' . $group_id . '</li>';
                          echo '<ul>';

                          echo '</ul>';
                        }
                        else
                        {
                          echo '<li class="placeholder">' . link_to_function($placeHolderOfGroup, 'insertPlaceholder("' . $placeHolderOfGroup . '");') . '</li>';
                        }
                      }
                      echo '</ul>';
                    }
                    else
                    {
                      echo '<li class="placeholder">' . link_to_function($placeHolderOfGroup, 'insertPlaceholder("' . $placeHolderOfGroup . '");') . '</li>';
                    }
                  }
                  echo '</ul>';
                }
                else
                {
                  echo '<li class="placeholder">' . link_to_function($placeHolderOfGroup, 'insertPlaceholder("' . $placeHolderOfGroup . '");') . '</li>';
                }
              }
            ?>
            </ul>
            </div>
          </td>
        </tr>
        <tr>
          <th>&nbsp;</th>
          <td>&nbsp;</td>
        </tr>    
      <?php endif; ?>
        
      <tr>
        <th>Bijlagen:</th>
        <td>
          <?php echo input_file_tag('bijlage0', array('class' => 'bijlagen')) ?> <br />
          <?php echo link_to_function('Bijlage toevoegen', 'bijlageToevoegen()', array('class' => 'bijlage_toevoegen')); ?>
        </td>
      </tr>
    </table>
    
    <hr />
    <?php echo submit_tag('Voorbeeld brief'); // opgelet: de naam van deze knop moet 'voorbeeld' bevatten, hierop wordt getest in de executePrint()' ?>
    <?php echo submit_tag('Voorbeeld e-mail'); // opgelet: de naam van deze knop moet 'voorbeeld' bevatten, hierop wordt getest in de executePrint()' ?>    
    <?php echo submit_tag('Brieven afdrukken'); ?>
    <?php echo submit_tag('E-mails verzenden', array('class' => 'emailonly')); ?>
    
  </div>
</form>

<script type="text/javascript">
  function insertPlaceholder(placeholder)
  {
    tinyMCE.execCommand('mceInsertContent', null, '%' + placeholder + '%');
  }

  jQuery(function($){
    
    // language tabs initialiseren
    $('#tabs').tt_tabs();
    
    tinyMCE.init({        
      mode : "textareas",
      theme : "advanced", 
      theme_advanced_toolbar_location : "top",
      width : "600", 
      height : "454",       
      language : "nl",      
      plugins: "paste, pagebreak",
      pagebreak_separator : "%pagebreak%",
      theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,undo,redo,|,cleanup,|,bullist,numlist,|,justifyleft,justifycenter,justifyright,justifyfull,|,cut,copy,paste,pastetext,pasteword,|,pagebreak",
      theme_advanced_buttons2 : "",
      theme_advanced_buttons3 : ""      
    });
    
    <?php if ($choose_template): ?>
    // Laad template na selectie    
    $('#template_id').change(function(){
      if ($(this).val())
      {
        $.getJSON('<?php echo url_for('ttCommunicatie/templateHtml?template_id=999');?>'.replace('999', $(this).val()), function(data) {
          var cultures  = data.cultures;
          var html      = data.html;
          var onderwerp = data.onderwerp;
          
          //console.info(cultures);
          <?php if ($edit_template) : ?>
            $(cultures).each(function(i, culture){
              var h = html[culture];
              var o = onderwerp[culture];
              var editorId = 'html_' + culture;
              
              tinyMCE.getInstanceById(editorId).execCommand('mceSetContent', false, h);
              $('#onderwerp_' + culture).val(o);
            });
          <?php endif; ?>
          //$('#sjabloon_reedsontvangen').html(data.reedsontvangen);
          $('#sjabloon_eenmalig').html(data.eenmalig);
        });
      }
    }).change();  
    <?php endif; ?>
  });

  function bijlageToevoegen()
  {
    var cnt = jQuery('input.bijlagen').size();
    var str = '<input id="bijlage' + cnt + '" type="file" value="" name="bijlage' + cnt + '" class="bijlagen">';

    jQuery('a.bijlage_toevoegen').before(str);
    jQuery('a.bijlage_toevoegen').before('<br />');
  }
</script>