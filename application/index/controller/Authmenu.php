<?php

namespace app\index\controller;

use think\Controller;
use think\Request;
use think\Db;

class Authmenu extends Controller
{
    //主页
    public function index()
    {
        $data = Db::name("auth_menu")->order("sort asc")->select();
        $result = to_tree($data);
        $this->assign("res", json_encode($result));
        return $this->fetch("index");
    }

    //    菜单的添加
    public function add()
    {
        if (request()->isPost()) {
            $list = request()->post();
            if (empty($list["parent_id"])) {
                $list["type"] = "menu";  //菜单栏控制
                $list["ico"] = "fa-" . input("ico");
                $list["parent_id"] = 0;
                $list["url"] = "#";
            } elseif ($list["parent_id"] != 0) {
                $find = Db::name("auth_menu")->where("id", "=", $list["parent_id"])->find();
                if ($find["parent_id"] == 0) {
                    $list["type"] = 1;  //菜单栏控制
                } else {
                    $list["type"] = 2;  //节点栏控制
                }
            }

            $findname = Db::name("auth_menu")->where("name", "=", $list["name"])->find();
            if (!empty($findname)) {
                return json(["code" => 0, "msg" => "该菜单名称已存在"]);
            }
            $result = Db::name("auth_menu")->insert($list);
            if ($result) {
                return json(["code" => 1, "msg" => "添加成功"]);
            } else {
                return json(["code" => 0, "msg" => "添加失败"]);
            }
        }
        $id = input("param.id");
        $find = Db::name("auth_menu")->where(["id" => $id])->find();
        $this->assign("find", $find);
        return $this->fetch("add");
    }

    /*
     * 编辑
     */
    public function edit()
    {
        $id = input("param.id");
        if (request()->isPost()) {
            $list = request()->post();
            $result = Db::name("auth_menu")->where("id", "=", $list["id"])->update($list);
            if ($result) {
                return json(["code" => 1, "msg" => "编辑成功"]);
            } else {
                return json(["code" => 0, "msg" => "编辑失败"]);
            }
        }
        $where["id"] = ["=", $id];
        $find = Db::name("auth_menu")->where($where)->find();
        $s_find = Db::name("auth_menu")->where("id", "=", $find["parent_id"])->field("name")->find();
        $this->assign("find", $find);
        $this->assign("s_find", $s_find);


        return $this->fetch("edit");
    }

    /*
     * 删除
     */
    public function del()
    {
        $id = input("param.id");
        $model = new Authmenu();
        $data = $model->del_menu($id);
        writelog("删除菜单成功");
        return json($data);
    }
}
