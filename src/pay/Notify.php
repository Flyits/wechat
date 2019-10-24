<?php


namespace flyits\wechat\pay;


use flyits\wechat\interfac\ResultNotify;

class Notify extends Base implements ResultNotify
{

    public function __construct()
    {
        parent::__construct();
        return $this->init();
    }

    /**
     * 初始化方法
     * @param
     * @return mixed
     * @throws
     */
    public function init()
    {
        $notify = $this->getData();
        if (array_key_exists('req_info', $notify)) {
            $this->refundNotify($this->decrypt($notify));
        } else {
            $this->notify($notify);
        }
        $resultInfo = '<xml>
                         <return_code><![CDATA[SUCCESS]]></return_code>
                         <return_msg><![CDATA[OK]]></return_msg>
                       </xml>';
        ob_end_clean();
        return $resultInfo;
    }

    /**
     * 获取微信发送数据
     * @param
     * @return mixed
     * @throws
     */
    private function getData()
    {
        $fileContent = file_get_contents("php://input");
        //禁止引用外部xml实体
        libxml_disable_entity_loader(true);
        //先把xml转换为simplexml对象，再把simplexml对象转换成 json，再将 json 转换成数组。
        $value = json_decode(json_encode(simplexml_load_string($fileContent, 'SimpleXMLElement', LIBXML_NOCDATA)), true);

        return $value;
    }

    /**
     * 支付结果通知
     * @param array $notify
     * @return mixed
     * @throws
     */
    public function notify(array $notify = [])
    {

    }

    /**
     * 退款结果通知
     * @param array $notify
     * @return mixed
     * @throws
     */
    public function refundNotify(array $notify = [])
    {

    }

    /**
     * AES-256-ECB解密
     * @param string $Ciphertext 微信回调通知密文
     * @return mixed
     * @throws
     * @author flyits
     * @time 2019/1/16 11:23
     */
    private function decrypt($Ciphertext = '')
    {
        //解密第一步，base64解码
        $decrypt = base64_decode($Ciphertext, true);
        //md5加密key得到解密秘钥
        $key = md5($this->config['key']);
        //使用openssl解密
        $decrypt = openssl_decrypt($decrypt, 'AES-256-ECB', $key, OPENSSL_RAW_DATA);
        $obj     = simplexml_load_string($decrypt, 'SimpleXMLElement', LIBXML_NOCDATA);
        $data    = json_decode(json_encode($obj), true);
        return $data;
    }
}