<?php

namespace Common\Model;

use Think\Model\RelationModel;

class UserModel extends RelationModel
{
    private $_condition;

    protected function _initialize()
    {
        parent::_initialize();
        $this->_condition();
    }

    private function _condition()
    {
        if (isset($_GET['email']) && $_GET['email']) {
            $this->_condition['email'] = array('LIKE', $_GET['email']);
        }
    }

    //获取用户列表
    public function getUserList()
    {
        $list = $count = M('user');
        if ($this->_condition) {
            $list->where($this->_condition);
            $count->where($this->_condition);
        }
        $count   = $count->count();
        $perPage = $_GET['perPage'] ? $_GET['perPage'] : 10;
        $Page    = new \Think\Page($count, $perPage);

        $_GET['p'] = $_GET['p'] ? $_GET['p'] : 1;
        $list      = $list
            ->page($_GET['p'] . ',' . $perPage)
            ->select();

        $show = $Page->show();
        return array('count' => $count, 'page' => $show, 'list' => $list);
    }

    //获取用户信息
    public function getUserInfo($userId)
    {
        return M('user')
            ->field('user.*')
            ->join('LEFT JOIN user_address ON user.id = user_address.user_id')
            ->join('LEFT JOIN user_collect ON user.id = user_collect.user_id')
            ->join('LEFT JOIN user_paypoints_log ON user.id = user_paypoints_log.user_id')
            ->join('LEFT JOIN user_view ON user.id = user_view.user_id')
            ->where("user.id = {$userId}")
            ->find();
    }

    //通过邮箱获取用户信息
    public function getUserInfoByEmail($email)
    {
        return M('user')
            ->where("email = '{$email}'")
            ->find();
    }

    //修改用户信息
    public function modify($conditon, $userId)
    {
        try {
            M('user')
                ->where("id = {$userId}")
                ->save($conditon);
            return array(
                'status' => 1,
                'info'   => '修改成功',
            );
        } catch (Exception $e) {
            return array(
                'status' => 0,
                'info'   => '修改失败',
            );
        }

    }
    //添加注册用户
    public function add($data)
    {
        $arr               = array();
        $arr['email']      = $data['email'];
        $arr['first_name'] = $data['first_name'];
        $arr['last_name']  = $data['last_name'];
        // $arr['location']   = $data['location'];
        // $arr['industry']   = $data['industry'];
        $arr['company_name'] = $data['company_name'];
        $arr['password']     = $this->shaByPassword($data['password']);
        $arr['reg_time']     = $_SERVER['REQUEST_TIME'];
        $id                  = M('user')->add($arr);
        if ($id) {
            return array(
                'status' => 1,
                'info'   => '新增成功',
                'data'   => array(
                    'id'  => $id, //返回用户id
                    'str' => $this->baseActive($data), //返回激活邮件链接加密参数
                ),
            );
        } else {
            return array(
                'status' => 0,
                'info'   => '新增失败',
            );
        }
    }

    //用户登录验证
    public function verification($data)
    {
        $map             = array();
        $map['email']    = $data['email'];
        $map['password'] = $this->shaByPassword($data['password']);
        $row             = D('user')
            ->where($map)
            ->find();
        if ($row) {
            if ($row['activation'] == 0) {
                return array(
                    'status' => -1,
                    'userId' => $row['id'],
                    'info'   => 'Mailbox validation failed!',
                );
            }
            //todo 写入session
            $this->userLoginLog($data['email']);
            unset($row['password']);
            session('user', $row);
            // dump(session('user'));
            //登录之后合并浏览记录
            D('UserView')->viewMerge();

            // 登录成功后合并购物车
            D('Cart')->cartMerge();
            // $url = previousPage();
            return array(
                'status' => 1,
                'info'   => 'login success!',
                'url'    => U('/Index/index'),
            );
        } else {
            return array(
                'status' => 0,
                'info'   => 'E-mail or password mistake!',
            );
        }
    }

    //密码加密
    private function shaByPassword($password)
    {
        return sha1(md5($password));
    }

    //生成加密串 邮件用
    private function baseActive($data)
    {
        return base64_encode($data['email'] . '&' . md5($this->shaByPassword($data['password']) . C('APP_KEY')));
    }

    //生成重置邮箱的加密字符串
    private function baseResetEmail($oldEmail, $newEmail, $password)
    {
        return base64_encode($oldEmail . '&' . $newEmail . '&' . time() . '&' . md5($this->shaByPassword($password) . C('APP_KEY')));
    }

    //重发重置邮箱邮件
    public function baseResendResetEmail($userId, $email)
    {
        $userRow = M('user')
            ->where("id = {$userId}")
            ->find();
        return base64_encode($userRow['email'] . '&' . $email . '&' . time() . '&' . md5($userRow['password'] . C('APP_KEY')));
    }

    //重发验证邮件
    public function resendBaseActive($email)
    {
        $password = M('user')
            ->field('password')
            ->where("email='{$email}'")
            ->find();
        return base64_encode($email . '&' . md5($password['password'] . C('APP_KEY')));
    }

    //生成找回密码的验证邮件 感觉不是太安全 单凭一个邮件就找回密码了
    public function baseFindpwdActive($email)
    {
        return base64_encode($email . '&' . time() . C('APP_KEY'));
    }

