<?php

namespace Common\Model;

use Think\Model\RelationModel;
use \Org\Util\Auth;

class AdminModel extends RelationModel
{

    protected $_link = array(
        'RoleRow' => array(
            'mapping_type'   => self::BELONGS_TO,
            'class_name'     => 'Role',
            'foreign_key'    => 'role_id',
            'mapping_name'   => 'roleRow',
            'mapping_fields' => 'name',
        ),
    );
    /**
     * 登录
     * @author luffy<luffyzhao@vip.126.com>
     * @dateTime 2016-02-17T14:30:42+0800
     * @return   [type]                   [description]
     */
    public function doLogin($email, $password)
    {
        $admin = $this->where(array('email' => $email))->find();

        if (empty($admin)) {
            return false;
        }

        if ($admin['password'] != $this->shaByPassword($password)) {
            return false;
        }
        $admin['overtime'] = false;
        session('admin', $admin);
        Auth::saveAccessList($admin['id']);

        $this->where(array('id' => $admin['id']))->save(array(
            'update_time' => time(),
        ));
        return true;
    }

    /**
     * 登录锁定
     * @author luffy<luffyzhao@vip.126.com>
     * @dateTime 2016-02-17T15:45:42+0800
     * @param    string                   $password [description]
     * @return   [type]                             [description]
     */
    public function doLoginLock($password)
    {

        if ($this->shaByPassword($password) == session('admin.password')) {
            session('admin.overtime', false);
            return true;
        } else {
            return false;
        }
    }

    /**
     * [getMenuList description]
     * @author luffy<luffyzhao@vip.126.com>
     * @dateTime 2016-02-29T12:01:45+0800
     * @return   [type]                   [description]
     */
    public function getMenuList()
    {
        $roleId = session('admin.role_id');

        $roleModel     = D('Role');
        $authRuleModel = D('AuthRule');
        $roleRow       = $roleModel->relation(true)->find($roleId);

        // 权限内的菜单
        $roleAuthRuleRows = array_column($roleRow['rules'], 'id');

        return $authRuleModel->getAuthRuleForRoleAuthRule($roleAuthRuleRows, 0);
    }

    /**
     * [shaByPassword description]
     * @author luffy<luffyzhao@vip.126.com>
     * @dateTime 2016-02-17T14:40:56+0800
     * @param    [type]                   $password [description]
     * @return   [type]                             [description]
     */
    private function shaByPassword($password)
    {
        return sha1(md5($password));
    }

}
