<?php
/**
 * @author seirios-ls
 * 2021/6/7
 */

namespace MessageLaunch\BaseInterface;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class Connector extends Config
{

    /**
     * 自动赋予变量
     * @param array $options
     */
    public function setOptions(array $options = [])
    {
        $vars = get_class_vars(get_class($this));

        $options = array_intersect_key($options, $vars);

        foreach ($options as $option => $value) {
            $this->$option = trim($value);
        }
    }

    /**
     * 请求
     * @param string $url
     * @param array $param
     * @param string $method
     * @param array $header
     * @return Response
     * @throws GuzzleException
     */
    protected function request(string $url, array $param, string $method = 'GET', array $header = []): Response
    {
        $options = [];
        $method = strtolower($method);
        if ($method == 'get') {
            $url  .= '?'.http_build_query($param);
        } else {
            $options = $this->buildRequestOptions($param, $method, $header);
        }

        $client = new Client();

        $response = $client->request($this->getMethod($method), $url, $options);

        return Response::generate($response->getStatusCode(), $response->getBody(), $response->getHeaders());
    }

    /**
     * 获取方法
     * @param $method
     * @return string
     */
    private function getMethod($method): string
    {
        if (!in_array($method, ['get', 'post', 'put', 'delete'])) {
            $method = 'get';
        }
        if ($this->async) {
            $method .= 'Async';
        }

        return $method;
    }

    private function buildRequestOptions(array $param, string $method = 'GET', array $header = []): array
    {
        $options = [];

        if ($header) {
            $options['headers'] = $options;
        }
        if ($this->format == 'form_params') {
            $options['form_params'] = $param;
        }
        if (strtoupper($this->format) == 'JSON') {
            $options['json'] = json_encode($param);
        } else {
            $options['form_params'] = $param;
        }

        return $options;
    }
}