<?php
namespace libs;

class HttpClient{

    //第一种使用fopen实现http的get post请求
    public static function fopenHttp($url,$data=[]){
        //请求百度实例
//        $fp=fopen("http://www.baidu.com","r");
//        $conetent=stream_get_contents($fp);
//        echo $conetent;
////        echo htmlspecialchars($conetent);
        $opts=[];
        if(is_array($data) && !empty($data)){
            //http_build_query  把数据变成?name='123'&pwd='451'
            $data = http_build_query($data);
            //进行post请求
            $opts=array(
                'http'=>array(
                    //发送方式
                    'method'=>'POST',
                    //header头部设置content-type为类型
                    'header'=>"Content-Type: application/x-www-form-urlencoded\r\n".
                        //cookie
                        "Cookie: foo=bar \r\n".
                        //长度
                        "Content-Length:".strlen($data)."\r\n",
                    'content'=>$data
                )
            );
        }
        //创建并返回一个资源流上下文，该资源流中包含了 options 中提前设定的所有参数的值
        $content=stream_context_create($opts);
        $fp=fopen($url,'r',false,$content);
        //读取结果stream_get_contents — 读取资源流到一个字符串
        $content=stream_get_contents($fp);
        echo $content;
    }

    //第二种使用file实现http请求
    public static function fileHttp($url,$data=""){
        $opts = [];
        if(is_array($data) && !empty($data)){
            // 进行post请求
            $data = http_build_query($data);
            $opts = array(
                'http'=>array(
                    'method'=>"POST",
                    'header'=>"Content-Type: application/x-www-form-urlencoded\r\n".
                        "Content-Length: ".strlen($data)."\r\n",
                    'content'=> $data
                )
            );
        }
        $context = stream_context_create($opts);
        $content = file($url,0,$context);
        // 读取结果
        $content = implode("\r\n",$content);
//        return $content;
        echo $content;
    }

    //第三种视图file_get_content实现http请求
    public static function fileGetContentsHttp($url,$data=""){
        $opts = [];
        if(is_array($data) && !empty($data)){
            // 进行post请求
            $data = http_build_query($data);
            $opts = array(
                'http'=>array(
                    'method'=>"POST",
                    'header'=>"Content-Type: application/x-www-form-urlencoded\r\n".
                        "Content-Length: ".strlen($data)."\r\n",
                    'content'=> $data
                )
            );
        }
        $context = stream_context_create($opts);
        $content = file_get_contents($url,false,$context);
        echo $content;
    }

    //第四中方法fsockopen
    public static function fsockopenHttp($url,$data="",$upload=false,$port=80){
        $method = 'GET';
        //parse_url — 解析 URL，返回其组成部分
        $parameter = parse_url($url);
//        var_dump($parameter);die;
        $path=$parameter['path'] ?? '/';
//        var_dump($path);die;
        if(isset($parameter['query'])){
            $path .= "?".$parameter['query'];
        }
//        var_dump($path);die;
        // 打开连接
        $fs = fsockopen($parameter['host'],$port,$errno,$errstr,30);
        if(!$fs){
            //抛出错误信息
        }
        // 编写HTTP报文
        $httpStr = "GET ".$path." HTTP/1.1\r\n";
//        echo $httpStr;die;
        if(!empty($data)){
            $method="POST";
            $httpStr = "POST ".$parameter['path'].'?'.$parameter['query']." HTTP/1.1\r\n";
            // 进行post请求
            $upload || ($data =is_array($data) ? http_build_query($data) : $data);
            $httpStr .= "Content-Length: ".strlen($data)."\r\n";
            $httpStr .=  $upload ? "Content-Type: multipart/form-data; boundary=--LC\r\n" : "Content-Type: application/x-www-form-urlencoded\r\n";
        }
        //编写http报文
        $httpStr.= "Host: ".$parameter['host']."\r\n";
        $httpStr.= "Accept: */*\r\n";
        $httpStr.= "\r\n";

        if($method == "POST"){
            $httpStr .= $data;
        }

        // 发送请求
        fwrite($fs,$httpStr);
        // 接收响应--------stream_get_contents一次性读完数据
        $contents = stream_get_contents($fs);
//        echo  $contents;
       return self::parseHttp($contents);
//        var_dump($res);
    }

    //第五张stream方法
    public static function streamHttp($url,$data="",$port="80"){
        $method = 'GET';
        $parameter = parse_url($url);
        $path = $parameter['path'] ?? '/';
        if(isset($parameter['query'])){
            $path .= "?".$parameter['query'];
        }
        // 打开连接
        $socket = stream_socket_client("tcp://".$parameter['host'].":".$port,$errno,$errstr,30);
        if(!$socket){
            // 抛出错误信息
        }
        // 编写HTTP报文
        $httpStr = "GET ".$path." HTTP/1.1\r\n";
        if(is_array($data) && !empty($data)){
            $method = "POST";
            $httpStr = "POST ".$parameter['path'].'?'.$parameter['query']." HTTP/1.1\r\n";
            // 进行post请求
            $data = http_build_query($data);
            $httpStr .= "Content-Length: ".strlen($data)."\r\n";
            $httpStr .= "Content-Type: application/x-www-form-urlencoded\r\n";
        }

        $httpStr.= "Host: ".$parameter['host']."\r\n";
        $httpStr.= "Accept: */*\r\n";
        $httpStr.= "\r\n";

        if($method == "POST"){
            $httpStr .= $data;
        }
        // 发送HTTP报文
        fwrite($socket,$httpStr);

        // 接收响应
        $contents = stream_get_contents($socket);
        return self::parseHttp($contents);
    }


    //获取详细的信息
    protected static function parseHttp($contents=""){
//            var_dump($contents);
        //按照空格进行分隔得到报文头部及报文实体
        list($http_header,$http_body)=explode("\r\n\r\n",$contents);
//        var_dump($http_header);
//        var_dump($http_body);
        //得到起始行信息-----状态行
        $http_header=explode("\r\n",$http_header);
//        var_dump($http_header);die;
        //得到状态
        list($status,$code,$codeInfo)= explode(" ",$http_header[0]);
//        var_dump($status);var_dump($code);var_dump($codeInfo);
        //获取响应头
        unset($http_header[0]);
//        var_dump($http_header);die;
        $headers = [];
        foreach($http_header as $v){
            list($key,$value)=explode(": ",$v);
            $headers[$key]=$value;
        }
//        var_dump($headers);
        //得到
//        var_dump($httpBody);
        $body="";
        if(isset($headers['Transfer-Encoding'])) {
            while($http_body){
                // 进行分割
                $httpBody = explode("\r\n", $http_body,2);
                $chunkedSize = intval($httpBody[0],16);
                $body .= substr($httpBody[1],0,$chunkedSize);
                $http_body = substr($httpBody[1],$chunkedSize+2);
            }
        }else{
            $body = $http_body;
        }
//        var_dump($body);
        //var_dump($httpBody[1]);
        // foreach($http_body as $v){
        // 返回响应头和内容数组
        return ["status"=>[$status,$code,$codeInfo],"header"=>$headers,"body"=>$body];
    }
}

?>