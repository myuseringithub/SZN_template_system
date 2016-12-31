<?php
/*
Plugin Name: SZN - Template System : Content HTML code & MySQL query generator (Content Output for each specific content).
Plugin URI: http://www.dentrist.com/
Description: Specific PHP process to produce desired output from request. This function selects and aggregated content according to parameters and pages requested: eitehr main page, archive, cu$
Version: 1.0
Author: SZN
Author URI: http://www.dentrist.com/
License: Help yourselves to the shelves
*/
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
// Autoloaders
require_once('dependencies/services_dependency/vendor/autoload.php'); // Symbolic link to "services_dependency" - Composer classes - e.g. Firebase classes.
require_once('appClasses/autoloadAppClasses.php'); // App classes

// Get the directory/folder of the current file. i.e. the plugins directory path (road map) & URL.
\SZN\App::$locations['plugin']['path'] = plugin_dir_path( __FILE__ );
\SZN\App::$locations['plugin']['url'] = WPMU_PLUGIN_URL . '/SZN_template_system'; // !!! SHOULD Change plugin_dir_url returned incorrect path with url.

new \SZN\App(); // Create main instance of App & Initialize settings.

require_once('wp_functions/SZN_wp_functions.php');
// require_once('/packages/SZN_packages.php');
// require_once('scripts_styles/SZN_scripts_styles.php');
// require_once('web_components/SZN_web_components.php');
// require_once('JSON_REST_API/SZN_JSON_REST_API.php');
// require_once('ADS/SZN_ads.php');
