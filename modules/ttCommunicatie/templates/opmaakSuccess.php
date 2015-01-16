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

<?php slot('breadcrumb');
include_partial('breadcrumb');
end_slot();
?>

<?php
$waarschuwingsAantal = 250;
if ($bestemmelingen_aantal > $waarschuwingsAantal)
{
  $sf_request->setAttribute('error_message', "Opgelet! Bent u zeker dat u <strong>$bestemmelingen_aantal brieven/e-mails</strong> wil verzenden?");
  include_partial('global/errormessage');
}
?>

<section id="widget-grid">
  <div class="row">
    <article class="col-sm-12 col-md-12 col-lg-12 sortable-grid ui-sortable">
      <div class="jarviswidget jarviswidget-sortable" id="wid-id-0" data-widget-colorbutton="false" data-widget-editbutton="false" data-widget-custombutton="false" role="widget">
        <header role="heading">
          <div class="jarviswidget-ctrls" role="menu">
            <a href="javascript:void(0);" class="button-icon jarviswidget-toggle-btn" rel="tooltip" title="" data-placement="bottom" data-original-title="Collapse">
              <i class="fa fa-minus"></i>
            </a>
          </div>
          <h2>Brief opmaken</h2>
          <span class="jarviswidget-loader">
            <i class="fa fa-refresh fa-spin"></i>
          </span>
        </header>
          <div role="content">
          <div class="widget-body no-padding">
            <?php echo form_tag('ttCommunicatie/print', array(
                                'name'      => 'print',
                                'multipart' => true,
                                'class' => 'smart-form'
                                )); ?>
            <?php echo input_hidden_tag('hash', $md5hash); ?>
            <?php if ($brief_template) : ?>
              <?php echo input_hidden_tag('template_id', $brief_template->getId()); ?>
            <?php endif; ?>
                <fieldset>
                  <div class="row">
                    <?php if ($show_bestemmelingen): ?>
                      <?php include_partial('showBestemmelingen', array('bestemmelingen' => $bestemmelingen)); ?>
                    <?php endif; ?>

                    <?php if ($choose_template) : ?>
                      <section class="col col-2">
                        <label class="label">Sjabloon:</label>
                        <label class="select">
                          <?php echo select_tag('template_id', objects_for_select($brief_templates, 'getId', 'getNaam', null, array('include_blank' => true))); ?>
                          <i></i>
                        </label>
                      </section>
                      <div style="clear:both"></div>
                    <?php endif; ?>

                  <?php if ($bestemmelingen_aantal === 1) :
                    $emailTo = $bestemmeling->getEmailTo();
                    $emailCc = $bestemmeling->getEmailCc(true);
                    $emailBcc = $bestemmeling->getEmailBcc(true);
                    ?>
                    <section class="col col-2">
                      <label class="label">Bestemmeling:</label>
                      <label class="input">
                        <?php echo input_tag('email_to', $emailTo, array('size' => 80)); ?>
                        &nbsp; <?php echo link_to_function('Cc/Bcc', "jQuery('.cc_bcc').toggle();"); ?>
                      </label>
                    </section>
                    <div style="clear:both"></div>
                    <section class="col col-2 cc_bcc" <?php echo $emailCc ? '' : 'style="display: none"'; ?>>
                      <label class="label">Cc:</label>
                      <label class="input"><?php echo input_tag('email_cc', $emailCc, array('size' => 80)); ?></label>
                    </section>
                    <div style="clear:both"></div>
                    <section class="col col-2 cc_bcc" <?php echo $emailBcc ? '' : 'style="display: none"'; ?>>
                      <label class="label">Bcc:</label>
                      <label class="input"><?php echo input_tag('email_bcc', $emailBcc, array('size' => 80)); ?></label>
                    </section>
                  <?php else : ?>
                    <section class="col col2">
                      <label class="label">Aantal bestemmelingen:</label>
                      <label class="label">
                        <span id="record-count"><?php echo $bestemmelingen_aantal; ?></span>
                        <?php
                          if ($show_bestemmelingen)
                          {
                            echo '(' . link_to_function('Toon lijst', 'showDialog("#dialog-bestemmelingen");') . ')';
                          }
                        ?>
                      </label>
                    </section>
                    <?php endif; ?>
                    <div style="clear:both"></div>
                    <section class="col col-2">
                      <label class="label">Verzenden via e-mail:</label>
                      <label class="select">
                        <?php echo select_tag('verzenden_via', options_for_select(array(
                            'liefst' => 'Ja, indien gewenst',
                            'altijd' => 'Ja, altijd',
                            'nee' => 'Nee, alles afdrukkken op papier'
                          ), 'liefst'), array('onchange' => 'changeButtons(this.value)'))?>
                      </label>
                    </section>

                    <?php if ($brief_template) : ?>
                      <div style="clear:both"></div>
                      <section class="col col-2">
                        <label class="label">Eenmalig verzenden:</label>
                        <label class="label" id="sjabloon_eenmalig"><?php echo $brief_template->getEenmaligVersturen() ? 'Ja' : 'Nee'; ?></label>
                      </section>
                    <?php endif; ?>

                    <?php if ($edit_template || $choose_template): ?>
                      <div style="clear:both"></div>
                      <section class="col col-6 required">
                        <label class="label">Onderwerp/tekst:</label>
                        <label class="input">
                          <?php
                          include_partial('brief_text_area_vertaalbaar', array(
                            'brief_template'      => $brief_template,
                            'systeemplaceholders' => $systeemplaceholders,
                            'choose_template'     => $choose_template,
                            'edit_template'       => $edit_template,
                            'bestemmeling' => isset($bestemmeling) ? $bestemmeling : null
                          ));
                          ?>
                        </label>
                      </section>
                      <section class="col col-6">
                          <?php if ($is_target && $edit_template) : ?>
                            <label class="label placeholders">Invoegvelden</label>
                            <div id="placeholders" style="overflow: auto; height: 500px; width: 275px; margin-left: 20px;">
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
                      </section>
                    <?php endif; ?>
                    <div style="clear:both"></div>
                    <section class="col col-2">
                      <label class="label">Bijlagen:</label>
                      <label class="input input-file">
                        <span class="button">
                          <?php echo input_file_tag('new_brief_bijlage0', array('class' => 'new_brief_bijlages', 'onchange' => 'this.parentNode.nextElementSibling.value = this.value')) ?>
                          <span class="bladeren">Bladeren</span>
                        </span>
                        <input type="text" class="bladeren" readonly="">
                      </label>
                      <br />
                      <?php echo link_to_function('Bijlage toevoegen', 'bijlageToevoegen()', array('class' => 'bijlage_toevoegen')); ?>
                    </section>
                  </div>
                </fieldset>
              <footer>
              <?php echo button_to_function('Voorbeeld brief', 'postForm("Voorbeeld brief")', array('class' => 'btn btn-default')); // opgelet: de naam van deze knop moet 'voorbeeld' bevatten, hierop wordt getest in de executePrint()' ?>
              <?php echo button_to_function('Voorbeeld e-mail', 'postForm("Voorbeeld e-mail")', array('class' => 'btn btn-default')); // opgelet: de naam van deze knop moet 'voorbeeld' bevatten, hierop wordt getest in de executePrint()' ?>

              <?php echo button_to_function('Brieven afdrukken', 'postForm("brieven afdrukken")', array('class' => 'btn btn-primary briefonly', 'data-type' => 'brieven')); ?>
              <?php echo button_to_function('E-mails verzenden', 'postForm("E-mails verzenden")', array('class' => 'btn btn-primary emailonly', 'data-type' => 'e-mails')); ?>
              </footer>
              </form>
            </div>
          </div>
      </div>
    </article>
  </div>
</section>
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
          <?php echo sfConfig::get('sf_style_smartadmin') ? "jQuery('#mailWindow').dialog({ title: '<div class=\'widget-header\'><h4>Verzonden</h4></div>', width: 700});" : "jQuery('#mailWindow').tt_window({width:'700px'});"; ?>
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
    <?php echo sfConfig::get('sf_style_smartadmin') ? "jQuery(dialog).dialog();" : "jQuery(dialog).tt_window();"; ?>
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
    var cnt = jQuery('input.new_brief_bijlages').size();
    var str = '<label class="input input-file">';
    str += '<span class="button">';
    str += '<input id="new_brief_bijlage' + cnt + '" type="file" value="" name="new_brief_bijlage' + cnt + ' onchange="this.parentNode.nextElementSibling.value = this.value">';
    str += '<span class="bladeren">Bladeren</span> </span><input type="text" class="bladeren" readonly=""></label><br />';

    jQuery('a.bijlage_toevoegen').before(str);
  }
</script>
