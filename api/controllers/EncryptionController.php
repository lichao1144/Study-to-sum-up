<?php
namespace controllers;

class EncryptionController{

    //秘钥
    protected $key="abcdefghijklmnopqrstuvwxyz";
    //异或方法
    public function index(){
        $txt=$_GET['txt'];
        //或运算
        var_dump($txt ^ $this->key);
        //base64加密
        $encrypt=base64_encode($txt ^ $this->key);
        var_dump($encrypt);
        //base64解密
        $decrypt=base64_decode($encrypt) ^ $this->key;
        var_dump($decrypt);
    }

    /*
     * md5加密
     * 第二个参数，false为可看懂的，true为看不懂得，默认为false
     * 生成32位加密字符串
     * */
    public function index2(){
        echo md5("123456",false);
        echo"<br>";
        echo md5("123456",true);
        echo"<br>";
        echo md5("123456");
        echo"<br>";
        //md5_file()计算指定文件的 MD5 散列值
        echo md5_file("./1144.jpeg");//生成32位16进制
    }

    /*
     * sha1加密
     * 第二个参数，false为可看懂的，true为看不懂得，默认为false
     *生成64位加密字符串
     * */
    public function index3(){
        echo sha1("123456",false);//40长度
        echo"<br>";
        echo sha1("123456",true);//20长度
    }

    /*
     * hash加密
     * hash第一个参数可以写加密方法
     * */
    public function index4(){
        echo hash("md5","123456");
        echo"<br>";
        echo hash("sha1","123456");
        echo"<br>";
        echo hash("sha256","123456");
        echo"<br>";
        //加密文件
        echo hash("sha256","./1144.jpeg");
        //hash_hmac使用 HMAC 方法生成带有密钥的哈希值
        echo"<hr>";
        echo hash_hmac("md5","123456",$this->key);
        echo"<br>";
        echo hash_hmac("sha1","123456",$this->key);
        echo"<br>";
        echo hash_hmac("sha256","123456",$this->key);
    }

    /*
     *各种加密算法
     * */
    public function index5(){
        var_dump(hash_hmac_algos());
    }

    /*
     * crypt  返回最多13个字符
     * */
    public function index6(){
        $h_pwd= crypt("123456",$this->key);
        echo $h_pwd; echo"<br>";
//        echo $h_pwda=crypt("123456",$h_pwd);
//        echo $h_pwda;echo"<br>";
        //hash_equals 可防止时序攻击的字符串比较
        if(hash_equals($h_pwd,crypt("123456",$h_pwd))){
            echo"这的确实123456使用crypt加密后的结果";
        }
    }

    /*
     * CSPRNG 函数
     * random_bytes    random_int
     * */
    public function index7(){
        $bytes = random_bytes(5);
        echo $bytes;
        echo"<br>";
        $bytess=random_int(100, 999);
        echo $bytess;
    }

    /*
     * openssl
     * openssl_encrypt
     * */
    public function index80(){
        //ECB模式
        $res=$this->index81("123456","李超666");//调用加密方法
        echo $res;
        echo"<br>";
        $resa=$this->index82($res,"李超666");//调用解密方法
        echo $resa;
        echo"<hr>";
        //CBC模式下的$v,
//        $iv=random_bytes(8);
        $iv=random_bytes(16);
        $reab=$this->index83("123456","李超666",$iv);
        echo $reab;
        echo"<br>";
        $read=$this->index84($reab,"李超666",$iv);
        echo $read;
    }
    //加密
    public function index81($data="",$key=""){
//        var_dump(openssl_get_cipher_methods());
//        return openssl_encrypt($data,"DES-ECB",$key);
        //使用ECB使用3des进行加密
//        return openssl_encrypt($data,"DES-EDE3",$key);
        //使用ECB使用aes进行加密
        return openssl_encrypt($data,"AES-128-ECB",$key);
    }
    //解密
    public function index82($data="",$key=""){
//        return openssl_decrypt($data,"DES-ECB",$key);
        //使用ECB使用3des进行解密
//        return openssl_decrypt($data,"DES-EDE3",$key);
        //使用ECB使用aes进行解密
        return openssl_decrypt($data,"AES-128-ECB",$key);
    }

    public function index83($data="",$key="",$iv=""){
//        var_dump(openssl_get_cipher_methods());
//        return openssl_encrypt($data,"DES-CBC",$key,0,$iv);
        //使用CBC使用3des进行加密
//        return openssl_encrypt($data,"DES-EDE3-CBC",$key,0,$iv);
        //使用CBC使用进行aes加密
        return openssl_encrypt($data,"AES-128-CBC",$key,0,$iv);
    }
    //解密
    public function index84($data="",$key="",$iv=""){
//        return openssl_decrypt($data,"DES-CBC",$key,0,$iv);
        //使用CBC使用3des进行解密
//        return openssl_decrypt($data,"DES-EDE3-CBC",$key,0,$iv);
        //使用CBC使用进行aes解密
        return openssl_decrypt($data,"AES-128-CBC",$key,0,$iv);
    }
}


?>