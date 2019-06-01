<?php
session_start();
/**
 *
 * 全局通用函数
 * 为了避免与php内置函数命名冲突 最好加前缀
 */
// 开启session功能
// 引入配置文件
require_once('config.php');

/**
 * 获取当前用户信息
 * para 无
 * return 用户信息
 */

function xk_get_current_user () {
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
 * @return [type] [返回要查找的数据]关联数组
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

/**
 * 数据库增删改语句
 * @param   $['sql'] [<SQL语句>]
 * @return   [<成功返回受影响函数 失败返回false>]
 */

function xk_query_execute ($sql) {
	$connect = mysqli_connect(ZXK_DB_HOST, ZXK_DB_USER, ZXK_DB_PASS,ZXK_DB_NAME);

	if(!$connect){
		exit('<h1>连接数据库失败</h1>');
	}
	mysqli_set_charset($connect, 'utf8');
	$qurey = mysqli_query($connect, $sql);

	if(!$qurey){
		// 没有查询操作就返回 false
		return false;
	}

	// 看业务需求要不要判断受影响行数
	$affected_rows = mysqli_affected_rows($connect);

	// 关闭连接
	mysqli_close($connect);
	return $affected_rows;
}