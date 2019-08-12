<?php
//得到客户端传递过来的数据
namespace libs;

class Request{
    protected $headers=[];
    //all方法
//    public function all($expect=['c','a','sign']){
//        foreach($expect as $v){
//            if(isset($_GET[$v])){
//                unset($_GET[$v]);
//            }
//        }
//        return $_GET+$_POST;
//    }
    public function __construct(){
        //获取header头
            $this->getHeaders();
    }
    //获取header头
    protected function getHeaders(){
        //        var_dump($_SERVER);
//        die;
        $headers=[];
        //如果有content-type 或 content-length
        isset($_SERVER['CONTENT_TYPE']) && $headers['Content-Type']=$_SERVER['CONTENT_TYPE'];
        isset($_SERVER['CONTENT_LENGTH']) && $headers['Content-Length']=$_SERVER['CONTENT_LENGTH'];
        //其他的报文头数据
        foreach($_SERVER as $k=>$v){
            if(strpos($k,"HTTP_")===0){
                $k=$this->dealHead($k);
                //这里代码为，判断是不是以Bearer Token形式来发送的Authorization，这时我们应该去掉里面的Bearer
                if($k == "Authorization"){
                    $v = str_replace("Bearer ","",$v);
                }
                $headers[$k]=$v;
            }
        }
//        var_dump($headers);
        $this->headers=$headers;
    }

    //处理header头数据
    protected function dealHead($k){
        $k=str_replace("HTTP_","",$k);
        $k=explode("_",$k);
        $k=array_map(function($v){
            return ucfirst(strtolower($v));
        },$k);
        $k=implode("-",$k);
        return $k;
    }

    //all方法
    public function all($name="",$default=''){
        parse_str(file_get_contents("php://input"),$data);
        $request=$_POST + $_GET + $data;
        if($name){
            return $request[$name] ?? $default;
        }
        return $request;
    }

    //返回除某些元素外的其他元素
    public function except($expect=[]){
        $data=$this->all();
        if(is_array($expect)){
            foreach($expect as $v){
                unset($data[$v]);
            }
        }else if(!empty($expect)){
            unset($data[$expect]);
        }
        return $data;
    }

    //get方法，来获取get请求数据
    public function get($name='',$default=''){
        if($name){
            return isset($_GET[$name]) ? $_GET[$name] : $default;
        }
        return $_GET;
    }

    //post方法,来获取post请求数据
    public function post($name='',$default=''){
        if($name){
            return isset($_GET[$name]) ? $_GET[$name] : $default;
        }
        return $_POST;
    }

    //header方法，来获取header头数据
    public function header($name=''){
        if($name){
            return $this->headers[$name] ?? null;
        }
        return $this->headers;
    }
}

?>