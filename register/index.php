<?php
require 'register.php';
switch ($_GET['a']) {
	case 'captcha':
		captcha();
		break;
	case 'verify':
		session_start();
		if(strtolower($_POST['captcha'])==$_SESSION['captcha']) echo 1;
		else echo 0;
		break;
	case 'checkName':
		checkName();
		break;
	default:
		register();
		break;
}
//error_reporting('E_ALL');
?>