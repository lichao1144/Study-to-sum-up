<?php
//CORS允许访问来源列表，多个要多个
//header("Access-Control-Allow-Origin: http://www.mouth6.com");
//header("Access-Control-Allow-Origin: http://www.mouth61.com");
header("Access-Control-Allow-Origin: *");
//CORS允许请求的方法
header("Access-Control-Allow-Method: GET,POST,HEAD,PUT");
//
//header("Access-Control-Allow-Headers: Authorization,Content-Type,Content-Length,Accept,Accept-Encoding,Host,Referer,x-api-token");
header("Access-Control-Expose-Headers: Referer");
//定义一个常量，来得到当前的文件位置 __DIR__ 要获取当前PHP脚本所在目录的绝对路径
define("APP_PATH",__DIR__);
define("FUNC_PATH",__DIR__.'/../functions');
define("CONFIG_PATH",__DIR__.'/../config');
define("LOG_PATH",__DIR__.'/../logs');

//设置错误处理
ini_set("display_errors","On");
ini_set("log_errors","On");
ini_set("error_log",__DIR__."/../logs/error.log");

//echo APP_PATH;
//require_once"libs\http.php";
////$xml=api\Respones::xml(200,"ok",["name"=>'zhangsan','age'=>18]);
////var_dump($xml);
//$res=libs\http::postHttp("http://www.mouth6.com/api/post.php",['username'=>'lisi','password'=>'4444']);

// 加载composer为我们提供的自动加载类
require_once "../vendor/autoload.php";
//1.引入自动加载
require_once "../libs/Autoload.php";
//libs\Response::json(200,"ok");
//$arr=[
//    "name" => "ahhaha"
//];
////echo demo();
//dd($arr);

//2.路由，得到操作哪个控制器，哪个方法
//$res=new controllers\IndexController();
//$res->display();

//路由，得到操作哪个控制器，哪个方法
$route=new libs\Route();
list($controller,$action)=$route->routeParse();

//实例化控制器
$controller="controllers\\".$controller;
//var_dump($controller);die;
(new $controller())->$action();
//$res=new $controller();
//$res->$action();
?>