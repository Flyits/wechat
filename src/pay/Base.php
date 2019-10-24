<?php


namespace flyits\wechat\pay;


use flyits\tool\Curl;
use flyits\wechat\Url;
use think\Exception;
use think\facade\Config;
use think\facade\Request;
use think\helper\Str;
use think\response\File;

class Base
{
    /**
     * 设置项
     * @var array
     */
    protected $config = [];

    /**
     * 可设默认值
     * @var array
     */
    protected $default = [];

    /**
     * 请求数据
     * @var array
     */
    protected $request = [];

    /**
     * 请求限制字段
     * @var array
     */
    protected $limit = [];

    /**
     * 必要参数
     * @var array
     */
    protected $mustParams = [];

    /**
     * 随机字符串
     * @var string
     */
    protected $nonceStr = null;

    /**
     * 签名
     * @var string
     */
    protected $sign = null;

    /**
     * 签名类型
     * @var string
     */
    protected $signType = null;

    /**
     * 商户订单号
     * @var string
     */
    protected $outTradeNo = null;

    /**
     * 通知地址
     * @var string
     */
    protected $notifyUrl = null;

    /**
     * 架构函数
     * @return mixed
     * @throws
     */
    public function __construct()
    {
        $this->setConfig(Config::get('wechat.pay'));
        return $this;
    }

    /**
     * 获取配置参数
     * @param array $config
     * @return $this
     */
    public function setConfig(array $config): self
    {
        $setup = ['appid', 'mch_id', 'sign_type', 'key', 'notify_url', 'expire', 'sslCertPath', 'sslKeyPath'];
        foreach ($config as $index => $item) {
            if (in_array($index, $setup)) {
                $this->config[$index] = $item;
            }
        }
        return $this;
    }

    /**
     * 设置默认值
     * @param array $default
     * @param  $class
     */
    public function setDefault(array $default = [], $class = null): void
    {
        $this->default = $default;

        foreach ($this->default as $item) {
            array_key_exists($item, $this->request) || call_user_func_array([$class ?? __CLASS__, 'set' . Str::studly($item)], []);
        }
    }

    /**
     * 设置最终请求数组
     * @param array $request
     * @return $this
     */
    protected function setRequest(array $request): self
    {
        foreach ($request as $index => $item) {
            if (in_array($index, $this->limit))
                $this->request[$index] = $item;
        }
        return $this;
    }

    /**
     * 设置请求限制字段
     * @param array $limit
     */
    protected function setLimit(array $limit): void
    {
        $this->limit = $limit;
    }

    /**
     * 设置请求参数中必要参数
     * @param array
     * @return $this
     */
    protected function setMustParams(array $mustParams = [])
    {
        $this->mustParams = [];
        return $this;
    }

    /**
     * 设置随机数
     * @param string $nonceStr
     * @return $this
     */
    public function setNonceStr(string $nonceStr = ''): self
    {
        $this->nonceStr = $nonceStr ?: Str::random(32);
        $this->setRequest(['nonce_str' => $this->nonceStr]);
        return $this;
    }

    /**
     * 参数加密
     * @return $this
     */
    public function setSign(): self
    {
        //签名步骤一：按字典序排序参数
        ksort($this->request);

        $string = '';
        foreach ($this->request as $k => $v) {
            if ($k != "sign" && $v != "" && !is_array($v)) {
                $string .= $k . "=" . $v . "&";
            }
        }
        $string = trim($string, "&");

        //签名步骤二：在string后加入KEY
        $string = $string . "&key=" . $this->config['key'];
        //签名步骤三：md5 || sha256 加密
        $string = $this->getSignType() == 'md5' ? md5($string) : hash_hmac('sha256', $string, $this->config['key']);
        //签名步骤四：所有字符转为大写
        $sign       = strtoupper($string);
        $this->sign = $sign;
        $this->setRequest(['sign' => $this->sign]);
        return $this;
    }

