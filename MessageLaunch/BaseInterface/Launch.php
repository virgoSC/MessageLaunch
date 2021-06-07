<?php
/**
 * @author seirios-ls
 * 2021/6/7
 */

namespace MessageLaunch\BaseInterface;

interface Launch
{
    public function send(string $phone, string $message):Response;

    public function sends(array $phone, string $message);

}