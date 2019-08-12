<?php
namespace libs;

class Controller{

    protected $key="";
    protected $randnumber="";
    protected $timestamp="";
    protected $signature="";
    //身份认证 + 数据安全保护 =签名
    public function __construct(){
        $this->key=config('signature.key');

        $this->checkSign();
        //随机数
        //时间戳
        //key值
        //其他参数
        //放入数组中，进行排序，sha1=》签名字符串
    }

    //get签名认证
    protected function checkSign(){
//        echo 1;die;
        //获取所有的参数
//        $all=requset()->all();
//       var_dump($all);die;
        //获取随机数
        $randnumber= requset()->all('randnumber');
        //获取时间戳
        $timestamp= requset()->all('timestamp');
        //获取客户端传递过来的签名
        $signature=requset()->all('sign');//签名

        $randnumber && $this->randnumber=$randnumber;
        $timestamp && $this->timestamp=$timestamp;
        $signature && $this->signature=$signature;
//        var_dump($randnumber);
//        var_dump($timestamp);
//        var_dump($signature);
//        die;
//        echo "400";die;
        if(!$this->randnumber || !$this->timestamp || !$this->signature){
            Response::returnData(400,'Bad Request');
        }

        //判断传递过来的时间戳是否符合要求
        if($this->timestamp +60 < time()){
            Response::returnData(1009,'Signature timeout ');
        }
        $this->compareSing();
    }

    //计算签名
    protected function compareSing(){
//        echo 1;die;
        //获取所有参数
//        $all=requset()->all();
        $all=requset()->except(['c','a','sign','access_token']);
//        unset($all['sign']);
//        var_dump($all);die;
//        @unset($all['randnumber']);
//        @unset($all['timestamp']);
//        @unset($all['signature']);
        //生成一个数组
        $signArr=["randnumber"=>$this->randnumber,"timestamp"=>$this->timestamp,"key"=>$this->key];
        $signArr=$signArr+$all;
//        var_dump($signArr);die;
        //进行字典排序
        sort($signArr,SORT_STRING);
        $signature=sha1(implode($signArr));
//        var_dump($signature);die;
        if($this->signature !== $signature){
            //签名没有得到认证
            Response::returnData(1001,'Signature not authenticated');
        }
    }
}
?>