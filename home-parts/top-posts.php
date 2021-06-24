<?php
/* The following posts should not be included in the big post area:
 * - Posts that have categories ['sponsored-post','commentaries']
 * - Posts that are assigned to show on the top-right area (right sidebar)
 * - Posts that have videos
 * - Posts that are assigned to display under "More News" section
 */

/* Get first the sticky post */
$stickyPosts = get_option('sticky_posts');
$stickyPostId = '';
$stickyPostDate = '';
$stickpost = '';
$featured_posts = array();
$more_item_ids = array();

if($stickyPosts) {	
	if( is_array($stickyPosts) ) {
		$count = count($stickyPosts);
		if($count>1) {
			arsort($stickyPosts);
		}
		$stickyPostId = $stickyPosts[0];
	} else {
		$stickyPostsIds = @unserialize($stickyPosts);
		if( is_array($stickyPostsIds) ) {
			$stickyPostId = $stickyPostsIds[0];
		}
	}
	if($stickyPostId) {
		if( $stickpost = get_post($stickyPostId) ) {
			$stickyPostDate = $stickpost->post_date;
		}
	}
}


/* Query posts not in category */
$not_in_categories = array('sponsored-post','commentaries');
$big_post_args = array(
  'post_type' => 'post',
  'suppress_filters' => false, 
  'posts_per_page' => 4,
  'orderby' => 'date',
  'order' => 'DESC',
  'post_status' => 'publish',
  'tax_query' => array (
          array(
            'taxonomy' => 'category',
            'terms' => $not_in_categories,
            'field' => 'slug',
            'operator' => 'NOT IN',
            'include_children' => true
          )
  			),
	'meta_query' => array(
			'relation' => 'OR',
  		array(
				'key' => 'video_single_post',
				'compare' => 'NOT EXISTS',
				'value' => 'null'
		  ),
		  array(
        'key' => 'video_single_post',
        'compare' => '=',
        'value' => ''
	    )
		)
);


$rightPostItems = (get_stick_to_right_posts(3)) ? get_stick_to_right_posts(3) : array();
$moreNewsItems = getMoreNewsPosts(3,'ID');

/* Exclude posts that show up on the right side bar and under "More News" area */

$exlude_post_ids = array();
$exclude_items = array();
if($rightPostItems) {
	$exclude_items[] = $rightPostItems;
}
if($moreNewsItems) {
	foreach($moreNewsItems as $m) {
		$more_item_ids[] = $m->ID;
	}
	$exclude_items[] = $more_item_ids;
}
if($exclude_items) {
	foreach($exclude_items as $ex_items) {
		foreach($ex_items as $id) {
			$exlude_post_ids[] = $id;
		}
	}
}

if($exlude_post_ids) {
	$exlude_post_ids = array_unique($exlude_post_ids);
}

if($exlude_post_ids) {
	$big_post_args['post__not_in'] = $exlude_post_ids;
}

$big_post = get_posts($big_post_args);
/* Compare the sticky post date with the latest posts */
if($stickyPostDate) {
	if($big_post) {
		$big_post_date = $big_post[0]->post_date;
		if( strtotime($big_post_date) > strtotime($stickyPostDate) ) {
			$bigPost = $big_post[0];
		} else {
			$bigPost = $stickpost[0];
		}
	} else {
		$bigPost = $stickpost[0];
	}
}

?>

