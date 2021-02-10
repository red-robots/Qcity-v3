<?php 
if ( shortcode_exists( 'instagram-feed' ) ) {
	if (do_shortcode('[instagram-feed]')) { ?>
	<div class="home-instagram-feeds">
	    <div class="wrapper">
	        <?php echo do_shortcode('[instagram-feed]'); ?>
	    </div>
	</div> 
	<?php } ?>
<?php } ?>
