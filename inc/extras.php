<?php
/**
 * Custom functions that act independently of the theme templates.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package ACStarter
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */

define('THEMEURI',get_template_directory_uri() . '/');


function acstarter_body_classes( $classes ) {
	// Adds a class of group-blog to blogs with more than 1 published author.
	if ( is_multi_author() ) {
		$classes[] = 'group-blog';
	}

	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}

	return $classes;
}
add_filter( 'body_class', 'acstarter_body_classes' );


function search_filter_church($query) {
    if ( ! is_admin() && $query->is_main_query() ) {
        if ( $query->is_search ) {
            $query->set( 'post_type', 'church_listing' );
        }
    }
}
add_action( 'pre_get_posts', 'search_filter_church' );

function search_filter_business($query) {
    if ( ! is_admin() && $query->is_main_query() ) {
        if ( $query->is_search ) {
            $query->set( 'post_type', 'business_listing' );
        }
    }
}
add_action( 'pre_get_posts', 'search_filter_business' );


function email_obfuscator($string) {
    $output = '';
    if($string) {
        $emails_matched = ($string) ? extract_emails_from($string) : '';
        if($emails_matched) {
            foreach($emails_matched as $em) {
                $encrypted = antispambot($em,1);
                $replace = 'mailto:'.$em;
                $new_mailto = 'mailto:'.$encrypted;
                $string = str_replace($replace, $new_mailto, $string);
                $rep2 = $em.'</a>';
                $new2 = antispambot($em).'</a>';
                $string = str_replace($rep2, $new2, $string);
            }
        }
        $output = apply_filters('the_content',$string);
    }
    return $output;
}


add_filter( 'taxonomy_archive ', 'slug_tax_event_category' );
function slug_tax_event_category( $template ) {
    if ( is_tax( 'event_cat' ) ) {
         global $wp_query;
         $page = $wp_query->query_vars['paged'];
        if ( $page = 0 ) {
            $template = get_stylesheet_directory(). '/taxonomy-event-category.php';
        }
    }

    return $template;

}

/* Options page under Story custom post type */
if( function_exists('acf_add_options_page') ) {
    acf_add_options_sub_page(array(
        'page_title'     => 'Story Options',
        'menu_title'    => 'Story Options',
        'parent_slug'    => 'edit.php?post_type=story',
    ));

    acf_add_options_sub_page(array(
        'page_title'     => 'Elections Options',
        'menu_title'    => 'Elections Options',
        'parent_slug'    => 'edit.php',
    ));
}

/*
*   Related Posts by category
*/

function qcity_related_posts() {
 
    $post_id = get_the_ID();
    $cat_ids = array();
    $categories = get_the_category( $post_id );
 
    if ( $categories && !is_wp_error( $categories ) ) {
 
        foreach ( $categories as $category ) {
 
            array_push( $cat_ids, $category->term_id );
 
        }
 
    }

    $current_post_type = get_post_type( $post_id );
     
    $args = array(
        'category__in'      => $cat_ids,
        'post_type'         => $current_post_type,
        'post_status'       => 'publish',
        'posts_per_page'    => '5',
        'post__not_in'      => array( $post_id )
    );

    $query = new WP_Query( $args );
 
    if ( $query->have_posts() ) :   ?>
        <aside class="related-posts">
            <h3>
                <?php _e( 'Related Posts', 'qcity' ); ?>
            </h3>
            <ul class="related-posts">
                <?php
     
                    while ( $query->have_posts() ) :
     
                        $query->the_post();
     
                        ?>
                        <li>
                            <a href="<?php the_permalink(); ?>">
                                <?php the_title(); ?>
                            </a>
                        </li>
                        <?php
     
                    endwhile;
     
                ?>
            </ul>
        </aside>
        <?php
     
    endif;
     
    wp_reset_postdata();
 
}


/*
*   Youtube iframe setup
*/

function youtube_setup( $src ){
    $url = '';
    if (strpos($src,'youtu.be') !== false) {
        $parts = explode('youtu.be/',$src);
        if($parts && count($parts)>1) {
            $id = $parts[1];
            $url = "https://www.youtube.com/embed/" . $id;
        }
    } else {
        if( strpos($src,'youtube.com/watch') ) {
            parse_str( parse_url( $src, PHP_URL_QUERY), $query);
            $id = $query['v'];
            $url = "https://www.youtube.com/embed/" . $id;
        }
    }
    return $url;
}

/*
*   Advertisements
*/

function get_ads_script($slug)
{
    $ad_script = '';
    $ads_params = array();

    $ad_post = get_page_by_path( $slug, OBJECT, 'ad' );

    $args = array(       
        'post_type'         => 'ad',
        'post_status'       => 'publish',
        'p'                 => $ad_post->ID,        
    );

    $query = new WP_Query( $args );

    if ( $query->have_posts() ) :   

         while ( $query->have_posts() ) :
     
            $query->the_post();

            $ad_enable = get_field('enable_ad');
            if( $ad_enable == 'Yes' ):

                $ad_script      = get_field('ad_script');
                $ads_label      = get_field('ads_label');
                $ads_link_text  = get_field('ads_link_text');
                $ads_link_url   = get_field('ads_link_url');

                $ads_params = array(
                    'ad_script'     => $ad_script,
                    'ads_label'     => $ads_label,
                    'ads_link_text' => $ads_link_text,
                    'ads_link_url'  => $ads_link_url
                );            
            endif;

        endwhile;

    endif;

    wp_reset_postdata();

    return $ads_params;
}

