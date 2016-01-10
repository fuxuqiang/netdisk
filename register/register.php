<?php
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
	session_start();
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

function checkName(){
	$name = $_POST['name'];
	require '../MySQLPDO.class.php';
	$ln = MySQLPDO::getInstance(array('dbname'=>'test'));
	try{
		$stmt = $ln->db->query("select * from `user` where `name`='$name'");
		if($stmt->fetch()) echo 0;
		else echo 1;
	}catch(PDOException $e){
		echo $e->getMessage();
	}
}

function register(){
	if(!empty($_POST)){
		require '../MySQLPDO.class.php';
		$data['name'] = $_POST['name'];
		$data['pwd'] = $_POST['pwd'];
		$data['birth'] = $_POST['birth'];
		$data['email'] = $_POST['email'];
		$ln = MySQLPDO::getInstance(array('dbname'=>'test'));
		try{
			$stmt = $ln->db->prepare('insert into `user` (`name`,`pwd`,`email`,`birth`) values (:name,:pwd,:email,:birth)');
			if($stmt->execute($data)){
				echo '<script>alert("register succeed");</script>';
			}
		}catch(PDOException $e){
			echo $e->getMessage();
		}
	}
	require 'register.html';
}
?>