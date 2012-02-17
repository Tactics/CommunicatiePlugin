<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/2000/REC-xhtml1-200000126/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php $sf_user->getCulture(); ?>" lang="<?php $sf_user->getCulture(); ?>">

<head>
	<?php echo include_http_metas() ?>
	<?php echo include_metas() ?>

	<?php echo include_title() ?>

   <script type="text/javascript">
    function voerPrintenUit()
    {
      window.print();

      <?php if (! $voorbeeld): ?>

      if (confirm("Heeft u de <?php echo $type ?> correct afgedrukt?\n(Hiermee worden ze als afgedrukt gemarkeerd)\nBij 'Ok', wacht tot het venster automatisch gesloten wordt."))
      {
        jQuery.ajax({
          url: "<?php echo url_for('brief/bevestigAfdrukken'); ?>",
          type: 'POST',
          data: {
            'onderwerp': jQuery('#onderwerp').val(),
            'template_ids': jQuery('#template_ids').val(),
            'layout_ids': jQuery('#layout_ids').val(),
            'object_ids': jQuery('#object_ids').val(),
            'html': jQuery('#html').val()
          },
          cache: false,
          success: function(l)
          {
             alert('De documenten werden gemarkeerd als afgedrukt.');
             //window.close();
          }
        });
      }

      <?php endif; ?>
    }

    function voerEmailUit(aantal)
    {
      if (confirm('Er worden ' + aantal + ' e-mails verzonden.  Doorgaan met verzenden?'))
      {
        jQuery.ajax({
          url: "<?php echo url_for('brief/verstuurEmail'); ?>",
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

  

	<style type="text/css">
    .printbox td
    {
			font-size: 10px !important;
			font-family: verdana !important;
    }
    
    .printbox
    {
			position: fixed !important;
			opacity: 0.9 !important;
			bottom: 0px !important;
			right: 0px !important;
			left: 0px !important;
			background-color: #EEEEEE !important;
			border: 1px solid black !important;
			text-align: center !important;
			padding: 4px !important;
			font-size: 10px !important;
			font-family: verdana !important;
		}

		.printbox a {
			text-decoration: none !important;
		}

		.printbox a img {
			border: 0px !important;
		}
	</style>

	<style media="print">
	  .printbox, #sfWebDebug {
			display:none !important;
		}
	</style>

  <style media="screen">
    html {
      background-color: #f5f5f5;
    }
    
    body {
      width: 560px;
      background-color: white;
    }

  </style>

</head>

<body>

  <?php
    $aantal_brieven = 0;
    $aantal_via_email = 0;
    $object_ids = array();
    $template_ids = array();
    $layout_ids = array();
   
    // een eerste kleine optimalisatie om de stylesheets ($berichtHead) slechts 1x te includen
    $vorigeCss = '';
    
    while ($rs->next())
    {
      if ($aantal_brieven > 0)
      {
        echo "\n\n<div style=\"page-break-before: always\"></div>\n\n";
      }

      $object = new $bestemmelingenClass();
      $object->hydrate($rs);

      if ($viaemail && $object->getMailerPrefersEmail())
      {
        $aantal_via_email++;
        if (! $voorbeeld)
        {
          continue;
        }
      }
      
      if (! isset($brief_template))
      {
        $layoutEnTemplateId = $object->getLayoutEnTemplateId();
        if (! $layoutEnTemplateId)
        {
          echo "<font color=red>brief_layout_id en brief_template_id niet gevonden voor {$bestemmelingenClass} (id: {$object->getId()})</font><br/>";
          continue;
        }
        $brief_template = BriefTemplatePeer::retrieveByPK($layoutEnTemplateId['brief_template_id']);
        if (! $brief_template)
        {
          echo "<font color=red>{$bestemmelingenClass} (id: {$object->getId()}): BriefTemplate (id: {$layoutEnTemplateId['brief_template_id']}) niet gevonden.</font><br/>";
          continue;
        }
        $brief_layout = BriefLayoutPeer::retrieveByPK($layoutEnTemplateId['brief_layout_id']);       
        $html = BriefTemplatePeer::getBerichtHtml($brief_template, $viaemail, $brief_template->getHtml(), $brief_layout, $emailverzenden);
      }
      
      // sommige brieven mogen slechts eenmalig naar een object_class/id gestuurd worden
      if (! $voorbeeld && $brief_template->getEenmaligVersturen() && $brief_template->ReedsVerstuurdNaar($bestemmelingenClass, $object->getId()))
      {
        continue;
      }
      
      $brief_layout = isset($brief_layout) ? $brief_layout : BriefLayoutPeer::retrieveByPK($brief_template->getBriefLayoutId());
      if (! $brief_layout)
      {
        echo "<font color=red>{$bestemmelingenClass} (id: {$object->getId()}): BriefLayout (id: {$layoutEnTemplateId['brief_layout_id']}) niet gevonden.</font><br/>";
        continue;
      }
      
      $template_ids[] = $brief_template->getId();  
      $layout_ids[] = $brief_layout->getId();

      $onderwerp = $brief_template->getOnderwerp();
      
      // Knip het resulterende document op in stukken zodat we meerdere
      // brieven kunnen afdrukken zonder foute HTML te genereren (meerdere HEAD / BODY blokken)
      $berichtHead = BriefTemplatePeer::getBerichtHead($html);
      $berichtBody = BriefTemplatePeer::getBerichtBody($html);
      
      /* Brieftemplate HEAD (CSS) */
      // @todo: te optimaliseren: slechts 1maal zelfde includen //
      if ($berichtHead != $vorigeCss)
      {
        echo $berichtHead;
        $vorigeCss = $berichtHead;
      }      
      
      // replace the placeholders
      $values = array_merge($object->fillPlaceholders(), $defaultPlaceholders);
      $values['onderwerp'] = BriefTemplatePeer::replacePlaceholders($onderwerp, $values);      

      $brief = BriefTemplatePeer::replacePlaceholders($berichtBody, $values);
      $brief = BriefTemplatePeer::clearPlaceholders($brief);

      echo "<!-- Brief " . $aantal_brieven . "-->\n";
      echo $brief;

      $object_ids[] = $object->getId();
      $aantal_brieven++;
    }

    if (! $aantal_brieven)
    {
      echo '<p>Er zijn geen brieven te versturen.</p>';
      echo '</div>';
    }
  ?>

  <div class="printbox">
    <?php echo input_hidden_tag('onderwerp', isset($onderwerp) ? $onderwerp : ''); ?>
    <?php echo input_hidden_tag('html', isset($html) ? $html : ''); ?>
    <?php echo input_hidden_tag('template_ids', implode(',', $template_ids)); ?>
    <?php echo input_hidden_tag('layout_ids',implode(',', $layout_ids)); ?>
    <?php echo input_hidden_tag('object_ids', implode(',', $object_ids)); ?>

    <div style='float:right;text-align:right'>
      <a href="javascript:window.close()"><?php echo image_tag("icons/close.png")?></a>
      <br/>
      <br/>
      <br/>
      <span style="font-size: 9px;">Totaal aantal <?php echo $viaemail ? 'e-mails' : 'brieven'; ?>: <?php echo $aantal_brieven ?></span>
    </div>

    <center>
    <div style="width: 210px;">
      <table style="width:100%">
        <tr>
          <?php /*
          <td style="text-align:center;">
            <?php if ($aantal_via_email) : ?>
              <span id="mailsversturen">
                <?php echo link_to_function(image_tag("icons/mail_32.png") . '<br/>' . ($voorbeeld ? 'Voorbeeld e-mail versturen' : ($aantal_via_email . ' e-mail(s) versturen')), 'voerEmailUit(' . $aantal_via_email . ');');?>
              </span>
              <span id="geenmailsversturen" style="display:none;">
                <?php echo image_tag('icons/mail_info_disabled_32.gif') . '<br/>Geen e-mails te verzenden.'; ?>
              </span>
            <?php else : ?>
              <?php echo image_tag('icons/mail_info_disabled_32.gif') . '<br/>Geen e-mails te verzenden.'; ?>
            <?php endif; ?>
          </td>
           */ ?>
          <?php if (count($aantal_brieven)) : ?>
            <td style="text-align:center;">
              <?php echo link_to_function(image_tag("/ttCommunicatie/images/icons/printer_32.gif") . '<br/>' . ($voorbeeld ? 'Voorbeeld afdrukken' : 'Afdrukken'), 'voerPrintenUit();');?>
            </td>
          <?php endif; ?>
          <td style="text-align:center;">
            <?php echo link_to_function(image_tag("/ttCommunicatie/images/icons/close_b_32.gif") . '<br/>Sluit venster', 'window.close();');?>
          </td>
        </tr>
      </table>
    </div>
    </center>
  </div>

</body>
</html>