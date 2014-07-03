<?php
// recursieve functie om alle placeholders weer te geven
function showPlaceholders($placeholders)
{   
  echo '<ul>';                            
  foreach($placeholders as $id => $placeholder)
  {
    if (is_array($placeholder))
    {
      echo '<li>' . $id;                         
      showPlaceholders($placeholder);
      echo '</li>';
    }
    else
    {
      echo '<li class="placeholder">' . link_to_function($placeholder, 'insertPlaceholder("' . $placeholder . '");') . '</li>';
    } 
  }    
  echo '</ul>';                            
}
?>

<?php include_partial('breadcrumb'); ?>

<?php if ($show_bestemmelingen): ?>
<div id="dialog-bestemmelingen" style="display:none;"> 
  <?php echo form_tag('', array('name' => 'select_bestemmelingen')) ?>
    <h2>Bestemmelingen</h2>
    <span>
      <?php 
        echo label_for('search', 'Zoeken');
        echo '&nbsp;';
        echo input_tag('search'); 
      ?>
    </span><br /><br />
    <span>
      <?php 
        echo checkbox_tag('select_all', 1, 1); 
        echo '&nbsp;';
        echo label_for('select_all', 'Selecteer / deselecteer alle');
      ?>
    </span>
    <hr />
    <div style="height: 170px; overflow: auto;">
      <ul style="list-style-type:none;">
        <?php 
          while ($rs->next()):
            $bestemmeling = new $bestemmelingenClass();
            $bestemmeling->hydrate($rs);
        ?>
        <li>
          <?php 
            echo checkbox_tag('bestemmeling_id', $bestemmeling->getId(), 1, array('id' => 'bestemmelingen_id_' . $bestemmeling->getId()));
            echo '&nbsp;';
            echo label_for('bestemmelingen_id_' . $bestemmeling->getId(), $bestemmeling->getMailerRecipientName());
          ?>
        </li>
        <?php endwhile; ?>
        <?php $rs->seek(0); // reset $rs ?>
      </ul>
    </div>
    <hr />
    <?php echo button_to_function('Opslaan', 'jQuery("#dialog-bestemmelingen").tt_window().close(); countBestemmelingen();');?>
  </form>
</div>
<?php endif; ?>

<?php 
  echo form_tag('ttCommunicatie/print', array(
    'name'      => 'print',
    'target'    => '_blank', 
    'multipart' => true
  )); 
?>
  <?php echo input_hidden_tag('hash', $md5hash); ?>  
  <?php if ($brief_template) : ?>
    <?php echo input_hidden_tag('template_id', $brief_template->getId()); ?>
  <?php endif; ?>
  <?php $aantalBestemmelingen = $rs->getRecordCount(); ?>
  <h2 class="pageblock">Brief opmaken</h2>
  <div class="pageblock">
    <table class="formtable">
      <?php if ($choose_afzender): ?>
        <tr>
          <th>Verstuur als:</th>
          <td>
            <?php 
              echo select_tag('afzender', options_for_select($mogelijke_afzenders))
            ?>
          </td>
        </tr>
      <?php endif ?>
      <?php if ($choose_template) : ?>
        <tr>
          <th>Sjabloon:</th>
          <td><?php echo select_tag('template_id', objects_for_select($brief_templates, 'getId', 'getNaam')); ?></td>
        </tr>      
      <?php endif; ?>
      
      <?php if ($aantalBestemmelingen === 1) : ?>
        <?php        
        $rs->next();
        $object = new $bestemmelingenClass();
        $object->hydrate($rs);
        $emailTo = $object->getMailerRecipientMail();
        $emailCc = '';
        $emailBcc = '';
        if (method_exists($object, 'getMailerRecipientCC') && ($emailCc = $object->getMailerRecipientCC()))
        {
          $emailCc = !empty($emailCc) ? implode(';', $emailCc) : '';
        }
        if (method_exists($object, 'getMailerRecipientBCC') && ($emailBcc = $object->getMailerRecipientBCC()))
        {
          $emailBcc = !empty($emailBcc) ? implode(';', $emailBcc) : '';
        }
        
        $rs->seek(0); // reset $rs
        ?>
        <tr>
          <th>Bestemmeling:</th>
          <td>
            <?php echo input_tag('email_to', $emailTo, array('size' => 80)); ?>
            &nbsp; <?php echo link_to_function('Cc/Bcc', "jQuery('.cc_bcc').toggle();"); ?>
          </td>
        </tr>
        <tr class="cc_bcc" <?php echo $emailCc ? '' : 'style="display: none"'; ?>>
          <th>Cc:</th>
          <td><?php echo input_tag('email_cc', $emailCc, array('size' => 80)); ?></td>
        </tr>
        <tr class="cc_bcc" <?php echo $emailBcc ? '' : 'style="display: none"'; ?>>
          <th>Bcc:</th>
          <td><?php echo input_tag('email_bcc', $emailBcc, array('size' => 80)); ?></td>
        </tr>
      <?php else : ?>       
        <tr>
          <th>Aantal bestemmelingen:</th>
          <td>
            <span id="record-count"><?php echo $aantalBestemmelingen; ?></span>
            <?php           
              if ($show_bestemmelingen)
              {
                echo '(' . link_to_function('Toon lijst', 'showDialog("#dialog-bestemmelingen");') . ')';
              }
            ?>
          </td>
        </tr>
      <?php endif; ?>
        
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
              'choose_template'     => $choose_template,
              'bestemmelingen_object' => $bestemmelingen_object
            ));
            ?>
          </td>
          <td>
            <?php if ($is_target) : ?>
              <h2 class="pageblock" style="margin-left: 20px; width: 300px;">Invoegvelden</h2>
              <div id="placeholders" class="pageblock" style="overflow: auto; height: 500px; width: 295px; margin-left: 20px;">            
              <?php
                $placeholders = eval("return $bestemmelingenClass::getPlaceholders();");   
                // Algemene placeholders indien gedefinieerd
                if (class_exists('Placeholder'))
                {
                  $placeholders = array_merge($placeholders, Placeholder::getPlaceholders());
                }
                showPlaceholders($placeholders);                      
              ?>
              </div>
            <?php endif; ?>
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
    
    <?php if ($show_bestemmelingen): ?>
      <?php echo submit_tag('Brieven afdrukken'); ?>
      <?php echo submit_tag('E-mails verzenden', array('class' => 'emailonly')); ?>
    <?php else: ?>
      <?php echo submit_tag('Brieven afdrukken', array('confirm' => 'Bent u zeker dat u tot ' . $rs->getRecordCount() . ' brieven wilt afdrukken?')); ?>
      <?php echo submit_tag('E-mails verzenden', array('class' => 'emailonly', 'confirm' => 'Bent u zeker dat u tot ' . $rs->getRecordCount() . ' e-mails wilt verzenden?')); ?>
    <?php endif; ?>
    
  </div>
