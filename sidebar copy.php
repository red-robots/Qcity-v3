<?php
/**
 * The sidebar containing the main widget area.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package ACStarter
 */

wp_reset_postdata();
wp_reset_query();

$post_id = get_the_ID();

$sidebar_event_text 	= get_field('sidebar_event_text', 'option');
$sidebar_business_text 	= get_field('sidebar_business_text', 'option');
$sidebar_post_text 		= get_field('sidebar_post_text', 'option');

if ( ! is_active_sidebar( 'sidebar-1' ) ) {
	return;
}
if( (get_post_type() == 'post') && !(is_page('events')) ) {
	$title = 'Trending';
	$qp = 'post';
	$args = array(
			'post_type'			=> $qp,
			'posts_per_page' 	=> 6,
			'post_status'       => 'publish',	
	);
} elseif( is_page('qcity-biz') ) {
	$title = 'Latest Business Articles';
	$qp = 'business_listing';
	$args = array(
			'post_type'			=> $qp,
			'posts_per_page' 	=> 6,
			'post_status'       => 'publish',	
	);
} elseif( is_tax() ) {
	$title = 'Black Business';
	$qp = 'black-business';
	$args = array(     
		        'category_name'     => 'black-business',        
		        'post_type'         => 'post',        
		        'post__not_in'      => array( $post_id ),
		        'post_status'       => 'publish',
		        'posts_per_page'    => 5,		       
	);
} elseif( (get_post_type() == 'page') && !( is_page('events') ) && !( is_page('business-directory') ) && !( is_page('media-gallery') ) ) {
	$title = 'Latest Stories';
	$qp = 'business_listing';
	$args = array(
			'post_type'			=> $qp,
			'posts_per_page' 	=> 6,
			'paged'             => 1,
			'post_status'       => 'publish',
	);
} elseif( (get_post_type() == 'page') && ( is_page('media-gallery') )  ) {
	$title = 'Trending';
	$qp = 'post';
	$args = array(
			'post_type'			=> $qp,
			'posts_per_page' 	=> 6,
			'post_status' 	  	=> 'publish',
			'paged'             => 1
	);
} elseif( is_page('events') ) {
	$title = 'Entertainment';
	$qp = 'entertainment';
	$args = array(     
		        'category_name'     => 'Entertainment',        
		        'post_type'         => 'post',        
		        'post__not_in'      => array( $post_id ),
		        'post_status'       => 'publish',
		        'posts_per_page'    => 5,
		        'paged'             => 1		       
		    );
} elseif( is_page('business-directory') ){
	$title = 'Black Business';
	$qp = 'black-business';
	$args = array(     
		        'category_name'     => 'black-business',        
		        'post_type'         => 'post',        
		        'post__not_in'      => array( $post_id ),
		        'post_status'       => 'publish',
		        'posts_per_page'    => 5,
		        'paged'             => 1		       
	);
}

if( is_page('events') ) {
	$text = $sidebar_event_text;
} elseif( ( ( get_post_type() == 'page') &&  is_page('business-directory') ) || get_post_type() == 'business_listing'  ) {
	$text = $sidebar_business_text;
} else {
	$text = $sidebar_post_text;
}
?>

<div class="widget-area category-post">

<?php 
	// If is Sponsored Post

	$sponsors = get_field('sponsors');
	if($sponsors):
		$post = get_post($sponsors[0]->ID);
		$logo = get_field("logo", $post);
		$description = get_field("description", $post);
		$logo_link = get_field("logo_hyperlink", $post);
		// setup_postdata( $post );
		// get_template_part('ads/sponsor-header');
		// wp_reset_postdata();
	endif;
	// echo '<pre>';
	// print_r($sponsors);
	// echo '</pre>';

	
	$link = get_field("sponsorship_policy_link",39809);
	$link_text = get_field("sponsorship_policy_text",39809);
	?>


	<?php  if( ( (get_post_type() != 'post') ||  is_category() )  ): ?>
	
	<?php
	$obj = get_queried_object();
	$currentTermId = ( isset($obj->term_id) && $obj->term_id ) ? $obj->term_id : '';
	$subscribeOpts = get_field('post_category_subscription','option');
	$subscriptionText = array();
	$subscription_info = '';
	if($subscribeOpts && $currentTermId) {
		foreach($subscribeOpts as $s) {
			$subtext = $s['subscription_text'];
			$termIds = $s['categories'];
			if( $termIds && in_array($currentTermId,$termIds) ) {
				$subscriptionText[$currentTermId] = $subtext;
			}
		}
		$subscription_info = ($subscriptionText) ? $subscriptionText[$currentTermId] : '';
	}
	$subscriptionBoxText = ($subscription_info) ? $subscription_info : $text;
	?>
	<div class="side-offer">
		<?php if ($subscriptionBoxText) { ?>
		<p><?php echo $subscriptionBoxText; ?></p>
		<?php } ?>
		<?php  
      $topSubscribe = get_field("topSubscribe","option");
      $subscribeText = ( isset($topSubscribe['subscribe_text_footer']) && $topSubscribe['subscribe_text_footer'] ) ? $topSubscribe['subscribe_text_footer']:'';


      $subscribeText = ($subscribeText) ? str_replace('>','',$subscribeText):'';
      $subscribeButton = ( isset($topSubscribe['subscribe_button']) && $topSubscribe['subscribe_button'] ) ? $topSubscribe['subscribe_button']:'';
      $subscribeName = ( isset($subscribeButton['title']) && $subscribeButton['title'] ) ? $subscribeButton['title']:'';
      $subscribeURL = ( isset($subscribeButton['url']) && $subscribeButton['url'] ) ? $subscribeButton['url']:'';
      $subscribeTarget = ( isset($subscribeButton['target']) && $subscribeButton['target'] ) ? $subscribeButton['target']:'_self';
    ?>
    <?php if ($subscribeName && $subscribeURL) { ?>
    <div class="btn">
			<a class="white" href="<?php echo $subscribeURL ?>" target="<?php echo $subscribeTarget ?>"><?php echo $subscribeName ?></a>
		</div>
    <?php } ?>
	</div>

		<?php

		//var_dump( $args );

		$wp_query = new WP_Query( $args );
		
		// might do an if / then for offers and invites category here..		

		if ($wp_query->have_posts()) : ?>
			<div class="next-stories">
				<h3><?php echo $title; ?></h3>
				<div class="sidebar-container">
					<?php while ($wp_query->have_posts()) : $wp_query->the_post();

						if( is_tax() ){
							get_template_part( 'template-parts/sidebar-black-biz-block');
						} else {
							get_template_part( 'template-parts/sidebar-block');
						}
						

						
						
					endwhile; wp_reset_postdata();  ?>
				</div>
				<div class="more">
					<a class="gray qcity-sidebar-load-more" data-page="1" data-action="qcity_sidebar_load_more" data-qp="<?php echo $qp; ?>" data-postid="<?php echo $post_id; ?>">
						<span class="load-text">Load More</span>
						<span class="load-icon"><i class="fas fa-sync-alt spin"></i></span>
					</a>
				</div>	
			<?php endif; ?>
			</div>

	<?php //dynamic_sidebar( 'sidebar-1' ); ?>

	<?php endif;  //if( (get_post_type() != 'post') ||  is_category() && ! $sponsors )  ?>

</div><!-- #secondary -->


