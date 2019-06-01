<?php
// 完成分类目录增删改查
// 完成分类目录列表详情

// 后台的先检查是否登入了
include_once('../function.php');
$current_uesr = xk_get_current_user();

// 为了避免逻辑混乱
// 删除功能另外一个页面做
// 添加功能
function add_categories(){
  if (empty($_POST['name'])) {
    $GLOBALS['erro_message'] = '请输入分类名称';
    return;
  }
  $GLOBALS['categories_name'] = $_POST['name'];
  if (empty($_POST['slug'])) {
    $GLOBALS['erro_message'] = '请输入分类别名';
    return;
  }
  // var_dump($GLOBALS['categories_name']);
  
  $categories_slug = $_POST['slug'];
  $affect = xk_query_execute("insert into categories value (null , '{$categories_slug}', '{$GLOBALS['categories_name']}');");
  if ($affect < 1){
    $GLOBALS['erro_message'] = '请检查输入格式';
    return false;
  };
  // 完成增加操作
  $GLOBALS['categories_name'] = '';
  return true;
}

if($_SERVER['REQUEST_METHOD'] == 'POST'){
  add_categories();
}

// 查询列表详细页
$resu = xk_fetch_query_all('SELECT * from categories;');

?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Categories &laquo; Admin</title>
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
        <h1>分类目录</h1>
      </div>
      <!-- 有错误信息时展示 -->
      <?php if (isset($erro_message)): ?>
        <div class="alert alert-danger">
        <strong>错误！</strong><?php echo $erro_message; ?>
      </div>
      <?php endif ?>

      <div class="row">
        <div class="col-md-4">
          <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
            <h2>添加新分类目录</h2>
            <div class="form-group">
              <label for="name">名称</label>
              <input id="name" class="form-control" name="name" type="text" placeholder="分类名称" <?php echo isset($categories_name)? "value='{$categories_name}'" : '' ?>>
            </div>
            <div class="form-group">
              <label for="slug">别名</label>
              <input id="slug" class="form-control" name="slug" type="text" placeholder="slug">
              <p class="help-block">https://zce.me/category/<strong>slug</strong></p>
            </div>
            <div class="form-group">
              <button class="btn btn-primary" type="submit">添加</button>
            </div>
          </form>
        </div>


        <div class="col-md-8">
          <div class="page-action">
            <!-- show when multiple checked -->
            <a id="del_some" class="btn btn-danger btn-sm" href="/admin/categories-delete.php?id=<?php echo $item['id'] ?>" style="display: none">批量删除</a>
          </div>
          <table class="table table-striped table-bordered table-hover">
            <thead>
              <tr>
                <th class="text-center"  width="40"><input id="ckAll" type="checkbox"></th>
                <th>名称</th>
                <th>Slug</th>
                <th class="text-center" width="100">操作</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($resu as $item ): ?>
                <tr>
                <td class="text-center"><input type="checkbox" class="j_btn" data-id="<?php echo $item['id'] ?>"></td>
                <td><?php echo $item['name'] ?></td>
                <td><?php echo $item['slug'] ?></td>
                <td class="text-center">
                  <a href="javascript:;" class="btn btn-info btn-xs">编辑</a>
                  <a href="/admin/categories-delete.php?id=<?php echo $item['id'] ?>" class="btn btn-danger btn-xs">删除</a>
                </td>
              </tr>
              <?php endforeach ?>
              
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <?php include_once('inc/aside.php') ?>

  <script src="/static/assets/vendors/jquery/jquery.js"></script>
  <script>
    // 全选 全部按钮
    $(function(){
      var $j_btn = $('.j_btn');
      var result = [] ;
      // 全选按钮
      $('#ckAll').on('click',function(){
        if ($(this).prop('checked')){
          $j_btn.each(function (i, item){
            
            $(item).prop('checked', true);
            map_id($(item));
          })
         }else {
           $j_btn.each(function (i, item){
            $(item).prop('checked', false);
            map_id($(item));
          })
         }
         // console.log(result);
        });

         //单选按钮 
      $('.j_btn').on('change',function(){
        
        map_id($(this));
        
          // console.log(result);
           
      })
     
      
       function map_id(item){
        var id = item.data('id');
        var btn = $('#del_some');
        if( item.prop('checked')){
          if(result.indexOf(id) == -1){
          result.push(id);
          }
        }else {
          var index = result.indexOf(id);
          result.splice(index,1);
        }
        if(result.length){
          btn.fadeIn()
        }else{
          btn.fadeOut()
          $('#ckAll').prop('checked', false);
        }  
       btn.prop('search', '?id=' +result);
       console.log(btn.attr('href'))
       }

    });
  </script>
  <script src="/static/assets/vendors/bootstrap/js/bootstrap.js"></script>
  <script>NProgress.done()</script>
</body>
</html>
