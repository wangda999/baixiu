<?php
require_once('../config.php'); // 由于require和include不能添加绝对路径 所以用相对路径或者物理路径
session_start();
function login(){
  if (empty($_POST['email'])){
    $GLOBALS['erro_message'] = '请输入账号邮箱';
    return;
  }
  if (empty($_POST['password'])){
    $GLOBALS['erro_message'] = '请输入密码';
    return;
  }
  $email = $_POST['email'];
  $password = $_POST['password'];

  // 连接数据库
  $connet = mysqli_connect(ZXK_DB_HOST, ZXK_DB_USER, ZXK_DB_PASS, ZXK_DB_NAME);

  if (!$connet) {
    $GLOBALS['erro_message'] = '请检查网络问题';
    return;
  }
  $str = "select * from users where email = '{$email}' limit 1;";
  $query = mysqli_query($connet, $str);
  if (!$query) {
    $GLOBALS['erro_message'] = '无此用户名信息';
    return;
  }
  
  // 从数据库中获取到用户信息
  $user = mysqli_fetch_assoc($query);
  if(!$user) {
    $GLOBALS['erro_message'] = '无此用户名信息';
    return;
  }
  // 注意密码一般是加密存储的 md5
  if($user['password'] !== $password){
    $GLOBALS['erro_message'] = '账号密码不匹配';
    return;
  }
  $_SESSION['current_login_user'] = $user;
  // 响应化
   header('location: index.php');
   exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST'){
  login();
}

if($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] == 'logout') {
  unset($_SESSION['current_login_user']);
}
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Sign in &laquo; Admin</title>
  <link rel="stylesheet" href="/static/assets/vendors/bootstrap/css/bootstrap.css">
  <link rel="stylesheet" href="/static/assets/css/admin.css">
  <link rel="stylesheet" href="/static/assets/vendors/animate/animate.css">
</head>
<body>
  <div class="login">
    <!-- novalidate 取消html5自动校验 -->
    <form class="login-wrap<?php echo isset($erro_message) ? '  shake animated' : '' ?>" accept="<?php  echo $_SERVER['PHP_SELF'] ?>" method="post" novalidate autocomplete="off">
      <img class="avatar" src="/static/assets/img/default.png">
      <!-- 有错误信息时展示 -->
      <?php if (isset($erro_message)): ?>
        <div class="alert alert-danger">
        <strong>错误！</strong> <?php echo $erro_message; ?>
      </div>
      <?php endif ?>
      <div class="form-group">
        <label for="email" class="sr-only">邮箱</label>
        <input id="email" name="email" type="email" class="form-control" placeholder="邮箱" autofocus>
      </div>
      <div class="form-group">
        <label for="password" class="sr-only">密码</label>
        <input id="password" name="password" type="password" class="form-control" placeholder="密码">
      </div>
      <button class="btn btn-primary btn-block">登 录</button>
    </form>
  </div>
</body>
</html>
