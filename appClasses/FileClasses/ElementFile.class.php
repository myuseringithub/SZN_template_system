<?php 
namespace SZN\_File; // should be on top.

class ElementFile extends File {
  public function __construct($fileArgs) {
    parent::__construct($fileArgs);
    $this->filePath['directoryPath'] = $this->getFilePathFromSource('directoryPath');
    $this->filePath['urlPath'] = $this->getFilePathFromSource('urlPath');
  }

  public function applyToDocument() {
    switch ($this->type) : // Type got overridden by type extension.
      case "linkImport":
        echo '<link rel="import" href="' . $this->filePath['urlPath'] . '">';
      break;
      case "script":
        echo '<script src="' . $this->filePath['urlPath'] . '"></script>';
      case null:
      case '':
      break;
      default:
        echo '<link rel="import" href="' . $this->filePath['urlPath'] . '">';
    endswitch;
  }
}
