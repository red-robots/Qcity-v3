<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package ACStarter
 */

get_header(); ?>
<div class="wrapper">
	<div id="primary" class="content-area-full">
		<main id="main" class="site-main" role="main">

		<?php 

		include( locate_template('template-parts/hero.php') ); 
		include( locate_template('template-parts/sponsored-posts.php') );
		include( locate_template('template-parts/subscribe-bar.php') ); 
		include( locate_template('template-parts/non-sticky-news.php') );
		include( locate_template('template-parts/home-bottom.php') );
		?>
			
		</main><!-- #main -->
	</div><!-- #primary -->
</div>
<?php
get_footer();
