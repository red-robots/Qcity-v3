<?php
/**
 * Template Name: Events (new layout)
 *
 */
get_header(); 
$page_id = get_the_ID(); 
$job_category = ( isset($_GET['category']) && $_GET['category'] ) ? $_GET['category'] : '';
?>

<div id="primary" class="content-area page-with-poweredby page-job-new pageEventsNew">
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

				$subtitle = get_field("subtitle"); 
				$lastModified = get_the_modified_date('F j, Y h:i a');
				// if($subtitle) {
				// 	$subtitle = str_replace("{{","<u>",$subtitle);
				// 	$subtitle = str_replace("}}","</u>",$subtitle);
				// }

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
					<span class="visitor-counter">
						<span>
							<em class="e1">Visitor Count</em>
							<em class="e2"><?php echo number_abbr($views); ?></em>
						</span>
					</span>
				<?php 
					$views_display = ob_get_contents();
					ob_end_clean(); 
				} 

				$main_page_title = ( get_field("alt_page_title") ) ? get_field("alt_page_title") : get_the_title();
			?>

			<?php if ($logo) { ?>
			<div class="sponsored-logo">
				<div class="wrapper">
					<span class="logo-img">
						<?php if ($poweredby) { ?>
						<span class="poweredby"><?php echo $poweredby ?></span>	
						<?php } ?>
						<?php echo $link_open ?>
						<img src="<?php echo $logo['url'] ?>" alt="<?php echo $logo['title'] ?>">
						<?php echo $link_close ?>
					</span>
				</div>
			</div>	
			<?php } ?>
			<header class="title-bar-gray">
				<div class="wrapper">
					<h1 class="t1"><?php echo $main_page_title; ?></h1>
					<?php if ($subtitle) { ?>
					<h2 class="t2"><?php echo $subtitle ?></h2>	
					<?php } ?>

					<?php if ($views_display) { ?>
						<?php echo $views_display ?>
					<?php } ?>
				</div>
			</header>

	<?php endwhile; ?>

			<?php $buttons = get_field("cta_buttons"); ?>
			<div class="entry-content">
				<div class="wrapper">
					
					<?php /* BUTTONS */ ?>
					<?php if ($buttons) { ?>
					<div class="pageCTAButtons">
						<ul class="flex">
						<?php $ctr=1; foreach ($buttons as $e) {
						if( $b = $e['button'] ) { 
							$btnId = strtolower(sanitize_title($b['title']));  
							$firstBtn = ($ctr==1) ? ' first':'';
							?>
							<li class="jbtn<?php echo $firstBtn ?>">
								<a id="<?php echo $btnId ?>" href="<?php echo $b['url'] ?>" target="<?php echo ( isset($b['target']) && $b['target'] ) ? $b['target'] : '_SELF'; ?>" class="jobctabtn"><?php echo $b['title'] ?></a>
								<?php if ($b['url']=='#eventcategories') { ?>
									<?php 
									/* Find Jobs Dropdown */
									$terms = get_terms( array(
								    'taxonomy' 		=> 'event_cat',
								    'orderby' => 'name',
    								'order' => 'ASC',
								    'hide_empty' 	=> true
									));
									if( is_array($terms) && !empty($terms) ) { ?>
									<div data-for="<?php echo $btnId ?>" class="jobCategories dropdownList">
										<ul class="cats">
											<?php foreach($terms as $term) { 
												//$termLink = get_term_link($term->term_id);
												$catSlug = $term->slug;
												$termLink = get_permalink() . '?category=' . $catSlug;
												$isActive = ($job_category==$catSlug) ? ' active':'';
												?>
                      	<li class="catlink<?php echo $isActive ?>"><a href="<?php echo $termLink;?>"><?php echo $term->name;?></a></li>
                      <?php } ?>
										</ul>
									</div>
									<?php } ?>
								<?php } ?>
							</li>
							<?php $ctr++; } ?>
						<?php } ?>
						</ul>
					</div>	
					<?php } ?>

					<div class="single-page-event-wrapper fw-left">

						<div id="processing-data" class="fw-left"><span class="load-icon-2"><i class="fas fa-sync-alt spin"></i></span></div>

						<div id="page-events-container" class="single-page-event">

							<?php
							/* SPONSORED EVENTS */
							$postID = array();
							$i = 0;
							$day = date('d');
							$day2 = $day - 1;
							$day_plus = sprintf('%02s', $day);
							$today = date('Ym') . $day_plus;
							
							$args = array(
								'post_type'			=>'event',
								'post_status'		=>'publish',
								'posts_per_page' 	=> -1,
								'order' 			=> 'ASC',
								'meta_key' 		=> 'event_date',
								'orderby'     => 'meta_value_num',
								'meta_query' 		=> array(
									array(
										'key'		=> 'event_date',
										'compare'	=> '>=',
										'value'		=> $today,
									),		
								),
								'tax_query' => array(
									array(
										'taxonomy' 	=> 'event_category', 
										'field' 	=> 'slug',
										'terms' 	=> array( 'premium' ) 
									)
								)
							);

							$sponsored = new WP_Query($args);
							if ($sponsored->have_posts()) { ?>
							<div id="sponsored-events-section" class="qcity-sponsored-container">
								<header class="section-title ">
									<h1 class="dark-gray">Sponsored</h1>
								</header>
								<div class="eventListWrap">
									<section class="events">
										<?php while ($sponsored->have_posts()) : $sponsored->the_post(); 
											$date 		= get_field("event_date", false, false);
											$date 		= new DateTime($date);
											$enddate 	= get_field("end_date", false, false);
											$enddate 	= ( !empty($enddate) ) ? new DateTime($enddate) : $date;

											$date_start 	= strtotime($date->format('Y-m-d'));
											$date_stop 		= strtotime($enddate->format('Y-m-d')); 
											$postID[] = get_the_ID();
											include( locate_template('template-parts/sponsored-block.php') );
										endwhile; ?>
									</section>
								</div>
							</div>
							<?php } ?>

							<?php 
							/* MORE EVENTS */
							$i = 0;
							$events = array();
							$paged = ( get_query_var( 'pg' ) ) ? absint( get_query_var( 'pg' ) ) : 1;
							$more_args = array(
								'post_type'			=>'event',
								'paged'			   => $paged,
								'post_status'		=>'publish',
								'posts_per_page' 	=> 27,
								'order' 			=> 'ASC',
								'meta_key' 		=> 'event_date',
								'orderby'     => 'meta_value_num',
								'meta_query' 		=> array(
									array(
										'key'		=> 'event_date',
										'compare'	=> '>=',
										'value'		=> $today,
									),		
								)
							);

							if($postID) {
								$more_args['post__not_in'] = $postID;
							}
							$more_events = new WP_Query($more_args);
							?>

							<div id="primary" class="content-area-event more-happenings-section fw-left">
									<main id="main" class="site-main" role="main">

										<?php if ( $more_events->have_posts() )  { ?>
										<header class="section-title qcity-more-happen">
											<h1 class="dark-gray">More Happenings</h1>
										</header>
										<?php } ?>

										<div class="page-event-list">
											<?php if ( $more_events->have_posts() )  { ?>
											<div class="listing_initial more-events-section">
												<div class="qcity-news-container">
													<div class="eventListWrap">
														<section class="events more-events-posts">
															<?php while ($more_events->have_posts()) : $more_events->the_post(); 
																	$date 		= get_field("event_date", false, false);
																	$date 		= new DateTime($date);
																	$enddate 	= get_field("end_date", false, false);
																	$enddate 	= ( !empty($enddate) ) ? new DateTime($enddate) : $date;

																	$date_start 	= strtotime($date->format('Y-m-d'));
																	$date_stop 		= strtotime($enddate->format('Y-m-d'));
																	$now 			= strtotime(date('Y-m-d'));
																	include( locate_template('template-parts/sponsored-block.php') );
																endwhile; ?>
														</section>
													</div>
												</div>
												<div id="more-posts-hidden" style="display:none;"><?php /* DO NOT DELETE!! THIS WILL BE USED AS CONTAINER FOR NEXT SET OF ITEMS FROM LOAD MORE BUTTON */ ?></div>

												<?php
					                $total_pages = $more_events->max_num_pages;
					                if ($total_pages > 1){ ?>

					                    <div id="pagination" class="pagination" style="display:none;">
					                        <?php
																    $pagination = array(
																			'base' => @add_query_arg('pg','%#%'),
																			'format' => '?pg=%#%',
																			'mid-size' => 1,
																			'current' => $paged,
																			'total' => $total_pages,
																			'prev_next' => True,
																			'prev_text' => __( '<span class="fa fa-arrow-left"></span>' ),
																			'next_text' => __( '<span class="fa fa-arrow-right"></span>' )
																    );
																    echo paginate_links($pagination);
																  ?>
					                    </div>

					                    <div id="more-bottom-button" class="more">	
															 	<a href="#" id="load-more-action" class="red" data-permalink="<?php echo $currentURL; ?>" data-next-page="2" data-total-pages="<?php echo $total_pages; ?>">		
															 		<span class="load-text">Load More</span>
																	<span class="load-icon"><i class="fas fa-sync-alt spin"></i></span>
															 	</a>
															</div>

					                    <?php
					            	} ?>

											</div>
											<?php } ?>
										</div>

										<div id="listing-search-result" class="listing_search" style="margin-bottom: 20px; padding: 0 0 40px;">
											<div class="listing_search_result"></div>				
										</div>
									</main>
							</div>

						</div>

						<div id="search-result-pagination"><?php /* DO NOT DELETE!! THIS WILL BE USED FOR AJAX PAGINATION */ ?></div>

					</div>


					<?php
					$newsletterForm = get_field("jobpage_newsletter",$page_id);
					$newsletter_title = get_field("newsletter_title",$page_id);
					$newsletter_text = get_field("newsletter_text",$page_id);
					if($newsletterForm) {
						$gravityFormId = $newsletterForm;
						$gfshortcode = '[gravityform id="'.$gravityFormId.'" title="false" description="false" ajax="true"]';
					 	if( do_shortcode($gfshortcode) ) { ?>
					 		<div class="jobpageNewsletter">
								<div class="form-subscribe-blue">
									<div class="form-inside">
									<?php if ($newsletter_title) { ?>
										<h3 class="gfTitle"><?php echo $newsletter_title ?></h3>
									<?php } ?>
									<?php if ($newsletter_text) { ?>
										<div class="gftxt"><?php echo $newsletter_text ?></div>
									<?php } ?>
									<?php echo do_shortcode($gfshortcode); ?>
									</div>
								</div>
							</div>
						<?php } ?>
					<?php } ?>
				</div>
			</div>
		
	</main>
