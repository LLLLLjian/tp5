<?php

namespace app\wechat\controller;

use think\Controller;
use think\Db;
use app\index\model\Wechatlogs;

class Wechat extends Controller
{

    public $table_name = "wechat_log";

    public function index()
    {
        // 先初始化微信
        $app = app('wechat.official_account');
        $app->server->push(function ($message) {
            $message['_id'] = Db::connect("db_mongo")->findAndModify($this->table_name);
            Db::connect("db_mongo")->name($this->table_name)->insert($message);
            $wechatLogsModel = new Wechatlogs();
            $wechatLogsModel->addWechatLogs($message);

            switch ($message['MsgType']) {
                case 'event':
                    if ($message['Event'] == 'subscribe') {
                        return "你终于关注我了呀！";
                    } else {
                        return '收到事件消息';
                    }
                    break;
                case 'text':
                    if (is_numeric($message['Content']) && preg_match("/^1[3-8]{1}\d{9}$/", $message['Content'])) {
                        $res = "";
                        $res = $this->getPhoneInfo($message['Content'], 2, 1);
                        return $res;
                    } else {
                        return '收到文字消息';
                    }
                    break;
                case 'image':
                    return '收到图片消息';
                    break;
                case 'voice':
                    return '收到语音消息';
                    break;
                case 'video':
                    return '收到视频消息';
                    break;
                case 'location':
                    return '收到坐标消息';
                    break;
                case 'link':
                    return '收到链接消息';
                    break;
                case 'file':
                    return '收到文件消息';
                    break;
                default:
                    return '你说的消息我接收记录到了';
                    break;
            }
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
    protected function getPhoneInfo($mobile, $sqlType = 1, $num = 1)
    {
        $res = array();
        if ($sqlType == 2) {
            $res = Db::connect("db_mongo")->name("phone_log")
                ->where("mobile", $mobile)
                ->find();
        } else {
            $res = Db::name("phone_log")
                ->where("mobile", $mobile)
                ->find();
        }

        // 防止出现死循环, 该手机号不在三方类库中
        if ($num >= 2) {
            return $res;
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
            return $this->getPhoneInfo($mobile, 2, $num + 1);
        }
        return $res;
    }
}
