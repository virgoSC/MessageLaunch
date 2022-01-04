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

    protected $extno;

    protected $baseUrl;

    protected $format;

    /**
     * @throws GuzzleException
     */
    public function send(string $phone, string $message, array $extra = []): Response
    {
        $this->mergeTag($message);

        $param = [
            'action' => 'send',
            'account' => $this->account,
            'mobile' => $phone,
            'content' => $this->message,
            'password' => $this->password,
            'extno' => $extra['extno'] ?? $this->extno,
            'rt' => 'json',
        ];

        return $this->request($this->baseUrl . '?action=send', $param, 'POST');
    }

    /**
     * @throws GuzzleException
     */
    public function sends(array $phone, string $message, array $extra = []): Response
    {
        $this->sendsCheck($phone);

        $this->mergeTag($message);

        $param = [
            'action' => 'send',
            'account' => $this->account,
            'mobile' => implode(',', $phone),
            'content' => $this->message,
            'password' => $this->password,
            'extno' => $extra['extno'] ?? $this->extno,
            'rt' => 'json',
        ];

        return $this->request($this->baseUrl . '?action=send', $param, 'POST');
    }

    /**
     * @throws GuzzleException
     */
    public function sendsPhoneSelf(array $phones, array $extra = []): Response
    {
        $toContent = [];
        foreach ($phones as $phone => $content) {
            $content = $this->mergeTag($content);
            $toContent[] = $phone . '#' . $content;
        }

        $toContent = implode("\r\n", $toContent);

        $this->sendsCheck($phones);

        $param = [
            'action' => 'p2p',
            'account' => $this->account,
            'password' => $this->password,
            'mobileContentList' => $toContent,
            'extno' => $extra['extno'] ?? $this->extno,
            'rt' => 'json',
        ];

        return $this->request($this->baseUrl . '?action=p2p', $param, 'POST');
    }

    /**
     * @throws GuzzleException
     */
    public function balance(): Response
    {
        $param = [
            'action' => 'overage',
            'account' => $this->account,
            'password' => $this->password,
            'rt' => 'json',
        ];

        return $this->request($this->baseUrl, $param);
    }

    public function sendsCheck(array $phones)
    {
        if (count($phones) > $this->massNumber) {
            throw new \RuntimeException('count phones > ' . $this->massNumber);
        }
    }

    protected function request(string $url, array $param, string $method = 'GET', array $header = []): Response
    {
        $Response = parent::request($url, $param, $method, $header);

        $result = $Response->getBody();

        if ($Response->getCode() != '200') {
            return $Response;
        }

        $result = json_decode($result, true);

        if (!$result) {
            $Response->setErrorNo($result);
            return $Response;
        }

        if ($status = $result['status'] ?? '') {
            if ($status !== 0) {
                $Response->setErrorNo('error code:' . $status);
            } else {
                $Response->setResult($result['list']);
            }
            return $Response;
        }
        $Response->setResult($result);

        return $Response;
    }
}