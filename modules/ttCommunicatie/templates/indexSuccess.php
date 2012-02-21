<script>
 function toonAanvragen()
 {
  jQuery.ajax({
    url: '<?php echo url_for('brieven/select');?>',
    type: 'POST',
    cache: false,
    data: jQuery('#brieven_form').serialize(),
    success: function(html)  { 
      jQuery('#aanvragen').html(html);
    }
  }); 
  }
</script>
  
<?php echo include_partial('breadcrumb', array()); ?>
<?php echo form_tag("brieven/select", array('id' => 'brieven_form', 'onsubmit' => "toonAanvragen();")); ?>

<h2 class="pageblock">Brieven afdrukken</h2>
<div class="pageblock">
	<fieldset>
		<legend>Parameters</legend>
		<table class='formtable'>
			<tr>
				<th>Type brief:</th>
				<td>
					<?php echo select_tag('brief_type', objects_for_select(BriefTemplatePeer::doSelect(new Criteria()), 'getId', 'getNaam'));?>
				</td>
			</tr>
			<tr>
			 <th>&nbsp;</th>
			 <td>&nbsp;</td>
      </tr>
			<tr>
			 <th>&nbsp;</th>
			 <td>Brieven worden per 100 afgedrukt</td>
      </tr>
		</table>
	</fieldset>
  <?php echo button_to("Nieuwe brief", 'ttCommunicatie/create'); ?>
	&nbsp;<?php echo button_to_function("Opmaken brieven", 'toonAanvragen();'); ?>
</div>

<div id="aanvragen">

</div>