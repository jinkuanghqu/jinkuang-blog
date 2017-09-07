<?php
namespace Home\Model;

use Think\Model\RelationModel;

class UserViewModel extends RelationModel
{
    protected $_link = array(
        'goods' => array(
            'mapping_type' => self::BELONGS_TO,
            'class_name'   => 'Goods',
            'mapping_name' => 'goods',
            'foreign_key'  => 'item_id_id',
        ),
    );

    protected $_validate = array(
        array('item_id', 'require', '商品ID必须填写！'),
    );

    protected $_auto = array(
        array('session_id', 'session_id', self::MODEL_INSERT, 'function'),
        array('user_id', 'getUserId', self::MODEL_INSERT, 'function'),
        array('add_time', 'time', self::MODEL_INSERT, 'function'),
    );

    /**
     * 添加商品浏览记录
     * @author luffy<luffyzhao@vip.126.com>
     * @dateTime 2016-03-10T10:35:24+0800
     * @param    [type]                   $goodsId [description]
     */
    public function addGoodsById($goodsId)
    {
        $data = array(
            'user_id' => getUserId(),
            'item_id' => $goodsId,
        );
        if ($data['user_id'] == 0) {
            $data['session_id'] = session_id();
        }

        $userViewRow = $this->field('id')->where($data)->find();

        if ($userViewRow) {
            return $this->where(['id' => $userViewRow['id']])->data([
                'add_time' => time(),
            ])->save();
        }
        $isVoid = $this->create([
            'item_id' => $goodsId,
            'type'    => 0,
        ]);
        if (!$isVoid) {
            return false;
        }
        return $this->add();

    }

    /**
     * 添加店铺浏览记录
     * @author luffy<luffyzhao@vip.126.com>
     * @dateTime 2016-03-10T10:35:24+0800
     * @param    [type]                   $goodsId [description]
     */
    public function addStoreById($storeId)
    {
        $isVoid = $this->create([
            'item_id' => $storeId,
            'type'    => 1,
        ]);

        if ($isVoid) {
            return $this->add();
        }
        return false;
    }

    /**
     * 用户登录之后浏览记录合并
     * @author luffy<luffyzhao@vip.126.com>
     * @dateTime 2016-03-10T11:24:03+0800
     * @param    string                   $value [description]
     */
    public function viewMerge()
    {
        $sessionId = session_id();
        $userId    = session('user.id');
        $this->where([
            'user_id'    => 0,
            'session_id' => $sessionId,
        ])->setField('user_id', $userId);
    }
}
