<?php
namespace libs;

class Upload{

    //上传文件的配置
    protected $config=[
        "ext"=>[],
        "mimeType"=>[],
        "size"=>-1,
        "savePath"=>"./uploads",
        "transferBybase64" => false,
    ];

    private $errno=0;

    public function __construct($config=[]){
        $this->config=array_merge($this->config,$config);
    }

    //设置config单个项
    public function setConfig($name,$value){
        $this->config[$name]=$value;
    }

    //获取config单个项
    public function getConfig($name){
        return $this->config[$name];
    }

    //上传单个文件
    public function uploadOne($fieldName="file"){
        //接受上传文件，遍历出数组
        $file="";
        foreach($_FILES as $v){
            $file=$v;
        }
        var_dump($file);die;
       return $this->upload($file);
    }

    //上传多个文件
    public function uploadAll(){
//        var_dump($_FILES);die;
        $results = [];
        foreach($_FILES as $v){
            $files = $v;
        }
//        var_dump($files);die;
        foreach($files['name'] as $key=>$v){
            $file=[
                "name"=>$v,
                "tmp_name"=>$files['tmp_name'][$key],
                "type" => $files['type'][$key],
				"error" => $files['error'][$key],
				"size" => $files['size'][$key]
            ];
//            var_dump($file);die;
            $result=$this->upload($file);
            if(!$result){
                continue;
            }
            array_push($results,$result);
        }
        return $results;
//        var_dump($file);
    }

    //上传文件
    public function upload($file=[]){
        $result=[];
        //文件上传是否出错
        if($file['error']){
            $this->errno=$file['error'];
            return;
        }

        //查看文件大小是否符合
        if(!$this->checkSize($file['size'])){
            $this->errno=1;
            return;
        }

        //检测后缀名
        if(!$this->checkExts($file['name'])){
            $this->errno=2;
            return;
        }
        //文件类型是否符合
        if(!$this->checkType($file['tmp_name'],$file['type'])){
            $this->errno=3;
            return;
        }
        //判断上传目录是否存在，不存在创建
        $this->checkUploadDir();
        //临时文件上传目录
        $filename=$this->newFileName($file['name']);
//        echo $filename;die;
        //is_uploaded_file — 判断文件是否是通过 HTTP POST 上传的
        if(!is_uploaded_file($file['tmp_name'])){
            $this->errno=5;
            return;
        }
        //move_uploaded_file — 将上传的文件移动到新位置
        move_uploaded_file($file['tmp_name'],$this->config['savePath'].DIRECTORY_SEPARATOR.$filename);

        //返回数据name size
        $result = [
            "name" => $file['name'],
            "size" => $file['size'],
            "savePath" => $this->config['savePath'],
            "filename" => $filename
        ];
//        var_dump($result);die;
        return $result;
    }

    //检查上传目录
    protected function checkUploadDir(){
        //is_dir — 判断给定文件名是否是一个目录
        if(!is_dir($this->config['savePath'])){
            //mkdir — 新建目录
            if(!mkdir($this->config['savePath'],0777,true)){
                $this->errno=4;
            }
        }
    }

    //生成上传名字
    protected function newFileName($name){
        $ext=$this->getExt($name);
        return uniqid().createRandString(5).'.'.$ext;
    }

    //检查类型
    protected function checkType($name,$type){
        //mime_content_type — 检测文件的 MIME 类型
        $mime =mime_content_type($name);
        if($mime==$type){
            return in_array($type,$this->config['mimeType']);
        }
        return false;
    }

    //检测后缀名
    protected function checkExts($name){
        $ext=$this->getExt($name);
        return in_array($ext,$this->config['ext']);
    }

    //得到文件后缀
    protected function getExt($name){
        //pathinfo — 返回文件路径的信息
        return pathinfo($name,PATHINFO_EXTENSION);
    }

    //检测文件大小
    protected function checkSize($size){
        if($this->config['size'] == -1){
            return true;
        }
        return $this->config['size'] > $size ? true :false;
    }

    //错误处理
    public function getError($errno=""){
        $errno= $errno ? $errno :$this->errno;
        $errstr='';
        switch($errstr){
            case UPLOAD_ERR_OK:
                $errstr="上传成功";
                break;
            case UPLOAD_ERR_FORM_SIZE:
                $errstr="上传文件的大小超过了 HTML 表单中 MAX_FILE_SIZE 选项指定的值。 ";
                break;
            case UPLOAD_ERR_OK:
                $errstr="上传成功";
                break;
            case UPLOAD_ERR_PARTIAL :
                $errstr="文件只有部分被上传。";
                break;
            case UPLOAD_ERR_NO_FILE :
                $errstr="上传成功";
                break;
            case UPLOAD_ERR_NO_TMP_DIR :
                $errstr="找不到临时文件夹。";
                break;
            case UPLOAD_ERR_CANT_WRITE:
                $errstr="文件写入失败。";
                break;
            case 1:
                $errstr="上传文件的大小超过了最大限制。";
                break;
            case 2:
                $errstr="上传文件的格式不符合。";
                break;
            case 3:
                $errstr="上传文件的类型不符合。";
                break;
            case 4:
                $errstr="创建目录失败。";
                break;
            case 5:
                $errstr="非法上传文件。";
                break;
            case 1007:
                $errstr="上传失败。";
                break;
        }
        return $errstr;
    }


    // 基于base64上传的方法
    public function uploadByStream($name,$size,$file){

        // 1. 文件的大小是否符合要求
        if(!$this->checkSize($size)){
            $this->errno = 8;
            return;
        }

        // 2. 文件的后缀是否符合要求
        if(!$this->checkExts($name)){
            $this->errno = 9;
            return;
        }
        // 4.1 判断上传目录是否存在，不存在创建
        $this->checkUploadDir();

        // 4.2将临时文件移动到指定的上传目录
        $filename = $this->newFileName($name);

        file_put_contents($this->config['savePath'].DIRECTORY_SEPARATOR.$filename,base64_decode($file));
        // 5. 返回数据
        // 返回的数据 name 、 size
        $result = [
            "name" => $name,
            "size" => $size,
            "savePath" => $this->config['savePath'],
            "filename" => $filename
        ];
        return $result;
    }
}

?>