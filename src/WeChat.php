<?php
/**
 * Created by PhpStorm.
 * User: flyits
 * Date: 2019/7/16
 * Time: 8:55
 */

namespace flyits\wechat;



class WeChat
{
	/**
	 *
	 * @var string
	 */
	protected $token;
	
	/**
	 *
	 * @var string
	 */
	protected $http;
	
	
	public function __construct()
	{
		$this->token   = (new Token())->get();
	}
	
	/**
	 * @return string
	 */
	public function getToken(): string
	{
		return $this->token;
	}
	
}