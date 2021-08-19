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

demo
```php
//聚合云
$send = new \MessageLaunch\MessageLaunch('JuHeYun', [
    'baseUrl' => 'http://1111111',
    'account' => '232323',
    'password' => 'dfsafdsfa',
    'extNo' => '32dfdsf43fd',
    
    //共有变量
    'massNumber' => '500',  //群发限制
    'format' => 'form_params', //post 提交格式
    'async' => false //是否异步
]);

//漫道
$send = new \MessageLaunch\MessageLaunch('ManDao', [
    'baseUrl' => 'http://39.107.242.113:7862/sms',
    'sn' => 'xxx-xxx-xxx-xxxxx',
    'pwd' => '3dsefd',
    'Ext' => '*',
    'sign' =>'aaa',
    //
    'massNumber' => '500',
]);

//单发
$response = $send->send('1821*******', '测试testCode.//.1-' . date('Y-m-d H:i:s'));
var_dump($response->getCode().'--->'.$response->getBody());

//群发
$response = $send->sends(['1821', '1822'], '测试testCode.//.1-' . date('Y-m-d H:i:s'));
var_dump($response->getCode().'--->'.$response->getBody());

//内容独立
$response = $send->sendsPhoneSelf([
    '1821' => 'tt1',
    '1822' => 'tt2'
]);
var_dump($response->getCode().'--->'.$response->getBody());

//余额
$response = $send->balance();
var_dump($response->getCode().'--->'.$response->getBody());

```
