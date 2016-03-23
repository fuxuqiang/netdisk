<?php
function index(){
	$db = MySQLPDO::getInstance();
	if(isset($_COOKIE['name']) && isset($_COOKIE['pwd'])){
		$name = $_COOKIE['name'];
		$pwd = $_COOKIE['pwd'];
		$row = $db->fetch("select `id`,`pwd` from `user` where `name`='$name'");
		if($pwd==$row['pwd']) {
			$_SESSION['name'] = $name;
			$_SESSION['id'] = $row['id'];
		}
	}
	if(isset($_SESSION['id'])){
		$user_id = $_SESSION['id'];
		$folder_id = isset($_GET['folder'])? $_GET['folder']:0;
		if($folder_id!=0){
			$sql = "select `folder_name`,`folder_path` from `folder` where `folder_id`=$folder_id and `user_id`=$user_id";
			$current_folder = $db->fetch($sql);
			$file_ids = $current_folder['folder_path'];
			$sql = "select `folder_id`,`folder_name` from `folder` where `folder_id` in ($file_ids) and `user_id`=$user_id";
			$path = $db->fetchAll($sql);
			$path[] = array('folder_id'=>$folder_id,'folder_name'=>$current_folder['folder_name']);
		}
		if(empty($_POST)){
			$folder = $db->fetchAll("select * from `folder` where `user_id`=$user_id and `folder_pid`=$folder_id");
			$file = $db->fetchAll("select * from `file` where `user_id`=$user_id and `folder_id`=$folder_id");
			require 'index.html';
		}else{
			if(!empty($_POST['newdir'])){
				$newdir = $_POST['newdir'];
				$sql = "select * from `folder` where `folder_pid`=$folder_id and `folder_name`='$newdir' and `user_id`=$user_id";
				if($db->fetch($sql)) echo 1;
				else{
					$sql = "select `folder_path` from `folder` where `folder_id`=$folder_id and `user_id`=$user_id";
					if($db->fetch($sql)){
						$parent_path = $db->fetch($sql)[0];
						$parent_path = "$parent_path,$folder_id";
					}else $parent_path = 0;
					$sql = "insert into `folder` (`user_id`,`folder_name`,`folder_time`,`folder_path`,`folder_pid`) values (?,?,now(),?,?)";
					if($db->data(array($user_id,$newdir,$parent_path,$folder_id))->query($sql)!=false) echo 2;
				}
			}
			if(!empty($_POST['del_id'])){
				$del_id = $_POST['del_id'];
				$sql = "delete from `folder` where `user_id`=$user_id and `folder_id`=$del_id";
				if($db->query($sql)!=false) echo 1;
			}
		}
	}else{
		echo '<a href="/netdisk/index.php/user/login">登陆</a><br>';
		echo '<a href="/netdisk/index.php/user/register">注册</a>';
	}
}
?>