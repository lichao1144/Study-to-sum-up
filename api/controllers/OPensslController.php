<?php
namespace controllers;

class OpensslController{

    public function createopenssl(){
        //1.生成公钥私钥对
        $config=[
          "config"=>"C:\phpStudy\PHPTutorial\Apache\conf\openssl.cnf",
            "private_key_bits"=>"2048",
        ];
        $pk=openssl_pkey_new($config);
//        echo $pk;die;
        // 得到私钥
        openssl_pkey_export($pk,$privateKey,null,$config);
//        var_dump($privateKey);
        //得到公钥
        $pk = openssl_pkey_get_details($pk);
        $publicKey = $pk['key'];
//        echo $publicKey;
        //openssl_private_decrypt — 使用私钥解密数据 
        //使用私钥加密数据
        openssl_private_encrypt("李超666", $encrypt, $privateKey);
        echo base64_encode($encrypt);
        //使用公钥解密
        openssl_public_decrypt($encrypt, $decrypt, $publicKey);
        //openssl_public_encrypt — 使用公钥加密数
        echo "<br>",$decrypt;
//        $pri_key  =  openssl_pkey_get_private($privateKey);
//        $pub_key  =  openssl_pkey_get_public($publicKey);
    }
}

?>