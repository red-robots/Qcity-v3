<?php
/**
 * Template Name: Front Page 
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package ACStarter
 */

get_header(); ?>
<div class="wrapper newHomeLayout">
	<div id="primary" class="content-area-full">
		<main id="main" class="site-main" role="main">

		<?php 
		$subscribe_text         = get_field('subscribe_text');
		$subscribe_link         = get_field('subscribe_link');
		$subscribe_button_name  = get_field('subscribe_button_name');
		$show_instagram_feeds  = get_field('show_instagram_feeds','option');

		include( locate_template('home-parts/top.php') );

		// include( locate_template('template-parts/hero.php') ); 
		include( locate_template('template-parts/sponsored-posts.php') );
		include( locate_template('template-parts/home-stories.php') );
		include( locate_template('template-parts/subscribe-bar.php') ); 

		$homeAD2 = get_field("home_ad2","option"); 
		if ($homeAD2) {  $ad_script = get_field("ad_script",$homeAD2); ?>
		<?php if ($ad_script) { ?>
		<div class="home-block-AD2 home-ad-fullwidth">
			<?php echo $ad_script ?>
		</div>	
		<?php } ?>
		<?php } 

		if($show_instagram_feeds=='on') {
			include( locate_template('template-parts/instagram-feeds.php') );
		}

		include( locate_template('template-parts/non-sticky-news.php') ); ?>

		<?php $homeAD3 = get_field("home_ad3","option"); 
		if ($homeAD3) {  $ad_script3 = get_field("ad_script",$homeAD3); ?>
			<?php if ($ad_script3) { ?>
			<div class="home-block-AD3 home-ad-fullwidth">
				<?php echo $ad_script3 ?>
			</div>	
			<?php } ?>
		<?php }  ?>

		<?php include( locate_template('template-parts/home-bottom.php') ); ?>
			
		</main><!-- #main -->
	</div><!-- #primary -->
</div>

<script type="text/javascript">
jQuery(document).ready(function($){
	if( $("#sponsoredPosts").length>0 ) {

		$("#sponsoredPosts").on('init', function(event, slick, direction){
			carouselInit();
		  $(window).on('resize',function(){
		  	carouselInit();
		  });
		});

		
		$("#sponsoredPosts").slick({
	    dots: true,
	    infinite: false,
	    variableWidth: true
	  });
	  
	  function carouselInit() {
	  	$('.carouselText').matchHeight();
		  var carouselWidth = $(".home-sponsored").width();
		  var bw = $(".slick-arrow").width();
		  var bbw = bw+10;
		  var wh = carouselWidth / 2;
		  var sb = wh-bbw;
		  $(".slick-prev").css("left",sb+"px");
		  $(".slick-next").css("right",sb+"px");
	  }
	}

	if( $("#homeStoriesMobile").length>0 ) {
		$("#homeStoriesMobile").slick({
	    dots: false,
	    infinite: false,
	    variableWidth: true
	  });
	}


	// convertStoriesToCarousel();
	// $(window).on('resize', function() {
 //    convertStoriesToCarousel();
	// });
	// function convertStoriesToCarousel() {
	// 	var viewportWidth = $(window).width();
	// 	if( $("#homeStories").length>0 ) {
	//     if (viewportWidth < 830 ) {
	//       $("#homeStories").slick({
	// 		    dots: true,
	// 		    infinite: false,
	// 		    variableWidth: true,
	// 		  });
	//     } else {
	//     	$("#homeStories").slick('unslick');
	//     }
	//   }
	// }

	/* Change the subscribe button name */
  // if( $(".form-subscribe-blue .gform_footer").length>0 ) {
  // 	$(".form-subscribe-blue .gform_footer input.gform_button").addClass('textchange');
  // 	$(".form-subscribe-blue .gform_footer input.gform_button").val("Subscribe");
  // }


});
</script>
<?php
get_footer();
