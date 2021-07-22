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
<?php 
$stickyHeaderCode = get_field("stickyHeaderCode","option"); 
$stickyAdCode = get_field("stickyAdCode","option");
$stickyAdEnable = get_field("stickyAdEnable","option");
$is_sticky_on = ( isset($stickyAdEnable) && $stickyAdEnable=='on' ) ? true : false;
?>
<script async='async' src='https://www.googletagservices.com/tag/js/gpt.js'></script>
<script>
  var googletag = googletag || {};
  googletag.cmd = googletag.cmd || [];
</script>
<?php if ($stickyHeaderCode) { echo $stickyHeaderCode; } ?>
<?php //get_template_part('parts/adcode-header'); ?>
<?php if( $ads_scripts = getHeaderScripts() ) { foreach($ads_scripts as $js) { echo $js; } } ?>
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
$current_page_id = (is_page()) ? get_the_ID() : 0;
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

<?php 
if( $customHeadScripts = get_field("custom_scripts_inside_head","option") ) { 
  echo $customHeadScripts;
} ?>
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
$hasPoweredByLogo = ($current_page_id) ? get_page_with_top_logo($current_page_id) : '';
$bodyClass = ($hasPoweredByLogo) ? 'hasPoweredByLogo':'';
$is_member_page = false;
if( is_page() ) {
  $pageTemplate = get_page_template_slug($current_page_id);
  if($pageTemplate=="page-membership-new.php") {
    $is_member_page = true;
  }
}

?>
<body <?php body_class($bodyClass); ?> data-today="<?php echo date('Ymd') ?>" data-dates="<?php echo $start_end ?>" data-range="<?php echo $dateRange ?>">

<?php if ( $is_sticky_on && ($stickyHeaderCode && $stickyAdCode) ) { ?>
<div id="stickyBottomAd" class="show-ad">
  <a id="stickyBottomAdClose"><span>Hide Ad</span></a>
  <div id="stickyBottomAdContent" style="display:block"><?php echo $stickyAdCode ?></div>
</div>
<?php } ?>

<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'acstarter' ); ?></a>

  <?php if (!$is_member_page) { ?>
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
          $mobileJoinBtn = get_field("mainNavRedButtonMobile","option");
          $mobileRedBtnName = ( isset($mobileJoinBtn['title']) && $mobileJoinBtn['title'] ) ? $mobileJoinBtn['title'] : '';
          $mobileRedBtnLink = ( isset($mobileJoinBtn['url']) && $mobileJoinBtn['url'] ) ? $mobileJoinBtn['url'] : '';
          $mobileRedBtnTarget = ( isset($mobileJoinBtn['target']) && $mobileJoinBtn['target'] ) ? $mobileJoinBtn['target'] : '_self';
          $mobile_join_button  = '';
          if($mobileRedBtnName && $mobileRedBtnLink) {
            $mobile_join_button = '<a href="'.$mobileRedBtnLink.'" target="'.$mobileRedBtnTarget.'" class="mobile-join-btn">'.$mobileRedBtnName.'</a>';
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
                'items_wrap' => $mobile_join_button . '<ul id="primary-menu" class="with-custom-link %2$s">%3$s</ul>'
              )
            ); 
          ?>
				</div>
				<?php wp_nav_menu(array('theme_location'=>'burger','menu_class'=>'main','container'=>'ul')); ?>
			</nav>

    </div>      
	
	</header><!-- #masthead -->
  <?php } ?>

	<div id="content" class="site-content mobile-body">

  <?php get_template_part('template-parts/top-page-ads-backup'); ?>
   
