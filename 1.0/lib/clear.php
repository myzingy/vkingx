<?php if(!defined("FF"))die('FF');
@removeDir(FF."cache/".implode("/",$FF)."/");
@createDir(FF."cache/");
if($_SERVER['HTTP_REFERER']){
	header("location:{$_SERVER['HTTP_REFERER']}");
	exit;
}
header("location:".site_url());
?>