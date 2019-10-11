<?php
/**
 * Created by PhpStorm.
 * User: flyits
 * Date: 2019/7/17
 * Time: 9:49
 */

namespace flyits\wechat;


use flyits\tool\Curl;
use think\Exception;
use think\facade\Config;

class User
{
    /**
     * 创建标签
     * @param string $tagName
     * @return mixed
     * @throws
     */
    public function createTag(string $tagName)
    {
        $url  = sprintf(Url::TAG_CREATE, Token::get());
        $data = [
            'tag' => [
                'name' => $tagName
            ]
        ];
        return Curl::post($url, $data);
    }

    /**
     * 编辑标签
     * @param int $tagId
     * @param string $tagName
     * @return mixed
     * @throws
     */
    public function updTag(int $tagId, string $tagName)
    {
        $url  = sprintf(Url::TAG_UPDATE, Token::get());
        $data = [
            'tag' => [
                'id'   => $tagId,
                'name' => $tagName
            ]
        ];
        return Curl::post($url, $data);
    }

    /**
     * 获取公众号已创建的标签
     * @param
     * @return mixed
     * @throws
     */
    public function getTags()
    {
        $url = sprintf(Url::TAG_GET, Token::get());
        return Curl::get($url);
    }

    /**
     * 删除标签
     * @param int $tagId
     * @return mixed
     * @throws
     */
    public function delTag(int $tagId)
    {
        $url = sprintf(Url::TAG_DELETE, Token::get());
        return Curl::post($url);
    }

    /**
     * 批量为用户打标签
     * @param int $tagId
     * @param array $openidList
     * @return mixed
     * @throws
     */
    public function batchTagging(int $tagId, array $openidList)
    {
        $url = sprintf(Url::TAG_BATCH_TAGGING, Token::get());
        return Curl::post($url, ['openid_list' => $openidList, 'tag_id' => $tagId]);
    }

    /**
     * 批量为用户取消标签
     * @param int $tagId
     * @param array $openidList
     * @return mixed
     * @throws
     */
    public function batchUnTagging(int $tagId, array $openidList)
    {
        $url = sprintf(Url::TAG_BATCH_UNTAGGING, Token::get());
        return Curl::post($url, ['openid_list' => $openidList, 'tag_id' => $tagId]);
    }

    /**
     * 获取用户身上的标签列表
     * @param string $openid
     * @return mixed
     * @throws
     */
    public function getTag(string $openid)
    {
        $url = sprintf(Url::TAG_GET_USER, Token::get());
        return Curl::post($url, ['openid' => $openid]);
    }

    /**
     * 设置用户备注名
     * @param string $openid
     * @param string $remark
     * @return mixed
     * @throws
     */
    public function userRemark(string $openid, string $remark)
    {
        $url = sprintf(Url::REMARK_USER, Token::get());
        return Curl::post($url, ['openid' => $openid, 'remark' => $remark]);
    }

    /**
     * 获取用户基本信息
     * @param string $openid
     * @return mixed
     * @throws
     */
    public function userInfo(string $openid)
    {
        $url = sprintf(Url::USER_INFO, Token::get(), $openid);
        return Curl::get($url);
    }

    /**
     * 批量获取用户基本信息
     * @param array $openidList
     * @return mixed
     * @throws
     */
    public function usersInfo(array $openidList)
    {
        $url = sprintf(Url::USERS_INFO, Token::get());
        return Curl::post($url, ['openid_list' => $openidList]);
    }

    /**
     * 获取用户列表
     * @param string $nextOpenid
     * @return mixed
     * @throws
     */
    public function userList(string $nextOpenid = '')
    {
        $url = sprintf(Url::USER_LIST, Token::get(), $nextOpenid);
        return Curl::get($url);
    }

    /**
     * 黑名单列表
     * @param string $beginOpenid
     * @return mixed
     * @throws
     */
    public function blackList(string $beginOpenid = '')
    {
        $url = sprintf(Url::BLACK_LIST, Token::get());
        return Curl::post($url, ['begin_openid' => $beginOpenid]);
    }

    /**
     * 拉黑用户
     * @param array $openidList
     * @return mixed
     * @throws
     */
    public function black(array $openidList)
    {
        if (count($openidList) > 20) {
            throw new Exception('需要拉入黑名单的用户的openid，一次拉黑最多允许20个');
        }

        $url = sprintf(Url::BLACK_BATCH, Token::get());
        return Curl::post($url, ['openid_list' => $openidList]);
    }

    /**
     * 取消拉黑用户
     * @param array $openidList
     * @return mixed
     * @throws
     */
    public function unBlack(array $openidList)
    {
        $url = sprintf(Url::BLACK_BATCH_DEL, Token::get());
        return Curl::post($url, ['openid_list' => $openidList]);
    }

    /**
     * 用户登录授权
     * @param string $redirectUrl 授权后重定向的回调链接地址， 请使用 urlEncode 对链接进行处理
     * @param string $scope 应用授权作用域，snsapi_base （不弹出授权页面，直接跳转，只能获取用户openid），
     * snsapi_userinfo （弹出授权页面，可通过openid拿到昵称、性别、所在地。并且， 即使在未关注的情况下，只要用户授权，也能获取其信息 ）
     * @param string $state 重定向后会带上state参数，开发者可以填写a-zA-Z0-9的参数值，最多128字节
     * @return mixed
     * @throws
     */
    public function login(string $redirectUrl, string $state, string $scope = 'snsapi_base')
    {
        if (!preg_match('[0-9A-Za-z]{0,120}', $state))
            throw new Exception('state参数只能是a-zA-Z0-9的参数值，最大长度128');
        $redirectUrl = strpos($redirectUrl, '//') ? urlencode($redirectUrl) : $redirectUrl;
        $url         = sprintf(Url::USER_AUTH, Config::get('wechat.app_id'), $redirectUrl, $scope, $state);
        Header("location:$url");
        exit();
    }

    /**
     * 通过code换取网页授权access_token
     * @param string $code
     * @return mixed
     * @throws
     */
    public function getAccessToken(string $code)
    {
        $appId     = Config::get('wechat.app_id');
        $appSecret = Config::get('wechat.app_secret');
        $url       = sprintf(Url::AUTH_ACCESS_TOKEN, $appId, $appSecret, $code);
        return Curl::get($url);
    }

    /**
     * 刷新ACCESS_TOKEN
     * @param string $refreshToken
     * @return mixed
     * @throws
     */
    public function refreshAccessToken(string $refreshToken)
    {
        $url = sprintf(Url::ACCESS_TOKEN_REFRESH, Config::get('wechat.app_id'), $refreshToken);
        return Curl::get($url);
    }

    /**
     * 拉取用户信息(需scope为 snsapi_userinfo)
     * @param string $accessToken
     * @param string $openid
     * @return mixed
     * @throws
     */
    public function getUserInfo(string $accessToken, string $openid)
    {
        $url = sprintf(Url::USER_GET_INFO, $accessToken, $openid);
        return Curl::get($url);
    }

    /**
     * 检测ACCESS_TOKEN是否有效
     * @param string $accessToken
     * @return mixed
     * @throws
     */
    public function checkAccessToken(string $accessToken)
    {
        $url = sprintf(Url::ACCESS_TOKEN_CHECK, Token::get());
        return Curl::get($url);
    }
}