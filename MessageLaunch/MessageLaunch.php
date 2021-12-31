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
 * @method Response sends(array $phones, string $message)
 * @method Response sendsPhoneSelf(array $phones)
 * @method Response balance()
 * @package MessageLaunch
 */
class MessageLaunch
{
    /**
     * @var array $instance
     */
    private $instance;

    /**
     * MessageLaunch constructor
     */
    public function append(string $instance, string $name, array $options = [])
    {

        if (!key_exists($name, $this->instance)) {
            $this->setInstance($instance, $name, $options);
        }
    }

    private function setInstance(string $instance, string $name, array $options = [])
    {
        $className = "MessageLaunch\\Connector\\" . $instance;

        if (!class_exists($className)) {
            return;
        }
        $class = new $className();
        $class->setOptions($options);

        $this->instance[$name] = $class;
    }

    public function send(string $phone, string $message, string $instance = '')
    {
        $key = array_keys($this->instance);

        if (!$instance or !key_exists($instance, $key)) {
            $instance = $key[0];
        }
        return $this->instance[$instance]->send($phone, $message);
    }

    public function __call($method, $param)
    {
        return $this->instance->$method(...$param);
    }
}