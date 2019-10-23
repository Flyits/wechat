<?php


namespace flyits\wechat\pay;


use flyits\wechat\Url;
use think\Exception;
use think\helper\Str;

class Order extends Base
{
    /**
     * 微信订单号
     * @var string
     */
    protected $transactionId = null;

    public function __construct()
    {
        parent::__construct();
        $this->setLimit(['appid', 'mch_id', 'transaction_id', 'out_trade_no', 'nonce_str', 'sign', 'sign_type']);
        $this->setMustParams(['appid', 'mch_id', 'transaction_id|out_trade_no', 'nonce_str', 'sign', 'sign_type']);
        $this->setDefault(['nonce_str']);
    }

    /**
     * 设置微信订单号
     * @param string $transactionId
     * @return Order
     */
    public function setTransactionId(string $transactionId): Order
    {
        $this->transactionId = $transactionId;
        $this->setRequest(['transaction_id' => $this->transactionId]);
        return $this;
    }

    /**
     * 执行查询
     * @param
     * @return mixed
     * @throws
     */
    public function query()
    {
        $url = Url::ORDER_QUERY;
        return $this->post($url, $this->toXml($this->getRequest()));
    }

    /**
     * 关闭订单
     * @param
     * @return mixed
     * @throws
     */
    public function close()
    {
        $this->setMustParams(['appid', 'mch_id', 'out_trade_no', 'nonce_str', 'sign']);
        return $this->post(Url::CLOSE_ORDER, $this->toXml($this->getRequest()));
    }
}