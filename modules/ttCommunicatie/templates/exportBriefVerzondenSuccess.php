<table>
  <thead>
    <tr>
      <th>Template</th>
      <th>Object class</th>
      <th>Object id</th>
      <th>Medium</th>
      <th>Onderwerp</th>
      <th>Status</th>
      <th>Bestemmeling</th>
      <th>E-mail</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach($pager->getResults() as $briefVerzonden):?>
    <tr>
      <td><?php
        $template = BriefTemplatePeer::retrieveCachedByPK($briefVerzonden->getBriefTemplateId());
        echo $template->getNaam();?>
      </td>
      <td><?php echo $briefVerzonden->getObjectClass();?></td>
      <td><?php echo $briefVerzonden->getObjectId();?></td>
      <td><?php echo $briefVerzonden->getMedium();?></td>
      <td><?php echo $briefVerzonden->getOnderwerp();?></td>
      <td><?php echo $briefVerzonden->getStatus();?></td>
      <td><?php

        $object = eval('return ' . $briefVerzonden->getObjectClass() . 'Peer::retrieveByPK(' . $briefVerzonden->getObjectId() . ');');

        $bestemmeling = $object;
        if ($object && method_exists($object, 'getBestemmeling'))
        {
          $bestemmeling = $object->getBestemmeling();
        }

        echo $bestemmeling ? $bestemmeling->getMailerRecipientName() : '';
        ?></td>
      <td><?php echo $briefVerzonden->getAdres();?></td>
    </tr>
    <?php endforeach;?>
  </tbody>
</table>