<?php

namespace app\index\controller;

use think\Controller;
use think\Request;
use think\Db;
use app\index\model\Phonelog AS PhonelogModel;

class Phonelog extends Common
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
        $phoneLogModel = new PhonelogModel();
        $res = $phoneLogModel->getInfoByMobile($mobile);
        if (empty($res)) {
            exec("/usr/bin/python3.5 /var/nginx/html/tp5/python/mongoPythonForPhone.py {$mobile} 2>&1");
            echo json_encode(array('code' => 0, 'msg' => '添加成功'));
        } else {
            echo json_encode(array('code' => 1, 'msg' => '添加失败, 该手机号已存在.'));
        }

        
    }

    /**
     * 显示指定的资源
     *
     * @param int $id            
     * @return \think\Response
     */
    public function read($mobile)
    {
        if (!empty($mobile)) {
            $phoneLogModel = new PhonelogModel();
            $res = $phoneLogModel->getInfoByMobile($mobile);
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
            $where[] = ["mobile", "=", "{$mobile}"];
        }
        $phone_type = input('get.phone_type');
        if (!empty($phone_type)) {
            if ($phone_type == 1) {
                $phone_type = "移动";
            } elseif ($phone_type == 2) {
                $phone_type = "联通";
            } elseif ($phone_type == 3) {
                $phone_type = "电信";
            }
            $where[] = ["phone_type", "=", "{$phone_type}"];
        }
        $entry_date = input('get.entry_date');
        if (!empty($entry_date)) {
            $tempTimeS = strtotime($entry_date);
            $tempTimeE = $tempTimeS + 86399;
            $where[] = ["create_time", ">=", "{$tempTimeS}"];
			$where[] = ["create_time", "<=", "{$tempTimeE}"];
        }

        $res = Db::name("phone_log")
            ->where($where)
            ->order(['id' => 'desc'])
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
