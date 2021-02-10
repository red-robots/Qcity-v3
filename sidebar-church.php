<?php
/**
 * The sidebar containing the main widget area.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package ACStarter
 */

if ( ! is_active_sidebar( 'sidebar-1' ) ) {
	return;
}
if( get_post_type() == 'post' ) {
	$title = 'Trending';
	$qp = 'post';
} elseif( is_page('qcity-biz') ) {
	$title = 'Latest Business Articles';
	$qp = 'business_listing';
} elseif( is_tax() ) {
	$title = 'Latest Business Articles';
	$qp = 'business_listing';
} elseif( get_post_type() == 'page' ) {
	$title = 'This is a page and needs to change the query';
	$qp = 'business_listing';
}
?>

<aside id="secondary" class="widget-area" role="complementary">

	<!--
	<div class="side-offer">
	    <h3>Search Denominations</h3>
	    <form id="category-select" class="category-select replace"  action="<?php echo esc_url( home_url( '/' ) ); ?>" method="get">
	        <?php $args = array(
	            'show_option_none'  => 'Select Denomination',
	            'show_count'        => 1,
	            'hide_empty'        => 0,
	            'orderby'       	=> 'name',
	            'hierarchical'      => 1,
	            'echo'          	=> 0,
	            'value_field' 		=> 'slug',
	            'taxonomy'          => 'denomination',
	            'name' 				=> 'denomination'
	        ); ?>
	        <?php $select  = wp_dropdown_categories( $args );
	        ?>
	        <?php $replace = "<select$1 onchange='return this.form.submit()' class= 'replace' >"; ?>
	        <?php $select  = preg_replace( '#<select([^>]*)>#', $replace, $select ); ?>
	        <?php echo $select; ?>
	        <noscript>
	            <input type="submit" value="View" />
	        </noscript>
	    </form>
	    

	    <?php
	    // $args = array(
	    //     'range' => 'weekly',
	    //     'post_type' => 'church_listing',
	    //     'wpp_start' => '',
	    //     'wpp_end' => '',
	    //     'limit'=>5,
	    //     'post_html' => '<div class="listing"><a href="{url}">{text_title}</a></div>'
	    // );
	    //wpp_get_mostpopular( $args );
	    ?>
	    <div class="btn">
	        <a class="white" href="<?php bloginfo('url'); ?>/church-directory/church-directory-sign-up">Add your church to this directory</a>
	    </div>
	-->


	<div class="side-offer">
		<p>Have you signed up to receive our daily news and events listings?</p>
		<div class="btn">
			<a class="white" href="#">Subscribe</a>
		</div>
	</div>
	
		<?php
		$wp_query = new WP_Query();
		$wp_query->query(array(
			'post_type'			=> 'post',
			'post_status'		=> 'publish',
			'posts_per_page' 	=> 6
		));
		if ($wp_query->have_posts()) : ?>
			<div class="next-stories">
				<h3><?php echo $title; ?></h3>
					<?php while ($wp_query->have_posts()) : $wp_query->the_post(); ?>
						<article class="small">
							<a href="<?php the_permalink(); ?>">
								<div class="img">
									<?php the_post_thumbnail('thumbnail'); ?>
								</div>
								<div class="xtitle">
									<?php the_title(); ?>
								</div>
							</a>
						</article>
						
					<?php endwhile; ?>
					<div class="more">
						<a class="gray" href="">Load More</a>
					</div>	
				<?php endif; ?>
			</div>
	<?php //dynamic_sidebar( 'sidebar-1' ); ?>
</aside><!-- #secondary -->
