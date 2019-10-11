<?php
/**
 * Created by PhpStorm.
 * User: flyits
 * Date: 2019/7/16
 * Time: 8:41
 */

namespace flyits\wechat;


class Url
{
    // 获取access_token
    const TOKEN = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=%s&secret=%s';
    // 获取微信服务器ip列表
    const IP_LIST = 'https://api.weixin.qq.com/cgi-bin/getcallbackip?access_token=%s';

    // ping
    const PING = 'https://api.weixin.qq.com/cgi-bin/callback/check?access_token=%s';

    // todo 创建菜单
    const MENU_CREATE = 'https://api.weixin.qq.com/cgi-bin/menu/create?access_token=%s';
    // 获取菜单
    const MENU_GET = 'https://api.weixin.qq.com/cgi-bin/menu/get?access_token=%s';
    // 删除菜单（包括个性化菜单）
    const MENU_DELETE = 'https://api.weixin.qq.com/cgi-bin/menu/get?access_token=%s';
    // 创建个性化菜单
    const MENU_CONDITIONAL_CREATE = 'https://api.weixin.qq.com/cgi-bin/menu/addconditional?access_token=%s';
    // 删除个性化菜单
    const MENU_CONDITIONAL_DELETE = 'https://api.weixin.qq.com/cgi-bin/menu/delconditional?access_token=%s';
    // 测试个性化菜单匹配结果
    const MENU_CONDITIONAL_TRY = 'https://api.weixin.qq.com/cgi-bin/menu/trymatch?access_token=%s';
    // 获取自定义菜单配置接口
    const SELF_MENU = 'https://api.weixin.qq.com/cgi-bin/get_current_selfmenu_info?access_token=ACCESS_TOKEN';

    // todo 上传临时素材

    // todo 用户管理
    // 创建标签
    const TAG_CREATE = 'https://api.weixin.qq.com/cgi-bin/tags/create?access_token=%s';
    // 获取标签
    const TAG_GET = 'https://api.weixin.qq.com/cgi-bin/tags/get?access_token=%s';
    // 编辑标签
    const TAG_UPDATE = 'https://api.weixin.qq.com/cgi-bin/tags/update?access_token=%s';
    // 删除标签
    const TAG_DELETE = 'https://api.weixin.qq.com/cgi-bin/tags/delete?access_token=%s';
    // 获取标签下粉丝列表
    const TAG_GET_USERS = 'https://api.weixin.qq.com/cgi-bin/user/tag/get?access_token=%s';
    // 批量为用户打标签
    const TAG_BATCH_TAGGING = 'https://api.weixin.qq.com/cgi-bin/tags/members/batchtagging?access_token=%s';
    // 批量为用户取消标签
    const TAG_BATCH_UNTAGGING = 'https://api.weixin.qq.com/cgi-bin/tags/members/batchuntagging?access_token=%s';
    // 获取用户身上的标签列表
    const TAG_GET_USER = 'https://api.weixin.qq.com/cgi-bin/tags/getidlist?access_token=%s';
    // 设置用户备注名
    const REMARK_USER = 'https://api.weixin.qq.com/cgi-bin/user/info/updateremark?access_token=%s';
    // 获取用户基本信息
    const USER_INFO = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token=%s&openid=%s&lang=zh_CN';
    // 批量获取用户基本信息
    const USERS_INFO = 'https://api.weixin.qq.com/cgi-bin/user/info/batchget?access_token=%s';
    // 获取用户列表
    const USER_LIST = 'https://api.weixin.qq.com/cgi-bin/user/get?access_token=%s&next_openid=%s';
    // 黑名单列表
    const BLACK_LIST = 'https://api.weixin.qq.com/cgi-bin/tags/members/getblacklist?access_token=%s';
    // 拉黑用户
    const BLACK_BATCH = 'https://api.weixin.qq.com/cgi-bin/tags/members/batchblacklist?access_token=%s';
    // 取消拉黑用户
    const BLACK_BATCH_DEL = 'https://api.weixin.qq.com/cgi-bin/tags/members/batchunblacklist?access_token=%s';
    // 用户授权
    const USER_AUTH = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=%s&redirect_uri=%s&response_type=code&scope=%s&state=%s#wechat_redirect';
    // 通过code换取网页授权access_token
    const AUTH_ACCESS_TOKEN = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid=%s&secret=%s&code=%s&grant_type=authorization_code';
    // 刷新网页授权access_token
    const ACCESS_TOKEN_REFRESH = 'https://api.weixin.qq.com/sns/oauth2/refresh_token?appid=%s&grant_type=refresh_token&refresh_token=%s';
    // 拉取用户信息
    const USER_GET_INFO = 'https://api.weixin.qq.com/sns/userinfo?access_token=%s&openid=%s&lang=zh_CN';
    // 检测网页授权ACCESS_TOKEN是否有效
    const ACCESS_TOKEN_CHECK = 'https://api.weixin.qq.com/sns/auth?access_token=%s&openid=%s';
}