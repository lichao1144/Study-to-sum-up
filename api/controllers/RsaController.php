<?php
namespace controllers;

class RsaController{
    //rsa生成
    public function rsa(){
        //自定义生成俩个数字，p  q 质数也成素数
        $p=$this->createPrime();
        $q=$this->createPrime();
        $N=$p*$q;
        $num=($p-1)*($q-1);
        while(true){
            $pulicKey=mt_rand(2,$num-1);
            if($this->checkPrimePair($pulicKey,$num)){
                break;
            }
        }
        $privateKey=$this->getPrivateKey($num,$pulicKey);
        return [[$N,$pulicKey],[$N,$privateKey]];
    }

    //生成数字
    protected function createPrime(){
        while(true){
            $key=rand();
            if($this->checkPrime($key)){
                break;
            }
        }
        return $key;
    }

    //判断数字是否为质数
    protected function checkPrime($key){
        $is=true;
        for($i=2;$i<floor($key/2);$i++){
            if($key%$i==0){
                $is=false;
                break;
            }
        }
        return $is;
    }

    protected function checkPrimePair($v1,$v2){
        $is=true;
        $min=min($v1,$v2);
        for($i=2;$i<=$min;$i++){
            if($v1%$i == 0 && $v2%$i ==0){
                $is=false;
                break;
            }
        }
        return $is;
    }

    protected function getPrivateKey($num,$pulicKey){
        for($priveKey=2;;$priveKey++){
            $product=gmp_mul($priveKey,$pulicKey);
            if(gmp_mod($product,$num)==1){
                break;
            }
        }
        return $priveKey;
    }
}

?>