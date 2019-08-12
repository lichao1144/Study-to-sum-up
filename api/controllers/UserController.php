<?php
namespace controllers;
use libs\Controller;
use libs\Response;
use models\UserModel;

class UserController {
    protected $key="1810b";
    //实现用户的登录
    public function login(){
        $name=requset()->all('name');
//        echo $name;die;
        $pwd=requset()->all('pwd');

        //验证是否有该用户
        $model=new UserModel();
        $res=$model->getUserInfoByName($name);
//        var_dump($res);die;
        if(!$res){
            //返回错误码，无效的用户
            Response::returnData(1002,'Invalid Name ');
        }

        //验证密码----------------哈希加密
        if(password_verify($pwd,$res['pwd'])){
            //返回错误码，无效的用户密码
            Response::returnData(1003,'Invalid Pwd');
        }

        //密码正确,生成token
//        $user_token=$this->createToken(30);
//        $lifetime=time()+7200;
//        var_dump($user_token);
        //方法一，数据库存储token
//        $res=$model->exec("update __table__ set usertoken=?,lifetime=? where name=?",[$user_token,$lifetime,$name]);
//
//        if(!$res){
//            //无服务器内部错误
//            Response::returnData(500,"Internal Error");
//        }

        //方法二 jwt生成token
        unset($res['pwd']);
        //进行加密数据
        $user=encrypet(json_encode($res),config("Encrypt.key"),config("Encrypt.iv"));
        $user_token=$model->createToken($user);
//        var_dump($token);
//        Response::returnData(200,"ok",['access_token'=>$user_token,"lifetime"=>$lifetime]);
        Response::returnData(200,"ok",['access_token'=>$user_token]);
    }

    public function corstext(){
        include "../views/cors.html";
    }
}
?>