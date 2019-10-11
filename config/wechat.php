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
];