<section id="homeTopElements" class="stickies-new stickyPostsTop">

	<?php /* BIG POST */ ?>
	<div class="left stickLeft">
		<?php if($bigPost) { 
			$mainID = $bigPost->ID;
			$content = $bigPost->post_content;
			$title = $bigPost->post_title;
			$date = $bigPost->post_date;
			$content = apply_filters("the_content",$content);
			$postdate = get_the_date('F d, Y',$mainID);
			$img  = get_field('story_image',$mainID);	
			$authorID = $bigPost->post_author;
			$guest_author 	=  get_field('author_name',$mainID); 
			$author_default = get_the_post_author($authorID);
			$post_author = ($guest_author) ? $guest_author : $author_default;
			$postedByArr = array($post_author,$postdate);
			$postedBy = ($postedByArr && array_filter($postedByArr) ) ? implode(" <span class='sep'>|</span> ", array_filter($postedByArr) ) : '';
			$bigPostLink = get_permalink($mainID);
			$featured_posts[] = $mainID;
			?>
			<div id="homeFeatArticle" class="inside">
				<article id="post-<?php echo $mainID; ?>" class="big-post">
					<div class="bigPhoto">
					<?php if( has_post_thumbnail($bigPost) ) {  ?>
						<?php echo get_the_post_thumbnail($mainID);  ?>
					<?php } elseif( $img ) { ?>
						<img src="<?php echo $img['sizes']['photo']; ?>" alt="<?php echo $img['alt']; ?>">
					<?php } else { ?>
						<img src="<?php bloginfo('stylesheet_directory'); ?>/images/default.png">
					<?php } ?>
					</div>
					<div class="info">	
						<div class="innerTxt">		
							<h3><?php echo $title; ?></h3>
							<div class="desc">
								<?php echo get_the_excerpt($mainID); ?>
							</div>
							<?php if ($postedBy) { ?>
							<div class="by">By <?php echo $postedBy; ?></div>
							<?php } ?>
						</div>	
					</div>
					<div class="article-link"><a href="<?php echo $bigPostLink; ?>"></a></div>
				</article>
			</div>
		<?php } ?>


		<div id="emailBlockMobileView"></div>

		<!--  MORE NEWS and COMMENTARY -->
		<?php
			$entries = getMoreNewsPosts();  
			$commentaries = getCommentaryPosts();
			$twoCol = ($entries && $commentaries) ? 'twocol':'onecol';
		?>
		<?php if ($entries || $commentaries) { ?>
		<div class="more-news-commentaries <?php echo $twoCol?>" style="margin-top:10px;">
			<?php 
				get_template_part( 'home-parts/more-news'); 
				get_template_part( 'home-parts/commentary-posts');
			?>
		</div>	
		<?php } ?>
	</div>



	<!-- RIGHT POSTS -->
	<div class="right stickRight">

		<?php  get_template_part( 'home-parts/subscribe-form'); ?>

		<?php
			$maxItems = 3;
			$right_posts = array();
			if($rightPostItems) {
				$right_post_count = count($rightPostItems);
				if($right_post_count<$maxItems) {
					$missing = $maxItems - $right_post_count;
					if($missing < 1) {
						$missing = $maxItems;
					}

					foreach($rightPostItems as $rp_id) {
						if( $right = get_post($rp_id) ) {
							$right_posts[$rp_id] = $right;
						}
					}

					if($big_post) {
						unset($big_post[0]);
						if($big_post) {
							$b=1; foreach($big_post as $bp) {
								if($b<=$missing) {
									$bp_id = $bp->ID;
									$right_posts[$bp_id] = $bp;
								}
								$b++;
							}
						}
					}
				}
			} else {

				$right_posts = array();
				$r_args = array(
				  'post_type' => 'post',
				  'suppress_filters' => false, 
				  'posts_per_page' => 5,
				  'orderby' => 'date',
				  'order' => 'DESC',
				  'post_status' => 'publish',
				  'tax_query' => array (
				          array(
				            'taxonomy' => 'category',
				            'terms' => $not_in_categories,
				            'field' => 'slug',
				            'operator' => 'NOT IN',
				            'include_children' => true
				          )
				  			),
					'meta_query' => array(
							'relation' => 'OR',
				  		array(
								'key' => 'video_single_post',
								'compare' => 'NOT EXISTS',
								'value' => 'null'
						  ),
						  array(
				        'key' => 'video_single_post',
				        'compare' => '=',
				        'value' => ''
					    )
						)
				);

				$right_posts_exclude = array();

				if($featured_posts) {
					foreach($featured_posts as $fp_id) {
						$right_posts_exclude[] = $fp_id;
					} 
				}

				if($more_item_ids) {
					foreach($more_item_ids as  $more_id) {
						$right_posts_exclude[] = $more_id;
					}
				}

				if($right_posts_exclude) {
					$r_args['post__not_in'] = $right_posts_exclude;
				}

				$right_posts = get_posts($r_args);

			}
		?>


		<?php if ($right_posts) { ?>
			<div class="stickRightBlockWrapper">
				<?php $xh=1; foreach ($right_posts as $e) {
					if($xh<=$maxItems) {
						// $e = get_post($right_id); 
						$right_id = $e->ID;
						$r_title = $e->post_title;
						$pagelink = get_permalink($right_id);
						$img 	= get_field('story_image',$right_id);
						$video 	= get_field('video_single_post',$right_id);
						$embed = '';
						if( $video ) {
							$embed = youtube_setup($video);
						}
						$placeholder = get_template_directory_uri() . '/images/right-image-placeholder.png';
						$default_image = get_template_directory_uri() . '/images/default.png';
						$thumbId = get_post_thumbnail_id($right_id);
						$text = get_the_excerpt($right_id);
						$excerpt  = shortenText($text,100,' ','...');

						$rpostdate = get_the_date('F d, Y',$right_id);
						$authorID = $e->post_author;
						$guest_author 	=  get_field('author_name',$right_id); 
						$author_default = get_the_post_author($authorID);
						$post_author = ($guest_author) ? $guest_author : $author_default;
						$postedByArr = array($post_author,$rpostdate);
						$postedBy = ($postedByArr && array_filter($postedByArr) ) ? implode(" <span class='sep'>|</span> ", array_filter($postedByArr) ) : '';
						$featured_posts[] = $right_id;
						?>
						<article class="story-block stickRightBlock">
							<div class="inside">
								<div class="textwrap">
									<div class="photo story-home-right">
										<?php if( $embed ) { ?>	
											<iframe class="video-homepage"  src="<?php echo $embed; ?>"></iframe>
										<?php } elseif( has_post_thumbnail($e) ) { ?>	
											<a href="<?php echo $pagelink; ?>" class="postThumb">
												<?php 
												$xImg = wp_get_attachment_image_src($thumbId,'large');
												echo get_the_post_thumbnail($right_id); ?>
												<span class="imagebox" style="background-image:url('<?php echo $xImg[0]?>');"></span>
											</a>
										<?php } else { ?>	
											<a href="<?php echo $pagelink; ?>" class="postThumb">
												<span class="imagebox" style="background-image:url('<?php echo $default_image?>');"></span>
												<img src="<?php echo $default_image ?>" alt="">
											</a>
										<?php } ?>	
										<img src="<?php echo $placeholder ?>" alt="" aria-hidden="true" class="placeholder">	
									</div>

									<div class="desc">
										<div class="inner">
											<h3 class="ptitle"><a href="<?php echo $pagelink; ?>"><?php echo $r_title; ?></a></h3>	
											<div class="excerpt"><?php echo $excerpt; ?></div>
											<?php if ($postedBy) { ?>
											<div class="by">By <?php echo $postedBy; ?></div>
											<?php } ?>
										</div>
									</div>
								</div>
							</div>
						</article>
					<?php } ?>
				<?php $xh++; } ?>
				</div>
		<?php } ?>

	</div>
</section>

