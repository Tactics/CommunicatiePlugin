<?php 
        echo object_input_tag($brief_template, 'getOnderwerp', array(
          'size' => 60
        )); 
      ?> (Invoegvelden toegestaan) <br /><br />

<?php 
  if ($is_systeemtempate)
  {
    $search  = array_keys($systeemvalues);
    $replace = array_values($systeemvalues);
    $html    = str_replace($search, $replace, $brief_template->getHtml());
  }
  echo object_textarea_tag('html', $html, array(
    'rich' => true,
    'tinymce_options' => $mceoptions
  )); 
?>