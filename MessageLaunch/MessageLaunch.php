<?php
/**
 * @author seirios-ls
 * 2021/6/7
 */

namespace MessageLaunch;

use MessageLaunch\BaseInterface\Connector;
use MessageLaunch\BaseInterface\Response;

/**
 * Class MessageLaunch
 * @method Response send(string $phone, string $message)
 * @method Response sends(array $phones, string $message)
 * @package MessageLaunch
 */
class MessageLaunch
{
    /**
     * @var Connector $instance
     */
    private $instance;

    /**
     * MessageLaunch constructor.
     */
    public function __construct(string $instance, array $options = [])
    {
        $this->setInstance($instance,$options);
    }

    private function setInstance(string $name, array $options = [])
    {
        $className = "MessageLaunch\\Connector\\" . $name;

        if (!class_exists($className)) {
            throw new \UnexpectedValueException("$name className not fund");
        }
        $this->instance = new $className();
        $this->instance->setOptions($options);
    }

    public function __call($method, $param)
    {
        return $this->instance->$method(...$param);
    }
}