<?php
if(! function_exists('__'))
  \Misc::use_helper('I18N');
?>

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
  <?php echo form_tag('ttCommunicatie/opmaak', array('name' => 'select_bestemmelingen')) ?>
    <h2><?php echo __('Bestemmelingen');?></h2>
    <span>
      <?php
        echo label_for('search', __('Zoeken'));
        echo '&nbsp;';
        echo input_tag('search');
      ?>
    </span><br /><br />
    <span>
      <a href="#" style="display: inline-block;" id="selecteer-alle"><?php echo __('selecteer');?></a> / <a href="#" style="display: inline-block;" id="deselecteer-alle"><?php echo __('deselecteer');?></a>
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
            echo checkbox_tag('bestemmeling_id', $bestemmeling->getId(), true, array('id' => 'bestemmelingen_id_' . $bestemmeling->getId()));
            echo '&nbsp;';
            echo label_for('bestemmelingen_id_' . $bestemmeling->getId(), $bestemmeling->getMailerRecipientName());
          ?>
        </li>
        <?php endwhile; ?>
        <?php $rs->seek(0); // reset $rs ?>
      </ul>
    </div>
    <hr />
    <?php echo submit_tag(__('Opslaan')) ?>
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
  <h2 class="pageblock"><?php echo __('Brief opmaken');?></h2>
  <div class="pageblock">
    <table class="formtable">
      <?php if ($choose_afzender): ?>
        <tr>
          <th><?php echo __('Verstuur als');?>:</th>
            <td>
              <?php
              echo select_tag('afzender', options_for_select($mogelijke_afzenders))
              ?>
                <br/>
              <?php echo input_tag('afzender_other', $afzender_other, array('size' => 80, 'style' => 'display:none;')); ?>
            </td>
        </tr>
      <?php endif ?>
      <?php if ($choose_template) : ?>
        <tr>
          <th><?php echo __('Sjabloon');?>:</th>
          <td><?php echo select_tag('template_id', objects_for_select($brief_templates, 'getId', 'getNaam')); ?></td>
        </tr>
      <?php endif; ?>

      <?php
        $emailCc = '';
        $emailBcc = '';
      ?>

      <?php if ($aantalBestemmelingen === 1) : ?>
        <?php
        $rs->next();
        $object = new $bestemmelingenClass();
        $object->hydrate($rs);
        $emailTo = $object->getMailerRecipientMail();
        if (method_exists($object, 'getMailerRecipientCC') && ($object->getMailerRecipientCC()))
        {
          $emailCc = $object->getMailerRecipientCC();
          $emailCc = !empty($emailCc) ? implode(';', $emailCc) : '';
        }
        if (method_exists($object, 'getMailerRecipientBCC') && ($object->getMailerRecipientBCC()))
        {
          $emailBcc = $object->getMailerRecipientBCC();
          $emailBcc = !empty($emailBcc) ? implode(';', $emailBcc) : '';
        }

        $rs->seek(0); // reset $rs
        ?>
        <tr>
          <th><?php echo __('Bestemmeling');?>:</th>
          <td>
            <?php echo input_tag('email_to', $emailTo, array('size' => 80)); ?>
            &nbsp; <?php echo link_to_function('Cc/Bcc', "jQuery('.cc_bcc').toggle();"); ?>
          </td>
        </tr>
        <tr class="cc_bcc" <?php echo $emailCc ? '' : 'style="display: none"'; ?>>
          <th><?php echo __('Cc');?>:</th>
          <td><?php echo input_tag('email_cc', $emailCc, array('size' => 80)); ?></td>
        </tr>
        <tr class="cc_bcc" <?php echo $emailBcc ? '' : 'style="display: none"'; ?>>
          <th><?php echo __('Bcc');?>:</th>
          <td><?php echo input_tag('email_bcc', $emailBcc, array('size' => 80)); ?></td>
        </tr>
      <?php else : ?>
        <tr>
          <th><?php echo __('Aantal bestemmelingen');?>:</th>
          <td>
            <span id="record-count"><?php echo $aantalBestemmelingen; ?></span>
            <?php if ($show_bestemmelingen): ?>
              (<a href="#" id="toggle-bestemmelingen"><?php echo __('Toon lijst');?></a>)
            <?php endif ?>
            <?php echo link_to_function('Cc/Bcc', "jQuery('.cc_bcc').toggle();"); ?>
          </td>
        </tr>
        <tr class="cc_bcc" <?php echo $emailCc ? '' : 'style="display: none"'; ?>>
          <th><?php echo __('Cc');?>:</th>
          <td><?php echo input_tag('email_cc', $emailCc, array('size' => 80)); ?></td>
        </tr>
        <tr class="cc_bcc" <?php echo $emailBcc ? '' : 'style="display: none"'; ?>>
          <th><?php echo __('Bcc');?>:</th>
          <td><?php echo input_tag('email_bcc', $emailBcc, array('size' => 80)); ?></td>
        </tr>
      <?php endif; ?>

      <tr>
        <th><?php echo __('Verzenden via e-mail');?>:</th>
        <td>
          <?php echo select_tag('verzenden_via', options_for_select(array(
              'liefst' => __('Ja, indien gewenst'),
              'altijd' => __('Ja, altijd'),
              'nee' => __('Nee, alles afdrukkken op papier')
            ), 'ja'), array('onchange' => 'jQuery(this).val() != "nee" ? jQuery(".emailonly").show() : jQuery(".emailonly").hide();'))?>
        </td>
      </tr>

      <?php if ($brief_template) : ?>
        <tr>
          <th><?php echo __('Eenmalig verzenden');?>:</th>
          <td id="sjabloon_eenmalig"><?php echo $brief_template->getEenmaligVersturen() ? __('Ja') : __('Nee'); ?></td>
        </tr>
      <?php endif; ?>

      <?php if ($edit_template) : ?>
        <tr>
          <th>&nbsp;</th>
          <td>&nbsp;</td>
        </tr>
        <tr class="required">
          <th><?php echo __('Onderwerp/tekst');?>:</th>
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
              <h2 class="pageblock" style="margin-left: 20px; width: 300px;"><?php echo __('Invoegvelden');?></h2>
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
        <th><?php echo __('Bijlagen');?>:</th>
        <td>
          <?php echo input_file_tag('bijlage0', array('class' => 'bijlagen')) ?> <br />
          <?php echo link_to_function('__(Bijlage toevoegen)', 'bijlageToevoegen()', array('class' => 'bijlage_toevoegen')); ?>
        </td>
      </tr>
    </table>

    <hr />
    <?php echo submit_tag('Voorbeeld brief'); // opgelet: de naam van deze knop moet 'voorbeeld' bevatten, hierop wordt getest in de executePrint()' ?>
    <?php echo submit_tag('Voorbeeld e-mail'); // opgelet: de naam van deze knop moet 'voorbeeld' bevatten, hierop wordt getest in de executePrint()' ?>

    <?php if ($show_bestemmelingen): ?>
      <?php echo submit_tag(__('Brieven afdrukken')); ?>
      <?php echo submit_tag(__('E-mails verzenden'), array('class' => 'emailonly')); ?>
    <?php else: ?>
      <?php echo submit_tag(__('Brieven afdrukken'), array('confirm' => __('Bent u zeker dat u tot') . ' ' . $rs->getRecordCount() . ' ' . __('brieven wilt afdrukken?'))); ?>
      <?php echo submit_tag(__('E-mails verzenden'), array('class' => 'emailonly', 'confirm' => __('Bent u zeker dat u tot') . ' ' . $rs->getRecordCount() . ' ' . __('e-mails wilt verzenden?'))); ?>
    <?php endif; ?>

  </div>
