<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package ACStarter
 */

get_header(); 
$name ='';
$last ='';
$name = get_the_author_meta('first_name');
$last = get_the_author_meta('last_name');
$desc = get_the_author_meta('description');
?>
<div class="author-profile">
	<!-- <div class="wrapper"> -->
		<div class="photo">
			<?php 
			if ( $chooseAuthor != '' ):
				$authorID   = $chooseAuthor['ID'];
				$authorPhoto = get_field( 'custom_picture', 'user_' . $authorID );
			else:
				$authorPhoto = get_field('custom_picture','user_'.get_the_author_meta('ID'));
			endif;
			if ( $authorPhoto ):
				echo wp_get_attachment_image( $authorPhoto, $size );
			endif; //  if photo  ?>
		</div>
		<div class="info">
			<h1><?php echo $name.' '.$last; ?></h1>
			<?php echo $desc; ?>
		</div>
		
	<!-- </div> -->
</div>
<div class="wrapper">
	<div id="primary" class="content-area" >
		<div class="single-page" style="margin: 0 auto">
			<div class="postby">Stories by: <?php echo $name.' '.$last; ?></div>
			<main id="main" class="site-main author-feed" role="main">

				

				<?php
				while ( have_posts() ) : the_post(); ?>

					<?php get_template_part('template-parts/story-block'); ?>

				<?php endwhile; // End of the loop.
				pagi_posts_nav();
				?>

				

			</main><!-- #main -->
		</div>
	</div><!-- #primary -->

<?php get_sidebar(); ?>
</div>
<?php get_footer();
