<?php
/**
 * Template Name: Custom Search
 * 
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package ACStarter
 */

get_header(); 


$search_text 	= (isset( $_GET['s'] ) && !empty( $_GET['s'] ) ) ? sanitize_text_field($_GET['s']) : '';
$paged 			= (int) ( isset($_GET['pg']) && $_GET['pg'] ) ? $_GET['pg'] : 1;
$perpage 		= (int) ( isset($_GET['perpage']) && $_GET['perpage'] ) ? $_GET['perpage'] : 10;
?>



<?php   ?>

<div class="wrapper">
	<div class="content-area-single">		
		<header class="entry-header toppage">
			<h1 class="entry-title">Searching for: <span class="color-golden"><?php echo $search_text; ?></span></h1>	
		</header>
	</div>
</div>

	
<div class="wrapper">
	<div id="primary" class="content-area-single">
		<?php
			
			$entries 	= isset( $entries ) ? $entries : array();
			$lists 		= isset($lists) ? $lists : array();

			if( empty( $entries ) ):

				$args = array(
					'post_type' 		=> 'post',
					'post_status'       => 'publish',
			        'order'             => 'DESC',
			        'orderby'           => 'date',
			        'posts_per_page'    => -1,
					's' 				=> $search_text,
					//'paged' 			=> get_query_var('pg')
				);
				$query = get_posts( $args );

				foreach ($query as $post) :		
					$entries[] = array(
								'ID' => $post->ID,
								'post_title' => $post->post_title,
								'content' => substr( sanitize_text_field($post->post_content), 0, 170),
								'url' => $post->guid,
								'date' => $post->post_date

					);	
				endforeach;	
				wp_reset_postdata();

			endif;	// if( empty( $entries ) )

						
			echo '<div class="search_results">';
			if( ! empty(  $entries ) ):		

				if( count($entries) > 0 ):
					echo '<p>Found: <strong>' . count( $entries ) . '</strong></p>'; 
					$end = ($paged * $perpage) - 1;
			        $start = ($paged==1) ? 0 : ($end - $perpage) + 1;
			        
			        for($x=$start; $x<=$end; $x++) {
			            if( isset($entries[$x]) ) {
			                $lists[$x] = $entries[$x];
			            }
			        }
				endif; // if( count($entries) > 0 ):

				
				if( count($lists) > 0 ):
					foreach( $lists as $post ):						
					 ?>
						<div class="search_results_item">
							<h3><a href="<?php echo esc_url($post['url']); ?>"><?php echo $post['post_title']; ?></a> </h3>
							<small><?php  echo date('F j, Y', strtotime($post['date']) ); ?></small>
							<p><?php echo esc_attr( $post['content'] ); ?> [...]</p>
						</div>
				<?php	endforeach; // foreach( $lists as $post ):
				endif; //  if( count($lists) > 0 ):

				$total = ($query) ? count($query) : 0;

				if( $total > 1 ) : ?>
					<div id="pagination" class="pagination pagination-search navigation" data-pageurl="">
			            <?php if ($total <= $perpage ) { ?>
			            <?php echo ''; ?>    
			            <?php } else { ?>
			                <?php
			                echo paginate_links( array(
			                    'base' => @add_query_arg('pg','%#%'),
			                    'format' => '',
			                    'current' => $paged,
			                    'total' => ceil($total / $perpage),
			                    'prev_text' => __( '&laquo;'),
			                    'next_text' => __( '&raquo;'),
			                    'type' => 'plain'
			                ) );
			                ?>
			            <?php } ?>
			        </div>
			
			<?php	endif; // if( $total > 1 ) :				?>

		<?php
			endif; 

			
		?>
		</div>
	</div>	
</div>

</div>

<?php

get_footer(); 
