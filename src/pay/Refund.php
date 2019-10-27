<?php


namespace flyits\wechat\pay;


use flyits\wechat\Url;
use think\Exception;

class Refund extends Base
{
    /**
     * 微信订单号
     * @var string
     */
    protected $transactionId = null;

    /**
     * 商户退款单号
     * @var string
     */
    protected $outRefundNo = null;

    /**
     * 订单金额
     * @var int
     */
    protected $totalFee = null;

    /**
     * 退款金额
     * @var int
     */
    protected $refundFee = null;

    /**
     * 退款货币种类
     * @var string
     */
    protected $refundFeeType = null;

    /**
     * 退款原因
     * @var string
     */
    protected $refundDesc = null;

    /**
     * 退款资金来源
     * @var
     */
    protected $refundAccount = null;

    public function __construct()
    {
        $this->setLimit(['appid', 'mch_id', 'nonce_str', 'sign', 'sign_type', 'transaction_id', 'out_trade_no', 'out_refund_no', 'total_fee', 'refund_fee', 'refund_fee_type', 'refund_desc', 'refund_account', 'notify_url', 'refund_id', 'offset']);
        $this->setDefault(['nonce_str', 'notify_url']);
        $this->setMustParams(['appid', 'mch_id', 'transaction_id|out_trade_no', 'nonce_str', 'sign', 'sign_type', 'out_refund_no', 'total_fee', 'refund_fee']);
        parent::__construct();
    }

    /**
     * 设置微信订单号
     * @param string $transactionId
     * @return self
     */
    public function setTransactionId(string $transactionId): self
    {
        $this->transactionId = $transactionId;
        return $this;
    }

    /**
     * 设置退款单号
     * @param string $outRefundNo
     * @return self
     */
    public function setOutRefundNo(string $outRefundNo): self
    {
        $this->outRefundNo = $outRefundNo;
        return $this;
    }

    /**
     * 设置订单金额
     * @param int $totalFee
     * @return self
     */
    public function setTotalFee(int $totalFee): self
    {
        $this->totalFee = $totalFee;
        return $this;
    }

    /**
     * 设置退款金额
     * @param int $refundFee
     * @return self
     */
    public function setRefundFee(int $refundFee): self
    {
        $this->refundFee = $refundFee;
        return $this;
    }

    /**
     * 设置退款货币种类
     * @param string $refundFeeType
     * @return self
     */
    public function setRefundFeeType(string $refundFeeType): self
    {
        $this->refundFeeType = $refundFeeType;
        return $this;
    }

    /**
     * 设置退款原因
     * @param string $refundDesc
     * @return self
     */
    public function setRefundDesc(string $refundDesc): self
    {
        $this->refundDesc = $refundDesc;
        return $this;
    }

    /**
     * 设置退款资金来源
     * @param mixed $refundAccount
     * @return  self
     */
    public function setRefundAccount($refundAccount): self
    {
        $this->refundAccount = $refundAccount;
        return $this;
    }

    /**
     * 设置退款通知地址
     * @param string $notifyUrl
     * @return Refund
     */
    public function setNotifyUrl(string $notifyUrl = ''): self
    {
        $this->notifyUrl = $notifyUrl ?: $this->config['refund_notify_url'];
        $this->setRequest(['notify_url' => $this->notifyUrl]);
        return $this;
    }

    /**
     * 发送退款请求
     * @param bool $assoc
     * @return mixed
     * @throws
     */
    public function refund(bool $assoc = true)
    {
        return $this->send(Url::REFUND, $assoc);
    }

    /**
     * 查询退款订单
     * @param bool $assoc
     * @return mixed
     * @throws
     */
    public function query(bool $assoc = true)
    {
        $this->setMustParams(['appid', 'mch_id', 'nonce_str', 'sign', 'transaction_id|out_trade_no|out_refund_no|refund_id']);
        return $this->send(Url::REFUND_QUERY, $assoc);
    }


}