<?php if(!defined("FF"))die('FF');
//统计
createDir(FF."/data/count/");
$count_year=date("Y",TIME);
$count_index=date("Ymd",TIME);
$getcount=array();
if(file_exists(FF."/data/count/getcount_{$count_year}.php")){
	include_once(FF."/data/count/getcount_{$count_year}.php"); //return $getcount['yearmonthday'];
}
if($FF[0]=="show"){
	if($FF[1]=="count"){
		echo intval(array_sum($getcount)/3);
		exit;
	}
	echo "<pre style=font-size:12px>";
	print_r($getcount);
	exit;
}
if(count($getcount)>30){
	//合并统计处理
}
$getcount[$count_index]+=1;
@file_put_contents(FF."/data/count/getcount_{$count_year}.php","<?php\n\$getcount=".var_export ($getcount,true)."\n?>");

//加载淘宝数据
@include_once(FF."/data/taobao.php"); //return $TB;

$wx=array("金", "木", "水", "火", "土");
$FF[0]=!$FF[0]?0:$FF[0];
$data['title']=$wx[$FF[0]];
$data['keyword']=$wx[$FF[0]];
$data['info']=($TB[$wx[$FF[0]]]);
shuffle($data['info']);
$data['view']='taobao';
load_layout(3);
//@$C->display(720,3);

?>