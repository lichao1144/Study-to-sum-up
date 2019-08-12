<?php
namespace libs;

//路由类
class Route{
    private $controller="index";
    private $action="index";

    //解析路由
    //c 表示控制器  a 表示方法名
    //控制器名称后缀 Controller   index=>indexController
    //方法名 不变    action       index=>index
    public function routeParse(){
        list($c,$a)=$this->getRoute();
//        $c=isset($_GET['c']) ? trim($_GET['c']) : "";
//        $a=isset($_GET['a']) ? trim($_GET['a']) : "";
//        var_dump($c,$a);
//        var_dump($_GET);
        //&& 与关系，前一个成立才能走下一个
        $c != "" && $this->controller=$c;
        $a != "" && $this->action=$a;

        //返回控制器和方法
        $this->controller=ucfirst($this->controller)."Controller";
        $this->action=$this->action;
//        var_dump($this->controller,$this->action);die;
//        var_dump($this->controller,$this->action,$_SERVER);
        return [$this->controller,$this->action];
    }

    protected function getRoute(){
        //直接通过地址中的参数c和a获取对应的控制器
        $controller="";
        $action="";
        list($controller,$action)= $this->getRouteByUrl();
        //通过pathinfo来获取
        $controller || $action || list($controller,$action)=$this->getRouteByPathInfo();
        //通过正则匹配
        $controller || $action || list($controller,$action)=$this->getRouteByUri();
//        var_dump($controller,$action);die;
        // 通过通用规则来获取
        $controller || $action || list($controller,$action) = $this->getRouteByParams();

        return [$controller,$action];
    }

    protected function getRouteByUrl(){
        $c=requset()->all('c',"");
        $a=requset()->all('a',"");
        return [$c,$a];
    }

    protected function getRouteByPathInfo(){
        $controller="";
        $action="";
        $pathinfo=$_SERVER['PATH_INFO'] ?? "";

        //如果确实存在
        if($pathinfo){
            $path=explode("/",$pathinfo);
//            var_dump($path);
            $controller=$path[1] ?? "";
            $action=$path[2] ?? "";
//            var_dump($controller);
            for($i=3;$i<count($path);$i=$i+2){
                $_GET[$path[$i]]=$path[$i+1] ?? "";
            }
//            var_dump($_GET);
        }
        return [$controller,$action];
    }

    protected function getRouteByUri(){
        $controller="";
        $action="";
//        var_dump($_SERVER);
        $uri=$_SERVER['REQUEST_URI'];
//        echo($uri);
        //正则匹配
        $regs=$this->regExpForRoute();
        foreach($regs as $reg=>$replace){
            if(preg_match($reg,$uri)){
                $newUri=preg_replace($reg,$replace,$uri);
//                echo $newUri;die;
                $params=explode("&",$newUri);
                foreach($params as $param){
                    $p=explode("=",$param);
                    if($p[0]=='c'){
                        $controller=$p[1];
                    }elseif($p[0] == 'a'){
                        $action=$p[1];
                    }else{
                        $_GET[$p[0]]=$p[1];
                    }
                }
                break;
            }
        }
        return [$controller,$action];
    }

    protected function regExpForRoute(){
        return[

               "#^/(\w+)/(\d+)\?(.*)$#"=>"c=$1&a=index&id=$2&$3",
               "#^/(\w+)/(\d+)$#" => "c=$1&a=index&id=$2",
               "#^/(\w+)/(\w+)\?(.*)$#" => "c=$1&a=$2&$3",
               "#^/(\w+)/(\w+)$#" => "c=$1&a=$2",
               "#^/(\w+)$#"=>"c=$1&a=index",
               "#^/(\w+)\?(.*)$#"=>"c=$1&a=index&$2",

        ];
    }

    protected function getRouteByParams(){
        $controller="";
        $action="";
        $uri=$_SERVER['REQUEST_URI'];

        $uri=explode("?",$uri);
        //处理？后半个部分
        if(isset($uri[1])){
            $params=explode("&",$uri[1]);
            foreach($params as $v){
                $v=explode('=',$v);
                $_GET[$v[0]]=$v[1];
            }
        }
        //?前半部分
        $path=explode('/',$uri[0]);
        $controller=$path[1] ?? "";
        $action=$path[2] ?? "";
        for($i=3;$i<count($path);$i+=2){
            $_GET[$path[$i]]=$path[$i+1] ?? "";
        }
        return [$controller,$action];
    }
}
?>