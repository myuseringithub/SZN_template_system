<?php
namespace SZN; // should be on top.

if ( ! defined( 'ABSPATH' ) ) { exit; } // security measure

require('SharedUtilityFunctions.trait.php');

class App {

  use \SZN\SharedUtilityFunctions; // import all functions and properties from this trait.

  // static & self & parent callings - http://stackoverflow.com/questions/20679780/php-access-a-parents-static-variable-from-an-extended-class-method
  public static $classesObjects = []; // Objects of the main classes that run the app.
  // public $renderedTemplate; // Final rendered obOutput (rendered webpage).
  public static $locations = [
      'firebase'  =>  [ //  recieves configurations from Wordpress config files.
        'defaultBaseURL'  =>  FIREBASE_DEFAULT_URL,
        'defaultToken'  =>  FIREBASE_DEFAULT_TOKEN, // This is the token generated using Firebase 2 for unlimited time.
        'frontendToken' => FIREBASE_FRONTEND_TOKEN // This is the new token generated for max of 1 hour used in frontend because Firebase 3 doesn't support the unlimited Token generated by Firebase 2. As a work around.
      ]
  ]; // App locations - different paths & URLs.
  public static $settings = [
    'environment' => ENVIRONMENT, // Environment - production or development (use localhost or domain).
    'domain' => DOMAIN // domain of the site used for firebase data particular to the site.
  ];
  // const FIREBASE_DEFAULT_TOKEN = '6f1iIOoYc5pFnZ0vgly5DYYwWWX3BZ5a1KDSCBFK';

  public static $mainClasses = [  // $classes list with full name.
      "\\SZN\\TemplateSystem",
      "\\SZN\\_View\\ViewController",  // View class object of TemplateSystem Object.
      "\\SZN\\_Data\\DataController",   // Data class object of TemplateSystem Object.
      "\\SZN\\_File\\FilesController", // The object that will hold all file objects.
      "\\SZN\\WPExtend\\AdminArea",   // add admin area sections and endpoints for ajax calls.
      "\\SZN\\WPExtend\\AdminAjax"
  ];

  public function __construct() {
    // foreach($this->classes as $className)  // for extenders that are not static // For non static classes that needs initiation.
    //     $this->objects[] = new $className;  // creating all objects
    // [1]  Firebase
    self::$classesObjects['Firebase'] = new \Firebase\FirebaseLib(self::$locations['firebase']['defaultBaseURL'], self::$locations['firebase']['defaultToken']); // recienves information from wordpress config // Firebase Class
    // Location for Firebase
    if(self::$settings['environment'] == 'production') { // get firebase domain data tree particular to the domain.
        $firebaseDomainDataTree =  $_SERVER['HTTP_HOST']; // get domain from http_host.
    } elseif(self::$settings['environment'] == 'development')  {
        $firebaseDomainDataTree = self::$settings['domain'];
    }
    self::$locations['firebase']['defaultUrlPath']  = '/' . 'domains/'. str_replace(".", "", str_replace("www.", "", $firebaseDomainDataTree));// remove www if present to get the correct name for firebase and get domain.

    self::retrieveFiles(); // Get files lists.
    // [2]  Paths & URL
    self::defineSeverLocations();   //  Define plugin location & other depended locations.
    // [3]  Classes
    self::initiateClasses(self::$mainClasses);
    // [4]  Define & Apply Files.
    if(!is_admin() || is_admin()) { // load only on front end. As the hook for actions is not set for backend i.r. admin in this case.
      add_action ('plugins_loaded', function (){  // Define files that were retrieved from firebase.
        self::$classesObjects['FilesController']->defineFiles();
        self::$classesObjects['FilesController']->applyFilesToDocument();
      });
    }
    // [7]  Match routing
    // [6]  Load template document:
    add_filter( 'template_include', function() { // filter hook template_include allow to change the template being used.
      self::matchRoute(); // match route and set document.
      self::loadDocument(); // get document / view tree and load them.
      // return $newTemplateFile; // return new template. in this case there is no need for one.
      // $temp = tmpfile(); // create temporary file, returns handle.
      // fwrite($temp, "writing to tempfile");
      // $temp_file = tempnam(sys_get_temp_dir(), 'template');  // temporary file with unique name, returns path.
      // return $temp_file;
      return false;
    }, PHP_INT_MAX );


    if((APP_ENVIRONMENT == 'production')) {
    } elseif(APP_ENVIRONMENT == 'development') {
    }

    // self::$defaults['DocumentLayout'] = 'app-drawer-panel.layout.php'; // default settings.

    // [BACKWARD COMPATIBILITY]
    self::backwardCompatibilitySupport();

    // self::$printTests[] = getallheaders();
    // self::$printTests[] = $_REQUEST;
    // self::$printTests[] = $_SERVER;
    self::printTests();
  }

