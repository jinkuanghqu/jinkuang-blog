<?php

/**
*  文章类目控制器
* @author simon 2016-03-01
*
*/

namespace Admin\Controller;
use Common\Controller\AdminController;
use Org\Util\FileUpdateASDataURL;

class ArticleController extends AdminController
{
    private $model;
    private $fields = array(
        'cat_name' => '所属分类',
        'title' => '文章标题',
        'time' => '发布时间',
        'sort'=> '排序',
        'action' => '操作',
    
    );
    private $page_num = 10;
    
    public function __construct()
    {
        
        parent::__construct();
        $this->model = D('Article');
        
    }
    
    public function _initialize()
    {
        parent::_initialize();
        
    }
    
    /**
    *文章管理
    *
    */
    
    public function index()
    {
        $category = D('ArticleCategory')->field('id,pid,name')->order('id asc')->select();
        $cat_array = array();
        if(count($category)>0){
            
            foreach($category as $key=>$value){
                
                $cat_array[$value['id']] = $value['name'];
            }
            
        }
        
        $th_str = "";
        foreach($this->fields as $value){
            $th_str .= "<th class='center'>{$value}</th>";
        }
        
        $cur_page = I('get.p', 1, 'intval');  
        $arts = $this->model->getArtLimit($cur_page,$this->page_num);          
        foreach($arts as $k=>$val){
            
            $arts[$k]['name'] = $cat_array[$val['c_id']];
            
        }

        // 分页
        $total_count = $this->model->count();
        $this->assign('page_note', '<span class="rows">每页' . $this->page_num . '条, 共 ' . $total_count . ' 条记录</span>');
        $Page       = new \Org\Util\Page($total_count, $this->page_num);
        $page_links = $Page->show();
        $this->assign('pages', $page_links);                
        
        $this->assign("arts",$arts);
        $this->assign("th_str",$th_str);
        $this->display();
        
        
    }
    
    
    /**
    *新增文章
    *
    */
    
    public function add()
    {

		$category = D("ArticleCategory")->field('id,pid,name')->order('sort asc')->select(); 
        // echo "<pre>";
        // print_r($category);die;
        if(count($category) <= 0){

            $this->redirect('/admin/ArticleCategory/add');        
            
        }
        $art = A("ArticleCategory");
        $result = $art->tree($category, 0, 0); 
        $options = "";
        $str = "";
        $msg = array();
        foreach($result as $key=>$value){
            $string = "┗━";
            $select = "";
            $str = str_repeat("&nbsp;&nbsp;",$value['deep']);
            if($value['deep'] === 0){
                $string = "";
            }
            if($_POST['cid'] === $value['id']){
                $select = " selected ";                
            }
            
            $options .= "<option value=".$value['id'].$select.">{$str}"."$string"."{$value['name']}</option>";
            
        }
        $this->assign('options',$options);
        
        if (IS_AJAX) {
    
            $return_data = array(
                'status' => 0,
                'info'   => '添加文章失败',
                );    

        
            if($_POST['cid'] && $_POST['title']){
                $log_str = '新增文章，标题为'.$_POST['title'];
                addAdminLog($log_str);            
                        
                if (FileUpdateASDataURL::isDataURL($_POST['file1'])) {
                    $fileUpdateASDataURL = new FileUpdateASDataURL();
                    //上传
                    if (($fileName = $fileUpdateASDataURL->update($_POST['file1'])) === false) {
                        $error = $fileUpdateASDataURL->getError();
                        $msg[] = "上传图片失败！";
                        // $return_data['info'] = "上传图片失败！";                               
                    }
                }                

                if(count($msg) <= 0){
                    
                    $data = array(
                        'c_id' => $_POST['cid'],
                        'author' => $_POST['author'],
                        'isdisplay' => $_POST['isdisplay'],
                        'title' => $_POST['title'],
                        'keywords' => $_POST['keywords'],
                        'description' => $_POST['description'],
                        'thumbnail' => $fileName,
                        'content' => $_POST['content'],
                        'link' => $_POST['link'],
                        'sort' => $_POST['sort'],                        
                        'add_time' => time(),
                        'clicks' => 0,             
                    );
                    
                    $result = $this->model->add($data);

                    if($result){
                        
                        $return_data = array(
                            'status' => 1,
                            'info'   => '添加文章成功！',
                            );   
                        
                    }              
                    
                }
                         
            }
            
            $this->ajaxReturn($return_data);exit;            
            
        }        
        
        $this->display();
        
    }    
    
