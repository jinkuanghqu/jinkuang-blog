<?php


namespace Common\Model;

use Think\Model\RelationModel;

class ArticleModel extends RelationModel
{
    //文章
    protected $_link = array(
        'Article' => array(
            'mapping_type'  => self::BELONGS_TO,
            'class_name'    => 'Article',
            'mapping_name'  => 'Article',
        ),
    );
    
    public function getArtLimit($page,$page_size=10)
    {

        return $this->order('add_time desc')->page($page . ',' . $page_size)->select();
        
    }

    
    public function getArticle($condition,$limit)
    {
        
        return $this->field("id,c_id,title,content,link")->where($condition)->order("sort asc")->limit($limit)->select();
        
    }
    
    public function getFaqLimit($title,$condition,$page,$page_size=10)
    {
        return $this->field("title,content")->where($condition)->join("article_category as ac on article.c_id=ac.id and ac.name='$title'")
        ->order("article.sort asc")->page($page . ',' . $page_size)->select();;
        
    }
    
    public function getFaqcount($title)
    {
        return $this->join("article_category as ac on article.c_id=ac.id and ac.name='$title'")->count();;
        
    }    

}
