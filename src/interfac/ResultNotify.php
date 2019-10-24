<?php


namespace flyits\wechat\interfac;


interface ResultNotify
{
    /**
     * 支付结果通知
     * @param array $notify
     * @return mixed
     * @throws
     */
    public function notify(array $notify = []);

    /**
     * 退款结果通知
     * @param array $notify
     * @return mixed
     * @throws
     */
    public function refundNotify(array $notify = []);
}