<?php
/**
 * Created by PhpStorm.
 * User: flyits
 * Date: 2019/7/9
 * Time: 15:07
 */
declare(strict_types = 1);

namespace flyits\wechat;

use Exception;
use flyits\wechat\interfac\MessageResponse;

class Message implements MessageResponse
{
    protected $data = [];
    // 接收消息者的openid
    protected $toUserName = '';
    // 发送消息者的openid
    protected $fromUserName = '';
    // 消息创建时间
    protected $createTime = '';
    // 事件推送的消息类型
    protected $msgType = '';
    // 事件推送的事件类型
    protected $event = '';
    // 事件推送的事件KEY值
    protected $eventKey = '';
    // 二维码的ticket，可用来换取二维码图片
    protected $ticket = '';
    // 地理位置纬度
    protected $latitude = '';
    // 地理位置经度
    protected $longitude = '';
    // 地理位置精度
    protected $precision = '';
    // 回复消息的xml字符串
    protected $returnXml = '';

    /**
     * @throws Exception
     */
    public function __construct()
    {
        if (isset($_GET['echostr'])) {
            return $this;
        }
        $postStr = file_get_contents('php://input');
        if (!empty($postStr)) {
            //禁止外部实体注入
            libxml_disable_entity_loader(true);
            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
            $postObj = json_decode(json_encode($postObj), true);
            $this->setData($postObj)
                ->setToUserName($postObj)
                ->setFromUserName($postObj)
                ->setCreateTime($postObj)
                ->setMsgType($postObj)
                ->setEvent($postObj)
                ->setEventKey($postObj)
                ->setTicket($postObj)
                ->setLatitude($postObj)
                ->setLongitude($postObj)
                ->setPrecision($postObj);
            $this->eventPush();
        } else {
            throw new Exception('没有接收到任何消息！');
        }
    }

    private function eventPush()
    {
        if ($this->msgType == 'event')
            if ($this->event == 'subscribe') {//判断是否关注事件
                if (empty($this->eventKey))
                    $this->subscribe();//通过搜索公众号关注
                else
                    $this->qrCodeSubscribe();//通过扫描二维码关注
            } else
                switch ($this->event) {
                    case 'unsubscribe'://取消关注
                        $this->unSubscribe();
                        break;
                    case 'SCAN'://已关注时扫描带参二维码的时间推送
                        $this->qrCode();
                        break;
                    case 'CLICK'://用户点击菜单拉取消息时的事件推送
                        $this->menuClick();
                        break;
                    case 'VIEW'://用户点击菜单跳转链接时的事件推送
                        $this->menuView();
                        break;
                    case 'scancode_push'://用户扫码推事件的事件推送
                        $this->scanCodePush();
                        break;
                    case 'scancode_waitmsg'://用户扫码推事件且弹出“消息接收中”提示框的事件推送
                        $this->scanCodeWaitMsg();
                        break;
                    case 'pic_sysphoto'://用户弹出系统拍照发图的事件推送
                        $this->picSysPhoto();
                        break;
                    case 'pic_photo_or_album'://用户弹出拍照或者相册发图的事件推送
                        $this->picPhotoOrAlbum();
                        break;
                    case 'pic_weixin'://用户弹出微信相册发图器的事件推送
                        $this->picWeixin();
                        break;
                    case 'location_select'://用户弹出地理位置选择器的事件推送
                        $this->locationSelect();
                        break;
                    case 'LOCATION'://用户上报地理位置事件
                        $this->location();
                        break;
                    case 'TEMPLATESENDJOBFINISH'://模版消息发送任务 结果推送
                        $this->templateSendJobFinish();
                        break;
                }
        else
            switch ($this->msgType) {
                case 'image':
                    $this->receiveImage();
                    break;
                case 'text':
                    $this->receiveText();
                    break;
                case 'voice':
                    $this->receiveVoice();
                    break;
                case 'video':
                    $this->receiveVideo();
                    break;
                case 'music':
                    $this->receiveMusic();
                    break;
                case 'news':
                    $this->receiveNews();
                    break;
            }
    }

    /**
     * 设置事件推送的原始数据数组
     * @param array $data
     * @return $this
     */
    public function setData($data): Message
    {

        $this->data = is_array($data) ? $data : [];
        return $this;
    }

