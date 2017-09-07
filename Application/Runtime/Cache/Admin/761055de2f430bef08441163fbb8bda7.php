<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
    <head>
        <title>后台管理系统</title>
        <meta charset="UTF-8">
        <meta name="keywords" content="我的系统">
        <meta name="description" content="">
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <!-- intoHead  -->
        <link rel="stylesheet" type="text/css" href="/Public/adminTpl/style/bootstrap.css" />

        <!-- 全局公共样式 -->
        <script type="text/javascript" src="/Public/adminTpl/js/jquery-1.9.1.min.js"></script>
        <script type="text/javascript" src="/Public/adminTpl/js/jquery.form.js"></script>
        <!-- LayerUi库 -->
        <script type="text/javascript" src="/Public/adminTpl/js/layer.js"></script>
        <script type="text/javascript" src="/Public/adminTpl/js/laypage.js"></script>
        <script type="text/javascript" src="/Public/adminTpl/js/jquery.confirm.js"></script>
        <!-- 后台JS -->
        <script type="text/javascript" src="/Public/adminTpl/js/index.js"></script>
        <script type="text/javascript" src="/Public/adminTpl/js/layerUi.js"></script>
        <script type="text/javascript" src="/Public/adminTpl/js/global.js"></script>
        <!-- // icheck插件 -->
        <link href="/Public/adminTpl/js/icheck/skins/minimal/blue.css" rel="stylesheet">
        <script src="/Public/adminTpl/js/icheck/icheck.js?v=1.0.2"></script>
        <!-- bootstrap 上传插件 -->
        <link href="/Public/adminTpl/js/bootstrap-fileinput/css/fileinput.min.css" media="all" rel="stylesheet" type="text/css" />
        <script src="/Public/adminTpl/js/bootstrap-fileinput/js/plugins/canvas-to-blob.min.js" type="text/javascript"></script>
        <script src="/Public/adminTpl/js/bootstrap-fileinput/js/fileinput.min.js"></script>
        <script src="/Public/adminTpl/js/bootstrap-fileinput/js/fileinput_locale_zh.js"></script>

        <!-- bootstrp表单验证  -->
        <script type="text/javascript" src="/Public/adminTpl/js/Validator/bootstrap3-validation.js"></script>

        <script type="text/javascript" src="/Public/adminTpl/js/dialog-plus-min.js"></script>
        <link rel="stylesheet" type="text/css" href="/Public/adminTpl/style/ui-dialog.css">

        <link rel="stylesheet" type="text/css" href="/Public/adminTpl/style/console1412.css" />
        
        <script type="text/javascript" src="/Public/adminTpl/js/bootstrap/tooltip.js"></script>
        <script type="text/javascript" src="/Public/adminTpl/js/bootstrap/tab.js"></script>

        <script type="text/javascript">
        //document.oncontextmenu=function(){ return false }
        </script>
        <script type="text/javascript">
            $(function(){
                var h = $('.viewFramework-sidebar',parent.document).height();
                $('.home-container').css('height', h-30);

                $(".menu-title").click(function(){
                    //alert('OK');
                    $(this).siblings().toggle();
                    if($("span", this).hasClass('icon-arrow-down')){
                        $("span", this).removeClass('icon-arrow-down').addClass('icon-arrow-right');
                    } else {
                        $("span", this).removeClass('icon-arrow-right').addClass('icon-arrow-down');
                    }
                });

            });
        </script>


        <!-- /intoHead  -->
    </head>


<script>
    $(function(){
        // var t;//设定跳转的时间
        t =  $('.iframe-body').attr('data-setTime');
        uid =  $('.iframe-body').attr('data-sid');
        //alert(uid);
        var lockrefer = setInterval(function() {
            refer();
        }, 1000);

        function refer(){
            if(t==60){
                clearInterval(lockrefer);
                parent.layer.open({
                    type: 2,
                    title: false,
                    closeBtn: 0,
                    shade: [0.8, '#393D49'],
                    maxmin: false,
                    maxmin: false,
                    area: ['100%', '100%'],
                    content: ["/admin/default/loginlock"], //这里content是一个普通的String
                });
                window.top.location.href = "/admin/default/loginlock";
            }else if(t==0 || uid == ''){
                window.top.location.href = "/admin/default/logout";
            }
            var body = $("body",parent.document);
            var loginOutIframe = body.find('.layui-layer').find('iframe').contents().find('body');
            loginOutIframe.find("#localtimeTime span").text(t);
            // console.log(t);
            t--;
        }
    });
</script>



