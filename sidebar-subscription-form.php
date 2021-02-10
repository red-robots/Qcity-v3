<?php
	$text = ( isset($text) && $text ) ? $text : '';
	$obj = get_queried_object();
	$currentTermId = ( isset($obj->term_id) && $obj->term_id ) ? $obj->term_id : '';
	$subscribeOpts = get_field('post_category_subscription','option');
	$subscriptionText = array();
	$subscription_info = '';
	if($subscribeOpts && $currentTermId) {
		foreach($subscribeOpts as $s) {
			$subtext = $s['subscription_text'];
			$termIds = $s['categories'];
			if( $termIds && in_array($currentTermId,$termIds) ) {
				$subscriptionText[$currentTermId] = $subtext;
			}
		}
		$subscription_info = ($subscriptionText) ? $subscriptionText[$currentTermId] : '';
	}
	$subscriptionBoxText = ($subscription_info) ? $subscription_info : $text;
	$gravityFormId = get_field("homeFormShortcode","option");
	$hformTitle = get_field("homeFormTextTitle","option");
	$hformText = get_field("homeFormTextContent","option");
	?>
	<div class="sidebar-SF form-subscribe-blue">
		<div class="form-inside">
			<?php if ($subscriptionBoxText) { ?>
			<div class="gftxt"><?php echo $subscriptionBoxText; ?></div>
			<?php } else { ?>
				<?php if ($hformText) { ?>
					<div class="gftxt"><?php echo $hformText ?></div>
				<?php } ?>
			<?php } ?>

			<?php  
	      $topSubscribe = get_field("topSubscribe","option");
	      $subscribeText = ( isset($topSubscribe['subscribe_text_footer']) && $topSubscribe['subscribe_text_footer'] ) ? $topSubscribe['subscribe_text_footer']:'';
	      $subscribeText = ($subscribeText) ? str_replace('>','',$subscribeText):'';
	      $subscribeButton = ( isset($topSubscribe['subscribe_button']) && $topSubscribe['subscribe_button'] ) ? $topSubscribe['subscribe_button']:'';
	      $subscribeName = ( isset($subscribeButton['title']) && $subscribeButton['title'] ) ? $subscribeButton['title']:'';
	      $subscribeURL = ( isset($subscribeButton['url']) && $subscribeButton['url'] ) ? $subscribeButton['url']:'';
	      $subscribeTarget = ( isset($subscribeButton['target']) && $subscribeButton['target'] ) ? $subscribeButton['target']:'_self';
	    ?>
	    <?php if( $subscribeCode = get_field("singleSubscriptionCode","option") ) { ?>
	    	
	    	<?php if ($gravityFormId) { 
	    		$gfshortcode = '[gravityform id="'.$gravityFormId.'" title="false" description="false" ajax="true"]';
					if( do_shortcode($gfshortcode) ) { ?>	
					<div class="sidebarSubscribe">
						<div class="ctctSubscribeForm">
							<div class="formWrap">
								<?php //echo $subscribeCode ?>
								<?php echo do_shortcode($gfshortcode); ?>
							</div>
						</div>
					</div>
	    		<?php } ?>
	    	<?php } ?>
	 
			<?php } else { ?>
			<div class="btn">
				<a class="white" href="<?php echo $subscribeURL ?>" target="<?php echo $subscribeTarget ?>"><?php echo $subscribeName ?></a>
			</div>
			<?php } ?>
		</div>
	</div>