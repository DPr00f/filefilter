<?php

class Filefilter {
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

  private function _scan($directory, $recursive = false, $filter = false, $extension = '', $matchedFolder = false) {
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
        }else if ($filter and !$matchedFolder){
          $split = explode('[\w\-\. ]+?', $filter);
          array_pop($split);
          $folderFilter = rtrim(implode('/', $split), '\/');
          if(preg_match('/'.$folderFilter.'/', $name)){
            $files = array_merge($this->_scan($name, $recursive, $filter, $extension, true), $files);
          }
        }
        continue;
      }
      if($this->returnBasePath){
        $name = $this->basepath . '/' . $name;
      }
      if($filter){
        if(preg_match('/'.$filter.'/', $name)){
          $files[] = $name;
        }
        continue;
      }
      $files[] = $name;
    }

    return $files;
  }

  private function getFilter($directory) {
    $directory = str_replace('**/*', '{TOGETHERREPLACE}', $directory);
    $split = explode('/', $directory);
    $last = array_pop($split);
    if(strpos($last, '*') !== FALSE and strpos($last, '**') === FALSE) {
      $last = str_replace('*', '[\w\-. ]+?', $last);
    }
    $directory = implode('/', $split) . '/' . $last;
    $filter = str_replace(
      ['/', '.', "{TOGETHERREPLACE}" ,'**', '*'],
      ['\/','\.', '.+?', '.+', '.+?'], 
      $directory
    );
    return $filter;
  }

  public function scan($directory, $recursive = false) {
    $extension = '';
    $filter = false;
    $directory = rtrim($directory, '/');
    $originalDirectory = $directory;
    $split = explode('/', $directory);

    if(strpos(end($split), '*') !== FALSE and strpos(end($split), '**') === FALSE) {
      $filter = $this->getFilter($originalDirectory);
      $last = array_pop($split);
      $extensions = explode('*', $last);
      $extension = end($extensions);
      $directory = rtrim(implode('/', $split), '/');
    }

    if(strpos($directory, '**') !== FALSE) {
      $recursive = true;
      if(!$filter){
        $filter = $this->getFilter($originalDirectory);
      }
      $split = explode('**', $directory);
      $directory = rtrim($split[0], '/');
    }

    if(strpos($directory, '*') !== FALSE) {
      if(!$filter){
        $filter = $this->getFilter($originalDirectory);
      }
      $split = explode('*', $directory);
      $directory = rtrim($split[0], '/');
    }
    
    return $this->_scan($directory, $recursive, $filter, $extension);
  }
}