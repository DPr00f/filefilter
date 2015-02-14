<?php
include 'Jocolopes/Filefilter.php';

$fs = new Jocolopes\Filefilter();

print_r($fs->scan('directory/**/target/*.js'));
print_r($fs->scan('directory/**/target/**/*.js'));
print_r($fs->scan('directory/**/*.js'));
print_r($fs->scan('directory/**/example/**/*.js'));