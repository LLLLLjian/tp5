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
        $count = Db::connect("db_mongo")->name("phone_log")
            ->where("_id", $id)
            ->find();
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
        //
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
        $limit = input('get.limit');
        $skip = (input('get.page', 1) - 1) * $limit;
        $res = Db::connect("db_mongo")->name("phone_log")
            ->order(array(
                "_id" => -1
            ))
            ->limit($skip, $limit)
            ->select();
        $count = Db::connect("db_mongo")->name("counters")
            ->where("_id", "phone_log")
            ->find();
        $arr = array(
            'code' => 0,
            'msg' => '',
            'count' => $count['seq'],
            'data' => $res
        );

        echo json_encode($arr);
    }
}
