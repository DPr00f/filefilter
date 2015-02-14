<?php

class Filesystem {
  protected $basepath;
  protected $returnBasePath;

  function __construct($basepath = null, $returnBasePath = false) {
    $this->basepath       = $basepath ? $basepath : dirname(__DIR__);
    $this->basepath = rtrim($this->basepath, '/');
    $this->returnBasePath = $returnBasePath;
  }

  public function returnPath($bool = null) {
    if(is_null($bool)) { return $this->returnBasePath; }
    $this->returnBasePath = $bool;
  }

  private function _scan($directory, $recursive = false, $filter = false, $extension = '') {
    $files = [];
    $dir = scandir($directory); 

    foreach ($dir as $el) {
      if($el === '.' or $el ==='..'){
        continue;
      }
      $name = $directory . '/' . $el;
      if(is_dir($name)){
        if($recursive){
          $files = array_merge($this->_scan($name, $recursive, $filter, $extension), $files);
        }
        continue;
      }
      if($this->returnBasePath){
        $name = $this->basepath . '/' . $name;
      }
      if($filter){
        if($this->endsWith($name, $extension)){
          $files[] = $name;
        }
        continue;
      }
      $files[] = $name;
    }

    return $files;
  }

  public function scan($directory, $recursive = false) {
    $extension = '';
    $filter = false;
    $directory = rtrim($directory, '/');
    $originalDirectory = $directory;
    $split = explode('/', $directory);

    if(strpos(end($split), '*') !== FALSE) {
      $filter = true;
      $last = array_pop($split);
      $extensions = explode('*', $last);
      $extension = end($extensions);
      $directory = rtrim(implode('/', $split), '/');
    }
    if(strpos($directory, '**') !== FALSE) {
      $recursive = true;
      $split = explode('**', $directory);
      $directory = rtrim($split[0], '/');
    }
      
    return $this->_scan($directory, $recursive, $filter, $extension);
  }

  private function endsWith($haystack,$needle,$case=true)
  {
    if($needle === ''){
      return true;
    }
    $expectedPosition = strlen($haystack) - strlen($needle);

    if ($case){
      return strrpos($haystack, $needle, 0) === $expectedPosition;
    }

    return strripos($haystack, $needle, 0) === $expectedPosition;
  }
}