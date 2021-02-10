<?php
/**
 * Enqueue scripts and styles.
 */
function acstarter_scripts() {
	wp_enqueue_style( 'acstarter-style', get_template_directory_uri() . '/style.css', array(), '2.0' );

	wp_deregister_script('jquery');
		//wp_register_script('jquery', get_template_directory_uri() .'/js/jquery-3.4.1.min.js', array(), '3.4.1', false);
		wp_register_script('jquery', get_template_directory_uri() .'/js/jquery.min.js', array(), '3.5.1', false);
		wp_enqueue_script('jquery');

	

	wp_enqueue_script( 
			'acstarter-blocks', 
			get_template_directory_uri() . '/assets/js/vendors.js', 
			array(), '1.2', 
			true 
		);

	wp_enqueue_script( 
			'acstarter-cookie', 
			get_template_directory_uri() . '/js/cookie.js', 
			array(), '2.2', 
			true 
		);

	wp_enqueue_script( 
			'acstarter-slider', 
			get_template_directory_uri() . '/assets/js/vendors/slick.min.js', 
			array(), '1.8', 
			true 
		);

	wp_enqueue_script( 
			'acstarter-custom', 
			get_template_directory_uri() . '/assets/js/custom.min.js', 
			array(), '1.2', 
			true 
		);

	wp_enqueue_script( 
		'font-awesome', 
		'https://use.fontawesome.com/8f931eabc1.js', 
		array(), '20180424', 
		true 
	);

	wp_enqueue_script( 
		'google-api', 
		'https://maps.googleapis.com/maps/api/js?key=AIzaSyAeqhZre9-4JooxIFFhcgGmWQ2de4Y4AhE', 
		array(), '20180424', 
		true 
	);

	//wp_enqueue_script( 'broadstreetads', '//cdn.broadstreetads.com/init-2.min.js', array( '' ), false, false );

	$vars = array(
			'url' => admin_url( 'admin-ajax.php' ),
			'postid'=>get_the_ID()
		);
		$tax = get_query_var( 'taxonomy' );
		$term = get_query_var( 'term' );
		if(!empty($tax)){
			$vars['tax']=$tax;
		} 
		if(!empty($term)){
			$vars['term']=$term;
		} 
		if(isset($_GET['date'])&&!empty($_GET['date'])){
			$vars['date']=$_GET['date'];
		} 
		if(isset($_GET['category'])&&!empty($_GET['category'])){
			$vars['category']=$_GET['category'];
		} 
		if(isset($_GET['search'])&&!empty($_GET['search'])){
			$vars['search']=$_GET['search'];
		} 
		wp_localize_script( 'custom', 'bellaajaxurl', $vars);



	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'acstarter_scripts' );

