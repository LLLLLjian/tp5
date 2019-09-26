<?php

namespace app\index\model;

use think\Model;
use think\Request;
use think\facade\Session;
use think\Db;
use think\Cache;
use think\Config;
use think\Debug;
use think\Response;

class Visitlogs extends Model
{
    protected $table = "visit_logs";
    protected $auto = ['user_id', 'ip', 'visit_url'];

    protected function setIpAttr()
    {
        return request()->ip();
    }

    protected function setUseridAttr()
    {
        return Session::get("id");
    }

    // 记录登录日志
    public function addVisitLogs()
    {

        $request = request();
        $response = response();
        $contentType = $response->getHeader('Content-Type');
        $accept = $request->header('accept');

        // 获取基本信息
        $runtime = number_format(microtime(true) - THINK_START_TIME, 10, '.', '');
        $reqs = $runtime > 0 ? number_format(1 / $runtime, 2) : '∞';
        $mem = number_format((memory_get_usage() - THINK_START_MEM) / 1024, 2);
        
        $inserArr = array();
        $inserArr["user_id"] = Session::get("id");
        $inserArr["ip"] = request()->ip();
        $inserArr["session_info"] = session_id();
        $inserArr["visit_url"] = request()->path();
        $inserArr['method'] = request()->method();
        $inserArr["visit_time"] = $_SERVER['REQUEST_TIME'];
        $inserArr["excu_time"] = number_format($runtime, 6);
        $inserArr["throughput_rate"] = $reqs;
        $inserArr["memory_consumption"] = $mem;
        $inserArr["query_info"] = Db::$queryTimes . ' queries ' . Db::$executeTimes . ' writes ';
        $inserArr["cache_info"] = Cache::$readTimes . ' reads,' . Cache::$writeTimes . ' writes';
        $inserArr["file_loading"] = count(get_included_files());
        $inserArr["config_loading"] = count(Config::get());
        $this->save($inserArr);
    }
}