/*
*   Search for Jobs content
*/

function have_content( $term_id )
{
    $content = false;
    $args = array(
            'post_type'     => 'job',
            'post_status'   => 'publish',
            'tax_query' => array( 
                    array(
                        'taxonomy' => 'job_cat',
                        'field' => 'id',
                        'terms' => $term_id,
                        'include_children' => false,
                        'operator' => 'IN'
                      )
            )

    );

    $query = new WP_Query( $args );

    if( $query->have_posts() ):

        $content = true;

    else:  

        $content = false;

    endif;
    wp_reset_postdata();

    return $content;
}

/*
*   Adding Ads inside single article
*/


add_filter('the_content', 'qcity_add_incontent_ad');
function qcity_add_incontent_ad( $content )
{   
    $hide_ads = (defined('HIDE_ADS') && HIDE_ADS ) ? true : false;
    if( is_single() && ( get_post_type() === 'post' ) && !$hide_ads ){
        $content_block  = explode('<p>',$content);
        $ads_6th        = get_ads_script('single-article-after-6th-paragraph');
        $ads_12th       = get_ads_script('single-article-after-12th-paragraph');
        if( !empty($content_block[5]) && $ads_6th)
        {               
            $content_block[5] .= '</div>
                            <div class="brown-bar"><div class="qcity-ads-label">'. $ads_6th['ads_label'] .' <a href="'. $ads_6th['ads_link_url'] .'">'. $ads_6th['ads_link_text'] .'</a> </div>'. $ads_6th['ad_script'] .'</div>
                            <div class="content-single-page">';
        }
        if( !empty($content_block[11]) && $ads_12th)
        {               
            $content_block[11] .= '</div>
                            <div class="brown-bar"><div class="qcity-ads-label">'. $ads_12th['ads_label'] .' <a href="'. $ads_12th['ads_link_url'] .'">'. $ads_12th['ads_link_text'] .'</a> </div>'. $ads_12th['ad_script'] .'</div>
                            <div class="content-single-page">';
        }
        for($i=1; $i<count($content_block); $i++)
        {   
            $content_block[$i] = '<p>'.$content_block[$i];
        }
        $content = implode('',$content_block);
    } elseif( is_page('business-directory-sign-up') ){

        //add_filter( 'gform_pre_render', 'qcity_insert_packages' );

        $content_block  = explode('<p>',$content);

        //r_dump($content_block);

        if( !empty($content_block[2])){

            $packages = get_field('packages');

            if( $packages ): 
                    $content_block[2] .= '<section class="tiers membership-thirds pricing-grid signup">';
                foreach( $packages as $package): 
                        
                        $title  = $package['package_title'];
                        $desc   = $package['package_details'];

                        if( $title ):
                            $content_block[2] .= '<div class="third plan">
                            <h3>'. $title .'</h3> '. $desc .'
                            </div>
                            ';
                        endif;                         
                endforeach;
                $content_block[2] .= '</section>';     
                ?>
            <?php endif; ?>    

        <?php }

            for($i=1; $i<count($content_block); $i++)
            {   
                $content_block[$i] = '<p>'.$content_block[$i];
            }
            $content = implode('',$content_block);   

    }
    return $content;    
}

function qcity_insert_packages()
{
    $content_block = '';
    if( is_page('business-directory-sign-up') ){

        $packages = get_field('packages');

        $content_block = '<section class="tiers membership-thirds pricing-grid signup">';

        foreach( $packages as $package): 
                        
                        $title  = $package['package_title'];
                        $desc   = $package['package_details'];

                        if( $title ):
                            $content_block .= '<div class="third plan">
                            <h3>'. $title .'</h3> '. $desc .'
                            </div>
                            ';
                        endif;                         
        endforeach;
        $content_block .= '</section>';
    } // page 
    return $content_block;
}

add_filter( 'rp4wp_append_content', '__return_false' );


function returnlimit( $limit ) {
    return "LIMIT 3";
}


function get_posts_ids($right_posts) {
    $ids = array();
    if($right_posts) {
        foreach($right_posts as $post){
            if( isset($post->ID) ) {
                $ids[] = $post->ID;
            }
        }
    }
    return $ids;
}


