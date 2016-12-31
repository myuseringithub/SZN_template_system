<?php
/*
Plugin Name: SZN - Wordpress Functions
Plugin URI: http://www.dentrist.com/
Description: Custom core wordpress functions used.
Version: 1.0
Author: SZN
Author URI: http://www.dentrist.com/
License: Help yourselves to the shelves
*/

$plugin_dir_path = plugin_dir_path( __FILE__ );
// Post Control VALIDATION - Not working
// include( plugin_dir_path( __FILE__ ) . 'incphp_functions/post-control-validation.php');
// Post settings
include( $plugin_dir_path . 'incphp_functions/posts.php');
// Post control
include( $plugin_dir_path . 'incphp_functions/post-control.php');
// Profile
include( $plugin_dir_path . 'incphp_functions/profile.php');
// Dashboard
include( $plugin_dir_path . 'incphp_functions/dashboard.php');
// Admin bar
include( $plugin_dir_path . 'incphp_functions/admin-bar.php');
// Other
include( $plugin_dir_path . 'incphp_functions/other.php');
// Admin menu
include( $plugin_dir_path . 'incphp_functions/admin-menu.php');
// API
include( $plugin_dir_path . 'incphp_functions/API.php');
// URL variables
include( $plugin_dir_path . 'incphp_functions/url-variables.php');

//////////////////////////////////////////////////////////////////////////////////////



?>