<body class="iframe-body" data-sid="<?php echo ($adminRow["id"]); ?>" data-settime="<?php echo (C("IFRAME_BODY_SETTIME")); ?>" data-actionurl="/Admin/Article/edit?id=75">
    <div class="console-container ng-scope">
        <div class="console-title console-title-border drds-detail-title clearfix">
            <!-- position  -->
            <ol class="breadcrumb">
              <li><i class="icon-home"></i> <a href="/Admin/Index/main">首页</a></li>
              <?php if(is_array($breadcrumbRows)): $i = 0; $__LIST__ = $breadcrumbRows;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li><a href="/Admin/<?php echo ($vo["name"]); ?>"><?php echo ($vo["title"]); ?></a></li><?php endforeach; endif; else: echo "" ;endif; ?>
            </ol>
            <!-- /position  -->
        </div>
        <script type="text/javascript" src="/Public/adminTpl/js/ueditor/ueditor.config.js"></script>
<script type="text/javascript" src="/Public/adminTpl/js/ueditor/ueditor.all.js"></script>
<script type="text/javascript" src="/Public/adminTpl/js/img_preview.js"></script>
<script src="/Public/adminTpl/js/Validator/bootstrap3-validation.js"></script>
<div class="row">
    <div class="col-sm-12">
        <form class="form-horizontal autoBootstrapValidator" action="" data-ajax='true' method="POST" name="formData" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo ($art["id"]); ?>" id="id" />
            <div class="form-group">
                <label for="cid" class="col-sm-2 control-label">文章分类</label>
                <div class="col-sm-5">
                    <select id="cid" name="cid" class="form-control">
                        <?php echo ($options); ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="author" class="col-sm-2 control-label">文章作者</label>
                <div class="col-sm-5">
                    <input type="text" class="form-control" name="author" check-type="required" id="author" placeholder="文章作者" value="<?php echo ($art['author']); ?>"/>
                </div>
            </div>
            <div class="form-group">
                <label for="title" class="col-sm-2 control-label">文章标题</label>
                <div class="col-sm-5">
                    <input type="text" class="form-control" name="title" check-type="required" id="title" placeholder="文章标题" value="<?php echo ($art['title']); ?>" />
                </div>
            </div>
            <div class="form-group">
                <label for="keywords" class="col-sm-2 control-label">关键字</label>
                <div class="col-sm-5">
                    <input type="text" class="form-control" name="keywords" check-type="required" id="keywords" placeholder="关键字" value="<?php echo ($art['keywords']); ?>" />
                </div>
            </div>
            <div class="form-group">
                <label for="description" class="col-sm-2 control-label">文章摘要</label>
                <div class="col-sm-5">
                    <textarea name="description" check-type="required" class="form-control" id="description" placeholder="文章摘要" rows="5"><?php echo ($art['description']); ?></textarea>
                </div>
            </div>
            <div class="form-group">
                <label for="link" class="col-sm-2 control-label">文章原链接</label>
                <div class="col-sm-5">
                        <input type="text" name="link" check-type="" class="form-control" id="description" placeholder="" value="<?php echo ($art['link']); ?>">
                </div>
            </div>
            <div class="form-group">
                <label for="sort" class="col-sm-2 control-label">排序</label>
                <div class="col-sm-5">
                    <input type="number" name="sort" class="form-control" id="description" placeholder="255" value="<?php echo ($art['sort']); ?>">
                </div>
            </div>
            <div class="form-group">
                <label for="isdisplay" class="col-sm-2 control-label">状态</label>            
                <div class="col-sm-5">
                <select id="isdisplay" name="isdisplay" class="form-control">
                    <option value="1" <?php if($art['isdisplay'] == 1): ?>selected<?php endif; ?>>显示</option>
                    <option value="0" <?php if($art['isdisplay'] == 0): ?>selected<?php endif; ?>>隐藏</option>
                </select>
                </div>
            </div>  
            <div class="form-group">
                <label for="inputTitle" class="col-sm-2 control-label">图片</label>
                <div class="col-sm-5">
                    <div class="input-group">
                        <textarea class="form-control input-file-text input-value-4-input" name="file1"><?php echo ($art['thumbnail']); ?></textarea>
                        <span class="input-group-btn">
                    <input type="file" class="btn-input-file" data-ext="/(\.jpg|\.png|\.gif)$/">
                    <button class="btn btn-success" type="button">上传</button>
                  </span>
                    </div>
                    <!-- /input-group -->
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label" for="content"> 文章内容 </label>
                <div class="col-sm-8">
                    <script id="container" name="content" type="text/plain"><?php echo ($art['content']); ?></script>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-3 col-sm-9">
                    <button type="submit" class="btn btn-primary" >提交</button>
                </div>
            </div>
        </form>
    </div>
</div>
<script type="text/javascript">
var ue = UE.getEditor('container');
</script>

    </div>
</body>
</html>