/* This custom field will show up under 'Status & Visibility' meta box */
function custom_meta_post_visibility_box($object) {
    wp_nonce_field(basename(__FILE__), "custom_meta_post_visibility-nonce");
    wp_nonce_field(basename(__FILE__), "sponsored_content_post-nonce");
    $display_post = (get_post_meta($object->ID, "custom_meta_post_visibility", true)) ? 1:'';
    $selected = ($display_post==1) ? 1 : 0;
    $screen = get_current_screen();
    $action = ( isset($screen->action) && $screen->action=='add' ) ? 'add':'edit';
    $is_selected = ($display_post==1) ? ' checked':'';
    $sponsoredVal = ( get_post_meta($object->ID, "sponsored_content_post", true) ) ? 1:'';
    $is_enabled = ($sponsoredVal) ? ' checked':'';
    ?>
    <div id="compStickToRight" class="components-panel__row">
        <div class="components-base-control">
            <div class="components-base-control__field">
                <label for="meta_display_post1" class="components-checkbox-control__input-container">
                    <span class="inputlabel">Stick on right side</span>
                    <input type="checkbox" id="meta_display_post1" name="custom_meta_post_visibility" class="components-checkbox-control__input cmeta_display_post meta_display_post1" value="1"<?php echo $is_selected?>>
                    <i class="chxboxstat"></i>
                </label>
            </div>
        </div>
    </div>
    <div id="customMetaSponsoredPost" class="components-panel__row">
        <div class="components-base-control customMetaSponsoredPostInput">
            <div class="components-base-control__field">
                <label for="sponsored_content_post" class="components-checkbox-control__input-container">
                    <span class="inputlabel">Enable Sponsored Content</span>
                    <input type="checkbox" id="meta_sponsored_content_post" name="sponsored_content_post" class="components-checkbox-control__input sponsored_content_post" value="1"<?php echo $is_enabled?>>
                    <i class="chxboxstat"></i>
                </label>
            </div>
        </div>
    </div>
    <?php  
}

function add_custom_meta_box() {
    add_meta_box("display-post-meta-box", "Post Visibility", "custom_meta_post_visibility_box", "post", "side", "high", null);
}
add_action("add_meta_boxes", "add_custom_meta_box");

function save_custom_meta_post_visibility_box($post_id, $post, $update) {
    if (!isset($_POST["custom_meta_post_visibility-nonce"]) || !wp_verify_nonce($_POST["custom_meta_post_visibility-nonce"], basename(__FILE__)))
        return $post_id;

    if (!isset($_POST["sponsored_content_post-nonce"]) || !wp_verify_nonce($_POST["sponsored_content_post-nonce"], basename(__FILE__)))
        return $post_id;

    if(!current_user_can("edit_post", $post_id))
        return $post_id;

    if(defined("DOING_AUTOSAVE") && DOING_AUTOSAVE)
        return $post_id;

    $slug = "post";
    if($slug != $post->post_type)
        return $post_id;

    $post_visibility = ( isset($_POST["custom_meta_post_visibility"]) && $_POST["custom_meta_post_visibility"] ) ? $_POST["custom_meta_post_visibility"]:''; 
    update_post_meta($post_id, "custom_meta_post_visibility", $post_visibility);

    $enabled_sponsored = ( isset($_POST["sponsored_content_post"]) && $_POST["sponsored_content_post"] ) ? $_POST["sponsored_content_post"]:''; 
    update_post_meta($post_id, "sponsored_content_post", $enabled_sponsored);

    if(isset($_POST['sticky'])) {
      $stickToTop = $_POST['sticky'];
      if($stickToTop) {
        $sp[] = $post_id;
        $optionVal = serialize($sp);
        update_option('sticky_posts',$optionVal);
      } else {
        $existingStickies = get_stick_to_top_posts();
        if($existingStickies) {
          $newStickies = array();
          foreach($existingStickies as $k=>$xid) {
            if($xid==$post_id) {
              unset($existingStickies[$k]);
            }
          }
          $optionVal = serialize($existingStickies);
          update_option('sticky_posts',$optionVal);
        }
      }
    } 

}
add_action("save_post", "save_custom_meta_post_visibility_box", 10, 3);

