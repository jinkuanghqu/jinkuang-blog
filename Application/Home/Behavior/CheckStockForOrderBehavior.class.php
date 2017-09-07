<?php
namespace Home\Behavior;

use \Common\Api\ProductStock;
use \Think\Behavior;

/**
 *
 */
class CheckStockForOrderBehavior extends Behavior
{
    private $orderRow;

    /**
     * [run description]
     * @author luffy<luffyzhao@vip.126.com>
     * @dateTime 2016-03-28T11:09:32+0800
     * @param    [type]                   $orderRow [description]
     * @return   [type]                             [description]
     */
    public function run(&$orderRow)
    {
        // 库存检测 20160505 by veter
        $checkStock = C('ENABLE_STOCK_CHECK', null, 1);
        if (!$checkStock) {
            return true;
        }
        // End;

        $this->orderRow = $orderRow;
        //检查库存
        $orderGoodsRows = D('OrderGoods')->where([
            'order_id' => $orderRow['id'],
        ])->select();
        $productStockApi = new ProductStock();
        $warehouseCode   = $orderRow['warehouse_code'];
        $storeCode       = D('Store')->getFieldById($orderRow['store_id'], 'code');

        $newOrderGoodsRows = $skuArray = array();

        foreach ($orderGoodsRows as $orderGoodsRow) {
            $productSku = $skuArray[] = D('Product')->getFieldById($orderGoodsRow['product_id'], 'sku');

            $newOrderGoodsRows[$productSku] = $orderGoodsRow['quantity'];
        }

        $sku = implode(',', $skuArray);

        if ($warehouseCode == 'GLOGROW') {
            $data = getStockForCBGoods($skuArray);
        } else {
            $data = $productStockApi->send([
                'compCode'      => $storeCode,
                'warehouseCode' => $warehouseCode,
                'sku'           => $sku,
            ]);
        }

        if (!$data || !is_array($data)) {
            redirect('/Index/jump/msg/product not stock at 1!');
        }

        if (count($data) != count($newOrderGoodsRows)) {
            redirect('/Index/jump/msg/product not stock at 2!');
        }

        foreach ($data as $inventory) {
            if ($newOrderGoodsRows[$inventory['sku']] > intval($inventory['availableInventory'])) {
                redirect('/Index/jump/msg/product not stock at 3!');
            }
        }
    }
}
