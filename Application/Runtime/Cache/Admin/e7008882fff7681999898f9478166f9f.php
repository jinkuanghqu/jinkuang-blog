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



<body class="iframe-body" data-sid="<?php echo ($adminRow["id"]); ?>" data-settime="<?php echo (C("IFRAME_BODY_SETTIME")); ?>" data-actionurl="/Admin/System/editSystem">
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
    <div class="col-sm-12">
        <ul class="nav nav-tabs">
            <?php if(is_array($settingRowsForArrange)): foreach($settingRowsForArrange as $k=>$vo): ?><li role="presentation" 
                <?php if($k == 'main'): ?>class="active"<?php endif; ?>
                ><a href="#<?php echo ($k); ?>"><?php echo ($groups[$k]); ?></a></li><?php endforeach; endif; ?>
        </ul>
    </div>
    <form class="form-horizontal autoBootstrapValidator" name="formData" data-ajax='true'>
    <div class="tab-content">
    <?php if(is_array($settingRowsForArrange)): foreach($settingRowsForArrange as $k=>$rows): ?><div role="tabpanel" class="tab-pane fade in
        <?php if($k == 'main'): ?>active<?php endif; ?>
        "
        id="<?php echo ($k); ?>"
        >
            <div class="col-sm-12">                
                    <?php if(is_array($rows)): $i = 0; $__LIST__ = $rows;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i; if($vo["input_types"] == 'hidden'): ?><input type="hidden" class="form-control" name="<?php echo ($vo['key']); ?>" value="<?php echo ($vo['value']); ?>"/>
                    <?php else: ?>
                        <div class="form-group">
                            <label for="inputname" class="col-sm-2 control-label"><?php echo ($vo["description"]); ?></label>
                            <div class="col-sm-6" data-toggle="tooltip" data-placement="right" title="调用方法：<?php echo (C("TMPL_L_DELIM")); ?>$Think.config.<?php echo ($vo["key"]); echo (C("TMPL_R_DELIM")); ?>">
                            <?php switch($vo["input_types"]): case "textarea": ?><textarea class="form-control" check-type="<?php echo ($vo["check"]); ?>" name="<?php echo ($vo["key"]); ?>"><?php echo ($vo["value"]); ?></textarea><?php break;?>
                                <?php case "text": ?><input type="text" class="form-control" check-type="<?php echo ($vo["check"]); ?>" name="<?php echo ($vo['key']); ?>" value="<?php echo ($vo['value']); ?>"/><?php break;?>
                                <?php case "radio": if($vo["value"] == 'TRUE'): ?><input type="radio" class="input-icheck"  name="<?php echo ($vo['key']); ?>" value="TRUE" checked="checked"/>是&nbsp;&nbsp;
                                    <input type="radio" class="input-icheck"  name="<?php echo ($vo['key']); ?>" value="FALSE" />否
                                    <?php else: ?>
                                    <input type="radio" class="input-icheck"  name="<?php echo ($vo['key']); ?>" value="TRUE" />是&nbsp;&nbsp;
                                    <input type="radio" class="input-icheck"  name="<?php echo ($vo['key']); ?>" value="FALSE" checked="checked" />否<?php endif; break;?>
                                <?php case "file": ?><div class="input-group">
                                        <textarea class="form-control input-file-text input-value-4-input" check-type="<?php echo ($vo["check"]); ?>" name="<?php echo ($vo['key']); ?>"><?php echo ($vo['value']); ?></textarea>
                                      <span class="input-group-btn">
                                        <input type="file" class="btn-input-file" data-ext="/(\.jpg|\.png|\.gif)$/" />
                                        <button class="btn btn-success" type="button">上传</button>
                                      </span>
                                    </div><!-- /input-group --><?php break;?>
                                <?php default: ?>
                                <input type="<?php echo ($vo["input_types"]); ?>" class="form-control" check-type="<?php echo ($vo["check"]); ?>" name="<?php echo ($vo['key']); ?>" value="<?php echo ($vo['value']); ?>"/><?php endswitch;?>                    
                            </div>
                            
                        </div><?php endif; endforeach; endif; else: echo "" ;endif; ?>

                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-9">
                            <button type="submit" class="btn btn-primary">提交</button>
                        </div>
                    </div>

                
            </div>
        </div><?php endforeach; endif; ?>
    </div>
    </form>
</div>

    </div>
</body>
</html>