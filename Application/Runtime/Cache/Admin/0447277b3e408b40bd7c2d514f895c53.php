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



<body class="iframe-body" data-sid="<?php echo ($adminRow["id"]); ?>" data-settime="<?php echo (C("IFRAME_BODY_SETTIME")); ?>" data-actionurl="/Admin/Flash/index">
    <div class="console-container ng-scope">
        <div class="console-title console-title-border drds-detail-title clearfix">
            <!-- position  -->
            <ol class="breadcrumb">
              <li><i class="icon-home"></i> <a href="/Admin/Index/main">首页</a></li>
              <?php if(is_array($breadcrumbRows)): $i = 0; $__LIST__ = $breadcrumbRows;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li><a href="/Admin/<?php echo ($vo["name"]); ?>"><?php echo ($vo["title"]); ?></a></li><?php endforeach; endif; else: echo "" ;endif; ?>
            </ol>
            <!-- /position  -->
        </div>
        <script src="/Public/adminTpl/js/viewBigImage.js"></script>

<div class="panel-group">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a class="accordion-toggle" data-toggle="collapse">
                    <i class="bigger-110 icon-down"></i>
                    焦点图列表
                </a>
            </h4>
        </div>
        <div class="panel-collapse collapse in">
            <div class="panel-body">
                <div><a href="javascript:void(0);" onclick="add()" class="btn btn-success" style="margin-left:5px">添加</a></div>

                <table class="table table-hover" style="margin-left:5px !important">
                    <thead>
                        <tr>
                            <td>序号</td>
                            <?php if(is_array($show_fields)): $i = 0; $__LIST__ = $show_fields;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?><th class="center"><?php echo ($item["title"]); ?></th><?php endforeach; endif; else: echo "" ;endif; ?>

                            <th>操作</th>
                        </tr>
                    </thead>
                    <tbody>
                            <?php if(is_array($flash_list)): $i = 0; $__LIST__ = $flash_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?><tr class="flash" picture="<?php echo (substr($item['picture'],1)); ?>">
                                    <td><?php echo ($key+1); ?></td>
                                    <?php if(is_array($show_fields)): $i = 0; $__LIST__ = $show_fields;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$sfield): $mod = ($i % 2 );++$i;?><td>
                                            <div class="center"><span class="ng-binding">
                                            <?php if(empty($sfield['ofilter'])): echo ($item[$key]); ?> <?php else: ?>
                                            <?php echo call_user_func($sfield['ofilter'],$item[$key]); endif; ?>
                                            </span></div>
                                        </td><?php endforeach; endif; else: echo "" ;endif; ?>
                                    <td>
                                     <div class="left">
                                        <span><a class="btn btn-sm btn-primary" href="javascript:void(0)"  onclick="edit(<?php echo ($item['id']); ?>)">编辑</a></span>
                                        <span><a class="btn btn-sm btn-danger" href="javascript:void(0)"  onclick="drop(<?php echo ($item['id']); ?>)">删除</a></span>
                                    </div>
                                 </td>
                                </tr><?php endforeach; endif; else: echo "" ;endif; ?>
                    </tbody>
                </table>
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


<style>
    td{
        overflow:hidden;white-space:nowrap;text-overflow:ellipsis;
    }
</style>

<script>
    $(document).ready(function(){

        $("tr.flash>td:nth-child(2)").viewBigImage(function(ele){
            return ele.parent().attr('picture');
        });

    });
</script>

<script language="javascript">

function edit(grade_id)
{
    $.get("<?php echo U('/Admin/Flash/edit');?>", {'id': grade_id}, function(data, status){
        if(true){
            var d = dialog({
                title: '编辑焦点图',
                content: data,
                width: 600,
                drag: true,
            });
            d.show();
        }
    });
} 

function add()
{
    $.get("<?php echo U('/Admin/Flash/add');?>", function(data, status){
        if(true){
            var d = dialog({
                title: '添加焦点图',
                content: data,
                width: 600,
                drag: true,
            });
            d.show();
        }
    });
}

function drop(flash_id){
    $.post("<?php echo U('/Admin/Flash/drop');?>", {'id': flash_id}, function(obj){
        if(obj.status){
            parent.layer.open({
                type: 1,
                title: false,
                closeBtn: 0, //不显示关闭按钮
                scrollbar: false,
                shade: 0,
                time: 1000, //2秒后自动关闭
                offset: '55px',
                shift: 5,
                content: '<div class="HTooltip bounceInDown animated" style="width:350px;padding:7px;text-align:center;position:fixed;right:7px;background-color:#5cb85c;color:#fff;z-index:100001;box-shadow:1px 1px 5px #333;-webkit-box-shadow:1px 1px 5px #333;font-size:14px;">'+obj.info+'</div>',
            });
            location.reload();
        } else {
            parent.layer.open({
                type: 1,
                title: false,
                closeBtn: 0, //不显示关闭按钮
                scrollbar: false,
                shade: 0,
                time: 2000, //2秒后自动关闭
                offset: ['55px','100%'],
                shift: 6,
                content: '<div class="HTooltip bounceInDown animated" style="width:350px;padding:7px;text-align:center;position:fixed;right:7px;background-color:#D84C31;color:#fff;z-index:100001;box-shadow:1px 1px 5px #333;-webkit-box-shadow:1px 1px 5px #333;font-size:14px;">'+obj.info+'</div>',
            });
        }
    });
}
</script>

    </div>
</body>
</html>