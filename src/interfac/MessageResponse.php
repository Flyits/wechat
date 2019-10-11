<?php


namespace flyits\wechat\interfac;


interface MessageResponse
{
    /**
     * 用户关注事件
     * @param
     * @return mixed
     * @throws
     */
    public function subscribe();

    /**
     * 二维码关注
     * @param
     * @return mixed
     * @throws
     */
    public function qrCodeSubscribe();

    /**
     * 扫描带参二维码
     * @param
     * @return mixed
     * @throws
     */
    public function qrCode();

    /**
     * 取消关注
     * @param
     * @return mixed
     * @throws
     */
    public function unSubscribe();

    /**
     * 用户点击菜单
     * @param
     * @return mixed
     * @throws
     */
    public function menuClick();

    /**
     * 用户点击菜单跳转
     * @param
     * @return mixed
     * @throws
     */
    public function menuView();

    /**
     * 扫码推事件用户点击按钮后，微信客户端将调起扫一扫工具，完成扫码操作后显示扫描结果（如果是URL，将进入URL）
     * @param
     * @return mixed
     * @throws
     */
    public function scanCodePush();

    /**
     * 扫码推事件且弹出“消息接收中”提示框用户点击按钮后，微信客户端将调起扫一扫工具，完成扫码操作后，将扫码的结果传给开发者
     * @param
     * @return mixed
     * @throws
     */
    public function scanCodeWaitMsg();

    /**
     * 用户弹出系统拍照发图的事件推送
     * @param
     * @return mixed
     * @throws
     */
    public function picSysPhoto();

    /**
     * 用户弹出拍照或者相册发图的事件推送
     * @param
     * @return mixed
     * @throws
     */
    public function picPhotoOrAlbum();

    /**
     * 用户弹出微信相册发图器的事件推送
     * @param
     * @return mixed
     * @throws
     */
    public function picWeixin();

    /**
     * 用户弹出地理位置选择器的事件推送
     * @param
     * @return mixed
     * @throws
     */
    public function locationSelect();

    /**
     * 用户上报地理位置事件
     * @param
     * @return mixed
     * @throws
     */
    public function location();

    /**
     * 模版消息发送任务 结果推送
     * @param
     * @return mixed
     * @throws
     */
    public function templateSendJobFinish();

    /**
     * 收到图片消息
     * @param
     * @return mixed
     * @throws
     */
    public function receiveImage();

    /**
     * 收到文字消息
     * @param
     * @return mixed
     * @throws
     */
    public function receiveText();

    /**
     * 收到语音消息
     * @param
     * @return mixed
     * @throws
     */
    public function receiveVoice();

    /**
     * 收到视频消息
     * @param
     * @return mixed
     * @throws
     */
    public function receiveVideo();

    /**
     * 收到音乐消息
     * @param
     * @return mixed
     * @throws
     */
    public function receiveMusic();

    /**
     * 收到图文消息
     * @param
     * @return mixed
     * @throws
     */
    public function receiveNews();
}