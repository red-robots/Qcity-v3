<?php
/**
 * Template Name: Map Page
 */
get_header(); ?>
<div id="primary" class="content-area-full map-page top-logo-page">
	<main id="main" class="site-main" role="main">

		<?php while ( have_posts() ) : the_post(); ?>
		<?php 
			$poweredby = get_field("poweredby");
			$logo = get_field("top_logo");
			$logo_website = get_field("top_logo_website"); 
			$link_open = '';
			$link_close = '';
			if($logo_website) {
				$link_open = '<a href="'.$logo_website.'" target="_blank">';
				$link_close = '</a>';
			}

			$placeholder = get_bloginfo("template_url") . "/images/rectangle.png"; 

			$views = '';
			$views_display = '';
			if(function_exists('the_views')) {
				ob_start();
				the_views(); 
				$views = ob_get_contents();
				ob_clean();
				if($views) {
					$views = preg_replace('/[^0-9.]+/', '', $views);
				}
			}
			if($views) { 
				ob_start(); ?>
				<span class="postViews business-map-counter">
					<span>
						<em class="e1">Visitor Count</em>
						<em class="e2"><?php echo $views ?></em>
					</span>
				</span>
			<?php 
				$views_display = ob_get_contents();
				ob_end_clean(); 
			}  
		?>
		<?php if ($logo) { ?>
		<div class="top-logo">
			<div class="wrapper">
				<span class="logo-img">
					<?php if ($poweredby) { ?>
					<span class="poweredby"><?php echo $poweredby ?></span>	
					<?php } ?>
					<?php echo $link_open ?>
					<img src="<?php echo $logo['url'] ?>" alt="<?php echo $logo['title'] ?>">
					<?php echo $link_close ?>
				</span>
				<?php echo $views_display; ?>
			</div>
		</div>	
		<?php } ?>
		<div class="map-div">
			<div class="wrapper mapwrapper">

			
				<?php 
				$subtitle = get_field("subtitle"); 
				if($subtitle) {
					$subtitle = str_replace("{{","<u>",$subtitle);
					$subtitle = str_replace("}}","</u>",$subtitle);
				} ?>

				<div class="titlediv">
					<?php echo $views_display; ?>
					<h1 class="t1"><?php the_title(); ?></h1>
					<?php if ($subtitle) { ?>
					<h2 class="t2"><?php echo $subtitle ?></h2>	
					<?php } ?>
				</div>

				<?php if ( $map = get_field("map_iframe_code") ) { ?>
				<div class="map-embed">
					<?php echo $map ?>
					<img src="<?php echo $placeholder ?>" alt="" aria-hidden="true" class="resizer">
				</div>
				<?php } ?>
	
				
			</div>
		</div>

		<?php 
		$sidebar_buttons = get_field("sidebar_buttons"); 
		$subscription_text = get_field("subscription_text"); 
		$subscription_button = get_field("subscription_button"); 
		$content_class = ( $sidebar_buttons && ($subscription_text || $subscription_button) ) ? 'half':'full';
		?>

		<div class="entry-content <?php echo $content_class ?>">
			<div class="wrapper">
				<div class="leftcol">
					<?php the_content(); ?>
				</div>

				<?php if ( $sidebar_buttons || ($subscription_text || $subscription_button) ) { ?>
				<div class="rightcol">
					<?php get_template_part('sidebar-map-page'); ?>
				</div>
				<?php } ?>
				
			</div>
			
		</div>

		<?php endwhile; ?>
	</main><!-- #main -->
</div><!-- #primary -->
<?php get_footer();
