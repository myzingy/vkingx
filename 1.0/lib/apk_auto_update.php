<?php if(!defined("FF"))die('FF');
header('Content-Type:text/html;charset=utf-8');
$apk_dir="android/{$FF[0]}/";
$apk_path=FF.$apk_dir;
$list=array();
if ($dh = opendir($apk_path)) {
	while (($file = readdir($dh)) !== false) {
		if($file=='.' || $file=='..') continue;
		$pathinfo=pathinfo($apk_path . $file);
		if($pathinfo['extension']!='apk') continue;
		if(filemtime ($apk_path . $file)>$list['t']){
			$list=array(
				'v'=>$pathinfo['filename'],
				'u'=>$base_url.$apk_dir.$file,
				't'=>filemtime ($apk_path . $file),
				's'=>filesize($apk_path . $file)
			);
		}
	}
	closedir($dh);
}
$list['t']=date("Y-m-d",$list['t']);
if (file_exists($apk_path.$list[v].'.txt')) {
    $list['i']=file_get_contents($apk_path.$list[v].'.txt');
} 
echo json_encode($list);
?>