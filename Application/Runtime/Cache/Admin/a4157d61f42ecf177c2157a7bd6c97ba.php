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



<body class="iframe-body" data-sid="<?php echo ($adminRow["id"]); ?>" data-settime="<?php echo (C("IFRAME_BODY_SETTIME")); ?>" data-actionurl="/Admin/ArticleCategory/index">
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
                        &nbsp;分类管理
                    </a>
                </h4>
            </div>    
  
            <div class="panel-collapse collapse in">
                
                
                <div class="panel-body">
                  <div class="row panel-scroll">  
                  
                        <div class="col-sm-12">
                        <a href="<?php echo U('/Admin/ArticleCategory/add');?>" class="btn btn-success">添加</a>
                        </div>  
                     <div class="col-sm-12">
                         <table class="table table-hover">
                             <thead>
                                <tr>
                                    <?php echo ($th_str); ?>
                                </tr>
                            </thead>
                            <tbody>
                            <?php if(is_array($result)): foreach($result as $key=>$vo): ?><tr class="ng-scope">

                                       <td>
                                            <div class="center"><span class="ng-binding"><?php echo ($vo["id"]); ?></span></div>
                                        </td>
                                        <td class="text-left">
                                            <div class="center" ><span class="ng-binding"><?php echo ($vo["name"]); ?></span></div>
                                        </td>
                                        <td>
                                            <div class="center"><span class="ng-binding"><?php echo ($vo["keywords"]); ?></span></div>
                                        </td>
                                        <td>
                                            <div class="center"><span class="ng-binding"><?php echo ($vo["description"]); ?></span></div>
                                        </td>                                    
                                     
                                        <td>
                                            <div class="center">
                                            <input name="sort" style="width:60px;" class="inputorder" type="number" value="<?php echo ($vo["sort"]); ?>" val="<?php echo ($vo["id"]); ?>" />                                
                                            </div>
                                        </td>
                                        <td>
                                            <div class="left">
                                                <a href="/Admin/ArticleCategory/edit?id=<?php echo ($vo["id"]); ?>" class="btn btn-sm btn-info">修改</a>
                                                <a class="btn btn-sm btn-info" id="del" onclick="del(<?php echo ($vo["id"]); ?>)">删除</a>        
                                            </div>
                                        </td>
                                    </tr><?php endforeach; endif; ?>		
                             </tbody>
                         </table>
                     </div>
                  </div>  
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
     
    function del(id){
    
        if (confirm("确认要删除？")) {
        
            $.post("<?php echo U('/admin/ArticleCategory/delete');?>",{id:id},function(result){
                if(result == 2){
                    alert('删除失败,此分类下有子类，请先删除子类');
                }
                if(result == 1){
                    window.location.href = "/admin/ArticleCategory/index"; 
                }
                if(result === 0){
                    alert('删除分类失败！');                
                }
            });  
        }  
    }

    $(function(){
        $(".inputorder").change(function(){
            var id = $(this).attr('val');
            var sort = $(this).val();
            if(sort && id){
                $.post("<?php echo U('/admin/ArticleCategory/updateSort');?>",{id:id,sort:sort},function(data){
                    if(data==1){
                        window.location.reload(true);
                    }
                });
            }
        })
    })           
    
    
</script>

    </div>
</body>
</html>