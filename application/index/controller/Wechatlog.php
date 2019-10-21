<?php

namespace app\index\controller;

use think\Controller;
use think\Request;
use think\Db;
use app\index\model\Wechatlogs as WechatlogsModel;

class Wechatlog extends Common
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        $app = app('wechat.official_account');$userSummary = $app->data_cube->userSummary('2019-10-01', '2019-10-30');var_dump($userSummary);return $this->fetch();
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
        //
    }

    /**
     * 保存新建的资源
     *
     * @param \think\Request $request            
     * @return \think\Response
     */
    public function save(Request $request)
    {
        //
    }

    /**
     * 显示指定的资源
     *
     * @param int $id            
     * @return \think\Response
     */
    public function read($id)
    {
        if (!empty($id)) {
            $wechatLogsModel = new WechatlogsModel();
            $res = $wechatLogsModel->getInfoByMogoId($id);
            $this->assign('res', $res);
            return $this->fetch();
        } else {
            exit("出错误了！！");
        }
    }

    /**
     * 显示编辑资源表单页.
     *
     * @param int $id            
     * @return \think\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * 保存更新的资源
     *
     * @param \think\Request $request            
     * @param int $id            
     * @return \think\Response
     */
    public function update(Request $request, $id)
    {
        echo $id . "update";
        exit;
    }

    /**
     * 删除指定资源
     *
     * @param int $id            
     * @return \think\Response
     */
    public function delete($id)
    {
        //
    }

    public function list()
    {
        $where = array();
        $limit = input('get.limit');
        $skip = (input('get.page', 1) - 1) * $limit;

        $res = Db::name("wechat_logs")
            ->where($where)
            ->order(['id' => 'desc'])
            ->limit($skip, $limit)
            ->select();
        $countNum = Db::name("wechat_logs")
            ->where($where)
            ->count();
        $arr = array(
            'code' => 0,
            'msg' => '',
            'count' => $countNum,
            'data' => $res
        );

        echo json_encode($arr);
    }
}
