<?php if(!defined("FF"))die('FF');

require_once(FF."include/config.php");

$data['base_url']=$base_url;
$data['index_page']=$index_page;
$selfurl=$host.$_SERVER['REQUEST_URI']; //��ҳ���ַ

//eregi("index\.php/(.*)",$selfurl,$tmp);
preg_match("/index\.php\/(.*)/",$selfurl,$tmp);
$FF=explode("/",str_replace("//","/",$tmp[1]));

if(!!$FF[0]){
	$file=array_shift($FF);	
}
require_once(FF."include/function.php");

if($cache && !$_POST){
    require_once(FF."include/cache.class.php");
    $C=new cache($file,$FF);
	$chtml=$C->show();
	//ob_clean();
	if($chtml){
		die($chtml);
	}
	//echo "aaaa";
}else{
    class dt{
        function display(){}
    }
    $C=new dt();
}
///cache end
//load lib
in(FF."lib/{$file}.php");
