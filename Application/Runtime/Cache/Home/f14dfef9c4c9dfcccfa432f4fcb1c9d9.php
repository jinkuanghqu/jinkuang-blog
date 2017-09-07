<?php if (!defined('THINK_PATH')) exit();?><nav class="main-navigation">
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <div class="navbar-header">
                        <span class="nav-toggle-button collapsed" data-toggle="collapse" data-target="#main-menu" aria-expanded="false"><span class="sr-only">Toggle navigation</span> <i class="fa fa-bars"></i></span>
                    </div>
                    <!-- 编写分类菜单 -->
                    <div class="navbar-collapse collapse" id="main-menu" aria-expanded="false" style="height: 1px;">
                        <ul class="menu">
                            <li role="presentation">
                                <a href="/" title="首页">首页</a>
                            </li>
                            <?php if(is_array($topMenu)): $i = 0; $__LIST__ = $topMenu;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li role="presentation">
	                                <a href="<?php echo U('/home/category/cateList/id/'.$vo['id']);?>" title="<?php echo ($vo["name"]); ?>" target="_blank"><?php echo ($vo["name"]); ?></a>
	                            </li><?php endforeach; endif; else: echo "" ;endif; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </nav>