function jupload_scripts() { 
$screen = get_current_screen();
$is_post = ( isset($screen->base) && $screen->base=='post' ) ? true:false; 
$postId = ( isset($_GET['post']) && $_GET['post'] ) ? $_GET['post'] : 0;
$sponsoredVal = ( get_post_meta($postId, "sponsored_content_post", true) ) ? 1:'';
$terms = ($postId) ? get_the_terms($postId,'category') : '';
$post_categories = array();
$post_is_sponsored = '';
if($terms) {
    foreach($terms as $t) {
        $slug = $t->slug;
        $post_categories[] = $slug;
        if($slug=='sponsored-post') {
            $post_is_sponsored = $slug;
        }
    }
}

if($is_post) { ?>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script>
    jQuery.noConflict();
    jQuery(document).ready(function($){
        var is_sponsored_post = '<?php echo $post_is_sponsored;?>';
        var post_categories = <?php echo json_encode($post_categories);?>;
        var selectedVal = ( typeof $("#display-post-meta-box input.cmeta_display_post:checked").val() !== 'undefined' ) ? $("#display-post-meta-box input.cmeta_display_post:checked").val() : '';
        var postmetaForm = $("#display-post-meta-box .components-base-control").clone();
        var newInputField = '<div id="stickToRightInputDiv" class="components-base-control__field" style="margin-bottom:5px">';
            newInputField += '<span class="components-checkbox-control__input-container"><input id="inspector-checkbox-control-888" class="components-checkbox-control__input stickToRightInput" type="checkbox" value=""><i class="chxboxstat"></i></span><label class="components-checkbox-control__label" for="inspector-checkbox-control-888">Stick on right side</label>';
            
            if(is_sponsored_post) {
                newInputField += '<label id="sponsoredContentInfo" for="inspector-checkbox-control-sponsored"><input id="inspector-checkbox-control-sponsored" class="components-checkbox-control__input sponsoredContentCbox" type="checkbox"><span id="sponsoredInputBox"></span><span id="labelSponsoredContent">Enable Sponsored Content</span></label>';
                
            } else {
                newInputField += '<label id="sponsoredContentInfo" for="inspector-checkbox-control-sponsored" style="display:none"><input id="inspector-checkbox-control-sponsored" class="components-checkbox-control__input sponsoredContentCbox" type="checkbox"><span id="sponsoredInputBox"></span><span id="labelSponsoredContent">Enable Sponsored Content</span></label>';
                $("input#meta_sponsored_content_post").prop('checked',false);
                $("input#meta_sponsored_content_post").removeAttr('checked',true);
                //$("input#meta_sponsored_content_post").val("");
            }

            newInputField += '</div>';
      
        add_stick_to_right();

        // $(document).on("click",'button.components-button',function(){
        //   var label = $(this).attr('data-label');
        //   if(label=='Document') {
        //     add_stick_to_right();
        //   } 
        // });



        $(document).on("click",function(){
          add_stick_to_right();
        });

        $(document).on("click",'button.components-button.components-panel__body-toggle',function(){
          var label = $(this).text().replace(/\s+/g,"").trim().toLowerCase();
          if(label=='status&visibility') {
            add_stick_to_right();
          }
        });


      function add_stick_to_right() {

         $(".components-base-control__field").each(function(){
            var div = $(this);
            var str = $(this).text().replace(/\s+/g,"").trim().toLowerCase();
            if(str=='sticktothetopoftheblog') {
                div.addClass("sticktothetopoftheblogField");
                div.css("margin-bottom","5px");
                var parent = $(this).parents(".components-base-control");
                parent.addClass("stickyOptionsDiv");
                if( $("#stickToRightInputDiv").length==0 ) {
                   parent.prepend(newInputField);
                }
            } else if(str=='pendingreview') {
                div.appendTo(".stickyOptionsDiv");
                $(".edit-post-sidebar .editor-post-format").addClass("moved");
                $(".edit-post-sidebar").addClass("hasPendingOpt");
            }
         });

         if(selectedVal) {
            $("input.stickToRightInput").attr("checked",true);
         }

         if( $("#meta_sponsored_content_post").length>0 ) {
            if( $("#meta_sponsored_content_post").is(":checked") ) {
               var enable_sponsored = $("#meta_sponsored_content_post:checked").val();
               $("#sponsoredContentInfo").addClass('checked');
               $("#inspector-checkbox-control-sponsored").prop('checked',true);
               $("#inspector-checkbox-control-sponsored").attr('checked',true);
            }
         } 

      }


        $(document).on("click","input.stickToRightInput",function(){
          var target = $(this);
          if(this.checked) {
            target.prop("checked",true);
            target.attr("checked",true);
            $("input.cmeta_display_post").prop("checked",true);
            $("input.cmeta_display_post").attr("checked",true);
            $("input.cmeta_display_post").val("1");
          } else {
            target.prop("checked",false);
            target.removeAttr("checked");
            $("input.cmeta_display_post").val("");
            $("input.cmeta_display_post").prop("checked",false);
            $("input.cmeta_display_post").removeAttr("checked");
          }
        });

        if( $("input.cmeta_display_post").length>0 ) {
            if( $("input.cmeta_display_post").is(":checked") ) {
                $("input.stickToRightInput").prop('checked',true);
                $("input.stickToRightInput").attr('checked',true);
            } else {
                $("input.stickToRightInput").prop('checked',false);
                $("input.stickToRightInput").removeAttr('checked',true);
            }
        }

        /* Enable Sponsored Post */
        var enabledSponsoredInput = ( typeof $("#meta_sponsored_content_post:checked").val() !== 'undefined' ) ? $("#meta_sponsored_content_post:checked").val() : '';
        $(document).on("click","input#inspector-checkbox-control-sponsored",function(){
            if( this.checked ) {
                $(this).prop('checked',true);
                $(this).attr('checked',true);
                $(this).parent().addClass("checked");
                $("input#meta_sponsored_content_post").prop('checked',true);
                $("input#meta_sponsored_content_post").attr('checked',true);
            } else {
                $(this).prop('checked',false);
                $(this).removeAttr('checked');
                $(this).parent().removeClass("checked");

                $("input#meta_sponsored_content_post").prop('checked',false);
                $("input#meta_sponsored_content_post").removeAttr('checked',true);
            }
        });

        

        $(document).on("click",".editor-post-taxonomies__hierarchical-terms-choice input.components-checkbox-control__input",function(){
            var labelName = $(this).parents(".components-base-control__field").find("label.components-checkbox-control__label").text().replace(/\s+/g,'-').trim().toLowerCase();
            if( this.checked ) {
                if(labelName=='sponsored-post') {
                    $("#sponsoredContentInfo").show();
                } 
            } else {
                if(labelName=='sponsored-post') {
                    $("#sponsoredContentInfo").hide();
                } 
            }
        });
        
    });
    </script>
<?php
    }
}
add_action( 'admin_print_scripts', 'jupload_scripts' );
  
