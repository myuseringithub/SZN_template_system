<?php
namespace SZN\_File; // should be on top.

class CSSFile extends File {
  public function __construct($fileArgs) {
    parent::__construct($fileArgs);
    $this->filePath['directoryPath'] = $this->getFilePathFromSource('directoryPath');
    $this->filePath['urlPath'] = $this->getFilePathFromSource('urlPath');
  }
  public function applyToDocument() {
    $this->enqueueScript();
  }
  public function enqueueScript() {
    wp_enqueue_style( $this->name , $this->filePath['urlPath'], false, '' );
    // Add is="custom-style"
    // http://wordpress.stackexchange.com/questions/34754/add-title-attribute-to-stylesheets-with-wp-enqueue-style
    // http://wordpress.stackexchange.com/questions/51581/when-enqueing-a-stylesheet-is-it-possible-to-remove-the-type-attribute
    // http://stackoverflow.com/questions/28158492/add-attribute-to-link-tag-thats-generated-through-wp-register-style

  }
}
