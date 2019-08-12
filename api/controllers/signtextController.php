<?php
namespace controllers;

class SigntextController{

    public function index(){
        $signArr = createSign();
        var_dump($signArr);
    }

}
?>