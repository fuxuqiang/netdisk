<?php
require 'MySQLPDO.class.php';
session_start();
if(isset($_SERVER['PATH_INFO']) && $_SERVER['PATH_INFO']!='/'){
	$params = explode('/', $_SERVER['PATH_INFO']);
	require 'application/'.$params[1].'.php';
	$params[2]();
}else{
	require 'application/index.php';
	index();
}