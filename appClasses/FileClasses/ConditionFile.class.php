<?php
namespace SZN\_File; // should be on top.

class ConditionFile extends File {
  public $function; // variable that contains the function ready to check the result of condition file.
  public $result; // if condition checked once then save the result for faster returning;

  public function __construct($fileArgs) {
    parent::__construct($fileArgs);
    $this->filePath['directoryPath'] = self::join_paths($this->getFileBasePathFromPathName()['directoryPath'], 'phpfiles/conditions', $this->source);
    $this->createCondition();
  }

  public function createCondition() {
      switch ($this->type) :
        case "function":
          $this->function = function() {
            return (  include $this->filePath['directoryPath'] );
          };
        break;
        case null:
        case '':
        break;
      endswitch;
  }

  public function checkCondition($expectedReturnedResult) {  // call the condition file as a function and return the result.
      if(!$this->result) {     // if condition already checked, return result directly.
        if($this->function) { // if function property was set.
          // http://stackoverflow.com/questions/15188058/why-store-function-as-variable-in-php
          $function = $this->function;
          $this->result = $function();
        } else {  // in case the functin property was not created, maybe because the condition file doesn't exist.
          $this->result = "Condition wasn't created properly (function property not set)";
        }
        // var_dump($this->source);
        // var_dump($this->result);
        // var_dump($expectedReturnedResult);
      }
      return ($this->result === $expectedReturnedResult) ? true :  false; // if the result of condition file function is the same as 'returnedResult' expectation then condition is met.
  }

}
