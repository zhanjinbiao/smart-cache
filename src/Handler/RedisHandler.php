<?php
/**
 * User: cZhan
 * Date: 2017/11/4
 * Time: 14:40
 */
namespace SmartCache\Handler;


class RedisHandler extends BaseHandler{

    private $redis = null;
    const DEFAULT_PORT = 6379;
    static private $instance = [];
    private $conf_str = '';
    private function __construct($conf)
    {
        $conf_str = Helper::confToString($conf);
        $this->redis = new \Redis();
        $this->conf_str = $conf_str;
        $this->conf = $conf;
        $this->getConnection();
    }

    /**根据配置获取单例
     * @param $conf
     * @return mixed
     */
    static public function getInstanceByConf($conf){
        $conf_str = Helper::confToString($conf);
        if(!isset(self::$instance[$conf_str])){
            self::$instance[$conf_str] = new RedisHandler($conf);
        }
        return self::$instance[$conf_str];
    }

    function __destruct()
    {
        // TODO: Implement __destruct() method.
        $conf_str = $this->conf_str;
        try{
            $this->redis->close();
            self::$instance[$conf_str] = null;
        }catch (\Exception $e){
            //todo :error
        }
    }

    /**
     * [saveCache save data]
     * @Author   Czhan
     * @DateTime 2017-11-06T01:28:46+0800
     * @param $key
     * @param $value
     * @param    integer $time [storage life(ms)]
     * @return bool [type]                          [description]
     * @throws \Exception
     * @internal param $ [type]                   $key   [key of save data]
     * @internal param $ [type]                   $value [value of save data]
     */
    public function save($key, $value, $time=0)
    {
        $redis = $this->redis;
        if($time > 0){
            $redis->psetex($key, $time, $value);
        }else{
            $redis->set($key, $value);
        }
        return true;
    }

    /**
     * [get get cache]
     * @Author   Czhan
     * @DateTime 2017-11-06T01:28:31+0800
     * @param $key
     * @return bool|string [type]                        [description]
     * @throws \Exception
     * @internal param $ [type]                   $key [description]
     */
    public function get($key)
    {
        // TODO: Implement getCache() method.
        $redis = $this->redis;
        $value = $redis->get($key);
        return $value;
    }

    protected function delete($key)
    {
        $redis = $this->redis;
        $value = $redis->del($key);
        return $value;
    }

    /**
     * [getRedisConnection description]
     * @Author   Czhan
     * @DateTime 2017-11-06T01:26:49+0800
     * @return bool
     * @throws \Exception
     */
    protected function getConnection(){
        $conf = $this->conf;
        $conf = (object)$conf;
        if(!property_exists($conf,'host')){
            throw new \Exception("config lose host params");
        }
        if(!property_exists($conf, 'port')){
            $conf->port = RedisHandler::DEFAULT_PORT;
        }
        try{
            $this->redis->connect($conf->host,$conf->port);
            if(property_exists($conf, 'password')){
                if($conf->password !== null){
                    $this->redis->auth($conf->password);
                }
            }
            if(property_exists($conf, 'database')){
                $this->redis->select($conf->database);
            }
        }catch(\Exception $e){
            return false;
        }
        return true;
    }


    public function __call($methodName,$argument){
        if($methodName == "select"){
            return false;
        }
        try{
            $this->redis->ping();
        }catch (\Exception $e){
            $this->getConnection();
        }
        $result = call_user_func_array(array($this->redis, $methodName),$argument);
        return $result;
    }
}
