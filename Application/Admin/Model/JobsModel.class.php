<?php
namespace Admin\Model;

use Think\Model;

class JobsModel extends Model
{

    /**
     * [addJobs description]
     * @author jinkkuanghqu@gmail.com
     * @dateTime 2016-02-18T17:10:11+0800
     * @param    [type]                   $type       [description]
     * @param    [type]                   $foreignKey [description]
     */
    public function addJobs($type, $payload)
    {
        return $this->data(array(
            'type'        => $type,
            'payload'     => serialize($payload),
            'add_time'    => time(),
            'update_time' => time(),
        ))->add();
    }

    /**
     * [updateJobs description]
     * @author jinkkuanghqu@gmail.com
     * @dateTime 2016-02-18T18:17:40+0800
     * @param    [type]                   $type    [description]
     * @param    [type]                   $payload [description]
     * @return   [type]                            [description]
     */
    public function updateJobs($id, $payload)
    {
        return $this->where(array(
            'id' => $id,
        ))->save(array(
            'payload'     => serialize($payload),
            'update_time' => time(),
        ));
    }

    /**
     * [getJobs description]
     * @author jinkkuanghqu@gmail.com
     * @dateTime 2016-03-21T10:19:15+0800
     * @param    [type]                   $type      [description]
     * @param    integer                  $page      [description]
     * @param    integer                  $page_size [description]
     * @return   [type]                              [description]
     */
    public function getJobs($type, $page = 0, $page_size = 10)
    {
        return $this->where("type='$type'")->page($page . ',' . $page_size)->select();
    }

    /**
     * [getJobNumber description]
     * @author jinkkuanghqu@gmail.com
     * @dateTime 2016-03-21T10:19:31+0800
     * @param    [type]                   $type [description]
     * @return   [type]                         [description]
     */
    public function getJobNumber($type)
    {
        return $this->where("type='$type'")->count();
    }

    /**
     * 通过 type 和 payload Jobs 长度
     * @author jinkkuanghqu@gmail.com
     * @dateTime 2016-03-21T10:19:57+0800
     * @param    string                   $value [description]
     * @return   [type]                          [description]
     */
    public function getJobsCountByPayload($type, $payload = array())
    {
        return $this->where([
            'type'    => $type,
            'payload' => serialize($payload),
        ])->count();
    }
    /**
     * 更新时间
     * @author jinkkuanghqu@gmail.com
     * @dateTime 2016-03-21T13:08:52+0800
     * @param    [type]                   $jobsId [description]
     * @return   [type]                           [description]
     */
    public function updateTime($jobsId)
    {
        $this->where(['id' => $jobsId])->setField('update_time', time());
    }

    public function destroy($jobId)
    {
        $this->delete($jobId);
    }
}
