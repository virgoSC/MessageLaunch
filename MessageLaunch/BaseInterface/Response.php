<?php
/**
 * @author seirios-ls
 * 2021/6/7
 */

namespace MessageLaunch\BaseInterface;

class Response
{

    private $code;

    private $body;

    private $header;

    public function __construct($code, $body, $header)
    {
        $this->code = $code;
        $this->header = $header;
        $this->body = $body;
    }


    public static function generate($code, $body, $header): Response
    {
        return (new self($code, $body, $header));
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getBody(): string
    {
        return $this->body;
    }

    public function getHeader(): string
    {
        return $this->header;
    }
}