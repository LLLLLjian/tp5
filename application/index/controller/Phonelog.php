<?php

namespace app\index\controller;

use think\Controller;
use think\Request;
use think\Db;

class Phonelog extends Controller
{

    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        return $this->fetch();
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
        $mobile = input('post.mobile');

        exec("/usr/bin/python3.5 /var/nginx/html/tp5/python/mongoPythonForPhone.py {$mobile} 2>&1");
        echo json_encode(array('code' => 0, 'msg' => '成功'));
    }

    /**
     * 显示指定的资源
     *
     * @param int $id            
     * @return \think\Response
     */
    public function read($id)
    {
        $res = Db::name("phone_log")
            ->where("_id", $id)
            ->find();
        var_dump($res);exit;
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
        echo $id."update";exit;
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

        $mobile = trim(input('get.mobile'));
        if (!empty($mobile) && is_numeric($mobile) && preg_match("/^1[3-8]{1}\d{9}$/", $mobile)) {
            $where["mobile"] = ["=","{$mobile}"];
        }
        $phone_type = input('get.phone_type');
        if (!empty($phone_type)) {
            $where["phone_type"] = ["=","{$mobile}"];
        }
        $entry_date = input('get.entry_date');
        if (!empty($entry_date)) {
            $tempTimeS = strtotime($entry_date);
            $tempTimeE = $tempTimeS + 86399;
            $where["create_time"] = [">=","{$tempTimeS}"];
            $where["create_time"] = ["<=","{$tempTimeE}"];
        }

        $res = Db::name("phone_log")
        ->where($where)
        ->order(['id'=>'desc'])
        ->limit($skip, $limit)
        ->select(); 
        $countNum = Db::name("phone_log")
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
