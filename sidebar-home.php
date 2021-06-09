<section class="ads-home">

    <?php  
    /* Ads */
    //$ad1 = get_field("ad1_sponsored_content_home","option");
    ?>

    <?php 
    $numdays = -1; /* days past plus today */
    $perpage = 15;
    //$sponsors = get_sponsored_posts('offers-invites+sponsored-post',$numdays,$perpage);
    //$sponsors = get_sponsored_posts('offers-invites+sponsored-post',$numdays,$perpage,1);

    // $ad1 = get_field("ad1_sponsored_content_home","option");
    // $ad2 = get_field("ad2_sponsored_content_home","option");

    $sponsors = get_sponsored_posts('sponsored-post',$numdays,$perpage,1);
    $sponsor_section_title = 'Sponsored';
    $maxDisplay = 4;
    if( $sponsors ) { ?>
    <div id="sponsoredPostDivider"></div>
    <div id="sponsoredPostDiv">
      <div class="sidebar-sponsored-posts">
          <?php $ctr=1; foreach($sponsors as $sp_id) {
            //$sp_id = $row->ID;
            $row = get_post($sp_id);
            if($row) {
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
              $postdate = get_the_date('F j,Y',$sp_id); 
              $is_show = ($ctr>$maxDisplay) ? ' hide':'';
              ?>
          
              <?php if ($ctr==1) { ?>
                <?php if ($sponsor_section_title) { ?>
                  <div class="sbTitle"><?php echo $sponsor_section_title ?></div>
                <?php } ?>   
              <?php } ?>     

              <article id="sponsoredPost<?php echo $sp_id?>" class="sp-item sp<?php echo $ctr.$is_show ?>">
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
          <?php } ?>
      </div>
    </div>


    <?php } ?>

</section>
