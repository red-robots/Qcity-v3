<?php
$entries = getMoreNewsPosts(3);
if($entries) { ?>
	<div class="moreNewsSection more-section-col">
		<header class="section-title ">
			<h2 class="dark-gray">More News</h2>
		</header>
		<div class="innerwrap">
			<div class="entries">
				<div class="flexwrap">
					<?php $ctr=1; foreach ($entries as $e) { 
						$postid = $e->ID;
						$posttitle = $e->post_title;
						//$terms = get_the_terms($postid,'category');
						$terms = false;
						$postdate = get_the_date('F j, Y',$postid);
						?>
						<div class="entry ctr<?php echo $ctr; ?>">
							<span class="num"><?php echo $ctr; ?>.</span>
							<div class="textInner">
								<h4><a href="<?php echo get_permalink($postid); ?>" class="posttitle"><?php echo $posttitle; ?></a></h4>
								<div class="postdate">
									<?php echo $postdate ?>
								</div>

								<?php if ($terms) { ?>
								<div class="terms">
									<?php $i=1; foreach ($terms as $term) {
										$comma = ($i>1) ? ', ':'';  
										$termName = $term->name;
										$termLink = get_term_link($term);
										?>
										<span><?php echo $comma ?><a href="<?php echo $termLink ?>"><?php echo $termName ?></a></span>
									<?php $i++; } ?>
								</div>	
								<?php } ?>
							</div>
						</div>
					<?php $ctr++; } ?>
				</div>
			</div>
		</div>
	</div>
<?php } ?>