add_action( 'admin_head', 'post_visibility_head_scripts' );
function post_visibility_head_scripts(){ ?>
    <style>
    .stickyOptionsDiv {
        position: relative;
        top: -57px;
    }
    .edit-post-sidebar .editor-post-format {
        position: relative;
        top: 80px;
    }
    .edit-post-sidebar .editor-post-format.moved {
      top: 110px;
    }
    .display-post-meta-box-control {
        margin-top: 15px;
    }
    .display-post-meta-box-control label {
        display: block;
        width: 100%;
    }
    .display-post-meta-box-control .components-base-control__field label.components-checkbox-control__input-container {
        display: block;
        width: 100%;
        position: relative;
        margin: 0 0;
        padding: 0 0 0 22px;
    }
    .display-post-meta-box-control .components-base-control__field input {
        margin-right: 2px;
        position: absolute;
        top: 1px;
        left: 0;
        z-index: 5;
        background: transparent!important;
    }
    .display-post-meta-box-control .components-base-control__field .chxboxstat {
        display: block;
        width: 16px;
        height: 16px;
        position: absolute;
        top: 1px;
        left: 0;
        z-index: 3;
        border: 2px solid transparent;
        border-radius:2px;
        transition: none;
        font-style: normal;
    }
    .display-post-meta-box-control .components-base-control__field input:checked + .chxboxstat {
        background: #11a0d2;
        border-color: #11a0d2;
    }.display-post-meta-box-control .components-base-control__field input:checked + .chxboxstat:before {
        content: "\2714";
        display: inline-block;
        position: absolute;
        top: 0px;
        left: 1px;
        color: #FFF;
        font-size: 12px;
        line-height: 1;
    }
    #stickToRightInputDiv .components-checkbox-control__input-container {
       /* position: absolute;
        z-index: 10;*/
        position: relative;
    }
    #stickToRightInputDiv input {
        position: absolute;
        z-index: 10;
        opacity: 0;
    }
    #stickToRightInputDiv .chxboxstat{
        display: block;
        width: 16px;
        height: 16px;
        position: absolute;
        top: 1px;
        left: 0;
        z-index: 3;
        border: 2px solid transparent;
        border-radius:2px;
        transition: none;
        font-style: normal;
        background: #FFF;
        border: 2px solid #6c7781;
    }
    #stickToRightInputDiv input:checked + .chxboxstat {
        background: #11a0d2;
        border-color: #11a0d2;
        border: 2px solid #6c7781;
    }
    #stickToRightInputDiv input:checked + .chxboxstat:before {
        content: "\2714";
        display: inline-block;
        position: absolute;
        top: 0px;
        left: 2px;
        color: #FFF;
        font-size: 11px;
        line-height: 1;
    }
    #display-post-meta-box label.components-checkbox-control__input-container {
        width: 100%!important;
        position: relative;
        padding-left: 22px;
    }
    #display-post-meta-box .components-base-control__field input {
        visibility: visible;
        position: absolute;
        top: 1px;
        left: 0;
    }
    
    /* This is the actual meta box. This will do the trick. */
    .metabox-location-side #display-post-meta-box{display:none!important;}
    #sponsoredContentInfo {
        display: block;
        max-width: 230px;   
        width: 230px;   
        position: absolute;
        top: 51px;
        left: 0;
    }
    #labelSponsoredContent {
        display: block;
        width: 100%;   
        padding-left: 28px;
    }
    #sponsoredInputBox {
        display: block;
        width: 16px;
        height: 16px;
        position: absolute;
        top: 1px;
        left: 0;
        z-index: 3;
        border: 2px solid transparent;
        border-radius: 2px;
        transition: none;
        font-style: normal;
        background: #FFF;
        border: 2px solid #6c7781;
    }
    #sponsoredContentInfo input {
        z-index: 50;
    }
    #sponsoredContentInfo.checked #sponsoredInputBox {
        background: #11a0d2;
    }
    #sponsoredContentInfo.checked #sponsoredInputBox:before {
        content: "\2714";
        display: inline-block;
        position: absolute;
        top: 0px;
        left: 2px;
        color: #FFF;
        font-size: 11px;
        line-height: 1;
    }
    .edit-post-sidebar.hasPendingOpt #sponsoredContentInfo {
        top: 75px;
    }
    </style>
<?php
}
/* end of meta box custom field */


function get_the_user_ip() {
if ( ! empty( $_SERVER['HTTP_CLIENT_IP'] ) ) {
//check ip from share internet
$ip = $_SERVER['HTTP_CLIENT_IP'];
} elseif ( ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
//to check ip is pass from proxy
$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
} else {
$ip = $_SERVER['REMOTE_ADDR'];
}
return apply_filters( 'wpb_get_ip', $ip );
}
 
add_shortcode('show_ip', 'get_the_user_ip');


