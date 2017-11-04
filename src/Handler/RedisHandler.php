<?php
/**
 * User: cZhan
 * Date: 2017/11/4
 * Time: 14:40
 */
namespace Handler;

class RedisHandler extends BaseHandler{

    static private $handler=null;
    private $helper;
    static public function getInstance($conf){
        if(self::$handler === null){
            return new RedisHandler($conf);
        }
        return null;
    }

    private function __construct($conf)
    {
        self::$handler = new \Redis();
        self::$handler->connect($conf->host,$conf->post);
        self::$handler->auth($conf->password);
        $this->helper = new helper();
    }

    function saveCache($key, $value, $time=0)
    {
        // TODO: Implement saveCache() method.
        if(!is_string($key)){
            throw new \Exception('key must is string type.');
        }
        if(!is_string($value)){
            throw new \Exception('value must is a array or a string!');
        }
        if(!is_numeric($time)){
            throw new \Exception('time must is string type.');
        }
        $cache_redis = self::$handler;
        $key = self::CACHE_PREFIX.$key;
        $long_msec = $time*1000;//保存的毫秒数
        $value = $this->helper->encodeValue($value,$time);
        if($time > 0){
            $cache_redis->psetex($key, $long_msec, $value);
        }else{
            $cache_redis->set($key, $value);
        }
        return true;
    }

    function getCache($key)
    {
        // TODO: Implement getCache() method.

        if(!is_string($key)){
            throw new \Exception('key must is string type.');
        }
        $cache_redis = self::$handler;
        $key = self::CACHE_PREFIX.$key;
        $value = $cache_redis->get($key);
        if($value === null){
            return null;
        }
        $data = $this->helper->decodeValue($value);
        return $data;
    }
}
