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
           $onderwerp = $sf_params->get('onderwerp[' . BriefTemplatePeer::getCulture($language) . ']') ? $sf_params->get('onderwerp[' . BriefTemplatePeer::getCulture($language) . ']') : $brief_template->getOnderwerp();
           $html      = $sf_params->get('html[' . BriefTemplatePeer::getCulture($language) . ']') ? $sf_params->get('html[' . BriefTemplatePeer::getCulture($language) . ']') :  $brief_template->getHtml();
         }
         else
         {
           $onderwerp = $sf_params->get('onderwerp[' . BriefTemplatePeer::getCulture($language) . ']') ? $sf_params->get('onderwerp[' . BriefTemplatePeer::getCulture($language) . ']') : $brief_template->getVertaling($brief_template->getOnderwerpSource($language['culture']));
           $html      = $sf_params->get('html[' . BriefTemplatePeer::getCulture($language) . ']') ? $sf_params->get('html[' . BriefTemplatePeer::getCulture($language) . ']') : $brief_template->getVertaling($brief_template->getHtmlSource($language['culture']));
         }

        echo input_tag('onderwerp[' . BriefTemplatePeer::getCulture($language) . ']', 
          $onderwerp,
          array('size' => 60, 'id' => 'onderwerp[' . BriefTemplatePeer::getCulture($language) . ']')
         );
         echo ' (Invoegvelden toegestaan) <br /><br />';
      
        echo textarea_tag('html[' . BriefTemplatePeer::getCulture($language) .']',
          $html,
          array(
            'rich' => true,
            'tinymce_options' => $mceoptions,
            'id' => 'html[' . BriefTemplatePeer::getCulture($language) .']'
          )
        );
      ?>
    </div>
  <?php endforeach; ?>
</div>