<?php
/**
 * @author seirios-ls
 * 2021/6/7
 */
namespace MessageLaunch\BaseInterface;

class Config
{
    /**
     * 群发最大数
     * @var string $massNumber
     */
    protected $massNumber;

    /**
     * post 格式
     * @var string $format
     */
    protected $format = 'form_params';

    /**
     * 是否异步
     * @var bool $async
     */
    protected $async = false;
}