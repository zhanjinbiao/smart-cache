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
        $handlerFactory = new HandlerFactory();
        $this->handler = $handlerFactory->createHandler($type);
    }

    public function __call($methodName,$argument){
        return call_user_func_array(array($this->handler, $methodName),$argument);
    }

}
