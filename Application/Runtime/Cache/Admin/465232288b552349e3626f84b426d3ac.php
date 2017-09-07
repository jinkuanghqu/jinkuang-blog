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



<body class="iframe-body" data-sid="<?php echo ($adminRow["id"]); ?>" data-settime="<?php echo (C("IFRAME_BODY_SETTIME")); ?>" data-actionurl="/Admin/Seo/edit/id/1.html">
    <div class="console-container ng-scope">
        <div class="console-title console-title-border drds-detail-title clearfix">
            <!-- position  -->
            <ol class="breadcrumb">
              <li><i class="icon-home"></i> <a href="/Admin/Index/main">首页</a></li>
              <?php if(is_array($breadcrumbRows)): $i = 0; $__LIST__ = $breadcrumbRows;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li><a href="/Admin/<?php echo ($vo["name"]); ?>"><?php echo ($vo["title"]); ?></a></li><?php endforeach; endif; else: echo "" ;endif; ?>
            </ol>
            <!-- /position  -->
        </div>
        <div class="row">
    <div class="col-sm-8">
        <form class="form-horizontal autoBootstrapValidator" action="<?php echo U('/Admin/Seo/edit');?>" method="post" enctype="application/x-www-form-urlencoded" data-ajax="true">
            <input type="hidden" name="id" value="<?php echo ($item['id']); ?>"/>
            <div class="form-group">
                <label class="col-sm-3 control-label" for="name">名称：</label>
                <div class="col-sm-5">
                    <input type="text" class="form-control" name="name" placeholder="请输入名字" value="<?php echo ($item['name']); ?>" check-type="required"/>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label" for="path">页面路径：</label>
                <div class="col-sm-5">
                    <input type="text" class="form-control" name="path" placeholder="网页地址主机名后面的部分,不包括前面的/" value="<?php echo ($item['page_path']); ?>" check-type="required"/>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label" for="title">SEO标题：</label>
                <div class="col-sm-5">
                    <input type="text" class="form-control" name="title" placeholder="请输入SEO标题" value="<?php echo ($item['title']); ?>"  check-type="required"/>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label" for="keywords">SEO关键词：</label>
                <div class="col-sm-5">
                    <input type="text" class="form-control" name="keywords" placeholder="请输入SEO关键词"  value="<?php echo ($item['keywords']); ?>" check-type="required"/>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label" for="description">SEO描述：</label>
                <div class="col-sm-5">
                    <textarea class="form-control" rows="5" name="description" placeholder="请输入SEO描述" check-type="required"><?php echo ($item['description']); ?></textarea>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-3 col-sm-6">
                    <button i="close" class="btn btn-default">取消</button>
                    <button id="submit" type="submit" class="btn btn-primary" style="margin-left: 20px">确认</button>
                </div>
             </div>
        </form>
    </div>
</div>
    </div>
</body>
</html>