<?php
//http://stackoverflow.com/questions/6411656/how-to-create-php-files-using-php

function writeConfig( $filename, $config ) {
    $fh = fopen($filename, "w");
    if (!is_resource($fh)) {
        return false;
    }
    foreach ($config as $key => $value) {
        fwrite($fh, sprintf("%s = %s\n", $key, $value));
    }
    fclose($fh);

    return true;
}

function readConfig( $filename ) {
    return parse_ini_file($filename, false, INI_SCANNER_NORMAL);
}

function checkPermissions($resource) {
  $perms = fileperms($resource);

  if (($perms & 0xC000) == 0xC000) {
      // Socket
      $info = 's';
  } elseif (($perms & 0xA000) == 0xA000) {
      // Symbolic Link
      $info = 'l';
  } elseif (($perms & 0x8000) == 0x8000) {
      // Regular
      $info = '-';
  } elseif (($perms & 0x6000) == 0x6000) {
      // Block special
      $info = 'b';
  } elseif (($perms & 0x4000) == 0x4000) {
      // Directory
      $info = 'd';
  } elseif (($perms & 0x2000) == 0x2000) {
      // Character special
      $info = 'c';
  } elseif (($perms & 0x1000) == 0x1000) {
      // FIFO pipe
      $info = 'p';
  } else {
      // Unknown
      $info = 'u';
  }

  // Owner
  $info .= (($perms & 0x0100) ? 'r' : '-');
  $info .= (($perms & 0x0080) ? 'w' : '-');
  $info .= (($perms & 0x0040) ?
              (($perms & 0x0800) ? 's' : 'x' ) :
              (($perms & 0x0800) ? 'S' : '-'));

  // Group
  $info .= (($perms & 0x0020) ? 'r' : '-');
  $info .= (($perms & 0x0010) ? 'w' : '-');
  $info .= (($perms & 0x0008) ?
              (($perms & 0x0400) ? 's' : 'x' ) :
              (($perms & 0x0400) ? 'S' : '-'));

  // World
  $info .= (($perms & 0x0004) ? 'r' : '-');
  $info .= (($perms & 0x0002) ? 'w' : '-');
  $info .= (($perms & 0x0001) ?
              (($perms & 0x0200) ? 't' : 'x' ) :
              (($perms & 0x0200) ? 'T' : '-'));

  return $info;

}

function getFilePermission($file) {
        // $length = strlen(decoct(fileperms($file)))-3;
        // return substr(decoct(fileperms($file)),$length);

        return decoct(fileperms($file) & 0777);
}

$config = array(
    "database" => "test",
    "user"     => "testUser"
);

$file = dirname(__FILE__)."/appDashboard.php";
$file = \SZN\UtilityFunctions::join_paths( \TemplateSystem::$appPath, 'styles', 'test.ini' );

var_dump(writeConfig($file, $config));
// var_dump(readConfig("test.ini"));
// echo 'exists '; var_dump(file_exists($file));
// echo '<br>';
// echo 'isWritable '; var_dump(is_writable($file));
// echo '<br>';
// echo 'File ' . dirname(__FILE__);
// echo '<br>';
// echo 'Permission ' . var_dump(checkPermissions($file));
// echo '<br>';
// echo 'PermissionNum ' . var_dump(getFilePermission($file));


?>
