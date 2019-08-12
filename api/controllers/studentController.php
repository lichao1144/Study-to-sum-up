<?php
namespace controllers;

use models\StudentModel;
use libs\Response;
class StudentController extends RestfulController{
        //查
    public function show(){
        $model = new StudentModel;
        $data = $model->query("select * from __table__");
        Response::restfulResponse(200,"ok",$data);
    }

    //添加
    public function store(){
        $data = requset()->post();
        $model = new StudentModel;
        if($model->exec("insert into __table__ set name=:name,sex=:sex,age=:age",$data)){
            Response::restfulResponse(201,"ok",$data);
        }
    }

    //put方法的话，接值需要进行file_get_contents("php://input")
    public function save(){
        $data = requset()->all();
        $id = requset()->get('id');
        var_dump($data);
//        var_dump($id);
//        die;
        $data['id'] = $id;
        $model = new StudentModel;
        // 1. 查数据表看有没有该条数据
        $student = $model->query("select * from __table__ where id=?",[$id]);
        if(!$student){
            Response::restfulResponse(404,"",["error"=>"NOT FOUND"]);
        }
        $res = $model->exec("update __table__ set name=?,age=?,sex=? where id=?",[$data['name'],$data['age'],$data['sex'],$id]);

        if(!$res){
            Response::restfulResponse(417,"",["error"=>"修改失败"]);
        }
        $student['name']=$data['name'];
        $student['age'] = $data['age'];
        $student['sex'] = $data['sex'];
        Response::restfulResponse(200,"",$student);
    }


    public function delete(){
        $id = requset()->get('id');
        //1.查找数据
        $model = new StudentModel;
        $student = $model->query("select * from __table__ where id=?",[$id]);
        if(!$student){
            Response::restfulResponse(404,"",["error"=>"NOT FOUND"]);
        }
        $res = $model->exec("delete from __table__ where id=?",[$id]);
        if(!$res){
            Response::restfulResponse(500,"",["error"=>"内部错误"]);
        }
        Response::restfulResponse(204,"","");
    }

}
?>