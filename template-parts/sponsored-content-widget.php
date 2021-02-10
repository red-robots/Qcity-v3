<?php
$numdays = 61;
$perpage = 1;
$sponsors = get_sponsored_posts('offers-invites+sponsored-post',$numdays,$perpage);
$sponsor_section_title = 'Sponsored Content';
if($sponsors) { ?>
<section class="c-sponsor-block c-sponsor-block--filled sponsor-paid-wrapper">
  <div class="c-sponsor-block__text sponsor-col-paid">
    <div class="c-sponsor-block__label t-uppercase t-lsp-b has-text-gray-dark t-size-xs has-xxs-btm-marg sponsored-title">
        <strong><?php echo $sponsor_section_title ?></strong>
    </div>
    <div class="c-sponsor-block__main">
        <p class="c-sponsor-block__sponsor has-text-sponsor t-uppercase t-lsp-m t-size-xs has-xxs-btm-marg"><strong><?php echo ($sponsors) ? $sponsors[0]->post_title: '' ?></strong></p>
        <h3 class="c-sponsor-block__headline c-sponsor-block__static-text t-lh-s has-xxxs-btm-marg"><a target="_parent" href="<?php echo get_the_permalink();  ?>" class="has-text-black-off has-text-hover-black"><?php echo get_the_title(); ?></a></h3>
        <p class="c-sponsor-block__desc c-sponsor-block__static-text t-lh-m"><?php echo get_the_excerpt(); ?></p>
    </div>
  </div>
  <?php
  $row = $sponsors[0];
  $postId = $row->ID;
  $default = get_template_directory_uri() . '/images/right-image-placeholder.png';
  $featImage =  ( has_post_thumbnail($postId) ) ? wp_get_attachment_image_src( get_post_thumbnail_id($postId), 'large') : '';
  $bgImg = ($featImage) ? $featImage[0] : $default;
  ?>
  <div class="sponsor-col-paid sponsor-col-image">
    <a target="_parent" href="<?php echo get_the_permalink($postId);  ?>" class="c-sponsor-image-link" style="background-image:url('<?php echo $bgImg?>');background-color:#e2e2e2;">
        <img src="<?php echo get_template_directory_uri() . '/images/right-image-placeholder.png'; ?>" alt="" aria-hidden="true">
        <?php if ($featImage) { ?>
        <?php echo get_the_post_thumbnail($postId,'thirds', array('class' => 'l-width-full c-sponsor-block__image')); ?>
        <?php } ?>
    </a>
  </div>

  <?php get_template_part( 'template-parts/headlines-blocks'); ?>
</section>
<?php } ?>