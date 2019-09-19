<?php

namespace app\index\controller;

use think\Controller;
use think\Config;
use think\Loader;
use think\Db;

class UploadController extends BaseController
{
    /*
     * 图片上传公共方法
     */
    public function img_save()
    {
        $type = input("param.type");
        $img = request()->file('file');
        // 移动到框架应用根目录/public/uploads/ 目录下
        $info = $img->move(ROOT_PATH . 'public' . DS . 'upload' . DS . $type . DS . date('Y') . DS . date('m-d'), md5(microtime(true)));
        if ($info) {
            // 成功上传后 获取上传信息
            $imgPath = "/upload/$type/" . date('Y') . '/' . date('m-d') . '/' . $info->getSaveName();
            return json(["code" => 1, "msg" => "上传成功", "url" => $imgPath]);
        } else {
            // 上传失败获取错误信息0
            return json(["code" => 0, "msg" => $img->getError(), "url" => '']);
        }
    }

    /*
     * 生成二维码 （不带logo图片的）
     */
    public function qr_code()
    {
        $type = input("param.type");
        //二维码URL参数
        $text = session("id");
        $filenames = qc_code($text, $type);
        $filenames_string = str_replace('\\', '/', $filenames);
        $file_name = substr($filenames_string, strripos($filenames_string, "/upload") + 1);
        try {
            return json(["code" => 1, "msg" => "生成成功", "url" => '/' . $file_name]);
        } catch (\Exception $e) {
            return json(["code" => 1, "msg" => "生成失败", "url" => '']);
        }
    }
}