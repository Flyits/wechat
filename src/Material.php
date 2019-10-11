<?php
/**
 * Created by PhpStorm.
 * User: flyits
 * Date: 2019/7/17
 * Time: 9:48
 */

namespace flyits\wechat;


use think\facade\Config;

class Material
{
	/**
	 *
	 * @var string
	 */
	protected $config = [];
	
	public function __construct()
	{
		$this->config = Config::get('wechat.material');
	}
	
	
}