    //找回密码解密
    public function findepwdDecode($str)
    {
        $arr = explode('&', substr(base64_decode($str), 0, -strlen(C('APP_KEY'))));
        $row = $this->getUserInfoByEmail($arr[0]);
        //半小时有效
        if ($row && $arr[1] + 1800 - time()) {
            return array(
                'status' => 1,
                'data'   => $row['email'],
            );
        } else {
            return array(
                'status' => 0,
                'info'   => '链接已失效',
            );
        }
    }

    //用户激活
    public function userActive($str)
    {
        $arr      = explode('&', base64_decode($str));
        $password = D('user')
            ->field('password')
            ->where("email = '{$arr[0]}'")
            ->find();
        if (md5($password['password'] . C('APP_KEY')) == $arr[1]) {
            //激活
            D('user')->where("email = '{$arr[0]}'")->save(array('activation' => '1'));
            return array(
                'status' => 1,
                'info'   => '激活成功',
            );
        } else {
            return array(
                'status' => 0,
                'info'   => '激活失败',
            );
        }
    }

    //登录的更新操作
    private function userLoginLog($email)
    {
        $map               = array();
        $map['last_login'] = $_SERVER['REQUEST_TIME'];
        $map['last_ip']    = $_SERVER['REMOTE_ADDR'];
        $map['logins']     = array('exp', 'logins+1');
        D('user')
            ->where("email = '{$email}'")
            ->save($map);
    }

    public function pwdChange($email, $password)
    {
        if (D('user')
            ->where("email = '{$email}'")
            ->save(array('password' => $this->shaByPassword($password)))) {
            return array(
                'status' => 1,
                'info'   => '修改成功',
            );
        } else {
            return array(
                'status' => 0,
                'info'   => '修改失败',
            );
        }
    }

    //验证密码并发送更改密码的邮件
    public function sendResetEmail($conditon, $newEmail)
    {
        $conditon['password'] = $this->shaByPassword($conditon['password']);
        $row                  = M('user')->where($conditon)->find();
        if ($row) {
            //加密字符串
            $str = $this->baseResetEmail($row['email'], $newEmail, $password);
            return array(
                'status' => 1,
                'info'   => '验证成功',
                'data'   => array(
                    'str'   => $str,
                    'email' => $newEmail,
                ),
            );
        } else {
            return array(
                'status' => 0,
                'info'   => '验证失败',
            );
        }
    }

    //解密重置邮箱邮件
    public function debaseMailBox($str)
    {
        $arr = explode('&', base64_decode($str));
        //是否失效
        if ($arr[2] + 86400 < time()) {
            return array(
                'status' => 0,
                'info'   => '链接已失效',
            );
        }
        $row = $this->getUserInfoByEmail($arr[0]);
        //半小时有效
        if ($row && $arr[3] == md5($row['password'] . C('APP_KEY'))) {
            $userId['id']      = $row['id'];
            $newEmail['email'] = $arr[1];
            M('user')
                ->where($userId)
                ->save($newEmail);
            return array(
                'status' => 1,
                'info'   => '重置成功',
            );
        } else {
            return array(
                'status' => 0,
                'info'   => '重置失败',
            );
        }
    }

    //重置密码
    public function passwordModify($userId, $oldPwd, $newPwd)
    {
        $condition['id']       = $userId;
        $condition['password'] = $this->shaByPassword($oldPwd);
        $userRow               = M('user')
            ->where($condition)
            ->find();
        $map['id']        = $userId;
        $save['password'] = $this->shaByPassword($newPwd);
        if ($userRow) {
            if (M('user')->where($map)->save($save)) {
                return array(
                    'status' => 1,
                    'info'   => '修改成功',
                );
            }
        }
        return array(
            'status' => 0,
            'info'   => '修改失败',
        );

    }

    /**
     * get user_id from email
     *@author simon
     */
    public function getUserId($type, $condition)
    {
        if ($type === 0) {

            return $this->Field('id')->where($condition)->find();

        }

        if ($type == 1) {

            return $this->Field('id')->where($condition)->select();

        }

        if ($type == 2) {

            return $this->Field('id')->select();

        }
    }

    /**
     * 给用户增加积分
     * @author veter
     *
     */
    public function addPayPoints($userId, $points)
    {
        $result = $this->where(array('id' => $userId))->setInc('paypoints', $points);
        return $result;
    }

    /**
     * 判断 vip 有效性
     * @author luffy<luffyzhao@vip.126.com>
     * @dateTime 2016-03-28T15:46:55+0800
     * @param    string                   $value [description]
     */
    public function vipIsVia($userId)
    {
        $data = array(
            'level' => 0,
        );

        $userRow = $this->field('id,level,level_time')->find($userId);

        $computeVipBehavior = new \Home\Behavior\ComputeVipBehavior();

        if ($userRow['level'] == 0) {
            $data['amount'] = $computeVipBehavior->firstLevel($userRow);
            return $data;
        }

        if ($userRow['level'] == 1) {
            $data['amount'] = $computeVipBehavior->secondLevel($userRow);
            $data['level']  = 1;
            return $data;
        }

        if ($userRow['level'] == 2 && $userRow['level_time'] < (time() - 31536000)) {
            $data['amount'] = $computeVipBehavior->secondLevel($userRow);
            return $data;
        }

        $data['level'] = 2;
        return $data;
    }

    public function getUserLimit($page, $page_size, $condition = array(), $fields = "id,email")
    {
        return $this->field($fields)->where($condition)->order('id asc')->page($page . ',' . $page_size)->select();
    }
}
