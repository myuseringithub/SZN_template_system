<?php


//******** VARIABLES**********//
$post_types = array('case', 'question', 'quize', 'open-question', 'mc-questions', 'sc-questions', 'entertainment', 'article');


// Remove jetpack sharing option from the post control for all users & Other MEtaboxes
/*
function post_remove_metaboxes() {
	
	$types = $post_types;

	foreach( $types as $type ) { // REMOVE GA
		remove_meta_box( 'sharing_meta' , $type , 'advanced' ); 
		remove_meta_box( 'disable_comment_images' , $type , 'normal' ); 
		remove_meta_box( 'categorydiv' , $type , 'side' );
		remove_meta_box( 'submitdiv', $type , 'side' );
	}
	foreach( $types as $type ) { // MOVE PUBLISH BUTTON - Seems to be not changing publish position
		remove_meta_box( 'submitdiv', $type , 'side' );
		add_meta_box( 'submitdiv', __( 'Publish' ), 'post_submit_meta_box', $type , 'side', 'high', null );
		
//		 remove_post_type_support($type , 'title'); //Remove Default metaboxes TITLE & CONTENT EDITOR
//		 remove_post_type_support( $type , 'editor');
	}
}
add_action( 'add_meta_boxes', 'post_remove_metaboxes', 99);
*/


//REMOVE COMMENTS METABOX BUT STILL ALLOW COMMENTS - if metabox is remvoed it appears to remove the functionality with it.
/* 
add_action(
 'add_meta_boxes', function () {
    global $wp_meta_boxes, $current_screen;
    $wp_meta_boxes[$current_screen->id]['normal']['core']['commentstatusdiv']['callback'] = function () {
        global $post;
        echo '<input type="hidden" value="' . $post->comment_status . '"      name="comment_status"/>';
        echo '<input type="hidden" value="' . $post->ping_status . '" name="ping_status"/>';
        echo '<style type="text/css">#commentstatusdiv {display: none;}</style>';
    };
  }
);
*/


/*// REMOVE POST META BOXES - in post control
function remove_my_post_metaboxes() {
 remove_meta_box( 'authordiv','post','normal' ); // Author Metabox
remove_meta_box( 'commentstatusdiv','post','normal' ); // Comments Status Metabox
remove_meta_box( 'commentsdiv','post','normal' ); // Comments Metabox
remove_meta_box( 'postcustom','post','normal' ); // Custom Fields Metabox
remove_meta_box( 'postexcerpt','post','normal' ); // Excerpt Metabox
remove_meta_box( 'revisionsdiv','post','normal' ); // Revisions Metabox
remove_meta_box( 'trackbacksdiv','post','normal' ); // Trackback Metabox
}
add_action('admin_menu','remove_my_post_metaboxes');
*/



// add css only to specific custom post type pages... 
//http://wordpress.stackexchange.com/questions/1058/loading-external-scripts-in-admin-but-only-for-a-specific-post-type
function load_admin_datapicker_script() {
    $current_screen = get_current_screen();
    if ( $current_screen->post_type === 'quize' )  {
        $ss_url = get_bloginfo('stylesheet_directory');
/*        wp_enqueue_script('jquery');
        wp_enqueue_script('custom_js_jquery_ui',"{$ss_url}/admin-metabox/js/jquery-ui-1.7.1.custom.min.js",array('jquery'));
        wp_enqueue_script('custom_js_daterangepicker',"{$ss_url}/admin-metabox/js/daterangepicker.jQuery.js",array('jquery'));
        wp_enqueue_script('custom_js_custom',"{$ss_url}/admin-metabox/js/custom.js",array('jquery'),NULL,TRUE);
*/        wp_enqueue_style('custom_css_daterangepicker',"{$ss_url}/css/admin-custom-type-quize.css");
    }
}
add_action( 'admin_enqueue_scripts', 'load_admin_datapicker_script' );



