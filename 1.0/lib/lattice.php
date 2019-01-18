<?php if(!defined("FF"))die('FF');
$ff_arr=array(
	'12'=>array(
		'name'=>'(12x12)GB2312',
		'ff_w'=>16,
		'ff_h'=>12,
		'path'=>FF."hzk/HZK12"
	),
	'16'=>array(
		'name'=>'(16x16)GB2312',
		'ff_w'=>16,
		'ff_h'=>16,
		'path'=>FF."hzk/HZK16"
	),
	'24S'=>array(
		'name'=>'24p宋体(汉字)',
		'ff_w'=>24,
		'ff_h'=>24,
		'path'=>FF."hzk/HZK24S"
	),
	'24H'=>array(
		'name'=>'24p黑体(汉字)',
		'ff_w'=>24,
		'ff_h'=>24,
		'path'=>FF."hzk/HZK24H"
	),
	'24K'=>array(
		'name'=>'24p楷体(汉字)',
		'ff_w'=>24,
		'ff_h'=>24,
		'path'=>FF."hzk/HZK24K"
	),
	'24F'=>array(
		'name'=>'24p仿宋(汉字)',
		'ff_w'=>24,
		'ff_h'=>24,
		'path'=>FF."hzk/HZK24F"
	),
	'24T'=>array(
		'name'=>'24pT(宋体英文字符)',
		'ff_w'=>24,
		'ff_h'=>24,
		'path'=>FF."hzk/HZK24T"
	),
	
);
in(FF."include/lattice.class.php");
$lattice=new lattice();
if($_POST){
	//if(eregi("/[a-z]+",$_POST['font_color_1'])){
	if(preg_match("/\/[a-z]+/",$_POST['font_color_1'])){
		$_POST['font_color_1']='<img src="images/qq'.$_POST['font_color_1'].'.gif" width="24" height="24" />';
		$_POST['font_color_2']='<img src="images/qq'.$_POST['font_color_2'].'.gif" width="24" height="24" />';
	}
	foreach($_POST as $k=>$v){
		$_POST[$k]=str_replace('\\','',$v);
	}
	$lattice->set($_POST);
	$lattice->set(array(
		'ff'=>$ff_arr[$_POST['ffid']]['path'],
		'ff_w'=>$ff_arr[$_POST['ffid']]['ff_w'],
		'ff_h'=>$ff_arr[$_POST['ffid']]['ff_h']
	));
	echo $lattice->change();
	exit;
}
$lattice->set(
	array(
		'line_num'=>1
		,'ff'=>FF."hzk/HZK24K"
		,'ff_w'=>24
		,'ff_h'=>24
		,'font_color_1'=>'★'
		,'font_color_2'=>'　'
	)
);
$data['title']="点阵汉字";
$data['keyword']="点阵汉字";
$data['lattice']=$lattice->change("静悄悄");
$data['view']='lattice';
$data['ff_arr']=$ff_arr;
load_layout();
@$C->display(720,3);
