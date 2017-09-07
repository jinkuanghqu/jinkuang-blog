<?php

namespace Common\Model;

use Org\Util\FileUpdateASDataURL;
use Think\Model\RelationModel;

class GradeModel extends RelationModel
{
    const GRADE_TYPE_STORE = 1,
          GRADE_TYPE_USER  = 2;
    //会员等级
    protected $_link = array(
        'grade' => array(
            'mapping_type' => self::HAS_ONE,
            'class_name'   => 'grade',
            //'foreign_key'   => 'id',
            'mapping_name' => 'grade',
        ),
    );
    // 获取店铺等级列表
    public function getStoreGrade($field_list = 'id,grade_name,grade_desc,grade_logo', $condition = array())
    {
        $condition['type'] = self::GRADE_TYPE_STORE;
        return $this->field($field_list)->where($condition)->select();
    }
    // 添加店铺等级
    public function addStoreGrade($data)
    {
        $data['type'] = self::GRADE_TYPE_STORE;
        return $this->add($data);
    }
    // 修改店铺等级
    public function editStoreGrade($id, $data)
    {
        return $this->where('id='.$id)->save($data);
    }
    public function dropStoreGrade($id)
    {
        return $this->where('id='.$id)->delete();
    }

    public function getUserGrade($condition = array(), $fetch = true)
    {
        $condition['type'] = 2;
        $this->field('grade_name,grade_logo')->where($condition);
        if ($fetch) {
            return $this->select();
        } else {
            return $this->find();
        }
    }

    //添加或修改用户等级
    public function userGradeAddModify()
    {
        $params = I('post.');
        if (FileUpdateASDataURL::isDataURL($params['grade_logo'])) {
            $fileUpdateASDataURL = new FileUpdateASDataURL();
            //上传
            if (($fileName = $fileUpdateASDataURL->update($params['grade_logo'])) === false) {
                $error = $fileUpdateASDataURL->getError();
            }
            $params['grade_logo'] = $fileName;
        }

        if ($params['id'] != null) {
            $str = 'save';
        } else {
            $str = 'add';
        }

        $params['type'] = 2;

        if ($this->$str($params)) {
            return array(
                'status' => 1,
                'info'   => "处理成功",
                'url'    => U('Admin/User/userGrade'),
            );
        } else {
            return array(
                'status' => 0,
                'info'   => "处理失败",
            );
        }

    }

    //删除用户等级
    public function destroy()
    {
        if (I('get.id')) {
            try {
                $this->delete(I('get.id'));
                return array(
                    'status' => 1,
                    'info'   => "处理成功",
                );
            } catch (Exception $e) {
                return array(
                    'status' => 0,
                    'info'   => "处理失败",
                );
            }
        }
    }
}
