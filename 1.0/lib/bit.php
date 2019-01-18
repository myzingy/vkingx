<?php if(!defined("FF"))die('FF');

$data['title']=$FF[0];
$data['keyword']=$FF[0];
$data['list']=dir_list(FF."doc/".implode("/",$FF)."/");
$data['now']=implode("/",$FF);
$data['last']=array_pop($FF);
if($FF){
	$data['per']=implode("/",$FF);
}
$data['view']='bit_list';
load_layout();
@$C->display(720,3);
?>