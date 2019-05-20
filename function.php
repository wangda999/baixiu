<?php

/**
 *
 * 全局通用函数
 * 为了避免与php内置函数命名冲突 最好加前缀
 */
// 开启session功能
session_start();


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