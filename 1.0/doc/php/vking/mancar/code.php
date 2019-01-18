<?

function getmaninfo($code){
	$mycode=array();//存放code信息
	
	$maninfo=array();//用户信息
	if(strlen($code)!=18 && strlen($code)!=16){
		return false;	
	}
	if(strlen($code)==16){
		$code=substr($code,0,6)."19".substr($code,6);
	}
	$mycode['p_code']=substr($code,0,6);//区位码
	$mycode['b_code']=substr($code,6,8);//生日码
	$mycode['o_code']=substr($code,14,3);//顺序码
	$mycode['t_code']=substr($code,17);//校验码
	$f=getf($code);
	if($mycode['t_code']!=$f){
		return false;	
	}
	$maninfo['出生地']=getcodeinfo($mycode['p_code']);
	$maninfo['出生日期']=$mycode['b_code'];
	$maninfo['性别']=($mycode['o_code']%2==0)?'女':'男';
	print_r($maninfo);
	return true; 
}
function getf($code){//获取权位
	$s=explode(" ","7 9 10 5 8 4 2 1 6 3 7 9 10 5 8 4 2");//加权因子
	$f=explode(" ","1 0 X 9 8 7 6 5 4 3 2");//对应效检码
	for($i=0;$i<17;$i++){
		$ts+=$s[$i]*substr($code,$i,1);	
	}
	$ts=$ts%11;
	return $f[$ts];
}
function getcodeinfo($val){
	include_once "chinacode.php";
	if($val>0){
		$p=substr($val,0,2)."0000";
		$c=substr($val,0,4)."00";
		$x=$val;
		return "{$chinacode[$p]}-{$chinacode[$c]}-{$chinacode[$x]}";	
	}else{
		$info=explode("-",$val);
		$p=preg_grep("/$info[0]/", &$chinacode);
		list($key) = each($p); 
		$val=$chinacode[$key]."-";
		$c=preg_grep("/$info[1]/", &$chinacode);
		$c=getonlynum($key,$c,1000);
		$val.=$chinacode[$c]."-";
		if(!$c){
			return false;
		}else{
			$x=preg_grep("/$info[2]/", &$chinacode);
			$x=getonlynum($c,$x,100);
			$val.=$chinacode[$x];
			return $x;	
			
		}
	}
		
}
function getonlynum($num,$array,$max){
	for (reset($array); list($key) = each($array);) {
		if($key-$num<$max && $key!=$num){
			return $key;	
		}
	}
	return false;
}
function createcid($city="",$birthday=0,$sex=2,$num=10){
	$birthday=($birthday)?$birthday:date("Ymd",time()+8*3600);
	$newcode=getcodeinfo(&$city);	
	if($newcode){
		echo "==========随机生成10个[$city]公民CID=============\r\n";
		$c=rand(0,980);
		$step=1;
		if($sex==1){
			$c=($c%2==0)?($c+1):$c;
			$step=2;
		}elseif($sex==0){
			$c=($c%2!=0)?($c+1):$c;
			$step=2;
		}
		for($i=0;$i<10;$i++){
			$c=str_pad($c,3,"0",STR_PAD_LEFT);
			$newcc=$newcode.$birthday.$c;
			echo (($c%2==0)?"gril:":"boy :").$newcc.getf($newcc)."\r\n";
			$c+=$step;	
		}
		return false;	
	}else{
		echo "地区名称错误或不存在\r\n";
		return false;
	}
	
}
//$newcode=createcid("陕西-咸阳-三原",19840214,1);
$code='610422198402142918';
getmaninfo($code);

?>