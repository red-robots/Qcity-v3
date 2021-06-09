<?php
/* Exclude posts from this category */
// $excludeTerm = 'sponsored-post';
// $excludeCat 	= get_category_by_slug($excludeTerm); 
// $excludeCatID 	= ( isset($excludeCat->term_id) && $excludeCat->term_id ) ? $excludeCat->term_id : '';

$exclude_categories = array('sponsored-post','commentaries','sponsored-post');
$excludePosts = getAllPostsByTermSlug( $exclude_categories );
$excludeCatID = getAllCategoriesByTermSlug($exclude_categories,'category');
$stickyPosts = get_option('sticky_posts');
$rightPostItems = (get_stick_to_right_posts(3)) ? get_stick_to_right_posts(3) : array();
$postWithVideos = (get_news_posts_with_videos()) ? get_news_posts_with_videos() : array();
$exlude_post_ids = array();
$moreNewsItems = getMoreNewsPosts(3);
$moreNewsIDs = array();
if($moreNewsItems) {
	foreach($moreNewsItems as $m) {
		$moreNewsIDs[] = $m->ID;
	}
}

if($rightPostItems || $postWithVideos) {
	$newDataList = array();
	if($rightPostItems) {
		foreach($rightPostItems as $x) {
			$newDataList[] = $x;
		}
	}
	$nLast = ($newDataList) ? count($newDataList) : 0;
	if($postWithVideos){
		if($postWithVideos) {
			$j=$nLast;
			foreach($postWithVideos as $xx) {
				$newDataList[$j] = $xx;
				$j++;
			}
		}
	}
	//$rightPostItems = array_merge($rightPostItems,$postWithVideos);
	$rightPostItems = ($newDataList) ? array_unique($newDataList) : array();
}

$featured_posts = array();
$mainArgs = array(
	'post_type'    => 'post',
	'posts_per_page'    => 1,
	'post_status'  => 'publish',
	'orderby'	=> 'date', 
	'order'		=> 'DESC',
);

if($excludePosts) {
	$exlude_post_ids = $excludePosts;
}
if($rightPostItems) {
	if($exlude_post_ids) {
		$exlude_post_ids = array_merge($exlude_post_ids,$rightPostItems);
	} else {
		$exlude_post_ids = $rightPostItems;
	}
}

$postsNotIn = ($exlude_post_ids) ? array_unique($exlude_post_ids) : array();
if($postsNotIn) {
	if($moreNewsIDs) {
		$postsNotIn = array_merge($postsNotIn,$moreNewsIDs);
	}
} else {
	if($moreNewsIDs) {
		$postsNotIn = $moreNewsIDs;
	}
}
if($postsNotIn) {
	$mainArgs['post__not_in'] = $postsNotIn;
	foreach($postsNotIn as $p) {
		$featured_posts[] = $p;
	}
}


// if($rightPostItems) {
// 	if($exlude_post_ids) {
// 		$mergedIds = array_merge($rightPostItems,)
// 	} else {
// 		$exlude_post_ids = $rightPostItems;
// 	}
// 	//$mainArgs['post__not_in'] = $rightPostItems;
// }


// if($excludeCatID) {
// 	$mainArgs['tax_query'] = array(
// 	    array(
// 	        'taxonomy' => 'category',
// 	        'field'    => 'id',
// 	        'terms'    => $excludeCatID,
// 	        'operator' => 'NOT IN'
// 	    )
// 	);
// }


$bigPost = array();
if($stickyPosts) {
	$ids = '';
	arsort($stickyPosts);
	$mainPostId = $stickyPosts[0];
	$mainPost = get_post($mainPostId);
	if($mainPost) {
		$bigPost = $mainPost;
	}
} else {
	$mainPost = get_posts($mainArgs);
	if($mainPost) {
		$bigPost = $mainPost[0];
	}
}

?>
<section id="homeTopElements" class="stickies-new stickyPostsTop">

	<div class="left stickLeft">
		<?php /* BIG POST */ ?>
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

		<?php /* More News and Commentary */ ?>
		<?php
			$entries = getMoreNewsPosts();  
			$commentaries = getCommentaryPosts();
			$twoCol = ($entries && $commentaries) ? 'twocol':'onecol';
		?>
		<?php if ($entries || $commentaries) { ?>
		<div class="more-news-commentaries <?php echo $twoCol?>">
			<?php 
				get_template_part( 'home-parts/more-news'); 
				get_template_part( 'home-parts/commentary-posts');
			?>
		</div>	
		<?php } ?>
		
	</div>


	<?php /* RIGHT POSTS */ ?>
	<div class="right stickRight">
		<?php  /* subscription form */
		get_template_part( 'home-parts/subscribe-form');
		include( locate_template('home-parts/top-right-posts.php') );
		?>
	</div>

</section>