</div>

<?php $eventSubmission = get_field("event_submission_form",$page_id); 
if($eventSubmission) { 
$es_shortcode = '[gravityform id="'.$eventSubmission.'" title="false" description="false" ajax="true"]'; 
if( do_shortcode($es_shortcode) ) { ?>
<div class="eventSubmissionForm esformPopUp">
	<div class="formInner">
		<a href="#" id="closeEsPopup"><span>x</span></a>
		<?php echo do_shortcode($es_shortcode); ?>
	</div>
</div>
<div class="esformbackdrop"></div>
<?php } ?>
<?php } ?>


<script type="text/javascript">
jQuery(document).ready(function($){
	if( $(".esformPopUp").length>0 ) {
		$(".esformbackdrop").insertAfter("#page.site");
		$(".esformPopUp").insertAfter("#page.site");
		$(document).on("click","#closeEsPopup",function(e){
			e.preventDefault();
			$(".esformbackdrop,.esformPopUp").removeClass('show animated fadeIn');
		});

		$(document).on("click","#post-an-event",function(e){
			e.preventDefault();
			$(".esformbackdrop,.esformPopUp").addClass('show animated fadeIn');
		});
	}
	if( $(".jobpageNewsletter").length>0 ) {
		var jobcount = ( $(".biz-job-wrap .job").length>0 ) ? $(".biz-job-wrap .job").length : 0;
		if( $(".biz-job-wrap .job").length>0 ) {
			var i = 1;
			if(jobcount==8) {
				$(".biz-job-wrap .job").each(function(){
					var target = $(this);
					if(i==4) {
						$(".jobpageNewsletter").insertAfter(target).addClass("show");
					}
					i++;
				});
			} 
			else if(jobcount>8) {
				$(".biz-job-wrap .job").each(function(){
					var target = $(this);
					if(i==5) {
						$(".jobpageNewsletter").insertAfter(target).addClass("show");
					}
					i++;
				});
			} else {
				var lastList = $(".biz-job-wrap .job").last();
				$(".jobpageNewsletter").insertAfter(lastList).addClass("show");
			}
		}
	}
});
</script>
<?php 
get_footer();
