<?php
/**
 *权限Model类
 */

namespace Admin\Model;

use Think\Model;

class AuthRuleModel extends Model
{

    /**
     * [getAuthRuleForPid description]
     * @author jinkkuanghqu@gmail.com
     * @dateTime 2016-02-29T12:04:49+0800
     * @param    integer                  $pid [description]
     * @return   [type]                        [description]
     */
    public function getAuthRuleForPid($pid = 0)
    {
        $authRuleRows = $this->where(array(
            'pid' => $pid,
        ))->order('sort Asc')->select();
        $authRuleAll = array();

        if ($authRuleRows) {
            foreach ($authRuleRows as $key => $value) {
                $authRuleAll[$key]          = $value;
                $authRuleAll[$key]['child'] = $this->getAuthRuleForPid($value['id']);
            }
        }

        return $authRuleAll;
    }

    /**
     * [getAuthRuleForRoleAuthRule description]
     * @author jinkkuanghqu@gmail.com
     * @dateTime 2016-02-29T12:07:37+0800
     * @param    [type]                   $roleAuthRuleRows [description]
     * @return   [type]                                     [description]
     */
    public function getAuthRuleForRoleAuthRule($roleAuthRuleRows, $pid = 0)
    {
        $authRuleRows = $this->where(array(
            'pid'    => $pid,
            'islink' => 1,
            'id'     => array('IN', $roleAuthRuleRows),
        ))->order('sort Asc')->select();
        $authRuleAll = array();

        if ($authRuleRows) {
            foreach ($authRuleRows as $key => $value) {
                $authRuleAll[$key]          = $value;
                $authRuleAll[$key]['name']  = '/' . MODULE_NAME . '/' . $value['name'];
                $authRuleAll[$key]['child'] = $this->getAuthRuleForRoleAuthRule($roleAuthRuleRows, $value['id']);
            }
        }

        return $authRuleAll;
    }

    /**
     * [getBreadcrumb description]
     * @author jinkkuanghqu@gmail.com
     * @dateTime 2016-02-29T14:57:01+0800
     * @return   [type]                   [description]
     */
    public function getBreadcrumb()
    {
        $name           = CONTROLLER_NAME . '/' . ACTION_NAME;
        $postionLastRow = $this->getByName($name);

        if ($postionLastRow['pid'] != 0) {
            $postionFirstRow = $this->find($postionLastRow['pid']);
        }
        return array($postionFirstRow, $postionLastRow);
    }

}
