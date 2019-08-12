<?php
namespace controllers;

use libs\Config;
use libs\HttpClient;
use libs\Log;
use libs\Model;
use libs\Upload;

class TextController{
    public function index(){
//        var_dump(createSign());
//        $data=[
//            "username"=>'lisi',
//            "password"=>'123'
//        ];
//        echo 1;die;
        var_dump(createSign());
    }

    //哈希加密密码测试
    public function encryptionpwd(){
        $pwd="1144167099";
        $pwdhash=$this->createencryptionpwd($pwd);
        var_dump($pwdhash);
    }
    public function createencryptionpwd($pwd){
        return password_hash($pwd,PASSWORD_DEFAULT);
    }

    //http展示各种不同传送方式
    public function Post(){
        if($_SERVER['REQUEST_METHOD']=="POST"){
            var_dump($_POST);die;
        }
        include"../views/post.html";
    }

    //调用方法测试fopen
    public function http(){
        //调用fopen方法
//        HttpClient::fopenHttp("http://www.mouth6.com/index.php?c=text&a=post",['name'=>'ss','pwd'=>444]);

        //调用file方法
//        HttpClient::fileHttp("http://www.mouth6.com/index.php?c=text&a=post",['name'=>'ss','pwd'=>444]);

        //调用file_get_contents
//        HttpClient::fileGetContentsHttp("http://www.baidu.com");
//        HttpClient::fileGetContentsHttp("http://www.mouth6.com/index.php?c=text&a=post",['name'=>'ss','pwd'=>444]);

        //调用fsockopen
//        HttpClient::fsockopenHttp("http://www.mouth6.com/index.php?c=text&a=post");

        //调用第五种stream方法
        $res = HttpClient::streamHttp("http://www.mouth6.com/index.php?c=text&a=post",['username'=>'小姐姐','password'=>'小哥哥，我要...']);
        // $res = HttpClient::streamHttp("http://www.apitest.com/index.php?c=test&a=post");
        if(substr($res['status'][1],0,1) == '2'){
            echo $res['body'];
        }else{
            echo $res['status'][1],":",$res['status'][2];
        }
    }

    //测试config
    public function Config(){
        $res = explode(".", "username.");
//        var_dump($res);
//        exit;
        $config = Config::getInstance();
        var_dump($config->getConfig());
    }

    //ajax测试
    public function ajax(){
        include"../views/ajax.html";
    }

    public function text(){
        new Model();
    }

    //创建config中的iv
    public function createiv(){
        $rt=base64_encode(random_bytes(16));
        $iv=substr(str_replace(["=","/","+"],"",$rt),-16);
        echo $iv;
    }

    //实现日志
    public function createlog(){
       log::getInstance()->info("this is a test");
    }

    //错误日志测试
    public function errortext(){
        $str;
    }
}
?>