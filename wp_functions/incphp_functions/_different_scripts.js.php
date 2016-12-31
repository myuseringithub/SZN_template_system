<?php
// DIFFERENT SCRIPTS //

//------------ SHOW ONLY AUTHOR COMMENTS ON COMMENTS SECTION
/*
if (!current_user_can('edit_others_posts')) {
  function author_posts_comments_only($query) {
    global $current_user;
    $query->query_vars['post_author'] = $current_user->ID;
  }
  add_action('pre_get_comments', 'author_posts_comments_only');
}
*/

/*
UPLOAD SETTINGS to change the default upload location.
if (get_option('upload_path')=='wp-content/uploads' || get_option('upload_path')==null) {
update_option('upload_path','../content/');
}
*/

// Add Attribute to Menu "Top Secondary Nav" - In this case adding a "data-hash" attribue for the hash navigation of idangerous swiper.
/////////nav menu walker - code taked from: http://stackoverflow.com/questions/6215797/wordpress-post-id-in-wp-nav-menu
/*
class Custom_Walker_Nav_Sec_Top extends Walker_Nav_Menu
{
    function start_el(&$output, $item, $depth, $args,  $id = 0) {
        global $wp_query;
        $indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

        $class_names = $value = '';

        $classes = empty( $item->classes ) ? array() : (array) $item->classes;

        $class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item ) );
        $class_names = ' class="' . esc_attr( $class_names ) . '"';

		// here is the change !
        $output .= $indent . '<li data-hash="slide'. $item->ID . '"' . $value . $class_names .'>';

        $attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';
        $attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : '';
        $attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : '';
        $attributes .= ! empty( $item->url )        ? ' href="'   . esc_attr( $item->url        ) .'"' : '';
        $attributes .= ' data-id="'. esc_attr( $item->object_id        ) .'"';
        $attributes .= ' data-slug="'. esc_attr(  basename(get_permalink($item->object_id )) ) .'"';



        $item_output = $args->before;
        $item_output .= '<a'. $attributes .'>';
        $item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
        $item_output .= '</a>'; // This is where I changed things.
        $item_output .= $args->after;

        $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args, $id);
    }
}
*/

/*
* Some hackery to have WordPress match postname to any of our public post types
* All of our public post types can have /post-name/ as the slug, so they better be unique across all posts
* Typically core only accounts for posts and pages where the slug is /post-name/

function custom_parse_request_tricksy( $query ) {

   // Only noop the main query
   if ( ! $query->is_main_query() )
       return;

   // Only noop our very specific rewrite rule match
   if ( 2 != count( $query->query ) || ! isset( $query->query['page'] ) ) {
       return;
   }

   // 'name' will be set if post permalinks are just post_name, otherwise the page rule will match
   if ( ! empty( $query->query['name'] ) ) {
       $query->set( 'post_type', array( 'post', 'studyfield', 'page' ) );
   }
}
add_action( 'pre_get_posts', 'custom_parse_request_tricksy' );
*/

/**---------------THIS WILL REMOVE SLUG OR POST NAME IN URL BUT WILL ALSO PREVENT CHILD PAGES FROM BEING NESTED INSIDE UNDER PARENT IN PERMALINK------------------------------------
 * Remove the slug from published post permalinks.
 http://colorlabsproject.com/tutorials/remove-slugs-custom-post-type-url/

function custom_remove_cpt_slug( $post_link, $post, $leavename ) {

    if ( 'studyfield' != $post->post_type || 'publish' != $post->post_status ) {
        return $post_link;
    }

    $post_link = str_replace( '/' . $post->post_type . '/', '/', $post_link );

    return $post_link;
}
add_filter( 'post_type_link', 'custom_remove_cpt_slug', 10, 3 );
 */
