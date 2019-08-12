<?php
namespace controllers;

//实现所有需要用户登录的接口都需要继承该类，用于验证签名
use libs\Response;
use models\UserModel;
use libs\Controller;

class UserCommonController extends Controller{
    protected $user;

    public function __construct(){
        //执行父类的构造方法
        parent::__construct();

        //验证签名
        $this->verifyToken();
    }

    //验证码签名
    protected function verifyToken(){
//        var_dump(requset()->header());die;
        //获取客户端的传过来的token,get或post传递过来的token
        $token=requset()->all('access_token');
        $token=requset()->header('Authorization') ? requset()->header('Authorization') : (requset()->all('access_token') ? requset()->all('access_token') : "");
//        var_dump($token);die;
        //通过token获取用户信息
        $model=new UserModel();
        //方法一，自行定义token
//        //通过token获取用户信息
//        $user=$model->getUserInfoByToken($token);
////        var_dump($user);die;
//        if(!$user){
//            //需要重新获取token
//            Response::returnData(401,"Need certification");
//        }
//
//        //验证token是否过期
//        if(time()-$user['lifetime']>0){
//            //无效的token
//            Response::returnData(1004,"Invalid token");
//        }
//        $this->user=$user;

        //方法二，jwt定义token
        $res = $model->verifyToken($token);
        // 通过结果来判断是否放行
        if(is_string($res)){
            //是字符串返回字符串
            Response::returnData(401,$res);
        }else if(is_null($res)){
            //不存在用户
            Response::returnData(1005,"NOT EXISTS USER");
        }

        $this->user = $res;
    }
}

?>