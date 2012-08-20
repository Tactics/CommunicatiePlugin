<?php include_partial('breadcrumb', array('object' => $object, 'action' => 'Communicatie bewerken'));  ?>
<h2 class="pageblock">Communicatie bewerken</h2>
<div class="pageblock">
  <?php echo form_tag('ttCommunicatie/updateBriefVerzonden', array('name' => 'edit_brief_verzonden')) ?>
    <?php include_partial('global/formvalidationerrors') ?>
    <?php echo input_hidden_tag('brief_verzonden_id', $brief_verzonden->getId()) ?>
    <?php echo input_hidden_tag('object_class', get_class($object)) ?>
    <?php echo input_hidden_tag('object_id', $object->getId()) ?>
    <table class="formtable">
      <tbody>
        <tr>
          <th>Naar:</th>
          <td><?php echo $object->__toString() ?></td>
        </tr>
        <tr class="required <?php if ($sf_request->hasError('medium')) echo ' error'; ?>">
          <th>Medium:</th>
          <td><?php echo select_tag('medium', options_for_select(BriefVerzondenPeer::getMediaOptionsForSelect(), $brief_verzonden->getMedium(), array('include_blank' => true))); ?></td>
        </tr>
        <tr class="required <?php if ($sf_request->hasError('adres')) echo ' error'; ?>">
          <th>Adres:</th>
          <td>
            <?php echo input_tag('adres', $brief_verzonden->getAdres(), array('style' => 'width:400px;')); ?>
          </td>
        </tr>
        <tr>
          <th>&nbsp;</th>
          <td>
            <?php
              if (count($contactFields))
              {
                foreach($contactFields as $contactField)
                {
                  $getter = 'get' . ucFirst(strtolower($contactField));
                  if ($object->$getter())
                  {
                    echo radiobutton_tag('verander_adres', $object->$getter(), false);
                    echo label_for('verander_adres', $object->$getter());
                    echo '<br />';
                  }
                }
              }
            ?>
          </td>
        </tr>
        <tr class="required <?php if ($sf_request->hasError('onderwerp')) echo ' error'; ?>">
          <th>Onderwerp:</th>
          <td><?php echo input_tag('onderwerp', $brief_verzonden->getOnderwerp(), array('style' => 'width:400px;')) ?></td>
        </tr>
        <tr class="required <?php if ($sf_request->hasError('html')) echo ' error'; ?>">
          <th>Info:</th>
          <td><?php echo textarea_tag('html', $brief_verzonden->getHtml(), array('size' => '75x6')) ?></td>
        </tr>
      </tbody>
    </table>
    <?php echo submit_tag('Opslaan') ?>&nbsp;<?php echo button_to_function('Annuleren', 'history.back()'); ?>
  </form>
</div>

<script type="text/javascript">
  jQuery(function($)
  {
    $('input[name="verander_adres"]').change(function()
    {
      $('input#adres').val($(this).val());
    });
  });
</script>