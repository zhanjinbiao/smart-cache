<?php
/**
 * Created by PhpStorm.
 * User: cZhan
 * Date: 2017/11/4
 * Time: 14:40
 */
namespace Handler;

abstract class BaseHandler{
	const CACHE_PREFIX="smartCache_";
	abstract function saveCache($key, $value, $time);

	abstract function getCache($key);
}