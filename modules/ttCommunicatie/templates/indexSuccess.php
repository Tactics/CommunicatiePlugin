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

<?php
if(! function_exists('__'))
  \Misc::use_helper('i18n');
?>

<?php echo include_partial('breadcrumb', array()); ?>
<?php echo form_tag("brieven/select", array('id' => 'brieven_form', 'onsubmit' => "toonAanvragen();")); ?>

<h2 class="pageblock"><?php echo __('Brieven afdrukken');?></h2>
<div class="pageblock">
	<fieldset>
		<legend><?php echo __('Parameters');?></legend>
		<table class='formtable'>
			<tr>
				<th><?php echo __('Type brief');?>:</th>
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
			 <td><?php echo __('Brieven worden per 100 afgedrukt');?></td>
      </tr>
		</table>
	</fieldset>
  <?php echo button_to(__("Nieuwe brief"), 'ttCommunicatie/create'); ?>
	&nbsp;<?php echo button_to_function(__("Opmaken brieven"), 'toonAanvragen();'); ?>
</div>

<div id="aanvragen">

</div>