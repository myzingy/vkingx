<?php if(!defined("FF"))die('FF');
$codefile=FF."include/{$FF[0]}.class.php";
in(FF."include/file.function.php");
$data['code']=get_code($codefile);
if($FF[0]=='lattice'){
	$data['title']="点阵汉字源码解析";
	$data['keyword']="点阵汉字源码解析";
}
if($FF[0]=='caishu'){
	$data['title']="猜数字源码解析";
	$data['keyword']="猜数字源码解析";
}
$data['view']='code';
load_layout();
@$C->display(0,1);
?>