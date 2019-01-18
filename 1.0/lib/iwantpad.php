<?php
if (!defined("FF"))
	die('FF');
//in(FF."include/graph.class.php");
$data['title'] = "在线 post工具，跨站工具";
$data['keyword'] = $data['bit_title'];
$data['view'] = 'xpost';
/*
$nicknamestr=file_get_contents(FF.'/lib/iwantpad/nickname.txt');
$nicknamearr=explode(',', $nicknamestr);
$nicknamelength=count($nicknamearr);
$nickname="";
$nicknamesize=rand(3,10);
for($i=0;$i<$nicknamesize;$i++){
	$index=rand(0,$nicknamelength);
	$nickname.=$nicknamearr[$index];
}
*/
/*
$nicknamestr=file_get_contents(FF.'/lib/iwantpad/nickname3.txt');
$nicknamearr=explode('######', $nicknamestr);
$nicknamearr1=explode(',', trim($nicknamearr[0]));
$nicknamearr2=explode(',', trim($nicknamearr[1]));
$nicknamearr3=explode(',', trim($nicknamearr[2]));
$i1=rand(0,count($nicknamearr1));
$i2=rand(0,count($nicknamearr2));
$i3=rand(0,count($nicknamearr3));
$nickname=$nicknamearr1[$i1].$nicknamearr2[$i2].$nicknamearr3[$i3];
*/
$nicknamestr=file_get_contents(FF.'/lib/iwantpad/nickname5.txt');
$nicknamearr=explode(',', $nicknamestr);
$nicknamelength=count($nicknamearr);
$nickname="";
$nicknamesize=rand(1,4);
for($i=0;$i<$nicknamesize;$i++){
	$index=rand(0,$nicknamelength);
	$tmp=trim($nicknamearr[$index]);
	if(!$tmp){
		$i--;
		continue;
	}
	$nickname.=$tmp;
}
////////////////////////////
$register=file_get_contents(FF.'/lib/iwantpad/register.tpl.txt');
$registerarr=explode('######', $register);

$mailserver=array('126','163','qq','sina','sohu','gmail');
$index=rand(0,5);
$mail=$mailserver[$index];
if($mail=='qq'){
	$username=abs(rand(123456,2345678900));
}else{
	$l=rand(5,20);
	$username="";
	for($i=0;$i<$l;$i++){
		$ord=rand(97,122);
		$username.=chr($ord);
	}
	
}
//$mail=$username.'@'.$mail.'.com';
$sidstr=file_get_contents(FF.'/lib/iwantpad/str.register.txt');
$sidarr=preg_split("/#([^#]+)#/", $sidstr);
$userindex=rand(1,2);
//$userindex=1;
$user=$sidarr[$userindex];
$user=explode(";", trim($user));
$in_u=explode("=", trim($user[0]));
$JSESSIONID=explode("=", trim($user[1]));
$replace_key=array('{in_u}','{JSESSIONID}');
$replace_val=array(trim($in_u[1]),trim($JSESSIONID[1]));
$registerarr[1]=str_replace($replace_key, $replace_val, $registerarr[1]);
//$mail="newpad{$FF[0]}@appcoffee.cn";
$mail="memo{$FF[0]}@sx.meifanchi.com";
$registerarr[2]=str_replace('{USERNAME}@{MAIL}.com', $mail, $registerarr[2]);
$registerarr[2]=str_replace('{NICKNAME}', $nickname, $registerarr[2]);
$method='POST';
$data['p_uri']=trim($registerarr[0]);
$data['p_header']=$header = trim($registerarr[1]);
$data['p_data']=$pdata = trim($registerarr[2]);
$pdata = str_replace(":", "=", $pdata);
$pdata = str_replace("\r\n", "&", $pdata);
$header=preg_replace("/(Content-Length:)([0-9]+)/", "\$0".(strlen($pdata)), $header);
$header = explode("\r\n", $header);
var_dump($method,$data['p_uri'],$header,$pdata);
$ch = curl_init();
//初始化curl
if(!$method){//GET
	curl_setopt($ch, CURLOPT_URL, $data['p_uri'].'?'.$pdata);
	//设置链接
}else{
	curl_setopt($ch, CURLOPT_URL, $data['p_uri']);
	//设置链接

	curl_setopt($ch, CURLOPT_POST, 1);
	//设置为POST方式

	curl_setopt($ch, CURLOPT_POSTFIELDS, $pdata);
	//POST数据
}
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//设置是否返回信息

curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
//设置HTTP头

curl_setopt($ch, CURLOPT_TIMEOUT, 15);   
//只需要设置一个秒的数量就可以

echo $response = curl_exec($ch);
//接收返回信息
$data['result'] = "200 OK";
unlink(FF.'/cache/tmp.html');
file_put_contents(FF.'/cache/tmp.html', $response);
if (curl_errno($ch)) {//出错则显示错误信息
	$data['result'] = curl_error($ch);
}

curl_close($ch);
$c_file=FF.'/lib/iwantpad/count_'.date('Y-m-d',TIME).'.txt';
$fp=@fopen($c_file, 'a+');
@fwrite($fp,"\n".$FF[0]."\t\t".$mail."\t\t".$nickname."\t\t".$userindex);
@fclose($fp);
if($FF[0]>=500) {
	echo "<pre>";	
	echo file_get_contents($c_file);
	die();
}
$_SERVER['REQUEST_URI']=str_replace($FF[0], $FF[0]+1, $_SERVER['REQUEST_URI']);
echo '<meta http-equiv="refresh" content="1; url=http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'].'" />';
//关闭curl链接
?>