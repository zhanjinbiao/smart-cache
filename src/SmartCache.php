<?php
/**
 * User: cZhan
 * Date: 2017/11/4
 * Time: 14:40
 */
//require(__DIR__."/Handler/RedisHandler.php");
namespace Handler;
use Handler\RedisHandler;

class SmartCache{
    private $handler;
    private $config;
    function __construct()
    {
        $this->config = include(__DIR__ . "/config.php");
        $this->config = (object)$this->config;
        if(!property_exists($this->config,'default')){
            return false;
        }
        $default = $this->config->default;
        if($default == 'redis'){
            $conf = $this->config->redis;
            $this->handler = RedisHandler::getInstance($conf);
        }
        return $this->handler;
    }
}

function __autoload($className)
{
    if (0 === strpos($className, $this->prefix)) {
        $parts = explode('\\', substr($className, $this->prefixLength));
        $filepath = $this->directory.DIRECTORY_SEPARATOR.implode(DIRECTORY_SEPARATOR, $parts).'.php';

        if (is_file($filepath)) {
            require $filepath;
        }
    }
}
new SmartCache();