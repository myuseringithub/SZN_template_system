<?php

namespace SZN; // should be on top.

// declare(encoding='UTF-8');  // should be ontop to prevent error when using different namespaced code sections in the file.

if ( ! defined( 'ABSPATH' ) ) { exit; } // security measure

class TemplateSystem extends \SZN\App {

  public function __construct() {
  }

  public static function getFileDirectoryPath() {
    // Usage:
    // include( TemplateSystem::getFileDirectoryPath('variables','variables.php') );
    return self::join_paths(self::$locations['phpfiles']['path'], func_get_args());
  }

  public static function includeFilePath() {
    include( self::getFileDirectoryPath(func_get_args()) );
  }

  public static function requireFilePath() {
    require( self::getFileDirectoryPath(func_get_args()) );
  }

  public static function insert($views) { // For Interface Purposes to shorten the name of the function;
    $is_child = true;
    self::$classesObjects['ViewController']->includeViews($views, $is_child);
  }

  // public static function createRouteDocument($file) { // For Interface Purposes to shorten the name of the function;
  //   self::$classesObjects['ViewController']->applyRenderFunctionOnUnits(self::$settings['routes']['document']['toplevelViewTrees'], 'ViewTree'); // Start rendering top level views and propogate.
  // }

  // public function includeCodeblock($codeblock) {
  //   ob_start();
  //     include(self::join_paths(self::$locations['phpfiles']['path'], 'codeblocks', $codeblock['codeblockFile']));
  //   $obContents = ob_get_clean();
  //   //self::$classesObjects['ViewController']->renderedViews[$codeblock['codeblockPositionInLayout']] = self::$classesObjects['ViewController']->renderedViews[$codeblock['codeblockPositionInLayout']] . $obContents;
  //   self::$classesObjects['ViewController']->addRenderedView($codeblock['codeblockPositionInLayout'], $obContents);
  // }

}
