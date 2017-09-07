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


    <body>
        <!-- header  -->
        <div class="viewFramework-topbar ng-scope">
    <!-- topbar -->
        <div class="aliyun-console-topbar ng-isolate-scope">
        <div class="topbar-wrap topbar-clearfix">
            <div class="topbar-head topbar-left"><a href="javasrcipt:;" onclick="mainFrame.window.location.reload();" class="topbar-logo topbar-left"><span class="icon-oas-2"></span></a> <a href="/admin/index/index" target="_self" class="topbar-home-link topbar-btn topbar-left" ><span class="ng-binding">管理控制台</span></a>
            </div>
            
            <div class="topbar-info topbar-right topbar-clearfix">
               
              <!--  <div class="dropdown topbar-notice topbar-btn topbar-left ng-scope" ><a href="javasrcipt:;" class="dropdown-toggle topbar-btn-notice"><span class="topbar-btn-notice-icon icon-bell"></span> <span class="topbar-btn-notice-num ng-binding">1</span></a>
                    <div class="topbar-notice-panel">
                        <div class="topbar-notice-arrow"></div>
                        <div class="topbar-notice-head"><span class="ng-binding">站内消息通知</span></div>
                        <div class="topbar-notice-body">                            
                            <ul class="ng-scope">                                
                                <li class="ng-scope">
                                    <a target="_blank" class="clearfix" href="javasrcipt:;"><span class="inline-block"><span class="topbar-notice-link ng-binding">【重要通知】阿里云推荐码限量开放申请中，分享9折推荐码，获10%返利！</span> <span class="topbar-notice-time ng-binding">2015-10-23</span></span>
                                        <span class="inline-block topbar-notice-class ng-scope"><span class="topbar-notice-class-name ng-binding">优惠活动</span></span>
                                        
                                    </a>
                                </li>
                               end ngRepeat: item in messages.messageList 
                            </ul>
                            end ngIf: messages && messages.messageList.length >0 
                             ngIf: !messages ||  messages.messageList.length == 0 
                        </div>
                        <div class="topbar-notice-foot"><a class="topbar-notice-more ng-binding" target="_blank" ng-href="#" aliyun-console-spm="401" data-spm-click="gostr=/aliyun;locaid=d401;;" href="javasrcipt:;">查看更多</a></div>
                    </div>
                </div>-->
                
                <div class="topbar-left topbar-accesskeys topbar-info-item ng-scope">
                    <a href="javasrcipt:;" data-url="<?php echo U('Admin/Index/clearCache');?>" class="topbar-btn ng-binding delete">清理缓存</a>
                </div>
          
                <div class="topbar-left ng-scope">
                    <div id='loginOut' class="dropdown topbar-info-item">
                        <a href="javasrcipt:;" class="dropdown-toggle topbar-btn">
                            <span class="ng-binding"><?php echo ($adminRow["name"]); ?>[<?php echo ($adminRow["email"]); ?>]</span>
                            <span class="icon-arrow-down"></span>
                        </a>
                        <ul class="dropdown-menu">
                            <!-- ngRepeat: link in navLinks.user.links -->
                            <li class="topbar-info-btn ng-scope">
                                <a href="<?php echo U('admin/default/logout');?>" target="_self"><span class="ng-binding">退出</span></a>
                            </li>
                            <!-- end ngRepeat: link in navLinks.user.links -->
                        </ul>
                    </div>
                </div>
                <!-- end ngIf: navLinks.user.show -->
                <!-- 国际化 -->
                <!-- ngIf: navLinks.i18n.show && navLinks.i18n.currentLanguage -->
            </div>
            <!-- ngIf: navLinks.assist.show && helpConfig -->
        </div>
    </div>
    <!-- /topbar -->
