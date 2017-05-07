<?php
/**
 * User: shenzhe
 * Date: 13-6-17
 */


namespace ZPHP\Server\Adapter;

use ZPHP\Core;
use ZPHP\Protocol;
use ZPHP\Server\IServer;

class Cli implements IServer
{
    public function run()
    {
        $server = Protocol\Factory::getInstance('Cli');
        Protocol\Request::setServer($server);
        Protocol\Request::parse($_SERVER['argv']);

        return Core\Route::route();
    }

}
