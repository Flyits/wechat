<?php


namespace flyits\wechat\pay;


use flyits\wechat\Url;
use think\Exception;
use think\facade\Request;
use think\helper\Str;

class UnifiedOrder extends Base
{
    /**
     * 设备号
     * @var string
     */
    protected $deviceInfo = null;


    /**
     * 商品描述
     * @var string
     */
    protected $body = null;

    /**
     * 商品详情
     * @var string
     */
    protected $detail = null;

    /**
     * 附加数据
     * @var string
     */
    protected $attach = null;

    /**
     * 商户订单号
     * @var string
     */
    protected $outTradeNo = null;

    /**
     * 标价币种
     * @var string
     */
    protected $feeType = null;

    /**
     * 标价金额
     * @var integer
     */
    protected $totalFee = null;

    /**
     * 终端ip
     * @var string
     */
    protected $spbillCreateIp = null;

    /**
     * 交易起始时间
     * @var string
     */
    protected $timeStart = null;

    /**
     * 交易结束时间
     * @var string
     */
    protected $timeExpire = null;

    /**
     * 订单优惠标记
     * @var string
     */
    protected $goodsTag = null;

    /**
     * 通知地址
     * @var string
     */
    protected $notifyUrl = null;

    /**
     * 交易类型
     * @var string
     */
    protected $tradeType = null;

    /**
     * 商品id
     * @var string
     */
    protected $productId = null;

    /**
     * 指定支付方式
     * @var string
     */
    protected $limitPay = null;

    /**
     * 用户标识
     * @var string
     */
    protected $openid = null;

    /**
     * 电子发票入口开放标识
     * @var string
     */
    protected $receipt = null;

    /**
     * 场景信息
     * @var array
     */
    protected $sceneInfo = null;


    /**
     * 架构函数
     * @param
     * @return mixed
     * @throws
     */
    public function __construct()
    {
        $this->setLimit(['appid', 'mch_id', 'device_info', 'nonce_str', 'sign', 'sign_type', 'body', 'detail', 'attach', 'out_trade_no', 'fee_type', 'total_fee', 'spbill_create_ip', 'time_start', 'time_expire', 'goods_tag', 'notify_url', 'trade_type', 'product_id', 'limit_pay', 'openid', 'receipt', 'scene_info']);

        //判断是否是手机用户
        if (Request::isMobile()) {
            $agent = $_SERVER['HTTP_USER_AGENT'];
            if ($wx = strpos($agent, 'MicroMessenger')) {
                //检测微信版本
                $wxVersion = substr(substr($agent, $wx), 15, 5);
                if ($wxVersion < '5.0') {
                    throw new Exception(['msg' => '微信版本过低，不支持支付功能，请先升级微信版本。']);
                }
                $this->setTradeType('JSAPI');
            } else {
                //非微信内部打开
                $this->setTradeType('MWEB');
            }
        } else {
            $this->setTradeType('NATIVE');
        }
        $this->setMustParams();

        parent::__construct();
        $this->setDefault(['nonce_str', 'notify_url', 'spbill_create_ip', 'time_start', 'time_expire'], $this);
        return $this;
    }

    /**
     * 设置设备号
     * @param string $deviceInfo
     * @return $this
     */
    public function setDeviceInfo(string $deviceInfo): self
    {
        $this->deviceInfo = $deviceInfo;
        $this->setRequest(['device_info' => $deviceInfo]);
        return $this;
    }

    /**
     * 设置商品描述
     * @param string $body
     * @return  $this
     */
    public function setBody(string $body): self
    {
        $this->body = $body;
        $this->setRequest(['body' => $this->body]);
        return $this;
    }

    /**
     * 设置商品详情
     * @param string $detail
     * @return $this
     */
    public function setDetail(string $detail): self
    {
        $this->detail = $detail;
        $this->setRequest(['detail' => $this->detail]);
        return $this;
    }

    /**
     * 设置附加数据
     * @param string $attach
     * @return  $this
     */
    public function setAttach(string $attach): self
    {
        $this->attach = $attach;
        $this->setRequest(['attach' => $this->attach]);
        return $this;
    }

    /**
     * 设置标价币种
     * @param string $feeType
     * @return $this
     */
    public function setFeeType(string $feeType = 'CNY'): self
    {
        $this->feeType = $feeType;
        $this->setRequest(['fee_type' => $this->feeType]);
        return $this;
    }

    /**
     * 设置标价金额
     * @param int $totalFee
     * @return $this
     */
    public function setTotalFee(int $totalFee): self
    {
        $this->totalFee = $totalFee;
        $this->setRequest(['total_fee' => $this->totalFee]);
        return $this;
    }

    /**
     * 设置终端IP
     * @param string $spbillCreateIp
     * @return $this
     */
    public function setSpbillCreateIp(string $spbillCreateIp = ''): self
    {
        $this->spbillCreateIp = $spbillCreateIp ?: Request::ip();
        $this->setRequest(['spbill_create_ip' => $this->spbillCreateIp]);
        return $this;
    }

    /**
     * 设置交易起始时间
     * @param string $timeStart
     * @return $this
     */
    public function setTimeStart(string $timeStart = ''): self
    {
        $this->timeStart = $timeStart ?: date('YmdHis');
        $this->setRequest(['time_start' => $this->timeStart]);
        return $this;
    }

