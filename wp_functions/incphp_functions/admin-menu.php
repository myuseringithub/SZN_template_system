<?php

/*
MERGE SEVERAL POSTS TYPES TO SHOW ALL POSTS ON THE ADMIN SECTION
Plugin Name: Merge post types on same admin screen
http://wordpress.stackexchange.com/questions/113808/merge-two-custom-post-types-into-one-admin-page/114797#114797

NEEDS TO BE ADDED = SHOW COLUMN OF POST TYPE ON QUIZE ALL POSTS ADMIN MANAGEMENT.
SOLUTION PROVIDED HERE - http://justintadlock.com/archives/2011/06/27/custom-columns-for-custom-post-types
*/
add_action( 'pre_get_posts', 'join_cpt_list_wspe_113808' );
function join_cpt_list_wspe_113808( $query )
{
    // If not backend, bail out
    if( !is_admin() )
        return $query;
    // Detect current page and list of CPTs to be shown in Dashboard > Posts > Edit screen
    global $pagenow;
    $cpts = array( 'quize', 'open-question', 'sc-questions', 'mc-questions' );
    if( 'edit.php' == $pagenow && ( get_query_var('post_type') && 'quize' == get_query_var('post_type') ) )
        $query->set( 'post_type', $cpts );
    return $query;
}



// LOGO TO ADMIN MENU
function add_logo_adminmenu() {
  ?>
  <?php $logo = of_get_option('logo'); ?>
  <script>
		jQuery(document).ready(function($) {
		additionalStyles = "";
		jQuery("#adminmenu").before('<a href="http://dentrist.com/"><div '+additionalStyles+' id="sidebar_adminmenu_logo" ><img width="160" src="<?php echo $logo; ?>" /></div></a>');
		});
    </script>

<?php }
add_action('admin_head', 'add_logo_adminmenu');




// Hide other CPTs for contributers.
function remove_menus(){

  if ( ! current_user_can( 'administrator' ) ) {
    // remove_menu_page( 'index.php' );                  //Dashboard
//remove_menu_page( 'edit.php' );                   //Posts
//remove_menu_page( 'upload.php' );                 //Media
//remove_menu_page( 'edit.php?post_type=page' );    //Pages
//remove_menu_page( 'edit-comments.php' );          //Comments
//remove_menu_page( 'themes.php' );                 //Appearance
//remove_menu_page( 'plugins.php' );                //Plugins
//remove_menu_page( 'users.php' );                  //Users
//remove_menu_page( 'tools.php' );                  //Tools
  // remove_menu_page( 'options-general.php' );        //Settings
  }
}
add_action( 'admin_menu', 'remove_menus' );



?>
