<?php
namespace libs;

class Http{

    //post方式
    public static function postHttp($url="",$data=[],$upload=false,$headers=[]){
        if(!$upload){
            //把数据变成&连接的形式
            $data=http_build_query($data);
        }
        $options=[
            CURLOPT_URL=>$url,
            CURLOPT_POST=>true,
            CURLOPT_POSTFIELDS=>$data
        ];
//        var_dump($options);die;
        if($headers){
            $options[CURLOPT_HTTPHEADER] = $headers;
        }
        if(self::checkHttps($url)){
            $options[CURLOPT_SSL_VERIFYPEER]=false;
            $options[CURLOPT_SSL_VERIFYHOST]=false;
        }
        return self::doHttp($options);
    }

    //get方式
    public static function getHttp($url){
        $options=[
          CURLOPT_URL=>$url
        ];
        if(self::checkHttps($url)){
            //不验证ssl证书
            $options[CURLOPT_SSL_VERIFYPEER]=false;
            //设置成 2，会检查公用名是否存在，并且是否与提供的主机名匹配。
            $options[CURLOPT_SSL_VERIFYHOST]=false;
        }
        return self::doHttp($options);
    }

    //判断连接https
    public static function checkHttps($url){
        //判断https
        if(strpos($url,"https://") == 0){
            return true;
        }
        return false;
    }


    public static function doHttp($options=[]){
       //设置默认的参数
        $option=[
            //true，则会跟踪爬取重定向页面，否则，不会跟踪重定向页面。
            CURLOPT_FOLLOWLOCATION=>true,
            //自动输出返回的内容
            CURLOPT_RETURNTRANSFER=>true,
        ];
        //合并数组array_merge只能合并关联数组，并且后面的会替代前面的
        //+号可以合并索引数组及关联数组，并且如果重复则保留前面的值
        $option=$option+$options;
        // 1. curl 初始化
        $curl = curl_init();
// 2. 设置参数
        curl_setopt_array($curl,$options);
        $result = curl_exec($curl);
        //3.执行curl
        curl_setopt_array($curl,$options);
// 可能会发生错误
        if(!$result){
           $result=curl_error($curl);
        }
// 4. 关闭
        curl_close($curl);

    }
}
?>