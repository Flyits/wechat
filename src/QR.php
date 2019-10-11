<?php
/**
 * Created by PhpStorm.
 * User: flyits
 * Date: 2019/7/17
 * Time: 9:50
 */

declare(strict_types = 1);

namespace flyits\wechat;


use flyits\tool\Curl;
use flyits\wechat\facade\Token;

class QR
{
    /**
     * 二维码有效时间
     * @param
     */
    private $expireSeconds = 30;

    /**
     * 二维码类型 QR_SCENE为临时的整型参数值，QR_STR_SCENE为临时的字符串参数值，QR_LIMIT_SCENE为永久的整型参数值，QR_LIMIT_STR_SCENE为永久的字符串参数值
     * @param
     */
    private $actionName = 'QR_STR_SCENE';

    /**
     * 二维码ticket
     * @param
     */
    private $ticket = '';

    /**
     * @param mixed $expireSeconds
     * @return QR
     */
    public function setExpireSeconds($expireSeconds): QR
    {
        $this->expireSeconds = $expireSeconds;
        return $this;
    }

    /**
     * @param mixed $actionName
     * @return  QR
     */
    public function setActionName($actionName): QR
    {
        $this->actionName = $actionName;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTicket()
    {
        return $this->ticket;
    }

    /**
     * 生成二维码
     * @param int|string $value
     * @param int $expire 最大2592000
     * @param string $type QR_SCENE为临时的整型参数值，QR_STR_SCENE为临时的字符串参数值，QR_LIMIT_SCENE为永久的整型参数值，QR_LIMIT_STR_SCENE为永久的字符串参数值
     * @return mixed
     * @throws
     */
    public function create($value, string $type = null, int $expire = null)
    {
        $qrBody = [
            "expire_seconds" => $expire ?: $this->expireSeconds,
            "action_name"    => $type ?: $this->actionName,
            "action_info"    => [
                "scene" => [
                    is_string($value) ? 'scene_str' : 'scene_id' => $value
                ],
            ],
        ];
        if (strpos($qrBody['action_name'], 'LIMIT')) {
            unset($qrBody['expire_seconds']);
        }

        $result       = json_decode(Curl::post(sprintf(Url::QR_CREATE, Token::get()), $qrBody), true);
        $this->ticket = $result['ticket'] ?? '';
        return $result;

    }

    /**
     * 显示二维码
     * @param string $ticket
     * @return mixed
     * @throws
     */
    public function show(string $ticket = '')
    {
        $url = sprintf(Url::QR_SHOW, $ticket);
        return Curl::get($url);
    }

    /**
     * 长连接转短连接
     * @param string $longUrl
     * @return string
     * @throws
     */
    public function shortURL(string $longUrl): string
    {
        $url    = sprintf(Url::URL_TO_SHORT, Token::get());
        $result = json_decode(Curl::post($url, ['action' => 'long2short', 'long_url' => $longUrl]), true);
        return $result['short_url'] ?? json_encode($result);
    }
}