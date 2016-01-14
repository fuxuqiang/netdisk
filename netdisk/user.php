<?php
function register(){
	if(!empty($_POST)){
		$data['name'] = $_POST['name'];
		$data['pwd'] = $_POST['pwd'];
		$data['birth'] = $_POST['birth'];
		$data['email'] = $_POST['email'];
		$db = MySQLPDO::getInstance(array('dbname'=>'test'));
		if($db->data($data)->query('insert into `user` (`name`,`pwd`,`email`,`birth`) values (:name,:pwd,:email,:birth)')){
			echo '<script>alert("register succeed");location.href="/netdisk/index.php/user/login"</script>';
		}
	}else require 'register.html';
}

function login(){
	if(!empty($_POST)){
		if($_POST['captcha']==$_SESSION['captcha']){
			$name = $_POST['name'];
			$db = MySQLPDO::getInstance(array('dbname'=>'test'));
			$row = $db->query("select `pwd` from `user` where `name`='$name'")->fetch();
			if($row){
				$pwd = $_POST['pwd'];
				if($pwd==$row['pwd']){
					if(isset($_POST['autologin']) && $_POST['autologin']=='on'){
						setcookie('name',$name,time()+3600*24*3,'/netdisk/');
						setcookie('pwd',$pwd,time()+3600*24*3,'/netdisk/');
					}
					$_SESSION['name'] = $name;
					echo 4;
				}
				else echo 3;
			}else echo 2;
		}else echo 1;
	}else require 'login.html';	
}

function logout(){
	session_destroy();
	setcookie('name','',time()-1,'/netdisk/');
	setcookie('pwd','',time()-1,'/netdisk/');
	header('location: /netdisk/index.php');
}

function verify(){
	if(strtolower($_POST['captcha'])==$_SESSION['captcha']) echo 1;
	else echo 0;
}

function checkName(){
	$name = $_POST['name'];
	$db = MySQLPDO::getInstance(array('dbname'=>'test'));
	if($db->query("select * from `user` where `name`='$name'")->fetch()) echo 0;
	else echo 1;
}

function captcha(){
	$img_w = 75;
	$img_h = 25;
	$char_len = 4;
	$font = 5;

	$char = array_merge(range('A','Z'), range('a','z'), range(1,9));
	$rand_keys = array_rand($char, $char_len);
	$code = '';
	foreach($rand_keys as $v){
		$code.=$char[$v];
	}
	$_SESSION['captcha'] = strtolower($code);

	$img = imagecreatetruecolor($img_w, $img_h);
	$str_color = imagecolorallocate($img, mt_rand(0,255), mt_rand(0,255), mt_rand(0,255));
	$font_w = imagefontwidth($font);
	$font_h = imagefontheight($font);
	$str_w = $font_w*$char_len;
	imagestring($img, $font, ($img_w-$str_w)/2, ($img_h-$font_h)/2, $code, $str_color);

	header('content-type:image/png');
	imagepng($img);
	imagedestroy($img);
}
?>