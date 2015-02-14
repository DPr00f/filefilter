<?php
include 'src/Filefilter.php';

$fs = new Filefilter();

// print_r(glob('directory/**/target/*.css'));
print_r($fs->scan('directory/**/*.js'));