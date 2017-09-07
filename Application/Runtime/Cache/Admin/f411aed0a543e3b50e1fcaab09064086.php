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



<body class="iframe-body" data-sid="<?php echo ($adminRow["id"]); ?>" data-settime="<?php echo (C("IFRAME_BODY_SETTIME")); ?>" data-actionurl="/Admin/Index/main.html">
    <div class="console-container ng-scope">
        <div class="console-title console-title-border drds-detail-title clearfix">
            <!-- position  -->
            <ol class="breadcrumb">
              <li><i class="icon-home"></i> <a href="/Admin/Index/main">首页</a></li>
              <?php if(is_array($breadcrumbRows)): $i = 0; $__LIST__ = $breadcrumbRows;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li><a href="/Admin/<?php echo ($vo["name"]); ?>"><?php echo ($vo["title"]); ?></a></li><?php endforeach; endif; else: echo "" ;endif; ?>
            </ol>
            <!-- /position  -->
        </div>
        <div class="console-container ng-scope">
    <div class="ng-scope">
        <!--main table-->
        <div class="row">
            <div class="col-sm-9">
                <div class="widget-group">
                    <div class="widget-box">
                        <div class="widget-header">
                            <h4 class="widget-title">
                                <label>
                                    <input name="rules[]" class="father input-icheck" type="checkbox" value="2">
                                    <span class="lbl"> 系统设置</span>
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
                                <label class="col-xs-2">
                                    <input name="rules[]" class="children input-icheck" type="checkbox" value="58">
                                    <span class="lbl"> 自定义变量</span>
                                </label>                                
                            </div>
                        </div>                                                  
                    </div>
                </div>

                <div class="panel-group">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                搜索样式
                            </h4>
                        </div>

                        <div class="panel-collapse collapse in">
                            <div class="panel-body">
                                <form class="form-inline">
                                  <div class="form-group">
                                    <label class="sr-only" for="exampleInputEmail3">商品名称</label>
                                    <input type="email" class="form-control" id="exampleInputEmail3" placeholder="商品名称">
                                  </div>

                                  <div class="form-group">
                                    <label class="sr-only" for="exampleInputEmail3">XXXXX</label>
                                    <input type="email" class="form-control" id="exampleInputEmail3" placeholder="商品名称">
                                  </div>                          

                                  <div class="form-group">
                                    <label class="sr-only" for="exampleInputPassword3">商品分类</label>
                                    <select class="form-control">
                                        <option value="-1">*所有商家</option>
                                        <option value="E0423">深圳汉光电子技术有限公司1</option>
                                        <option value="E0400">买家分销公司</option>
                                    </select>
                                  </div>         
                                  <button type="submit" class="btn btn-info">Sign in</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>                                       
            </div>

            <div class="panel-group col-sm-3">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a class="accordion-toggle" data-toggle="collapse">
                                <i class="bigger-110 icon-down"></i>
                                &nbsp;站点信息
                            </a>
                        </h4>
                    </div>

                    <div class="panel-collapse collapse in">
                        <div class="panel-body">
                            <p>PHP版本：5.5.12，MySQL版本：5.6.17</p>
                            <p>服务器：Windows NT</p>
                            <p>PHP运行方式：apache2handler</p>
                            <p>服务器IP：127.0.0.1</p>
                            <p>程序版本：1.0.0&nbsp;&nbsp;<a href="javascript:;" id="update">检查更新</a>&nbsp;&nbsp;<span id="upmsg"></span></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="panel-group col-sm-3">
                <div class="panel panel-success">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a class="accordion-toggle" data-toggle="collapse">
                                <i class="bigger-110 icon-down"></i>
                                &nbsp;站点信息
                            </a>
                        </h4>
                    </div>

                    <div class="panel-collapse collapse in">
                        <div class="panel-body">
                            <p class="text-muted">Fusce dapibus, tellus ac cursus .</p>
                            <p class="text-primary">Nullam id dolor id nibh ultricies.</p>
                            <p class="text-success">Duis mollis, est non commodo ,.</p>
                            <p class="text-info">Maecenas sed diam eget risus varius.</p>
                            <p class="text-warning">Etiam porta sem malesuada .</p>
                            <p class="text-danger">Donec ullamcorper nulla non metus.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="panel-group col-sm-3">
                <div class="panel panel-warning">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a class="accordion-toggle" data-toggle="collapse">
                                <i class="bigger-110 icon-down"></i>
                                &nbsp;站点信息
                            </a>
                        </h4>
                    </div>

                    <div class="panel-collapse collapse in">
                        <div class="panel-body">
                            <p>PHP版本：5.5.12，MySQL版本：5.6.17</p>
                            <p>服务器：Windows NT</p>
                            <p>PHP运行方式：apache2handler</p>
                            <p>服务器IP：127.0.0.1</p>
                            <p>程序版本：1.0.0&nbsp;&nbsp;<a href="javascript:;" id="update">检查更新</a>&nbsp;&nbsp;<span id="upmsg"></span></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="panel-group col-sm-3">
                <div class="panel panel-danger">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a class="accordion-toggle" data-toggle="collapse">
                                <i class="bigger-110 icon-down"></i>
                                &nbsp;站点信息
                            </a>
                        </h4>
                    </div>

                    <div class="panel-collapse collapse in">
                        <div class="panel-body">
                            <p>PHP版本：5.5.12，MySQL版本：5.6.17</p>
                            <p>服务器：Windows NT</p>
                            <p>PHP运行方式：apache2handler</p>
                            <p>服务器IP：127.0.0.1</p>
                            <p>程序版本：1.0.0&nbsp;&nbsp;<a href="javascript:;" id="update">检查更新</a>&nbsp;&nbsp;<span id="upmsg"></span></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="panel-group col-sm-3">
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a class="accordion-toggle" data-toggle="collapse">
                                <i class="bigger-110 icon-down"></i>
                                &nbsp;站点信息
                            </a>
                        </h4>
                    </div>

                    <div class="panel-collapse collapse in">
                        <div class="panel-body">
                            <p>PHP版本：5.5.12，MySQL版本：5.6.17</p>
                            <p>服务器：Windows NT</p>
                            <p>PHP运行方式：apache2handler</p>
                            <p>服务器IP：127.0.0.1</p>
                            <p>程序版本：1.0.0&nbsp;&nbsp;<a href="javascript:;" id="update">检查更新</a>&nbsp;&nbsp;<span id="upmsg"></span></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="row">
            <div class="panel-group col-sm-4">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a class="accordion-toggle" data-toggle="collapse">
                                <i class="bigger-110 icon-down"></i>
                                &nbsp;表单样式
                            </a>
                        </h4>
                    </div>

                    <div class="panel-collapse collapse in">
                        <div class="panel-body">
                            <form class="form-horizontal">
                                <div class="form-group">
                                    <label for="inputEmail3" class="col-sm-3 control-label">用户名</label>
                                    <div class="col-sm-9">
                                        <input type="email" class="form-control" id="inputEmail3" placeholder="用户名">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputPassword3" class="col-sm-3 control-label">密码</label>
                                    <div class="col-sm-9">
                                        <input type="password" class="form-control" id="inputPassword3" placeholder="密码">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="exp" class="col-sm-3 control-label">文本框例子</label>
                                    <div class="col-sm-9">
                                        <textarea class="form-control" id="exp" rows="3"></textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="checkbox col-sm-offset-3 col-sm-9">
                                        <label>
                                            <input type="checkbox" class="input-icheck"> 记住我
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-offset-3 col-sm-9">
                                        <button type="submit" class="btn btn-primary">登录</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="panel-group col-sm-8">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a class="accordion-toggle" data-toggle="collapse">
                                <i class="bigger-110 icon-down"></i>
                                &nbsp;提醒样式
                            </a>
                        </h4>
                    </div>

                    <div class="panel-collapse collapse in">
                        <div class="panel-body">
                            <p class="alert alert-info">Nullam id dolor id nibh ultricies vehicula ut id elit.</p>        
                            <p class="alert alert-success">Duis mollis, est non commodo luctus, nisi erat porttitor ligula.</p>
                            <p class="alert alert-info">Maecenas sed diam eget risus varius blandit sit amet non magna.</p>
                            <p class="alert alert-warning">Etiam porta sem malesuada magna mollis euismod.</p>
                            <p class="alert alert-danger">Donec ullamcorper nulla non metus auctor fringilla.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="panel-group col-sm-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a class="accordion-toggle" data-toggle="collapse">
                                <i class="bigger-110 icon-down"></i>
                                &nbsp;列表样式
                            </a>
                        </h4>
                    </div>

                    <div class="panel-collapse collapse in">
                        <div class="panel-body">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th class="center">序号</th>                        
                                        <th class="center">名称</th>                                       
                                        <th class="center">排序</th>
                                        <th class="center">类型</th>
                                        <th class="center">状态</th>
                                        <th class="contact-th center">操作</th>
                                    </tr>
                                </thead>
                                <tbody>                              
                                    <tr class="active">                                        
                                        <td>1</td>
                                        <td>创新奇iNew-V1白色-4G-2500AM电池-超长待机！</td>
                                        <td>
                                            <input type="type" class="input-sort" type="text" value="255" />
                                        </td>
                                        <td>模块</td>
                                        <td>
                                            <span class="label label-success">启用</span>
                                        </td>
                                        <td>
                                            <a href="javascript:void(0);" class="btn btn-sm btn-success">分配</a>
                                            <a href="javascript:void(0);" class="btn btn-sm btn-info">查看</a>
                                            <a href="javascript:void(0);" class="btn btn-sm btn-warning">禁用</a>
                                            <a href="javascript:void(0);" class="btn btn-sm btn-danger">删除</a>
                                        </td>
                                    </tr>
                                    <tr class="success">                                       
                                        <td>2</td>
                                        <td>创新奇iNew-V1白色-4G-2500AM电池-超长待机！</td>
                                        <td>
                                            <input type="type" class="input-sort" type="text" value="255" />
                                        </td>
                                        <td>模块</td>
                                        <td>
                                            <span class="label label-danger">禁用</span>
                                        </td>
                                        <td>
                                            <a href="javascript:void(0);" class="btn btn-sm btn-success">分配</a>
                                            <a href="javascript:void(0);" class="btn btn-sm btn-info">查看</a>
                                            <a href="javascript:void(0);" class="btn btn-sm btn-warning">禁用</a>
                                            <a href="javascript:void(0);" class="btn btn-sm btn-danger">删除</a>
                                        </td>
                                    </tr>
                                    <tr class="warning">                                       
                                        <td>3</td>
                                        <td>创新奇iNew-V1白色-4G-2500AM电池-超长待机！</td>
                                        <td>
                                            <input type="type" class="input-sort" type="text" value="255" />
                                        </td>
                                        <td>模块</td>
                                        <td>
                                            <span class="label label-danger">禁用</span>
                                        </td>
                                        <td>
                                            <a href="javascript:void(0);" class="btn btn-sm btn-success">分配</a>
                                            <a href="javascript:void(0);" class="btn btn-sm btn-info">查看</a>
                                            <a href="javascript:void(0);" class="btn btn-sm btn-warning">禁用</a>
                                            <a href="javascript:void(0);" class="btn btn-sm btn-danger">删除</a>
                                        </td>
                                    </tr>
                                    <tr class="danger">                                        
                                        <td>4</td>
                                        <td>创新奇iNew-V1白色-4G-2500AM电池-超长待机！</td>
                                        <td>
                                            <input type="type" class="input-sort" type="text" value="255" />
                                        </td>
                                        <td>模块</td>
                                        <td>
                                            <span class="label label-success">启用</span>
                                        </td>
                                        <td>
                                            <a href="javascript:void(0);" class="btn btn-sm btn-success">分配</a>
                                            <a href="javascript:void(0);" class="btn btn-sm btn-info">查看</a>
                                            <a href="javascript:void(0);" class="btn btn-sm btn-warning">禁用</a>
                                            <a href="javascript:void(0);" class="btn btn-sm btn-danger">删除</a>
                                        </td>
                                    </tr>
                                    <tr class="info">                                        
                                        <td>5</td>
                                        <td>创新奇iNew-V1白色-4G-2500AM电池-超长待机！</td>
                                        <td>
                                            <input type="type" class="input-sort" type="text" value="255" />
                                        </td>
                                        <td>模块</td>
                                        <td>
                                            <span class="label label-success">启用</span>
                                        </td>
                                        <td>
                                            <a href="javascript:void(0);" class="btn btn-sm btn-success">分配</a>
                                            <a href="javascript:void(0);" class="btn btn-sm btn-info">查看</a>
                                            <a href="javascript:void(0);" class="btn btn-sm btn-warning">禁用</a>
                                            <a href="javascript:void(0);" class="btn btn-sm btn-danger">删除</a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            
                            <div class="row">
                                <div class="col-sm-4">
                                    <p class="text-primary">每页 <kbd>10</kbd> 条, 共 <kbd>220</kbd> 条记录。当前第 <kbd>4</kbd> 页</p>
                                </div>
                                <div class="col-sm-8 text-right">
                                    <nav>
                                      <ul class="pagination">
                                        <li class="disabled">
                                          <a href="#" aria-label="Previous">
                                            <span aria-hidden="true">&laquo;</span>
                                          </a>
                                        </li>
                                        <li><a href="#">1</a></li>
                                        <li><a href="#">2</a></li>
                                        <li><a href="#">3</a></li>
                                        <li class="active"><a href="#">4</a></li>
                                        <li><a href="#">5</a></li>
                                        <li>
                                          <a href="#" aria-label="Next">
                                            <span aria-hidden="true">&raquo;</span>
                                          </a>
                                        </li>
                                      </ul>
                                    </nav>
                                </div>
                            </div>                            
                            
                        </div>
                    </div>
                </div>
            </div>
            
        </div>

        <div class="row">
            <div class="panel-group col-sm-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a class="accordion-toggle" data-toggle="collapse">
                                <i class="bigger-110 icon-down"></i>
                                &nbsp;导航-标签页
                            </a>
                        </h4>
                    </div>

                    <div class="panel-collapse collapse in">
                        <div class="panel-body">
                            <ul class="nav nav-tabs">
                              <li role="presentation" class="active"><a href="#">发送站内信</a></li>
                              <li role="presentation"><a href="#">未读的站内信</a></li>
                              <li role="presentation"><a href="#">站内信列表</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            

            <div class="panel-group col-sm-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a class="accordion-toggle" data-toggle="collapse">
                                <i class="bigger-110 icon-down"></i>
                                &nbsp;导航-路径
                            </a>
                        </h4>
                    </div>

                    <div class="panel-collapse collapse in">
                        <div class="panel-body">
                            <ol class="breadcrumb">
                              <li><a href="#">首页</a></li>
                              <li><a href="#">分类页面</a></li>
                              <li class="active">分类列表页面</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="panel-group col-sm-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a class="accordion-toggle" data-toggle="collapse">
                                <i class="bigger-110 icon-down"></i>
                                &nbsp;文件上传
                            </a>
                        </h4>
                    </div>

                    <div class="panel-collapse collapse in">
                        <div class="panel-body">
                            <form class="form-horizontal" method="post" enctype="multipart/form-data">
                                <div class="form-group">
                                  <label for="inputTitle" class="col-sm-3 control-label">图片</label>
                                  <div class="col-sm-5">
                                    <div class="input-group">
                                      <textarea class="form-control input-file-text input-value-4-input" name="image"></textarea>
                                      <span class="input-group-btn">
                                        <input type="file" class="btn-input-file" data-ext="/(\.jpg|\.png|\.gif)$/" />
                                        <button class="btn btn-success" type="button">上传</button>
                                      </span>
                                    </div><!-- /input-group -->                                    
                                  </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-offset-3 col-sm-9">
                                        <button type="submit" class="btn btn-primary">提交</button>
                                    </div>
                                </div>
                            </form>

                            <code>
                            <pre>
php
// 判断
if (FileUpdateASDataURL::isDataURL($params['image'])) {
    $fileUpdateASDataURL = new FileUpdateASDataURL();
    //上传
    if (($fileName = $fileUpdateASDataURL->update($params['image'])) === false) {
        $error = $fileUpdateASDataURL->getError();
    }
}
                            </pre>
                            </code>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
    </div>
</body>
</html>