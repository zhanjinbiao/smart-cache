<?php
/**
 * User: czhan
 * Date: 2017/11/4
 * Time: 16:21
 */
namespace SmartCache\Handler;

class Helper{
    private $E = 10;

    function __construct($e = 10)
    {
        $this->setE($e);
    }

    public function setE($e){
        $this->E = $e;
    }

    public function encodeValue($data,$time=0){
        $save_time = microtime(true)*1000;
        $value = json_encode(['data'=>$data,'save_time'=>$save_time,'save_long'=>$time]);

        return $value;
    }


    public function decodeValue($data){
        //解析获取的数据
        $value = json_decode($data, true);
        if(empty($value) || !isset($value['save_time']) || !isset($value['save_long']) || !isset($value['data'])){
            return false;
        }
        //计算出保存数据离现在的毫秒级时长
        $now_microtime = microtime(true)*1000;//当前毫秒级的时间戳
        $save_microtime = $value['save_time'];//保存数据的时间戳
        $pass_time = $now_microtime - $save_microtime;//计算出保存数据离现在的毫秒级时长
        //设置的（毫秒级）保存时长
        $long_msec = $value['save_long'];
        if($long_msec == 0){
            return $value['data'];
        }
        //根据 ($pass_time/$long_msec)^e(自然常数)算出需要刷新的概率
        $prob = $this->getProbInThisTime( $long_msec, $pass_time);
        $prob_num = $long_msec * $prob;//算出相对的概率数
        $rand_num = mt_rand(0,$long_msec);
        if($rand_num < $prob_num){//需要刷新数据，缓存过期
            return false;
        }

        $data = $value['data'];
        return $data;
    }

    /**(y=(x/m)^e) m为maxTime
     * @param $m 增长度（当x等于这个数是概率为100%）
     * @param $x 函数变量
     * @return int
     * @throws \Exception
     */
    public function getProbInThisTime($m,$x){
        if(!is_numeric($m) || $m < 0){
            throw new \Exception('key must is a positive integer.');
        }
        if(!is_numeric($x) || $x < 0){
            throw new \Exception('key must is a positive integer.');
        }
        if($x >= $m){
            return 1;
        }
        $lowNum = (float)$x/$m;
        $E = $this->E;//自然常数
        $prob = pow($lowNum, $E);
        return $prob;
    }
}
