<?php
define('WEB', true);
define( 'APP', 'api' );
require_once "common.php";

$mod  = trim($_REQUEST['m']) ? trim($_REQUEST['m']) : 'index';
$page = trim($_REQUEST['p']) ? trim($_REQUEST['p']) : 'index';
$file =  APP_PATH. "{$mod}". DS ."{$page}".'.php';
if( !is_file($file) ){
    exit($file.' file is not exists...');
}
require_once( $file );