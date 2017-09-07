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



<body class="iframe-body" data-sid="<?php echo ($adminRow["id"]); ?>" data-settime="<?php echo (C("IFRAME_BODY_SETTIME")); ?>" data-actionurl="/Admin/Enroll/collegeList">
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
                        &nbsp;学院列表
                    </a>
                </h4>
            </div>

            <div class="panel-collapse collapse in">
                <div class="panel-body">
                    <div class="row panel-scroll">

                            <div class="col-sm-12">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th  class="center">学院ID</th>
                                            <th  class="center">学院名称</th>
                                            <th class="center">排序</th>
                                            <th class="center">操作</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if(is_array($collegeData)): $i = 0; $__LIST__ = $collegeData;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$collegeData): $mod = ($i % 2 );++$i;?><tr class="info">
                                            <td><?php echo ($collegeData["id"]); ?></input></td>
                                            <td><?php echo ($collegeData["name"]); ?></td>
                                            <td><?php echo ($collegeData["sort"]); ?></td>
                                            <td>
                                                <a href="<?php echo U('/Admin/Enroll/editCollege',array('id'=>$adminRow['id']));?>" class="btn btn-sm btn-info">修改</a>                                
                                                <!-- <a href="javascript:void(0);" data-ajax="<?php echo U('/Admin/Enroll/delCollege',array('id'=>$collegeData['id']));?>" class="btn btn-sm btn-danger ajax-destroy">删除</a> -->
                                            </td>
                                        </tr><?php endforeach; endif; else: echo "" ;endif; ?>
                                    </tbody>
                                </table>
                            </div>
                    </div>
                    <div class="row">
    <div class="col-sm-4">
        <p class="text-primary">每页 <kbd><?php echo ($pages["listRows"]); ?></kbd> 条, 共 <kbd><?php echo ($pages["totalRows"]); ?></kbd> 条记录。当前第 <kbd><?php echo ($pages["now"]); ?></kbd> 页</p>
    </div>
    <?php if(!empty($pages['url'])): ?><div class="col-sm-8 text-right">
        <nav>
          <ul class="pagination">

          <?php if(empty($pages['prev'])): ?><li class="disabled">
              <a href="javascript:void(0);" aria-label="Previous">
                <span aria-hidden="true">&laquo;</span>
              </a>
            </li>
          <?php else: ?>
            <li>
              <a href="<?php echo ($pages['prev']); ?>" aria-label="Previous">
                <span aria-hidden="true">&laquo;</span>
              </a>
            </li><?php endif; ?>

            <?php if(is_array($pages['url'])): foreach($pages['url'] as $k=>$vo): if(empty($vo)): ?><li class="active"><a href="javascript:void(0);"><?php echo ($k); ?></a></li>
             <?php else: ?>
                <li><a href="<?php echo ($vo); ?>"><?php echo ($k); ?></a></li><?php endif; endforeach; endif; ?>
           
            
          <?php if(empty($pages['next'])): ?><li class="disabled">
              <a href="javascript:void(0);" aria-label="Next">
                <span aria-hidden="true">&raquo;</span>
              </a>
            </li>
          <?php else: ?>
            <li>
              <a href="<?php echo ($pages['next']); ?>" aria-label="Next">
                <span aria-hidden="true">&raquo;</span>
              </a>
            </li><?php endif; ?>
          </ul>
        </nav>
    </div><?php endif; ?>
</div> 
                </div>
            </div>
        </div>
    </div>

</div>

    </div>
</body>
</html>