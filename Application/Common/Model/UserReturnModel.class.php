<?php

namespace Common\Model;

use Think\Model\RelationModel;

class UserReturnModel extends RelationModel
{
    const WAIT_PROCESSING = 1,
          PROCESSING      = 2,
          REVOKED         = 3,
          FINISHED        = 4;

    protected $status_map = array(
        self::WAIT_PROCESSING => '待处理',
        self::PROCESSING      => '处理中',
        self::REVOKED         => '已撤销',
        self::FINISHED        => '已完成',
    );

    public function getUserReturnList($condition)
    {
        $count = $this->where($condition)->count();

        $perPage = C('PAGE_SIZE', null, 10);
        $Page = new \Org\Util\Page($count, $perPage);
        $_GET['p'] = I('get.p')?I('get.p'):1;
        $list = $this->field('user_return.*,user.email as user_name')
                     ->where($condition)
                     ->join('user ON user_return.user_id=user.id')
                     ->order('dispute.add_time DESC')
                     ->page($_GET['p'].','.$perPage)
                     ->select();
        $show = $Page->show();
        return array('count'=>$count, 'pages' => $show, 'list' => $list);
    }

    public function destroy($id)
    {
        try {
            $this->where('id='.$id)->delete();
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
    // 买家提出退货
    public function addOne($data)
    {
        try {
            $data['status']   = self::WAIT_PROCESSING;
            $data['add_time'] = time();

            $id = $this->add($data);

            D('Order')->setReturning($data['order_sn']); // 设置订单状态为退货中

            return array(
                'status' => 1,
                'info' => 'Action success!',
                'data' => $id,
            );
        } catch (Exception $e) {
            return array(
                'status' => 0,
                'info' => 'Action failed',
            );
        }
    }

    // 设置退货为处理中
    public function setProcessing($id)
    {
        try {
            $this->where('id='.$id)->setField('status', self::PROCESSING);
            return array(
                'status'    => 1,
                'info'      => '操作成功',
            );
        } catch (Excpetion $e) {
            return array(
                'status'    => 0,
                'info'      => '操作失败',
            );
        }
    }
    // 设置退货为已完成
    public function setFinished($id)
    {
        $data = array(
            'status'    => self::FINISHED,
            'end_time'  => time(),
        );

        try {
            $this->where('id='.$id)->save($data);

            $orderSn = $this->where('id='.$id)->getField('order_sn');
            D('Order')->setReturned($orderSn); // 设置订单状态为退货完成

            return array(
                'status'    => 1,
                'info'      => '操作成功',
            );
        } catch (Excpetion $e) {
            return array(
                'status'    => 0,
                'info'      => '操作失败',
            );
        }
    }
    // 买家撤销退货
    public function setRevoked($orderSn)
    {
        $data = array(
            'status'    => self::REVOKED,
            'end_time'  => time(),
        );
        try {
            $this->where('order_sn="'.$orderSn.'"')->save($data);

            //$orderSn = $this->where('id='.$id)->getField('order_sn');
            D('Order')->setReturnRevoked($orderSn); // 设置订单状态为退货撤销

            return array(
                'status' => 1,
                'info' => 'Action success!',
            );
        } catch (Exception $e) {
            return array(
                'status' => 0,
                'info' => 'Action failed',
            );
        }
    }

    // 获取状态映射
    public function getStatusMap()
    {
        return $this->status_map;
    }

    //获取用户信息
    public function getUserInfo($userReturnId)
    {
        return $this->field('user.email email, user.first_name first_name')
            ->join('LEFT JOIN user_return ON user.id = user_return.user_id')
            ->where("user_return.id = {$userReturnId}")
            ->find();
    }
}
