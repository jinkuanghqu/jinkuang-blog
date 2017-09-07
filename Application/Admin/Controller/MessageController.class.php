<?php
namespace Admin\Controller;

use Common\Controller\AdminController;
use Common\Model\MessageModel;
use Common\Model\GradeModel;

/**
* message
*@author jinkkuanghqu@gmail.com
* 2016-02-19
*/
class MessageController extends AdminController
{
	
    protected $model;
    private $page_num = 10;

    function __construct()
    {
        parent::__construct();
        $this->model = D('Message');
    }
    
    public function _initialize()
    {
        parent::_initialize();
    }
	
	/**
	* send message
	*@author jinkkuanghqu@gmail.com
	* 2016-02-19
	*
	*/
    public function sendMessage()   
    {
        $grade = new \Common\Model\GradeModel();;
        $grades = $grade->getUserGrade();
        $options = "";
        
        foreach($grades as $value){
            
            $options .= '<option value='.$value['id'].'>'.$value['grade_name'].'</option>';
            
        }
        
        $this->assign('options',$options);
        $this->display();   			
    }
    
    
	/**
	* message to jobs
	*@author jinkkuanghqu@gmail.com
	* 2016-02-19
	*
	*/
    public function send()
    {   

        $log = '发送消息';
        addAdminLog($log);
        if(IS_AJAX){
            
            $return_data = array(
                'status' => 0,
                'info'   => '发送消息失败！',
                );                
            
            if(IS_POST){
                         
                $user_obj = new \Common\Model\UserModel();
                
                $condition = "";
                $userId = -1;
                
                if($_POST['type'] == 0){
        
                    $condition = "email=\"".trim($_POST['sendee'])."\"";
                    $user_ret = $user_obj->getUserId(0,$condition);                    
                    if($user_ret){
                        
                        $data = array(
                            'title' => trim($_POST['title']),
                            'content' => trim($_POST['content']),
                            'sender_id' => $_SESSION['admin']['id'],
                            'user_id' => $user_ret['id'],
                            'send_time' => time(),
                        ); 

                        $ret = $this->model->addMessage($data);   

                        if($ret){
                            
                            $return_data = array(
                                'status' => 1,
                                'info'   => '发送消息成功！',
                                );                        
                                               
                        }                         
                        
                    }else{
                        
                        $return_data['info'] = "未找到用户！";
                    }
                    
                    $this->ajaxReturn($return_data);exit;            
                    
                    
                }else{
                              
                    $userId = 1;                  
                    $data = array(
                        "type"      => $_POST['type'],            
                        "title"     => $_POST['title'],
                        "content"   => $_POST['content'],
                        "sender_id"   => $_SESSION['admin']['id'],                
                        "grade"   => $_POST['grade'],                
                        "user_id"     => $userId,
                        "send_time" => time(),               
                    );
                    
                    if($_POST['type'] == 1){
                        
                        $grade = implode(',',$_POST['grade']);                        
                        if(!empty($data['grade'])){
                            
                            foreach($data['grade'] as $key=>$value){
                                $data['grade'][$key] = array('grade_id'=>$value,'user_id'=>$userId);
                            }
                            
                        }else{
                                                   
                            $return_data['info'] = "未指定用户！";                        
                            $this->ajaxReturn($return_data);exit;            
                        
                        }
                        
                    }                       
                    
                    
                    if($userId >= 0){
                        
                        $Jobs = new \Admin\Model\JobsModel();
                        $ret = $Jobs->addJobs("message", $data);
                        if($ret){
                            
                            $return_data = array(
                                'status' => 1,
                                'info'   => '发送消息成功！',
                                );                        
                                               
                        }
                        
                    }else{
                        
                        $return_data['info'] = "未找到用户！";
                        
                    }                                              
                    
                }
           
            }

            $this->ajaxReturn($return_data);exit;            
            
        }

        
    }

	/**
	* 
	*@author jinkkuanghqu@gmail.com
	* 2016-02-19
	*
	*/    
    public function createNewArr($array)
    {
        $users = array();
        foreach($array as $value){
            $users[] = $value['id'];
        }
        
        return $users;
        
    }

