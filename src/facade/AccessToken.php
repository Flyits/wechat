<?php
/**
 * Created by PhpStorm.
 * User: flyits
 * Date: 2019/7/15
 * Time: 14:15
 */

namespace flyits\wechat\facade;


use think\facade\Facade;

/**
 * Class Token
 * @package think\facade
 * @mixin
 */
class AccessToken extends Facade
{
	protected static function getFacadeClass()
	{
		return 'Token';
	}
}