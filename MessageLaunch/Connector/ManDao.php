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

class ManDao extends Connector implements Launch
{

    protected $sn;

    protected $pwd;

    protected $Ext;

    protected $tag;

    protected $baseUrl;

    protected $format = '';

    /**
     * @throws GuzzleException
     */
    public function send(string $phone, string $message): Response
    {
        $param = [
            'sn' => $this->sn,
            'pwd' => strtoupper(md5($this->sn . $this->pwd)),
            'mobile' => $phone,
            'Content' => mb_convert_encoding($message . $this->tag, "gb2312", "UTF-8"),
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

        $param = [
            'sn' => $this->sn,
            'pwd' => strtoupper(md5($this->sn . $this->pwd)),
            'mobile' => implode(',', $phone),
            'Content' => mb_convert_encoding($message . $this->tag, "gb2312", "UTF-8"),
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
        $phones = array_map(function ($a) {
            return mb_convert_encoding($a . $this->tag, "gb2312", "UTF-8");
        },$phones);

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
}