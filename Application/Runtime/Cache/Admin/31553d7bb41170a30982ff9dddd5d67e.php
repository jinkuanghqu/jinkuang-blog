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



<body class="iframe-body" data-sid="<?php echo ($adminRow["id"]); ?>" data-settime="<?php echo (C("IFRAME_BODY_SETTIME")); ?>" data-actionurl="/Admin/Menu/add">
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
        <form class="form-horizontal autoBootstrapValidator" name="formData" data-ajax='true'>
            <div class="form-group">
                <label for="inputPid" class="col-sm-3 control-label">上级菜单</label>
                <div class="col-sm-5">
                    <select name="pid" id="inputPid" class="form-control">
                        <option value="0">顶级菜单</option>
                        <?php if(is_array($parentRow)): $i = 0; $__LIST__ = $parentRow;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i; if(isset($authRuleRow) && $authRuleRow['pid'] == $item['id']): ?><option value="<?php echo ($item['id']); ?>" selected="selected"><?php echo ($item['title']); ?></option>
                            <?php else: ?>
                            <option value="<?php echo ($item['id']); ?>"><?php echo ($item['title']); ?></option><?php endif; endforeach; endif; else: echo "" ;endif; ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="inputTitle" class="col-sm-3 control-label">菜单名称</label>
                <div class="col-sm-5">
                    <?php if(isset($authRuleRow)): ?><input type="text" class="form-control" name="title" value="<?php echo ($authRuleRow['title']); ?>" check-type="required chinese" id="inputTitle" placeholder="菜单名称" />
                    <?php else: ?>
                    <input type="text" class="form-control" name="title" check-type="required chinese" id="inputTitle" placeholder="菜单名称" /><?php endif; ?>
                </div>
            </div>
            <div class="form-group">
                <label for="inputName" class="col-sm-3 control-label">菜单链接</label>
                <div class="col-sm-5">
                    <?php if(isset($authRuleRow)): ?><input type="text" class="form-control" name="name" value="<?php echo ($authRuleRow["name"]); ?>" data-ajax="<?php echo U('/Admin/Menu/verifyname',array('id'=>$authRuleRow['id']));?>" id="inputName" check-type="required" placeholder="菜单链接">
                    <?php else: ?>
                    <input type="text" class="form-control" name="name" id="inputName" check-type="required" data-ajax="<?php echo U('/Admin/Menu/verifyname');?>" placeholder="菜单链接"><?php endif; ?>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label" for="inputIcon"> ICON图标 </label>
                <div class="col-sm-5">
                    <?php if(isset($authRuleRow)): ?><input type="text" class="form-control" name="icon" value="<?php echo ($authRuleRow["icon"]); ?>" id="inputIcon" placeholder="ICON图标">
                    <?php else: ?>
                    <input type="text" class="form-control" name="icon" id="inputIcon" placeholder="ICON图标"><?php endif; ?>
                </div>
            </div>
            
            <div class="form-group">
                <label class="col-sm-3 control-label" for="inputIslink">是否菜单链接</label>
                <div class="col-sm-5">
                    <?php if(isset($authRuleRow) && $authRuleRow['islink'] == 1): ?><input type="checkbox" class="form-control input-icheck" checked="checked" name="islink" id="inputIslink" value="1" />
                    <?php else: ?>
                    <input type="checkbox" class="form-control input-icheck" name="islink" id="inputIslink" value="1" /><?php endif; ?>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-3 control-label" for="inputSort"> 排序 </label>
                <div class="col-sm-5">
                    <?php if(isset($authRuleRow)): ?><input type="text" class="form-control " name="sort" value="<?php echo ($authRuleRow['sort']); ?>" id="inputSort" check-type="number" range="0~500" placeholder="ICON图标 越小越靠前">
                    <?php else: ?>
                    <input type="text" class="form-control " name="sort" value="255" id="inputSort" check-type="number" range="0~500" placeholder="ICON图标 越小越靠前"><?php endif; ?>
                </div>               
            </div>


            <div class="form-group">
                <div class="col-sm-offset-3 col-sm-9">
                    <button type="submit" class="btn btn-primary">提交</button>
                </div>
            </div>
        </form>
    </div>
</div>

    </div>
</body>
</html>