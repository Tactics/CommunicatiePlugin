<div id="dialog-bestemmelingen" style="display:none;">
  <h2>Bestemmelingen</h2>
  <span>
    <?php
    echo label_for('search', 'Zoeken');
    echo '&nbsp;';
    echo input_tag('search');
    ?>
  </span><br /><br />
  <span>
  <?php
  echo checkbox_tag('select_all', 1, 1);
  echo '&nbsp;';
  echo label_for('select_all', 'Selecteer / deselecteer alle');
  ?>
  </span>
  <hr />
  <div style="height: 170px; overflow: auto;">
    <ul style="list-style-type:none;">
      <?php foreach ($bestemmelingen as $objectId => $objectBestemmelingen) : ?>
        <?php foreach ($objectBestemmelingen as $index => $objectBestemmeling) : ?>
          <label class="checkbox">
            <?php
            echo checkbox_tag("bestemmelingen[$objectId][$index]", $index, true, array('class' => 'bestemmeling', 'name' => "bestemmelingen[$objectId][]")); ?>
            <i></i>
            <?php echo '&nbsp;' . $objectBestemmeling->getNaam();
            ?>
          </label>
          </li>
        <?php endforeach; ?>
      <?php endforeach; ?>
    </ul>
  </div>
  <hr />
  <?php echo button_to_function('Opslaan', 'jQuery("#dialog-bestemmelingen").tt_window().close(); countBestemmelingen();');?>
</div>

<script type="text/javascript">
  jQuery(function($){
    $.expr[':'].icontains = function(a, i, m) {
      return jQuery(a).text().toUpperCase().indexOf(m[3].toUpperCase()) >= 0;
    };

    $('#select_all').change(function(){
      $('input.bestemmeling').attr('checked', $(this).is(':checked')).change();
    }).change();

    $('#search').keyup(function(){
      var search = $(this).val();

      $('#dialog-bestemmelingen li').show();
      $('#dialog-bestemmelingen li > label').not(':icontains("' + search + '")').closest('li').hide();
    });    
  });
</script>