<?php

namespace app\index\controller;

use think\Db;
use think\facade\Session;
use app\index\model\User;

class Index extends Common
{
    public function index()
    {
        return $this->fetch();
    }

    //ifrm的内容
    public function welcome()
    {
        // 系统
        $systemInfo['os'] = PHP_OS;
        //ThinkPHP 版本
        $systemInfo['ThinkPHP'] = \think\facade\App::version();;
        // PHP版本
        $systemInfo['phpversion'] = PHP_VERSION;
        // 最大上传文件大小
        $systemInfo['maxuploadfile'] = ini_get('upload_max_filesize');
        // 脚本运行占用最大内存
        $systemInfo['memorylimit'] = get_cfg_var("memory_limit") ? get_cfg_var("memory_limit") : '-';
        //当前的IP
        $systemInfo['REMOTE_ADDR'] = $_SERVER['REMOTE_ADDR'];
        // 数据统计
        $sysRes = array();
        $tempRes = Db::query("SHOW TABLES");
        if (!empty($tempRes)) {
            foreach ($tempRes AS $key=>$value) {
                $sysRes[$key] = array(
                    'showName' => $value['Tables_in_tentcent_tp'],
                    'showUrl' => $this->getUrlByTable($value['Tables_in_tentcent_tp']),
                    'showNum' => Db::name($value['Tables_in_tentcent_tp'])->count()
                );
            }
        }
        $this->assign("sysRes", $sysRes);

        //当前日期
        $ymd = date("Y-m-d");
        $this->assign("ymd", $ymd);
        $this->assign("systemInfo", $systemInfo);
        return $this->fetch('welcome');
    }

    public function get_info()
    {
        $userModel = new User();
        $find = $userModel->getUserInfoByid();
        if (empty($find)) {
            return json(["code" => 0, "msg" => "不存在该用户的"]);
        }
        if (request()->isAjax()) {
            $list = request()->post();
            $result = Db::name("user")->where(["id" => $list["id"]])->update($list);
            if ($result) {
                return json(["code" => 1, "msg" => "编辑成功"]);
            } else {
                return json(["code" => 0, "msg" => "编辑失败"]);
            }
        }
        $this->assign("find", $find);
        return $this->fetch("get_info");
    }

    public function hello($name = 'ThinkPHP5')
    {
        return 'hello,' . $name;
    }

    public function testmongo()
    {
        $test1 = Db::connect("db_mongo")->name("counters")->select();
        return dump($test1);
    }

    public function testmongoinc()
    {
        $resId = Db::connect("db_mongo")->name("counters")
            ->where("_id", "wechat_log")
            ->setInc("seq");
        return dump($resId);
    }

    public function testmongoinc1()
    {
        $resId = Db::connect("db_mongo")->findAndModify("wechat_log");
        return dump($resId);
    }

    public function testphone()
    {
        $res = Db::connect("db_mongo")->name("phone_log")
            ->where("mobile", "13978412182")
            ->select();
        dump($res);
    }

    public function testphone1($mobile = '18634678077')
    {
        $a = $this->getPhoneInfo($mobile, 2);
        dump($a);
        exit();
    }

    public function getPhoneInfo($mobile, $sqlType = 1)
    {
        $res = array();
        if ($sqlType == 2) {
            $res = Db::connect("db_mongo")->name("phone_log")
                ->where("mobile", $mobile)
                ->find();
        } else {
            $res = "";
        }

        if (!empty($res)) {
            $resStr = "";
            $resStr .= "你查询的手机号为:" . $res['mobile'];
            $resStr .= ", 该手机号运营商为:" . $res['phone_type'];
            $resStr .= ", 该手机号的归属地为:" . $res['province'] . $res['city'];
            $resStr .= ", 归属地的邮编为:" . $res['zip_code'];
            $resStr .= ", 归属地的区号为:" . $res['area_code'];
            $res = $resStr;
        } else {
            exec("/usr/bin/python3.5 /var/nginx/html/tp5/python/mongoPythonForPhone.py {$mobile} 2>&1");
            return $this->getPhoneInfo($mobile, 2);
        }
        return $res;
    }

    /**
     * 通过表名获取
     */
    public function getUrlByTable($tableName)
    {
        if (empty($tableName)) {
            return "";
        } else {
            $tableName = trim($tableName, "s");
            // index/phonelog/index
            return "index/".str_replace("_", "", $tableName)."/index";
        }
    }

    public function getPwd()
    {
        exec("/usr/bin/python3.5 /var/nginx/html/tp5/python/mongoPythonForPhone.py 13834895301 2>&1", $out, $res);
        return dump($out);
    }
}
