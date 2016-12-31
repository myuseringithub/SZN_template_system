<?php
namespace SZN\_File; // should be on top.

class JSFile extends File {
  public function __construct($fileArgs) {
    parent::__construct($fileArgs);
    $this->filePath['directoryPath'] = $this->getFilePathFromSource('directoryPath');
    $this->filePath['urlPath'] = $this->getFilePathFromSource('urlPath');
  }
  public function applyToDocument() {
    $this->enqueueScript();
  }
  public function enqueueScript() {
    $is_inFooter = ($this->filePositionInPage == 'admin_footer' || $this->filePositionInPage == 'wp_footer') ? true : false;
    wp_enqueue_script( $this->name , $this->filePath['urlPath'], null, null, $is_inFooter);
  }
}
