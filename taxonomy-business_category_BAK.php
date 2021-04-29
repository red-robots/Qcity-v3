<?php
/**
 * The template for displaying archive pages.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package ACStarter
 */

get_header(); 
get_template_part('template-parts/banner-biz');
$placeholder = get_template_directory_uri() . '/images/right-image-placeholder.png';


//$add_business = get_field('add_your_business');
//$add_business_link = get_field('add_business_link');

//var_dump($ob);
?>

<div class="wrapper" >
	<?php $ob = get_queried_object(); ?>
	<div class="business-category-header">
		<header class="page-header biz">		
			<h1><?php echo $ob->name; ?></h1>
			<?php
				//the_archive_title( '<h1 class="page-title">', '</h1>' );
				the_archive_description( '<div class="taxonomy-description">', '</div>' );
			//echo '<!-- <pre>';
			//print_r($ob);
			//echo '</pre> -->';
			?>
		</header><!-- .page-header -->
	</div>
	<div class="featured_business">
		<header class="section-title ">
			<h2 class="dark-gray">Paid Posts</h2>
			<!--
			<div class="biz-submit">
				<a href="<?php //echo bloginfo( 'url' ); ?>/business-directory/business-directory-sign-up/">Submit your business</a>
			</div>
			-->
		</header>
	</div>
	<div class="clear"></div>
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

			<div class="listing_initial">

				<div class="business-category-page">
					<?php
//echo '<!-- <pre>';
//print_r($ob);
//echo '</pre> -->';
						$args = array(
								'post_type' 	=> 'business_listing',
								'post_status'	=> 'publish',
								//'category_name' => $ob->slug,
								'tax_query' => array( 
												'relation' => 'AND',
												array(
											        'taxonomy' 			=> 'business_classification',
											        'field' 			=> 'slug',
											        'terms' 			=> array( 'featured' ),
											        'include_children' 	=> true,
											        'operator' 			=> 'IN'
											      ),
												array(
											        'taxonomy' 			=> $ob->taxonomy,
											        //'field' 			=> 'id',
													'field' 			=> 'term_id',
											        'terms' 			=> array( $ob->term_id ),
											        'include_children' 	=> true,
											        'operator' 			=> 'IN'
											      )
								)
						);

						$query = new WP_Query( $args );

						if ( $query->have_posts() ) : ?>
							<div class="qcity-news-container">
								<section class="sponsored">
									<?php
									/* Start the Loop */
									while ( $query->have_posts() ) : $query->the_post();

										get_template_part( 'template-parts/business-block' );

									endwhile;

									wp_reset_postdata(); ?>
								
								</section>

							</div>
							
							<!--
							<div class="more ">	
								 	<a class="red qcity-load-more" data-page="1" data-action="qcity_business_load_more" >		
								 		<span class="load-text">Load More</span>
										<span class="load-icon"><i class="fas fa-sync-alt spin"></i></span>
								 	</a>
							</div>
							-->

						<?php else: ?>
							<div class="qcity-news-container" style="padding-bottom: 20px;">
								<section class="sponsored">
									<a href="<?php echo bloginfo( 'url' ); ?>/business-directory/business-directory-sign-up/">
										<h5 class="sponsored-empty">Be the first.</h5>
									</a>								
								</section>
							</div>
						<?php endif; ?>

						<div class="mt-5" style="">
							<?php //get_template_part('template-parts/business-directory'); ?>
							<div class="biz-dir" style="margin-top: 20px;">
								<header class="section-title ">
									<h2 class="dark-gray">Free Listings</h2>
								</header>
								<?php
								/*
									Biz Directory.
								*/
								$business_listing_arr = array();
								$i = 0;
								$wp_query = new WP_Query();
								$wp_query->query(array(
									'post_type'			=>'business_listing',
									'posts_per_page' 	=> -1,
									'post_status'   	=> 'publish',
									//'paged' => $paged,
									'tax_query' => array(
											array(
											    'taxonomy' 			=> $ob->taxonomy,
												'field' 			=> 'term_id',
												'terms' 			=> array( $ob->term_id ),
											),
									    ),
								));
								if ($wp_query->have_posts()) : ?>
								
								    <?php while ($wp_query->have_posts()) : $wp_query->the_post(); $i++; 
									    	
									    	$phone 		= get_field('phone');
									    	$website 	= get_field('website');
									    	$title 		= get_the_title();
									    	$bob 		= get_field('black_owned_business');

									    	$business_listing_arr[] = array(
									    			'title' 	=> $title,
									    			'phone'		=> $phone,
									    			'website'	=> $website,
									    			'bob'		=> $bob
									    	);
								    ?>
										    
										    
								    <?php endwhile; ?>	
								       
								<?php endif; wp_reset_postdata(); ?>

								<?php 
									$title = array_column($business_listing_arr, 'title');
									array_multisort($title, SORT_ASC, $business_listing_arr);
								 ?>

								 <div class="">
									<table class="business-directory-table">
										<?php 
										$i = 0;	
										foreach( $business_listing_arr as $key => $biz_list): 
												if( ($i % 2) == 0 ) {
										    		$cl = 'even';
										    		//$i = 0;
										    	} else {
										    		$cl = 'odd';
										    	}
											?>

											<tr class="row <?php echo $cl; ?>">
										    	<td><?php echo $biz_list['title']; ?></td>
										    	<td class="table-desktop"><?php echo $biz_list['phone']; ?></td>
										    	<td>
										    		<a href="<?php echo $biz_list['website'] ?>" target="_blank">View Website</a>
										    	</td>
										    	<td><?php echo ($biz_list['bob']) ? 'BOB' : ''; ?></td>
										    </tr>

										<?php  $i++; endforeach; ?>
									</table>
								</div> 

							</div>
						</div>	
					<!-- close biz directory mt-5 -->
				
				</div>

			</div>

			<div class="listing_search">
				<div class="listing_search_result">				
				</div>				
			</div>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php get_sidebar(); ?>
</div>
<?php get_footer();
