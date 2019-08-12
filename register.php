<?php
$name=$_POST['name'];
$pwd=$_POST['pwd'];

//echo $name;
//echo $price;
$link=mysqli_connect('localhost','root','root','mouth6');
mysqli_set_charset($link,'utf8');
$sql="insert into userinfo (name,pwd) values($name,$pwd)";
$result=mysqli_query($link,$sql);
if($result && mysqli_affected_rows($link)>0){
    echo json_encode(['code'=>1,'msg'=>'insert ok']);
}else{
    echo json_encode(['code'=>2,'msg'=>'insert no']);
}
mysqli_close($link);

?>