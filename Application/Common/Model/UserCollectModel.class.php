<?php

namespace Common\Model;

use Think\Model\RelationModel;

class UserCollectModel extends RelationModel
{

    const GOODS_COLLECT = 1,
          STORE_COLLECT = 2;

    // 收藏商品
    public function addGoodsCollect($userId, $goodsId, $keyword='')
    {
        $data = array(
            'user_id'   => $userId,
            'type'      => self::GOODS_COLLECT,
            'item_id'   => $goodsId,
        );
        if ($this->where($data)->find()) {
            return -1;
        } else {
            $data['keyword']  = $keyword;
            $data['add_time'] = time();

            try {
                $this->data($data)->add();
            } catch (Exception $e) {
                return 0;
            }
            return 1;
        }
    }

    // 收藏店铺
    public function addStoreCollect($userId, $storeId, $keyword='')
    {
        $data = array(
            'user_id'   => $userId,
            'type'      => self::STORE_COLLECT,
            'item_id'   => $storeId,
        );
        if ($this->where($data)->find()) {
            return -1;
        } else {
            $data['keyword']  = $keyword;
            $data['add_time'] = time();

            try {
                $this->data($data)->add();
            } catch (Exception $e) {
                return 0;
            }
            return 1;
        }
    }

    // 获取收藏的商品的id
    public function getFavoriteGoodsIds($userId)
    {
        $condition = array(
            'user_id'   => $userId,
            'type'      => self::GOODS_COLLECT
        );

        return $this->where($condition)
                    ->order('add_time DESC')
                    ->getField('item_id');
    }

    // 获取收藏的商品
    public function getFavoriteGoods($userId, $limit = -1, $page = 1)
    {
        $condition = array(
            'user_collect.user_id'   => $userId,
            'user_collect.type'      => self::GOODS_COLLECT
        );
        if ($limit == -1) {
            return $this->where($condition)->count();
        }
        $field_list = 'goods.id,goods.name,goods.price,goods.image,user_collect.add_time';
        return $this->field($field_list)
                    ->join('goods ON user_collect.item_id=goods.id')
                    ->where($condition)
                    ->page($page . ',' . $limit)
                    ->order('user_collect.add_time DESC')
                    ->select();
    }

    public function getCollectProductList($userId) {
        $type = self::GOODS_COLLECT;
        $count = D("user_collect")
            ->where("type = {$type}")
            ->where("user_id = {$userId}")
            ->count();
        $perPage = 4;
        $Page = new \Org\Util\Page($count, $perPage);
        $_GET['p'] = I('get.p')?I('get.p'):1;
        $list = M('user_collect')
            ->field('user_collect.id,user_collect.item_id,goods.image,goods.name,goods.store_code,goods.store_name,goods.price,store.address')
            ->join('LEFT JOIN goods ON goods.id = user_collect.item_id')
            ->join('LEFT JOIN store ON store.code = goods.store_code')
            ->where("user_collect.type = {$type}")
            ->where("user_collect.user_id = {$userId}")
            ->order('user_collect.add_time DESC')
            ->page($_GET['p'].','.$perPage)
            ->select();
        $show = $Page->show();
        return array('count'=>$count, 'pages' => $show, 'list' => $list);
    }

    public function getCollectStoreList($userId)
    {
        $type = self::STORE_COLLECT;
        $count = D("user_collect")
            ->where("type = {$type}")
            ->where("user_id = {$userId}")
            ->count();
        $perPage = 4;
        $Page = new \Org\Util\Page($count, $perPage);
        $_GET['p'] = I('get.p')?I('get.p'):1;
        $list = M('user_collect')
            ->field('user_collect.id,store.store_name,store.address,store.seo_keywords,store.banner')
            ->join('LEFT JOIN store ON store.id = user_collect.item_id')
            ->where("user_collect.type = {$type}")
            ->where("user_collect.user_id = {$userId}")
            ->order('user_collect.add_time DESC')
            ->page($_GET['p'].','.$perPage)
            ->select();
        $show = $Page->show();
        return array('count'=>$count, 'pages' => $show, 'list' => $list);
    }

    public function destory($idArr)
    {
        try {
            M('user_collect')->where(array('id'=>array('IN',$idArr)))->delete();
            return array(
                'status' => 1,
                'info' => '删除成功',
            );
        } catch (Exception $e) {
            return array(
                'status' => 0,
                'info' => '删除失败',
            );
        }

    }
}
