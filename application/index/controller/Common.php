<?php

/**
 * File Name: Common.php
 * Author: llllljian
 * mail: 18634678077@163.com
 * Created Time: Thu 18 Jul 2019 12:40:27 PM CST
 **/

namespace app\index\controller;

use think\Controller;
use think\Db;
use think\Request;
use think\facade\Session;
use think\facade\Env;
use app\index\model\Menus;
use app\index\model\User;
use app\index\model\Visitlogs;

class Common extends Controller
{
    // 检查是否登录
    public function initialize()
    {
        $userModel = new User();
        $menusModel = new Menus();

        // 取出seession的值
        $id = Session::get("id");

        if (empty($id)) {
            $this->redirect("index/login/index");
        } else {
            $userInfo = $userModel->getUserInfoByid();
        }

        $roleslevel = Session::get("roleslevel");

        if (($roleslevel & 1) > 0) {

        } else {
            // 获取当前的操作的url
            $url = getActionUrl();
            // 过滤首页和欢迎页
            if (!in_array($url, config("auth.permission_authlist"))) {
                if (request()->isPost()) {
                    $this->error('你没有该操作权限');
                } else {
                    exit("你没有权限操作");
                }
            }
        }
        
        // 记录日志
        $visitLogsModel = new Visitlogs();
        $visitLogsModel->addVisitLogs();

        // 获取当前用户可以访问的菜单
        $menuInfo = $menusModel->getMenusArrByRoles();
        $this->assign('menuInfo', $menuInfo); // 读取当前管理员的所有菜单栏的列表
        $this->assign('userInfo', $userInfo); // 读取当前管理员的信息
        parent::initialize(); // TODO: Change the autogenerated stub
    }

    // 空方法跳转
    public function _empty()
    {
        $this->redirect('error/page');
    }

    public function clear()
    {
        $cachePath = Env::get('runtime_path') . 'cache/';
        $tempPath = Env::get('runtime_path'). 'temp/';
        if (delete_dir_file($cachePath) || delete_dir_file($tempPath)) {
            return json(["code" => 1, "msg" => "清除缓存成功"]);
        } else {
            return json(["code" => 0, "msg" => "清除缓存失败"]);
        }
    }
}
