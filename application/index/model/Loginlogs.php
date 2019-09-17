<?php

namespace app\index\model;

use think\Model;
use think\Request;
use think\facade\Session;

class Loginlogs extends Model
{
    protected $table = "login_logs";

    // 记录登录日志
    public function addLoginLogs()
    {
        var_dump(Session::get("id"));exit;
        $inserArr = array();
        $inserArr["user_id"] = Session::get("id");
        $inserArr["ip"] = request()->ip();
        $inserArr["browser_type"] = get_broswer_type();
        $inserArr["browser"] = get_broswer();
        $inserArr["type_os"] = get_os();
        $this->save($inserArr);

        $user = new User;
        $user->save(['last_login_time'  => time(), 'last_login_ip' => $inserArr["ip"]], ['id' => $inserArr["user_id"]]);
    }
}
