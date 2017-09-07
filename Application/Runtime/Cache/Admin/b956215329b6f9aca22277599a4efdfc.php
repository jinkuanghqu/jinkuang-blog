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



<body class="iframe-body" data-sid="<?php echo ($adminRow["id"]); ?>" data-settime="<?php echo (C("IFRAME_BODY_SETTIME")); ?>" data-actionurl="/Admin/Menu/index">
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
    <div class="panel-group col-sm-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a class="accordion-toggle" data-toggle="collapse">
                        <i class="bigger-110 icon-down"></i>
                        &nbsp;后台菜单
                    </a>
                </h4>
            </div>

            <div class="panel-collapse collapse in">
                <div class="panel-body">
                    <div class="row">                        
                        <div class="col-sm-12">
                            <a href="<?php echo U('/Admin/Menu/add');?>" class="btn btn-success">添加</a>
                        </div>
                        <form action="<?php echo U('/Admin/Menu/destroy');?>" class="form-horizontal checkbox-del">
                            <div class="col-sm-12 panel-scroll">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th class="center"><input class="check-all" type="checkbox" value=""></th>
                                            <th class="center">菜单名称</th>
                                            <th class="center">链接</th>
                                            <th class="center">ICON</th>
                                            <th class="center">是否菜单</th>
                                            <th class="center">排序</th>
                                            <th class="center">操作</th>
                                        </tr>
                                    </thead>
                                    <tbody>                
                                        <?php if(is_array($authRuleAll)): $i = 0; $__LIST__ = $authRuleAll;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$authRuleRows): $mod = ($i % 2 );++$i;?><tr class="success">                                       
                                            <td><input class="ids" type="checkbox" name="id[]" value="<?php echo ($authRuleRows["id"]); ?>"></td>
                                            <td class="text-left"><?php echo ($authRuleRows["title"]); ?></td>
                                            <td>
                                                <?php echo ($authRuleRows["name"]); ?>
                                            </td>
                                            <td><i class="<?php echo ($authRuleRows["icon"]); ?>"></i></td>
                                            <td>
                                                <?php if($authRuleRows["islink"] == 0): ?><span class="label label-danger">否</span>
                                                <?php else: ?>
                                                <span class="label label-success">是</span><?php endif; ?>
                                            </td>
                                            <td><input type="text" class="input-sort input-ajax-submit" data-ajax="<?php echo U('/Admin/Menu/sort',array('id'=>$authRuleRows['id']));?>" value="<?php echo ($authRuleRows["sort"]); ?>" /></td>
                                            <td>
                                                <a href="<?php echo U('/Admin/Menu/edit',array('id'=>$authRuleRows['id']));?>" class="btn btn-sm btn-info">修改</a>                                
                                                <a href="javascript:void(0);" data-ajax="<?php echo U('/Admin/Menu/destroy',array('id'=>$authRuleRows['id']));?>" class="btn btn-sm btn-danger ajax-destroy">删除</a>
                                            </td>
                                        </tr>
                                        <?php if($authRuleRows.child): if(is_array($authRuleRows['child'])): $i = 0; $__LIST__ = $authRuleRows['child'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$authRuleRow): $mod = ($i % 2 );++$i;?><tr class="info">                                       
                                                <td><input class="ids" type="checkbox" name="id[]" value="<?php echo ($authRuleRow["id"]); ?>"></input></td>
                                                <td class="text-left">&nbsp;&nbsp;┗━ <?php echo ($authRuleRow["title"]); ?></td>
                                                <td>
                                                    <?php echo ($authRuleRow["name"]); ?>
                                                </td>
                                                <td><i class="<?php echo ($authRuleRow["icon"]); ?>"></i></td>
                                                <td>
                                                    <?php if($authRuleRow["islink"] == 0): ?><span class="label label-danger">否</span>
                                                    <?php else: ?>
                                                    <span class="label label-success">是</span><?php endif; ?>
                                                </td>
                                                <td><input type="text" class="input-sort input-ajax-submit" data-ajax="<?php echo U('/Admin/Menu/sort',array('id'=>$authRuleRow['id']));?>" value="<?php echo ($authRuleRow["sort"]); ?>" /></td>
                                                <td>
                                                    <a href="<?php echo U('/Admin/Menu/edit',array('id'=>$authRuleRow['id']));?>" class="btn btn-sm btn-info">修改</a>                                
                                                    <a href="javascript:void(0);" data-ajax="<?php echo U('/Admin/Menu/destroy',array('id'=>$authRuleRow['id']));?>" class="btn btn-sm btn-danger ajax-destroy">删除</a>
                                                </td>
                                            </tr><?php endforeach; endif; else: echo "" ;endif; endif; endforeach; endif; else: echo "" ;endif; ?>
                                    </tbody>
                                </table>
                            </div>

                            <div class="col-sm-12">
                                <button class="btn btn-info" type="submit">删除</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
</div>


    </div>
</body>
</html>