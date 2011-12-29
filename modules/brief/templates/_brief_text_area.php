<?php 
        echo object_input_tag($brief_template, 'getOnderwerp', array(
          'size' => 60
        )); 
      ?> (Invoegvelden toegestaan) <br /><br />

<?php 
  echo object_textarea_tag($brief_template, 'getHtml', array(
    'rich' => true,
    'tinymce_options' => $mceoptions
  )); 
?>