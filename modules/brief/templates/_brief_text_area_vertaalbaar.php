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
           $onderwerp = $brief_template->getOnderwerp();
           $html = $brief_template->getHtml();
         }
         else
         {
           $onderwerp = $brief_template->getVertaling($brief_template->getOnderwerpSource($language['culture']));
           $html      = $brief_template->getVertaling($brief_template->getHtmlSource($language['culture']));
         }

        echo input_tag('onderwerp[' . BriefTemplatePeer::getCulture($language) . ']', 
          $onderwerp,
          array('size' => 80)
         );
         echo '(Invoegvelden toegestaan) <br /><br />';
      
        echo textarea_tag('html[' . BriefTemplatePeer::getCulture($language) .']',
          $html,
          array(
            'rich' => true,
            'tinymce_options' => $mceoptions
          )
        );
      ?>
    </div>
  <?php endforeach; ?>
</div>