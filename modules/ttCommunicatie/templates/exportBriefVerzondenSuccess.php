<?php
if(! function_exists('__'))
  \Misc::use_helper('I18N');
?>

<table>
  <thead>
    <tr>
      <th><?php echo __('Template');?></th>
      <th><?php echo __('Object class');?></th>
      <th><?php echo __('Object id');?></th>
      <th><?php echo __('Medium');?></th>
      <th><?php echo __('Onderwerp');?></th>
      <th><?php echo __('Status');?></th>
      <th><?php echo __('Datum verzonden');?></th>
      <th><?php echo __('Bestemmeling');?></th>
      <th><?php echo __('E-mail');?></th>
    </tr>
  </thead>
  <tbody>
    <?php foreach($pager->getResults() as $briefVerzonden):
      $object = eval('return ' . $briefVerzonden->getObjectClass() . 'Peer::retrieveByPK(' . $briefVerzonden->getObjectId() . ');');

      $refObjectId = $refObjectClass = '';
      if($object)
      {
        $refObjectId = $object->getId();
        $refObjectClass = get_class($object);
      }
      if ($object && method_exists($object, 'getMailerRefObjectId'))  $refObjectId = $object->getMailerRefObjectId();
      if ($object && method_exists($object, 'getMailerRefObjectClass'))  $refObjectClass = $object->getMailerRefObjectClass();

      ?>
    <tr>
      <td><?php
        $template = BriefTemplatePeer::retrieveCachedByPK($briefVerzonden->getBriefTemplateId());
        echo $template->getNaam();?>
      </td>
      <td><?php echo $refObjectClass;?></td>
      <td><?php echo $refObjectId;?></td>
      <td><?php echo $briefVerzonden->getMedium();?></td>
      <td><?php echo $briefVerzonden->getOnderwerp();?></td>
      <td><?php echo $briefVerzonden->getStatus();?></td>
      <td><?php echo $briefVerzonden->getCreatedAt('d/m/Y');?></td>
      <td><?php
        $bestemmeling = $object;
        if ($object && method_exists($object, 'getBestemmeling'))  $bestemmeling = $object->getBestemmeling();

        echo $bestemmeling ? $bestemmeling->getMailerRecipientName() : '';
        ?></td>
      <td><?php echo $briefVerzonden->getAdres();?></td>
    </tr>
    <?php endforeach;?>
  </tbody>
</table>