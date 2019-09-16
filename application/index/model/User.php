<?php

namespace app\index\model;

use think\Db;
use think\Model;

class User extends Model
{
    protected $table = "user";

    //获取角色
    public function getAdminRole()
    {
        $result = Db::name("auth_role")->select();
        foreach ($result as $key => $value) {
            $user = Db::name("user")
                ->field("username")
                ->where(array('role_id' => $value["role_id"]))
                ->select();
            $tmp = array();
            if (!empty($user)) {
                foreach ($user as $k => $v) {
                    $tmp[] = $v["username"];
                }
                $result[$key]["admin"] = implode(',', $tmp);
            } else {
                $result[$key]["admin"] = '无';
            }
        }
        return $result;
    }

    /*
     * 获取子节点
     */
    public function getPermissionInfo($where)
    {
        $result = Db::name("auth_menu")
            ->where($where)
            ->select();
        foreach ($result as $key => $value) {
            $result[$key]['parent'] = Db::name("auth_menu")
                ->where(array('id' => $value["parent_id"]))
                ->field("name")
                ->find();
        }
        return $result;
    }

    /*
     * 获取所有的菜单栏以及子节点
     *
     */
    public function getAllPermissionInfo()
    {
        $menus = new AuthmenuModel();
        $data = $menus->getMenu();
        $tmp = array();
        foreach ($data as $key => $value) {
            if (!empty($value["cmenu"])) {
                foreach ($value['cmenu'] as $k => $v) {
                    $tmp = DB::name('auth_menu')
                        ->where(array('type' => 'per', 'parent_id' => $v['id']))
                        ->select();
                    if (!empty($tmp)) {
                        $data[$key]['cmenu'][$k]['per'] = $tmp;
                    } else {
                        $data[$key]['cmenu'][$k]['per'] = array();
                    }
                }
            }
        }
        return $data;
    }

    /*
     * 获取二级菜单
     */
    public function getCmenuInfo()
    {
        $where["parent_id"] = array('neq', '0');
        $where["type"] = array('eq', 'menu');
        $menu = Db::name("auth_menu")
            ->where($where)
            ->order("sort asc")
            ->select();
        return $menu;
    }
}
