<?
/***************************************************
*函数名: clear_sql;
*作用: 转义$str的值;
*返回值:转义的串;
*示例: clear_sql($str);
*依附关系:无;
***************************************************/
function clear_sql(){
	$Q=func_get_arg(0);
	if (is_array($arg=$Q)){
		for ($i=0; $i<sizeof($arg); $i++){
			$arg[$i] = clear_sql($arg[$i]);
		}
		return $arg;
	}
	if (get_magic_quotes_gpc()==1){//On
		return $Q;
	}else{//Off
		return addslashes($Q);
	}
	
}
/***************************************************
*函数名: clear_htm;
*作用: 转义$str的htm标记或清除htm标记;
*返回值:转义的串;
*示例: clear_htm($str);clear_htm($str,1);
*依附关系:HTM_CHAR_SET需要设置,这里为GB2312;
***************************************************/
function clear_htm(){
	if (!defined('HTM_CHAR_SET')) {
    	define("HTM_CHAR_SET","GB2312");
	}
	$str=func_get_arg(0);
	if(func_num_args()==2 && func_get_arg(1)){
		if(is_array($str)){
			foreach($str as $key=>$value){
				$var[$key]=preg_replace("/<.*?>/","",$value);
			}
			return $var;
		}else{
			return preg_replace("/<.*?>/","",$str);
		}
	}else{
		if(is_array($str)){
			foreach($str as $key=>$value){
				$var[$key]=preg_replace("/<.*?>/","",$value);
			}
			return $var;
		}else{
			return htmlentities($str,ENT_COMPAT,HTM_CHAR_SET);
		}	
	}
}
/***************************************************
*函数名: clear($1,$2);$1为要处理的对象，$2为1时将过滤htm;不为1时转意htm
*作用: 获取$_get $_post $_request 的值;
*返回值:转义的串或数组;
*示例: clear($_get);clear($_post['type']);
*依附关系:必须存在串处理函数clear_sql();clear_htm();
***************************************************/
function clear(){
	$var=func_get_arg(0);
	if(!$var) return false;
	$flag=(func_num_args()==2)?true:false;
	$htm=0;
	if($flag){
		$htm=func_get_arg(1);	
	}
	if(is_array($var)){
		foreach($var as $key=>$value){
			$var[$key]=clear_sql($value);
			if($flag){
				$var[$key]=clear_htm($value,$htm);	
			}
		}	
	}else{
		$var=clear_sql($var);
		if($flag){
			$var=clear_htm($var,$htm);	
		}	
	}
	return $var;
	
}
/***************************************************
*函数名: replaceForm
*作用: 返回标准SQL;
*返回值:返回标准SQL;
*示例: replaceForm('DBSQL','DBTABLE',array('name'=>'姓名1','sex'=>'性别1','age'=>'年龄1'));
* REPLACE INTO DBSQL.DBTABLE ( `name` , `sex` , `age` ) VALUES ( '姓名1' , '性别1' , '年龄1' ) 
***************************************************/
function insertForm(){
	$args_num=func_num_args();
	$args_val=func_get_args();
	if($args_num==3){
		$dbname=$args_val[0].".";
		$table=$args_val[1];
		$array=$args_val[2];
	}elseif($args_num==2){
		$dbname='';
		$table=$args_val[0];
		$array=$args_val[1];
	}else{
		return false;	
	}
	$sql="INSERT INTO {$dbname}{$table} ( ";
	$i=0;
	$jjkey=$jjvalue='';
	foreach($array as $key=>$value){
		if(is_array($value)){
			foreach($value as $key2=>$value2){
				$do=" , ";
				if($i+1==count($value)){
					$do="),(";
					$i=-1;
				}
				if($key<1){
					$jjkey.="`{$key2}`".$do;	
				}
				$jjvalue.="'{$value2}'".$do;
				$i++;
				
			}
		}else{
			$do=" , ";
			if($i+1==count($array)){
				$do="";	
			}
			$jjkey.="`{$key}`".$do;
			$jjvalue.="'{$value}'".$do;
			$i++;
		}		
	}
	$jjkey=str_replace("),(","",$jjkey);
	$sql.= "{$jjkey} ) VALUES ( {$jjvalue} ) ";
	$sql=str_replace(",( )","",$sql);
	return $sql;
}
/***************************************************
*函数名: editValue
*作用: $array_id 唯一字段 字段=》指定数据,$array_value 数组 字段=》修改数据
*返回值:返回标准SQL;
*示例: editValue('DBSQL','DBTABLE',array('time'=>time()),array('id'=>123,'name'=>'aa'));
* UPDATE DBSQL.DBTABLE SET `time`='1204631133' WHERE `id`='123' AND `name`='aa'
*依附关系:必须存在串处理函数my_request();clear_sql();clear_htm();
***************************************************/
function editValue(){
	$args_num=func_num_args();
	$args_val=func_get_args();
	if($args_num==4){
		$dbname=$args_val[0].".";
		$table=$args_val[1];
		$array_value=$args_val[2];
		$array_id=$args_val[3];
	}elseif($args_num==3){		
		$table=$args_val[0];
		$array_value=$args_val[1];
		$array_id=$args_val[2];
	}else{
		return false;	
	}
	
	$set='';
	$i=0;
	foreach($array_value as $key=>$value){
		
			$do=",";
			if($i+1==count($array_value)){
				$do="";	
			}
			$set.="`{$key}`='{$value}'{$do}";
			$i++;
	}
	if(is_array($array_id)){
		$where=' WHERE ';
		$i=0;
		foreach($array_id as $key=>$value){
			
				$do=" AND ";
				if($i+1==count($array_id)){
					$do="";	
				}
				$where.="`{$key}`='{$value}'{$do}";
				$i++;
		}
	}
	$sql=" UPDATE {$dbname}{$table} SET {$set} {$where}";
	return $sql;
}
/***************************************************
*函数名: delValue
*作用: $array_id 唯一字段 字段=》指定数据,$array_value 数组 字段=》修改数据
*返回值:返回标准SQL;
*示例: delValue('DBSQL','DBTABLE',array('id'=>123,'name'=>'aa'));
* DELETE FROM  DBSQL.DBTABLE WHERE `id`='123' AND `name`='aa'
*依附关系:必须存在串处理函数my_request();clear_sql();clear_htm();
***************************************************/
function delValue(){
	$args_num=func_num_args();
	$args_val=func_get_args();
	if($args_num==3){
		$dbname=$args_val[0].".";
		$table=$args_val[1];
		$array_id=$args_val[2];
	}elseif($args_num==2){		
		$table=$args_val[0];
		$array_id=$args_val[1];
	}else{
		return false;	
	}
	
	$where='';
	$i=0;
	foreach($array_id as $key=>$value){
		
			$do=" AND ";
			if($i+1==count($array_id)){
				$do="";	
			}
			$where.="`{$key}`='{$value}'{$do}";
			$i++;
	}
	$sql=" DELETE FROM {$dbname}{$table} WHERE {$where}";
	return $sql;
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
//整理树形菜单
function fileter($var){return (($var));}
function ccdp($arr){
	if(!is_array($arr)) return NULL;
	for($i=0;$i<count($arr);$i++){
		for($j=0;$j<count($arr);$j++){
			if(empty($arr[$i]['id'])){break;}
			if($arr[$i]['id']==$arr[$j]['fid']){
				$arr[$i]['child'][]=$arr[$j];
				$arr[$j]=NULL;
			}
		}
		
	}
	$arr=array_filter($arr,"fileter");
	$arr=array_values($arr);
	for($i=0;$i<count($arr);$i++){
		if(!empty($arr[$i]['child'])){
			$arr_center=$arr[$i]['child'];
			$arr[$i]['child']=NULL;
			$arr_first=array_slice($arr,0,$i+1);
			$arr_after=array_slice($arr,$i+1);
			$arr=array_merge($arr_first,$arr_center,$arr_after);
		}
	}
	for($i=0;$i<count($arr);$i++){
		if(empty($arr[$i]['depth'])){
			$arr[$i]['depth']=1;
		}
		for($j=0;$j<count($arr);$j++){
			if($arr[$i]['id']==$arr[$j]['fid']){
				$arr[$j]['depth']=$arr[$i]['depth']+1;
			}
		}
	}
	return ($arr);
}