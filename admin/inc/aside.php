<?php

require_once('../function.php'); // 注意是谁引入这个文件相对路径就从哪开始
$path = $_SERVER['PHP_SELF'];
$path = substr($path, 7);

// 判断用户是否登录


// 接受用户数据
$user_name = xk_get_current_user();


 ?>
<div class="aside">
    <div class="profile">
      <img class="avatar" src="<?php echo $user_name['avatar'] ?>">
      <h3 class="name"><?php echo $user_name['nickname'] ?></h3>
    </div>
    <ul class="nav">
      <li <?php echo $path === 'index.php'? 'class="active"' : ''; ?>>
        <a href="/admin/index.php"><i class="fa fa-dashboard"></i>仪表盘</a>
      </li>
      <?php $menu_posts = array('posts.php', 'post-add.php', 'categories.php')?>
      <li <?php echo in_array($path, $menu_posts) ? 'class="active"' : ''; ?>>

        <a href="#menu-posts" <?php echo in_array($path, $menu_posts)? '' : 'class="collapsed"'; ?> data-toggle="collapse">
          <i class="fa fa-thumb-tack"></i>文章<i class="fa fa-angle-right"></i>
        </a>
        <ul id="menu-posts" class="collapse<?php echo in_array($path, $menu_posts)? ' in' : ''; ?>">
          <li <?php echo $path === 'posts.php'? 'class="active"' : ''; ?>><a href="/admin/posts.php" >所有文章</a></li>
          <li <?php echo $path === 'post-add.php'? 'class="active"' : ''; ?>><a href="/admin/post-add.php" >写文章</a></li>
          <li <?php echo $path === 'categories.php'? 'class="active"' : ''; ?>><a href="/admin/categories.php" >分类目录</a></li>
        </ul>
      </li>
      <li <?php echo $path === 'comments.php'? 'class="active"' : ''; ?>>
        <a href="/admin/comments.php"><i class="fa fa-comments"></i>评论</a>
      </li>
      <li <?php echo $path === 'users.php'? 'class="active"' : ''; ?>>
        <a href="/admin/users.php"><i class="fa fa-users"></i>用户</a>
      </li>
      <?php   $menu_settings =array('nav-menus.php', 'slides.php', 'settings.php'); ?>
      <li <?php echo in_array($path, $menu_settings) ? 'class="active"' : ''; ?>>
        <a href="/admin/#menu-settings" <?php echo in_array($path, $menu_settings) ? '' : 'class="collapsed"'; ?> data-toggle="collapse">
          <i class="fa fa-cogs"></i>设置<i class="fa fa-angle-right"></i>
        </a>
        <ul id="menu-settings" class="collapse<?php echo in_array($path, $menu_settings)? ' in' : ''; ?>">
          <li<?php echo $path === 'nav-menus.php'? 'class="active"' : ''; ?>><a href="/admin/nav-menus.php">导航菜单</a></li>
          <li<?php echo $path === 'slides.php'? 'class="active"' : ''; ?>><a href="/admin/slides.php">图片轮播</a></li>
          <li<?php echo $path === 'settings.php'? 'class="active"' : ''; ?>><a href="/admin/settings.php">网站设置</a></li>
        </ul>
      </li>
    </ul>
</div>