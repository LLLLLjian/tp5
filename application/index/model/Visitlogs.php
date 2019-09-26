<?php

namespace app\index\model;

use think\Model;
use think\Request;
use think\facade\Session;

class Visitlogs extends Model
{
    protected $table = "visit_logs";
    protected $auto = ['user_id', 'ip', 'visit_url'];

    protected function setIpAttr() {
        return request()->ip();
    }

    protected function setUseridAttr() {
        return Session::get("id");
    }

    // 记录登录日志
    public function addVisitLogs()
    {
        $inserArr = array();
        $inserArr["user_id"] = Session::get("id");
        $inserArr["session_info"] = json_encode(Session::get());
        $inserArr["visit_url"] = request()->path();
        $inserArr['method'] = request()->method();
        $inserArr["visit_time"] = time();
        $inserArr["excu_time"] = json_encode(request());
        $inserArr["ip"] = request()->ip();
        $this->save($inserArr);
    }
}
