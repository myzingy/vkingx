<?php if(!defined("FF"))die('FF');
in(FF."include/graph.class.php");
$data['title']="图形动画";
$data['keyword']="图形动画";
$data['view']='graph_basic';
if($FF[0]=='basic'){
	$graph=new graph();
	if($_POST){
		$cr=$_POST['cr']/2;
		$cc=$_POST['img_size']/2;
		$graph->set(array(
			'snum'=>$_POST['snum'],
			'width'=>$cr,
			'height'=>$cr,
			'c_x'=>$cc,
			'c_y'=>$cc
		));
		$graph->init();
		$graph->get_sarr();
		
		if($_POST['img_ext']==1){
			for($i=1;$i<$cr-10;$i+=5){
				$r=$cr-$i;
				$graph->set(array('snum'=>$_POST['snum'],'width'=>$r,'height'=>$r));
				$graph->get_sarr($i*$_POST['cc_x'],$i*$_POST['cc_y']);
			}
		}
		$image_src=$graph->create_img($_POST['img_size'],$_POST['img_size'],$_POST['draw']?$_POST['draw']:NULL);
		die(get_url($image_src)."?".time());
	}
	$graph->set(array('snum'=>9,'width'=>500,'height'=>500,'c_x'=>250,'c_y'=>250));
	
	$graph->init();
	$graph->get_sarr();
	for($i=1;$i<250-10;$i+=5){
		$r=250-$i;
		$graph->set(array('width'=>$r,'height'=>$r));
		$graph->get_sarr($i);
	}
	$data['image']=$graph->create_img(500,500);
}
if($FF[0]=='curve'){
	$data['title'].=" 图形曲线";
	$data['keyword'].=" 图形曲线";
}
if($FF[0]=='anim'){
	$data['title'].=" 动态图片";
	$data['keyword'].=" 动态图片 GIF";
}
if($FF[0]=='1.0'){
	$drawarr=array('line','polygon','circle');
	//FF[1]='b_600_10_1.0_1.0_0_x.png';
	list($type,$size,$snum,$cc_x,$cc_y,$drawtype,$ext)=explode("_",$FF[1]);
	if('x.gif'!=$ext){
		if(!$_SERVER['HTTP_REFERER']){
			header("location:".site_url('graph/basic'));
		}
	}
	$type=in_array($type,array('a','b','c'))?$type:'b';
	$size=($size>600?600:($size<100?100:$size))+0;
	$cc_x=($cc_x>20?20:($cc_x<-20?-20:$cc_x))+0;
	$cc_y=($cc_y>20?20:($cc_y<-20?-20:$cc_y))+0;
	if(!in_array($drawtype,$drawarr)){
		$drawtype=($drawtype>2?2:($drawtype<0?0:$drawtype))+0;
		$drawtype=$drawarr[$drawtype];
	}
	if($type=='b'){
		$graph=new graph();
		$graph->set(array('snum'=>$snum,'width'=>$size,'height'=>$size,'c_x'=>$size/2,'c_y'=>$size/2,'beta'=>true));
		$graph->init();
		$graph->get_sarr();
		for($i=1;$i<$size/2-10;$i+=5){
			$r=$size/2-$i;
			$graph->set(array('width'=>$r,'height'=>$r));
			$graph->get_sarr($i*$cc_x,$i*$cc_y);
		}
		echo $graph->create_img($size,$size,$drawtype);
		exit;
	}
}
load_layout();
?>