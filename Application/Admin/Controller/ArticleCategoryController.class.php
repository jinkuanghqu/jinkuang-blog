<?php

/**
*  文章分类控制器
* @author simon 2016-03-01
*
*/

namespace Admin\Controller;
use Common\Controller\AdminController;

class ArticleCategoryController extends AdminController
{
    private $model;
    private $category_fields = array(
    
        'id'    => '分类ID',
        'title' => '分类名称',
        'keywords' => '关键词',
        'description' => '栏目描述',        
        'sort'  => '排序',
        'operation' => '操作'
    );
   

    public function __construct()
    {
        
        parent::__construct();
        $this->model = D('ArticleCategory');;
        
    }
    
    public function _initialize()
    {
        parent::_initialize();
        
    }
    
    //分类管理
    public function index()
    {
		$category = $this->model->order('sort asc')->select();
        $result = $this->tree($category, 0, 0);
        
        $th_str = "";
        foreach($this->category_fields as $value){
            $th_str .= "<th class='center'>{$value}</th>";
        }
                
        $td_str = "";
        $str = "";
        foreach($result as $key=>$value){
            $string = "┗━";
            $str = str_repeat("&nbsp;&nbsp;",$value['deep']);
            if($value['deep'] === 0){
                $string = "";
            }
            $result[$key]['name'] = "{$str}"."$string"."{$value['name']}";
            
        }
        

        $this->assign('result',$result);                
        $this->assign('th_str',$th_str);        
        $this->assign('td_str',$td_str);
        $this->display();
               
    }

    //添加分类
    public function add()
    {
    
		$category = $this->model->field('id,pid,name')->order('sort asc')->select();        
        
        $result = $this->tree($category, 0, 0);

        $options = "";
        $str = "";
        foreach($result as $key=>$value){
            $string = "┗━";
            $str = str_repeat("&nbsp;&nbsp;",$value['deep']);
            if($value['deep'] === 0){
                $string = "";
            }
            $options .= "<option value=".$value['id'].">{$str}"."$string"."{$value['name']}</option>";
            
        }
        
        if (IS_AJAX) {
            
            $return_data = array(
                'status' => 0,
                'info'   => '添加失败',
            );              
           
            if($_POST['name']){
                
                $log_str = '新增文章分类';
                addAdminLog($log_str);
                $data = array(
                    'pid'  => $_POST['pid'],
                    'name' => $_POST['name'],
                    'keywords' => $_POST['keywords'],
                    'description' => $_POST['description'],
                    'sort' => $_POST['sort'],
                );
                
                $ret = $this->model->add($data);
                if($ret){
                    
                    $return_data = array(
                        'status' => 1,
                        'info'   => '添加成功',
                    );  
                }
           
            }
            
            $this->ajaxReturn($return_data);exit;            
            
            
        } 
        $this->assign('options',$options);
        $this->display();          
           
    }
    
    public function tree(&$list, $parent_id, $deep) {
        static $tree;
        foreach($list as $row) {
            if($row['pid'] == $parent_id) {
                $row['deep'] = $deep;
                $tree[] = $row;
                $this->tree($list, $row['id'],$deep+1);
            }
        }

        return $tree;
    }


    
    //编辑分类
    public function edit()
    {
        if(IS.GET){
            
            $id = I('get.id', 0, 'intval');
            $cat_ret = $this->model->find($id);
            // echo "<pre>";
            // print_r($cat_ret);die;
            if($cat_ret == null){
                
                echo "<pre>未找到此分类！</pre>";die();
                
            }else{
                
                $category = $this->model->field('id,pid,name')->order('sort asc')->select();        
                
                $result = $this->tree($category, 0, 0);

                $options = "";
                $str = "";
                foreach($result as $key=>$value){
                    $string = "┗━";
                    $select = "";
                    $str = str_repeat("&nbsp;&nbsp;",$value['deep']);
                    if($value['deep'] === 0){
                        $string = "";
                    }
                    if($value['id'] == $id){
                        $select = " selected ";
                    }
                    $options .= "<option value=".$value['id'].$select.">{$str}"."$string"."{$value['name']}</option>";
                    
                }                
                
                $this->assign("options",$options);
                $this->assign("cat_ret",$cat_ret);
                $this->display();                    
                
            }
        
            
        }

        
    }


    //更新分类
    
    public function update()
    {

        $log_str = '更新文章分类，分类ID为:'.$_POST['id'];
        addAdminLog($log_str);  
        
        if(IS_AJAX){
            
            $return_data = array(
                'status' => 0,
                'info' => '修改失败'
            );
            
            if($_POST['id']){
                                       
                $data = array(
                    'name' => $_POST['name'],
                    'sort' => $_POST['sort'],
                    'keywords' => $_POST['keywords'],
                    'description' => $_POST['description'],
                    'is_system' => $_POST['is_system'],
                    'is_hide' => $_POST['is_hide'],
               
                );

                if($_POST['id'] != $_POST['pid']){
                    
                    $data['pid'] = $_POST['pid'];
                    
                }            

                $ret = $this->model->where("id={$_POST['id']}")->save($data);

                if($ret){
                    
                    $return_data = array(
                        'status' => 1,
                        'info' => '修改成功'
                    );                    
                }
            }

            $this->ajaxReturn($return_data);exit;             
            
        }                
        
    }    

    //删除分类
    
    public function delete()
    {
                 
        $id = $_POST['id'];
        $log_str = '删除文章分类，分类ID为：'.$id;
        addAdminLog($log_str);           
        $count = $this->model->where("pid=$id")->count();
        if($count>0){
            echo 2;
        }else{
            
            $ret = $this->model->where("id=$id")->delete();
            if($ret){

                echo 1;
                
            }else{
                echo 0;
            }
        }
        
    }
    
    //更新排序
    public function updateSort()
    {
        
        if($_POST['id'] && is_numeric($_POST['sort'])){
            
            $data = array(
                'sort' => $_POST['sort']
            );
            
            $ret = $this->model->where("id=".$_POST['id'])->save($data);
            if($ret){
                echo  1;
            }
        }else{
            
            echo 0;
            
        }        
        
    }
    
    
    
    
}


?>
