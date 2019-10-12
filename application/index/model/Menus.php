<?php

namespace app\index\model;

use think\Model;
use think\Db;

class Menus extends Model
{
    protected $table = "menus";

    // 通过roles获取当前登录人可以查看的菜单
    public function getMenusArrByRoles($roleslevel = 0)
    {
        if (empty($roleslevel)) {
            $roleslevel = session("roleslevel");
        }

        $menu = $this
            ->where(array("parent_id" => 0, "menu_type" => 1))
            ->where('roleslevel', 'exp', " & {$roleslevel} > 0")
            ->order("menu_sort asc")
            ->select();

        $menusArr = array();
        if (!empty($menu)) {
            foreach ($menu as $key => $value) {
                $pid = $value['id'];
                $menusArr[$key] = $value;

                $cmenu = Db::name("menus")
                    ->where(array('parent_id' => $pid, 'menu_type' => 2))
                    ->where('roleslevel', 'exp', " & {$roleslevel} > 0")
                    ->order("menu_sort asc")
                    ->select();
                if (!empty($cmenu)) {
                    $menusArr[$key]['cmenu'] = $cmenu;
                } else {
                    $menusArr[$key]['cmenu'] = array();
                }
            }
        }
        return $menusArr;
    }
}