    //编辑文章
    public function edit()
    {
        $id = $_GET['id'];
		$category = D("ArticleCategory")->field('id,pid,name')->order('sort asc')->select();               
        $result = A("ArticleCategory")->tree($category, 0, 0);

        $art = $this->model->find($id);

        $options = "";
        $str = "";
        foreach($result as $key=>$value){
            $string = "┗━";
            $select = "";
            $str = str_repeat("&nbsp;&nbsp;",$value['deep']);
            if($value['deep'] === 0){
                $string = "";
            }
            if($value['id'] === $art['c_id']){
                $select = " selected =\"selected\"";
            }
            $options .= "<option value=".$value['id']." {$select}>{$str}"."$string"."{$value['name']}</option>";
            
        }

        if(IS_AJAX){
    
            $return_data = array(
                'status' => 0,
                'info'   => '编辑文章失败',
                );  
                
                
            if($_POST['id']){
                
                $log_str = '编辑文章，文章ID为'.$_POST['id'];
                addAdminLog($log_str);                  
                
                $msg = array();
                if($art['thumbnail'] != $_POST['file1']){
                    
                    if (FileUpdateASDataURL::isDataURL($_POST['file1'])) {
                        $fileUpdateASDataURL = new FileUpdateASDataURL();
                        //上传
                        if (($fileName = $fileUpdateASDataURL->update($_POST['file1'])) === false) {
                            $error = $fileUpdateASDataURL->getError();
                            $msg[] = "上传图片失败！";
                            // $return_data['info'] = "上传图片失败！";                               
                        }
                        
                        $url = $fileName;
                    }  
                    
                }else{
                    
                    $url = $_POST['file1'];
                }
                
                if(count($msg) <= 0){
                    
                    $data = array(
                        'c_id' => $_POST['cid'],
                        'title' => $_POST['title'],
                        'keywords' => $_POST['keywords'],
                        'description' => $_POST['description'],
                        'thumbnail' => "$url",
                        'content' => $_POST['content'],
                        'link' => $_POST['link'],
                        'sort' => $_POST['sort'],                        
                    ); 

                    $ret = $this->model->where("id=".$_POST['id'])->save($data);
                    // echo $this->model->getLastSql();echo "<br>";
                    if($ret){
                        
                        $return_data = array(
                            'status' => 1,
                            'info'   => '编辑文章成功',
                            );                 
                        
                    }
                   
                }
                                
            }
            
            $this->ajaxReturn($return_data);exit;            
            
        }
        $this->assign("art",$art);
        $this->assign("options",$options);
        $this->display();
        
    }
    
    
    //删除文章 
    public function delete()
    {
        
        $id = $_GET['id'];
        $this->model->where("id=$id")->delete();
        
        $log_str = '删除文章，文章ID为'.$id;
        addAdminLog($log_str);  
		$this->redirect('index');
               
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

    //上传图片    
    public function upload()
    {
        
        $extensions = array("jpg","bmp","gif","png");
        $uploadFilename = $_FILES['file']['name'];
        $extension = pathInfo($uploadFilename,PATHINFO_EXTENSION);
        if (in_array($extension,$extensions)) {  
        
            $uploadPath = str_replace("\\",'/',realpath("Public")."/uploads/");
            $date = date('Y-m-d') . '/';
            $uploadPath .= $date;
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath);
            }
            
            $uuid = str_replace('.','',uniqid("",TRUE)).".".$extension;
            $desname = $uploadPath.$uuid;
            
            $previewname = '/Public/uploads/'. $date . $uuid;

            $tag = move_uploaded_file($_FILES['file']['tmp_name'],$desname);
            if($tag){
                
                return $previewname;
                
            }else{
                
                return false;
                
            }

        } else {  
            return false;
        }    
        
    }
    
   
    
}


?>