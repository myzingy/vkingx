<?php if(!defined("FF"))die('FF');
/*******************************************************************************************************
*
* author:vking
* updatetime:2010-07-13 
* 
* 调用说明：	
* 代码中FF为网站根目录路径 通过define("FF",str_replace("\\", "/",dirname(__FILE__))."/");设置
* $ps=new caishu();//实例化
* $ps->show_index();//返回索引数组。
* 猜数字直接使用索引数组即可，猜姓氏需要以索引数组为姓氏数组下标。
* 如感觉显示效果单一，可将索引数组打乱。
*
********************************************************************************************************/
class caishu{
	var $array_index;//索引数组
	function __construct(){
	
	}
	function show_index($num=5){
		$this->create_index($num);
		return $this->array_index;
	}
	//核心算法，感谢老曾同学，不得不说他很强大
	function create_index($shift){
		$arr = array();
		for ($i=0;$i<$shift;$i++){
			$arg = 1<<$i;
			for ($j=$arg;$j<1<<$shift;$j+=$arg<<1){
				for ($k=0;$k<$arg;$k++){
					$arr[$i][]=$k+$j;
				}
			}
		}
		$this->array_index=$arr;
	}
	//随机化2唯索引数组
	function rand_index($num=5){
		$this->create_index($num);
		for($i=0;$i<count($this->array_index);$i++){
			shuffle($this->array_index[$i]);
		}
		return $this->array_index;
	}
}
?>