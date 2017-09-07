<?php if (!defined('THINK_PATH')) exit();?><script type="text/javascript" src="/Public/adminTpl/js/img_preview.js"></script>
<script src="/Public/adminTpl/js/Validator/bootstrap3-validation.js"></script>
<script src="/Public/adminTpl/js/global.js"></script>

<ol class="breadcrumb">
  <li><a href="#">焦点图管理</a></li>
  <li><a href="<?php echo U('Admin/Flash/index');?>">焦点图列表</a></li>
  <li class="active"> 添加焦点图</li>
</ol>

<div class="ng-isolate-scope mt50">
    <form class="form-horizontal" action="/Admin/Flash/add" method="post" enctype="multipart/form-data">

        <?php echo ($content); ?>

        <div class="form-group">
            <div class="col-sm-offset-3 col-sm-6">
                <button i="close" class="btn btn-default">取消</button>
                <button id="submit" type="submit" class="btn btn-primary" style="margin-left: 20px">确认</button>
            </div>
         </div>
    </form>
</div>

<style>
strong{ min-width:200px;}
</style>

<script>
    $(function(){
        $("form").validation();
        $("button[type='submit']").on('click', function(event){
            if ($("form").valid(this, "error!")==false) {
                return false;
            }
        });
    });
</script>