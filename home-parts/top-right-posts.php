<?php
global $wpdb;
// $exclude_categories = array('sponsored-post','commentaries','sponsored-post');
// $excludeCatID = getAllCategoriesByTermSlug($exclude_categories,'category');
// $moreNewsItems = getMoreNewsPosts(3);
// $postWithVideos = (get_news_posts_with_videos()) ? get_news_posts_with_videos() : array();
$maxItems = 3;
//$right_posts = get_stick_to_right_posts($maxItems);
$right_posts = ( isset($rightPostItems) && $rightPostItems ) ? $rightPostItems : '';

if( isset($postWithVideos) && $postWithVideos ) {
	if($right_posts) {
		foreach($right_posts as $k=>$id) {
			if(in_array($id,$postWithVideos)) {
				unset($right_posts[$k]);
			}
		}
	}
}

if(isset($featured_posts) && $featured_posts) {
	$featured_posts = $featured_posts;
} else {
	$featured_posts  = array();
}

$xLast = (isset($featured_posts) && $featured_posts) ? count($featured_posts):0;
if($excludeCatID) {
	foreach($excludeCatID as $catid) {
		$query = "SELECT p.ID FROM ".$wpdb->prefix."posts p, ".$wpdb->prefix."term_relationships rel
		      WHERE p.ID=rel.object_id AND rel.term_taxonomy_id=".$catid." AND p.post_type='post' AND p.post_status='publish'";
		$result = $wpdb->get_results($query);
		if($result) {
			foreach($result as $row) {
				$featured_posts[$xLast] = $row->ID;
				$xLast++;
			}
		}
	}
}

$featured_posts = ($featured_posts) ? array_unique($featured_posts) : array();
$xLast = (isset($featured_posts) && $featured_posts) ? count($featured_posts):0;
$right_post_count = ($right_posts) ? count($right_posts) : 0;

if($right_post_count<$maxItems) {
	$rr = array(
		'post_type'    => 'post',
		'posts_per_page'    => $maxItems,
		'post_status'  => 'publish',
		'orderby'	=> 'date', 
		'order'		=> 'DESC',
	);
	

	if( isset($postWithVideos) && $postWithVideos ) {
		foreach($postWithVideos as $pv) {
			$featured_posts[$xLast] = $pv;
			$xLast++;
		}
	}
	
	/* Exclude any posts with videos */
	if($featured_posts) {
		$rr['post__not_in'] = $featured_posts;
	}

	$xx_right_posts = get_posts($rr);
	if($xx_right_posts) {
		foreach($xx_right_posts as $x) {
			$right_posts[$right_post_count] = $x->ID;
			$right_post_count++;
		}
	}
}

if($right_items = $right_posts) { ?>
	<div class="stickRightBlockWrapper">
	<?php $xh=1; foreach ($right_items as $right_id) {
		if($xh<=$maxItems) {
			$e = get_post($right_id); 
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



