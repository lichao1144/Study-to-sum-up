<?php
namespace controllers;

use libs\HttpClient;
use libs\Response;
use libs\Upload;
use libs\Http;

class UploadController{
    //上传测试----上传单个文件
    public function uploada(){
        if($_SERVER['REQUEST_METHOD']=="POST"){
//            var_dump($_FILES);die;
            $upload=new Upload();
            $upload->setConfig("ext",['jpg','jpeg','png']);
            $upload->setConfig("mimeType",['image/jpeg','image/png']);
            $res=$upload->UploadOne();
            if(!$res){
//                echo $upload->getError();die;
                Response::returnData(1007,'Upload file failed',["error"=>$upload->getError()]);
            }
//            var_dump($res);
            Response::returnData(1008,'Upload file successfully');
        }
        include"../views/upload.html";
    }

    //上传测试----上传多个文件
    public function uploads(){
            $upload=new Upload();
            $upload->setConfig("ext",['jpg','jpeg','png']);
            $upload->setConfig("mimeType",['image/jpeg','image/png']);
            $res=$upload->UploadAll();
            if(!$res){
//                echo ;die;
                Response::returnData(1007,'Upload file failed',["error"=>$upload->getError()]);
            }
//            var_dump($res);
            Response::returnData(1008,'Upload file successfully');
//        include"../views/upload.html";
    }

    // base64 方式上传
    public function uploadByBase64(){
        //
        $file = $_POST['file'];
        $name = $_POST['name'];
        $size = $_POST['size'];

        $upload = new Upload();
        $upload->setConfig("ext",['jpg','jpeg','png']);
        $upload->setConfig("mimeType",['image/jpeg','image/png','image/gif']);
        $res = $upload->uploadByStream($name,$size,$file);
        if(!$res){
            Response::returnData(1007,'Upload file successfully',['error'=>$upload->getError()]);
        }

        Response::returnData(1008,"Upload file successfully",$res);
    }

    //过去文件的base64格式
    public function getbase64(){
        $file=file_get_contents("./uploads/1144.jpg");
        $file =  base64_encode($file);
        echo $file;
    }

    //file_get_contents
    public function uploadByStream(){
        $file = file_get_contents("php://input");
        file_put_contents("./uploads/1144.jpg", $file);
    }


    //json格式
    public function Json(){
        $data = file_get_contents("php://input");
        var_dump($data);
    }

    //xml格式
    public function Xml(){
        $data = file_get_contents("php://input");
        var_dump($data);
    }

    //php://input 与php://output
    public function outputtext(){
        echo "hello";
        file_put_contents("php://output","hello");
    }

    //测试上传接口--------curlfile进行文件的上传
    public function checkupload(){
        //1.使用curl方式
        //使用CURLFile创建一个文件
        $cfile=new \CURLFile("./1144.jpeg",'image/jpeg','1144.jpeg');
        $data['file']=$cfile;
        $res=Http::postHttp("http://www.mouth6.com/index.php?c=upload&a=uploadinterfacetest",$data,true);
//        var_dump($res);
//        Http::postHttp();
    }

    //上传接口
    public function uploadinterfacetest(){
//        echo 1;die;
//        $res=requset()->post();
//        var_dump($res);die;
        $upload=new Upload();
        $upload->setConfig("ext",['jpg','jpeg','png']);
        $upload->setConfig("mimeType",['image/jpeg','image/png']);
        $res=$upload->UploadOne();
        if(!$res){
//                echo 1;die;
            Response::returnData(1007,'Upload file failed',["error"=>$upload->getError()]);
        }
//            var_dump($res);
        Response::returnData(1008,'Upload file successfully',$res);
        //显示出上传图片
//        echo "<script>parent.add('".json_encode($res)."');</script>";
    }

    //从客户端读取,调用上传接口进行上传
    public function uploadinterfacetest2(){
        if($_SERVER['REQUEST_METHOD']== "POST"){
            //将客户端上传的文件提交给我们指定的上传接口，如阿里云，七牛
//            var_dump($_FILES);
            $file=$_FILES['file'];
            $cfile=new \CURLFile($file['tmp_name'],$file['type'],$file['name']);
            $data['file']=$cfile;
            $res=Http::postHttp("http://www.mouth6.com/index.php?c=upload&a=uploadinterfacetest",$data,true);
        var_dump($res);die;
        }
        include"../views/upload2.html";
    }

    //文件上传的报文
    public function uploadinterfacetest3(){
        //fsockopen方法
        //报文编写
        $data="----LC\r\n";
        $data .="Content-Disposition: form-data; name=\"username\"\r\n\r\n";
        $data .="lichao\r\n";
        $data .="----LC\r\n";
        $data .="Content-Disposition: form-data; name=\"file\"; filename=\"1144.jpeg\"\r\n";
        $data .="Content-Type: image/jpeg\r\n\r\n";
        $data .= file_get_contents("./1144.jpeg");
        $data .="\r\n----LC--\r\n\r\n";

//        $fs=fsockopen("www.mouth6.com","80",$errno,$errstr,30);
//        $package="POST /index.php?c=upload&a=uploadinterfacetest HTTP/1.1\r\n";
//        $package .="Host: www.mouth6.com\r\n";
//        $package .="Content-Type: multipart/form-data; boundary=--LC\r\n";
//        $package .="Content-Length: ".strlen($data)."\r\n";
//        $package .="\r\n";
//
//        $package .=$data;
//        fwrite($fs,$package);//发送
//        //打印
//        $res=stream_get_contents($fs);
//        var_dump($res);
        $res=HttpClient::fsockopenHttp("http://www.mouth6.com/index.php?c=upload&a=uploadinterfacetest",$data,true);
        var_dump($res);
    }
}
?>