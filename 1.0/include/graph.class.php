<?php if(!defined("FF"))die('FF');
/*******************************************************************************************************
*
* author:vking
* updatetime:2010-07-14 
* 
* 调用说明：	
* 代码中FF为网站根目录路径 通过define("FF",str_replace("\\", "/",dirname(__FILE__))."/");设置
* $graph=new graph();//实例化
* $graph->set(array('snum'=>9,'width'=>500,'height'=>500,'c_x'=>250,'c_y'=>250));
* $graph->init();
* $graph->get_sarr();
	for($i=1;$i<250-10;$i+=5){
		$r=250-$i;
		$graph->set(array('width'=>$r,'height'=>$r));//重设外接圆半径
		$graph->get_sarr($i);//设置起点坐标偏移
	}
	$image_src=$graph->create_img(500,500);//输出生成image的路径地址，要显示到网站上你需将地址转为http地址。
*	
*
********************************************************************************************************/
class graph{
	var $snum;//坐标个数，如是三角形此数应为3
	var $darr;//坐标基数数组；
	var $sarr;//坐标数组；
	var $c_x;//圆心横坐标
	var $c_y;//圆心纵坐标
	var $width;//圆宽度
	var $height;//圆高度
	var $beta=false;//图片输出方式
	function __construct(){
	
	}
	function set($arr=null){
		if(!is_array($arr)) return;
		foreach($arr as $k=>$v){
			$this->$k=$v;
		}
	}
	function init(){//核心函数，计算圆被等分的坐标点（弧度值）
		$this->snum=$this->snum?$this->snum:3;
		for($i=0.0,$p=0;$i<2*M_PI;$i+=(2*M_PI)/$this->snum,$p++){
			$this->darr[$p]=array(
				'x'=>$i,
				'y'=>$i
			);
		}
	}
	function get_sarr($x=0,$y=0){//根据弧度及偏移$x,$y计算点坐标
		$x=deg2rad($x);//角度转弧度。
		$y=$y?deg2rad($y):$x;
		$this->c_x=$this->c_x?$this->c_x:200;
		$this->c_y=$this->c_y?$this->c_y:150;
		$this->width=$this->width?$this->width:100;
		$this->height=$this->height?$this->height:100;
		foreach($this->darr as $i=>$v){
			$sarr[$i]=array(
				'x'=>(int)($this->width*cos($v['x']+$x)+$this->c_x),
				'y'=>(int)($this->height*sin($v['y']+$y)+$this->c_y)
			);
		}
		$this->sarr[]=$sarr;
	}
	function create_img($width=0,$height=0,$type='polygon',$url=''){
		$width=$width?$width:400;
		$height=$height?$height:300;
		$im=imagecreate($width,$height);
		$bg_color=imagecolorallocate($im,0,0,0);
		$rgb=array(rand(0,255),rand(0,255),rand(0,255));
		$rgb_index=rand(0,2);
		$step=-15;
		$snum=count($this->sarr);
		if($type=='line'){
			
			for($i=0;$i<$snum;$i+=2){
				if($rgb[$rgb_index]+$step<0 || $rgb[$rgb_index]+$step>255){
					$step=-$step;
				}
				$rgb[$rgb_index]=$rgb[$rgb_index]+$step;
				$color=imagecolorallocate($im,$rgb[0],$rgb[1],$rgb[2]);
				foreach($this->sarr[$i] as $k=>$v){
					if(!$this->sarr[$snum-$i][$k]['x']) break;
					imageline($im,$v['x'],$v['y'],$this->sarr[$snum-$i][$k]['x'],$this->sarr[$snum-$i][$k]['y'],$color);
				}
			}
		}
		$step=-abs($step);
		if($type=='polygon'){
			foreach($this->sarr as $tuone){
				if($rgb[$rgb_index]+$step<0 || $rgb[$rgb_index]+$step>255){
					$step=-$step;
				}
				$rgb[$rgb_index]=$rgb[$rgb_index]+$step;
				$color=imagecolorallocate($im,$rgb[0],$rgb[1],$rgb[2]);
				$tuarr=array();
				foreach($tuone as $pos){
					$tuarr[]=$pos['x'];
					$tuarr[]=$pos['y'];
				}
				imagepolygon($im,$tuarr,count($tuone),$color); 
			}
		}
		$step=-abs($step);
		if($type=='circle'){
			foreach($this->sarr as $i=>$tuone){
				if($rgb[$rgb_index]+$step<0 || $rgb[$rgb_index]+$step>255){
					$step=-$step;
				}
				$rgb[$rgb_index]=$rgb[$rgb_index]+$step;
				$color=imagecolorallocate($im,$rgb[0],$rgb[1],$rgb[2]);
				foreach($tuone as $pos){
					imageellipse($im,$pos['x'],$pos['y'],$snum+10-$i,$snum+10-$i,$color); 
				}
				
			}
		}
		imagestring($im, 5, $width-100, $height-15, "vking@T-800", $color);
		if($this->beta){
			ob_start();
			header("Content-type: image/png");
			@imagepng($im);
			$image_data = ob_get_contents();
       		ob_end_clean();
			return $image_data;
		}
		$url=$url?$url:(FF."data/basic.png");
		@imagepng($im,$url);
		imagedestroy($im);
		return $url;	
	}
}
?>