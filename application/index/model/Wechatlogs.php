<?php

namespace app\index\model;

use think\Model;

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
}
