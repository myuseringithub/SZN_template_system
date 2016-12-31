<?php
namespace SZN\_File; // should be on top.

if ( ! defined( 'ABSPATH' ) ) { exit; } // security measure

class FilesController extends \SZN\TemplateSystem { /* A class that will hold all files to be added to the pages - i.e. scripts like javascript, css, etc. */
  use FilesControllerProcessing;

  public $filesObjects = []; // all file objects;
  public static $filesLoadedIntoDOM = [];
  public $conditionsObjects = []; // all condition objects;

  public function __construct() { // DO NOT DELETE THIS EMPTY CONSTRUCT.
  }

  public function defineFiles() {
    foreach ((array)self::$settings['files']['conditions'] as $unitKey => $unitArgs) {
      (!$unitArgs['name']) ? $unitArgs['name'] = $unitKey : null;
      // $unitArgs['type'] = $this->addFileArgsArrayType($unitArgs);

      if( (boolean)$this->validateArgs($unitArgs) ) {
          $this->conditionsObjects[$unitKey] = new ConditionFile($unitArgs);;
      }
    }

    self::$filesLoadedIntoDOM = array_merge(
      (array)self::$settings['files']['javascripts'],
      (array)self::$settings['files']['codeblocks'],
      (array)self::$settings['files']['stylesheets'],
      (array)self::$settings['files']['elements']
    ); // incase null it will change to array to prevent error throwing.

    foreach (self::$filesLoadedIntoDOM as $unitKey => $unitArgs) {
      (!$unitArgs['name']) ? $unitArgs['name'] = $unitKey : null;
      $unitArgs['type'] = $this->addFileArgsArrayType($unitArgs);

      if( $this->checkConditions($unitArgs['conditions'] )
          && (boolean)$this->validateArgs($unitArgs) )  {
            $this->filesObjects[$unitKey] = $this->defineFile($unitArgs);
        }
    }

    return self::$classesObjects['FilesController']; // to allow chaining actions.
  }

  public function defineFile($unitArgs) { // Accepts File arguments.
      switch ($unitArgs['type']) :
        case "js":
          $unitObject = new JSFile($unitArgs);
        break;
        case "css":
          $unitObject = new CSSFile($unitArgs);
        break;
        case "php":
          $unitObject = new PHPFile($unitArgs);
        break;
        case "html":
          $unitObject = new ElementFile($unitArgs);
        break;
        case null:
        case '':
      endswitch;

      return $unitObject;
  }

  public function applyFilesToDocument($adminAreaHook = false) { // admin area when using admin hook.
    // Hook function to Wordpress Action.
    foreach ($this->filesObjects as $unitObject) {
      $unitObject->filePositionInPage = $unitObject->getWPActionNameFromFilePositionInPageName($adminAreaHook);  // in case using for admin area or other option of frontend-user.
      $hookPriority = PHP_INT_MAX; // A trick to hook very late (i.e. be the last). PHP_INK_MAX is for the priority of the hooked element, in this case it is last, so codeblocks would rended lastely after wp-head if choosen. http://wordpress.stackexchange.com/questions/142416/hook-after-wp-enqueue-scripts.
      add_action( $unitObject->filePositionInPage, [$unitObject, 'applyToDocument'], $hookPriority);
    }
  }
}
trait FilesControllerProcessing {
  public function addFileArgsArrayType($unitArgs) {
    return pathinfo($unitArgs['source'], PATHINFO_EXTENSION);
  }
  public function checkConditions($conditions = []) {
    if( empty($conditions) || $conditions == NULL ) { // case there are no conditions.
      return true;
    } else {
      $conditionMet = true;
      foreach ($conditions as $conditionKey => $conditionSettings) { // All conditions array should be met (&&). check each condition in array.
        if(!isset($conditionSettings['returnedResult'])) {  // in case no expected value is set.
          $conditionSettings['returnedResult'] = true;
        }
        $conditionMet = ($this->conditionsObjects[$conditionKey]) ?
                        $this->conditionsObjects[$conditionKey]->checkCondition($conditionSettings['returnedResult'])
                        : false ;
        if($conditionMet != true) break;
      }
      return $conditionMet;
    }
  }
  public function validateArgs($unitArgs) { // check Files arguments validity. // check that all necessary properties exist.
    if( $unitArgs['source'] ) {
      return true;
    } else {
      $this->notDefinedFiles['name'];
      return false;
    }
  }
}

