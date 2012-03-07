<?php include_partial('breadcrumb', array('identifier' => 'Brieven', 'object' => $brief_template)); ?>

<style type="text/css">
  li.unsupported a { color: grey }

  #placeholders > div {display: none;}
  #placeholders > div.active {display: block;}
</style>

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
    
    $('#bestemmelingen :checkbox').change(function()
    {
      // Geef placeholders van de verschillende targets weer
      $('#placeholders #target_' + $(this).val()).toggleClass('active', this.checked);

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
    var  str = '<input id="new_brief_bijlage' + cnt + '" type="file" value="" name="new_brief_bijlage' + cnt + '">';
    
    jQuery('a.bijlage_toevoegen').before(str);
    jQuery('a.bijlage_toevoegen').before('<br />');
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

<h2 class="pageblock"><?php echo $brief_template->getId() ? 'Sjabloon bewerken' : 'Nieuw sjabloon'; ?></h2>
<div class="pageblock">
  <?php include_partial('global/formvalidationerrors') ?>  
  
  <?php echo form_tag("ttCommunicatie/update", array('multipart' => true, 'name' => 'edit_template')); ?>  
  <?php echo input_hidden_tag('template_id', $brief_template->getId()); ?>
  <table class="formtable">
    <tr <?php if ($sf_request->hasError('naam')) {echo 'class="error"';} ?>>
      <th>Naam:</th>
      <td>
        <?php echo object_input_tag($brief_template, 'getNaam', array('size' => 80, 'disabled' => $brief_template->isSysteemTemplate())); ?>
        <?php 
          if ($brief_template->isSysteemTemplate())
          {
            echo object_input_hidden_tag($brief_template, 'getNaam');
          }
        ?>
      </td>
    </tr>
    <?php if ($brief_template->isSysteemTemplate()): ?>
    <tr>
      <th>Systeemnaam:</th>
      <td><?php echo $brief_template->getSysteemnaam() ?></td>
    </tr>
    <?php endif; ?>
    <tr>
      <th>&nbsp;</th>
      <td>&nbsp;</td>
    </tr>
    <tr <?php if ($sf_request->hasError('bestemmelingen')) {echo 'class="error"';} ?>>
      <th>Mogelijke bestemmelingen:</th>
      <td id="bestemmelingen">
        <?php foreach(sfConfig::get('sf_communicatie_targets') as $oClass): ?>
        <label>
          <?php echo checkbox_tag('classes[]', $oClass['class'], in_array($oClass['class'], $brief_template->getBestemmelingArray()), array('disabled' => $brief_template->isSysteemTemplate())); ?>
          <?php echo $oClass['label']; ?>
        </label>
        <?php endforeach; ?>
      </td>
    </tr>    
    <tr>
      <th>&nbsp;</th>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <th>Onderwerp/tekst:</th>
      <td>
        <table>
          <tr <?php if ($sf_request->hasError('onderwerp')) {echo 'class="error"';} ?>>
            <td>
              <?php                 
                include_partial('brief_text_area_vertaalbaar', array(
                  'brief_template'      => $brief_template,                                   
                  'systeemplaceholders' => $systeemplaceholders,
                  'choose_template'     => false
                ));                            
              ?>
            </td>
            <td>
              <h2 class="pageblock" style="margin-left: 20px; width: 280px;">Invoegvelden</h2>
              <div class="pageblock" style="width: 275px; margin-left: 20px;margin-bottom: 0px;border-bottom: 0px;">
                <label><input type="checkbox" id="hideUnsupported"> Geef enkel invoegvelden weer die door elk type bestemmeling ondersteund worden.</label>
              </div>

              <div id="placeholders_summary" class="pageblock" style="overflow: auto; height: 430px; width: 275px; margin-left: 20px; display:none;"></div>
                <div id="placeholders" class="pageblock" style="overflow: auto; height: 430px; width: 275px; margin-left: 20px;">
                  <?php 
                  foreach(sfConfig::get('sf_communicatie_targets') as $oClass): ?>
                    <div id="target_<?php echo $oClass['class']; ?>" style="margin-bottom: 6px;">
                      <strong><?php echo $oClass['label']; ?></strong>
                      <?php  
                        $placeholders = eval("return {$oClass['class']}::getPlaceholders();");                        
                        showPlaceholders($placeholders);                          
                      ?>
                    </div>
                  <?php endforeach; ?>
                  </div>
                <?php if ($briefTemplate->isSysteemtemplate() && count($systeemplaceholders) > 0): ?>
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
            </td>
          </tr>
        </table>
    </tr>
     <tr>
      <th>&nbsp;</th>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <th>Bijlagen:</th>
      <td>
        <?php foreach ($brief_template->getBriefBijlages() as $brief_bijlage): ?>
          <?php $node = DmsNodePeer::retrieveByPK($brief_bijlage->getBijlageNodeId()); ?>
          <?php echo link_to($node->getDiskName(), 'ttCommunicatie/downloadAttachment?file_id=' . $node->getId()) . ' ' . link_to(image_tag('icons/trash_16.gif'), 'ttCommunicatie/deleteAttachment?file_id=' . $node->getId() . '&brief_bijlage_id=' . $brief_bijlage->getId() , array('title' => 'Verwijderen')) . '<br />'; ?>
        <?php endforeach; ?>
        <?php echo input_file_tag('new_brief_bijlage0', array('class' => 'new_brief_bijlages')) ?> <br />
        <?php echo link_to_function('Bijlage toevoegen', 'bijlageToevoegen()', array('class' => 'bijlage_toevoegen')); ?>
      </td>
    </tr>
    <tr <?php if ($sf_request->hasError('brief_layout_id')) {echo 'class="error"';} ?>>
      <th>Layout:</th>
      <td>
        <?php echo select_tag('brief_layout_id', objects_for_select(BriefLayoutPeer::getSorted(), 'getId', 'getNaam', $brief_template->getBriefLayoutId(), array('include_blank' => true))); ?>
      </td>
    </tr>
    <tr>
      <th>Slechts eenmalig versturen:</th>
      <td>        
        <?php echo select_tag('eenmalig_versturen', options_for_select(array(0 => 'nee' , 1 => 'ja'), $brief_template->getEenmaligVersturen())); ?>
      </td>
    </tr>
  </table>
  <hr>
  <?php echo submit_tag('Opslaan'); ?>
  &nbsp;<?php echo button_to_function('Annuleren', 'history.back();'); ?>
   </form>
</div>
