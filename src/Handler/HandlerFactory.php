<?php
/**
 * User: cZhan
 * Date: 2017/11/10
 * Time: 10:33
 */
namespace SmartCache\Handler;

class HandlerFactory{
    public function createHandler($type="default"){
        $config = include(__DIR__ . "/config.php");
        $config = (object)$config;
        if(!property_exists($config,$type)){
            return null;
        }
        $default = $config->$type;
        if($default == 'redis'){
            $conf = $config->redis;
            return RedisHandler::getInstance($conf);
        }
        return null;
    }
}