<?php

/**
 * Plugin Name: SZN - App Classes
 * Description: Register the autoloader for all our app classes, all of which must use the WPPlugins top level.
 * Author: SZN
 * Version: 1.0
 * License: Help yourselves to the shelves
 * Plugin URI: http://www.dentrist.com/
 * Author URI: http://www.dentrist.com/
 */

 if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// example of class usage -  $file = SZN\WPExtend\ContentTypes::VARIABLE;

  // Partially from http://stackoverflow.com/questions/17806301/best-way-to-autoload-classes-in-php
 spl_autoload_register(function ($class) {

   // array_shift - removes first value.
   // array_pop - removes last value.

  // $current_namespace =str_replace("\\","/",__NAMESPACE__); // Current file namespace, incase the autoloader itself has namespace.
 	$segments = array_filter(explode("\\", $class)); // [MAINNAMESPACE, SUBNAMESPACE, CLASS]
  // $className = str_replace("\\","/",$class); // MAINNAMESPACE/SUBNAMESPACE/CLASS
  // $path = __DIR__ . '/' . (empty($current_namespace)?'':$current_namespace.'/') . "{$className}.class.php";
  $classMainNamespace = array_shift($segments); // MAINNAMESPACE

  if($classMainNamespace === "SZN") { // Changed WPPlugins to SZN

    $className = array_pop($segments); // CLASS

    if(substr( end($segments), 0, 1 ) === "_") { // Check if the last $segment starts with "_", denoting it is the file name in which the class lives (According to my setup).
      $fileName = str_replace("_", "", array_pop($segments)); // Remove "_" from Last segment is the filename. // FILENAME
      $folders = implode("/", $segments); // SUBSPACE/SUBNAMESPACE => SUBFOLDER/SUBFOLDER
    } else { // In case all SUBNAMESPACE denoting the folder structure. & Class name is the actuall file name.
      $fileName = $className;
      $folders = implode("/", $segments); // SUBSPACE/SUBNAMESPACE => SUBFOLDER/SUBFOLDER
    }

    $path = __DIR__ . '/' . (empty($folders) ? '' : $folders . '/') . "{$fileName}.class.php";

    if (file_exists($path)) {
      include $path;
    }

 	}
  // elseif($classMainNamespace === "TemplateSystem") { // The TemplateSystem class doesn't have Namespace therefore it must be required seperately not using the automatic above function to determine it's location.
  //   require_once( 'TemplateSystem.class.php');
  // }


 });
