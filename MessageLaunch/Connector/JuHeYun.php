<?php
/**
 * @author seirios-ls
 * 2021/6/7
 */

namespace MessageLaunch\Connector;

use GuzzleHttp\Exception\GuzzleException;
use MessageLaunch\BaseInterface\Connector;
use MessageLaunch\BaseInterface\Launch;
use MessageLaunch\BaseInterface\Response;

class JuHeYun extends Connector implements Launch
{

    protected $account;

    protected $password;

    protected $extNo;

    protected $baseUrl;

    protected $format = 'json';

    /**
     * @throws GuzzleException
     */
    public function send(string $phone, string $message): Response
    {
        $message = $this->mergeTag($message);

        $param = [
            'action' => 'send',
            'account' => $this->account,
            'password' => $this->password,
            'mobile' => $phone,
            'content' => $message,
            'extno' => $this->extNo,
            'rt' => 'json'
        ];
        var_dump($param);

        return $this->request($this->baseUrl, $param, 'GET', []);
    }

    /**
     * @throws GuzzleException
     */
    public function sends(array $phone, string $message): Response
    {
        $this->sendsCheck($phone);

        $message = $this->mergeTag($message);

        $param = [
            'action' => 'send',
            'account' => $this->account,
            'password' => $this->password,
            'mobile' => implode(',', $phone),
            'content' => $message,
            'extno' => $this->extNo,
            'rt' => 'json'
        ];

        return $this->request($this->baseUrl, $param, 'GET', []);
    }

    /**
     * @throws GuzzleException
     */
    public function sendsPhoneSelf(array $phones): Response
    {
        $content = [];

        foreach ($phones as $k => $v) {
            $content[] = $k . '#' . $v;
        }
        $content = array_filter($content, 'self::mergeTag');

        $content = implode("\r\n", $content);

        $this->sendsCheck($phones);

        $param = [
            'action' => 'p2p',
            'account' => $this->account,
            'password' => $this->password,
            'mobileContentList' => $content,
            'extno' => $this->extNo,
            'rt' => 'json'
        ];

        return $this->request($this->baseUrl, $param, 'GET', []);
    }

    public function sendsCheck(array $phones)
    {
        if (count($phones) > $this->massNumber) {
            throw new \RuntimeException('count phones > ' . $this->massNumber);
        }
    }

    /**
     * @throws GuzzleException
     */
    public function balance(): Response
    {
        $param = [
            'action' => 'balance',
            'account' => $this->account,
            'password' => $this->password,
            'rt' => 'json'
        ];

        return $this->request($this->baseUrl, $param, 'GET', []);
    }

    protected function request(string $url, array $param, string $method = 'GET', array $header = []): Response
    {
        $Response = parent::request($url, $param, $method, $header);

        $result = $Response->getBody();

        if ($Response->getCode() != '200') {
            $Response->setErrorNo($Response->getBody());
            return $Response;
        }

        $result = json_decode($result, true);

        if (!isset($result['status'])) {
            $Response->setErrorNo($result);
            return $Response;
        }

        $status = $result['status'];

        if (isset($param['action']) and $param['action'] == 'balance') {
            $Response->setSuccess(true);
            $Response->setResult($result['balance']);
            return $Response;
        }

        if ($status !== '0') {
            $Response->setErrorNo($status);
            return $Response;
        }
        //成功
        $Response->setResult($result);


        $list = $result['list'] ?? '';
        if ($list) {
            $Response->setSuccess(true);
            $list = array_column($list, 'mid');
            $list = implode(',', $list);
            $Response->setResult($list);
        }

        return $Response;
    }
}