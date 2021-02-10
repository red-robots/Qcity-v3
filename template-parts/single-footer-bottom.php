<?php

$footer_title_article_middle = get_field('footer_title_article_middle', 'option');
$footer_title_article_right = get_field('footer_title_article_right', 'option');
$post_id = get_the_ID();
$excludedPosts = get_trending_articles(5);
$trend = ($excludedPosts) ? count($excludedPosts) : 0;
$excludedPosts[$trend] = $post_id;
$excludeItems2 = array();
?>

<section class="home-bottom single-article-bottom">
	
     <?php /* NOT WORKING!! */ ?>
	<!-- <div class="jobs desktop-version">		
        <script async src="https://modules.wearehearken.com/qcitymetro/embed/4551.js"></script>
	</div> -->

    <!--- West End Connect -->
	<div class="biz-dir mobile-gap flexbox bgwhite" style="background-color: none">
        <div class="fxdata">
            <header class="before-footer-title ">
                <h2 ><?php echo $footer_title_article_middle; ?></h2>
            </header>
            <div class="footer-content-list txtwrap">
                <?php
                    $args = array(
                        'post_type'         => 'post',
                        'posts_per_page'    => 5,
                        'post_status'       => 'publish',  
                        'category_name'     => 'west-end',
                    );
                    
                    if($excludedPosts) {
                        $args['post__not_in'] = $excludedPosts;
                    }
                    $excludeItems2 = $excludedPosts;
                    $lastIndex = ($excludedPosts) ? count($excludedPosts) : 0;
                    $trending = new WP_Query( $args );
                    if( $trending->have_posts() ):                    
                        while( $trending->have_posts() ): $trending->the_post();
                            $guest_author =  get_field('author_name');
                            $author = ( $guest_author ) ? $guest_author : get_the_author();
                            $excludeItems2[$lastIndex] = get_the_ID();

                            echo '<div class="footer-content-list-item">';
                            echo '<h3><a href="'. get_permalink() .'">'. get_the_title() .'</a></h3>';
                            echo '<div class="footer-content-author"><span class="footer-author">'. $author .'</span> <span class="footer-content-date">'. date('M. j, Y', strtotime(get_the_date() )) .'</span></div>';
                            echo '</div>';
                        $lastIndex++; endwhile; ?>  

                        <div class="more footer-more"> 
                            <a href="<?php bloginfo('url'); ?>/category/west-end/" class="red " >        
                                <span class="load-text">Read More</span>                    
                            </a>
                        </div>

                        <div class="clearfix"></div>

                        <?php 
                    else:    

                        echo 'No posts available';

                    endif; 
                    wp_reset_postdata();
                ?> 
            </div>
        </div>
    </div>

    <?php /* NOT WORKING!! */ ?>
    <!-- <div class="jobs mobile-version">      
        <script async src="https://modules.wearehearken.com/qcitymetro/embed/4551.js"></script>
    </div> -->
	
	<!--- Trending Articles -->
	<div id="footTrending" class="ad flexbox bgwhite">
        <div class="fxdata">
            <header class="before-footer-title ">
                <h2 ><?php echo $footer_title_article_right; ?></h2>
            </header>
            <div class="footer-content-list txtwrap">
            <?php
                $args = array(
                        'post_type'         => 'post',
                        'posts_per_page'    => 5,
                        'post_status'       => 'publish',  
                        'orderby'           => 'date',
                        'order'             => 'DESC'
                );
                if($excludeItems2) {
                    $args['post__not_in'] = $excludeItems2;
                }
                $trending = new WP_Query( $args );
                if( $trending->have_posts() ):                    
                    while( $trending->have_posts() ): $trending->the_post();
                        $guest_author =  get_field('author_name');
                        $author = ( $guest_author ) ? $guest_author : get_the_author();
                        echo '<div class="footer-content-list-item">';
                        echo '<h3><a href="'. get_permalink() .'">'. get_the_title() .'</a></h3>';
                        echo '<div class="footer-content-author"><span class="footer-author">'. $author .'</span> <span class="footer-content-date">'. date('M. j, Y', strtotime(get_the_date() )) .'</span></div>';
                        echo '</div>';
                    endwhile;  
                    ?>

                    <div class="more footer-more"> 
                        <a href="<?php bloginfo('url'); ?>/category/news/" class="red " >        
                            <span class="load-text">Read More</span>                    
                        </a>
                    </div>
                    <div class="clearfix"></div>

                    <?php
                else:

                    echo 'No posts available';

                endif; 
                wp_reset_postdata();
            ?>
            </div>
        </div>
	</div>

    <!--- Advertisements -->
    <?php $ads_bottom = get_ads_script('business-directory-home'); ?>
    <?php if ($ads_bottom) { ?>
    <div id="adverts" class="ad flexbox">
        <div class="fxdata">
            <div class="desktop-version align-center txtwrap"> 
                <?php echo $ads_bottom['ad_script']; ?>
            </div>
        </div>
    </div>
    <?php } ?>

</section>