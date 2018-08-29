<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/2000/REC-xhtml1-200000126/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php $sf_user->getCulture(); ?>" lang="<?php $sf_user->getCulture(); ?>">

<head>
	<?php echo include_http_metas() ?>
	<?php echo include_metas() ?>

	<?php echo include_title() ?>
</head>

<body>
  <?php
    $printAttachements = false;

    $aantal_brieven = 0;
    $aantal_via_email = 0;        
    $brief_verzonden_ids = array();
    
    // een eerste kleine optimalisatie om de stylesheets ($berichtHead) slechts 1x te includen
    $vorigeCss = '';
    
    while (isset($rs) ? $rs->next() : true)
    {
      if ($aantal_brieven > 0)
      {
        echo "\n\n<div class=\"page-break-before\" style=\"page-break-before: always\"></div>\n\n";
      }

      if (isset($rs))
      {
        $object = new $bestemmelingenClass();
        $object->hydrate($rs);
      }
      else if (isset($bestemmelingen_object))
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
      
      // work with copy of culturebrieven
      $tmpCultureBrieven = $cultureBrieven;
      
      // parse If and foreach statements
      $tmpCultureBrieven[$culture]['body'] = BriefTemplatePeer::parseForeachStatements($tmpCultureBrieven[$culture]['body'], $object);
      $tmpCultureBrieven[$culture]['body'] = BriefTemplatePeer::parseIfStatements($tmpCultureBrieven[$culture]['body'], $object);      
      
      // replace placeholders
      $tmpCultureBrieven = BriefTemplatePeer::replacePlaceholdersFromCultureBrieven($tmpCultureBrieven, $object);
      $head = $tmpCultureBrieven[$culture]['head'];
      $onderwerp = $tmpCultureBrieven[$culture]['onderwerp'];
      $body = $tmpCultureBrieven[$culture]['body'];
      $brief = $head . $body;
      
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

  <form id="afdrukkenBijlagen" method="post" action="<?php echo url_for('ttCommunicatie/afdrukkenBijlagen');?>" target="afdrukken_bijlagen">
      <input type="hidden" name="brief_verzonden_ids" value="<?php echo implode(',', $brief_verzonden_ids);?>" />
  </form>

  <script type="text/javascript">
    <?php if(count($brief_verzonden_ids) && (method_exists($object, 'printAttachments')) ? $object->printAttachments() : false):?>
      window.open('', 'afdrukken_bijlagen');
      document.getElementById('afdrukkenBijlagen').submit();
    <?php endif;?>
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
</body>
</html>