    /**
     * 设置交易结束时间
     * @param string $timeExpire
     * @return $this
     */
    public function setTimeExpire(string $timeExpire = ''): self
    {
        $this->timeExpire = $timeExpire ?: date('YmdHis', Request::time() + $this->config['expire']);
        $this->setRequest(['time_expire' => $this->timeExpire]);
        return $this;
    }

    /**
     * 设置订单优惠标记
     * @param string $goodsTag
     * @return  $this
     */
    public function setGoodsTag(string $goodsTag): self
    {
        $this->goodsTag = $goodsTag;
        $this->setRequest(['goods_tag' => $this->goodsTag]);
        return $this;
    }

    /**
     * 设置交易类型
     * @param string $tradeType
     * @return $this
     * @throws \Exception
     */
    public function setTradeType(string $tradeType): self
    {
        $limit = ['JSAPI', 'NATIVE', 'APP', 'MWEB'];
        if (!in_array($tradeType, $limit)) {
            throw new Exception('非法的交易类型,该值必须是' . implode(',', $limit) . '之一');
        }
        $this->tradeType = $tradeType;
        $this->setRequest(['trade_type' => $tradeType]);
        return $this;
    }

    /**
     * 设置商品id
     * @param string $productId
     * @return $this
     */
    public function setProductId(string $productId): self
    {
        $this->productId = $productId;
        $this->setRequest(['product_id' => $this->productId]);
        return $this;
    }

    /**
     * 设置支付方式
     * @param string $limitPay
     * @return $this
     */
    public function setLimitPay(string $limitPay = 'no_credit'): self
    {
        $this->limitPay = $limitPay;
        $this->setRequest(['limit_pay' => $this->limitPay]);
        return $this;
    }

    /**
     * 设置用户标识
     * @param string $openid
     * @return $this
     */
    public function setOpenid(string $openid): self
    {
        $this->openid = $openid;
        $this->setRequest(['openid' => $this->openid]);
        return $this;
    }

    /**
     * 设置电子发票入口开放标识
     * @param string $receipt
     * @return $this
     */
    public function setReceipt(string $receipt): self
    {
        $this->receipt = $receipt;
        $this->setRequest(['receipt' => $this->receipt]);
        return $this;
    }

    /**
     * 设置场景信息
     * @param array $sceneInfo
     * {    "store_info" : {
     *      "id": "SZTX001",                    -门店id    id    否    String(32)    SZTX001    门店编号，由商户自定义
     *      "name": "腾大餐厅",                  -门店名称    name    否    String(64)    腾讯大厦腾大餐厅    门店名称 ，由商户自定义
     *      "area_code": "440305",              -门店行政区划码    area_code    否    String(6)    440305    门店所在地行政区划码，详细见《最新县及县以上行政区划代码》
     *      "address": "科技园中一路腾讯大厦" }}   -门店详细地址    address    否    String(128)    科技园中一路腾讯大厦    门店详细地址 ，由商户自定义
     * @return $this
     */
    public function setSceneInfo(array $sceneInfo): self
    {
        $this->sceneInfo = json_encode($sceneInfo, JSON_UNESCAPED_UNICODE);
        $this->setRequest(['scene_info' => $this->sceneInfo]);
        return $this;
    }

    /**
     * 设置请求参数中必要参数
     * @param array
     * @return $this
     */
    protected function setMustParams(array $mustParams = []): UnifiedOrder
    {
        $pub    = ['appid', 'mch_id', 'nonce_str', 'sign', 'body', 'out_trade_no', 'total_fee', 'spbill_create_ip', 'notify_url', 'trade_type'];
        $jsApi  = ['openid'];
        $native = [];
        $mweb   = ['scene_info'];
        switch ($this->getTradeType()) {
            case'JSAPI':
                $this->mustParams = array_merge($pub, $jsApi);
                break;
            case'NATIVE':
                $this->mustParams = array_merge($pub, $native);
                break;
            case 'MWEB':
            default:
                $this->mustParams = array_merge($pub, $mweb);
        }
        return $this;
    }

    /**
     * 获取当前支付方式
     * @return string
     */
    public function getTradeType(): string
    {
        return $this->tradeType;
    }

    /**
     * 生成前端JSPAI调用支付参数
     * @param string $prepay_id = ''
     * @return mixed
     * @throws
     */
    public function jsApi(string $prepay_id = '')
    {
        $data            = [
            'appId'     => $this->config['appid'],
            'timeStamp' => Request::time(),
            'nonceStr'  => Str::random(32),
            'package'   => 'prepay_id=' . $prepay_id,
            'signType'  => $this->getSignType(),
        ];
        $data['paySign'] = $this->sign($data);
        return $data;
    }

    /**
     * 请求微信支付统一下单接口
     * @param bool $assoc
     * @return mixed
     * @throws
     */
    public function unifiedOrder(bool $assoc = true)
    {
        $data = $this->send(Url::UNIFIED_ORDER, $assoc);
        if ($this->getTradeType() == 'JSAPI' && array_key_exists('prepay_id', $data)) {
            return $this->jsApi($data['prepay_id']);
        }
        return $data;
    }
}