	/**
	* get unsent Message from jobs
	*@author jinkkuanghqu@gmail.com
	* 2016-02-24
	*
	*/   
    public function unsentMessage()
	{
        
        $sender_id = $_SESSION['admin']['id'];
        $cur_page = I('get.p', 1, 'intval');
        $Jobs = new \Admin\Model\JobsModel();        
        $unsent_message = $Jobs->getJobs("message",$cur_page,$this->page_num);
        
        $message_array = array();
        if(count($unsent_message)>0){
            
            $i = 0;
            foreach($unsent_message as $value){
                $message = array();
                $receive_obj = "";
                $message_array[$i]['id'] = $value['id'];
                $message = unserialize($value['payload']);
                if($message['type'] === '0'){
                    
                    $user_id = $message['user_id'][0];
                    $user_obj = new \Common\Model\UserModel();
                    $user_ret = $user_obj->find($user_id);                   
                    $receive_obj = $user_ret['email'];
                    
                }
                if($message['type'] == 1){
                    
                    $receive_obj = "指定等级用户";
                    
                }
                if($message['type'] == 2){
                    
                    $receive_obj = "所有用户";
                    
                }
                // echo "<pre>";
                // print_r($value);die;
                $message_array[$i]['title'] = $message['title'];
                $message_array[$i]['content'] = $message['content'];
                $message_array[$i]['receive_obj'] = $receive_obj;
                $message_array[$i]['send_time'] = $value['add_time']; 
                $i++;
            }
            
        }
        
        // 分页
        $total_count = $Jobs->getJobNumber("message");
        $this->assign('page_note', '<span class="rows">每页' . $this->page_num . '条, 共 ' . $total_count . ' 条记录</span>');
        $Page       = new \Org\Util\Page($total_count, $this->page_num);
        $page_links = $Page->show();
        $this->assign('pages', $page_links);                
        
        
        $this->assign("message_array",$message_array);
        $this->display();
	}	
	
	/**
	* has send message 
	*@author jinkkuanghqu@gmail.com
	* 2016-02-24
	*
	*/    
    public function haSendMessage()
    {
        $sender_id = $_SESSION['admin']['id'];
        $cur_page = I('get.p', 1, 'intval');
        
        $haSendMessage = $this->model->getMessageLimit($sender_id,$cur_page);
        // echo "<pre>";
        // print_r($haSendMessage);
        
        $user_obj = new \Common\Model\UserModel();
        
        foreach($haSendMessage as $key=>$value){
            $msg_status = "";
            $user_id = $value['user_id'];
            $ret = $user_obj->find($user_id);
            $haSendMessage[$key]['sendee'] = $ret['email'];
            if($value['status'] == 1){

                $msg_status = '<div class="center"><span class="btn btn-sm btn-info">已读</span></div>';
                
            }else{
                
                $msg_status = '<div class="center"><span class="btn btn-sm btn-warning">未读</span></div>';                
            }
            $haSendMessage[$key]['msg_status'] = $msg_status;               
            
        }
 
 
        // 分页
        $total_count = $this->model->count();
        $this->assign('page_note', '<span class="rows">每页' . $this->page_num . '条, 共 ' . $total_count . ' 条记录</span>');
        $Page       = new \Org\Util\Page($total_count, $this->page_num);
        $page_links = $Page->show();
        $this->assign('pages', $page_links);        
        
        $this->assign("haSendMessage",$haSendMessage);
        $this->display();
    }
    

    
    public function test()
    {
      
        $message = new \Common\Api\MessageApi();
        $ret = $message->fetch();
        echo "<pre>";
        print_r($ret);die;
    }
    
    
	/**
	* get unsent message info
	*@author jinkkuanghqu@gmail.com
	* 2016-02-24
	*
	*/     
    
    public function showMessage()
    {
        
        if(IS.GET){
            
            $id = I('get.id', 0, 'intval');
            $Jobs = new \Admin\Model\JobsModel();           
            $msg_ret = $Jobs->find($id);
            if(count($msg_ret) <= 0){
                echo "此消息已发送！";die;
            }
            $message = array();
            $message_receiver = unserialize($msg_ret['payload']);
            if($message_receiver['type'] === '0'){

                $user_id = $message_receiver['user_id'][0];
                $user_obj = new \Common\Model\UserModel();
                $user_ret = $user_obj->find($user_id);

                $message['receiver'] = $user_ret['email'];
                $message['content'] = $message_receiver['content'];
                
            }
        
            if($message_receiver['type'] == 1){

                $grade = new \Common\Model\GradeModel();;
                $grades = $grade->getUserGrade();
                
                $grade_arr = array();
                foreach($grades as $value){
                    $grade_arr[$value['id']] = $value['grade_name'];
                }

                $receivers = "";
                foreach($message_receiver['grade'] as $val){
                    $receivers .= $grade_arr[$val].',';
                }
                
                $receivers = rtrim($receivers,',');
                $message['receiver'] = $receivers;                
                $message['content'] = $message_receiver['content'];
                
            }
            if($message_receiver['type'] == 2){

                $message['receiver'] = "所有用户";
                $message['content'] = $message_receiver['content'];
                
            }            

            // echo "<pre>";
            // print_r($message);          
            $this->assign('message', $message);
            $this->display();        	
            
        }
       
    }

	/**
	* get message info
	*@author jinkkuanghqu@gmail.com
	* 2016-02-24
	*
	*/     
    
    public function messageContent()
    {
        $id = I('get.id', 0, 'intval');
        $msg_ret = $this->model->find($id);
        // echo "<pre>";
        // print_r($msg_ret);
        if(count($msg_ret) <= 0){
            echo "未找到此消息";die;
        }        
        
        $this->assign('msg_ret',$msg_ret);
        
        $this->display();        	
        
        
        
    }

  
	
}