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

    /**
     * tag
     * @var string $tag
     */
    protected $tag;
    /**
     * tag位置
     * @var string $tag_pos
     */
    protected $tag_pos = 'after';

    public function getTag(): string
    {
        return $this->tag;
    }

    public function getTagPos(): string
    {
        return $this->tag_pos;
    }

    protected function mergeTag($message): string
    {
        if ($this->tag_pos == 'after') {
            return $message . $this->tag;
        } else {
            return $this->tag . $message;
        }
    }
}