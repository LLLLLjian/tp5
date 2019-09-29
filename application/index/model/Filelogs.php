<?php

namespace app\index\model;

use think\Model;
use think\Request;
use think\facade\Session;

class Filelogs extends Model
{
    protected $table = "file_logs";

    // 记录日志
    public function addFileLogs($filePath, $type)
    {
        $inserArr = array();
        $inserArr["user_id"] = Session::get("id");
        $inserArr['file_path'] = $filePath;
        $inserArr['file_type'] = $type;
        $inserArr['create_time'] = time();
        $this->save($inserArr);
    }
}
