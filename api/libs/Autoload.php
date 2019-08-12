<?php
class Autoload{
    //1.__autoload()魔术方法  全局只能有一个
    //2. spl_autoload_register 注册自动加载函数 可以注册多个

    //注册自动加载
    public function __construct(){
        //注册自动加载类函数
        spl_autoload_register([$this,"_autoload"]);

        //加载函数库
        $this->loadFunctions();

        //注册异常处理
//        set_exception_handler([$this,"exceptionHandler"]);
//        set_error_handler([$this,"errorHandler"]);
//        register_shutdown_function([$this,"shutdownHandler"]);
    }

    //自动加载
    protected function _autoload($name){
//            echo $name;echo"<br>";
        $ext=".php";
        $file=str_replace("\\",DIRECTORY_SEPARATOR,$name).$ext;
//        echo $file;echo"<br>";
        //找到文件的真实路径
        $file=APP_PATH.DIRECTORY_SEPARATOR."../".$file;
//        echo $file;die;
        if(file_exists($file)){
            include_once $file;
        }
    }

    //自动加载函数库
    protected function loadFunctions($path=''){
            $paths=[
                FUNC_PATH,
                $path
            ];
        foreach($paths as $path){
            //判断是否存在文件
            if($path && is_dir($path)){
                //打开一个目录，读取它的内容，然后关闭：
                if($dir=opendir($path)){
                    while(($file=readdir($dir)) !==false){
                        if($file != '.' && $file != '..'){
                            include $path.DIRECTORY_SEPARATOR.$file;
                        }
                    }
                }
            }
        }

    }
}
new Autoload();
?>