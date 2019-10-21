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
		$res = array();
        if (!empty($id)) {
			$id = intval($id);
            $res0 = Db::connect("db_mongo")->name("wechat_log")
            ->where("_id", $id)
            ->find();

            $res1 = $this->where("m_wechat_log_id", $id)->find()->toArray();

            $res = array_merge($res0, $res1);
        }
		return $res;
    }
}
