<?php
/**
 * User: cZhan
 * Date: 2017/11/4
 * Time: 14:40
 */
namespace SmartCache;
use SmartCache\Handler\RedisHandler;

class SmartCache{
    private $handler;
    function __construct($type="default")
    {
        $config = include(__DIR__ . "/Handler/Config.php");
        $config = (object)$config;
        if(!property_exists($config,$type)){
            return false;
        }
        $default = $config->$type;
        if($default == 'redis'){
            $conf = $config->redis;
            $this->handler = RedisHandler::getInstance($conf);
        }
    }

    public function __call($methodName,$argument){
        return call_user_func_array(array($this->handler, $methodName),$argument);
    }

}
