<?php echo object_input_tag($brief_template, 'getOnderwerp', array(
  'size' => 60
)); ?> (Invoegvelden toegestaan) <br /><br />

<?php 
  $html = $brief_template->getHtml();
  if ($is_systeemtemplate)
  {
    $search  = array_keys($systeemvalues);
    $replace = array_values($systeemvalues);
    $html    = str_replace($search, $replace, $html);
  }
  
  echo textarea_tag('html', $html, array(
    'rich' => true,
    'tinymce_options' => $mceoptions
  )); 
?>