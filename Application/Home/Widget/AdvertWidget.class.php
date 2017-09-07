<?php

namespace Home\Widget;

use Common\Controller\WidgetController;

/**
 * 调用前台调用类型
 */
class AdvertWidget extends WidgetController
{
    private $viewPath = "Advert";

    /**
     * 调用前台调用类型By Code
     * @author luffy<luffyzhao@vip.126.com>
     * @dateTime 2016-03-04T15:06:19+0800
     * @param    [type]                   $code [description]
     * @return   [type]                         [description]
     */
    public function byCode($code)
    {
        $codeKey = 'AdvertWidget:byCode-' . $code;

        $content = S($codeKey);

        // if ($content === false) {
        $advertRow = D('WebCall')->getAdvertDetailByCode($code);
        if (!isset($advertRow['view']) || !$advertRow['view']) {
            return false;
        }

        $viewFile = $this->viewPath . ":" . $advertRow['view'];

        $this->assign('webCallDetail', $advertRow['WebCallDetail']);

        $content = $this->fetch($viewFile);

        S($codeKey, $content);
        // }
        echo $content;
    }
}
