<?php

namespace app\index\model;

use think\Model;

class Phonelog extends Model
{
    protected $table = "phone_log";

    protected $mobileDetail = array(
        'id' => '手机号ID',
        'mobile' => '手机号',
        'phone_type' => '运营商',
        'province' => '归属省',
        'city' => '归属市',
        'zip_code' => '邮编',
        'area_code' => '区号',
        'rlId' => '归属地区ID',
        'shortname' => '归属地简称',
        'level' => '归属地级别',
        'merger_name' => '归属地全称',
        'lng' => '经度',
        'lat' => '纬度',
        'english' => '地区英文名'
    );

    // 通过手机号获取
    public function getInfoByMobile($mobile = "")
    {
        $mobileInfo = "";
        if (!empty($mobile)) {
            $mobileInfo = $this->alias('pl')
            ->field('pl.*, rl.*, rl.id AS rlId')
            ->join('region_log rl', 'rl.zipcode = pl.zip_code', 'LEFT')
            ->where(array("pl.mobile" => $mobile))
            ->where(array("rl.level" => 2))
            ->find()->toArray();

            if (!empty($mobileInfo)) {
                $mobileDetail = $this->mobileDetail;
                foreach ($mobileInfo AS $key=>$value) {
                    $mobileInfo[$key] = array(
                        'showKey' => isset($mobileDetail[$key]) ? $mobileDetail[$key] : $key,
                        'showValue' => $value
                    );
                }
            }
        }
        return $mobileInfo;
    }
}
