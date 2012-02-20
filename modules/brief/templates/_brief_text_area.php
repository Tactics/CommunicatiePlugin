<?php echo input_tag('onderwerp[' . $language_array[0]['culture'] . ']', $brief_template->getOnderwerp(), array(
  'size' => 60, 'id' => 'onderwerp[' . $language_array[0]['culture'] . ']'
)); ?> (Invoegvelden toegestaan) <br /><br />

<?php 
  $html = $brief_template->getHtml();
  if ($is_systeemtemplate)
  {
    $search  = array_keys($systeemvalues);
    $replace = array_values($systeemvalues);
    $html    = str_replace($search, $replace, $html);
  }
  
  echo textarea_tag('html[' . $language_array[0]['culture'] . ']', $html, array(
    'rich' => true,
    'tinymce_options' => $mceoptions,
    'id' => 'html[' . $language_array[0]['culture'] . ']'
  )); 
?>