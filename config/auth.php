<?php

return [
    /***
     * 跳过权限验证方法列表
     ***/
    'permission_authlist' => [
        'index/index/index',
		'index/index/welcome',
        'index/login/index',  //主页
        'index/login/login', //登录
        'index/login/loginout', //退出
        'index/upload/img_save',//图片上传
        'index/upload/qr_code',//二维码的生成
        'index/common/clear',  //清除缓存
        'index/index/get_info', //个人信息
        'index/login/info', //个人信息查看
    ],
];
