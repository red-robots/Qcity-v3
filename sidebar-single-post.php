<?php
$placeholder = get_template_directory_uri() . '/images/rectangle.png';
$current_post_id = get_the_ID();
$postType = get_post_type();
$perpage = 5;
global $wpdb;
$current_date = date('Y-m-d');
$currentMonth = date('m');
$previousMonth = $currentMonth-1;
$currentYear = date('Y');
$currentDateUnix = strtotime(date('Y-m-d'));
$listMax = 5;
$maxdays = 10;
$trendingArticles = array();
$trendingPostIDs = array();
for( $i=1; $i<=$maxdays; $i++ ) {
	$min = "-".$i." days";
	$prevdate = date('Y-m-d',strtotime($min));
	$query = "SELECT p.ID, p.post_title, p.post_date, meta.meta_value AS views FROM ".$wpdb->prefix."posts p LEFT JOIN ".$wpdb->prefix."postmeta meta
	ON p.ID=meta.post_id WHERE meta.meta_key='views' AND meta.meta_value>0 AND p.post_type='post' AND p.post_status='publish' AND DATE(p.post_date)='".$prevdate."'";
	$result = $wpdb->get_results($query);
	if($result) {
		foreach($result as $row) {
			$trendingArticles[] = $row;
		}
	}
}


if($trendingArticles) {
	$keys = array_column($trendingArticles, 'views');
	array_multisort($keys, SORT_DESC, $trendingArticles);
	foreach($trendingArticles as $t) {
		$trendingPostIDs[] = $t->ID;
	}
}

$entries = array();
$raw_entries = array();
if($trendingPostIDs) {
	$trending_count = count($trendingPostIDs);
	// $c=1; foreach($trendingPostIDs as $id) {
	// 	$raw_entries[] = $id;
	// 	$c++;
	// }
	//arsort($raw_entries);
	//asort($trendingPostIDs);
	if($trending_count>$listMax) {
		// $lastKey = array_key_last($entries);
		// unset($entries[$lastKey]);
		for($n=0; $n<$listMax; $n++) {
			$entries[] =  $trendingPostIDs[$n];
		}
	} else {
		$entries = $trendingPostIDs;
	}
}

$ads = get_field("trending_ads","option"); 
$adList = array();

/* TRENDING ARTICLES */
if($postType=='post') { ?>
	
<?php if ($entries || $ads) { ?>
  <aside id="singleSidebar" class="singleSidebar stickySidebar">
  	<div class="helper"></div>
  	<?php 
  	if($ads) {
  		foreach($ads as $a) {
  			$id = $a->ID;
  			$adScript = get_field('ad_script',$id);
  			if($adScript) {
  				$adList[] = $adScript;
  			}
  		}
  	}
  	?>
  	
  	<div id="sidebar-single-post">
	  	<?php if ($adList) { ?>
	  	<div class="sideBarAds">
	  		<?php foreach ($adList as $ad) { ?>
	  			<div class="adBox"><?php echo $ad ?></div>
	  		<?php } ?>
	  	</div>
	  	<?php } ?>

	  	<?php if($trendingPostIDs) { ?>
	
		  	<?php if( $entries ) { ?>
		  	<div class="trending-sidebar-wrap">
					<div id="sbContent">
						<div class="sbWrap">
							<h3 class="sbTitle">Trending</h3>
							<ol class="trending-entries">
								<?php $i=1; foreach($entries as $pid) {
									$img  = get_field('event_image',$pid);
									$image = '';
									$altImg = '';
									if( $img ){
									  $image = $img['url'];
									  $altImg = ( isset($img['title']) && $img['title'] ) ? $img['title']:'';
									} elseif ( has_post_thumbnail($pid) ) {
										$thumbid = get_post_thumbnail_id($pid);
									  $image = get_the_post_thumbnail_url($pid);
									  $altImg = get_the_title($thumbid);
									} 
									$viewsCount = get_post_meta($pid,'views',true);
									$postDate = get_the_date('d/m/Y',$pid);
									$pagelink = get_permalink($pid);
									$posttitle = get_the_title($pid); ?>
									<li class="entry" data-pid="<?php echo $pid ?>" data-postdate="<?php echo $postDate ?>" data-views="<?php echo $viewsCount ?>">
										<?php if ($i==1) { ?>
											<?php if ($image) { ?>
											<div class="trendImg">
												<a href="<?php echo $pagelink; ?>"><img src="<?php echo $image ?>" alt="<?php echo $altImg; ?>"></a>
											</div>	
											<?php } ?>
										<?php } ?>
										<p class="headline"><a href="<?php echo $pagelink; ?>"><?php echo $posttitle; ?></a></p>
									</li>
								<?php $i++; } ?>
							</ol>
						</div>
					</div>
				</div>
				<?php } ?>

			<?php } ?>
		</div>
  </aside>
<?php } ?>

<?php } ?>

