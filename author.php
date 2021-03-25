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
$id = get_the_ID();
$name ='';
$last ='';
$name = get_the_author_meta('first_name');
$last = get_the_author_meta('last_name');
$desc = get_the_author_meta('description');

$photoHelper = get_bloginfo('template_url') . '/images/square.png';		
$authorPhoto = get_field('custom_picture','user_'.get_the_author_meta('ID'));
$size = 'full';
$imgObj = ($authorPhoto) ? wp_get_attachment_image_src($authorPhoto, $size):'';
?>
<div class="author-profile">
	<div class="wrapper">
		<div class="flexwrap">
			<div class="photo">
			<?php if ( $imgObj ) { //echo wp_get_attachment_image( $authorPhoto, $size ); ?>
				<span class="authorpic" style="background-image:url('<?php echo $imgObj[0] ?>')"></span>
			<?php } else { ?>
				<span class="nophoto"><i class="fas fa-user nopicIcon"></i></span>
			<?php } ?>
				<img src="<?php echo $photoHelper ?>" alt="" class="helper">
			</div>
			<div class="authorBio">
				<h1><?php echo $name.' '.$last; ?></h1>
				<?php echo $desc; ?>
			</div>
		</div>
	</div>
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
