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

class RuanWei extends Connector implements Launch
{
    protected $account;

    protected $password;

    protected $Ext;

    protected $baseUrl;

    protected $format = '';

    /**
     * @throws GuzzleException
     */
    public function send(string $phone, string $message): Response
    {
        $this->mergeTag($message);

        $param = [
            'action' => 'p2p',
            'account' => $this->account,
            'password' => md5($this->sn . $this->pwd),
            'mobile' => $phone,
            'Content' => mb_convert_encoding($this->message, "gb2312", "UTF-8"),
            'Ext' => $this->Ext,
            'Stime' => '',
            'Rrid' => ''
        ];

        return $this->request($this->baseUrl . '/mt', $param, 'POST');
    }

    /**
     * @throws GuzzleException
     */
    public function sends(array $phone, string $message): Response
    {
        $this->sendsCheck($phone);

        $this->mergeTag($message);

        $param = [
            'sn' => $this->sn,
            'pwd' => strtoupper(md5($this->sn . $this->pwd)),
            'mobile' => implode(',', $phone),
            'Content' => mb_convert_encoding($this->message, "gb2312", "UTF-8"),
            'Ext' => $this->Ext,
            'Stime' => '',
            'Rrid' => ''
        ];

        return $this->request($this->baseUrl . '/mt', $param, 'POST');
    }

    /**
     * @throws GuzzleException
     */
    public function sendsPhoneSelf(array $phones): Response
    {
        $phones = array_map(function ($message) {
            $message = $this->mergeTag($message);
            return mb_convert_encoding($message, "gb2312", "UTF-8");
        }, $phones);

        $_phone = implode(',', array_keys($phones));

        $_content = implode(",", $phones);

        $this->sendsCheck($phones);

        $param = [
            'sn' => $this->sn,
            'pwd' => strtoupper(md5($this->sn . $this->pwd)),
            'mobile' => $_phone,
            'Content' => $_content,
            'Ext' => $this->Ext,
            'Stime' => '',
            'Rrid' => ''
        ];

        return $this->request($this->baseUrl . '/gxmt', $param, 'POST');
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
            'sn' => $this->sn,
            'pwd' => strtoupper(md5($this->sn . $this->pwd)),
        ];

        return $this->request($this->baseUrl . '/balance', $param);
    }

    protected function request(string $url, array $param, string $method = 'GET', array $header = []): Response
    {
        $Response = parent::request($url, $param, $method, $header);

        $result = $Response->getBody();

        if ($Response->getCode() != '200') {
            return $Response;
        }

        $result = simplexml_load_string($result);
        if (!$result) {
            $Response->setErrorNo($result);
            return $Response;
        }
        $result = (array)$result;
        $result = $result[0] ?? '';
        $Response->setResult($result);

        if ($url == $this->baseUrl . '/balance') {
            $Response->setSuccess(true);
        } else {
            if (substr($result, 0, 1) == '-') {
                $Response->setSuccess(false);
                $Response->setErrorNo($result);
            } else {
                $Response->setSuccess(true);
            }
        }
        return $Response;
    }
}