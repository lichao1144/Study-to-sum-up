<?php


function dd($var){
    var_dump($var);
    exit;

}

function requset(){
    //设置静态对象，来确定全局只有一个request
    static $requset=null;
    if(!$requset instanceof libs\Request){
        $requset=new libs\Request();
    }
    return $requset;
}

function config($name=""){
    $config = libs\Config::getInstance();
    return $config->getConfig($name);
}

//加密数据
function encrypet($data,$key,$iv){
   return openssl_encrypt($data,"AES-128-CBC",$key,0,$iv);
}

//解密数据
function decrypet($data,$key,$iv){
   return openssl_decrypt($data,"AES-128-CBC",$key,0,$iv);
}
