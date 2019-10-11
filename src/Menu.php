<?php
/**
 * Created by PhpStorm.
 * User: flyits
 * Date: 2019/7/15
 * Time: 14:29
 */

namespace flyits\wechat;


use flyits\tool\Curl;

class Menu extends WeChat
{
	
	
	/**
	 * 创建菜单
	 * @param array $menu
	 * @author flyits
	 * @time   2019/7/15 17:50
	 * @throws
	 * @return mixed
	 */
	public function create(array $menu = [])
	{
		$url = sprintf(Url::MENU_CREATE, $this->token);
		return Curl::post($url, $menu);
	}
	
	/**
	 * 获取菜单
	 * @author flyits
	 * @time   2019/7/16 8:56
	 * @throws
	 * @return mixed
	 */
	public function get()
	{
		$url = sprintf(Url::MENU_GET, $this->token);
		return Curl::get($url);
	}
	
	/**
	 * 删除菜单
	 * @author flyits
	 * @time   2019/7/16 9:00
	 * @throws
	 * @return mixed
	 */
	public function delete()
	{
		$url = sprintf(Url::MENU_DELETE, $this->token);
		return Curl::post($url);
	}
	
	/**
	 * 添加个性化菜单
	 * @param array $menu
	 * @author flyits
	 * @time   2019/7/16 9:01
	 * @throws
	 * @return mixed
	 */
	public function addConditional(array $menu = [])
	{
		$url = sprintf(Url::MENU_CONDITIONAL_CREATE, $this->token);
		return Curl::post($url, $menu);
	}
	
	/**
	 * 删除个性化菜单
	 * @author flyits
	 * @time   2019/7/16 9:29
	 * @throws
	 * @return mixed
	 */
	public function delConditional()
	{
		$url = sprintf(Url::MENU_CONDITIONAL_DELETE, $this->token);
		return Curl::post($url);
	}
	
	/**
	 * 测试个性化菜单匹配结果
	 * @param string $userId user_id可以是粉丝的OpenID，也可以是粉丝的微信号
	 * @author flyits
	 * @time   2019/7/16 9:50
	 * @throws
	 * @return mixed
	 */
	public function tryConditional(string $userId = '')
	{
		$url = sprintf(Url::MENU_CONDITIONAL_TRY, $this->token);
		return Curl::post($userId, ['user_id' => $userId]);
	}
	
}