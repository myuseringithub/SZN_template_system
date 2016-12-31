<?php
namespace SZN\_View; // should be on top.

class View extends ViewTree {

  // public $viewFile;
  // public $queryargsFiles = [];
  // public $conditionalQueryFiles = [];
  // public $viewChildren = [];
  // public $data;

  public function __construct($objectArgs) {
    self::setObjectProperties($this, $objectArgs);
  }

  // public function addRenderedView($viewPositionInLayout, $content) {
  //   self::$viewControllerObject->renderedViews[$viewPositionInLayout] = self::$viewControllerObject->renderedViews[$viewPositionInLayout] . $content;
  // }

  public function render($views = []) {
    $viewTree = end(self::$currentViewTreeArray); // keep track of the path.
    $viewTree->currentPath[] = $this->key;
    if($this->queryargsFiles) { // add wordpress query to rended view.
      $data = self::$classesObjects['DataController']->createQueries($this->queryargsFiles);
      $this->addData($data);
    }
    if($this->conditionalQueryFiles) {
        $data = self::$classesObjects['DataController']->createConditionalQueries($this->conditionalQueryFiles);
        $this->addData($data);
    }
    if (!empty($this->data)) : // If the requested QUERY has POSTS (query above).
      $data = $this->data;
    endif;
    if($this->childrenViews) {
      // Render, Sort views to positions, Combine rendered contents.
      // $this->renderViews(array_keys($this->childrenViews));
      // $views = $this->getSortedAndCombinedRenderedViews($this->children);
      $views = $this->getSortedViewsAccordingToPosition($views, $this->childrenViews);
      // $views = $this->getAddedTreeViews($views);
    }
    // $template = self::$templateObject;
    $templateFilename = self::$units['Template'][$this->templateFile]->templateFile;
    ob_start();
    include( self::join_paths(self::$locations['templates']['path'], $templateFilename) );
    wp_reset_postdata();
    $obContents = ob_get_clean(); // using ob_get_clean prevents errors. problem arises when ob_end_clean is used after return !!!
    $this->renderedContents = $obContents;
    // return $obContents;
  }

  public function addData($data) {
    if($this->data == NULL) {
      $this->data = $data;
    } else {
      if($data != NULL) {
        $this->data = array_merge($this->data, $data);
      }
    }
  }

  public function checkConditions() { // Check if view conditions are met.
    // CHECK CONDITIONS IF MET
    $conditionsResult = self::$classesObjects['FilesController']->checkConditions($this->conditions); // check the conditions if met.
    return $conditionsResult;
  }

}
