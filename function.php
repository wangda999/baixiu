<?php

/**
 *
 * 全局通用函数
 * 为了避免与php内置函数命名冲突 最好加前缀
 */
// 开启session功能
session_start();

// 引入配置文件
require_once('config.php');
/**
 * 获取当前用户信息
 * para 无
 * return 用户信息
 */

function xk_get_current_user(){
	if (empty($_SESSION['current_login_user'])) {
		// 无法获取信息返回登录页
		header('Location: /admin/login.php');
		exit();
	}

	return $_SESSION['current_login_user'];
}

/**
 * 查询操作SQL
 * @param SQL查询语句 ''
 * @return [type] [返回要查找的数据]
 */

function xk_fetch_query_all($sql) {
	$data = null;
	$link = mysqli_connect(ZXK_DB_HOST, ZXK_DB_USER, ZXK_DB_PASS,ZXK_DB_NAME );
	if (!$link) {
		exit('?');
	}
	mysqli_set_charset($link, 'utf8');
	
	$serach = mysqli_query($link, $sql);
	if (!$serach) {
		exit('?');
		return false;
	}

	while ($row = mysqli_fetch_assoc($serach)){
		$data[] = $row;
	}

	// 释放缓存区
	mysqli_free_result($serach);
	// 断开连接通道
	mysqli_close($link);

	return $data;
}
function xk_fetch_query_one($sql){
	return xk_fetch_query_all($sql)[0];
}