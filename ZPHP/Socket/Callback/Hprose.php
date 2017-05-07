<?php


namespace ZPHP\Socket\Callback;


abstract class Hprose extends Swoole
{

    protected $protocol;
    protected $serv;

    public function setServ($serv)
    {
        $this->serv = $serv;
    }

    abstract public function onRegister();
}
