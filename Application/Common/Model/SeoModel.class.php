<?php
/**
 * 页面SEO模型
 * @author veter
 * @date   2016-04-19T14:18:24+0800
 * @param  [type]                   $data [description]
 */

namespace Common\Model;

use Think\Model;

class SeoModel extends Model
{

    protected $_validate = array(
        array('name', 'require', '名字不能重复且不能为空', self::EXISTS_VALIDATE, 'unique', self::MODEL_INSERT),
        array('page_path', 'require', '页面路径不能重复且不能为空', self::EXISTS_VALIDATE, 'unique', self::MODEL_INSERT),
        array('title', 'require', 'SEO标题不能为空'),
        array('description', 'require', 'SEO描述不能为空'),
        array('keywords', 'require', 'SEO关键词不能为空'),
    );

    /**
     * 添加一条数据
     * @author veter
     * @date   2016-04-19T14:18:22+0800
     * @param  [type]                   $data [description]
     */
    public function addOne($data)
    {
        if (!$this->create($data)) {
            return false;
        } else {
            return $this->add();
        }
    }
    /**
     * 更新一条数据
     * @author veter
     * @date   2016-04-19T14:19:30+0800
     * @param  [type]                   $where [description]
     * @param  [type]                   $data  [description]
     * @return [type]                          [description]
     */
    public function updateOne($where, $data)
    {
        if (!$this->create($data, 2)) {
            return false;
        } else {            
            return $this->where($where)->save($data);
        }
    }
    /**
     * 判断是否有重复的数据
     * @author veter
     * @date   2016-04-19T14:21:18+0800
     * @param  [type]                   $action     [description]
     * @param  [type]                   $foreignKey [description]
     * @return [type]                               [description]
     */
    public function pathExists($path)
    {
        return $this->where(array(
                'page_path' => $path,
            ))->getField('id');
    }
    /**
     * 判断是否有重复的名字
     * @author veter
     * @date   2016-04-19T14:22:04+0800
     * @param  [type]                   $name [description]
     * @return [type]                         [description]
     */
    public function nameExists($name)
    {
        return $this->where(array('name' => $name))->getField('id');
    }
    /**
     * 分页获取数据列表
     * @author veter
     * @date   2016-04-13T10:27:57+0800
     * @param  [type]                   $fieldList   [要获取的字段列表]
     * @param  [type]                   $queryParams [sql查询参数 array('where' =>,'join'=>,'order'=>)]
     * @param  [type]                   $curPage     [当前页]
     * @param  integer                  $pageSize    [每页大小记录条数
     * @param  array                    $pageParams  [分页的查询条件]
     * @return [type]                                [array(记录总条数,数据列表,分页链接)]
     */
    public function getByPage($fieldList, $queryParams, $curPage, $pageSize = 0, $pageParams = array())
    {
        $totalCount = $this->where($queryParams['where'])->count();

        $this->where($queryParams['where']);

        if (!empty($queryParams['join'])) {
            if (is_array($queryParams['join'])) {
                foreach ($queryParams['join'] as $joinStr) {
                    $this->join($joinStr);
                }
            } else if (is_string($queryParams['join'])) {
                $this->join($queryParams['join']);
            }
        }

        if (!empty($queryParams['order'])) {
            if (is_array($queryParams['order'])) {
                foreach ($queryParams['order'] as $joinStr) {
                    $this->order($joinStr);
                }
            } else if (is_string($queryParams['order'])) {
                $this->order($queryParams['order']);
            }
        }

        if (empty($pageSize)) {
            $pageSize = C('PAGE_SIZE');
        }

        $resultSet = $this->field($fieldList)
            ->page($curPage, $pageSize)
            ->select();

        $pageObj = new \Org\Util\Page($totalCount, $pageSize);
        if (!empty($pageParams)) {
            $pageObj->parameter = $pageParams;
        }

        $pageLinks = $pageObj->show();

        return array($totalCount, $resultSet, $pageLinks);
    }
    /**
     * 前端调用，根据路径获取seo信息
     * @author veter
     * @date   2016-04-19T17:14:27+0800
     * @param  [type]                   $action [description]
     * @return [type]                           [description]
     */
    public function getSeoByPath($path)
    {
        return $this->field('title,keywords,description')
                    ->where(array('page_path' => $path))
                    ->find();
    }
}
