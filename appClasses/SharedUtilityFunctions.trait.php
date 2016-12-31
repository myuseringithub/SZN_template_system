<?php
namespace SZN; // should be on top.

trait SharedUtilityFunctions {

  public static $URL = [
    'segments'  =>  [],
    'path' => ''
  ]; // get path segments.

  public static $printTests = []; // for testing
  public static function printTests() {
    $uri_parts = explode('?', $_SERVER['REQUEST_URI'], 2);
    if($uri_parts[0] == '/test') {
      echo 'Tests:';
      echo '<pre>';
      print_r(self::$printTests);
      echo '</pre>';
    }
  }

  // [EXTEND CLASS] - Add children methods to top-level class.
  public static $extenderStaticClasses = ['\SZN\UtilityFunctions', '\SZN\TemplateSystem']; // Function in these classes are added to the App class. // list of implemented classes  //http://stackoverflow.com/questions/6565261/can-you-extend-two-classes-in-one-class
  // private $objects = []; // storage for objects of classes
  public static function __callStatic($methodName, $methodArgs) { // looking for class method in the static classes. ALTERTED TO STATIC CLASSES from : http://stackoverflow.com/questions/6565261/can-you-extend-two-classes-in-one-class
      //
      // NOT QUITE WORKING, JUST PREVENTS ERROR FOR FUNCTIONS USING REFERENCED PARAMS - Gets around a limitation in PHP. To allow to pass parameters by reference a wordaround is used.
      // $referenceableArgs = [];
      // foreach ($methodArgs as &$methodArg) {
      //   $referenceableArgs[] = &$methodArg;
      // }

      foreach(self::$extenderStaticClasses as $unitName) {  // Check methods that exists in the array of extender classes.
          $callback = array($unitName, $methodName);
          if(is_callable($callback) && method_exists($unitName, $methodName))  { // if method can be called from current scope && if method exists in class.
            if( isset($methodArgs[0]) && $methodArgs[0] != null && is_array($methodArgs[0])) { // if a multidimensional array, that may hold reference params (used as a workaround becouse of the limitation to pass directl params as references in _call function.)
              // must be called by name rather than 'self' inorder to prevent infinate loop.
              // if($methodName == 'sortMultidimensionalArrayByChild') var_dump(\SZN\UtilityFunctions::checkEqualArrayReferences($methodArgs[0]));
              if(self::checkIfArrayHasReferenceParam($methodArgs[0])) { // create a copy of methodArgs inorder to check if it is passing reference.
                // $arg = func_get_arg(1); // create a copy of methodArgs inorder to check if it is passing reference.
                $methodArgs = array_shift($methodArgs); // Get first array element to be used args in case where the params are passed using parent array to allow params refrence with _call function. Its a work around for the problem.
                return call_user_func_array($callback, $methodArgs);  // call method.
              }
            }
            return call_user_func_array($callback, $methodArgs);  // call method.
          }
      }
  }

  public static function includeFileWithArgs($fileName, $args=NULL) {
      if(file_exists($fileName)) include($fileName);
  }

  public static function checkIfArrayHasReferenceParam($arrayParameters) { // check if to array paramenter references are the same. i.e. contains atleast one parameter which is a reference.
    foreach (array_keys($arrayParameters) as $unitKey) { // loop through all array parameters.
      $statement = self::checkIfParamsAreEqualReferences($arrayParameters, $arrayParameters, $unitKey);
      if($statement) return true; // break when at least one array parameter is referenced.
    }
    return false;
  }

  public static function checkIfParamsAreEqualReferences($unit1, $unit2, $unitKey) { /// check if array contains refernce parameters in specific position.
    if($unit1[$unitKey] !== $unit2[$unitKey]){
        return false;
    }
    $value_of_first = $unit1[$unitKey];
    $unit1[$unitKey] = ($unit1[$unitKey] == true) ? false : true; // modify $first by making sure it is changed.
    // var_dump($second[0]);
    $is_ref = ($unit1[$unitKey] === $unit2[$unitKey]); // after modifying $first, $second will not be equal to $first, unless $second and $first points to the same variable.
    $unit1[$unitKey] = $value_of_first; // unmodify $unit1
    return $is_ref;
  }

  // To allow reference by param to work, pass the variable to the function with '[&$param]'
  public static function sortMultidimensionalArrayByChild(&$array, $sortByField = 'order') { // first argument is passed by refrence so it gets to be modified.
      if(is_array($array)) {
        uasort($array, function($previous, $next) use ($sortByField) {
          if ( !isset($previous[$sortByField]) && !isset($next[$sortByField]) ) {
            return 0;
          }
          return $previous[$sortByField] - $next[$sortByField];
        });
      }
      return $array;
  }

  // Funtions for absolute paths and also urls. it combined two paths and removes extra slashs or adds if needed between two parts.
  public static function join_paths() { // using function get args will get all inserted variables in the call.
    $paths = [];
    $args = func_get_args();
    if(self::isMultidimensionalArray($args)) {
      $args = self::joinArraysWithStrings($args);
    }

    foreach ($args as $arg) { // remove null or empty values.
        if ($arg !== '') {
          $paths[] = $arg;
        }
    }

    return self::joinStringPaths($paths);
  }

  public static function joinStringPaths($paths) { // join strings with slashes, with no duplicate slashes inbetween.
    if(filter_var($paths[0], FILTER_VALIDATE_URL)) { // if a url
      $protocol = strtolower(substr($paths[0],0,strpos( $paths[0],'/'))).'//'; // get https:// or http:// from first part.
      $paths[0] = preg_replace("(^https?://)", "", $paths[0] );
      return $protocol . preg_replace('#/+#','/',join('/', $paths)); // combine paths and add protocol
    } else { // absolute path directory
      return preg_replace('#/+#','/',join('/', $paths));
    }
  }

