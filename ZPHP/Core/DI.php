<?php
/**
 * Created by PhpStorm.
 * User: zhaoye
 * Date: 2016/11/28
 * Time: 下午4:45
 */


namespace ZPHP\Core;

class DI
{


    protected static $closureList;
    static private $instance = null;

    private function __construct()
    {

    }

    public static function getInstance()
    {
        if (empty(self::$instance)) {
            self::$instance = new DI();
        }

        return self::$instance;
    }

    /**
     * 注入
     * @param $key
     * @param $type
     * @param $objectName
     */
    public static function set($key, $type, $objectName, $params = [])
    {
        $objectName = str_replace("/", "\\", $objectName);
        self::$closureList[$type][$key] = function () use ($objectName, $params) {
            return Factory::getInstance($objectName, $params);
        };
    }

    /**
     * @param $key
     * @param string $type
     * @return mixed
     */
    public static function get($key, $type = 'model', $params = [])
    {
        if (empty(self::$closureList[$type][$key])) {
            $objectName = $type."\\".$key;
            self::set($key, $type, $objectName, $params);
        }

        return call_user_func(self::$closureList[$type][$key]);
    }
}