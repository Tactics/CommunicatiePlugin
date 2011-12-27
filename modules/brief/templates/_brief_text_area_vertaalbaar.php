<div id="tabs">
  <ul>
    <?php foreach ($language_array as $language): ?>
      <li><a title="<?php echo $language['label'] ?>" href="#<?php echo $language['label'] ?>"><?php echo $language['label'] ?></a></li>
    <?php endforeach; ?>
  </ul>
  
  <?php foreach ($language_array as $language): ?>
    <div id="<?php echo $language['label'] ?>">
      <?php 
        echo object_input_tag($brief_template, 'getOnderwerp', array(
          'size' => 80,
          'name' => 'onderwerp_' . $language['label'],
          'id' => 'onderwerp_' . $language['label']
        )); 
      ?> (Invoegvelden toegestaan) <br /><br />
      <?php 
        echo object_textarea_tag($brief_template, 'getHtml', array(
          'rich' => true,
          'tinymce_options' => $mceoptions,
          'name' => 'html_' . $language['label'],
          'id' => 'html_' . $language['label']
        )); 
      ?>
    </div>
  <?php endforeach; ?>
</div>