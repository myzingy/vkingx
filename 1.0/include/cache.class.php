<?php
/*
*cache class
*/
class cache{
	var $file;
	var $FF;
	var $time;//分钟，0永久
	var $step;
	var $cachedir;
	var $flag;
	function __construct($file,$FF){
		$this->file=$file;
		$this->FF=$FF;
		$this->cachedir=FF."/cache/";
		$this->step=5;
		ob_start();
	}
	function show(){
		$cache_file=$this->cache_name();
		if(file_exists($cache_file)){
			$cache=file_get_contents($cache_file);
			if ( ! preg_match("/(\d+TS-->)/", $cache, $match))
			{
				return FALSE;
			}
			$cachetime=trim(preg_replace("/[^0-9]+/", '', $match['1']));
			if (TIME >= $cachetime && $cachetime!=333)
			{ 		
				@unlink($cache_file);
				return FALSE;
			}
			return str_replace($match['0'], '', $cache);
		}
	}
	function display($time=60,$step=2){
		$this->time=$time;
		$this->step=$step;
		$cache=$this->show();
		if(!$cache){
			$cache=$this->_write();
			ob_clean();
		}
		die($cache);
		
	}
	function _write(){
		createDir($this->cachedir.$this->file);
		if($this->time>0){
			$time=TIME+$this->time*60;
			$c="{$time}TS-->";
		}else{
			$c="333TS-->";
		}
		$cache=ob_get_contents();
		file_put_contents($this->cache_name(),$c.$cache);
		return $cache;
	}
	function cache_name(){
		for($i=0;$i<$this->step;$i++){
			if($this->FF[$i]){
				$param[]=urlencode($this->FF[$i]);
			}
		}
		$param=$param?$param:array('index');
		$param=implode("-",$param).".cache";
		return $this->cachedir.$this->file."/".$param;
	}
}
?>