# MessageLaunch
短信发送集合

安装
```shell
composer require virgo/message-launch
```

支持服务商

|  服务商   | code  |
|  ----  | ----  |
| 聚合云  | JuHeYun |
|漫道|ManDao|
|软维|RuanWei|

demo
```php
<?php
/**
 * @author seirios-ls
 * 2021/6/7
 */

require 'vendor/autoload.php';

$messageLaunch = new \MessageLaunch\MessageLaunch();

$messageLaunch->append('JuHeYun', 'JuHeYun1', [
//    'baseUrl' => 'http://39.107.242.113:7862/sms',
    'baseUrl' => 'http://139.107.242.113:7862/sms',
    'account' => 'aaaaa',
    'password' => 'vvvvv',
    'extNo' => '222222',
    //
    'massNumber' => '500',
    'tag' => '【tag】',
    'tag_pos' => 'before' //before
]);
//
////聚合云
$messageLaunch->append('JuHeYun', 'JuHeYun2', [
    'baseUrl' => 'http://39.107.242.113:7862/sms',
     'baseUrl' => 'http://139.107.242.113:7862/sms',
    'account' => 'aaaaa',
    'password' => 'vvvvv',
    'extNo' => '222222',
    //
    'massNumber' => '500',
    'tag' => '【tag】',
    'tag_pos' => 'before' //before
]);
//
////漫道
$messageLaunch->append('ManDao', 'ManDao', [
    'baseUrl' => 'http://sdk2.entinfo.cn:8060/webservice.asmx',
    'sn' => '32323232',
    'pwd' => 'dddddd',
    'Ext' => '',
    //
    'massNumber' => '500',
    'tag' => '【tag2】',
    'tag_pos' => 'before' //before
]);

//软维
$messageLaunch->append('RuanWei', 'RuanWei', [
    'baseUrl' => 'http://8.142.148.197:7862/sms',
    'account' => '11111',
    'password' => '222222',
    'extno' => '22',
    //
    'massNumber' => '500',
    'tag' => '【tag22】',
    'tag_pos' => 'before' //before
]);
//单发
if (1) {
    $response = $messageLaunch->send('18200000001', '验证码1234', 'RuanWei', [
//    'extno' => '222'
    ]);
}

//群发
if (0) {
    $response = $messageLaunch->sends(['18200000001'], '验证码2365.//.1-' . date('Y-m-d H:i:s'), 'RuanWei');
}
//内容独立
if (0) {
    $response = $messageLaunch->sendsPhoneSelf([
        '18200000001' => 'messageTest:code:4203',
        '18200000002' => 'messageTest:code:5891'
    ],'RuanWei');
}

//余额
if (0) {
    $response = $messageLaunch->balance('RuanWei');
}



var_dump($response->getSuccess());

var_dump($response->getReturnId());

var_dump($response->getCode());
var_dump($response->getBody());
var_dump($response->getResult());
var_dump($response->getErrorNo());
exit;



```
