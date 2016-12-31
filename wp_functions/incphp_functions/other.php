<?php
/** remove version from enqueue css and js (?ver=) **/
/**https://wordpress.org/support/topic/get-rid-of-ver-on-the-end-of-cssjs-files **/
function remove_cssjs_ver( $src ) {
    if( strpos( $src, '?ver=' ) )
        $src = remove_query_arg( 'ver', $src );
    return $src;
}
add_filter( 'style_loader_src', 'remove_cssjs_ver', 1000 );
add_filter( 'script_loader_src', 'remove_cssjs_ver', 1000 );

/*
Facebook Metadata https://developers.facebook.com/tools/debug/og/object?q=http%3A%2F%2Fdentrist.com%2Fmcq%2F
*/
//Adding the Open Graph in the Language Attributes
function add_opengraph_doctype( $output ) {
		return $output . ' xmlns:og="http://opengraphprotocol.org/schema/" xmlns:fb="http://www.facebook.com/2008/fbml"';
	}
add_filter('language_attributes', 'add_opengraph_doctype');

//Lets add Open Graph Meta Info

function insert_fb_in_head() {
	global $post;
	if(is_post_type_archive( 'mcq' )) {
		$default_image="http://dentrist.com/site/../content/uploads/2016/01/Dentrist-og-Image-effects-changed-1.jpg"; //replace this with a default image on your server or an image in your media library
		echo '<meta property="og:image" content="' . $default_image . '"/>';
	}
	echo "
";
}
add_action( 'wp_head', 'insert_fb_in_head', 5 );

/*--------MENU / NAVIGATION---------------------------------  Register navigations to allow usage in wordpresss visual admin ---------------------------------*/
	register_nav_menus(array('top_sec_nav' => __('Top Secondary Navigation (top_sec_nav)', 'ipin')));

// REMOVE ANNOYING FOOTER
/**
 * remove annoying footer thankyou from wordpress that stops people from entering text
 */
function hid_wordpress_thankyou() {
  echo '<style type="text/css">#wpfooter {display:none;}</style>';
}
add_action('admin_head', 'hid_wordpress_thankyou');

// PREVENT AUTHORS FROM EDITING OTHERS' COMMENTS ON THEIR OWN POSTS.
// http://scribu.net/wordpress/prevent-blog-authors-from-editing-comments.html
function restrict_comment_editing( $caps, $cap, $user_id, $args ) {
	if ( 'edit_comment' == $cap ) {
		$comment = get_comment( $args[0] );
		if ( $comment->user_id != $user_id ) // If you want to prevent authors from editing even their own comments, just remove this line
			$caps[] = 'moderate_comments';
	}
	return $caps;
}
add_filter( 'map_meta_cap', 'restrict_comment_editing', 10, 4 );

// Comments - According to what I have checked this is used for single post pages
function ipin_comment($comment, $args, $depth) {
	$GLOBALS['comment'] = $comment;
	?>
	<li <?php comment_class(); ?> id="comment-<?php comment_ID() ?>">

        <!-- avatar of comment -->
		<?php if ('1' == $show_avatars = get_option('show_avatars')) { ?>
		<a href="<?php echo get_author_posts_url(get_comment(get_comment_ID())->user_id); ?>" title=" <?php echo get_comment_author(); ?> " style="font-style:normal;">
        <div class="comment-avatar"><?php echo get_avatar(get_comment_author_email(), '32'); ?></div>
		</a>
		<?php } ?>

        <!-- Replay button -->
		<div class="pull-right"><?php comment_reply_link(array('reply_text' => __('Reply', 'ipin'), 'depth' => $depth, 'max_depth'=> $args['max_depth'])) ?></div>

		<div class="comment-content<?php if ($show_avatars == '1') { echo ' comment-content-with-avatar'; } ?>">

			<strong>
            <a href="<?php echo get_author_posts_url(get_comment(get_comment_ID())->user_id); ?>" title=" <?php echo get_comment_author(); ?> " style="font-style:normal;">
            <span <?php comment_class(); ?>><?php comment_author_link() ?></span></a>
            </strong>

            <div id="post-comment-info" class=""> / <?php comment_date('j M Y g:ia'); ?> <a href="#comment-<?php comment_ID() ?>" title="<?php esc_attr_e('Comment Permalink', 'ipin'); ?>">#</a> <?php edit_comment_link('e','',''); ?> </div>

			<?php if ($comment->comment_approved == '0') : ?>
			<br /><em><?php _e('Your comment is awaiting moderation.', 'ipin'); ?></em>
			<?php endif; ?>

			<?php comment_text() ?>
        </div>
	<?php
}

// COMMENT FORM - Email, Name ...
function ipin_commentform_format($arg) {
	$commenter = wp_get_current_commenter();
	$req = get_option( 'require_name_email' );
	$aria_req = ( $req ? " aria-required='true'" : '' );
	$arg['author'] = '<div class="commentform-input pull-left"><label>' . __('Name (Required)', 'ipin') . '</label> <input class="commentform-field" id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '"' . $aria_req . ' /></div>';
	$arg['email'] = '<div class="commentform-input pull-left"><label>' . __('Email (Required)', 'ipin') . '</label> <input class="commentform-field" id="email" name="email" type="text" value="' . esc_attr( $commenter['comment_author_email'] ) . '"' . $aria_req . ' /></div>';
	$arg['url'] = '<div class="commentform-input pull-left"><label>' . __('Website', 'ipin') . '</label> <input class="commentform-field" id="url" name="url" type="text" value="' . esc_attr( $commenter['comment_author_url'] ) . '"' . $aria_req . ' /></div>';
    return $arg;
}
add_filter('comment_form_default_fields', 'ipin_commentform_format');

?>
