<?php

namespace ZPHP\Coroutine\Http;

use ZPHP\Core\Log;
use ZPHP\Coroutine\Base\IOvector;

/**
 * Created by PhpStorm.
 * User: zhaoye
 * Date: 16-9-2
 * Time: 下午2:54
 */
class Client implements IOvector
{

    protected $swooleHttpClient;
    protected $data;

    /**
     * @param $data = ['url'=>'','postdata'=>[]]
     * @param $callback
     */
    protected function initHttpClient($callback, $data)
    {
        $this->data = $data;
        $this->data['callback'] = $callback;
    }


    public function command(callable $callback = null, $data)
    {
        $this->initHttpClient($callback, $data);
        $this->getHttpClient();
    }

    /**
     * 获取一个http客户端
     * @param $base_url
     * @param $callBack
     */
    public function getHttpClient()
    {

        try {
            $parseUrl = parse_url($this->data['url']);
            if (empty($parseUrl['host'])) {
                throw new \Exception("输入地址有误");
            }
            $this->data['host'] = $parseUrl['host'];
            $this->data['ssl'] = $parseUrl['scheme'] == 'https' ? true : false;
            if ($this->data['ssl'] == true) {
                $this->data['port'] = 443;
            } else {
                $this->data['port'] = empty($parseUrl['port']) ? 80 : $parseUrl['port'];
            }
            $this->data['path'] = $parseUrl['path'];

            $data = $this->data;
            swoole_async_dns_lookup(
                $this->data['host'],
                function ($host, $ip) use (&$data) {
                    try {
                        if (empty($ip)) {
                            throw new \Exception("找不到该域名");
                        }
                        $client = new \swoole_http_client($ip, $data['port'], $data['ssl']);
                        $this->myCurl($client);
                    } catch (\Exception $e) {
                        call_user_func_array($this->data['callback'], ['data' => ['exception' => $e->getMessage()]]);
                    }

                }
            );
        } catch (\Exception $e) {
            Log::write($e->getMessage());
            call_user_func_array($this->data['callback'], ['data' => ['exception' => $e->getMessage()]]);
        }
    }


    /**
     * http 请求的过程
     */
    public function myCurl($swoolehttpclient)
    {
        if (!empty($this->data['postdata'])) {
            $swoolehttpclient->post(
                $this->data['path'],
                $this->data['postdata'],
                function ($swoolehttpclient) {
                    call_user_func_array($this->data['callback'], ['data' => $swoolehttpclient->body]);
                }
            );
        } else {
            $swoolehttpclient->get(
                $this->data['path'],
                function ($swoolehttpclient) {
                    call_user_func_array($this->data['callback'], ['data' => $swoolehttpclient->body]);
                }
            );
        }
    }

}