<?php

/**
 * 配置文件
 *
 */

return [
    /*
      * 默认配置，将会合并到各模块中
      */
    'default'         => [
        /*
         * 指定 API 调用返回结果的类型：array(default)/object/raw/自定义类名
         */
        'response_type' => 'array',
        /*
         * 使用 ThinkPHP 的缓存系统
         */
        'use_tp_cache'  => true,
        /*
         * 日志配置
         *
         * level: 日志级别，可选为：
         *                 debug/info/notice/warning/error/critical/alert/emergency
         * file：日志文件位置(绝对路径!!!)，要求可写权限
         */
        'log' => [
            'permission' => 0777,
            'level' => Env::get('WECHAT_LOG_LEVEL', 'debug'),
            'file' => Env::get('WECHAT_LOG_FILE', '/tmp/easywechat-dev/easywechat_' . date('Ymd') . '.log'),
        ],
    ],

    //公众号
    'official_account' => [
        'default' => [
            // AppID
            'app_id' => Env::get('WECHAT_OFFICIAL_ACCOUNT_APPID'),
            // AppSecret
            'secret' => Env::get('WECHAT_OFFICIAL_ACCOUNT_SECRET'),
            // Token
            'token' => Env::get('WECHAT_OFFICIAL_ACCOUNT_TOKEN'),
            // EncodingAESKey
            'aes_key' => Env::get('WECHAT_OFFICIAL_ACCOUNT_AES_KEY'),
            /*
             * OAuth 配置
             *
             * scopes：公众平台（snsapi_userinfo / snsapi_base），开放平台：snsapi_login
             * callback：OAuth授权完成后的回调页地址(如果使用中间件，则随便填写。。。)
             */
            //'oauth' => [
            //    'scopes'   => array_map('trim',
            //        explode(',', Env::get('WECHAT_OFFICIAL_ACCOUNT_OAUTH_SCOPES', 'snsapi_userinfo'))),
            //    'callback' => Env::get('WECHAT_OFFICIAL_ACCOUNT_OAUTH_CALLBACK', '/examples/oauth_callback.php'),
            //],
        ],
    ],

    //第三方开发平台
    //'open_platform'    => [
    //    'default' => [
    //        'app_id'  => Env::get('WECHAT_OPEN_PLATFORM_APPID', ''),
    //        'secret'  => Env::get('WECHAT_OPEN_PLATFORM_SECRET', ''),
    //        'token'   => Env::get('WECHAT_OPEN_PLATFORM_TOKEN', ''),
    //        'aes_key' => Env::get('WECHAT_OPEN_PLATFORM_AES_KEY', ''),
    //    ],
    //],

    //小程序
    //'mini_program'     => [
    //    'default' => [
    //        'app_id'  => Env::get('WECHAT_MINI_PROGRAM_APPID', ''),
    //        'secret'  => Env::get('WECHAT_MINI_PROGRAM_SECRET', ''),
    //        'token'   => Env::get('WECHAT_MINI_PROGRAM_TOKEN', ''),
    //        'aes_key' => Env::get('WECHAT_MINI_PROGRAM_AES_KEY', ''),
    //    ],
    //],

    //支付
    //'payment'          => [
    //    'default' => [
    //        'sandbox'    => Env::get('WECHAT_PAYMENT_SANDBOX', false),
    //        'app_id'     => Env::get('WECHAT_PAYMENT_APPID', ''),
    //        'mch_id'     => Env::get('WECHAT_PAYMENT_MCH_ID', 'your-mch-id'),
    //        'key'        => Env::get('WECHAT_PAYMENT_KEY', 'key-for-signature'),
    //        'cert_path'  => Env::get('WECHAT_PAYMENT_CERT_PATH', 'path/to/cert/apiclient_cert.pem'),    // XXX: 绝对路径！！！！
    //        'key_path'   => Env::get('WECHAT_PAYMENT_KEY_PATH', 'path/to/cert/apiclient_key.pem'),      // XXX: 绝对路径！！！！
    //        'notify_url' => 'http://example.com/payments/wechat-notify',                           // 默认支付结果通知地址
    //    ],
    //    // ...
    //],

    //企业微信
    //'work'             => [
    //    'default' => [
    //        'corp_id'  => 'xxxxxxxxxxxxxxxxx',
    //        'agent_id' => 100020,
    //        'secret'   => Env::get('WECHAT_WORK_AGENT_CONTACTS_SECRET', ''),
    //        //...
    //    ],
    //],
];
