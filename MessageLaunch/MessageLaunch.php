<?php
/**
 * @author seirios-ls
 * 2021/6/7
 */

namespace MessageLaunch;

use MessageLaunch\BaseInterface\Response;

/**
 * Class MessageLaunch
 * @package MessageLaunch
 */
class MessageLaunch
{
    /**
     * @var array $instance
     */
    private $instance = [];

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

    /**
     * 单发
     * @param string $phone
     * @param string $message
     * @param string $instanceName
     * @param array $extra
     * @return mixed
     * @author xis
     */
    public function send(string $phone, string $message, string $instanceName = '', array $extra = []): Response
    {
        $instance = $this->getInstance($instanceName);

        return $this->instance[$instance]->send($phone, $message, $extra);
    }

    /**
     * 批发
     * @param array $phones
     * @param string $message
     * @param string $instanceName
     * @param array $extra
     * @return mixed
     * @author xis
     */
    public function sends(array $phones, string $message, string $instanceName = '', array $extra = []): Response
    {
        $instance = $this->getInstance($instanceName);

        return $this->instance[$instance]->sends($phones, $message, $extra);
    }

    /**
     * 单点发送
     * @param array $phonesContent
     * @param string $instanceName
     * @param array $extra
     * @return mixed
     * @author xis
     */
    public function sendsPhoneSelf(array $phonesContent, string $instanceName = '', array $extra = []): Response
    {
        $instance = $this->getInstance($instanceName);

        return $this->instance[$instance]->sendsPhoneSelf($phonesContent, $extra);
    }

    /**
     * 余额
     * @param string $instance
     * @return mixed
     * @author xis
     */
    public function balance(string $instance = ''): Response
    {
        $instance = $this->getInstance($instance);

        return $this->instance[$instance]->balance();
    }

    private function getInstance(string $instance = '')
    {
        $key = array_keys($this->instance);

        if (!$instance or !in_array($instance, $key)) {
            $instance = $key[0];
        }
        return $instance;
    }
}