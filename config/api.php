<?php
/**
 * Created by PhpStorm.
 * User: flyits
 * Date: 2019/7/11
 * Time: 10:39
 */

return [
	//
	'base'  => 'https://api.weixin.qq.com/cgi-bin/',
	'token' => 'token?grant_type=client_credential&appid=%s&secret=%s',
	'menu'  => [
		'create'      => 'menu/create?access_token=%s',
		'get'         => 'menu/create?access_token=%s',
		'delete'      => 'menu/create?access_token=%s',
		'conditional' => [
			'create' => 'menu/addconditional?access_token=%s',
			'delete' => 'menu/delconditional?access_token=%s',
			'try'    => 'menu/trymatch?access_token=%s',
		],
	]
];