class File extends FilesController {
  use ExternalRecievedArgsProcessing;

  // public $name; public $source; public $type; public $path; public $filePositionInPage; & Other parameters

  public function __construct($fileArgs=[]) {
    foreach($fileArgs as $unitKey => $unitValue){
      $this->{$unitKey} = $unitValue; // {} emphasizes the access of variable-named property.
    }
  }
}
trait ExternalRecievedArgsProcessing {
  public function getWPActionNameFromFilePositionInPageName($adminAreaHook = false) { // Admin Area In Specific Page.
    switch ($this->filePositionInPage) :
      case "general":
        $filePositionInPage = 'wp_enqueue_scripts';
      break;
      case "footer":
        $filePositionInPage = 'wp_footer';
      break;
      case "admin":
        $filePositionInPage = 'admin_enqueue_scripts';
      break;
      case null:
      case '':
        $filePositionInPage = 'SZN_after_head_tag';
      break;
      default:
        $filePositionInPage = $this->filePositionInPage;
    endswitch;
    // with current setup 'wp-head' would hook after the 'wp_enqueue_scripts' because wp_enqueue_scripts has priority of 1 and wp-head set in this file to maximum priority possible.

    // Admin area related.
    if($adminAreaHook) {
      switch ($filePositionInPage) :
        case "wp_footer":
        case "admin_enqueue_scripts":
          $filePositionInPage = 'admin_footer';
        break;
        default:
          $filePositionInPage = 'admin_head';
      endswitch;
    }

    return $filePositionInPage;
  }

  public function getFileBasePathFromPathName() { // returns $basePath with ['directoryPath'] & ['urlPath']
    $basePath = [];
    switch ($this->path) :
      case "custom":
      case "app":
        $basePath['directoryPath'] = self::$locations['app']['path'];
        $basePath['urlPath'] = self::$locations['app']['url'];
      break;
      case "sharedCustom":
      case "sharedApp":
        $basePath['directoryPath'] = self::join_paths(self::$locations['app']['path'], 'sharedApp');
        $basePath['urlPath'] = self::join_paths(self::$locations['app']['url'], 'sharedApp');
      break;
      case "bowerComponents":
      case "bower":
        $basePath['directoryPath'] = self::$locations['bowerComponents']['path'];
        $basePath['urlPath'] = self::$locations['bowerComponents']['url'];
      break;
      case null:
      case '':
          // return plugins_url( $this->source ,__FILE__); //////// SHOULD USE IN SOME CASES Must Use Plugins URL
      break;
      default:
        $basePath['directoryPath'] =  self::$locations['app']['path'];
        $basePath['urlPath'] = self::$locations['app']['url'];
    endswitch;

    return $basePath;
  }

  public function getFilePathFromSource($pathType) {
      return self::join_paths( $this->getFileBasePathFromPathName()[$pathType], $this->source );
  }
}

// Filter to Wordpress CSS Enqueue - Not for dentrist but for webapps  !!! !!
// function SZN_addAttributeToStyle($src) {
//     return str_replace("type='text/css'", "type='text/css' is='custom-style'", $src);
// }
// add_filter('style_loader_tag', 'SZN_addAttributeToStyle');

// Remove default wordpress jquery.js files and others
// function remove_jquery_migrate( &$scripts)
// {
//     if(!is_admin())
//     {
//         $scripts->remove( 'jquery');
//         $scripts->add( 'jquery', false, array( 'jquery-core' ), '1.11.1' );
//     }
// }
// function jquery_cdn() {
//    if (!is_admin()) {
//       wp_deregister_script('jquery');
//       wp_register_script('jquery', '', false, '1.8.3');
//       wp_enqueue_script('jquery');
//    }
// }
// add_action('init', 'jquery_cdn');
// add_filter( 'wp_default_scripts', 'remove_jquery_migrate' );

// Extended Files:
require('FileClasses/CSSFile.class.php');
require('FileClasses/JSFile.class.php');
require('FileClasses/PHPFile.class.php');
require('FileClasses/ElementFile.class.php');
require('FileClasses/ConditionFile.class.php');
