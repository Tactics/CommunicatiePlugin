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
         if (! $choose_template && $edit_template)
         {
           if (array_key_exists('default', $language) && $language['default'] == true)
           {
             $onderwerp = $sf_params->get('onderwerp[' . BriefTemplatePeer::getCulture($language) . ']') ? $sf_params->get('onderwerp[' . BriefTemplatePeer::getCulture($language) . ']') : $brief_template->getOnderwerp();
             $html      = $sf_params->get('html[' . BriefTemplatePeer::getCulture($language) . ']') ? $sf_params->get('html[' . BriefTemplatePeer::getCulture($language) . ']') :  $brief_template->getHtml();
           }
           else
           {
             $onderwerp = $sf_params->get('onderwerp[' . BriefTemplatePeer::getCulture($language) . ']') ? $sf_params->get('onderwerp[' . BriefTemplatePeer::getCulture($language) . ']') : $brief_template->getVertaling($brief_template->getOnderwerpSource($language['culture']));
             $html      = $sf_params->get('html[' . BriefTemplatePeer::getCulture($language) . ']') ? $sf_params->get('html[' . BriefTemplatePeer::getCulture($language) . ']') : $brief_template->getVertaling($brief_template->getHtmlSource($language['culture']));;
           }
         }
         // Wanneer er de mogelijkheid is om uit templates te kiezen, onderwerp & html
         // invullen met AJAX script (opmaakSuccess).
         else
         {
           $onderwerp = '';
           $html = '';
         }
         
         // @todo
         // && $sf_context->getActionName() == 'opmaak' is tijdelijke oplossing.
         // Wanneer gebruiker klaar is om mails te versturen is het de bedoeling dat 
         // systeemplaceholders automatisch vervangen worden.
         // Bij het bewerken van een template is dit niet gewenst.
         if ($brief_template && $brief_template->isSysteemTemplate() && $sf_context->getActionName() == 'opmaak')
         {
           $search  = array_keys($systeemplaceholders);
           $replace = array_values($systeemplaceholders);
           $html    = str_replace($search, $replace, $html);
         }
         
        echo input_tag('onderwerp[' . BriefTemplatePeer::getCulture($language) . ']', $onderwerp, array(
          'style' => 'width: 434px;', 'disabled' => !$edit_template, 'class' => $sf_request->hasError('onderwerp') ? 'state-error' : ''
        ));
        echo ' (Invoegvelden toegestaan) <br /><br />';

        // indien 1 bestemmeling, kunnen we de brief reeds parsen
        if (isset($bestemmeling))
        {
          $html = BriefTemplatePeer::parseForeachStatements($html, $bestemmeling);
          $html = BriefTemplatePeer::parseIfStatements($html, $bestemmeling);
          $html = BriefTemplatePeer::replacePlaceholdersFromObject($html, $bestemmeling);
        }
        echo textarea_tag('html[' . BriefTemplatePeer::getCulture($language) .']', $html);
      ?>
    </div>
  <?php endforeach; ?>
</div>