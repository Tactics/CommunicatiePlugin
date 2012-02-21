<?php $language_array = BriefTemplatePeer::getTranslationLanguageArray(); ?>

<div id="tabs">
  <ul>
    <?php foreach ($language_array as $language): ?>
      <li><a title="<?php echo $language['label'] ?>" href="#<?php echo $language['label'] ?>"><?php echo $language['label'] ?></a></li>
    <?php endforeach; ?>
  </ul>
  
  <?php foreach ($language_array as $language): ?>
    <div id="<?php echo $language['label'] ?>">
      <?php 
         if (array_key_exists('default', $language) && $language['default'] == true)
         {
           $onderwerp = $sf_params->get('onderwerp[' . BriefTemplatePeer::getCulture($language) . ']') ? $sf_params->get('onderwerp[' . BriefTemplatePeer::getCulture($language) . ']') : '';
           $html      = $sf_params->get('html[' . BriefTemplatePeer::getCulture($language) . ']') ? $sf_params->get('html[' . BriefTemplatePeer::getCulture($language) . ']') :  '';
         }
         else
         {
           $onderwerp = $sf_params->get('onderwerp[' . BriefTemplatePeer::getCulture($language) . ']') ? $sf_params->get('onderwerp[' . BriefTemplatePeer::getCulture($language) . ']') : '';
           $html      = $sf_params->get('html[' . BriefTemplatePeer::getCulture($language) . ']') ? $sf_params->get('html[' . BriefTemplatePeer::getCulture($language) . ']') : '';
         }
         
         if ($brief_template && $brief_template->isSysteemTemplate())
         {
           $search  = array_keys($systeemplaceholders);
           $replace = array_values($systeemplaceholders);
           $html    = str_replace($search, $replace, $html);
         }
         
        echo input_tag('onderwerp[' . BriefTemplatePeer::getCulture($language) . ']', $onderwerp, array(
          'style' => 'width: 434px;'
        ));
        echo ' (Invoegvelden toegestaan) <br /><br />';
      
        echo textarea_tag('html[' . BriefTemplatePeer::getCulture($language) .']', $html);
      ?>
    </div>
  <?php endforeach; ?>
</div>