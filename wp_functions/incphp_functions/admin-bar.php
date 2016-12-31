<?php

// Hide admin bar
//  add_filter('show_admin_bar', '__return_false');
show_admin_bar( false );


// Remove default "Add New" menu options (In order to readd them in a desired order)
function tru_remove_default_new_content_menu() {
    global $wp_admin_bar;

    $wp_admin_bar->remove_menu('new-post');
	$wp_admin_bar->remove_menu('new-page');
	$wp_admin_bar->remove_menu('new-media');
	$wp_admin_bar->remove_menu('new-user');
	$wp_admin_bar->remove_menu('new-case');
	$wp_admin_bar->remove_menu('new-question');
	$wp_admin_bar->remove_menu('new-entertainment');
	$wp_admin_bar->remove_menu('new-open-question');
	$wp_admin_bar->remove_menu('new-sc-questions');
	$wp_admin_bar->remove_menu('new-mc-questions');
	$wp_admin_bar->remove_menu('new-quize');
	$wp_admin_bar->remove_menu('new-article');
	$wp_admin_bar->remove_menu('new-book');
}
add_action( 'wp_before_admin_bar_render', 'tru_remove_default_new_content_menu' );

// Add custom "Add New" menu - Add options in a desired order
function tru_new_content_menu() {
	global $wp_admin_bar;
    $wp_admin_bar->add_menu( array(
								'parent' => 'new-content',
								'id' => 'new_article',
								'title' => '+ Article',
								'href' => admin_url( 'post-new.php?post_type=article' ),
							)
	);
    $wp_admin_bar->add_menu( array(
								'parent' => 'new-content',
								'id' => 'new_case',
								'title' => '+ Dental Case',
								'href' => admin_url( 'post-new.php?post_type=case' ),
							)
	);
	$wp_admin_bar->add_menu( array(
								'parent' => 'new-content',
								'id' => 'new_open-question',
								'title' => '+ Open Question',
								'href' => admin_url( 'post-new.php?post_type=open-question' ),
							)
	);
	$wp_admin_bar->add_menu( array(
								'parent' => 'new-content',
								'id' => 'new_sc-questions',
								'title' => '+ SC Question',
								'href' => admin_url( 'post-new.php?post_type=sc-questions' ),
							)
	);
	$wp_admin_bar->add_menu( array(
								'parent' => 'new-content',
								'id' => 'new_mc-questions',
								'title' => '+ MC Question',
								'href' => admin_url( 'post-new.php?post_type=mc-questions' ),
							)
	);




	$wp_admin_bar->add_menu( array(
								'parent' => 'new-content',
								'id' => 'new_question',
								'title' => 'Ask a Quesion',
								'href' => admin_url( 'post-new.php?post_type=question' ),
							)
	);
	$wp_admin_bar->add_menu( array(
								'parent' => 'new-content',
								'id' => 'new_entertainment',
								'title' => '+ Entertaining post',
								'href' => admin_url( 'post-new.php?post_type=entertainment' ),
							)
	);
	$wp_admin_bar->add_menu( array(
								'parent' => 'new-content',
								'id' => 'new_media',
								'title' => '+ Media Files',
								'href' => admin_url( 'media-new.php' ),
							)
	);

}
add_action( 'wp_before_admin_bar_render', 'tru_new_content_menu' );



//--------------------------------
// WPSnippy to Remove WordPress Admin Bar Menu Items
/*
function wpsnippy_admin_bar() {
    global $wp_admin_bar;
// To remove WordPress logo and related submenu items
   $wp_admin_bar->remove_menu('wp-logo');
   $wp_admin_bar->remove_menu('about');
   $wp_admin_bar->remove_menu('wporg');
   $wp_admin_bar->remove_menu('documentation');
   $wp_admin_bar->remove_menu('support-forums');
   $wp_admin_bar->remove_menu('feedback');
// To remove Site name/View Site submenu and Edit menu from front end
//   $wp_admin_bar->remove_menu('site-name');
   $wp_admin_bar->remove_menu('view-site');
   $wp_admin_bar->remove_menu('dashboard');
//   $wp_admin_bar->remove_menu('edit');
// To remove Update Icon/Menu
   $wp_admin_bar->remove_menu('updates');
// To remove Comments Icon/Menu
   $wp_admin_bar->remove_menu('comments');
// To remove 'New' Menu
//   $wp_admin_bar->remove_menu('new-content');
// To remove 'Howdy, user' Menu completely and Search field from front end
//   $wp_admin_bar->remove_menu('top-secondary');
//   $wp_admin_bar->remove_menu('search');
// To remove 'Howdy, user' subMenus
//   $wp_admin_bar->remove_menu('user-actions');
//   $wp_admin_bar->remove_menu('user-info');
//   $wp_admin_bar->remove_menu('edit-profile');
//   $wp_admin_bar->remove_menu('logout');
}
add_action( 'wp_before_admin_bar_render', 'wpsnippy_admin_bar' );
*/




/* DEFAULT POST TYPE REMOVAL from admin bar - this will not disable
function remove_default_post_type() {
	remove_menu_page('edit.php');
}
add_action('admin_menu','remove_default_post_type');
*/











?>
