# smart-cache
Modify the cache refresh time as the number of requests changes

##### 使用方式
* 引入smartCache.php文件
```
require __DIR__.'/vendor/smart-cache/smart-cache/src/SmartCache.php';
```
* 配置文件 /smart-cache/src/config.php

> default属性表示缓存方式，暂时只有redis

redis[default] 使用的时候默认的配置，可以添加任意组的配置然后使用，配置格式：
```
'default' => [
    'host'     => 'localhost',
    'password' => null,
    'port'     => 6379,
    'database' => 0,
],
'addTest' => [
    'host'     => 'localhost',
    'password' => null,
    'port'     => 6379,
    'database' => 1,
]
```
* 调用实例
```
$a = 10;
$smartCache = new SmartCache();

//更换为addTest的配置
$smartCache->connection('addTest');
/**保存缓存
*第一个参数为保存的键，
*第二个为保存的值可以是字符串或者数组，
*第三个参数为保存的时长，
*/
$res = $smartCache->saveCache('test',['name'=>'czhan','level'=>999],10);

//获取缓存，只需传入键值
$data = $smartCache->getCache('test');

//清楚缓存
$data = $smartCache->deleteCache('test')

//以数组形式传入配置，第一个参数为host，
//第二个参数为part（默认为6379），第三参数为密码（没有则为null，或者不传）
//第四个参数为选择的database可以不传
$smartCache->connection(['127.0.0.1','6379',null,0]);

$smartCache->connection(['127.0.0.1','6379','test']);

$smartCache->connection(['127.0.0.1','6379']);

$smartCache->connection(['127.0.0.1']);

```
> 并且支持redis对象原本的所有方法，keys等操作。