    /**
     * 设置接收消息的openid
     * @param mixed $toUserName
     * @return $this
     */
    public function setToUserName($toUserName): Message
    {

        $this->toUserName = is_string($toUserName) ? $toUserName : ($toUserName['ToUserName'] ?? '');
        return $this;
    }

    /**
     * 设置发送消息的openid
     * @param mixed $fromUserName
     * @return $this
     */
    public function setFromUserName($fromUserName): Message
    {
        $this->fromUserName = is_string($fromUserName) ? $fromUserName : ($fromUserName['FromUserName'] ?? '');;
        return $this;
    }

    /**
     * 设置事件推送消息的创建时间
     * @param mixed $createTime
     * @return $this
     */
    public function setCreateTime($createTime): Message
    {
        $this->createTime = is_string($createTime) ? $createTime : ($createTime['ToUserName'] ?? '');;
        return $this;
    }

    /**
     * 设置消息类型
     * @param mixed $msgType
     * @return $this
     */
    public function setMsgType($msgType): Message
    {
        $this->msgType = is_string($msgType) ? $msgType : ($msgType['MsgType'] ?? '');
        return $this;
    }

    /**
     * 设置事件类型
     * @param mixed $event
     * @return $this
     */
    public function setEvent($event): Message
    {
        $this->event = is_string($event) ? $event : ($event['Event'] ?? '');;
        return $this;
    }

    /**
     * 设置事件KEY值
     * @param mixed $eventKey
     * @return $this
     */
    public function setEventKey($eventKey): Message
    {
        $this->eventKey = is_string($eventKey) ? $eventKey : ($eventKey['EventKey'] ?? '');
        return $this;
    }

    /**
     * 设置二维码的ticket，可用来换取二维码图片
     * @param mixed $ticket
     * @return $this
     */
    public function setTicket($ticket): Message
    {
        $this->ticket = is_string($ticket) ? $ticket : ($ticket['Ticket'] ?? '');
        return $this;
    }

    /**
     * 设置地理位置纬度
     * @param mixed $latitude
     * @return $this
     */
    public function setLatitude($latitude): Message
    {
        $this->latitude = is_string($latitude) ? $latitude : ($latitude['Latitude'] ?? '');
        return $this;
    }

    /**
     * 设置地理位置经度
     * @param mixed $longitude
     * @return $this
     */
    public function setLongitude($longitude): Message
    {
        $this->longitude = is_string($longitude) ? $longitude : ($longitude['Longitude'] ?? '');
        return $this;
    }

    /**
     * 设置地理位置精度
     * @param mixed $precision
     * @return $this
     */
    public function setPrecision($precision): Message
    {
        $this->precision = is_string($precision) ? $precision : ($precision['Precision'] ?? '');
        return $this;
    }

    /**
     * 获取事件推送的原始消息
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * 获取消息发送者的openid
     * @return string
     */
    public function getToUserName(): string
    {
        return $this->toUserName;
    }

    /**
     * 获取消息接受者的openid
     * @return string
     */
    public function getFromUserName(): string
    {
        return $this->fromUserName;
    }

    /**
     * 获取事件推送消息创建的时间
     * @return string
     */
    public function getCreateTime(): string
    {
        return $this->createTime;
    }

    /**
     * 获取事件推送的消息类型
     * @return string
     */
    public function getMsgType(): string
    {
        return $this->msgType;
    }

    /**
     * 获取事件推送的事件类型
     * @return string
     */
    public function getEvent(): string
    {
        return $this->event;
    }

    /**
     * 获取事件推送的时间key值
     * @return string
     */
    public function getEventKey(): string
    {
        return is_array($this->eventKey) ? '测试号' : $this->eventKey;
    }

    /**
     * 获取扫描二维码事件的二维码ticket
     * @return string
     */
    public function getTicket(): string
    {
        return $this->ticket;
    }

    /**
     * 获取地理位置纬度
     * @return string
     */
    public function getLatitude(): string
    {
        return $this->latitude;
    }

    /**
     * 获取地理位置经度
     * @return string
     */
    public function getLongitude(): string
    {
        return $this->longitude;
    }

    /**
     * 获取地理位置精度
     * @return string
     */
    public function getPrecision(): string
    {
        return $this->precision;
    }

