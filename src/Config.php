<?php
/**
 * Created by PhpStorm.
 * User: flyits
 * Date: 2019/7/11
 * Time: 11:02
 */
declare(strict_types = 1);

namespace flyits\wechat;


class Config
{
	/**
	 * 配置参数
	 * @var
	 */
	protected $config = [];
	
	/**
	 * 配置文件路径
	 * @var
	 */
	protected $path = '';
	
	/**
	 * 配置文件后缀
	 * @var
	 */
	protected $ext = '';
	
	public function __construct(string $path = null, string $ext = '.php')
	{
		$this->path = $path ?? null;
		$this->ext  = $ext;
	}
	
	/**
	 *
	 * @access public
	 * @param string $file
	 * @param string $name
	 * @return mixed
	 */
	public function parse(string $file, string $name): array
	{
		$type = pathinfo($file, PATHINFO_EXTENSION);
		
		switch ($type) {
			case 'php':
				$config = include $file;
				break;
			case'yml':
			case'yaml':
				if (function_exists('yaml_parse_file')) {
					$config = yaml_parse_file($file);
				}
				break;
			case 'ini':
				$config = parse_ini_file($file, true, INI_SCANNER_TYPED) ?: [];
				break;
			case 'json':
				$config = json_decode(file_get_contents($file), true);
				break;
		}
		return isset($config) && is_array($config) ? $this->set($config, strtolower($name)) : [];
	}
	
	/**
	 * 设置配置参数 name为数组则为批量设置
	 * @access public
	 * @param  array  $config 配置参数
	 * @param  string $name   配置名
	 * @return array
	 */
	public function set(array $config, string $name = null): array
	{
		if (!empty($name)) {
			if (isset($this->config[$name])) {
				$result = array_merge($this->config[$name], $config);
			} else {
				$result = $config;
			}
			
			$this->config[$name] = $result;
		} else {
			$result = $this->config = array_merge($this->config, array_change_key_case($config));
		}
		
		return $result;
	}
}