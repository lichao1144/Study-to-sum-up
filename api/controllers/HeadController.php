<?php
namespace controllers;

class HeadController{
    public function index(){
        $contentType=requset()->header("Content-Type");
        var_dump($contentType);
    }
}
?>