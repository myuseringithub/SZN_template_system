<?php

 namespace SZN\WPExtend; // should be ontop.

 if ( ! defined( 'ABSPATH' ) ) { exit; } // security measure

/**
* Register three admin pages and add a stylesheet and a javascript to two of
* them only.
* https://github.com/toscho/T5-Admin-Menu-Demo/blob/master/admin-menu-demo.php
*/
class AdminArea extends \SZN\TemplateSystem {

  public function __construct() {
    if(is_admin()) { // load only on front end. As the hook for actions is not set for backend i.r. admin in this case.
      add_action( 'admin_menu', [__CLASS__, 'addAdminMenu'] ); /* call code on admin pages only, not on front end requests or during AJAX calls. */
    }
  }

 	/**
 	 * Register the pages and the style and script loader callbacks.
 	 *
 	 * @wp-hook addAdminMenu
 	 * @return  void
 	 */
 	public static function addAdminMenu()
 	{
 		// $main is now a slug named "toplevel_page_SZNAdminPage"
 		// built with get_plugin_page_hookname( $menu_slug, '' )
 		$main = add_menu_page(
 			'SZN AdminPage',                         // page title
 			'SZN AdminPage',                         // menu title
 			// Change the capability to make the pages visible for other users.
 			// See http://codex.wordpress.org/Roles_and_Capabilities
 			'manage_options',                  // capability
 			'SZNAdminPage',                         // menu slug
 			[__CLASS__, 'includeDashboardInterface'] // callback function
 		);
    // This is a duplicate to the main page, to allow changing the name of the tab from the subtab main page etc.
    $sub = add_submenu_page(
 			'SZNAdminPage',                         // parent slug
 			'Main Page',                     // page title
 			'Main Page',                     // menu title
 			'manage_options',                  // capability
 			'SZNAdminPage',                     // menu slug
 			[__CLASS__, 'includeDashboardInterface'] // callback function, same as above
 		);
    // $sub is now a slug named "SZNAdminPage_page_SZNAdminPage-sub"
    // built with get_plugin_page_hookname( $menu_slug, $parent_slug)
    $sub = add_submenu_page(
 			'SZNAdminPage',                         // parent slug
 			'Sub Page',                     // page title
 			'Sub Page',                     // menu title
 			'manage_options',                  // capability
 			'SZNAdminPage-sub',                     // menu slug
 			[__CLASS__, 'includeDashboardInterface'] // callback function, same as above
 		);
 		/* See http://wordpress.stackexchange.com/a/49994/73 for the difference
 		 * to "'admin_enqueue_scripts', $hook_suffix"
 		 */
 		foreach ( [$main, $sub] as $hookSuffix )
 		{
      // included php files that include the javascript initialization file that creates the global SZN object, should be added to the page first. (so make sure it is added before the js and css, seems like wordpress adds the admin hook before js and css hooks)
      add_action("admin_head-{$hookSuffix}", [__CLASS__, 'includeToAdminHead']);
 			// make sure the style callback is used on our page only
 			add_action("admin_print_styles-{$hookSuffix}", [__CLASS__, 'enqueue_style']);
      add_action("admin_print_scripts-{$hookSuffix}", [__CLASS__, 'enqueue_script']);
      add_action("load-{$hookSuffix}", function() {
        $usingAdminAreaHook = true;
        // $classesObjects['FilesController']  = self::$classesObjects['FilesController']; // to allow calling a function from a static call.
        self::$classesObjects['FilesController']->applyFilesToDocument($usingAdminAreaHook);
      }); // add all files listed in firebase to admin area. from styles to scripts.. .
 		}
 	}
 	/**
 	 * Print included HTML file.
 	 *
 	 * @wp-hook SZNAdminPage_page_t5-text-included
 	 * @return  void
 	 */
 	public static function includeDashboardInterface()
 	{
 		global $title;
 		print "<h1>$title</h1>";
 		$file = self::join_paths( self::$plugin_directory_path, 'dashboardInterface', "mainDocumentAppDashboard.php" );
 		if ( file_exists( $file ) ) require $file;
 	}
  /**
 	 * Include files to admin head.
 	 *
 	 * @return void
 	 */
  public static function includeToAdminHead()
  {
    // !important scripts added here, get printed in the head section before the firebase files. i.e. before polymer library which causes some problems.
    // echo '<script src="https://www.gstatic.com/firebasejs/3.0.5/firebase-app.js"></script>';
    // echo '<link rel="import" href="' . self::join_paths( self::$plugin_directory_url, '/dashboardInterface/elements', "/szn-appfiles.html") . '"></link>';
    // echo '<link rel="import" href="' . self::join_paths( self::$plugin_directory_url, '/dashboardInterface/elements', "/szn-fileslist.html") . '"></link>';

    $files = [];
    // first file to be loaded is for initialization.
    $files[] = self::join_paths( self::$plugin_directory_path, 'dashboardInterface/javascripts', "initializeGlobalSZN.js.php");
    // Other files should be included after initialization.
    $files[] = self::join_paths( self::$plugin_directory_path, 'dashboardInterface/javascripts', "appBehaviors.js.php");
    $files[] = self::join_paths( self::$plugin_directory_path, 'dashboardInterface/javascripts', "firebaseSDK.js.php");

    foreach ($files as $file) {
      if ( file_exists( $file ) ) include $file;
    }
  }
 	/**
 	 * Load stylesheet on our admin page only.
 	 *
 	 * @return void
 	 */
 	public static function enqueue_style()
 	{
 		wp_register_style(
 			'SZN_dashboardCSS',
 			self::join_paths( self::$plugin_directory_url, 'dashboardInterface/stylesheets', "main.css")
 		);
 		wp_enqueue_style( 'SZN_dashboardCSS' );
 	}
 	/**
 	 * Load JavaScript on our admin page only.
 	 *
 	 * @return void
 	 */
 	public static function enqueue_script() // Not sure this is working !
 	{
 		wp_register_script(
 			'SZN_dashboardJS',
 			self::join_paths( self::$plugin_directory_url, 'dashboardInterface/javascripts', "main.js" ),
 			[],
 			false,
 			true
 		);
    wp_enqueue_script('SZN_dashboardJS');
 	}

}
