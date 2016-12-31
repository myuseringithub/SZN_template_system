<?php

namespace SZN\_View; // should be on top.
// use \Base\Section as BSection; // alias - http://stackoverflow.com/questions/3449122/extending-a-class-with-an-other-namespace-with-the-same-classname

if ( ! defined( 'ABSPATH' ) ) { exit; } // security measure

// View - File containing code with query loop.
// Partial view - File containing code with query loop, that can be rendered inside a parent view.
// renderedTemplate - is the final HTML rendered code. As a result of combination of queries, views, partial views, and the main layout.

class ViewController extends \SZN\TemplateSystem { // A view controller manages a set of views that make up a portion of your app’s user interface.

  // public $renderedViews = []; // Array of Views with data inserted. $key defined the insertion position in the layout.
  public static $units = []; // all units of different class types - Template, View, ViewTree. // all the views that were defined for this template. & all viewTrees & templates.

  public static $currentViewTreeArray = [];

  public static $count = [];

  public static $treePaths = [];

  public function __construct() {
  }

  public function defineUnits($units, $unitClass) { // array of units and unit class.
    foreach ($units as $unitKey => $unitArgs) {
      $unitArgs['key'] = $unitKey;
      $unitObject = $this->defineUnit($unitArgs, $unitClass);
      self::$units[$unitClass][$unitKey] = $unitObject;
    }
  }

  public function defineUnit($unitArgs, $unitClass) {  // returns an object from the specific unit args created by the corresponding unitType class.
    $namespaceClassName = __NAMESPACE__ . '\\' . $unitClass;
    $unit = new $namespaceClassName($unitArgs);
    return $unit;
    // ----- PREVIOUS COMMENTS
    // if(isset($viewArgs['parentViewFile'])) {
    //   $view = new PartialView();
    // } elseif(!isset($viewArgs['parentViewFile'])) {
    //   $view = new MainView();
    // }
    // $view->defineView($viewArgs);
    // self::$views[] = $view; // not partial views.
    // return $view;
  }

  public function applyRenderFunctionOnUnits($unitsKeys, $classname) { // call 'render' method on unit object of specific class.
    foreach($unitsKeys as $unitKey) {
      self::$units[$classname][$unitKey]->render();
    }
  }

  // public function renderViews($viewsKeys) {
  //   // foreach (self::$views as $view) {
  //   //   if(is_a($view, __NAMESPACE__ . '\\' . 'MainView')) {
  //   //     $view->renderView();
  //   //   }
  //   // }
  //   foreach($viewsKeys as $viewKey) {
  //     self::$units['View'][$viewKey]->renderView();
  //     // $this->renderedViews[$viewPositionInLayout] = $this->renderedViews[$viewPositionInLayout] . $obContents;
  //     // if(isset($view['children'])) {
  //     // }
  //   }
  // }
  //
  // public function renderViewTrees($toplevelViewTrees) {
  //   // foreach (self::$views as $view) {
  //   //   if(is_a($view, __NAMESPACE__ . '\\' . 'MainView')) {
  //   //     $view->renderView();
  //   //   }
  //   // }
  //   foreach($toplevelViewTrees as $toplevelViewTree) {
  //     self::$units['ViewTree'][$toplevelViewTree]->renderViewTree();
  //     // $this->renderedViews[$viewPositionInLayout] = $this->renderedViews[$viewPositionInLayout] . $obContents;
  //     // if(isset($view['children'])) {
  //     // }
  //   }
  // }

  // public function addRenderedView($viewPositionInLayout, $content) {
  //   $this->renderedViews[$viewPositionInLayout] = $this->renderedViews[$viewPositionInLayout] . $content;
  // }

  public function getSortedViewsAccordingToPosition($views, $childrenViews) {
    foreach ($childrenViews as $unitKey => $unitValue) { // $unitValue = childView
      $views[$unitValue['insertionPosition']][] = $unitKey;
    }
    return $views;
  }

