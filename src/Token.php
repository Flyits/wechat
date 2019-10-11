<?php
/**
 * Created by PhpStorm.
 * User: flyits
 * Date: 2019/7/11
 * Time: 10:48
 */
declare(strict_types = 1);

namespace flyits\wechat;


use flyits\tool\Curl;
use think\facade\Cache;
use think\facade\Config;

class Token
{
    /**
     * 公众号AppId
     * @var string
     */
    protected $appId = '';

    /**
     * 公众号秘钥
     * @var string
     */
    protected $appSecret = '';

    /**
     * 静态实例
     * @var Token
     */
    protected static $instance;

    /**
     * AccessToken
     * @var string
     */
    protected $token = '';

    /**
     * AccessToken有效期
     * @var string
     */
    protected $expireTime = 0;

    /**
     * 错误代码
     * @var string
     */
    protected $errCode = '';

    /**
     * 错误信息
     * @var string
     */
    protected $errMsg = '';

    public function __construct()
    {
        $this->appId     = Config::get('wechat.app_id');
        $this->appSecret = Config::get('wechat.app_secret');
        $this->generate();
    }

    /**
     * @param array $err
     */
    public function setErr(array $err): void
    {
        $this->errCode = $err['errcode'];
        $this->errMsg  = $err['errmsg'];
    }

    /**
     * @throws
     */
    protected function generate()
    {
        if (Cache::has('wechat_token')) {
            $this->token  = Cache::get('wechat_token');
            $this->expireTime = Cache::get('wechat_token_expire');
        } else {
            $response = Curl::get(sprintf(Url::TOKEN, $this->appId, $this->appSecret));
            $response = json_decode($response, true);
            if (array_key_exists('access_token', $response)) {
                $this->token  = $response['access_token'];
                $this->expireTime = $_SERVER['REQUEST_TIME'] + $response['expire_in'] - 10;
                Cache::set('wechat_token', $this->token, $this->expireTime);
            } else {
                $this->setErr($response);
            }
        }
    }

    /**
     * 获取access_token
     * @time   2019/7/15 13:57
     * @return mixed
     * @throws
     */
    public function get()
    {
        if ($this->expireTime > $_SERVER['REQUEST_TIME'])
            $this->generate();
        return $this->token;
    }

    /**
     * 获取微信服务器IP地址
     * @time   2019/7/15 14:23
     * @return mixed
     * @throws
     */
    public function ipList()
    {
        $response = json_decode(Curl::get(sprintf(Url::IP_LIST, $this->token)), true);
        if (array_key_exists('errcode', $response)) {
            $this->setErr($response);
        }
        return $response;
    }

    public static function __callStatic($name, $arguments)
    {
        if (self::$instance != null)
            return call_user_func_array(self::$instance->$name, $arguments);
        else {
            self::$instance = new self();
            return call_user_func_array(self::$instance->$name, $arguments);
        }
    }
}