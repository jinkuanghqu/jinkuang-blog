<?php if (!defined('THINK_PATH')) exit(); if(!empty($newArticleData)): ?><div class="widget">
        <h4 class="title">最新文章</h4>
        <?php if(is_array($newArticleData)): $i = 0; $__LIST__ = $newArticleData;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><a href="<?php echo U('home/article/details/id/'.$vo['id']);?>" class="btn btn-default btn-block" target="_blank"><?php echo ($vo["title"]); ?></a><?php endforeach; endif; else: echo "" ;endif; ?>
    </div><?php endif; ?>