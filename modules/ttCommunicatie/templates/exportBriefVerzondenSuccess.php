<table>
  <thead>
    <tr>
      <th>Template</th>
      <th>Object class</th>
      <th>Object id</th>
      <th>Medium</th>
      <th>Onderwerp</th>
      <th>Status</th>
      <th>Datum verzonden</th>
      <th>Bestemmeling</th>
      <th>E-mail</th>
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