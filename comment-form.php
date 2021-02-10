<?php 
$the_post_id = get_the_ID(); 
$user_ip = get_the_user_ip();
$ip = ($user_ip) ? base64_encode($user_ip) : '';
?>
<div class="comment-respond">
  <form action="<?php echo get_site_url(); ?>/wp-comments-post.php" method="post" id="commentform" class="comment-form" novalidate="">
    <p class="comment-before">Editors will review your comment, which may be shared in our Morning Brew newsletter.</p>

    <p class="comment-form-author">
      <label for="author">Name <span class="required">*</span></label>
      <input id="author" name="author" type="text" value="" size="30" maxlength="245" aria-required="true" required="required">
    </p>

    <p class="comment-form-email">
      <label for="email">Email <span class="required">*</span></label>
      <input id="email" name="email" type="email" value="" size="30" maxlength="100" aria-required="true" required="required">
    </p>

    <p class="comment-form-city">
      <label for="city">City</label>
      <input id="city" name="city" type="text" size="30" value="">
    </p>

    <p class="comment-form-phone">
      <label for="city">Daytime Phone</label>
      <input id="phone" name="phone" type="text" size="30">
    </p>

    <p class="comment-form-comment"><label for="comment">Comment *</label> <textarea autocomplete="new-password" id="comment" name="gc1d63b108" cols="45" rows="8" maxlength="65525" aria-required="true" required="required"></textarea><textarea id="a07aa7cc48458d873e4d0cea0897b181" aria-hidden="true" name="comment" autocomplete="new-password" style="padding:0;clip:rect(1px, 1px, 1px, 1px);position:absolute !important;white-space:nowrap;height:1px;width:1px;overflow:hidden;" tabindex="-1"></textarea><script data-noptimize="" type="text/javascript">document.getElementById("comment").setAttribute( "id", "a07aa7cc48458d873e4d0cea0897b181" );document.getElementById("gc1d63b108").setAttribute( "id", "comment" );</script></p>


    <p class="form-submit">
      <input name="submit" type="submit" id="submit" value="Post Comment">
      <input type="hidden" name="comment_post_ID" value="<?php echo $the_post_id ?>" id="comment_post_ID">
      <input type="hidden" name="comment_parent" id="comment_parent" value="0">
    </p>
  </form>
</div>