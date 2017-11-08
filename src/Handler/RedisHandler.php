<?php
/**
 * User: cZhan
 * Date: 2017/11/4
 * Time: 14:40
 */
namespace Handler;

class RedisHandler extends BaseHandler{

    static private $handler=null;
    static private $redis=null;
    private $config='default';
    private $defaultPort=6379;
    private $connection_state = false;
    static public function getInstance($conf){
        if(self::$handler === null){
            self::$handler = new RedisHandler($conf);
        }
        return self::$handler;
    }

    private function __construct($conf)
    {
        self::$redis = new \Redis();
        $this->conf = $conf;
        $this->helper = new helper();
    }

    /**
     * [saveCache save data]
     * @Author   Czhan
     * @DateTime 2017-11-06T01:28:46+0800
     * @param    [type]                   $key   [key of save data]
     * @param    [type]                   $value [value of save data]
     * @param    integer                  $time  [storage life(ms)]
     * @return   [type]                          [description]
     */
    public function save($key, $value, $time=0)
    {
        if($time > 0){
            self::$redis->psetex($key, $time, $value);
        }else{
            self::$redis->set($key, $value);
        }
        return true;
    }

    /**
     * [get get cache]
     * @Author   Czhan
     * @DateTime 2017-11-06T01:28:31+0800
     * @param    [type]                   $key [description]
     * @return   [type]                        [description]
     */
    public function get($key)
    {
        // TODO: Implement getCache() method.

        $redis = self::$redis;
        $value = $redis->get($key);
        return $value;
    }

    protected function delete($key)
    {
        $redis = self::$redis;
        $value = $redis->del($key);
        return $value;
    }

    /**
     * [connection choose connection]
     * @Author   Czhan
     * @DateTime 2017-11-06T00:47:31+0800
     * @param    [string or array]                   $config [description]
     * @return   [type]                           [description]
     */
    public function connection($config){
        if(!is_string($config) && !is_array($config)){
            throw new \Exception("config is invalid type.", 1);

        }
        $this->connection_state = false;
        $this->config = $config;
    }

    /**
     * [getRedisConnection description]
     * @Author   Czhan
     * @DateTime 2017-11-06T01:26:49+0800
     * @return   [type]                   [description]
     */
    protected function getConnection(){
        if($this->connection_state){//判断是否已经连接
            return true;
        }
        if(is_string($this->config)){
            if(!isset($this->conf[$this->config])){
                return false;
            }
            $conf = $this->conf[$this->config];
        }else{
            $count = count($this->config);
            if($count == 1){
                $conf['host'] = $this->config[0];
                $conf['port'] = $this->defaultPort;
            } elseif ($count == 2) {
                $conf['host'] = $this->config[0];
                $conf['port'] = $this->config[1];
            } elseif ($count == 3){
                $conf['host'] = $this->config[0];
                $conf['port'] = $this->config[1];
                $conf['password'] = $this->config[2];
            } elseif ($count == 4){
                $conf['host'] = $this->config[0];
                $conf['port'] = $this->config[1];
                $conf['password'] = $this->config[2];
                $conf['database'] = $this->config[3];
            }else{
                return false;
            }
        }
        $conf = (object)$conf;
        try{
            self::$redis->connect($conf->host,$conf->port);
            if(property_exists($conf, 'password')){
                if($conf->password !== null){
                    self::$redis->auth($conf->password);
                }
            }
            if(property_exists($conf, 'database')){
                self::$redis->select($conf->database);
            }
        }catch(\Exception $e){
            return false;
        }
        $this->connection_state = true;
        return true;
    }

    public function __call($methodName,$argument){
        $this->getConnection();
        return call_user_func_array(array(self::$redis, $methodName),$argument);
    }
}
