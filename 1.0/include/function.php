<?php
function load_layout($type=1){
	
	$arr=array(
		1=>'main',
		2=>'main_left_right',
		3=>'ajax'
	);
	in(FF."layout/{$arr[$type]}.php");
	
}
function site_url($s=''){
	global $base_url,$index_page;
	return $base_url.($s?"{$index_page}/{$s}":"");
}
function in($file){
	global $data,$FF,$C;
	extract($data);
	if(file_exists($file)){
		require_once($file);
	}else{
		$data['title']="页面未找到";
		$data['error']="{$file} not exists!!!";
		$data['view']="error";
		load_layout();
	}
	unset($data);
}
function get_url($filepath){
	global $base_url;
	return str_replace(FF,$base_url,$filepath);
}
function convert_reback() {
	$args=func_get_args(); 
	if(!$args) return;
	$do="\$a=array(";
	for($i=0;$i<count($args);$i++){
		$do.=$args[$i];
		$do.=(($i+1)<count($args))?",":");";
	}
	@eval($do);
	return ($a);
}
function createDir($dir){
	$dir=str_replace("\\","/",$dir);
	//$dir=explode("/",$dir);
	if(substr($dir,0,-1)!="/"){
		$dir.="/";
	}
	$mode='0777';
	if (!file_exists($dir)){
		if (!file_exists($dir)){
			createDir(dirname($dir));
			mkdir($dir, 0777);
		}
	}
}
function removeDir($dir) {
  if ($handle = opendir("$dir")) {
   while (false !== ($item = readdir($handle))) {
     if ($item != "." && $item != "..") {
       if (is_dir("$dir/$item")) {
         @removeDir("$dir/$item");
       } else {
         @unlink("$dir/$item");
       }
     }
   }
   closedir($handle);
   @rmdir($dir);
  }
  
  return true;
}
function dir_list($path){
	$list=array();
	//$path=iconv("UTF-8","GBK",$path);
	if( !file_exists($path) || !is_dir($path)  ) return false;
	if ($dh = opendir($path)) {
        while (($file = readdir($dh)) !== false) {
			if(strstr($file,".tmp_")) continue;
        	$pathinfo=pathinfo($path . $file);
			$list[]=array(
				//'name'=>iconv("GBK","UTF-8",$file),
                'name'=>$file,
				'type'=>filetype($path . $file),
				'time'=>filemtime ($path . $file),
				'mini_type'=>$pathinfo['extension']
			);
        }
        closedir($dh);
    }
	return $list;
}
?>