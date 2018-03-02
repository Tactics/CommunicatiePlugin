<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/2000/REC-xhtml1-200000126/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php $sf_user->getCulture(); ?>" lang="<?php $sf_user->getCulture(); ?>">

<head>
	<?php echo include_http_metas() ?>
	<?php echo include_metas() ?>

	<?php echo include_title() ?>
</head>

<body>
  <?php
      $objectBestemmelingen = array();
      /** @var BriefVerzonden $briefVerzonden */
      $object = $briefVerzonden->getObject();
      
      $objectBestemmelingen = array($briefVerzonden->getBestemmeling()->getTtCommunicatieBestemmeling());
      
      foreach ($objectBestemmelingen as $index => $bestemmeling)
      {
        $bestemmeling->setObject($object);
        $email = $bestemmeling->getEmailTo();
        
        /** @var BriefTemplate $object_brief_template */
        $object_brief_template = $briefVerzonden->getBriefTemplate();
        /** @var BriefLayout $brief_layout */
        $brief_layout = $object_brief_template->getBriefLayout();
        
        // onderwerp en tekst ophalen
        $onderwerpen = $object_brief_template->getOnderwerpCultureArr();
        $htmls = $object_brief_template->getHtmlCultureArr();
        
        // Culture voor object ophalen
        $culture = BriefTemplatePeer::calculateCulture($bestemmeling);

        $cultureBrieven = array();
        foreach (BriefTemplatePeer::getCultureLabelArray() as $culture => $label)
        {
          $cultureBrieven[$culture] = $brief_layout->getHeadAndBody('brief', $culture, $htmls[$culture]);
          $cultureBrieven[$culture]['onderwerp'] = $onderwerpen[$culture];
        }
        
        // work with copy of culturebrieven
        $tmpCultureBrieven = $cultureBrieven;

        // parse If and foreach statements
        $tmpCultureBrieven[$culture]['body'] = BriefTemplatePeer::parseForeachStatements($tmpCultureBrieven[$culture]['body'], $bestemmeling);
        $tmpCultureBrieven[$culture]['body'] = BriefTemplatePeer::parseIfStatements($tmpCultureBrieven[$culture]['body'], $bestemmeling);

        // replace placeholders
        $defaultPlaceholders = BriefTemplatePeer::getDefaultPlaceholders($bestemmeling, false, false, true);
        if (!$object_brief_template->getIsPubliciteit() && isset($defaultPlaceholders['uitschrijven']))
        {
          unset($defaultPlaceholders['uitschrijven']);
        }
        $tmpCultureBrieven = BriefTemplatePeer::replacePlaceholdersFromCultureBrieven($tmpCultureBrieven, $bestemmeling, $defaultPlaceholders);
        $head = $tmpCultureBrieven[$culture]['head'];
        $onderwerp = $tmpCultureBrieven[$culture]['onderwerp'];
        $body = $tmpCultureBrieven[$culture]['body'];
        $brief = $head . $body;
        
        echo BriefTemplatePeer::clearPlaceholders($brief);
        
        // Log de brief tijdelijk om later te kunnen bevestigen
        if (! $voorbeeld)
        {
          $newBriefVerzonden = new BriefVerzonden();
          $newBriefVerzonden->setObjectClass(get_class($object));
          $newBriefVerzonden->setObjectId($object->getId());
          $newBriefVerzonden->setObjectClassBestemmeling($bestemmeling->getObjectClass());
          $newBriefVerzonden->setObjectIdBestemmeling($bestemmeling->getObjectId());
          $newBriefVerzonden->setBriefTemplate($object_brief_template);
          $newBriefVerzonden->setMedium(BriefverzondenPeer::MEDIUM_PRINT);
          $newBriefVerzonden->setAdres($bestemmeling->getAdres());
          $newBriefVerzonden->setOnderwerp($onderwerp);
          $newBriefVerzonden->setCulture($culture);
          $newBriefVerzonden->setHtml($brief);
          $newBriefVerzonden->setStatus(BriefVerzondenPeer::STATUS_NT_VERZONDEN);
          $newBriefVerzonden->save();

          $brief_verzonden_ids[] = $newBriefVerzonden->getId();
        }

        $aantal_brieven++;
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