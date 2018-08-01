<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/2000/REC-xhtml1-200000126/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php $sf_user->getCulture(); ?>" lang="<?php $sf_user->getCulture(); ?>">

<head>
	<?php echo include_http_metas() ?>
	<?php echo include_metas() ?>

	<?php echo include_title() ?>
</head>

<body>
  <?php
      echo $briefVerzonden->getHtml();
  ?>

  <div class="printbox">
    <?php echo input_hidden_tag('brief_verzonden_ids', implode(',', $brief_verzonden_ids)); ?>
    <div style='float:right;text-align:right'>
      <a href="javascript:window.close()"><?php echo image_tag("icons/close.png")?></a>
    </div>

    <center>
    <div style="width: 210px;">
      <table style="width:100%">
        <tr>
          <td style="text-align:center;">
            <?php echo link_to_function(image_tag("/ttCommunicatie/images/icons/printer_32.gif") . '<br/>' . 'Afdrukken', 'voerPrintenUit();');?>
          </td>
          <td style="text-align:center;">
            <?php echo link_to_function(image_tag("/ttCommunicatie/images/icons/close_b_32.gif") . '<br/>Sluit venster', 'window.close();');?>
          </td>
        </tr>
      </table>
    </div>
    </center>
  </div>
  
  <script type="text/javascript">
    var printCompleteCallback = function () {
      if (confirm("Heeft u de brieven correct afgedrukt?\n(Hiermee worden ze als afgedrukt gemarkeerd)"))
      {
        jQuery.ajax({
          url: "<?php echo url_for('ttCommunicatie/bevestigAfdrukken'); ?>",
          type: 'POST',
          data: {
            'hash': '<?php echo $md5hash; ?>',
            'template_id': '<?php echo $brief_template ? $brief_template->getId() : ""; ?>',
            'brief_verzonden_ids': jQuery('#brief_verzonden_ids').val()
          },
          cache: false,
          success: function(l)
          {
            alert('De documenten werden gemarkeerd als afgedrukt.');
          }
        });
      }
    };

    function voerPrintenUit()
    {
      window.print();
      if (window.onafterprint) { //check if browser supports window.onafterprint event handler (Firefox and IE)
        $(window).one("afterprint", printCompleteCallback);
      }
      else { //Use setTimeout solution if onafterprint is not supported (Chrome)
        setTimeout(printCompleteCallback, 0);
      }
    }

    function voerEmailUit(aantal)
    {
      if (confirm('Er worden ' + aantal + ' e-mails verzonden.  Doorgaan met verzenden?'))
      {
        jQuery.ajax({
          url: "<?php echo url_for('ttCommunicatie/verstuurEmail'); ?>",
          type: 'POST',
          cache: false,
          success: function(html)
          {
             alert('Alle e-mails werden verzonden.');
             window.close();
             $('#mailsversturen').hide();
             $('#geenmailsversturen').show();
          }
        });
      }
    }

  </script>
</body>
</html>