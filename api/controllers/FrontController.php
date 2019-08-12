<?php
namespace controllers;

use libs\Http;
use libs\Response;
use libs\Upload;
use models\UserModel;
use libs\Controller;

class FrontController{

    public function js(){
        //接受前台发送的方法名
//        $callback=$_GET['callback'];
//        echo $callback;die;
        //接受jsoncallback
        $callback=$_GET['jsoncallback'];
        $model=new UserModel();
        $res=$model->query("select * from __table__ where id=1");
//        var_dump($res);
        $res=json_encode($res);
        //调用次方法可以进行弹框
//        echo"alert('{$res}')";
        //返回数据进行前台的操作
//        echo $callback."('{$res}')";
        //jsonp返回数据
        echo $callback."('".$res."')";
    }

    public function jsupload(){
        include"../views/jsupload.html";
    }

    public function text(){
        $res=Http::postHttp("http://www.mouth6.com/index.php?c=index&a=display",["randnumber"=>'5Bky07Dg$SvLsxg4BZI8',"timestamp"=>"1560995317","sign"=>"0ab0d2802c6188d161716ccd4cdee999a57a6647"],false,["Authorization: eyJhbGciOiJIUzI1NiJ9.eyJhdWQiOlsiYXVkaWVuY2VfMSIsImF1ZGllbmNlXzIiXSwiZXhwIjoxNTYxMDQxNTMwLCJpYXQiOjE1NjEwMzQzMzAsImlzcyI6ImxpY2hhbyIsImp0aSI6IjEiLCJuYmYiOjE1NjEwMzQzMzAsInN1YiI6Imh0dHA6XC9cL3d3dy5tb3V0aDYuY29tIiwiY2xhaW1fbmFtZSI6IjEiLCJpZCI6ImlkIn0.4FNIZuYm63xHKu0npQ0bC1PUEVJmlSd6sFYtax-6kr4","x-api-token: leningEducation"]);
        var_dump($res);
    }

    public function header(){
        include"../views/header.html";
    }

    //防盗链测试
    public function daolian(){
        if(requset()->header("Referer") !=="http://www.mouth6.com/index.php?c=front&a=header"){
                            exit("deny access");
        }
        $img=base64_encode(file_get_contents("./uploads/5d0b482a22ba1Hyu6t.jpg"));
        var_dump($img);
        echo"<img> src='/9j/4AAQSkZJRgABAQAAAQABAAD/2wBDAAICAgICAQICAgIDAgIDAwYEAwMDAwcFBQQGCAcJCAgHCAgJCg0LCQoMCggICw8LDA0ODg8OCQsQERAOEQ0ODg7/2wBDAQIDAwMDAwcEBAcOCQgJDg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg7/wAARCALPBQADASIAAhEBAxEB/8QAHgAAAQQDAQEBAAAAAAAAAAAAAAQFBgcCAwgBCQr/xABLEAABAwMDAgUCAwUHAwMCAQ0BAgMEAAURBhIhMUEHEyJRYRRxMoGRCBUjQqEWM1KxwdHwJGLhQ3LxFyU0CVOiGCaSJ0RjgjVFVP/EABsBAAIDAQEBAAAAAAAAAAAAAAADAQIEBQYH/8QAMhEAAgICAgICAgIBAwMDBQAAAAECEQMhEjEEQRMiUWEFMiMUQnEGkaEVJIEzUsHw8f/aAAwDAQACEQMRAD8A+/lF'>";
    }

    //切片上传
    public function sliceupload(){
        include"../views/sliceupload.html";
    }
    //切片上传测试
    public function sliceuploadtext(){
        $tmpPath=$_FILES['file']['tmp_name'];//临时路径名称
//        echo $tmpPath;die;
        $data=requset()->post();
        $blobNum=$data['everynum'];//条数
        $totalnum=$data['totalnum'];//总条数
        $filename=$data['filename'];//文件名称
        $filepath = '../sliceupload';//上传地址
        if(!file_exists($filepath)){
            mkdir($filepath,0777,true);
        }
        //移动临时文件
        move_uploaded_file($tmpPath,$filepath.'/'.$blobNum.".txt");
        $str="";
        if ($blobNum < $totalnum) {
            $str .= file_get_contents($filepath.'/'.$blobNum.".txt");
        }
        //追加文件
        file_put_contents("uploads/".$filename,$str,FILE_APPEND);
        //删除分块文件
        @unlink($filepath."/".$blobNum.".txt");
        Response::returnData(200,'Upload file successfully');
    }

    
    public function Index(){

    }
}

?>