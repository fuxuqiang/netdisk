<?php
require '../MySQLPDO.class.php';
session_start();
if(isset($_SERVER['PATH_INFO']) && $_SERVER['PATH_INFO']!='/'){
	$params = explode('/', $_SERVER['PATH_INFO']);
	require $params[1].'.php';
	$params[2]();
}else{
	if(isset($_COOKIE['name']) && isset($_COOKIE['pwd'])){
		$name = $_COOKIE['name'];
		$pwd = $_COOKIE['pwd'];
		$db = MySQLPDO::getInstance(array('dbname'=>'test'));
		if($pwd==$db->query("select `pwd` from `user` where `name`='$name'")->fetch()[0]){
			$_SESSION['name'] = $name;
		}
	}
	if(isset($_SESSION['name'])){
		require 'index.html';
	}else{
		echo '<a href="/netdisk/index.php/user/login">login</a><br>';
		echo '<a href="/netdisk/index.php/user/register">register</a>';
	}
}
?>