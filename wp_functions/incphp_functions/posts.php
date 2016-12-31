<?php
//******** VARIABLES**********//
$post_types_userbased = array('case', 'question', 'quize', 'open-question', 'mc-questions', 'sc-questions', 'entertainment', 'article');
$post_types_all = array('case', 'question', 'quize', 'open-question', 'mc-questions', 'sc-questions', 'entertainment', 'article', 'book');


//******** Add show_in_rest for wp-types plugins to show custom post types in Wordpress REST API V2**********//
add_filter( 'wpcf_type', 'add_show_in_rest_func', 10, 2);
function add_show_in_rest_func($data, $post_type) {
    //if(in_array($post_type, $post_types_all)){
        $data['show_in_rest'] = true;
    //}
    return $data;
}
/* From git issue - If you want to enable the custom post type in WordPress REST API, you can use this code too. */
add_action( 'init', 'my_custom_post_type_rest_support', 50 );
function my_custom_post_type_rest_support() {
  global $wp_post_types;
  // set the visibility
  $wp_post_types['article']->show_in_rest = true;
  /* set how you want access the custom post type
   * /wp-json/wp/v2/article/
   * If you want change for "zxy"
   * $wp_post_types['article']->rest_base = 'zxy';
   * now your endpoint will look like this:
   * /wp-json/wp/v2/zxy
   */
  $wp_post_types['article']->rest_base = 'article';
}



/* MULTIPLE POST TYPES IN ARCHIVE - CUSTOM POST TYPE 'quize'
	PROBLEM - redirects to Default archive page although a custom one is created.*/
// http://www.peterrknight.com/how-to-query-multiple-custom-post-types-with-query_posts-wordpress-tip/
function quize_archive_types($query) {
	$pt = $query->get( 'post_type' );
  if ( 'quize' == $pt && !is_admin() && $query->is_main_query() ) { // INSTEAD OF POSTTYPE - $query->query_vars['post_type'] == 'quize'
      $query->set('post_type', array( 'quize', 'open-question', 'sc-questions', 'mc-questions' ));
  }
}
add_action('pre_get_posts','quize_archive_types');






// PUBLISH POSTS WITH NO CONTENT AND NO TITLE !
//http://wordpress.stackexchange.com/questions/28021/how-to-publish-a-post-with-empty-title-and-empty-content
function wpse28021_mask_empty($value)
{
    if ( empty($value) ) {
        return ' ';
    }
    return $value;
}
add_filter('wp_insert_post_data', 'wpse28021_unmask_empty');
function wpse28021_unmask_empty($data)
{
    if ( ' ' == $data['post_title'] ) {
        $data['post_title'] = '';
    }
    if ( ' ' == $data['post_content'] ) {
        $data['post_content'] = '';
    }
    return $data;
}
add_filter('pre_post_title', 'wpse28021_mask_empty');
add_filter('pre_post_content', 'wpse28021_mask_empty');


// Multiple thumbnails - Multiple Post Thumbnails
/*  */  if (class_exists('MultiPostThumbnails')) {
        new MultiPostThumbnails(
            array(
                'label' => 'Secondary Image',
                'id' => 'secondary-image',
                'post_type' => 'post'
            )
        );
    }


// Add custom types to AUTHOR
function add_pagination_to_author_page_query_string($query_string)
{
    if (isset($query_string['author_name'])) $query_string['post_type'] = $GLOBALS['post_types_userbased'];
    return $query_string;
}
add_filter('request', 'add_pagination_to_author_page_query_string');

// Include Custom type to Archive (Other than the default 'post' type) & prevents common disappearance of menu bar.
function namespace_add_custom_types( $query ) {
  if( is_category()&& $query->is_main_query() || is_tag() && empty( $query->query_vars['suppress_filters'] )  ) {
    $query->set( 'post_type', $GLOBALS['post_types_all']);
	  return $query;
	}
}
add_action( 'pre_get_posts', 'namespace_add_custom_types' );

//Include CUSTOM POST TYPEs to homepage / main query
function my_get_posts( $query ) {
	if ( is_home() && $query->is_main_query() )
		$query->set( 'post_type', $GLOBALS['post_types_userbased']); // array( 'post', 'case', 'question')
	return $query;
}
add_filter( 'pre_get_posts', 'my_get_posts' );




?>
