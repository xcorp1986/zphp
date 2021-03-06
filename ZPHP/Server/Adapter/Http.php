<?php
/**
 * User: shenzhe
 * Date: 13-6-17
 */


namespace ZPHP\Server\Adapter;

use ZPHP\Core;
use ZPHP\Protocol;
use ZPHP\Server\IServer;

class Http implements IServer
{

    public function run()
    {
        Protocol\Request::setServer(
            Protocol\Factory::getInstance(
                Core\Config::getField('Project', 'protocol', 'Http')
            )
        );
        Protocol\Request::parse($_REQUEST);

        return Core\Route::route();
    }

}