function get_the_post_author($authorID) {
    $authorName = '';
    if($authorID) {
        $displayName = get_the_author_meta('display_name',$authorID);
        $fname = get_the_author_meta('first_name',$authorID);
        $lname = get_the_author_meta('last_name',$authorID);
        $authorNameArrs = array($fname,$lname);
        if(  $authorNameArrs && array_filter($authorNameArrs) ) {
            $authorName = implode(" ", array_filter($authorNameArrs));
        } else {
            $authorName = ($displayName) ? ucwords($displayName) : '';
        }
    }
    return $authorName;
}

function gettheids($postObj) {
    if( empty($postObj) ) return '';
    $ids = array();
    foreach($postObj as $obj) {
        $ids[] = $obj->ID;
    }
    return $ids;
}

function shortenText($string, $limit, $break=".", $pad="...") {
  // return with no change if string is shorter than $limit
  if(strlen($string) <= $limit) return $string;

  // is $break present between $limit and the end of the string?
  if(false !== ($breakpoint = strpos($string, $break, $limit))) {
    if($breakpoint < strlen($string) - 1) {
      $string = substr($string, 0, $breakpoint) . $pad;
    }
  }

  return $string;
}



function get_stick_to_right_posts($limit=3) {
  global $wpdb;
  $post_ids = array();
  $limit_sql = ($limit) ? ' LIMIT '.$limit:'';
  $query = "SELECT p.ID FROM ".$wpdb->prefix."posts p, ".$wpdb->prefix."postmeta m
            WHERE p.ID=m.post_id AND m.meta_key='custom_meta_post_visibility' AND m.meta_value=1 AND p.post_status='publish' AND p.post_type='post'
            ORDER BY p.post_date DESC".$limit_sql;

  $result = $wpdb->get_results($query);
  if($result) {
    foreach($result as $row) {
      $post_ids[] = $row->ID;
    }
  }
  return ($post_ids) ? $post_ids : '';
}



function get_stick_to_top_posts() {
  global $wpdb;
  $optionVal = array();
  $query = "SELECT * FROM ".$wpdb->prefix."options WHERE option_name='sticky_posts'";
  $result = $wpdb->get_row($query);
  if($result) {
    $optionVal = ($result->option_value) ? @unserialize($result->option_value) : '';
  }
  return $optionVal;
}


function add_query_vars_filter( $vars ) {
  $vars[] = "pg";
  return $vars;
}
add_filter( 'query_vars', 'add_query_vars_filter' );


function get_total_events_by_date() {
  $postID = array();

  /* SPONSORED EVENTS */
  $day = date('d');
  $day2 = $day - 1;
  $day_plus = sprintf('%02s', $day2);
  $today = date('Ym') . $day_plus;
  $args1 = array(
    'post_type'         =>'event',
    'post_status'       =>'publish',
    'posts_per_page'    => -1,
    'order'             => 'ASC',
    'meta_key'      => 'event_date',
    'orderby'     => 'meta_value_num',
    'meta_query'        => array(
        array(
            'key'       => 'event_date',
            'compare'   => '>=',
            'value'     => $today,
        ),      
    ),
    'tax_query' => array(
        array(
            'taxonomy'  => 'event_category', 
            'field'     => 'slug',
            'terms'     => array( 'premium' ) 
        )
    )
  );

  $sponsored = get_posts($args1);
  $total1 = ($sponsored) ? count($sponsored):0;
  if($sponsored) {
    foreach($sponsored as $s) {
      $postID[] = $s->ID;
    }
  }

  /* MORE EVENTS */
  $args2 = array(
      'post_type'         =>'event',
      'post_status'       =>'publish',
      'posts_per_page'    => -1,
      'order'             => 'ASC',
      'meta_key'          => 'event_date',
      'orderby'           => 'meta_value_num',
      'meta_query'        => array(
          array(
              'key'       => 'event_date',
              'compare'   => '>=',
              'value'     => $today,
          ),      
      )
  );

  if($postID) {
    $args2['post__not_in'] = $postID;
  }

  $more = get_posts($args2);
  $total2 = ($more) ? count($more):0;
  $final_total = $total1 + $total2;

  return $final_total;
}

function getAllCategoriesByTermSlug($catSlugs,$taxonomy='category') {
    global $wpdb;
    $catList = array();
    if($catSlugs) {
        foreach($catSlugs as $slug) {
            $query = "SELECT term.term_id FROM ".$wpdb->prefix."terms term, ".$wpdb->prefix."term_taxonomy tax 
                      WHERE term.term_id=tax.term_id AND tax.taxonomy='".$taxonomy."' AND term.slug='".$slug."'";
            $result = $wpdb->get_results($query);
            if($result) {
                foreach($result as $row) {
                    $catList[] = $row->term_id;
                }
            }
        }
    }
    return $catList;
}

