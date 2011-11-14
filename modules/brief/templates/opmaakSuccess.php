<?php include_partial('global/breadcrumb'); ?>
<?php // use_javascript('/ttBericht/js/tinymce/tiny_mce.js'); ?>

<?php $onsubmit = $choose_template ? "if (! jQuery('#onderwerp').val()) {alert('Gelieve een onderwerp op te geven'); jQuery('#onderwerp').focus(); return false;}" : ''; ?>

<?php echo form_tag('brief/print', array('target' => '_blank', 'multipart' => true, 'onsubmit' => $onsubmit)); ?>
  <?php echo input_hidden_tag('choose_template', $choose_template); ?>
  <h2 class="pageblock">Brief opmaken</h2>
  <div class="pageblock">
    <table class="formtable">
      <tr>
        <th>Aantal bestemmelingen:</th>
        <td><?php echo $aantalBestemmelingen; ?></td>
      </tr>
      <tr>
        <th>Verzenden via e-mail:</th>
        <td>
          <?php echo select_tag('verzenden_via', options_for_select(array('ja' => 'Ja, indien mogelijk', 'nee' => 'Nee, alles afdrukkken op papier'), 'ja'), array('onchange' => 'jQuery(".emailonly").toggle(jQuery(this).val() == "ja");'))?>
        </td>
      </tr>
      
      <?php if ($choose_template) : ?>
        <tr>
          <th>Sjabloon:</th>
          <td>
            <?php echo select_tag('template_id', objects_for_select($brief_templates, 'getId', 'getNaam', $brief_template->getId())); ?>
          </td>
        </tr>      
     
        <tr>
          <th>Eenmalig verzenden:</th>
          <td id="sjabloon_eenmalig"></td>
        </tr>

        <tr>
          <th>&nbsp;</th>
          <td>&nbsp;</td>
        </tr>
        <tr class="required">
          <th>Onderwerp:</th>
          <td>
            <?php echo input_tag('onderwerp', $onderwerp, array('size' => 60)); ?>
          </td>
        </tr>
        <tr>
          <th>&nbsp;</th>
          <td>&nbsp;</td>
        </tr>
        <tr class="required">
          <th>Tekst:</th>
          <td>
          <?php

          $mceoptions = 'theme : "advanced", width:"600", height:"454", convert_urls:\'false\', language:"nl", relative_urls:\'false\', plugins:"paste",
            theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,undo,redo,|,cleanup,|,bullist,numlist,|,justifyleft,justifycenter,justifyright,justifyfull,|,cut,copy,paste,pastetext,pasteword",
            theme_advanced_buttons2 : "",
            theme_advanced_buttons3 : ""
            ';

          echo textarea_tag('html', $brief_template->getHtml(), array(
            'rich' => true,
            'tinymce_options' => $mceoptions
          ));
          ?>
          </td>
          <td>
            <h2 class="pageblock" style="margin-left: 20px; width: 200px;">Invoegvelden</h2>
            <div id="placeholders" class="pageblock" style="overflow: auto; height: 430px; width: 195px; margin-left: 20px;">
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
    // Laad template na selectie
    $('#template_id').change(function(){
      if ($(this).val())
      {
        $('.enkelVoorSjablonen').show();
        $.getJSON('<?php echo url_for('brief/templateHtml?template_id=999');?>'.replace('999', $(this).val()), function(data) {
          tinyMCE.execCommand('mceSetContent', false, data.html);
          $('#sjabloon_reedsontvangen').html(data.reedsontvangen);
          $('#sjabloon_eenmalig').html(data.eenmalig);
          $('#onderwerp').val(data.onderwerp);
        });
      }
    }).change();
  });

  function bijlageToevoegen()
  {
    var cnt = jQuery('input.bijlagen').size();
    var  str = '<input id="bijlage' + cnt + '" type="file" value="" name="bijlage' + cnt + '" class="bijlagen">';

    jQuery('a.bijlage_toevoegen').before(str);
    jQuery('a.bijlage_toevoegen').before('<br />');
  }
</script>