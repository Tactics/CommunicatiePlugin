<?php slot('breadcrumb');
include_partial('breadcrumb', array('identifier' => 'Briefsjablonen', 'object' => $brief_template));
end_slot();
?>

<script type="text/javascript">
  function insertAtCaret(areaId,text) {
    var txtarea = document.getElementById(areaId);
    var scrollPos = txtarea.scrollTop;
    var strPos = 0;
    var br = ((txtarea.selectionStart || txtarea.selectionStart == '0') ?
        "ff" : (document.selection ? "ie" : false ) );
    if (br == "ie") {
        txtarea.focus();
        var range = document.selection.createRange();
        range.moveStart ('character', -txtarea.value.length);
        strPos = range.text.length;
    }
    else if (br == "ff") strPos = txtarea.selectionStart;

    var front = (txtarea.value).substring(0,strPos);
    var back = (txtarea.value).substring(strPos,txtarea.value.length);
    txtarea.value=front+text+back;
    strPos = strPos + text.length;
    if (br == "ie") {
        txtarea.focus();
        var range = document.selection.createRange();
        range.moveStart ('character', -txtarea.value.length);
        range.moveStart ('character', strPos);
        range.moveEnd ('character', 0);
        range.select();
    }
    else if (br == "ff") {
        txtarea.selectionStart = strPos;
        txtarea.selectionEnd = strPos;
        txtarea.focus();
    }
    txtarea.scrollTop = scrollPos;
}


  function insertPlaceholder(placeholder)
  {
    tinyMCE.execCommand('mceInsertContent', null, '%' + placeholder + '%');
  }

  jQuery(function($)
  {
    //string escaping
    function escapeStr(str)
    {
      if (str)
        return str.replace(/([ !"#$%&'()*+,.\/:;<=>?@[\\\]^`{|}~])/g,'\\\\$1')

      return str;
    }

    // language tabs initialiseren
    <?php echo sfConfig::get('sf_style_smartadmin') ? "$('#tabs').tabs();" : "$('#tabs').tt_tabs();"; ?>

    tinyMCE.init({
      selector: "textarea",
      width : "600",
      height : "454",
      language : "nl",
      plugins: [
        "paste, link, pagebreak, table, jbimages, code"
      ],
      pagebreak_separator : "%pagebreak%",
      toolbar: "undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent",
      relative_urls: false
    });

    $('#bestemmelingen :checkbox').change(function()
    {
      // Geef placeholders van de verschillende targets weer
      $('#placeholders #target_' + escapeStr($(this).val())).toggleClass('active', this.checked);

      // Duid aan welke placeholders niet door alle targets ondersteund worden
      var shared = new Object();
      var first = true;

      $('#placeholders > div.active > ul').each(function(){
        if (first)
        {
          $(this).find('li.placeholder').each(function(){
            shared[$(this).text()] = this;
          });

          first = false;
        }
        else
        {
          var newShared = {};

          $(this).find('li.placeholder').each(function(){
            if (shared[$(this).text()] !== undefined)
            {
              newShared[$(this).text()] = this;
            }
          });

          shared = newShared;
        }
      });

      // duid placeholders aan die niet door alle bestemmelingen ondersteund worden als "unsupported"
      $('#placeholders div.active li.placeholder').each(function(){
        $(this).toggleClass('unsupported', shared[$(this).text()] === undefined);
      });

      // update the lijst met supported placeholders
      $('#placeholders_summary').html('<ul></ul>');
      for(var i in shared)
      {
        $('#placeholders_summary > ul').append(shared[i].cloneNode(true));
      }

      if (! $('#placeholders_summary ul li').size())
      {
        $('#placeholders_summary').html($('<p>Geen geschikte velden</p>'));
      }

    }).change();

    // Checkbox toggled weergave alle placeholders of korte lijst met supported placeholders
    $('#hideUnsupported').click(function(){
      $('#placeholders').toggle();
      $('#placeholders_summary').toggle();
    });

  });

  function bijlageToevoegen()
  {
    var cnt = jQuery('input.new_brief_bijlages').size();
    var str = '<label class="input input-file">';
    str += '<span class="button">';
    str += '<input class="new_brief_bijlages" id="new_brief_bijlage' + cnt + '" type="file" value="" name="new_brief_bijlage' + cnt + '" onchange="this.parentNode.nextElementSibling.value=this.value">';
    str += '<span class="bladeren">Bladeren</span> </span><input type="text" class="bladeren" readonly="">';
    str += '<a href="#" onclick="clearBijlage(this); return false;" style="position: absolute; left: 285px; top: 8px"><?php echo image_tag("icons/trash_16.gif"); ?></a>';
    str += '</label><br />';

    jQuery('a.bijlage_toevoegen').before(str);
  }

  function clearBijlage(el)
  {
    jQuery(el).parent().find('input.new_brief_bijlages').val('');
    jQuery(el).prev().val('');
  }
</script>

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
          <h2><?php echo $brief_template->getId() ? 'Sjabloon bewerken' : 'Nieuw sjabloon'; ?></h2>
          <span class="jarviswidget-loader">
            <i class="fa fa-refresh fa-spin"></i>
          </span>
        </header>
        <div role="content">
          <div class="widget-body no-padding">
            <?php include_partial('global/formvalidationerrors') ?>
            <?php echo form_tag("ttCommunicatie/update", array('multipart' => true, 'name' => 'edit_template', 'class' => 'smart-form')); ?>
              <?php echo input_hidden_tag('template_id', $brief_template->getId()); ?>
              <fieldset>
                <div class="row">
                  <section class="col col-2">
                    <label class="label">Naam:</label>
                    <label class="input">
                      <?php echo object_input_tag($brief_template, 'getNaam', array('size' => 80, 'disabled' => $brief_template->isSysteemTemplate())); ?>
                      <?php
                      if ($brief_template->isSysteemTemplate())
                      {
                        echo object_input_hidden_tag($brief_template, 'getNaam');
                      }
                      ?>
                    </label>
                  </section>
                  <div style="clear:both"></div>
                  <?php if ($brief_template->isSysteemTemplate()): ?>
                    <section class="col col-2">
                      <label class="label">Systeemnaam:</label>
                      <label class="input"><?php echo $brief_template->getSysteemnaam() ?></label>
                    </section>
                    <div style="clear:both"></div>
                  <?php endif; ?>
                  <section class="col col-6">
                    <label class="label">Communicatie in functie van:</label>
                    <span id="bestemmelingen" class="inline-group">
                      <?php foreach(sfConfig::get('sf_communicatie_targets') as $oClass): ?>
                        <label class="checkbox <?php if ($sf_request->hasError('bestemmelingen')) {echo 'state-error';} ?>">
                          <?php echo checkbox_tag('classes[]', $oClass['class'], in_array($oClass['class'], $brief_template->getBestemmelingArray())); ?>
                          <i></i>
                          <?php echo $oClass['label']; ?>
                        </label>
                      <?php endforeach; ?>
                    </span>
                  </section>
                  <div style="clear:both"></div>
                  <section class="col col-6">
                    <label class="label">Onderwerp/tekst:</label>
                    <label class="input">
                      <?php
                        include_partial('brief_text_area_vertaalbaar', array(
                          'brief_template'      => $brief_template,
                          'systeemplaceholders' => $systeemplaceholders,
                          'choose_template'     => false,
                          'edit_template'       => true
                        ));
//                      ?>
                    </label>
                  </section>
                  <section class="col col-6">
                    <label class="label placeholders">Invoegvelden</label>
                    <label class="checkbox placeholders">
                      <input type="checkbox" id="hideUnsupported">
                      <i></i>
                      Geef enkel invoegvelden weer die <br />door elk type bestemmeling ondersteund worden.
                    </label>
                    <div id="placeholders" style="overflow: auto; height: 430px; width: 275px; margin-left: 20px;">
                      <?php
                        foreach(sfConfig::get('sf_communicatie_targets') as $oClass): ?>
                          <?php if ('Algemeen' == $oClass['class']) :
                            continue;
                          endif; ?>
                          <div id="target_<?php echo $oClass['class']; ?>" style="margin-bottom: 6px;">
                          <strong><?php echo $oClass['label']; ?></strong>
                          <?php
                            $placeholders = eval("return {$oClass['class']}::getPlaceholders();");
                            // Algemene placeholders indien gedefinieerd
                            if (class_exists('Placeholder'))
                            {
                              $placeholders = array_merge($placeholders, array('Algemeen' => Placeholder::getPlaceholders()));
                            }
                          showPlaceholders($placeholders);
                      ?>
                        </div>
                      <?php endforeach; ?>

                      <?php if ($brief_template->isSysteemtemplate() && count($systeemplaceholders) > 0): ?>
                        <div class="system">
                          <strong>Briefspecifieke velden</strong>
                            <ul>
                              <?php foreach ($systeemplaceholders as $systeemplaceholder): ?>
                                <li class="placeholder"><a href="#" onClick="insertPlaceholder('<?php echo $systeemplaceholder ?>'); return false;"><?php echo $systeemplaceholder ?></a></li>
                              <?php endforeach; ?>
                            </ul>
                        </div>
                      <?php endif; ?>
                    </div>
                  </section>
                  <div style="clear:both"></div>
                  <section class="col col-2">
                    <label class="label">Bijlagen:</label>
                    <?php foreach ($brief_template->getBriefBijlages() as $brief_bijlage): ?>
                      <?php $node = DmsNodePeer::retrieveByPK($brief_bijlage->getBijlageNodeId()); ?>
                      <?php echo link_to($node->getDiskName(), 'ttCommunicatie/downloadAttachment?file_id=' . $node->getId()) . ' ' . link_to(image_tag('icons/trash_16.gif'), 'ttCommunicatie/deleteAttachment?file_id=' . $node->getId() . '&brief_bijlage_id=' . $brief_bijlage->getId() , array('title' => 'Verwijderen')) . '<br />'; ?>
                    <?php endforeach; ?>
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
                  <div style="clear:both"></div>
                  <section class="col col-2">
                    <label class="label">Layout:</label>
                    <label class="select">
                      <?php echo select_tag('brief_layout_id', objects_for_select(BriefLayoutPeer::getSorted(), 'getId', 'getNaam', $brief_template->getBriefLayoutId(), array('include_blank' => true, 'class' =>  $sf_request->hasError('brief_layout_id') ? "error" : ""))); ?>
                      <i style="left: 268px !important;"></i>
                    </label>
                  </section>
                  <div style="clear:both"></div>
                  <section class="col col-2">
                    <label class="label">Slechts eenmalig versturen:</label>
                    <label class="select">
                      <?php echo select_tag('eenmalig_versturen', options_for_select(array(0 => 'nee', 1 => 'ja'), $brief_template->getEenmaligVersturen())); ?>
                      <i style="left: 268px !important;"></i>
                    </label>
                  </section>
                  <div style="clear:both"></div>
                  <section class="col col-2">
                    <label class="label">Is publiciteit:</label>
                    <label class="select">
                      <?php echo select_tag('is_publiciteit', options_for_select(array(0 => 'nee', 1 => 'ja'), $brief_template->getIsPubliciteit())); ?>
                      <i style="left: 268px !important;"></i>
                    </label>
                  </section>
                  <?php if (sfConfig::get('sf_communicatie_enable_categories', false)) : ?>
                    <div style="clear:both"></div>
                    <section class="col col-2">
                      <label class="checkbox">
                        <?php echo checkbox_tag('voor_alle_categorieen', 1, ($brief_template->getId() && $brief_template->getCategorie() === null)); ?>
                        <i></i>
                        Voor alle categorieën
                      </label>
                    </section>
                  <?php endif; ?>
                  <div style="clear:both"></div>
                  <section class="col col-2">
                    <label class="label">Unieke bestemmeling:</label>
                    <label class="select">
                      <?php echo select_tag('unieke_bestemmeling', options_for_select(array(0 => 'nee', 1 => 'ja'), $brief_template->getUniekeBestemmeling())); ?>
                      <i style="left: 268px !important;"></i>
                    </label>
                  </section>
                </div>
              </fieldset>
            <footer>
              <?php
              echo input_hidden_tag('language_label');
              // opgelet: de naam van deze knop moet 'voorbeeld' bevatten, hierop wordt getest in de executePrint()'
              echo submit_tag('Voorbeeld brief', array('class' => 'btn btn-default button-target-blank'));
              echo '&nbsp;';
              // opgelet: de naam van deze knop moet 'voorbeeld' bevatten, hierop wordt getest in de executePrint()'
              echo submit_tag('Voorbeeld e-mail', array('class' => 'btn btn-default button-target-blank'));
              echo '&nbsp;';
              echo submit_tag('Opslaan', array('class' => 'btn btn-primary'));
              echo '&nbsp;';
              echo button_to_function('Annuleren', 'history.back();', array('class' => 'btn btn-default '));
              ?>
            </footer>
             </form>
           </div>
          </div>
        </div>
      </article>
    </div>
</section>


<script type="text/javascript">
  jQuery(function($){
    // Afhankelijk van op welke submit tag gebruiker klikt is target _blank.
    $('input[name="commit"]').click(function(){
      var form = $(this).closest('form');

      if ($(this).hasClass('button-target-blank'))
      {
        form.attr('target', '_blank');
      }
      else
      {
        form.removeAttr('target');
      }
    });

    // Geselecteerde taal in input_hidden_tag stoppen
    $('#tabs a').click(function(){
      $('#language_label').val($(this).attr('title'));
    }).click();
  });
</script>
