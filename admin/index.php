<?php
    require_once('../function.php');
    // 在后台界面第一要素就是判断用户有没有登录
    xk_get_current_user();
    // TODO: 获取数据库数据 列表呈现
    
    // 评论和待审核数据
    $comments_sum = xk_fetch_query_one('select count(1) as count from comments; ')['count'];
    $held_sum = xk_fetch_query_one("select count(1) as count  from comments where `status` = 'held'; ")['count'];

    // 分类数据
    $categories_sum = xk_fetch_query_one('select count(1) as count from categories;')['count'];

    // 文章和草稿数据
    $posts_sum = xk_fetch_query_one('select count(1) as count from posts;')['count'];
    $drafted_sum = xk_fetch_query_one("select count(2)  as drafted from posts where `status`= 'drafted';")['drafted'];
    
    // TODO: 图形化呈现
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Dashboard &laquo; Admin</title>
  <link rel="stylesheet" href="/static/assets/vendors/bootstrap/css/bootstrap.css">
  <link rel="stylesheet" href="/static/assets/vendors/font-awesome/css/font-awesome.css">
  <link rel="stylesheet" href="/static/assets/vendors/nprogress/nprogress.css">
  <link rel="stylesheet" href="/static/assets/css/admin.css">
  <script src="/static/assets/vendors/nprogress/nprogress.js"></script>
</head>
<body>
  <script>NProgress.start()</script>

  <div class="main">
    
    <?php include_once('inc/navbar.php') ?>

    <div class="container-fluid">
      <div class="jumbotron text-center">
        <h1>One Belt, One Road</h1>
        <p>Thoughts, stories and ideas.</p>
        <p><a class="btn btn-primary btn-lg" href="post-add.html" role="button">写文章</a></p>
      </div>
      <div class="row">
        <div class="col-md-4">
          <div class="panel panel-default">
            <div class="panel-heading">
              <h3 class="panel-title">站点内容统计：</h3>
            </div>
            <ul class="list-group">
              <li class="list-group-item"><strong><?php echo $posts_sum ?></strong>篇文章（<strong><?php echo $drafted_sum ?></strong>篇草稿）</li>
              <li class="list-group-item"><strong><?php echo $categories_sum ?></strong>个分类</li>
              <li class="list-group-item"><strong><?php echo $comments_sum ?></strong>条评论（<strong><?php echo $held_sum ?></strong>条待审核）</li>
            </ul>
          </div>
        </div>
        <div class="col-md-4">
          <canvas id="myChart"></canvas>
        </div>
        <div class="col-md-4"></div>
      </div>
    </div>
  </div>
  
  <?php include_once('inc/aside.php') ?>

  <script src="/static/assets/vendors/jquery/jquery.js"></script>
  <script src="/static/assets/vendors/bootstrap/js/bootstrap.js"></script>
  <script>NProgress.done()</script>
  <script src="/static/assets/vendors/chart/chart.js" type="text/javascript" charset="utf-8" ></script>
  <script>
    var ctx = document.getElementById('myChart').getContext('2d');
    var myChart = new Chart(ctx, {
      type: 'pie',
      data: {
        datasets: [
          {
            data: [<?php echo $posts_sum; ?>, <?php echo $categories_sum; ?>, <?php echo $comments_sum; ?>],
            backgroundColor: [
              'skyblue',
              'pink',
              'yellow',
            ]
          },
          {
            data:[<?php echo $drafted_sum; ?>, <?php echo $categories_sum; ?>, <?php echo $held_sum ?>],
            backgroundColor: [
              'skyblue',
              'pink',
              'yellow',
            ]
          }
        ],

        // These labels appear in the legend and in the tooltips when hovering different arcs
        labels: [
          '文章',
          '分类',
          '文章',
          
        ]
        
      }
    });
  </script>
</body>
</html>
