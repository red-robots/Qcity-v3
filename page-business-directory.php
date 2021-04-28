<?php
/**
 * Template Name: Business Directory
 */
get_header(); 
?>
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

		<div class="map-div title-gray-bg">
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
				
			</div>
		</div>

		<?php 
		$sidebar_buttons = get_field("sidebar_buttons"); 
		$subscription_text = get_field("subscription_text"); 
		$subscription_button = get_field("subscription_button"); 
		$content_class = ( $sidebar_buttons && ($subscription_text || $subscription_button) ) ? 'half':'full';
		?>

		<div class="entry-content biz-directory <?php echo $content_class ?>">
			<div class="wrapper">
				<div class="leftcol">
					
					<?php the_content(); ?>
					
					<div class="listing-header">
						<div class="content-area-title">
							<header class="section-title ">
								<h2 class="dark-gray">Find a Business by Category</h2>
							</header>
						</div>
					</div>

					<?php 
					$business_category = array();
					$terms = get_terms('business_category');
					if ($terms) { ?>
						<div class="listing_initial">
							<section class="biz-cats">
						    <?php foreach ($terms as $category) {
						    	if( $category->count > 0 ):

						    		$icon = get_field('icon', $category);

							    	$business_category[] = array(
							    				'name' 	=> $category->name,
							    				'url' 	=> get_term_link($category->term_id),
							    				'icon'	=> $icon['url']
							    	);
						    	endif;
						    }

						  	array_multisort($business_category, SORT_ASC, $terms);					
								include( locate_template('template-parts/business-categories.php')); ?>
							</section>
						</div>
					<?php } ?>

				</div>

				<?php if ( $sidebar_buttons || ($subscription_text || $subscription_button) ) { ?>
				<div class="rightcol">
					<?php 
					$template = basename(__FILE__, '.php'); 
					include( locate_template('sidebar-map-page.php'));
						//get_template_part('sidebar-map-page');
					?>
				</div>
				<?php } ?>
				
			</div>
			
		</div>

		<?php endwhile; ?>
	</main><!-- #main -->
</div><!-- #primary -->
<?php 
get_footer();
