<?php
$square = get_bloginfo("template_url") . "/images/square.png";
$gravatar = get_bloginfo("template_url") . "/images/gravatar.png";
$commentaries = getCommentaryPosts(3);
if ( $commentaries->have_posts() )  { ?>
<div class="commentaries-section more-section-col">
	<header class="section-title ">
		<h2 class="dark-gray">Commentary</h2>
	</header>
	<div class="flexwrap">
		<?php  $ctr=1; while ( $commentaries->have_posts() ) :  $commentaries->the_post(); ?>
			<?php
				$authorID = get_the_author_meta('ID');
				$custom_author = get_field('choose_author');
				$authorName = '';
				$avatarURL = '';
				if($custom_author) {
					$avatarURL = get_avatar_url($custom_author['ID']);
					$arrs = array($custom_author['user_firstname'],$custom_author['user_lastname']);
					$authorName = implode(' ',array_filter($arrs));
				} else {
					$fname = get_the_author_meta( 'first_name' , $authorID ); 
					$lname = get_the_author_meta( 'last_name' , $authorID ); 
					$arrs = array($fname,$lname);
					if($arrs && array_filter($arrs)) {
						$authorName = implode(' ',array_filter($arrs));
					} else {
						$authorName = get_the_author_meta( 'display_name' , $authorID );
					}
					//$avatarURL = get_avatar_url($authorID);
				}
				$authorPicId = get_field("custom_picture","user_".$authorID);'';
				$imgsrc = wp_get_attachment_image_src($authorPicId,'medium_large');
				$nopic = get_bloginfo('template_url') . '/images/nophoto.png';
				$avatarURL = ($imgsrc) ? $imgsrc[0] : $nopic;
				
				//$picBg = ($avatarURL) ? ' style="background-image:url('.$avatarURL.')"':'';
			?>
			<div class="entry-block ctr<?php echo $ctr ?>">
				<a href="<?php echo get_permalink(); ?>" class="inner">
					<span class="photodiv">
						<span class="pic" style="background-image:url('<?php echo $avatarURL ?>')">
							<img src="<?php echo $square ?>" alt="" aria-hidden="true" class="helper">
						</span>
					</span>
					<span class="titlediv">
						<h4 class="ptitle"><?php the_title(); ?></h4>
						<?php if ($authorName) { ?>
						<span class="by">
							<span>By: <?php echo ucwords($authorName); ?></span>
						</span>
						<?php } ?>
					</span>
				</a>
			</div>
		<?php $ctr++; endwhile; wp_reset_postdata(); ?>
	</div>
</div>
<?php } ?>


