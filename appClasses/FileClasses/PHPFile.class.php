<?php
namespace SZN\_File; // should be on top.

class PHPFile extends File {
  public function __construct($fileArgs) {
    parent::__construct($fileArgs);
    $this->filePath['directoryPath'] = $this->getFilePathFromSource('directoryPath');
    $this->filePath['urlPath'] = $this->getFilePathFromSource('urlPath');
  }
  public function applyToDocument() {
    if($this->filePositionInPage == 'admin_footer') {
      $this->applyToDocumentWrapWithClass();
    } else {
      $this->includeCodeblock();
    }
  }
  public function applyToDocumentWrapWithClass() {
    echo '<div class="adminDashboardScripts">';
    self::includeFileWithArgs(
      $this->filePath['directoryPath'],
      $this->parameters
    );
    echo '</div>';
  }
  public function includeCodeblock() {
    self::includeFileWithArgs(
      $this->filePath['directoryPath'],
      $this->parameters
    );
  }
}
