<?php

namespace app\index\model;

use think\Model;
use think\Request;
use think\facade\Session;

class Filelogs extends Model
{
    protected $table = "file_logs";

    // 记录登录日志
    public function addFileLogs()
    {
        $inserArr = array();
        $this->save($inserArr);
    }
}
