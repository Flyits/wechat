<?php
/**
 * Created by PhpStorm.
 * User: flyits
 * Date: 2019/7/15
 * Time: 14:15
 */

namespace flyits\wechat\facade;


use think\Facade;

/**
 * Class Token
 * @package think\facade
 * @mixin
 */
class Token extends Facade
{
    protected static function getFacadeClass()
    {
        return 'flyits\wechat\Token';
    }
}