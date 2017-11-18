<?php
/**
 * User: cZhan
 * Date: 2017/11/4
 * Time: 14:40
 */
namespace SmartCache;
use SmartCache\Handler\Helper;
use SmartCache\Handler\RedisHandler;

class SmartCache{
    const DEFAULT_STRING = 'default';
    private function __construct()
    {
    }

    /**设置配置
     * @param $conf
     * @return mixed|RedisHandler
     * @throws \Exception
     */
    static public function connection($conf=self::DEFAULT_STRING){
        $conf = Helper::getConfig($conf);
        $redisHandler = RedisHandler::getInstanceByConf($conf);
        return $redisHandler;
    }


    /**
     * @param $methodName
     * @param $arguments
     * @return mixed
     */
    static public function __callStatic($methodName, $arguments)
    {
//        $redisHandler = new RedisHandler();
        // TODO: Implement __callStatic() method.
        $redisHandler = self::connection();
        return call_user_func_array(array($redisHandler, $methodName),$arguments);
    }

    
}
