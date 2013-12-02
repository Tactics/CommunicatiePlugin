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

<?php
$waarschuwingsAantal = 250;
if ($bestemmelingen_aantal > $waarschuwingsAantal)
{
  $sf_request->setAttribute('error_message', "Opgelet! Bent u zeker dat u <strong>$bestemmelingen_aantal brieven/e-mails</strong> wil verzenden?");
  include_partial('global/errormessage');
}
?>

<h2 class="pageblock">Brief opmaken</h2>
<div class="pageblock">
  <?php echo form_tag('ttCommunicatie/print', array(
    'name'      => 'print',
    'target'    => '_blank',
    'multipart' => true
  )); ?>
  <?php echo input_hidden_tag('hash', $md5hash); ?>
  <?php if ($brief_template) : ?>
    <?php echo input_hidden_tag('template_id', $brief_template->getId()); ?>
  <?php endif; ?>

  <?php if ($show_bestemmelingen): ?>
    <div id="dialog-bestemmelingen" style="display:none;">
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
          <?php foreach ($bestemmelingen as $objectId => $objectBestemmelingen) : ?>
            <?php foreach ($objectBestemmelingen as $index => $objectBestemmeling) : ?>
              <li>
                <label>
                  <?php
                  echo checkbox_tag("bestemmelingen[$objectId][$index]", $index, true, array('class' => 'bestemmeling', 'name' => "bestemmelingen[$objectId][]"));
                  echo '&nbsp;' . $objectBestemmeling->getNaam();
                  ?>
                </label>
              </li>
            <?php endforeach; ?>
          <?php endforeach; ?>
        </ul>
      </div>
      <hr />
      <?php echo button_to_function('Opslaan', 'jQuery("#dialog-bestemmelingen").tt_window().close(); countBestemmelingen();');?>    
    </div>
<?php endif; ?>

  <table class="formtable">
    <?php if ($choose_template) : ?>
      <tr>
        <th>Sjabloon:</th>
        <td><?php echo select_tag('template_id', objects_for_select($brief_templates, 'getId', 'getNaam')); ?></td>
      </tr>      
    <?php endif; ?>

    <?php if ($bestemmelingen_aantal === 1) :      
      $emailTo = $bestemmeling->getEmailTo();
      $emailCc = $bestemmeling->getEmailCc(true);
      $emailBcc = $bestemmeling->getEmailBcc(true);
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
          <span id="record-count"><?php echo $bestemmelingen_aantal; ?></span>
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
            'bestemmeling' => isset($bestemmeling) ? $bestemmeling : null
          ));
          ?>
        </td>
        <td>
          <?php if ($is_target) : ?>
            <h2 class="pageblock" style="margin-left: 20px; width: 300px;">Invoegvelden</h2>
            <div id="placeholders" class="pageblock" style="overflow: auto; height: 500px; width: 295px; margin-left: 20px;">            
            <?php
              $placeholders = eval("return $objectClass::getPlaceholders();");
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

  <?php echo submit_tag('Brieven afdrukken', array('data-type' => 'brieven')); ?>
  <?php echo submit_tag('E-mails verzenden', array('class' => 'emailonly', 'data-type' => 'e-mails')); ?>
  </form>
</div>


<script type="text/javascript">
  var submitBtn = null;
  
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
    var aantal = jQuery('input.bestemmeling:checked').length;
    jQuery('#record-count').html(aantal);

    return aantal;
  }
  
  <?php endif; ?>

  jQuery(function($){
    // language tabs initialiseren
    $('#tabs').tt_tabs();
    
    tinyMCE.init({
      selector: "textarea",
      width : "600",
      height : "454",
      language : "nl",
      plugins: [
        "paste, link, pagebreak, jbimages"
      ],
      pagebreak_separator : "%pagebreak%",
      toolbar: "undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent",
      relative_urls: false
    });
    
    <?php if ($show_bestemmelingen): ?>
      $.expr[':'].icontains = function(a, i, m) {
        return jQuery(a).text().toUpperCase().indexOf(m[3].toUpperCase()) >= 0;
      };

      $('#select_all').change(function(){
        $('input.bestemmeling').attr('checked', $(this).is(':checked')).change();
      }).change();

      $('#search').keyup(function(){
        var search = $(this).val();

        $('#dialog-bestemmelingen li').show();
        $('#dialog-bestemmelingen li > label').not(':icontains("' + search + '")').closest('li').hide();
      });
    <?php endif; ?>

    // confirmBox indien > $waarschuwingsAantal bestemmelingen
    $('form[name=print]').on('click', 'input[type=submit]', function(){
      submitBtn = this;
    });
    
    $('form[name="print"]').on('submit', function(){
      var type = $(submitBtn).data('type');
      if (type) // voorbeelden hebben geen data-type attrib en worden sowieso gesubmit
      {
        var aantal = <?php echo $show_bestemmelingen ? 'countBestemmelingen()' : $bestemmelingen_aantal; ?>;
        if ((aantal >= <?php echo $waarschuwingsAantal; ?>) && !confirm('Bent u zeker dat u ' + aantal + ' ' + type + ' wil ' + (type == 'brieven' ? 'afdrukken' : 'verzenden') + '?'))
        {
          return false;
        }
      }
    }); 
    
    <?php if ($choose_template): ?>
    // Laad template na selectie    
    $('#template_id').change(function(){
      if ($(this).val())
      {
        $.getJSON('<?php echo url_for('ttCommunicatie/templateHtml?template_id=xxx999&hash=' . $md5hash);?>'.replace('xxx999', $(this).val()), function(data) {
          var cultures  = data.cultures;
          var html      = data.html;
          var onderwerp = data.onderwerp;
          
          //console.info(cultures);
          <?php if ($edit_template) : ?>
            $(cultures).each(function(i, culture){
              var h = html[culture];
              var o = onderwerp[culture];
              var editorId = 'html_' + culture;
              
              tinyMCE.get(editorId).execCommand('mceSetContent', false, h);
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
