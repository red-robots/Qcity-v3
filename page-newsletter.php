<?php
/**
 * Template Name: Newsletter (new form)
 *
 */

get_header(); ?>
<div class="wrapper">
	<div id="primary" class="content-area-full newsletter-page-new">
		<main id="main" class="site-main" role="main">

			<?php while ( have_posts() ) : the_post(); ?>
				<div style="display:none;">
					<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
				</div>
				<?php if ( get_the_content() ) { ?>
				<div class="entry-content"><?php the_content(); ?></div>
				<?php } ?>
			<?php endwhile;  ?>


			<?php if( $newsletter_form_code = get_field("newsletter_form_code") ) { ?>
			<div class="newsletter-form-code">
				<?php echo $newsletter_form_code; ?>
			</div>
			<?php } ?>

		</main><!-- #main -->
	</div><!-- #primary -->
</div>

<?php $nDesc = get_field("newsletter_option_description"); ?>
<script>
	jQuery(document).ready(function($){
		<?php if($nDesc) { ?>
			var newsletterText = JSON.parse('<?php echo json_encode($nDesc) ?>');
			$("#newsletter-options li").each(function(k,v){
				var target = $(this);
				if( typeof $(newsletterText)[k] !='undefined' && $(newsletterText)[k]!=null ) {
					target.append('<div class="option-description">'+$(newsletterText)[k].text+'</div>');
				}
				target.find("label").show();
			});
		<?php } ?>
	});
</script>
<?php get_footer();
