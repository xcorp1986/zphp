<?php
/**
 * Created by PhpStorm.
 * User: zhaoye
 * Date: 16/9/14
 * Time: 下午3:47
 */


namespace ZPHP\Coroutine\Mysql;

use ZPHP\Coroutine\Base\CoroutineBase;

class MySqlCoroutine extends CoroutineBase
{
    public $bind_id;

    /*
     * $this->data = $sql;
     */


    public function __construct(MysqlAsynPool $mysqlAsynPool)
    {
        $this->ioVector = $mysqlAsynPool;
    }

//    public function query($sql){
//        $this->data = $sql;
//        $this->send(function($result){
//            if(empty($this->coroutineCallBack)){
//                throw new \Exception($result['exception']);
//            }else {
//                call_user_func($this->coroutineCallBack, $result);
//            }
//        });
//        return $this;
//    }


}