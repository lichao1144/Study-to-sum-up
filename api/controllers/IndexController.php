<?php
namespace controllers;
use libs\Controller;
use models\UserModel;
class IndexController extends UserCommonController{

    public function display(){
//        echo"this is text";
//        dd('afafafaf');
        $model= new UserModel();
       $res= $model->query("select * from __table__");
        var_dump($res);
    }
}
?>