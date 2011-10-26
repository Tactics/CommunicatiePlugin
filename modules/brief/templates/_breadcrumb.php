<ul class="breadcrumb">
	<li><?php echo link_to("Home", "@homepage") ?></li>
	<li>&gt; <?php echo link_to_unless((! isset($brief)), "Brieven", "brief/list") ?></li>
</ul>