  public static function joinArraysWithStrings($mixedArray) { // join array of strings and subarrays. i.e. transforms subarrays to string units in a new main array.
    $combinedArray = [];  // all stings array, created from arrays of strings and strings values.
    foreach ($mixedArray as $value) {
        if(is_array($value)) { // if array
            if(self::isMultidimensionalArray($value)) {
              $value = self::joinArraysWithStrings($value); // if nested keep on looping function.
            }
            $combinedArray = array_merge($combinedArray, $value); // metge array
        } else {  // if string
          $combinedArray[] = $value;
        }
    }
    return $combinedArray;
  }

  public static function isMultidimensionalArray($array) {
      $result = array_filter($array,'is_array');
      if(count($result)>0) return true;
      return false;
  }

  /**
   * Return class methods by scope
   *
   * @param string $class
   * @param bool $inherit
   * @static bool|null $static returns static methods | object methods | both
   * @param array $scope ['public', 'protected', 'private']
   * @return array
   */
  public static function getClassMethods($class, $inherit = false, $static = null, $scope = ['public', 'protected', 'private'])
  {
      $return = [
          'public' => [],
          'protected' => [],
          'private' => []
      ];
      $reflection = new \ReflectionClass($class);
      foreach ($scope as $key) {
          $pass = false;
          switch ($key) {
              case 'public': $pass = \ReflectionMethod::IS_PUBLIC;
                  break;
              case 'protected': $pass = \ReflectionMethod::IS_PROTECTED;
                  break;
              case 'private': $pass = \ReflectionMethod::IS_PRIVATE;
                  break;
          }
          if ($pass) {
              $methods = $reflection->getMethods($pass);
              foreach ($methods as $method) {
                  $isStatic = $method->isStatic();
                  if (!is_null($static) && $static && !$isStatic) {
                      continue;
                  } elseif (!is_null($static) && !$static && $isStatic) {
                      continue;
                  }
                  if (!$inherit && $method->class === $reflection->getName()) {
                      $return[$key][] = $method->name;
                  } elseif ($inherit) {
                      $return[$key][] = $method->name;
                  }
              }
          }
      }
      return $return;
  }

  // get list of class method names that begin with the provided prefix.
  public static function getMethodsPrifexWithString($className, $prefixString) {  // get all immediate function with 'szn_' prefix.
    $immediateFunctions = self::getClassMethods($className); // get immediate methods of the class sorted in public, private, protected..
    // Admin ajax endpoint function names. Will be added to the wordpress endpoints under wp_ajax. named as szn_FUNCTIONANME.
    $filteredArray = array_filter($immediateFunctions['public'], function($value) use ($prefixString) { // use ($variable) allows to use the external variable inside a callback.
        return strpos($value, $prefixString) === 0;
    });
    return $filteredArray;
  }

  // APP CLASS DEPENDEDNT - Dependent on APP properties. firebase defaultPath & class.
  public static function queryFirebase($path) { // get/query specific path from Firebase
    return json_decode(self::$classesObjects['Firebase']->get( self::join_paths(self::$locations['firebase']['defaultUrlPath'], $path) ), true);
  }

  public static function setObjectProperties($object, $objectProperties) {  // define object's properties from arguments.
    foreach($objectProperties as $unitKey => $unitValue){
      $object->{$unitKey} = $unitValue; // {} emphasizes the access of variable-named property.
    }
  }

  // Dependent on Wordpress plugin to work - 'mobble' or something like that.
  /* USAGE:
  * if( SZN\WPExtend\Visitor::checkPlatform(['desktop', 'mobile', 'tablet']) ) { }
  *
  *
  */
  public static function checkPlatform($array_platform) {
      // Check platform - mobile, tablet, desktop
      $platform_condition = false;
      foreach ($array_platform as &$platform) {
        	switch ($platform) { // get condition to check which platform is required
          	case "desktop":
          		$platform_condition = (!is_mobile() && !is_tablet());
          	break;
          	case "mobile":
          		$platform_condition = is_mobile();
          	break;
          	case "tablet":
          		$platform_condition = is_tablet();
          	break;
        	}
        	if ($platform_condition) { $platform_condition = true; break;}
      }
      return $platform_condition;
  }

  // Dependent on 'Groups' plugin to work.
  // uses Groups plugin
  //* NOT WORKING BECAUSE THE PLUGINS ARE INSTANTIATED AFTER THIS CLASS *//
  public static function isCurrentUserInGroup($group) {
    require_once( ABSPATH . 'wp-includes/pluggable.php' );
    global $current_user;
    $is_a_member = false;

    // Be aware of namespaces
    $groupObject = \Groups_Group::read_by_name( $group );
  	if ($groupObject) {
  		 $is_a_member = \Groups_User_Group::read( get_current_user_id() , $groupObject->group_id );
  	}

    return $is_a_member;
  }

  // foreach ($args as $arg) { // remove null or empty values.
  //   if ($arg !== '') {
  //     $paths[] = $arg;
  //   }
  // }
  // if(self::isMultidimensionalArray($args)) {
  //   $args = self::joinArraysWithStrings($args); // join subarrays to one array of strings.
  // }
  // print_r(self::joinStringPaths($paths));
  // // array_filter($paths); // handles removing empty values;
  // return self::joinStringPaths($paths);

}
