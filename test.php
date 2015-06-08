<?php
/**
 * Created by PhpStorm.
 * User: creace
 * Date: 6/7/2015
 * Time: 9:05 PM
 */

ini_set('display_errors', true);
error_reporting(E_ALL);

$pdo = new PDO('mysql:host=localhost;dbname=images', 'root', '');
require_once dirname(__FILE__).'/ImageFile.php';
$imgFile = new ImageFile($pdo);
$result = $imgFile->createTable();
echo "Create db table: ".var_export($result, true).PHP_EOL;
