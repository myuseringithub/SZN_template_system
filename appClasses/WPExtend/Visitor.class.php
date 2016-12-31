<?php

 namespace SZN\WPExtend; // should be ontop.

 if ( ! defined( 'ABSPATH' ) ) { exit; } // security measure

//  For BACKWARD COMPATIBILITY
class Visitor extends \SZN\App {

  public static $extenderStaticClasses = ['\SZN\UtilityFunctions']; // Function in these classes are added to the App class. // list of implemented classes  //http://stackoverflow.com/questions/6565261/can-you-extend-two-classes-in-one-class

  public static function __callStatic($methodName, $methodArgs) { // looking for class method in the static classes. ALTERTED TO STATIC CLASSES from : http://stackoverflow.com/questions/6565261/can-you-extend-two-classes-in-one-class
      foreach(self::$extenderStaticClasses as $unitName) {  // Check methods that exists in the array of extender classes.
          $callback = array($unitName, $methodName);
          if(is_callable($callback) && method_exists($unitName, $methodName))  { // if method can be called from current scope && if method exists in class.
            return call_user_func_array($callback, $methodArgs);  // call method.
          }
      }
  }


}
