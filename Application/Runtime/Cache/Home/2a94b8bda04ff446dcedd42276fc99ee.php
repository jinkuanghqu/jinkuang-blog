<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <?php echo W('Common/tdk',[$tdk]);?>
    <meta name="HandheldFriendly" content="True" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <link rel="shortcut icon" href="./favicon.ico" type="image/x-icon" />
    <link rel="stylesheet" href="/Public/blogstyle/css/bootstrap.min.css" />
    <link rel="stylesheet" href="/Public/blogstyle/css/font-awesome.min.css" />
    <link rel="stylesheet" href="/Public/blogstyle/css/vs.min.css" />
    <link rel="stylesheet" type="text/css" href="/Public/blogstyle/css/screen.min.css" />
    <script src="/Public/blogstyle/js/jquery.fitvids.min.js"></script>
    
    <script type="text/javascript" src="/Public/blogstyle/js/embed_pc_V1.js"></script>
</head>
<body class="home-template">
    <header class="main-header" style="background-image: url(/Public/images/logo_bg.jpg);">
        <div class="container">
            <div class="row">
                <div class="col-xs-12">
                    <h1><span class="hide">JinKuang - </span>学习笔记</h1>
                    <h2 class="hide"></h2>
                </div>
            </div>
        </div>
    </header>
    <?php echo W('Top/topMenu');?> 

	<section class="content-wrap">
    <div class="container">
        <div class="row">
            <main class="col-md-8 main-content">
                <?php if(is_array($articleData)): $i = 0; $__LIST__ = $articleData;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><article class="post">
                        <div class="post-head">
                            <h1 class="post-title"><a href="<?php echo U('home/article/details/id/'.$vo['id']);?>" style="text-decoration: underline;"><?php echo ($vo["title"]); ?></a></h1>
                            <div class="post-meta">
                                <span class="author">作者：<a href="<?php echo U('home/about/author');?>" target="_blank"><?php echo ($vo["author"]); ?></a></span> • 
                                <time class="post-date" datetime="2017年02月20日" title="2017年02月20日"><?php echo (date("Y-m-d",$vo["add_time"])); ?></time>
                            </div>
                        </div>
                        <?php if(($vo["thumbnail"]) != ""): ?><div class="featured-media">
                                <a href="<?php echo U('home/article/details/id/'.$vo['id']);?>"><img src="<?php echo ($vo["thumbnail"]); ?>" style="max-height:260px;" alt="<?php echo ($vo["title"]); ?>"/></a>
                            </div><?php endif; ?>
                        <div class="post-content">
                            <p><?php echo ($vo["description"]); ?></p>
                        </div>
                        <div class="post-permalink">
                            <a href="<?php echo U('home/article/details/id/'.$vo['id']);?>" class="btn btn-default">阅读全文</a>
                        </div>
                        <footer class="post-footer clearfix"></footer>
                    </article><?php endforeach; endif; else: echo "" ;endif; ?>
            </main>
            <aside class="col-md-4 sidebar">
                <?php echo W('RightSiderArticle/newArticle');?>
            </aside>
        </div>
    </div>
</section>

    <footer class="main-footer">
        <div class="container">
            <div class="row">
            </div>
        </div>
    </footer>
    <div class="copyright">
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <span>Copyright &copy; <a href="/">金矿个人博客</a></span> | 
                    <span><a href="http://www.miibeian.gov.cn/" target="_blank">粤ICP备17097173号-1</a></span> | 
                    <span></span>
                </div>
            </div>
        </div>
    </div>
    <a href="/#" id="back-to-top" style="display: none;"><i class="fa fa-angle-up"></i></a>
    <script src="/Public/blogstyle/js/jquery.min.js"></script>
    <script src="/Public/blogstyle/js/bootstrap.min.js"></script>
    <script src="/Public/blogstyle/js/jquery.fitvids.min1.js"></script>
    <script src="/Public/blogstyle/js/highlight.min.js"></script>
    <script src="/Public/blogstyle/js/main.min.js"></script>
    <script type="text/javascript">var _bdhmProtocol = (("https:" == document.location.protocol) ? " https://" : " http://");
    document.write(unescape("%3Cscript src='" + _bdhmProtocol + "hm.baidu.com/h.js%3Fc8d13872a523d9c286aa7affbe0921f1' type='text/javascript'%3E%3C/script%3E"));</script>
    <script src="/Public/blogstyle/js/h.js" type="text/javascript"></script>
</body>
</html>