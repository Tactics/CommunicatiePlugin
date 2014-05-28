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
    'multipart' => true
  )); ?>
  <?php echo input_hidden_tag('hash', $md5hash); ?>
  <?php if ($brief_template) : ?>
    <?php echo input_hidden_tag('template_id', $brief_template->getId()); ?>
  <?php endif; ?>

  <?php if ($show_bestemmelingen): ?>
    <?php include_partial('showBestemmelingen', array('bestemmelingen' => $bestemmelingen)); ?>
  <?php endif; ?>

  <table class="formtable">
    <?php if ($choose_template) : ?>
      <tr>
        <th>Sjabloon:</th>
        <td><?php echo select_tag('template_id', objects_for_select($brief_templates, 'getId', 'getNaam', null, array('include_blank' => true))); ?></td>
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
          ), 'liefst'), array('onchange' => 'changeButtons(this.value)'))?>
      </td>
    </tr>

    <?php if ($brief_template) : ?>
      <tr>
        <th>Eenmalig verzenden:</th>
        <td id="sjabloon_eenmalig"><?php echo $brief_template->getEenmaligVersturen() ? 'Ja' : 'Nee'; ?></td>
      </tr>
    <?php endif; ?>

    <?php if ($edit_template || $choose_template): ?>
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
            'edit_template'       => $edit_template,
            'bestemmeling' => isset($bestemmeling) ? $bestemmeling : null
          ));
          ?>
        </td>
        <td>
          <?php if ($is_target && $edit_template) : ?>
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
  <?php echo button_to_function('Voorbeeld brief', 'postForm("Voorbeeld brief")'); // opgelet: de naam van deze knop moet 'voorbeeld' bevatten, hierop wordt getest in de executePrint()' ?>
  <?php echo button_to_function('Voorbeeld e-mail', 'postForm("Voorbeeld e-mail")'); // opgelet: de naam van deze knop moet 'voorbeeld' bevatten, hierop wordt getest in de executePrint()' ?>

  <?php echo button_to_function('Brieven afdrukken', 'postForm("brieven afdrukken")', array('class' => 'briefonly', 'data-type' => 'brieven')); ?>
  <?php echo button_to_function('E-mails verzenden', 'postForm("E-mails verzenden")', array('class' => 'emailonly', 'data-type' => 'e-mails')); ?>
  </form>
</div>
<div id="mailWindow" style="display:none;height:400px;overflow:scroll;"></div>

<script type="text/javascript">
  var submitBtn = null;
  
  function insertPlaceholder(placeholder)
  {
    tinyMCE.execCommand('mceInsertContent', null, '%' + placeholder + '%');
  }

  function changeButtons(value)
  {
    if (value != "nee")
    {
      jQuery(".emailonly").show();
      jQuery(".briefonly").hide();
    } else {
      jQuery(".emailonly").hide();
      jQuery(".briefonly").show();
    }
  }

  function postForm(commit)
  {
    if(commit == 'E-mails verzenden')
    {
      var input = $("<input>").attr("type", "hidden").attr("name", "commit").val(commit);
      jQuery(document.forms['print']).append($(input));
      tinyMCE.triggerSave(); //!important do not remove
      jQuery.ajax({
        url: '<?php echo url_for('ttCommunicatie/print'); ?>',
        type: 'POST',
        data: jQuery(document.forms['print']).serialize(),
        success: function(html)
        {
          jQuery("#mailWindow").tt_window({width:'700px'});
          jQuery('#mailWindow').html(html);
        }
      });
    }
    else
    {
      jQuery(document.forms['print']).attr('target', '_blank');
      var input = $("<input>").attr("type", "hidden").attr("name", "commit").val(commit);
      jQuery(document.forms['print']).append($(input));
      jQuery(document.forms["print"]).submit();
    }
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
      readonly: <?php echo $edit_template ? '0' : '1'; ?>,
      plugins: [
        "paste, link, pagebreak, jbimages"
      ],
      pagebreak_separator : "%pagebreak%",
      toolbar: "undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent",
      relative_urls: false
    });

    $(".briefonly").hide();

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
          $(cultures).each(function(i, culture){
            var h = html[culture];
            var o = onderwerp[culture];
            var editorId = 'html_' + culture;

            tinyMCE.get(editorId).execCommand('mceSetContent', false, h);
            $('#onderwerp_' + culture).val(o);
          });

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
