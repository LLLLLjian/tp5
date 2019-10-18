<?php
namespace app\index\model;

use think\Model;
use think\Db;

class Wechatlogs extends Model
{
    protected $table = "wechat_logs";

    protected function test($value)
    {
        $array = array(
            "subscribe" => 1,
            "unsubscribe" => 2,
            "text" => 3,
            "image" => 4,
            "voice" => 5,
            "video" => 6,
            "shortvideo" => 7,
            "location" => 8,
            "link" => 9
        );
        return $array[$value];
    }

    // 记录微信日志
    public function addWechatLogs($data)
    {
        $inserArr = array();
        $inserArr["m_wechat_log_id"] = $data['_id'];
        $inserArr["wechat_type"] = $data['MsgType'];
        $inserArr["ToUserName"] = $data['ToUserName'];
        $inserArr["FromUserName"] = $data['FromUserName'];
        $inserArr["create_time"] = time();
        $this->save($inserArr);
    }

    // 根据mongoId获取信息
    public function getInfoByMogoId($id)
    {
        if (!empty($id)) {
            $res0 = Db::connect("db_mongo")->name("wechat_log")
            ->where("_id", $id)
            ->find();

            $res1 = $this->where("m_wechat_log_id", $id)->find();

            $res = array_merge($res0, $res1);
            dump($res, $res0, $res1);exit;
        }
    }
}
