<?php
require_once('../function.php');
// 先处理单条删除逻辑
function delete_categories (){
	if(empty($_GET['id'])){
		
		exit('<h1>404_Not Found</h1>');
	}
	$id = $_GET['id'];
	$affect = xk_query_execute("delete from categories where id in ({$id}) ;");

	header('location: /admin/categories.php');
}
delete_categories();