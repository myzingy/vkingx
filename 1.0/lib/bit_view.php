<?php if(!defined("FF"))die('FF');
$codefile=FF."doc/".implode("/",$FF);
//$codefile=iconv("UTF-8","GBK",$codefile);
if(!file_exists($codefile)){
	//$FF[count($FF)-1]=iconv("UTF-8","GBK",urldecode($FF[count($FF)-1]));
    $FF[count($FF)-1]=urldecode($FF[count($FF)-1]);
	$codefile=urldecode($codefile);
	//$codefile=iconv("UTF-8","GBK",$codefile);
}

$data['bit_title']=$FF[count($FF)-1];
//$data['bit_title']=iconv("GBK","UTF-8",$data['bit_title']);
array_pop($FF);
if($FF){
	$data['per']=implode("/",$FF);
}
$data['title']=$data['bit_title'];
$data['keyword']=$data['bit_title'];
in(FF."include/file.function.php");
$data['code']=get_code($codefile);
$data['view']='code';
load_layout();
@$C->display(0,5);
?>