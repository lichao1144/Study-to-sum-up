<?php

/*
 * 转为json格式，json_encode
 * */
//$arr=[
//    "name"=>"lichao",
//    "age"=>23,
//    "hobby"=>"fuck you"
//];
//echo json_encode($arr);
//echo"<br>";
//----------------------------------------------------------------------
/*
 * xml可扩展标记语言
 * 格式为：
 *      <?xml version='1.0' encoding='utf-8' ?>
 *      <root>
 *          <name>李超</name>
 *          <age>23</age>
 *          <hobby>fuck you</hobby>
 *      </root>
 * php怎么解析xml
 *      1.simplexml_load_string  从变量中解析xml
 *      simplexml_load_file    从指定的xml文件进行解析
 * */
    $str="<?xml version='1.0' encoding='utf-8' ?>
       <root>
           <name>李超</name>
           <age>23</age>
           <hobby>fuck you</hobby>
       </root>";
    $obj=simplexml_load_string($str);
    $json=json_decode(json_encode($obj),true);
    var_dump($json);
//--------------------------------------------------------------------
//2.xmlreader 逐行解析
$xmlReader=new XMLReader();
$xml=$xmlReader->XML($str);
//解析xml
$data=[];
while($xmlReader->read()){
    if($xmlReader->nodeType == XMLReader::ELEMENT){
        $nodeNmae=$xmlReader->name;
    }
    if($xmlReader->nodeType == \XMLReader::TEXT && !empty($nodeNmae)){
            $data[$nodeNmae]=strtolower($xmlReader->value);
    }
}
var_dump($data);
//------------------------------------------------------------
//3.DOMDOCument
$dom=new DOMDocument();
$dom->loadXML($str);
$data=[];
$data['name']=$dom->getElementsByTagName("name")[0]->nodeValue;
$data['age']=$dom->getElementsByTagName("age")[0]->nodeValue;
$data['hobby']=$dom->getElementsByTagName("hobby")[0]->nodeValue;
var_dump($data);
?>