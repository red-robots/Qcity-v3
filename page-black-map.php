<?php
/**
 * Template Name: Map Page
 */
get_header(); ?>
<div id="primary" class="content-area-full map-page">
	<main id="main" class="site-main" role="main">

		<?php while ( have_posts() ) : the_post(); ?>
		<?php 
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

				<?php if ( $sidebar_buttons && ($subscription_text || $subscription_button) ) { ?>
				<div class="rightcol">

					<?php if ($sidebar_buttons) { ?>
					<div class="sbbuttons">
						<?php foreach ($sidebar_buttons as $button) {
							$b =  $button['button'];
							$btnName = ( isset($b['title']) && $b['title'] ) ? $b['title'] : ''; 
							$btnLink = ( isset($b['url']) && $b['url'] ) ? $b['url'] : ''; 
							$btnTarget = ( isset($b['target']) && $b['target'] ) ? $b['target'] : '_self'; 
							if($btnName && $btnLink) { ?>
							<div class="button"><a href="<?php echo $btnLink ?>" target="_blank"><?php echo $btnName ?></a></div>
						<?php } 
						} ?>
					</div>
					<?php } ?>


					<?php if ($subscription_text || $subscription_button) { ?>
					<div class="subscription">
						<?php if ($subscription_text) { ?>
						<div class="text"><?php echo $subscription_text ?></div>	
						<?php } ?>
						<?php if ($subscription_button) { 
							$s_btnName = $subscription_button['title'];
							$s_btnLink = $subscription_button['url'];
							$s_btnTarget = ( isset($subscription_button['target']) && $subscription_button['target'] ) ? $subscription_button['target'] : '_self';
							if($s_btnName && $s_btnLink) { ?>
							<div class="sub-button">
								<a href="<?php echo $s_btnLink ?>" target="_blank"><?php echo $s_btnName ?></a>
							</div>	
							<?php } ?>
						<?php } ?>
					</div>
					<?php } ?>
				</div>
				<?php } ?>
				
			</div>
			
		</div>

		<?php endwhile; ?>
	</main><!-- #main -->
</div><!-- #primary -->
<?php get_footer();
