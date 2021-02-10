<?php 
/* subscription form */
$hformTitle = get_field("homeFormTextTitle","option");
$hformText = get_field("homeFormTextContent","option");
$gravityFormId = get_field("homeFormShortcode","option");
if($gravityFormId) {
	$gfshortcode = '[gravityform id="'.$gravityFormId.'" title="false" description="false" ajax="true"]';
	if( do_shortcode($gfshortcode) ) { ?>	
	<div class="form-subscribe-blue">
		<div class="form-inside">
		<?php if ($hformTitle) { ?>
			<h3 class="gfTitle"><?php echo $hformTitle ?></h3>
		<?php } ?>
		<?php if ($hformText) { ?>
			<div class="gftxt"><?php echo $hformText ?></div>
		<?php } ?>
		<?php echo do_shortcode($gfshortcode); ?>
		</div>
	</div>
	<?php } ?>
<?php } ?>