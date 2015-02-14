<?php
include 'src/filesystem.php';

$fs = new Filesystem();

// print_r(glob('directory/**/target/*.css'));
print_r($fs->scan('directory/**/*.css'));