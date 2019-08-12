<?php
namespace libs;
class Log{
    //单一实例
    static private $instance;
    //存放日志的目录
    protected $dir =LOG_PATH;
    //存放的数据的格式
    protected $format="[%s]-[%s]:%s\r\n";

    private function __construct(){
    }

    public static function getInstance(){
        if(!self::$instance instanceof self){
            self::$instance=new self;
        }
        return self::$instance;
    }

    private function __clone(){

    }

    public function setDir($dir){
        $this->dir=$dir;
        return $this;
    }

    public function setFormat($format){
        if($format){
            $this->format=$format;
        }
    }

    public function info($message=""){
        //判断目录是否存在
        $this->checkdir();
        //创建文件
        $filename=$this->createfile();
        //生成格式
        $message=$this->createmessage($message);
        file_put_contents($this->dir.DIRECTORY_SEPARATOR.$filename,$message,FILE_APPEND);
    }

    protected function checkdir(){
        if(!is_dir($this->dir)){
            mkdir($this->dir,0777,true);
        }
    }

    protected function createfile(){
        $filename=date("Y-m-d").".log";
        return $filename;
    }

    protected function createmessage($message){
        return sprintf($this->format,date("Y-m-d H:i:s"),"info",$message);
    }
}


?>