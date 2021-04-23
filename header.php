<?php
/**
 * The header for theme.
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package ACStarter
 */

?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
<link rel="preconnect" href="https://fonts.gstatic.com">
<link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,600;0,700;0,800;1,300;1,400;1,600;1,700;1,800&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
<script defer src="<?php bloginfo( 'template_url' ); ?>/assets/svg-with-js/js/fontawesome-all.js"></script>

<script async='async' src='https://www.googletagservices.com/tag/js/gpt.js'></script>
<script>
  var googletag = googletag || {};
  googletag.cmd = googletag.cmd || [];
</script>
<script>
window.googletag = window.googletag || {cmd: []};
  googletag.cmd.push(function() {
    googletag.defineSlot('/1009068/In-Story_Gutenbers', [300, 250], 'div-gpt-ad-1563273598512-0').addService(googletag.pubads());
    googletag.pubads().enableSingleRequest();
    googletag.enableServices();
  });
  
 window.googletag = window.googletag || {cmd: []};
  googletag.cmd.push(function() {
    googletag.defineSlot('/1009068/Gutenburg_720x90', [728, 90], 'div-gpt-ad-1563293411913-0').addService(googletag.pubads());
    googletag.pubads().enableSingleRequest();
    googletag.enableServices();
  });

    window.googletag = window.googletag || {cmd: []};
  googletag.cmd.push(function() {
    googletag.defineSlot('/1009068/600x200', [600, 200], 'div-gpt-ad-1563224728772-0').addService(googletag.pubads());
    googletag.pubads().enableSingleRequest();
    googletag.pubads().collapseEmptyDivs();
    googletag.enableServices();
  });
</script>

<script async src="https://securepubads.g.doubleclick.net/tag/js/gpt.js"></script>
<script>
  window.googletag = window.googletag || {cmd: []};
  googletag.cmd.push(function() {
    googletag.defineSlot('/1009068/In-Story_Gutenbers', [[300, 250], [600, 200], [728, 90]], 'div-gpt-ad-1565127901858-0').addService(googletag.pubads());
    googletag.pubads().enableSingleRequest();
    googletag.enableServices();
  });
