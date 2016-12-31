<?php
namespace SZN\_View; // should be on top.

class Template extends ViewController {
  public function __construct($objectArgs) {
    self::setObjectProperties($this, $objectArgs);
  }
}
