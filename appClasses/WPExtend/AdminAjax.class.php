<?php

 namespace SZN\WPExtend; // should be ontop.

 if ( ! defined( 'ABSPATH' ) ) { exit; } // security measure

// adds Wordpress Ajax calls to the admin endpoints.
class AdminAjax extends AdminArea { // initiated by AdminArea class files.

  public function __construct() { // ALL FUNCTION ENDPOINTS MUST START WITH 'szn_'
    /*
    * Request of /wp-admin/admin-ajax.php?action=wp_ajax_szn_ajaxCheckTemplateFiles will return 0 if succeeded.
    * ajaxurl - javascript variable on frontend. a wordpress parameter that exists inside admin area
    * JSON - because the method used is Json. the string should be json encoded and then echoed to the function. will appear as data.detail.response
    */
    $endpointFunctions = self::getMethodsPrifexWithString(__CLASS__, 'szn_'); // get Prefixed Endpoint Function Names
    $this->addFunctionsAsAdminEndpoints($endpointFunctions);
  }

  public function addFunctionsAsAdminEndpoints($units) {  // add functions with 'szn_' prefix to wordpress admin ajax endpoints.
    // the custom wordpress ajax admin endpoint starts with szn_ as well as all the functions used as endpoints.
    foreach ($units as $unitName) {
      add_action( 'wp_ajax_'.$unitName, array ( $this, $unitName ) ); // add actions to wp_ajax_ as documentation specifies.
    }
  }

  /**
  * Ajax request on admin area.
  *
  */
  public function szn_ajaxCreateFile() 	{

    // $file = \SZN\UtilityFunctions::join_paths( self::$appPath, 'styles', 'test1.ini' );
    $file = $_REQUEST['file'];

    $fh = fopen($file, "w");
    if (!is_resource($fh)) {
        echo 'false';
        wp_die(); // this is required to terminate immediately and return a proper response
    }
    foreach ($config as $key => $value) {
        fwrite($fh, sprintf("%s = %s\n", $key, $value));
    }
    fclose($fh);
    chmod($file, 0775); // chages permissions.
    // unlink($file); // deletes a file.


    echo true;

    wp_die(); // this is required to terminate immediately and return a proper response
  }

  public function szn_ajaxCheckTemplateFiles() 	{
        $templates = [];
        $files = $this->getFilesList(self::$views_directory_path);
        foreach ($files as $file) {
          $matches = $this->checkTemplateInsertionPositions($file);
          $templates[] = [
            'insertionPositions'  =>  $matches,
            'templateFile'  =>  $file
          ];
        }
        header('Content-Type: application/json');
        echo json_encode($templates);
        wp_die(); // this is required to terminate immediately and return a proper response
  }

  public function getFilesList($directoryPath) { // get list of files in path.
    return array_diff(scandir($directoryPath), array('.', '..'));
  }

  public function checkTemplateInsertionPositions($file) {
    $pattern = "/^.+?App::insert[\n\s]*\([\n\s]*.+?\[[\n\s]*[\'\"](.+?)[\'\"][\n\s]*.+?\).+?$/is";

    $fh = fopen(self::join_paths(self::$views_directory_path, $file), 'r') or die($php_errormsg);
    while (!feof($fh)) {
      $line = fgets($fh);
      // preg_match($pattern , $line, $matches);
      // echo trim($match[1]);
      if (preg_match($pattern, $line, $match)) {
        $matches[] = $match[1];
      }
    }
    fclose($fh);

    return $matches;
  }

}
