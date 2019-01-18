<?php if(!defined("FF"))die('FF');
header('Content-Type:text/html;charset=utf-8');

$SOFT=array(
	'GodStick'=>array(
		'name'=>'神棍',
		'Version'=>'3.5',
		'date'=>'2011-03-23',
		'info'=>'本次主要针对性能方面修改<br />*优化页面绘图线程<br />*切换界面释放多余线程<br />*六爻增加变卦显示<br />*重新制作在线升级流程'
	),
);
$softname=$FF[0];
$softVersion=$FF[1];
if($SOFT[$softname]){
	if($SOFT[$softname]['Version']!=$softVersion){
		$downurl="http://www.meifanchi.com/vking/android/{$FF[0]}/{$FF[0]}{$SOFT[$softname]['Version']}.apk";
		echo "<font color=red>有新的版本（{$SOFT[$softname]['date']} {$FF[0]}{$SOFT[$softname]['Version']}.apk）";
		echo "<br />{$SOFT[$softname]['info']}";
		echo "<br /><a href=\"{$downurl}\">点击下载</a>，安装前请删除老版本！</font>";
	}else{
		echo "已经是最新版";
	}
}
?>