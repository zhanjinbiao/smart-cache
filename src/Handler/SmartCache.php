<?php
/**
 * User: cZhan
 * Date: 2017/11/4
 * Time: 14:40
 */
use Handler\RedisHandler;

class SmartCache{
    private $handler;
    private $config;
    private $cacheType;
    function __construct($type='default')
    {
        $this->config = include(__DIR__ . "/config.php");
        $this->config = (object)$this->config;
        if(!property_exists($this->config, $type)){
            return false;
        }
        $this->cacheType = $this->config->get($type);
        if($this->cacheType == 'redis'){
            $conf = $this->config->redis;
            $this->handler = RedisHandler::getInstance($conf);
        }
        return $this->handler;
    }
}