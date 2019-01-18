<?php if(!defined("FF"))die('FF');
//in(FF."include/graph.class.php");
$passwd='fan@jing';
$data['title']="Admin";
$data['view']='admin';
$data['time']=substr(md5((TIME-TIME%120).$passwd), 0,8);
if(!empty($_POST['act'])){
	$post=json_decode($_POST['act'],true);
	var_dump($post);
	if($post['dirname'] && $post[$data['time']]==$passwd){
		$dir=FF."doc/".ucfirst(strtolower($post['dirname']));
		createDir($dir);
		if (is_uploaded_file($_FILES['file']['tmp_name'])) {
			$filename=iconv('utf-8', 'gbk', $_FILES['file']['name']);
			$filename=str_replace(" ", "_", $filename);
		   move_uploaded_file($_FILES['file']['tmp_name'], $dir."/".$filename);
		}
	}
	header("Location:{$_SERVER['HTTP_REFERER']}");
	exit();
}
load_layout();
//@$C->display(0,3);
?>