function getAllPostsByTermSlug($catSlugs) {
    global $wpdb;
    $postsIds = array();
    if($catSlugs) {
        foreach($catSlugs as $slug) {
            $query = "SELECT term.term_id,term.slug FROM ".$wpdb->prefix."terms term, ".$wpdb->prefix."term_taxonomy tax 
                      WHERE term.term_id=tax.term_id AND tax.taxonomy='category' AND term.slug='".$slug."'";
            $result = $wpdb->get_results($query);

            /* Get all the posts assigned to these categories */
            if($result) {
                foreach($result as $row) {
                    $term_id = $row->term_id;
                    $post_queries = "SELECT p.ID,p.post_title,term.term_id,term.slug FROM ".$wpdb->prefix."posts p, ".$wpdb->prefix."term_relationships rel, ".$wpdb->prefix."terms term 
                    WHERE p.ID=rel.object_id AND rel.term_taxonomy_id=term.term_id AND rel.term_taxonomy_id=".$term_id." AND p.post_type='post' AND p.post_status='publish'";
                    $post_result = $wpdb->get_results($post_queries);
                    if($post_result) {
                        foreach($post_result as $p) {
                            $postsIds[] = $p->ID;
                        }
                    }
                }
            }
            
        }
    }

    return ($postsIds) ? array_unique($postsIds) : array();
}

function get_news_posts_with_videos($limitNum=null) {
    global $wpdb;
    $whichCatId = get_field("elect_which_category","option");
    $posts_with_videos = array();
    if($limitNum) {
        $query = "SELECT p.ID, p.post_date FROM ".$wpdb->prefix."posts p, ".$wpdb->prefix."postmeta m WHERE p.ID=m.post_id AND p.post_status='publish' AND p.post_type='post' AND m.meta_key='video_single_post' AND m.meta_value!='' ORDER BY p.post_date DESC LIMIT ".$limitNum;
    } else {
        $query = "SELECT p.ID, p.post_date FROM ".$wpdb->prefix."posts p, ".$wpdb->prefix."postmeta m WHERE p.ID=m.post_id AND p.post_status='publish' AND p.post_type='post' AND m.meta_key='video_single_post' AND m.meta_value!='' ORDER BY p.post_date DESC LIMIT 100";
    }
    $items = array();
    $result = $wpdb->get_results($query);
    if($result) {
        foreach($result as $row) {
            $id = $row->ID;
            $terms = get_the_terms($id,'category');
            $include = array();
            if($whichCatId) {
                if($terms) {
                    foreach($terms as $t) {
                        $term_id = $t->term_id;
                        if($term_id==$whichCatId) {
                            $items[$id] = $row;
                            $posts_with_videos[$id] = $id;
                        }
                    }
                }
            }
        }
    }

    return ($posts_with_videos) ? array_values($posts_with_videos) : '';
}


function get_count_stories() {
    global $wpdb;
    $query = "SELECT count(*) as total FROM ".$wpdb->prefix."posts p WHERE p.post_type='story' AND post_status='publish'";
    $result = $wpdb->get_row($query);
    // $args = array(
    //   'post_type'       =>'story',
    //   'post_status'     =>'publish',
    //   'posts_per_page'  => -1
    // );
    // $post = get_posts($args);
    return ($result) ? $result->total : 0;
}
add_filter( 'gform_init_scripts_footer', '__return_true' );

function getTermId($slug) {
    global $wpdb;
    $query = "SELECT term_id FROM ".$wpdb->prefix."terms WHERE slug='".$slug."'";
    $res = $wpdb->get_row($query);
    $exclude_term_id = ($res) ? $res->term_id : '';
    return $exclude_term_id;
}

function getMoreNewsPosts($limit=3) {
    global $wpdb;
    $query = "SELECT p.* FROM ".$wpdb->prefix."posts p, ".$wpdb->prefix."postmeta m
              WHERE p.ID=m.post_id AND m.meta_key='home_more_news' AND TRIM(IFNULL(m.meta_value,'')) <> '' AND p.post_status='publish' AND p.post_type='post'
                ORDER BY p.ID DESC LIMIT " . $limit;
    $entries = $wpdb->get_results($query);
    return ($entries) ? $entries : '';
}

function getCommentaryPosts($limit=3) {
    $termName = 'commentaries';
    $args = array(
      'post_type'               =>'post',
      'post_status'         => 'publish',       
      'posts_per_page'  => $limit,
      'orderby'   => 'ID',
        'order'         => 'DESC',
      'tax_query'               => array(
                array(
                  'taxonomy' => 'category',
                  'field'    => 'slug',
                  'terms'    => $termName,
                  'operator' => 'IN'
                )
        )
    );
    $commentaries = new WP_Query($args);
    return ($commentaries) ? $commentaries : '';
}

function getGravityFormList() {
    global $wpdb;
    $query = "SELECT id, title FROM ".$wpdb->prefix."gf_form WHERE is_active=1 AND is_trash=0 ORDER BY title ASC";
    $result = $wpdb->get_results($query);
    return ($result) ? $result : '';
}


