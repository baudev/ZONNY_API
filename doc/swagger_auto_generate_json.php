<?php
/**
 * Generate automatically the swagger documentation in the swagger.json file
 */
require( dirname(__FILE__)."/../vendor/autoload.php");
$swagger = \Swagger\scan( dirname(__FILE__)."/../", array("exclude" => dirname(__FILE__)."/../vendor/"));
$file = 'swagger.json';
file_put_contents($file, $swagger);