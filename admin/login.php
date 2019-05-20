<?php
require_once('../config.php'); // 由于require和include不能添加绝对路径 所以用相对路径或者 物理路径？
// 物理路径写法 =>

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
  // 我的格式编码不是中文的 需要更改
  mysqli_set_charset($connet, 'utf8');
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

// 退出功能
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
  <script src="/static/assets/vendors/jquery/jquery.min.js"></script>
  <script>
    // 在鼠标焦点离开时 实现输入账号请求头像功能
    // TODO: 1 验证是不是一个邮箱
    // TODO：2 发送ajax请求  =>
    // 
    
    $(function($){
      var reg = /^[a-zA-Z0-9]+@[a-zA-Z0-9]+\.([a-zA-Z0-9]{2,4})$/;


      var oldValue = $('#email').val();
      $('#email').on('blur', function (){
        // 校验邮箱
        var value = $('#email').val();
        // alert(value);
        // console.log(value);
        // alert(oldValue);
        if ( value == '' || !reg.test(value)) return;

        if (oldValue == value) return;
        oldValue = value;
        // 发送ajax请求
        $.get('/admin/api/avatar.php',{ email: value}, function(res){
          if(!res) {
            $('.avatar').attr('src', '/static/assets/img/default.png');
               return;
          }

          $('.avatar').fadeOut(function (){
            $(this).on('load', function(){
              $(this).fadeIn();
            }).attr('src', res);
          })
        })
      })
    })
  </script>
</body>
</html>