    /**
     * 设置签名类型
     * @param string $signType
     * @return  $this
     */
    public function setSignType(string $signType): self
    {
        $this->signType = $signType;
        $this->setRequest(['sign_type' => $this->signType]);
        return $this;
    }

    /**
     * 设置商户订单号
     * @param string $outTradeNo
     * @return  $this
     */
    public function setOutTradeNo(string $outTradeNo = null): self
    {
        $this->outTradeNo = $outTradeNo;
        $this->setRequest(['out_trade_no' => $this->outTradeNo]);
        return $this;
    }

    /**
     * 设置通知地址
     * @param string $notifyUrl
     * @return $this
     */
    public function setNotifyUrl(string $notifyUrl = '')
    {
        $this->notifyUrl = $notifyUrl ?: $this->config['notify_url'];
        $this->setRequest(['notify_url' => $this->notifyUrl]);
        return $this;
    }

    /**
     * 获取签名类型
     * @return string
     */
    public function getSignType(): string
    {
        return $this->signType ?: $this->config['sign_type'];
    }

    /**
     * 获取当前支付方式必填参数
     * @return array
     */
    public function getMustParams(): array
    {
        return $this->mustParams;
    }

    /**
     * 获取最终请求参数
     * @param bool|int $xml 返回数据类型 默认数组，可传true返回xml格式
     * @return array|string
     * @throws
     */
    public function getRequest($xml = false)
    {
        $this->setSign();

        foreach ($this->getMustParams() as $index => $mustParam) {
            if (strpos($mustParam, '|')) {
                $param = array_flip(explode('|', $mustParam));
                if (!array_intersect_key($this->request, $param)) {
                    throw new Exception('缺少必要参数' . $mustParam);
                };
            } elseif (!array_key_exists($mustParam, $this->request)) {
                throw new Exception('缺少必要参数' . $mustParam);
            }
        }
        return $xml ? $this->toXml($this->request) : $this->request;
    }

    /**
     * 发送请求
     * @param string $url
     * @param bool $assoc
     * @param bool $xml
     * @return mixed
     * @throws
     */
    public function send(string $url, $assoc = true, $xml = true)
    {
        $result = $this->post($url, $xml ? $this->toXml($this->getRequest()) : $this->getRequest());

        $obj  = simplexml_load_string($result, 'SimpleXMLElement', LIBXML_NOCDATA);
        $data = json_decode(json_encode($obj), $assoc);
        return $data;
    }

    /**
     * 数组转换为xml格式
     * @param array $data
     * @param bool|int $cdata
     * @return mixed
     * @throws
     */
    public function toXml(array $data, bool $cdata = false)
    {
        if (!is_array($data) || count($data) <= 0) {
            throw new Exception('数组数据异常！');
        }

        $xml = "<xml>";
        foreach ($data as $key => $val) {
            if (!is_numeric($val) or $cdata) {
                $xml .= "<" . $key . "><![CDATA[" . $val . "]]></" . $key . ">";
            } else {
                $xml .= "<" . $key . ">" . $val . "</" . $key . ">";
            }
        }
        $xml .= "</xml>";
        return $xml;
    }

    /**
     * 发起CurlPost请求
     * @param $url
     * @param $rawData
     * @param $useCert
     * @return mixed
     * @throws
     */
    public function post($url, $rawData, $useCert = true)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $rawData);
        if ($useCert == true && $this->config['sslCertPath']) {
            curl_setopt($ch, CURLOPT_SSLCERTTYPE, 'PEM');
            curl_setopt($ch, CURLOPT_SSLCERT, $this->config['sslCertPath']);
            curl_setopt($ch, CURLOPT_SSLKEYTYPE, 'PEM');
            curl_setopt($ch, CURLOPT_SSLKEY, $this->config['sslKeyPath']);
        }
        curl_setopt(
            $ch, CURLOPT_HTTPHEADER,
            array(
                'Content-Type: text'
            )
        );
        $data = curl_exec($ch);
        curl_close($ch);
        return ($data);
    }
}