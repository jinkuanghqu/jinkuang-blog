<?php

namespace Common\Model;

use Think\Model\RelationModel;

class MessageModel extends RelationModel
{
	//消息表
    protected $_link = array(

        'message' => array(
            'mapping_type'  => self::HAS_MANY,
            'class_name'    => 'message',
            'mapping_name'  => 'message',
        ),

    );

    //添加信息
    public function addMessage($data)
    {

        return $this->add($data);

    }

    public function getMessage($sender_id,$type=0,$status=0)
    {
        $string = "sender_id='$sender_id'";

        if($type === 1){

            $string .= "and status=0 ";

        }

        if($type === 2){

            $string .= "and status=1 ";

        }

        return $this->where($string)->order('send_time desc')->select();

    }


    public function getMessageLimit($sender_id,$page,$page_size=10)
    {
        $string = "sender_id='$sender_id'";

        return $this->where($string)->order('send_time desc')->page($page . ',' . $page_size)->select();

    }

    public function getMessageList($condition)
    {
        $count = D('message')
            ->where($condition)
            ->count();

        $perPage = 5;
        $Page = new \Org\Util\Page($count,$perPage);

        $_GET['p'] = I('get.p')?I('get.p'):1;
        $list = D('message')
            ->where($condition)
            ->order('send_time DESC')
            ->page($_GET['p'].','.$perPage)
            ->select();

        $show = $Page->show();
        return array('count'=>$count, 'pages' => $show, 'list' => $list);
    }

    //获取所有信息统计数据
    public function getMessageTotal($userId)
    {
        return array(
            'all' => D('message')->where("user_id = {$userId}")->count(),
            'unRead' => D('message')->where("user_id = {$userId} AND status = 0")->count(),
            'hasRead' => D('message')->where("user_id = {$userId} AND status = 1")->count(),
        );
    }

    public function destroy($condition)
    {
        try {
            M('message')
            ->where($condition)
            ->delete();
            return array(
                'status' => 1,
                'info' => '操作成功',
            );
        } catch (Exception $e) {
            return array(
                'status' => 0,
                'info' => '操作失败',
            );
        }
    }
    //已读
    public function readFlag($condition, $save)
    {
        try {
            M('message')
            ->where($condition)
            ->save($save);
            return array(
                'status' => 1,
                'info' => '操作成功',
            );
        } catch (Exception $e) {
            return array(
                'status' => 0,
                'info' => '操作失败',
            );
        }
    }

    public function messageInfo($id, $userId)
    {
        $result = array();
        $result['info'] = D('message')
                        ->find($id);
        $userId = $result['info']['user_id'];
        $time = $result['info']['send_time'];
        $result['pre'] = D('message')
                        ->where("id != {$id} AND user_id = {$userId} AND send_time < {$time}")
                        ->order('send_time DESC')
                        ->find();
        $result['next'] = D('message')
                        ->where("id != {$id} AND user_id = {$userId} AND send_time > {$time}")
                        ->order('send_time ASC')
                        ->find();
        return $result;
    }
}
