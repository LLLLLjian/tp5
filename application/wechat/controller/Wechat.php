<?php
/**
 * File Name: Wechat.php
 * Author: llllljian
 * mail: 18634678077@163.com
 * Created Time: Sun 30 Jun 2019 03:35:47 PM CST
 **/
namespace app\wechat\controller;

use think\Controller;
use think\Db;

class Wechat extends Controller
{

    public $table_name = "wechat_log";

    public function index()
    {
        // 先初始化微信
        $app = app('wechat.official_account');
        $app->server->push(function ($message) {
            if ($message['MsgType'] == "event" && $message['Event'] == 'subscribe') {
                return "你终于关注我了呀！";
            }
            $message['_id'] = Db::connect("db_mongo")->findAndModify($this->table_name);
            Db::connect("db_mongo")->name($this->table_name)
                ->insert($message);
            if (preg_match("/^1[3-8]{1}\d{9}$/", $message['Content'])) {
                $res = array();
                $res = $this->getPhoneInfo($message['Content'], 2);
                return $res;
            }
            return "你说的消息我接收记录到了";
        });
        
        $app->server->serve()->send();
    }

    public function index1()
    {
        // 先初始化微信
        $echoStr = input('param.echostr');
        
        if ($this->checkSignature()) {
            echo $echoStr;
            exit();
        }
    }

    private function checkSignature()
    {
        $param = input('param.');
        
        $signature = $param["signature"];
        $timestamp = $param["timestamp"];
        $nonce = $param["nonce"];
        $token = config("wechat.official_account.default.token");
        
        $tmpArr = array(
            $token,
            $timestamp,
            $nonce
        );
        // use SORT_STRING rule
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode($tmpArr);
        $tmpStr = sha1($tmpStr);
        
        if ($tmpStr == $signature) {
            return true;
        } else {
            return false;
        }
    }
    
    // 处理手机号相关信息
    protected function getPhoneInfo($mobile, $sqlType = 1)
    {
        $res = array();
        if ($sqlType == 2) {
            $res = Db::connect("db_mongo")->name("phone_log")
                ->where("mobile", $mobile)
                ->find();
        } else {
            $res = "";
        }
        
        if (! empty($res)) {
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
}