</div>
        <!-- /header  -->

        <div id='sidebar-left' class="viewFramework-body viewFramework-sidebar-full">
                <div class="viewFramework-sidebar">
        <!-- sidebar -->
        <script type="text/javascript">
        //document.oncontextmenu=function(){ return false }
        </script>
    <div id="sidebar" product-id="account" class="sidebar-content">
        <!-- ngIf: !loadingState -->
        <div class="sidebar-inner ng-scope">
            <!-- ngIf: versionGreaterThan1_3_21 -->
            <div id='sidebar-icon' class="sidebar-fold ng-scope icon-unfold"></div>
            <!-- end ngIf: versionGreaterThan1_3_21 -->
            <div class="sidebar-nav">
                <ul class="entrance-nav sidebar-trans" id="nav" style="height:auto;">
                <?php if($menuList){ foreach($menuList as $model){ ?>
                        <li class="nav-item ng-scope">
                            <?php if(empty($model['child'])): ?><a href="<?php echo ($model["name"]); ?>" data-submenu="<?php echo ($model["id"]); ?>" data-count="4" class="ng-scope">
                                <div class="nav-icon"><span class="<?php echo ($model["icon"]); ?>"></span></div><span class="nav-title ng-binding"><?php echo ($model["title"]); ?></span>
                            </a>
                            <?php else: ?>
                            <a href="" data-submenu="<?php echo ($model["id"]); ?>" data-count="4" class="ng-scope">
                                <div class="nav-icon"><span class="<?php echo ($model["icon"]); ?>"></span></div><span class="nav-title ng-binding"><?php echo ($model["title"]); ?></span>
                            </a><?php endif; ?>
                        </li>
                <?php }} ?>
                </ul>

            </div>

            <div id="time" class="time">
                <font id="today"></font>
            </div>
        </div>
        <!-- end ngIf: !loadingState -->
    <!-- ngIf: loadingState -->
    </div>
        <!-- /sidebar -->
    </div>


            <div id='sidebal-middle' class="viewFramework-product">

                                <div class="viewFramework-product-navbar ng-scope hidden">
                    <!-- product nav -->
                    <div class="product-nav-stage ng-scope product-nav-stage-main">
                        <div class="product-nav-scene product-nav-main-scene">
                            <div class="product-nav-title ng-binding">商品模块</div>
                            <!-- 自定义内容插入点，比如商标、logo -->
                            <div customized-content="" class="ng-isolate-scope"></div>
                            <div class="product-nav-list" id="product-nav-list">
                                <?php if($menuList){ foreach($menuList as $model){ ?>
                                        <ul class="submenu-<?php echo ($model["id"]); ?> hidden" data-submenu="<?php echo ($model["id"]); ?>">
                                            <?php if(!empty($model['child'])){ foreach($model['child'] as $action){ ?>
                                                        <li>
                                                            <div class="ng-isolate-scope">
                                                                <a href="javascript:;" data-url="<?php echo ($action["name"]); ?>" class="ng-scope">
                                                                    <div class="nav-icon"></div>
                                                                    <div class="nav-title ng-binding"><?php echo ($action["title"]); ?></div>
                                                                </a>
                                                            </div>
                                                        </li>
                                            <?php }} ?>
                                        </ul>
                                <?php }} ?>
                            </div>

                        </div>
                    </div>


                    <!-- /product nav -->
                </div>
                <div class="viewFramework-product-navbar-collapse ng-scope hidden">
                    <div class="product-navbar-collapse-inner">
                        <div class="product-navbar-collapse-bg"></div>
                        <div id='icon-left' class="product-navbar-collapse">
                            <span class="icon-collapse-left"></span>
                            <span class="icon-collapse-right"></span>
                        </div>
                    </div>
                </div>


                <div class="viewFramework-product-body" id="mainFrameBody" >
                    <!-- product body -->
                    <iframe id="mainFrame" name="mainFrame" frameborder="0" src="<?php echo U('Admin/Index/main');?>" width="100%" height="99%" ></iframe>
                    <!-- /product body -->
                </div>
                <div class="copy">
                    <span class="line_white"></span>POWERED BY MingYang V1.0.0.151228 版权所有 © 2013-2015 北京铭扬致远科技有限公司，并保留所有权利。
                </div>
            </div>
        </div>

    </body>
</html>