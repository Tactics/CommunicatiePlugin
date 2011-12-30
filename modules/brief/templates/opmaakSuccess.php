<?php include_partial('global/breadcrumb'); ?>
<?php use_javascript('/ttBericht/js/tinymce/tiny_mce.js'); ?>

<?php echo form_tag('brief/print', array('target' => '_blank', 'multipart' => true)); ?>

  <h2 class="pageblock">Brief opmaken</h2>
  <div class="pageblock">
    <table class="formtable">
      <tr>
        <th>Sjabloon:</th>
        <td>
          <?php echo select_tag('template_id', objects_for_select($brief_templates, 'getId', 'getNaam', $brief_template->getId())); ?>
        </td>
      </tr>
      <tr>
        <th>Aantal bestemmelingen:</th>
        <td><?php echo $aantalBestemmelingen; ?></td>
      </tr>
      <tr>
        <th>Eenmalig verzenden:</th>
        <td id="sjabloon_eenmalig"></td>
      </tr>
      <tr>
        <th>Verzenden via e-mail:</th>
        <td>
          <?php echo select_tag('verzenden_via', options_for_select(array('ja' => 'Ja, indien mogelijk', 'nee' => 'Nee, alles afdrukkken op papier'), 'ja'), array('onchange' => 'jQuery(".emailonly").toggle(jQuery(this).val() == "ja");'))?>
        </td>
      </tr>
      <tr>
        <th>&nbsp;</th>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <th>&nbsp;</th>
        <td>&nbsp;</td>
      </tr>
      <tr class="required">
        <th>Onderwerp/tekst:</th>
        <td>
        <?php

        $mceoptions = 'theme : "advanced", width:"600", height:"454", convert_urls:\'false\', language:"nl", relative_urls:\'false\', plugins:"paste",
          theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,undo,redo,|,cleanup,|,bullist,numlist,|,justifyleft,justifycenter,justifyright,justifyfull,|,cut,copy,paste,pastetext,pasteword",
          theme_advanced_buttons2 : "",
          theme_advanced_buttons3 : ""
          ';

          if ($is_vertaalbaar)
          {
            include_partial('brief_text_area_vertaalbaar', array(
              'brief_template'  => $brief_template, 
              'mceoptions' => $mceoptions,
              'language_array'  => $language_array
            ));
          }
          else
          {
            include_partial('brief_text_area', array(
              'brief_template'  => $brief_template, 
              'mceoptions' => $mceoptions
            ));
          }
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
      <tr>
        <th>Bijlagen:</th>
        <td>
          <?php echo input_file_tag('bijlage0', array('class' => 'bijlagen')) ?> <br />
          <?php echo link_to_function('Bijlage toevoegen', 'bijlageToevoegen()', array('class' => 'bijlage_toevoegen')); ?>
        </td>
      </tr>
    </table>
    
    <hr />
    <?php echo submit_tag('Voorbeeld weergeven'); // opgelet: de naam van deze knop moet 'voorbeeld' bevatten, hierop wordt getest in de executePrint()' ?>
    <?php echo submit_tag('E-mails verzenden', array('class' => 'emailonly')); ?>
    <?php echo submit_tag('Brieven afdrukken'); ?>
    
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
          var cultures  = data.cultures;
          var html      = data.html;
          var onderwerp = data.onderwerp;

          //console.log(cultures);
          $(cultures).each(function(i, culture){
            var h = html[culture];
            var o = onderwerp[culture];
            tinyMCE.getInstanceById('html[' + culture + ']').execCommand('mceSetContent', false, h);
            $('input[name="onderwerp[' + culture + ']"]').val(o);
          });
          $('#sjabloon_reedsontvangen').html(data.reedsontvangen);
          $('#sjabloon_eenmalig').html(data.eenmalig);
        });
      }
    }).change();
    
    
    <?php if ($is_vertaalbaar): ?>
      $('#tabs').tt_tabs();
    <?php endif; ?>
  });

  function bijlageToevoegen()
  {
    var cnt = jQuery('input.bijlagen').size();
    var  str = '<input id="bijlage' + cnt + '" type="file" value="" name="bijlage' + cnt + '" class="bijlagen">';

    jQuery('a.bijlage_toevoegen').before(str);
    jQuery('a.bijlage_toevoegen').before('<br />');
  }
</script>