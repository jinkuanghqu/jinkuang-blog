<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
    <head>
        <title>深圳大学赛事后台管理系统</title>
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



<body class="iframe-body" data-sid="<?php echo ($adminRow["id"]); ?>" data-settime="<?php echo (C("IFRAME_BODY_SETTIME")); ?>" data-actionurl="/Admin/Role/add.html">
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
                <label for="inputname" class="col-sm-3 control-label">用户组名称</label>
                <div class="col-sm-5">                    
                    <input type="text" class="form-control" name="name" value="<?php echo ($roleRow['name']); ?>" check-type="required chinese" id="inputname" placeholder="用户组名称" />
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-3 control-label" for="inputIslink">是否启用</label>
                <div class="col-sm-5">
                    <?php if($roleRow['status'] == 1): ?><input type="checkbox" class="form-control input-icheck" checked="checked" name="status" id="inputIslink" value="1" />
                    <?php else: ?>
                    <input type="checkbox" class="form-control input-icheck" name="status" id="inputIslink" value="1" /><?php endif; ?>
                </div>
            </div>

            <div class="form-group">
                <label for="" class="col-sm-3 control-label">说明</label>
                <div class="col-sm-5">
                    <textarea rows='4' name="remark" class="form-control"><?php echo ($roleRow['remark']); ?></textarea>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-3 control-label">权限选择</label>
                <div class="col-sm-9">
                    <div class="widget-group">
                        <?php if(is_array($authRuleAll)): $i = 0; $__LIST__ = $authRuleAll;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$authRuleRow): $mod = ($i % 2 );++$i;?><div class="widget-box">
                            <div class="widget-header">
                                <h4 class="widget-title">
                                    <label>
                                        <?php if(in_array($authRuleRow['id'],$roleAuthRuleRows)): ?><input name="rules[][id]" class="ace ace-checkbox-2 father" type="checkbox" value="<?php echo ($authRuleRow["id"]); ?>" checked="checked">
                                        <?php else: ?>
                                        <input name="rules[][id]" class="ace ace-checkbox-2 father" type="checkbox" value="<?php echo ($authRuleRow["id"]); ?>"><?php endif; ?>
                                        <span class="lbl"> <?php echo ($authRuleRow["title"]); ?> </span>
                                    </label>
                                </h4>
                                <div class="widget-toolbar">
                                    <a href="#" data-toggle="collapse">
                                        <i class="icon-down"></i>
                                    </a>
                                </div>
                            </div>
                            <div class="widget-body collapse in">
                                <div class="widget-main row">
                                    <?php if(is_array($authRuleRow['child'])): $i = 0; $__LIST__ = $authRuleRow['child'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$authRule): $mod = ($i % 2 );++$i;?><label class="col-xs-4">
                                        <?php if(in_array($authRule['id'],$roleAuthRuleRows)): ?><input name="rules[][id]" class="ace ace-checkbox-2 children" type="checkbox" value="<?php echo ($authRule["id"]); ?>" checked="checked">
                                        <?php else: ?>
                                        <input name="rules[][id]" class="ace ace-checkbox-2 children" type="checkbox" value="<?php echo ($authRule["id"]); ?>"><?php endif; ?>                                        
                                        <span class="lbl"> <?php echo ($authRule["title"]); ?></span>
                                    </label><?php endforeach; endif; else: echo "" ;endif; ?>
                                </div>
                            </div>
                        </div><?php endforeach; endif; else: echo "" ;endif; ?>
                    </div>
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
<script type="text/javascript">
		$(".children").click(function(){
			$(this).parent().parent().parent().parent().find(".father").prop("checked", true);
		})
		$(".father").click(function(){
			if(this.checked){
				$(this).parent().parent().parent().parent().find(".children").prop("checked", true);
			}else{
				$(this).parent().parent().parent().parent().find(".children").prop("checked", false);
			}
		})
</script>

    </div>
</body>
</html>