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
        $param = [
            'action' => 'send',
            'account' => $this->account,
            'password' => $this->password,
            'mobile' => $phone,
            'content' => urlencode($message),
            'extno' => $this->extNo,
            'rt' => 'json'
        ];

        return $this->request($this->baseUrl, $param, 'GET', []);
    }

    /**
     * @throws GuzzleException
     */
    public function sends(array $phone, string $message): Response
    {
        $this->sendsCheck($phone);

        $param = [
            'action' => 'send',
            'account' => $this->account,
            'password' => $this->password,
            'mobile' => implode(',', $phone),
            'content' => urlencode($message),
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

        foreach ($phones as $k=> $v) {
            $content[] = $k.'#'.$v;
        }
        $content = implode("\r\n",$content);
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

    public function sendsCheck(array $phones): void
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
}