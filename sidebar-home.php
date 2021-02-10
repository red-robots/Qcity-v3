<section class="ads-home">

    <?php $right_rail =  get_ads_script('right-rail');   ?>
    <?php if ( isset($right_rail['ad_script']) && $right_rail['ad_script'] ) { ?>
    <!-- Right Rail Home -->
    <div class="desktop-version align-center"> <!-- Right Rail Home -->
        <?php  echo $right_rail['ad_script']; ?>
    </div>
    <?php } ?>
    
    <?php /* West Side Connect  or Hearken Subscription Form */ ?>
    <!-- <script async src="https://modules.wearehearken.com/wndr/embed/868.js"></script> -->
    <!-- <div class="desktop-version" style="margin-bottom: 20px">
        <script async src="https://modules.wearehearken.com/qcitymetro/embed/4551.js"></script>
    </div> -->

    <?php $ad_right = get_ads_script('small-ad-right');  ?>
    <?php if ( isset($ad_right['ad_script']) && $ad_right['ad_script'] ) { ?>
    <div class="desktop-version align-center">
        <?php echo $ad_right['ad_script']; ?>
    </div>
    <?php } ?>
    <!-- Small Optional Ad Right -->

    <?php 
    $numdays = 71;
    $perpage = 3;
    //$sponsors = get_sponsored_posts('offers-invites+sponsored-post',$numdays,$perpage);
    $sponsors = get_sponsored_posts('sponsored-post',$numdays,$perpage);
    $sponsor_section_title = 'Sponsored Content';
    if( $sponsors ) { ?>
    <div id="sponsoredPostDivider"></div>
    <div id="sponsoredPostDiv">
      <div class="sidebar-sponsored-posts">
          <div class="sbTitle"><?php echo $sponsor_section_title ?></div>
          <?php $ctr=1; foreach($sponsors as $row) {
            $sp_id = $row->ID;
            $posttitle = $row->post_title;
            $sponsorCompanies = get_field('sponsors',$sp_id);
            $info = get_field("spcontentInfo","option");
            if($info) {
                $i_title = $info['title'];
                $i_text = $info['text'];
                $i_display = ($info['display'] && $info['display']=='on') ?  true : false;
            } else {
                $i_title = '';
                $i_text = '';
                $i_display = '';
            }
            $sponsorsList = '';
            if($sponsorCompanies) {
                $n=1; foreach($sponsorCompanies as $s) {
                    $comma = ($n>1) ? ', ':'';
                    $sponsorsList .= $comma . $s->post_title;
                    $n++;
                }
            }
            $default = get_template_directory_uri() . '/images/right-image-placeholder.png';
            $featImage =  ( has_post_thumbnail($sp_id) ) ? wp_get_attachment_image_src( get_post_thumbnail_id($sp_id), 'large') : '';
            $bgImg = ($featImage) ? $featImage[0] : $default;
            $pagelink = get_the_permalink($sp_id); 
            $postdate = get_the_date('F j,Y',$sp_id); ?>
            <article id="sponsoredPost<?php echo $sp_id?>" class="sp-item sp<?php echo $ctr ?>">
              <div class="inside">
                  <a href="<?php echo $pagelink;?>" class="thumb">
                      <img src="<?php echo get_template_directory_uri() . '/images/right-image-placeholder.png'; ?>" alt="" aria-hidden="true">
                      <?php if ($featImage) { ?>
                      <span class="featImage" style="background-image:url('<?php echo $bgImg?>')"></span> 
                      <?php } ?>
                  </a>

                  <a href="<?php echo $pagelink;?>" class="titlediv">
                      <?php if ($sponsorsList) { ?>
                      <span class="t1"><?php echo $sponsorsList ?></span>
                      <?php } ?>
                      <span class="t2"><?php echo $posttitle; ?></span>
                      <span class="postdate" style="display:none"><?php echo $postdate; ?></span>
                  </a>
              </div>
            </article>
          <?php $ctr++; } ?>
      </div>
    </div>


    <?php } ?>

</section>
