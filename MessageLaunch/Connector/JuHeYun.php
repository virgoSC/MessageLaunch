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

        return $this->request($this->baseUrl, $param,'GET',[]);
    }

    public function sends(array $phone, string $message)
    {
        // TODO: Implement sends() method.
    }
}