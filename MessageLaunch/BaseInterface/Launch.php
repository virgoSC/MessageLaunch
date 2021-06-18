<?php
/**
 * @author seirios-ls
 * 2021/6/7
 */

namespace MessageLaunch\BaseInterface;

interface Launch
{
    //单发
    public function send(string $phone, string $message):Response;

    //群发
    public function sends(array $phone, string $message) :Response;

    //群发检查
    public function sendsCheck(array $phones);

    //群发 号码内容分离
    public function sendsPhoneSelf(array $phones) :Response;

    //余额
    public function balance():Response;

}