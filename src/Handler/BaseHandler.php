<?php
/**
 * Created by PhpStorm.
 * User: cZhan
 * Date: 2017/11/4
 * Time: 14:40
 */
namespace SmartCache\Handler;


abstract class BaseHandler{
    const CACHE_PREFIX = "smartCache_";
    protected $conf =[];


    /**
     * [saveCache save data]
     * @Author   Czhan
     * @DateTime 2017-11-06T01:28:46+0800
     * @param    [type]                   $key   [key of save data]
     * @param    [type]                   $value [value of save data]
     * @param    integer                  $time  [storage life(s)]
     * @return   [type]                          [description]
     */
    public function saveCache($key, $value, $time=0)
    {
        // TODO: Implement saveCache() method.
        if(!is_string($key)){
            throw new \Exception('key must is string type.');
        }
        if(!is_string($value) && !is_array($value)){
            throw new \Exception('value must is a array or a string!');
        }
        if(!is_numeric($time)){
            throw new \Exception('time must is string type.');
        }
        $key = self::CACHE_PREFIX.$key;
        $long_msec = $time*1000;//保存的毫秒数
        $value = Helper::encodeValue($value,$long_msec);
        $save = $this->save($key, $value, $long_msec);
        if(!$save){
            return false;
        }
        return true;
    }

    /**
     * [getCache get cache]
     * @Author   Czhan
     * @DateTime 2017-11-06T01:28:31+0800
     * @param    [type]                   $key [description]
     * @return   [type]                        [description]
     */
    public function getCache($key)
    {
        if(!is_string($key)){
            throw new \Exception('key must is string type.');
        }
        $key = self::CACHE_PREFIX.$key;
        $value = $this->get($key);
        if($value === false){
            return null;
        }
        $data = Helper::decodeValue($value);
        return $data;
    }

    public function deleteCache($key){
        if(!is_string($key)){
            throw new \Exception('key must is string type.');
        }
        $key = self::CACHE_PREFIX.$key;
        $row = $this->delete($key);
        return $row;
    }


    abstract protected function getConnection();

    /**
     * [save save todo]
     * @Author   Czhan
     * @DateTime 2017-11-06T01:59:49+0800
     * @param    [type]                   $key   [save key]
     * @param    [type]                   $value [save data]
     * @param    [type]                   $time  [description]
     * @return   [boolean]                          [description]
     */
    abstract function save($key, $value, $time);

    /**
     * [get get todo]
     * @Author   Czhan
     * @DateTime 2017-11-06T01:59:55+0800
     * @param    [type]                   $key [description]
     * @return   [string]                        [description]
     */
    abstract function get($key);

    abstract protected function delete($key);

}