<?php

/**
 * 处理用户头像问题
 * 输入 用户账号
 * 输出 头像路径
 */
require_once('../../config.php');

if (empty($_GET['email'])) {
  
  exit('');
}
$user_email = $_GET['email'];
$link = mysqli_connect(ZXK_DB_HOST, ZXK_DB_USER, ZXK_DB_PASS, ZXK_DB_NAME); // => boolean

if(!$link){
	
	exit('');
}
$user = mysqli_query($link, "select avatar from users where email = '{$user_email}' limit 1;
");
if(empty($user)) {
	exit('');
}
$row = mysqli_fetch_assoc($user);
echo $row['avatar'];