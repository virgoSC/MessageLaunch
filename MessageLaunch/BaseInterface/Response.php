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

    private $success = false;

    private $result;

    private $errorNo;

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

    public function setResult($result): Response
    {
        $this->result = $result;
        $this->success =true;
        return $this;
    }

    public function getResult()
    {
        return $this->result;
    }

    /**
     * @return bool
     */
    public function getSuccess(): bool
    {
        return $this->success;
    }

    /**
     * @param bool $success
     */
    public function setSuccess(bool $success): void
    {
        $this->success = $success;
    }

    /**
     * @return mixed
     */
    public function getErrorNo()
    {
        return $this->errorNo;
    }

    /**
     * @param mixed $errorNo
     */
    public function setErrorNo($errorNo): Response
    {
        $this->errorNo = $errorNo;
        $this->success = false;
        return $this;
    }

}