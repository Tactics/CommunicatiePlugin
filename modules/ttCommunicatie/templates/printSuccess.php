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

      if (confirm("Heeft u de brieven correct afgedrukt?\n(Hiermee worden ze als afgedrukt gemarkeerd)\nBij 'Ok', wacht tot het venster automatisch gesloten wordt."))
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
      /*background-color: #f5f5f5;*/
    }
    
    body {
      /*
      width: 560px;     
      background-color: white;
      */
    }

  </style>

</head>

<body>

  <?php
    $aantal_brieven = 0;
    $aantal_via_email = 0;        
    $brief_verzonden_ids = array();
    
    // een eerste kleine optimalisatie om de stylesheets ($berichtHead) slechts 1x te includen
    $vorigeCss = '';
    
    while (isset($rs) ? $rs->next() : true)
    {
      if ($aantal_brieven > 0)
      {
        echo "\n\n<div STYLE=\"page-break-before: always\"/>\n\n";
      }

      if (isset($rs))
      {
        $object = new $bestemmelingenClass();
        $object->hydrate($rs);
      }
      else
      {
        $object = $bestemmelingen_object;
      }      
      
      $email = $object->getMailerRecipientMail();
      if (((($verzenden_via == 'liefst') && $object->getMailerPrefersEmail()) || ($verzenden_via == 'altijd')) && $email)      
      {
        $aantal_via_email++;
        if (! $voorbeeld)
        {
          continue;
        }
      }
      
      if (! $brief_template)      
      {
        // brief_template uit object zelf halen
        if (method_exists($object, 'getLayoutEnTemplateId'))
        {
          // template ophalen
          $layoutEnTemplateId = $object->getLayoutEnTemplateId();
          if (isset($layoutEnTemplateId['brief_template_id']) && $layoutEnTemplateId['brief_template_id'])
          {
            $object_brief_template = BriefTemplatePeer::retrieveByPK($layoutEnTemplateId['brief_template_id']);          
            if (! $object_brief_template)
            {
              echo '<font color="red">' . get_class($object) . '&rarr;getLayoutEnTemplateId(): brief_template_id ' . $layoutEnTemplateId['brief_template_id'] . ' niet gevonden.</font><br/>';
              continue;                
            } 
          }
          else
          {
            echo '<font color="red">' . get_class($object) . '&rarr;getLayoutEnTemplateId(): brief_template_id niet opgegeven.</font><br/>';
            continue;  
          }

          // layout ophalen       
          if (isset($layoutEnTemplateId['brief_layout_id']) && $layoutEnTemplateId['brief_layout_id'])
          {
            $brief_layout = BriefLayoutPeer::retrieveByPK($layoutEnTemplateId['brief_layout_id']);
            if (! $brief_layout)
            {
              echo '<font color="red">' . get_class($object) . '&rarr;getLayoutEnTemplateId(): brief_layout_id ' . $layoutEnTemplateId['brief_layout_id'] . ' niet gevonden.</font><br/>';
              continue;
            } 
          }  
          else
          {
            echo '<font color="red">' . get_class($object) . '&rarr;getLayoutEnTemplateId(): brief_layout_id niet opgegeven.</font><br/>';
            continue;  
          }
        }
        else
        {
          echo '<font color="red">' . get_class($object) . '&rarr;getLayoutEnTemplateId(): method niet gevonden.</font><br/>';
          continue;            
        }  

        // onderwerp en tekst ophalen
        $onderwerpen = $object_brief_template->getOnderwerpCultureArr();
        $htmls = $object_brief_template->getHtmlCultureArr();

        $cultureBrieven = array();
        foreach (BriefTemplatePeer::getCultureLabelArray() as $culture => $label)
        {
          $cultureBrieven[$culture] = $brief_layout->getHeadAndBody($emailLayout ? 'mail' : 'brief', $culture, $htmls[$culture], $emailverzenden);
          $cultureBrieven[$culture]['onderwerp'] = $onderwerpen[$culture];      
        } 
      }
      else
      {
        $object_brief_template = $brief_template;
      }
      
      // sommige brieven mogen slechts eenmalig naar een object_class/id gestuurd worden
      if (! $voorbeeld && $object_brief_template->getEenmaligVersturen() && $object_brief_template->ReedsVerstuurdNaar($bestemmelingenClass, $object->getId()))
      {
        continue;
      }
      
      if (! $brief_layout)
      {
        echo "<font color=red>{$bestemmelingenClass} (id: {$object->getId()}): BriefLayout (id: {$layoutEnTemplateId['brief_layout_id']}) niet gevonden.</font><br/>";
        continue;
      }
      
      
      
      // Culture voor object ophalen
      $culture = BriefTemplatePeer::calculateCulture($object);
      
      // Adres ophalen als placeholder
      $defaultPlaceholders = array_merge($defaultPlaceholders, array(
          'bestemmeling_adres' => nl2br($object->getAdres())
      ));
           
      // nodige placeholders uit template halen
      $usedPlaceholders = array();
      if(preg_match_all('/\%([A-Za-z0-9_:]+)\%/', $cultureBrieven[$culture]['onderwerp'] . $cultureBrieven[$culture]['body'], $matches)) {
          $usedPlaceholders = $matches[1];
      }
      
      // replace the placeholders
      
      $placeholders = $is_target 
        ? array_merge($object->fillPlaceholders($usedPlaceholders, $culture), $defaultPlaceholders)
        : $defaultPlaceholders;      
      $onderwerp = BriefTemplatePeer::replacePlaceholders($cultureBrieven[$culture]['onderwerp'], $placeholders);
      $placeholders['onderwerp'] = $onderwerp;
      $body = BriefTemplatePeer::replacePlaceholders($cultureBrieven[$culture]['body'], $placeholders);
      $brief = $cultureBrieven[$culture]['head'] . $body;
      
      // voorbeeld melding om te vermijden dat dit wordt gebruikt om effectief af te drukken
      if ($voorbeeld)
      {
        $watermerkDiv = '
          <div style="width: 560px; position: absolute; left: 50px; top: 450px; -moz-transform: rotate(-300deg); font-size:80px; color:red; opacity:0.4">
            VOORBEELD
          </div>
        ';

        $brief = $watermerkDiv . $brief;
      }      

      echo "<!-- Brief " . $aantal_brieven . "-->\n";
      echo BriefTemplatePeer::clearPlaceholders($brief);
      
      // Log de brief tijdelijk om later te kunnen bevestigen
      if (! $voorbeeld)
      {
        $bestemmeling = null;
        if (method_exists($object, 'getBestemmeling'))
        {
          $bestemmeling =  $object->getBestemmeling();
        }                   
        
        $briefVerzonden = new BriefVerzonden();
        $briefVerzonden->setObjectClass(get_class($object));
        $briefVerzonden->setObjectId($object->getId());
        $briefVerzonden->setObjectClassBestemmeling(isset($bestemmeling) ? get_class($bestemmeling) : null);
        $briefVerzonden->setObjectIdBestemmeling(isset($bestemmeling) ? $bestemmeling->getId() : null);
        $briefVerzonden->setBriefTemplate($object_brief_template);
        $briefVerzonden->setMedium(BriefverzondenPeer::MEDIUM_PRINT);
        $briefVerzonden->setAdres($object->getAdres());
        $briefVerzonden->setOnderwerp($onderwerp);
        $briefVerzonden->setCulture($culture);
        $briefVerzonden->setHtml($brief);
        $briefVerzonden->setStatus(BriefVerzondenPeer::STATUS_NT_VERZONDEN);
        $briefVerzonden->save();

        $brief_verzonden_ids[] = $briefVerzonden->getId();
      }      
      
      $aantal_brieven++;      
      
      if (isset($bestemmelingen_object))
      {
        break;
      }
    }

    if (! $aantal_brieven)
    {
      echo '<p>Er zijn geen brieven te versturen.</p>';
      echo '</div>';
    }
  ?>

  <div class="printbox">        
    <?php echo input_hidden_tag('brief_verzonden_ids', implode(',', $brief_verzonden_ids)); ?>    
    <div style='float:right;text-align:right'>
      <a href="javascript:window.close()"><?php echo image_tag("icons/close.png")?></a>
      <br/>
      <br/>
      <br/>
      <span style="font-size: 9px;">Totaal aantal <?php echo $viaemail ? 'e-mails: ' . $aantal_via_email : 'brieven: ' . $aantal_brieven; ?></span>
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