// ADD ADMIN COLUMNS WITH ADVANCED CUSTOM FIELD 
//http://www.elliotcondon.com/advanced-custom-fields-admin-custom-columns/
/*-------------------------------------------------------------------------------
	Custom Columns TO QUIZ
-------------------------------------------------------------------------------*/
function my_page_columns($columns)
{
	// Columns to add and the existing ones
	$columns = array(
		'cb'	 	=> '<input type="checkbox" />',
		'title' 	=> 'Title',
		'question'	=>	'Question',
		'post-type'	=>	'Post Type',
//		'featured' 	=> 'Featured',
		'author'	=>	'Author',
		'date'		=>	'Date',
	);
	return $columns;
}
add_filter('manage_quize_posts_columns', 'my_page_columns', 10, 2); // add to this type
function my_custom_columns($column)
{
	// output to columns
	global $post;
	$field_name = "question";
	$field = get_field_object($field_name);
	$title = $field['value'];
	
	if($column == 'question')
	{
		echo '<a href="post.php?post=' . $post->ID . '&action=edit"><b>'. $title . '</b></a>';
	} elseif ($column == 'post-type') {
		// Change to readable data
		if(get_post_type($post) == 'open-question') {echo '<center>Open Q</center>';}
		elseif(get_post_type($post) == 'sc-questions'){echo '<center>SC</center>';}
		elseif(get_post_type($post) == 'mc-questions'){echo '<center>MC</center>';}
		;
	}
	/*
	elseif($column == 'featured')
	{
		if(get_field('featured'))
		{
			echo 'Yes';
		}
		else
		{
			echo 'No';
		}
	}
	*/
}
add_action('manage_quize_posts_custom_column', 'my_custom_columns');
add_action('manage_open-question_posts_custom_column', 'my_custom_columns');
add_action('manage_sc-questions_posts_custom_column', 'my_custom_columns');
add_action('manage_mc-questions_posts_custom_column', 'my_custom_columns');
add_action('manage_open-question_posts_custom_column', 'my_custom_columns');
add_action('manage_sc-questions_posts_custom_column', 'my_custom_columns');
add_action('manage_mc-questions_posts_custom_column', 'my_custom_columns');
/*-------------------------------------------------------------------------------
	Sortable Columns
-------------------------------------------------------------------------------
function my_column_register_sortable( $columns )
{
	$columns['featured'] = 'featured';
	return $columns;
}
add_filter("manage_edit-quize_sortable_columns", "my_column_register_sortable" );
*/
/*-------------------------------------------------------------------------------
	Change ACTION LINKS POSITION TO ANOTHER COLUMN
	http://stackoverflow.com/questions/13418722/move-custom-column-admin-links-from-bellow-post-title
-------------------------------------------------------------------------------*/
add_action( 'admin_head-edit.php', 'so_13418722_move_quick_edit_links' );
// Move QUICK EDIT in post type edit 
function so_13418722_move_quick_edit_links()
{
    global $current_screen;
    if( 'quize' != $current_screen->post_type )
        return;

    if( current_user_can( 'delete_plugins' ) )
    {
        ?>
        <script type="text/javascript">
        function so_13418722_doMove()
        {
            jQuery('td.post-title.page-title.column-title div.row-actions').each(function() {
                var $list = jQuery(this);
                var $firstChecked = $list.parent().parent().find('td.question.column-question');

                if ( !$firstChecked.html() )
                    return;

                $list.appendTo($firstChecked);
            }); 
        }
        jQuery(document).ready(function ($){
            so_13418722_doMove();
        });
        </script>
        <?php
    }
}




// CHANGE any button text to custom text.
/*
Plugin Name: Retranslate
Description: Adds translations.
Version:     0.1
Author:      Thomas Scholz
Author URI:  http://toscho.de
License:     GPL v2
*/
class Toscho_Retrans {
    // store the options
    protected $params;
    /**
     * Set up basic information
     * 
     * @param  array $options
     * @return void
     */
    public function __construct( array $options )
    {
        $defaults = array (
            'domain'       => 'default'
        ,   'context'      => 'backend'
        ,   'replacements' => array ()
        ,   'post_type'    => array ( 'post' )
        );
        $this->params = array_merge( $defaults, $options );
        // When to add the filter
        $hook = 'backend' == $this->params['context'] 
            ? 'admin_head' : 'template_redirect';
        add_action( $hook, array ( $this, 'register_filter' ) );
    }
    /**
     * Conatiner for add_filter()
     * @return void
     */
    public function register_filter()
    {
        add_filter( 'gettext', array ( $this, 'translate' ), 10, 3 );
    }
    /**
     * The real working code.
     * 
     * @param  string $translated
     * @param  string $original
     * @param  string $domain
     * @return string
     */
    public function translate( $translated, $original, $domain )
    {
        // exit early
        if ( 'backend' == $this->params['context'] )
        {
            global $post_type;

            if ( ! empty ( $post_type ) 
                && ! in_array( $post_type, $this->params['post_type'] ) )
            {
                return $translated;
            }
        }
        if ( $this->params['domain'] !== $domain )
        {
            return $translated;
        }
        // Finally replace
        return strtr( $original, $this->params['replacements'] );
    }
}
// Sample code
// Replace 'Publish' with 'Save' and 'Preview' with 'Lurk' on pages and posts
$Toscho_Retrans = new Toscho_Retrans(
    array (
        'replacements' => array ( 
            'Add Media' => 'Insert Media into Text'
//        ,   'Preview' => 'Lurk' 
        )
    ,   'post_type'    => $post_types
    )
);






?>