    /**
     * 设置消息回复的xml字符串
     * @param string $type
     * @param integer $count
     * @param array $article
     */
    public function setReturnXml(string $type = 'text', int $count = 1, array $article = []): void
    {
        $base = '<xml>
  					<ToUserName><![CDATA[' . $this->fromUserName . ']]></ToUserName>
  					<FromUserName><![CDATA[' . $this->toUserName . ']]></FromUserName>
  					<CreateTime>' . time() . '</CreateTime>
  					<MsgType><![CDATA[' . $type . ']]></MsgType>
  					%s
				  </xml>';
        switch ($type) {
            case 'text':
                $this->returnXml = sprintf($base, ' <Content><![CDATA[%s]]></Content>');
                break;
            case 'image':
                $this->returnXml = sprintf($base, '<Image><MediaId><![CDATA[%s]]></MediaId></Image>');
                break;
            case 'voice':
                $this->returnXml = sprintf($base, '<Voice><MediaId><![CDATA[%s]]></MediaId></Voice>');
                break;
            case 'video':
                $this->returnXml = sprintf($base, '<Video>
																	<MediaId><![CDATA[%s]]></MediaId><Title><![CDATA[%s]]></Title>
 																	<Description><![CDATA[%s]]></Description>
 																  </Video>');
                break;
            case 'music':
                $this->returnXml = sprintf($base, '<Music>
																	<Title><![CDATA[%s]]></Title>
    																<Description><![CDATA[%s]]></Description>
    																<MusicUrl><![CDATA[%s]]></MusicUrl>
    																<HQMusicUrl><![CDATA[%s]]></HQMusicUrl>
    																<ThumbMediaId><![CDATA[%s]]></ThumbMediaId>
 																  </Music>');
                break;
            case 'articles':
                $str = '';

                foreach ($article as $index => $item) {
                    $str .= " <item>
      							<Title><![CDATA[$item->title]]></Title>
      							<Description><![CDATA[$item->description]]></Description>
      							<PicUrl><![CDATA[$item->picurl]]></PicUrl>
      							<Url><![CDATA[$item->url]]></Url>
    						  </item>";
                }
                $this->returnXml = sprintf($base, '<ArticleCount>' . $count . '</ArticleCount> <Articles>' . $str . '</Articles>');
                break;
        }
    }

    /**
     * send text message.
     * @param string $content 回复的消息内容（换行：在content中能够换行，微信客户端就支持换行显示）
     * @time   2019/7/9 14:26
     * @return string
     */
    public function sendText(string $content): string
    {
        $this->setReturnXml('text');
        $resultStr = sprintf($this->returnXml, $content);
        return $resultStr;
    }

    /**
     * send image message.
     * @param string $mediaId 通过素材管理中的接口上传多媒体文件，得到的id
     * @time   2019/7/9 14:26
     * @return string
     */
    public function sendImage(string $mediaId): string
    {
        $this->setReturnXml('image');
        $resultStr = sprintf($this->returnXml, $mediaId);
        return $resultStr;
    }

    /**
     * send voice message.
     * @param string $mediaId 通过素材管理中的接口上传多媒体文件，得到的id
     * @time   2019/7/9 14:26
     * @return string
     */
    public function sendVoice(string $mediaId): string
    {
        $this->setReturnXml('voice');
        $resultStr = sprintf($this->returnXml, $mediaId);
        return $resultStr;
    }

    /**
     * send video message.
     * @param string $mediaId 通过素材管理中的接口上传多媒体文件，得到的id
     * @param string $title 视频消息的标题
     * @param string $description 视频消息的描述
     * @time   2019/7/9 14:26
     * @return string
     */
    public function sendVideo(string $mediaId, string $title = '', string $description = ''): string
    {
        $this->setReturnXml('video');
        $resultStr = sprintf($this->returnXml, $mediaId, $title, $description);
        return $resultStr;
    }

    /**
     * send music message.
     * @param string $title 音乐标题
     * @param string $description 音乐描述
     * @param string $musicUrl 音乐链接
     * @param string $HQMusicUrl 高质量音乐链接，WIFI环境优先使用该链接播放音乐
     * @param string $ThumbMediaId 缩略图的媒体id，通过素材管理中的接口上传多媒体文件，得到的id
     * @time   2019/7/9 14:26
     * @return string
     */
    public function sendMusic(string $ThumbMediaId, string $title = '', string $description = '', string $musicUrl = '', string $HQMusicUrl = ''): string
    {
        $this->setReturnXml('music');
        $resultStr = sprintf($this->returnXml, $title, $description, $musicUrl, $HQMusicUrl, $ThumbMediaId);
        return $resultStr;
    }

    /**
     * 回复图文消息
     * @param array $articles 图文消息内容
     * @param int $count 图文消息个数；当用户发送文本、图片、视频、图文、地理位置这五种消息时，开发者只能回复1条图文消息；其余场景最多可回复8条图文消息
     * @return string
     * @throws \Exception
     */
    public function sendArticles(array $articles, int $count = 0): string
    {
        $count = $count ?? count($articles);
        if ($count > 8) {
            throw new Exception('图文消息最多只能发送8条');
        }
        $this->setReturnXml('articles', $count, $articles);
        return $this->returnXml;
    }

    /**
     * 用户关注事件
     * @param
     * @return mixed
     * @throws
     */
    public function subscribe()
    {
    }

    /**
     * 二维码关注
     * @param
     * @return mixed
     * @throws
     */
    public function qrCodeSubscribe()
    {
    }

    /**
     * 扫描带参二维码
     * @param
     * @return mixed
     * @throws
     */
    public function qrCode()
    {
    }

    /**
     * 取消关注
     * @param
     * @return mixed
     * @throws
     */
    public function unSubscribe()
    {
    }

    /**
     * 用户点击菜单
     * @param
     * @return mixed
     * @throws
     */
    public function menuClick()
    {
    }

    /**
     * 用户点击菜单跳转
     * @param
     * @return mixed
     * @throws
     */
    public function menuView()
    {
    }

    /**
     * 扫码推事件用户点击按钮后，微信客户端将调起扫一扫工具，完成扫码操作后显示扫描结果（如果是URL，将进入URL）
     * @param
     * @return mixed
     * @throws
     */
    public function scanCodePush()
    {
    }

    /**
     * 扫码推事件且弹出“消息接收中”提示框用户点击按钮后，微信客户端将调起扫一扫工具，完成扫码操作后，将扫码的结果传给开发者
     * @param
     * @return mixed
     * @throws
     */
    public function scanCodeWaitMsg()
    {
    }

    /**
     * 用户弹出系统拍照发图的事件推送
     * @param
     * @return mixed
     * @throws
     */
    public function picSysPhoto()
    {
    }

    /**
     * 用户弹出拍照或者相册发图的事件推送
     * @param
     * @return mixed
     * @throws
     */
    public function picPhotoOrAlbum()
    {
    }

    /**
     * 用户弹出微信相册发图器的事件推送
     * @param
     * @return mixed
     * @throws
     */
    public function picWeixin()
    {
    }

    /**
     * 用户弹出地理位置选择器的事件推送
     * @param
     * @return mixed
     * @throws
     */
    public function locationSelect()
    {
    }

    /**
     * 用户上报地理位置事件
     * @param
     * @return mixed
     * @throws
     */
    public function location()
    {
    }

    /**
     * 模版消息发送任务 结果推送
     * @param
     * @return mixed
     * @throws
     */
    public function templateSendJobFinish()
    {
    }

    /**
     * 收到图片消息
     * @param
     * @return mixed
     * @throws
     */
    public function receiveImage()
    {
    }

    /**
     * 收到文字消息
     * @param
     * @return mixed
     * @throws
     */
    public function receiveText()
    {
    }

    /**
     * 收到语音消息
     * @param
     * @return mixed
     * @throws
     */
    public function receiveVoice()
    {
    }

    /**
     * 收到视频消息
     * @param
     * @return mixed
     * @throws
     */
    public function receiveVideo()
    {
    }

    /**
     * 收到音乐消息
     * @param
     * @return mixed
     * @throws
     */
    public function receiveMusic()
    {
    }

    /**
     * 收到图文消息
     * @param
     * @return mixed
     * @throws
     */
    public function receiveNews()
    {
    }

    public function index($token): string
    {
        $echoStr = $_GET['echostr'];
        if ($echoStr) {
            $signature = $_GET['signature'];
            $timestamp = $_GET['timestamp'];//时间戳
            $nonce     = $_GET['nonce'];//随机数
            $tmpArr    = array($token, $timestamp, $nonce);
            sort($tmpArr, SORT_STRING);
            $tmpStr = implode($tmpArr);
            $tmpStr = sha1($tmpStr);
            if ($tmpStr == $signature) {
                echo $echoStr;
                exit();
            } else {
                return "1";
            }
        }
    }
}