  public static function matchRoute() { // matches request to the related route using route conditions. and extracts the document key.

    self::$URL['segments'] = explode('/', trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/')); // get segments of url path (endpoint).
    self::$URL['path']  = $_SERVER[REQUEST_URI];

    // Route File Name
    // $currentFilename = basename('archive-mcq', '.php'); // basename returns trailing name component of path.
    // $currentFilename = 'index.php'/);
    // get route Settings
    self::$settings['routes'] = self::queryFirebase(['routes']);  // get all routes.
    self::sortMultidimensionalArrayByChild(self::$settings['routes']); // sort route.
    if(self::$settings['routes'] == null) {
      // get default document settigns.
      self::$settings['routes'] = self::queryFirebase(['routes', '_defaultRouteSettings']);
    }
    foreach (self::$settings['routes'] as $unitKey => $unitValue) { // loop through routes and check if conditions are met. if so set document and exit.
      if(self::$settings['routes'][$unitKey]['conditions']) {
        $conditionsMet = self::$classesObjects['FilesController']->checkConditions( self::$settings['routes'][$unitKey]['conditions'] );
        // var_dump($conditionsMet);
        if($conditionsMet) {
          self::$settings['document'] = self::$settings['routes'][$unitKey]['document'];
          break;
        }
      } else {
          self::$settings['document'] = self::$settings['routes'][$unitKey]['document'];
      }
    }
    // var_dump(self::$classesObjects['FilesController']->conditionsObjects);
    // var_dump(self::$settings['document'] );
    // get_post_type();

    $route = str_replace("/", "", $_SERVER['REQUEST_URI']);

    if( str_replace(".", "", str_replace("www.", "", $_SERVER['HTTP_HOST'])) == 'gazitengcom') {
      self::$settings['document'] = self::$settings['routes']['_defaultRouteSettings']['document'];
    } else {
      if(isset(self::$settings['document'])) {

      } else {
          self::$settings['document'] = self::$settings['routes']['single-examination']['document'];
      }
    }
  }

  public static function loadDocument() { // loads the templates of the document tree.
    $viewControllerObject = self::$classesObjects['ViewController']; // for calling static functions or variables.
    self::$classesObjects['ViewController']->defineUnits(self::$settings['files']['templates'], 'Template');
    self::$classesObjects['ViewController']->defineUnits(self::queryFirebase(['views']), 'View');

    // get route document view trees.
    self::$settings['document'] = self::queryFirebase(['documents', self::$settings['document']]);
    // ORDER BY 'order' filed $routeSettings['document'] = self::sortMultidimensionalArrayByChild($routeSettings['document']); // reorder

    $viewControllerObject = self::$classesObjects['ViewController']; // for calling static functions or variables.
    self::$classesObjects['ViewController']->defineUnits(self::$settings['document']['viewTrees'], 'ViewTree');

      // Load Main Template that includes the header and footer and content.
      get_header('start');
      get_header('end');
      self::$classesObjects['ViewController']->applyRenderFunctionOnUnits(self::$settings['document']['toplevelViewTrees'], 'ViewTree'); // Start rendering top level views and propogate.
      get_footer('end');

  }

  public static function retrieveFiles() { // FIREBASE SETTINGS RETRIEVE
    self::$settings['files'] = [  // Files List
      'conditions' => self::queryFirebase(['files', 'conditions']),
      'codeblocks' => self::queryFirebase(['files', 'codeblocks']),
      'elements' => self::queryFirebase(['files', 'elements']),
      'stylesheets' => self::queryFirebase(['files', 'stylesheets']),
      'javascripts' => self::queryFirebase(['files', 'javascripts']),
      'templates' => self::queryFirebase(['files', 'templates'])
    ];
    foreach (self::$settings['files'] as $unitKey => $unitValue) {  // Manipulate array - Sort by "order" child.
      self::sortMultidimensionalArrayByChild(self::$settings['files'][$unitKey]); // changes array (i.e. passed by refrence)
    }
  }

  public static function defineSeverLocations() { // define the locations of the files different types in the server.
    self::$settings['locations'] = self::queryFirebase(['settings', 'locations']);  // get locations from firebase.
    while(!isset(self::$settings['locations'])) {
      self::$settings['locations'] = self::queryFirebase(['settings', 'locations']);  // get locations from firebase.
    }

    self::$locations['app'] = [ // all locations are related to document root in the server.
      'path' => self::join_paths(BASE_PATH, self::$settings['locations']['app']),
      'url' => self::join_paths(BASE_URL, self::$settings['locations']['app'])
    ];
    self::$locations['phpfiles'] = [
      'path' => self::join_paths(self::$locations['app']['path'], self::$settings['locations']['phpfiles']),
      'url' => self::join_paths(self::$locations['app']['url'], self::$settings['locations']['phpfiles'])
    ];
    self::$locations['bowerComponents'] = [
      'path' => self::join_paths(self::$locations['app']['path'], self::$settings['locations']['bowerComponents']),
      'url' => self::join_paths(self::$locations['app']['url'], self::$settings['locations']['bowerComponents'])
    ];
    self::$locations['queries'] = [
      'path' => self::join_paths(self::$locations['phpfiles']['path'], self::$settings['locations']['queries'])
    ];
    self::$locations['templates'] = [
      'path' => self::join_paths(self::$locations['phpfiles']['path'], self::$settings['locations']['templates'])
    ];
  }

  public static function initiateClasses($namespacedClasses)  { //  Create main objects for the classes and save them.
    foreach ($namespacedClasses as $unitKey => $unitValue) {  //  Initialize Classes.
      $className = (new \ReflectionClass($unitValue))->getShortName();  // gets class name without the namespace.
      self::$classesObjects[$className] = new $unitValue();  // initiate classes and save objects.
    }
  }

  // BACKWARD COMPATIBILITY property names.
  public static $templateObject; public static $dataControllerObject; public static $viewControllerObject; public static $filesControllerObject; public static $plugin_directory_path; public static $plugin_directory_url; public static $bower_components_directory_path; public static $bower_components_directory_url; public static $web_components_directory_path; public static $web_components_directory_url; public static $queries_directory_path; public static $views_directory_path; public static $scripts_directory_path; public static $scripts_directory_url; public static $appPath; public static $appURL; public static $templatesPath; public static $templatesURL; public static $firebase = []; public static $defaults;
  public static function backwardCompatibilitySupport() { // add support for older files that still use some old function or property names.

    //  BACKWARD COMPATIBILITY naming. This should be deleted after all naming changed.
    self::$dataControllerObject = self::$classesObjects['DataController'];
    self::$viewControllerObject = self::$classesObjects['ViewController'];
    self::$filesControllerObject = self::$classesObjects['FilesController'];
    self::$firebase['class'] = self::$classesObjects['Firebase'];
    self::$templateObject = self::$classesObjects['TemplateSystem'];  //  TemplateSystem object.

    // BACKWARD COMPATIBILITY naming. delete after changing all old names.
    self::$appPath = self::$locations['app']['path'];
    self::$appURL = self::$locations['app']['url'];
    self::$templatesPath = self::$locations['phpfiles']['path'];
    self::$templatesURL = self::$locations['phpfiles']['url'];
    self::$plugin_directory_path = self::$locations['plugin']['path'];
    self::$plugin_directory_url = self::$locations['plugin']['url'];
    self::$queries_directory_path = self::$locations['queries']['path'];
    self::$views_directory_path = self::$locations['templates']['path'];
    self::$bower_components_directory_path = self::$locations['bowerComponents']['path'];
    self::$bower_components_directory_url = self::$locations['bowerComponents']['url'];
    self::$firebase['defaultPath'] = self::$locations['firebase']['defaultUrlPath'];

    // CHANGE THESE NAMES - didn't make sure they exists in other place
    // self::$web_components_directory_path = WPMU_PLUGIN_DIR . '/' . 'SZN_web_components';
    // self::$web_components_directory_url = WPMU_PLUGIN_URL . '/' . 'SZN_web_components';
    // self::$scripts_directory_path = WPMU_PLUGIN_DIR . '/' . 'SZN_scripts_styles';
    // self::$scripts_directory_url = WPMU_PLUGIN_URL . '/' . 'SZN_scripts_styles';
  }

}