  // public function getSortedAndCombinedRenderedViews($childrenViews) {
  //   foreach ($childrenViews as $keyChild => $viewChild) {
  //     $views[$viewChild['position']] = $views[$viewChild['position']] . self::$views[$keyChild]->renderedContents;
  //   }
  //   return $views;
  // }

  // public function getAddedTreeViews($currentChildViews) {
  //   // foreach ($childrenViews as $keyChild => $viewChild) {
  //   //   $views[$viewChild['insertionPosition']][] = $keyChild;
  //   // }
  //   // var_dump($currentChildViews);
  //
  //   $views = $currentChildViews;
  //   return $views;
  // }

  public function includeViews($viewsKeys, $is_child = false) {
    // foreach ($views as $view) {
    //   $view->renderView();
    // }
    foreach ($viewsKeys as $viewKey) {

      // check view condition HERE !!

      // case viewTree Class
      $namespaceClassName = __NAMESPACE__ . '\\' . 'ViewTree';
      if(is_a($viewKey, $namespaceClassName)) {
        $viewKey->render();
        continue;
      }

      // case view key serial string.
      $this->includeView($viewKey, $is_child);
    }
  }

  public function includeView($viewKey, $is_child = false) {
    $currentViewTreeArray = end(self::$currentViewTreeArray);

    if($is_child){
      // self::$count['num'] = self::$count['num'] + 1;
      self::$treePaths[$currentViewTreeArray->key][] = $viewKey;
    }

    foreach ($currentViewTreeArray->childrenViewTrees as $childTreeKey => $childTree) { // viewTrees inside other viewTrees.
      $viewTreePathString = self::$units['View'][$currentViewTreeArray->viewUnit]->treePaths[$childTree['viewTreePath']]; // get the tree path
      $viewTreePathArray = preg_split('@/@', $viewTreePathString, NULL, PREG_SPLIT_NO_EMPTY); // seperate tree paths
      if( self::$treePaths[$currentViewTreeArray->key] == $viewTreePathArray ) {
        // $views[$childTree['viewInsertionPosition']][] = self::$units['ViewTree'][$childTreeKey]->viewUnit;
        $views[$childTree['viewInsertionPosition']][] = self::$units['ViewTree'][$childTreeKey];
      }
    }

    if(self::$units['View'][$viewKey]->checkConditions())  {  // display if conditions are met.
      self::$units['View'][$viewKey]->render($views); // render view
      echo self::$units['View'][$viewKey]->renderedContents;
    }

    if($is_child){
      array_pop(self::$treePaths[$currentViewTreeArray->key]);
      // self::$count['keys'][$viewKey] =  self::$count['num'];
      // self::$count['num'] = self::$count['num'] - 1;
    }

  }

  // public function insertViewsIntoLayout($layout) {
  //   $this->renderViews();
  //   //ob_start();
  //     $views = $this->renderedViews;
  //     include( \SZN\UtilityFunctions::join_paths(self::$templatesPath, 'layouts', $layout) ); // to the DOCUMENT DIRECTLY.
  //   //$this->renderedTemplate = ob_get_clean();
  // }

  public function insertUnitsOfClassIntoDocument($units, $classname) { // echo return from the call renderedContents on specific class.
    foreach ($units as $unitKey) {
      echo self::$units[$classname][$unitKey]->renderedContents;
    }
  }

}

require('ViewClasses/ViewTree.class.php');
require('ViewClasses/View.class.php');
require('ViewClasses/Template.class.php');

// class MainView extends View {
//   public $viewPositionInLayout;
//
//   public function defineView($viewArgs) {
//     parent::defineView($viewArgs);
//   }
//
//   public function renderView() {
//     $obContents = parent::renderView();
//     $this->addRenderedView($this->viewPositionInLayout, $obContents);
//   }
// }
//
// class PartialView extends View {
//   public $parentViewFile;
//   public $partialViewPositionInParentView;
//   public $specificInludedViewFile;
//
//   public function defineView($viewArgs) {
//     parent::defineView($viewArgs);
//   }
//
//   public function renderView() {
//     $obContents = parent::renderView();
//     echo $obContents;
//   }
// }
