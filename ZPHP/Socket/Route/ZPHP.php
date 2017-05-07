<?php
/**
 * User: shenzhe
 * Date: 2014/2/7
 *
 * 内置route
 */


namespace ZPHP\Socket\Route;

use ZPHP\Core;
use ZPHP\Protocol;

class ZPHP
{
    public function run($data, $fd = null)
    {
        $server = Protocol\Factory::getInstance(Core\Config::getField('socket', 'protocol', 'Http'));
        $server->setFd($fd);
        $server->parse($data);

        return Core\Route::route($server);
    }

}
