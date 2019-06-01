<?php
  require_once('../function.php');
  xk_get_current_user();
  // TODO: 展示页面
  // 查询数据  =>联合查询的使用
//   $sql = "select 
//   posts.id as id,
//   posts.title AS title,
//   posts.created as time,
//   posts.`status` as `status`,
//   users.nickname as nickname,
//   categories.`name` as `name`
//   from posts
//   INNER JOIN categories on posts.category_id = categories.id
//   INNER JOIN users on posts.user_id = users.id;";
// $result_post = xk_fetch_query_all($sql);
   /**
    * @param [状态转化] $[status] [转化数据格式]
    * @return data [<想要的数据格式>]
    */
   function format_status($data){
    $statuList = array(
      'published' => '发表',
      'drafted' => '草稿',
      'trashed' => '回收箱'
  );
    return $statuList[$data];
   }
   /*时间转化*/
   function formatTime($time) {
    $timestamp = strtotime($time);
    return date('Y年m月d日<b\r>H:i:s', $timestamp);
   }
  // TODO: 分页功能
  // 获取需要数字
   //总条数


// TODO: 筛选功能
  // 处理表单 获取数据
  // bug:下一页 会刷新状态
  $categories_name = xk_fetch_query_all('select * from categories');
  $where = '1 = 1';
  $search = '';
  if (isset($_GET['cate']) && $_GET['cate'] != -1){
    $where .= ' and categories.id = ' . (int)$_GET['cate'];
    $search = '&cate=' . (int)$_GET['cate'];
  }
  if (isset($_GET['status']) && $_GET['status'] != 'all'){
    $where .= " and posts.`status` = '{$_GET['status']}'";
    $search .= '&status=' . $_GET['status'];
  }
  
  
  // 动态获取当前页数
  $page = empty($_GET['p']) ? '1': $_GET['p'];
  // 判读 page 特殊情况 需要算出最大页数后
 $total = xk_fetch_query_one("select 
  count(1) as total
  from posts
  INNER JOIN categories on posts.category_id = categories.id
  INNER JOIN users on posts.user_id = users.id
  where {$where};")['total'];
  // 每页条数设置
  $size = 20; //显示数目
  $offset = ($page - 1) * $size;
  $max_total =(int)ceil($total / $size);// celi 获得是浮点型数字
  $visible = 5 ;// 显示的个数
  $space = ($visible - 1) / 2;
  // 1 限制条数 =>(数据库降序排列 order by 字段名 desc) limit 都是放在sql语句最后
  // 判断page
  
 if ($page < 1 ){
    header('location: posts.php?p=1');
  }
  if ($page > $max_total ){
    header('location: posts.php?p='. $max_total);
  }

  // 2 设置按钮
  
  $begin = $page - $space;// 开始的数 =>1
  $end = $begin + $visible - 1;// 结束的数 用开始值 + 间距值 比较好算 => 5
  // 考虑特殊情况
  if ($begin < 1) {
    $begin = 1;
    $end = $begin + $visible - 1;
  }
  if ($end > $max_total) {
    $end = $max_total;
    $begin = $end - $visible + 1;
    // 当页面数据内容不足时 bug:开始页会出现负数
    if($begin <1){
      $begin = 1;
    }
  }
  $nextPage = $page+1;
  $prevPage = $page-1;
  echo($nextPage);
  // TODO:上一页下一页显示隐藏 √
  // TODO: 全部删除功能 全选功能
  // TODO: page还有bug未解决 通过URL 传递p=-1 问题; √
  
  
  // 查询功能都是最后一步
  $sql = "select 
  posts.id as id,
  posts.title AS title,
  posts.created as time,
  posts.`status` as `status`,
  users.nickname as nickname,
  categories.`name` as `name`
  from posts
  INNER JOIN categories on posts.category_id = categories.id
  INNER JOIN users on posts.user_id = users.id
  where {$where}
  ORDER BY posts.created DESC
  limit {$offset},{$size};";
$result_post = xk_fetch_query_all($sql);
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Posts &laquo; Admin</title>
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
      <div class="page-title">
        <h1>所有文章</h1>
        <a href="post-add.html" class="btn btn-primary btn-xs">写文章</a>
      </div>
      <!-- 有错误信息时展示 -->
      <!-- <div class="alert alert-danger">
        <strong>错误！</strong>发生XXX错误
      </div> -->
      <div class="page-action">
        <!-- show when multiple checked -->
        <a class="btn btn-danger btn-sm" href="javascript:;" style="display: none">批量删除</a>
        <form class="form-inline" post="get" accept="<?php echo $_SERVER['PHP_SELF']; ?>">
          <select name="cate" class="form-control input-sm">
            <option value="-1">所有分类</option>
            <?php foreach ($categories_name as $item): ?>
              <option value="<?php echo $item['id']; ?>"<?php echo isset($_GET['cate']) && $_GET['cate'] == $item['id'] ? ' selected' : '' ?>><?php echo $item['name'] ?></option>
            <?php endforeach ?>
          </select>
          <select name="status" class="form-control input-sm">
            <option value="all">所有状态</option>
            <option <?php echo isset($_GET['status']) && $_GET['status'] == 'drafted' ? ' selected' : '' ?> value="drafted">草稿</option>
            <option <?php echo isset($_GET['status']) && $_GET['status'] == 'published' ? ' selected' : '' ?> value="published">已发布</option>
            <option <?php echo isset($_GET['status']) && $_GET['status'] == 'trashed' ? ' selected' : '' ?> value="trashed"<?php echo isset($_GET['status']) && $_GET['status'] == 'trashed' ? ' selected' : '' ?>>回收站</option>
          </select>
          <button class="btn btn-default btn-sm">筛选</button>
        </form>
        <ul class="pagination pagination-sm pull-right">
          <?php if ($page != 1): ?>
            <li><a href="?p=<?php echo $prevPage . $search ?>">上一页</a></li>
          <?php endif ?>
          
          <?php for ($i = $begin; $i <= $end ; $i++): ?>
            <li <?php echo $i == $page?' class="active"' : '' ?>><a  href="?p=<?php echo $i .$search ?>"><?php echo $i ?></a></li>
          <?php endfor ?>
          <?php if ($page != $max_total): ?>
            <li><a href="?p=<?php echo $nextPage . $search ?>">下一页</a></li>
          <?php endif ?>
        </ul>
      </div>
      <table class="table table-striped table-bordered table-hover">
        <thead>
          <tr>
            <th class="text-center" width="40"><input type="checkbox"></th>
            <th>标题</th>
            <th>作者</th>
            <th>分类</th>
            <th class="text-center">发表时间</th>
            <th class="text-center">状态</th>
            <th class="text-center" width="100">操作</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($result_post as $key): ?>
            <tr>
            <td class="text-center"><input type="checkbox"></td>
            <td><?php echo $key['title'] ?></td>
            <td><?php echo $key['name'] ?></td>
            <td><?php echo $key['nickname'] ?></td>
            <td class="text-center"><?php echo formatTime($key['time']); ?></td>
            <td class="text-center"><?php echo format_status($key['status']); ?></td>
            <td class="text-center">
              <a href="javascript:;" class="btn btn-default btn-xs">编辑</a>
              <a href="javascript:;" class="btn btn-danger btn-xs">删除</a>
            </td>
          </tr>
          <?php endforeach ?>
        </tbody>
      </table>
    </div>
  </div>

  <?php include_once('inc/aside.php') ?>

  <script src="/static/assets/vendors/jquery/jquery.js"></script>
  <script src="/static/assets/vendors/bootstrap/js/bootstrap.js"></script>
  <script>NProgress.done()</script>
</body>
</html>
