<?php
namespace libs;

class Model{
    protected $pdo;
    protected $table = "";

    //连接数据库并获取表名
    public function __construct(){
        //dsn 数据源名称
        $config=config("database");
//        var_dump($config);die;
        $this->pdo=new \PDO($config['dsn'],$config['root'],$config['password']);
        if($this->table==""){
            $this->getTableName();
        }
    }

    //获取默认表名
    public function getTableName(){
        //获取调用方法的类名称get_class (): 获取当前调用方法的类名；
        //get_called_class():获取静态绑定后的类名
        $name=get_called_class();
        //models\UserModel 变成user
//        echo $name;
        //1.先截取，得到UserModel  substr截取 strpos从哪里截取 -》获取到UserModel
       // $res=substr($name,strpos($name,'\\')+1);
        //2.把UserModel中的Model去掉,截取有3中方式（1）substr(字符串,开始结的位置，结束位置)
                                //                (2)substring
                                //                （3)str_replace(替换的内容，要替换成什么)
        //$res=substr($res,0,4);
        //$res=strtolower($res);
//        echo $res;
        //$this->table=$res;
        $this->table = strtolower(str_replace("Model","",substr($name,strpos($name,"\\")+1)));
    }

    //设置表名
    public function setTableName($tableName=''){
            $this->table=$tableName;
    }

    //替换表名
    public function replaceTableName($sql){
        return str_replace('__table__',$this->table,$sql);
    }

    //查询
    public function query($sql,$data=[]){
        $sql=$this->replaceTableName($sql);
        //预处理
        $stm=$this->pdo->prepare($sql);
        $stm->execute($data);

        //返回所有的结果
        $res=$stm->fetchAll(\PDO::FETCH_ASSOC);
        if(count($res) == 1){
            return $res[0];
        }else{
            return $res;
        }
    }

    //增删改
    public function exec($sql,$data=[]){
        $sql=$this->replaceTableName($sql);
        $stm=$this->pdo->prepare($sql);
        $res=$stm->execute($data);
        return $res;
    }
}

?>