</script>
<script>
var ajaxURL = "<?php echo admin_url('admin-ajax.php'); ?>";
var assetsDIR = "<?php echo get_bloginfo("template_url") ?>/images/";
var currentURL = '<?php echo get_permalink();?>';
var params={};location.search.replace(/[?&]+([^=&]+)=([^&]*)/gi,function(s,k,v){params[k]=v});
var jobsCount = '<?php echo get_category_counter('job'); ?>';
var eventsCount = '<?php echo get_total_events_by_date(); ?>';
</script>
<!--
<script type="text/javascript"async src="https://launch.newsinc.com/js/embed.js" id="_nw2e-js"></script>
-->
<?php wp_head(); ?>
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<?php 
$ob = get_queried_object();
$current_term_id = ( isset($ob->term_id) && $ob->term_id ) ? $ob->term_id : '';
$current_term_name = ( isset($ob->name) && $ob->name ) ? $ob->name : '';
$current_term_slug = ( isset($ob->slug) && $ob->slug ) ? $ob->slug : '';
$electionCatId = get_field("elect_which_category","option");
$electionCatId = ($electionCatId) ? $electionCatId : '-1';
if ( get_post_type()=='story')  { 
$articles = get_field("story_article"); 
if($articles) {
  $story = $articles[0];
  $images = $story['images'];
  $text = ( isset($story['post_content']) && $story['post_content'] ) ? $story['post_content']:'';
  $content = ($text) ? shortenText(strip_tags($text),200," ","...") : '';
  $photos = ( isset($images['photos']) && $images['photos'] ) ? $images['photos']:"";
  $mainPic = ($photos) ? $photos[0] : '';
}
?>
<meta property="og:url"                content="<?php echo get_permalink(); ?>" />
<meta property="og:type"               content="article" />
<meta property="og:title"              content="<?php echo get_the_title(); ?>" />
<meta property="og:description"        content="<?php echo $content ?>" />
<?php if ($mainPic) { ?>
<meta property="og:image"              content="<?php echo $mainPic['url'] ?>" />
<?php } ?>
<?php } ?>
<style type="text/css">
html body.single-post .oakland-background.oakland-optin-visible.oakland-lightbox,
body.single-post .oakland-background.oakland-optin-visible.oakland-lightbox{display:none!important;}
.gform_wrapper ul li.gfield{clear: none !important;}
</style>
</head>
<?php
$dd = date('d') - 1;
$day = str_pad($dd,2,'0',STR_PAD_LEFT);
$nexday = str_pad($dd+1,2,'0',STR_PAD_LEFT);
$dateToday = date('Ym') . $day;
$dateRange = '';
for($i=0; $i<3; $i++) {
  $d = $day + $i;
  $days = str_pad($d,2, '0', STR_PAD_LEFT);
  $comma = ($i>0) ? ',':'';
  $dateRange .= $comma . date('Ym'). $days;
}
$start_end = $dateToday . ',' . date('Ym') . $nexday;
?>
<body <?php body_class(); ?> data-today="<?php echo date('Ymd') ?>" data-dates="<?php echo $start_end ?>" data-range="<?php echo $dateRange ?>">
<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'acstarter' ); ?></a>

	<header id="masthead" class="site-header " role="banner" >

    <div class="mobile-stick" id="fixed" >
      <div class="wrapper-header ">
        <div class="logo">
        	<a href="<?php bloginfo('url'); ?>" style="background: transparent;">
          	<img src="<?php bloginfo('template_url'); ?>/images/qc-logo.png" alt="<?php bloginfo('name'); ?>">
          </a>
        </div>

        <?php
        $instagram = get_field("instagram_link_short","option"); 
        $headerBtnLink = get_field("header_button_mobile_view","option");
        $headerBtnTarget = ( isset($headerBtnLink['target']) && $headerBtnLink['target'] ) ? $headerBtnLink['target'] : '_self';
        if($headerBtnLink) { ?>
        <div class="newsletter-link" >
            <a href="<?php echo $headerBtnLink['url']?>" target="<?php echo $headerBtnTarget ?>" class="news-letter-btn btn2"><?php echo $headerBtnLink['title']?></a>
        </div>
        <?php } ?>
    </div>

          <?php  
          $topSubscribe = get_field("topSubscribe","option");
          $subscribeText = ( isset($topSubscribe['subscribe_text']) && $topSubscribe['subscribe_text'] ) ? $topSubscribe['subscribe_text']:'';
          $subscribeButton = ( isset($topSubscribe['subscribe_button']) && $topSubscribe['subscribe_button'] ) ? $topSubscribe['subscribe_button']:'';
          $subscribeName = ( isset($subscribeButton['title']) && $subscribeButton['title'] ) ? $subscribeButton['title']:'';
          $subscribeURL = ( isset($subscribeButton['url']) && $subscribeButton['url'] ) ? $subscribeButton['url']:'';
          $subscribeTarget = ( isset($subscribeButton['target']) && $subscribeButton['target'] ) ? $subscribeButton['target']:'_self';
          $redButton = get_field("mainNavRedButton","option");
          $redButtonName = ( isset($redButton['title']) && $redButton['title'] ) ? $redButton['title'] : '';
          $redButtonLink = ( isset($redButton['url']) && $redButton['url'] ) ? $redButton['url'] : '';
          $redButtonTarget = ( isset($redButton['target']) && $redButton['target'] ) ? $redButton['target'] : '_self';
          $customMenuLink = '';
          if($redButtonName && $redButtonLink) {
            $customMenuLink = '<li class="menu-item red-button-link"><a href="'.$redButtonLink.'" target="'.$redButtonTarget.'" class="headerRedBtn redbutton">'.$redButtonName.'</a></li>';
          }
          ?>
          <?php if ($subscribeText || $subscribeButton) { ?>
          <section class="red-band">
            <div class="wrapper">
              <?php echo $subscribeText ?>
              <?php if ($subscribeButton) { ?>
                <a href="<?php echo $subscribeURL ?>" target="<?php echo $subscribeTarget ?>" class="topSubscribeBtn"><?php echo $subscribeName ?></a>
              <?php } ?>
            </div>
          </section>
          <?php } ?>

	        <div class="mainnav-wrap">
	        	<div class="wrapper-mnav">
					<nav id="site-navigation" class="main-navigation " role="navigation">
                        
						<div class="wrapper" >
                            
							<div class="burger">
							  <span></span>
							</div>
							<?php 
                wp_nav_menu( 
                  array( 
                    'theme_location' => 'primary', 
                    'menu_id' => 'primary-menu', 
                    'menu_class'=>'desktop-version',
                    'echo' => true,
                    'items_wrap' => '<ul id="primary-menu" class="with-custom-link %2$s">%3$s'.$customMenuLink.'</ul>'
                  )
                ); 
              ?>
              <?php //get_search_form(); ?>
						</div>
					</nav><!-- #site-navigation -->
				</div>
			</div>
			<nav class="mobilemenu">
				<div class="mobilemain">
					<?php //wp_nav_menu( array( 'theme_location' => 'primary', 'menu_id' => 'primary-menu' ) ); 
            wp_nav_menu( 
              array( 
                'theme_location' => 'primary', 
                'menu_id' => 'primary-menu', 
                'menu_class'=>'mobile-version',
                'echo' => true,
                'items_wrap' => '<ul id="primary-menu" class="with-custom-link %2$s">%3$s'.$customMenuLink.'</ul>'
              )
            ); 
          ?>
				</div>
				<?php wp_nav_menu(array('theme_location'=>'burger','menu_class'=>'main','container'=>'ul')); ?>
			</nav>

    </div>      
	
	</header><!-- #masthead -->

	<div id="content" class="site-content mobile-body">

  <?php 
  /* Black on the map page id = 181208 */
  $current_page_id = (is_page()) ? get_the_ID() : 0;
  $template_slug = get_page_template_slug($current_page_id);
  $show_ads = true;
  if($template_slug=='page-black-map.php') {
    $show_ads = false;
  }
  if( $show_ads ) {
    if($electionCatId!=$current_term_id) { ?>
      <?php if ( $ads_header = get_ads_script('leaderboard-ad-home') ) { ?>
      <div class="ads_home_leaderboard">
        <?php echo $ads_header['ad_script'] ?>
      </div>
      <?php } ?>
    <?php } ?>
  <?php } ?>
   
