<?php
// $object
// $module_name
// $plurar (if empty = $module_name)
// $module_list_action  (if empty = list)
// $module_show_action  (if empty = show)
if (! isset($module_name))
{
  $module_name = $sf_context->getModuleName();
}
if (! isset($identifier))
{
  $identifier = $module_name;
}
if (!isset($module_list_action))
{
  $module_list_action = 'list';
}
if (!isset($module_show_action))
{
  $module_show_action = 'show';
}


?>

<ul class="breadcrumb">
  <li><?php echo link_to("Home", "@homepage") ?></li>
  <li><?php echo link_to_unless((! isset($object)), $identifier, $module_name . '/' . $module_list_action) ?></li>
	<?php if (isset($object) && $object): ?>
  	<?php if ( $object->getId()) : ?>
	<li><?php echo link_to_unless(! isset($action), $object->__toString(), $module_name . '/' . $module_show_action . '?id=' . $object->getId()) ?></li>
	 <?php else : ?>
	<li>Nieuwe <?php echo ucfirst(strToLower(get_class($object)));?></li>
	 <?php endif ?>
 <?php endif ?>
 <?php if (isset($action) && $action) : ?>
	<li><?php echo $action ?></li>
 <?php endif ?>
</ul>