</form>

<script type="text/javascript">
  function insertPlaceholder(placeholder)
  {
    tinyMCE.execCommand('mceInsertContent', null, '%' + placeholder + '%');
  }
  
  <?php if ($show_bestemmelingen): ?>
  function showDialog(dialog)
  {
    jQuery(dialog).tt_window();
    jQuery('div.close').remove();
  }    
    
  function countBestemmelingen()
  {
    var aantal = jQuery('input[name="bestemmeling_id"]:checked').length;
    jQuery('#record-count').html(aantal);
  }
  
  <?php endif; ?>

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
      theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,undo,redo,|,cleanup,|,bullist,numlist,|,justifyleft,justifycenter,justifyright,justifyfull,|,cut,copy,paste,pastetext,pasteword,|,pagebreak,|,link,unlink,",
      theme_advanced_buttons2 : "",
      theme_advanced_buttons3 : "",
      init_instance_callback : function(editor)
      {
        <?php if($brief_template && ! $brief_template->getBewerkbaar()):?>
        editor.getBody().setAttribute('contenteditable',false);
        <?php endif;?>
      }

    });

    <?php if ($show_bestemmelingen): ?>
    $.expr[':'].icontains = function(a, i, m) {            
      return jQuery(a).text().toUpperCase().indexOf(m[3].toUpperCase()) >= 0; 
    };

    
    $('#select_all').change(function(){
      $('input[name="bestemmeling_id"]').attr('checked', $(this).is(':checked')).change();
    }).change();        
        
    $('input[name="bestemmeling_id"]').change(function(){
      var checked = $(this).is(':checked');
      if (checked)
      {
        $('input#niet_verzenden_naar_' + $(this).val()).remove();
        
        $('input[name="bestemmeling_id"]').each(function()
        {
          checked = (checked && $(this).is(':checked'));
          return checked;
        }); 
      }
      else
      {
        var input = '<input id="niet_verzenden_naar_' + $(this).val() + '" type="hidden" value="' + $(this).val() + '" name="niet_verzenden_naar[]">';
        $('form[name="print"]').prepend(input);
      }
      
      $('#select_all').attr('checked', checked);
    }).change();
    
    $('#search').keyup(function(){
      var search = $(this).val();
      
      $('#dialog-bestemmelingen li').show();
      $('#dialog-bestemmelingen li > label').not(':icontains("' + search + '")').closest('li').hide(); 
    });
    
    // confirmbox
    //$('form[name=print]').submit(function(){
    //  return confirm("Bent u zeker dat u tot " + jQuery('input[name=bestemmeling_id]:checked').length + " e-mails wilt verzenden?");
    //});
    
    <?php endif; ?>
    
    <?php if ($choose_template): ?>
    // Laad template na selectie    
    $('#template_id').change(function(){
      if ($(this).val())
      {
        $.getJSON('<?php echo url_for('ttCommunicatie/templateHtml?template_id=999');?>'.replace('999', $(this).val()), function(data) {
          var cultures  = data.cultures;
          var html      = data.html;
          var onderwerp = data.onderwerp;

          <?php if ($edit_template) : ?>
            tinyMCE.activeEditor.getBody().setAttribute('contenteditable', data.bewerkbaar == '1' ? true : false);

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