</form>

<script type="text/javascript">
  function insertPlaceholder(placeholder)
  {
    tinyMCE.execCommand('mceInsertContent', null, '%' + placeholder + '%');
  }

  function showHideOther(element) {
      var value = element.val();
      if (value === 'other') {
          jQuery('#afzender_other').show();
      } else {
          jQuery('#afzender_other').val('');
          jQuery('#afzender_other').hide();
      }
  }

  showHideOther(jQuery('#afzender'));
  jQuery('#afzender').on('change', function(){
      showHideOther(jQuery(this));
  });

  <?php if ($show_bestemmelingen): ?>
  function showDialog(dialog)
  {
    jQuery(dialog).tt_window();
    jQuery('div.close').remove();
  }

  (function() {
    var toggled = true;

    jQuery('#toggle-bestemmelingen').click(function() {
      showDialog('#dialog-bestemmelingen');

      if (toggled === false) {
        toggleBestemmelingen(false)();
      }

      toggled = true;
    });

    jQuery('#selecteer-alle').click(toggleBestemmelingen(true));
    jQuery('#deselecteer-alle').click(toggleBestemmelingen(false));

    function toggleBestemmelingen(bool) {
      return function() {
        jQuery('input[name="bestemmeling_id"]:visible').attr('checked', bool);
      };
    }
  })();

  jQuery('form[name="select_bestemmelingen"]').submit(function() {
    jQuery("#dialog-bestemmelingen").tt_window().close();
    saveBestemmelingen();
    countBestemmelingen();
    return false;
  });

  function countBestemmelingen()
  {
    var aantal = jQuery('input[name="bestemmeling_id"]:checked').length;
    jQuery('#record-count').html(aantal);
  }

  function saveBestemmelingen() {
    // clear hidden inputs
    jQuery('input[name="niet_verzenden_naar[]"]').remove();

    // maxLength van string
    var maxLength = 2000;
    var counter = 0;
    var eForm = jQuery('form[name="print"]');

    jQuery('input[name="bestemmeling_id"]').each(function() {
      var checked = jQuery(this).is(':checked');
      var bestemmelingId = jQuery(this).val();

      if (false === checked) {
        // zoek de huidige container input
        var currentContainerIdentifier = 'niet_verzenden_naar_' + counter.toString();
        // als die niet bestaat, aanmaken
        if (jQuery('input#' + currentContainerIdentifier).length === 0) {
          eForm.prepend('<input id="' + currentContainerIdentifier + '" type="hidden" value="" name="niet_verzenden_naar[]">');
        }
        // inladen
        var currentContainer = jQuery('input#' + currentContainerIdentifier);
        // check string length, indien te groot om de id + delimiter toe te voegen, dan nieuwe container maken
        if ((currentContainer.val().length + bestemmelingId.toString().length + 1) > maxLength) {
          counter = counter + 1;
          currentContainerIdentifier = 'niet_verzenden_naar_' + counter.toString();
          eForm.prepend('<input id="' + currentContainerIdentifier + '" type="hidden" value="" name="niet_verzenden_naar[]">');
          currentContainer = jQuery('input#' + currentContainerIdentifier);
        }
        // id toevoegen aan value
        var currentContainerValue = currentContainer.val();
        currentContainerValue = currentContainerValue + (currentContainerValue.length === 0 ? '' : ',') + bestemmelingId.toString();
        currentContainer.val(currentContainerValue);
      }
    });
  }

  <?php endif; ?>

  jQuery(function($){
    // language tabs initialiseren
    $('#tabs').tt_tabs();

    // Deze configuratie zou eigenlijk overschrijfbaar moeten zijn
    // @TODO eens bekijken hoe/of symfony 1 dit toelaat
    tinyMCE.init({
      mode : "textareas",
      theme : "advanced",
      theme_advanced_toolbar_location : "top",
      width : "600",
      height : "454",
      language : "nl",
      plugins: "paste, pagebreak",

      pagebreak_separator : "%pagebreak%",
      theme_advanced_font_sizes : "10px,12px,14px,16px,24px",
      theme_advanced_text_colors : "000000",
      theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|styleselect,fontselect,fontsizeselect,forecolor,|,undo,redo,|,cleanup,|,bullist,numlist,|,justifyleft,justifycenter,justifyright,justifyfull,|,cut,copy,paste,pastetext,pasteword,|,pagebreak,|,link,unlink,",
      theme_advanced_buttons2 : "",
      theme_advanced_buttons3 : "",
      theme_advanced_fonts : "Andale Mono=andale mono,times;"+
            "Arial=arial,helvetica,sans-serif;"+
            "Arial Black=arial black,avant garde;"+
            "Book Antiqua=book antiqua,palatino;"+
            "Calibri=calibri,sans-serif;"+
            "Comic Sans MS=comic sans ms,sans-serif;"+
            "Cooper Hewitt=cooper hewitt,helvetica,arial,sans-serif;"+
            "Courier New=courier new,courier;"+
            "Georgia=georgia,palatino;"+
            "Helvetica=helvetica;"+
            "Impact=impact,chicago;"+
            "Symbol=symbol;"+
            "Tahoma=tahoma,arial,helvetica,sans-serif;"+
            "Terminal=terminal,monaco;"+
            "Times New Roman=times new roman,times;"+
            "Trebuchet MS=trebuchet ms,geneva;"+
            "Verdana=verdana,geneva;"+
            "Webdings=webdings;"+
            "Wingdings=wingdings,zapf dingbats",
        content_css : "/ttCommunicatie/web/css/fonts.css",
      init_instance_callback : function() {
        $('#template_id').change();
      }
    });

    <?php if ($show_bestemmelingen): ?>
    $.expr[':'].icontains = function(a, i, m) {
      return jQuery(a).text().toUpperCase().indexOf(m[3].toUpperCase()) >= 0;
    };

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
        $.getJSON('<?php echo url_for('ttCommunicatie/templateHtml?template_id=999');?>'.replace('999', $(this).val()) + '&hash=' + $('#hash').val() , function(data) {
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
    });
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
