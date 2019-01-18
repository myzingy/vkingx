<?php
if (!defined("FF"))
	die('FF');
//in(FF."include/graph.class.php");
$data['title'] = "在线 post工具，跨站工具";
$data['keyword'] = $data['bit_title'];
$data['view'] = 'xpost';
if ($data['p_uri'] = $_POST['uri']) {
	$data['p_header']=$header = $_POST['header'];
	$data['p_data']=$pdata = $_POST['data'];
	$method=$_POST['method']=='POST'?true:false;
	$pdata = str_replace(":", "=", $pdata);
	$pdata = str_replace("\r\n", "&", $pdata);
	$header=preg_replace("/(Content-Length:)([0-9]+)/", "\$0".(strlen($pdata)), $header);
	$header = explode("\r\n", $header);
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

	$response = curl_exec($ch);
	//接收返回信息
	$data['result'] = "200 OK";
	if (curl_errno($ch)) {//出错则显示错误信息

		$data['result'] = curl_error($ch);

	}

	curl_close($ch);
	//关闭curl链接
}
load_layout();
@$C -> display(0, 1);
?>