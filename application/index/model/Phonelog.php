<?php

namespace app\index\model;

use think\Model;

class Phonelog extends Model
{
    protected $table = "phone_log";

    // 通过手机号获取
    public function getInfoByMobile($mobile = "")
    {
        $mobileInfo = "";
        if (!empty($mobile)) {
            $mobileInfo = $this->alias('pl')
            ->join('region_log rl', 'rl.zipcode = pl.zip_code', 'LEFT')
            ->where(array("mobile" => $mobile))->find();
        }
        return $mobileInfo;
    }
}