add_action( 'admin_head', 'acf_custom_admin_styles' );
function acf_custom_admin_styles(){ ?>
<style type="text/css">
    .acf-field[data-name="tier_1_desc"],
    .acf-field[data-name="tier_1_btn"],
    .acf-field[data-name="tier_1_btn_link"],

    .acf-field[data-name="tier_2_desc"],
    .acf-field[data-name="tier_2_btn"],
    .acf-field[data-name="tier_2_btn_link"],

    .acf-field[data-name="tier_3_desc"],
    .acf-field[data-name="tier_3_btn"],
    .acf-field[data-name="tier_3_btn_link"] {
        display: none!important;
    }
    .acf-field-flexible-content [data-layout="tier_data"] {
        margin-top: 10px;
    }
    .acf-field-flexible-content [data-layout="tier_data"] .acf-fc-layout-handle {
        position: relative;
        color: transparent;
    }
    .acf-field-flexible-content [data-layout="tier_data"] .acf-fc-layout-handle:before {
        content:attr(data-title);
        display: inline-block;
        font-size: 15px;
        font-weight: bold;
        line-height: 1.2;
        position: absolute;
        top: 8px;
        left: 42px;
        color: #000;
        z-index: 5;
    }
    body.wp-admin .interface-interface-skeleton__editor {
        padding-bottom: 50px;
    }
</style>
<script type="text/javascript">
jQuery(document).ready(function($){
    /* ACF Flexible Content For Tiers Content (DONATE page) */
    if( $('[data-layout="tier_data"]').length > 0 ) {
        $('[data-layout="tier_data"]').each(function(){
            var str = $(this).find('[data-name="title"] .acf-input-wrap input').val();
            var title = ( str.replace(/\s+/g,'').trim() ) ? str.replace(/\s+/g,' ').trim() : '(Blank)';
            $(this).find(".acf-fc-layout-handle").attr("data-title",title);
        });
    }
    $(document).on('keyup','[data-layout="tier_data"] [data-name="title"] .acf-input-wrap input',function(){
        var val = $(this).val();
        var parent = $(this).parents('[data-layout="tier_data"]');
        if(val) {
            parent.find(".acf-fc-layout-handle").attr("data-title",val);
        } else {
            parent.find(".acf-fc-layout-handle").attr("data-title",'Untitled');
        }
    });
});
</script>
<?php } 

$gravityFormsSelections = array('homeFormShortcode','homeSBFormShortcode');
function acf_load_gravity_form_choices( $field ) {
    // reset choices
    $field['choices'] = array();
    $choices = getGravityFormList();
    if( $choices && is_array($choices) ) {       
        foreach( $choices as $choice ) {
            $post_id = $choice->id;
            $post_title = $choice->title;
            
            $field['choices'][ $post_id ] = $post_title;
            
        }   
    }
    return $field;
}
foreach($gravityFormsSelections as $fieldname) {
    add_filter('acf/load_field/name='.$fieldname, 'acf_load_gravity_form_choices');
}


function get_sponsored_posts($terms,$numdays=61,$perpage=3) {
    global $wpdb;
    $prefix = $wpdb->prefix;
    $slugs = explode("+",$terms);
    $in_terms = str_replace("+",",",$terms);
    $entries = array();
    $items = array();
    $final_output = array();
    foreach($slugs as $slug) {
        $query = "SELECT p.ID,p.post_title,p.post_date, terms.term_id, terms.name AS catname, terms.slug AS catslug FROM ".$prefix."posts p, ".$prefix."term_relationships rel, ".$prefix."terms terms,".$prefix."term_taxonomy tax
                  WHERE rel.term_taxonomy_id=terms.term_id AND rel.term_taxonomy_id=tax.term_taxonomy_id AND tax.taxonomy='category' AND terms.slug='".$slug."'
                  AND p.ID=rel.object_id AND p.post_type='post' AND p.post_status='publish' AND  DATE(p.post_date) >= DATE(NOW()) - INTERVAL ".$numdays." DAY ORDER BY p.post_date DESC";
        $result = $wpdb->get_results($query);
        if($result) {
            foreach($result as $row) {
                $entries[$row->catslug][] = $row->ID;
            }
        }
    }

    $count_slug = ($slugs) ? count($slugs) : 0;
    if($entries) {
      if($count_slug>1) {
        $slug1 = ( isset($slugs[0]) && $slugs[0] ) ? $slugs[0] : '';
        $slug2 = ( isset($slugs[1]) && $slugs[1] ) ? $slugs[1] : '';
        $group1 = ( isset($entries[$slug1]) && $entries[$slug1] ) ? $entries[$slug1] : array();
        $group2 = ( isset($entries[$slug2]) && $entries[$slug2] ) ? $entries[$slug2] : array();
        if($group1 && $group2) {
            foreach($group2 as $pid) {
                if(in_array($pid,$group1) ) {
                    if( $data = get_post($pid) ) {
                      $items[] = $data;
                    }
                }
            }
        }
      } else {
        $k = $slugs[0];
        foreach($entries[$k] as $pid) {
          if( $data = get_post($pid) ) {
            $items[] = $data;
          }
        }
      }
    }
    
    if($items) {
        $total = count($items);
        $range = range(0,($total-1));
        shuffle($range);
        $ctr=1; foreach($range as $i) {
            if($ctr<=$perpage) {
                $data = $items[$i];
                $final_output[] = $data;
            }
            $ctr++;
        }
    }

    return $final_output;
}

function get_trending_articles($perpage=5) {
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
        if($trending_count>$listMax) {
            for($n=0; $n<$listMax; $n++) {
                $entries[] =  $trendingPostIDs[$n];
            }
        } else {
            $entries = $trendingPostIDs;
        }
    }
    return $entries;
}

