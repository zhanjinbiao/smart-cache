<?php
/**
 * Created by PhpStorm.
 * User: cZhan
 * Date: 2017/11/4
 * Time: 14:40
 */
namespace Handler;

abstract class BaseHandler{
	const CACHE_PREFIX = "smartCache_";
	static private $handler=null;
    protected $conf =[];
	protected $helper = null;
	

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
		if(!$this->getConnection()){
			return false;
		}
        $key = self::CACHE_PREFIX.$key;
        $value = $this->get($key);
        if($value === null){
            return null;
        }
        $data = $this->helper->decodeValue($value);
        return $data;
    }


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
        if(!$this->getConnection()){
        	return false;
        }
        $key = self::CACHE_PREFIX.$key;
        $long_msec = $time*1000;//保存的毫秒数
        $value = $this->helper->encodeValue($value,$time);
        $save = $this->save($key, $value, $time);
        if(!$save){
        	return false;
        }
        return true;
    }

    abstract protected function getConnection();

}