<?php
namespace SZN\_View; // should be on top.

class ViewTree extends ViewController {

  public function __construct($objectArgs) {
    self::setObjectProperties($this, $objectArgs);  // Set object properties using a list of properties args.
  }

  public function render() {
      // self::$count['num'] = 0;

      // \SZN\TemplateSystem::$viewControllerObject->renderViews([$this->viewUnit]);
      self::$currentViewTreeArray[] = $this; // for tracking paths along the viewTrees.

      // foreach ($this->childrenViewTrees as $childTreeKey => $childTree) {
      //   self::$units['ViewTree'][$childTreeKey]->viewUnit;
      // }

      if($this->checkConditions())  {  // display if conditions are met.
        $this->includeViews([$this->viewUnit]);
        // $this->insertUnitsOfClassIntoDocument([$this->viewUnit], 'View'); // insertViewsIntoDocument
      }

      // print_r(self::$count);

      // clear array values for the next tree
      // unset(self::$count);

      // self::$count = [];
      array_pop(self::$currentViewTreeArray);
  }

  public function checkConditions() { // Check if view conditions are met.
    // CHECK CONDITIONS IF MET
    $conditionsResult = self::$classesObjects['FilesController']->checkConditions($this->conditions); // check the conditions if met.
    return $conditionsResult;
  }

}
