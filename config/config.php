<?php
/**
 * Created by PhpStorm.
 * User: flyits
 * Date: 2019/7/11
 * Time: 10:39
 */

return [
    // 微信公众号APPID
    'app_id'     => 'wxf3c5912daa7446ff',
    // 微信公众号开发者秘钥
    'app_secret' => '3a282400c06f37c7b8463c457fbdf5b0',
    // 接入微信密参
    'token'      => 'flyits',

    'material' => [

        // 素材是否保存
        'save' => false,
        // 素材保存路径
        'path' => \think\facade\App::getRootPath() . 'material',
    ],

    'pay' => [
        // 公众账号id
        'appid'       => '',
        // 商户号
        'mch_id'      => '',
        // API 秘钥
        'key'         => '',

        // 订单失效时间
        'expire'      => 1800,
        // 证书路径
        'sslCertPath' => '',
        // 证书路径
        'sslKeyPath'  => '',
        // 通知回调地址
        'notify_url'  => 'http://www.baiduc.com',
        // 加密方式 默认为MD5，支持HMAC-SHA256和MD5。
        'sign_type'   => 'md5',
    ]
];