<?php
/**
 * 数据库操作--无优版v2.0   
 *  by vking goto999@126.com  
 *  2010-02-12   
 *  简单介绍：主要优点简化查询操作，使其返回需要的字段   
 *  $db=new dbsql($host,$dbuser,$dbpass,$dbname);   
 *  允许错误提示 $db->set_error_info(true);默认允许提示   
 *  建立持续连接 $db->set_connect_type(true);默认为connect
 *  设置返回类型 $db->set_results_type("assoc");默认assoc为数组,设置为object将返回对象   
 *  获取连接信息 $db->get_info();   
 *  切换数据库 $db->select_db($dbname);
 *  获取一个记录 $db->dosql($sql,0);   
 *  获取一行记录 $db->dosql($sql,1);
 *  获取查询记录行数  $db->dosql($sql,"NUM_ROWS");   
 *  获取一列记录 $db->dosql($sql,"tagname");tagname要获取的字段名称   
 *  获取一组记录（返回二唯数组或对象）$db->dosql($sql);   
 *  获取数据表字段 （返回一维数组）$db->dosql($tablename); 
 */
class dbsql{       
	private $_link;       
	private $_dbserver;       
	private $_dbuser;       
	private $_dbpass;       
	private $_dbname;       
	private $_error=true;//允许报错       
	private $_connect_type=false;//false=>connect       
	private $_argsnum;       
	private $_res;
	private $_retult_type;//默认assoc返回数组 object=>返回obj对象      
	function __construct(){
		$this->_result_type="assoc";
		$this->_argsnum=func_num_args();
		$args=func_get_args();
		switch ($this->_argsnum){
		case 0 :
			if(!$this->_link=mysql_connect()){
				$this->error();
			}
		break;
		case 1 :
			$this->_dbserver=$args[0];
			if(!$this->_link=mysql_connect($this->_dbserver)){
 				$this->error();
			}
		break;
		case 2 :
			$this->_dbserver=$args[0];
			$this->_dbuser=$args[1];
			if(!$this->_link=mysql_connect($this->_dbserver,$this->_dbuser)){
				$this->error();
			}
		break;
		case 3 :
			$this->_dbserver=$args[0];
			$this->_dbuser=$args[1];
			$this->_dbpass=$args[2];
			if(!$this->_link=mysql_connect($this->_dbserver,$this->_dbuser,$this->_dbpass)){
 				$this->error();
			}
		break;
		default:
			$this->_dbserver=$args[0];
			$this->_dbuser=$args[1];
			$this->_dbpass=$args[2];
			$this->_dbname=$args[3];
			if($this->_connect_type){
    			if(!$this->_link=mysql_pconnect($this->_dbserver,$this->_dbuser,$this->_dbpass)){
        			$this->error();
    			}
			}else{
    			if(!$this->_link=mysql_connect($this->_dbserver,$this->_dbuser,$this->_dbpass)){
        			$this->error();
    			}
			}
			mysql_select_db($this->_dbname,$this->_link);
		}
	}
	function select_db(){
		$args=func_get_args();
		if(isset($args[1])){
			if(!mysql_select_db($args[0],$args[1])){
				$this->error();
			}
		}else{
			if(!mysql_select_db($args[0])){
				$this->error();
  			}
  		}
  	}
  	function set_connect_type(){
		$args=func_get_args();
		$this->_connect_type=$args[0];       
	}       
	function set_error_info(){
		$args=func_get_args();
		$this->_error=$args[0];       
	}
	function set_result_type(){
		$args=func_get_args();
		$this->_result_type=$args[0];
	}       
	function error(){
		if(!$this->_error){
			return 0;
		}
		$args=func_get_args();
		$err=array(
		1005=>'创建表失败',
		1006=>'创建数据库失败',
		1007=>'数据库已存在，创建数据库失败',
		1008=>'数据库不存在，删除数据库失败',
		1009=>'不能删除数据库文件导致删除数据库失败',
		1010=>'不能删除数据目录导致删除数据库失败',
		1011=>'删除数据库文件失败',
		1012=>'不能读取系统表中的记录',
		1020=>'记录已被其他用户修改',
		1021=>'硬盘剩余空间不足，请加大硬盘可用空间',
		1022=>'关键字重复，更改记录失败',
		1023=>'关闭时发生错误',
		1024=>'读文件错误',
		1025=>'更改名字时发生错误',
		1026=>'写文件错误',
		1032=>'记录不存在',
		1036=>'数据表是只读的，不能对它进行修改',
		1037=>'系统内存不足，请重启数据库或重启服务器',
		1038=>'用于排序的内存不足，请增大排序缓冲区',
		1040=>'已到达数据库的最大连接数，请加大数据库可用连接数',
		1041=>'系统内存不足',
		1042=>'无效的主机名',
		1043=>'无效连接',
		1044=>'当前用户没有访问数据库的权限',
		1045=>'不能连接数据库，用户名或密码错误',
		1046=>'数据库不存在',
		1048=>'字段不能为空',
		1049=>'数据库不存在',
		1050=>'数据表已存在',
		1051=>'数据表不存在',
		1054=>'字段不存在',
		1065=>'无效的SQL语句，SQL语句为空',
		1081=>'不能建立Socket连接',
		1114=>'数据表已满，不能容纳任何记录',
		1116=>'打开的数据表太多',
		1129=>'数据库出现异常，请重启数据库',
		1130=>'连接数据库失败，没有连接数据库的权限',
		1133=>'数据库用户不存在',
		1141=>'当前用户无权访问数据库',
		1142=>'当前用户无权访问数据表',
		1143=>'当前用户无权访问数据表中的字段',
		1146=>'数据表不存在',
		1147=>'未定义用户对数据表的访问权限',
		1149=>'SQL语句语法错误',
		1158=>'网络错误，出现读错误，请检查网络连接状况',
		1159=>'网络错误，读超时，请检查网络连接状况',
		1160=>'网络错误，出现写错误，请检查网络连接状况',
		1161=>'网络错误，写超时，请检查网络连接状况',
		1062=>'字段值重复，入库失败',
		1064=>'SQL语句错误',
		1169=>'字段值重复，更新记录失败',
		1177=>'打开数据表失败',
		1180=>'提交事务失败',
		1181=>'回滚事务失败',
		1203=>'当前用户和数据库建立的连接已到达数据库的最大连接数，请增大可用的数据库连接数或重启数据库',
		1205=>'加锁超时',
		1211=>'当前用户没有创建用户的权限',
		1216=>'外键约束检查失败，更新子表记录失败',
		1217=>'外键约束检查失败，删除或修改主表记录失败',
		1226=>'当前用户使用的资源已超过所允许的资源，请重启数据库或重启服务器',
		1227=>'权限不足，您无权进行此操作',
		1235=>'MySQL版本过低，不具有本功能'          
		);
		echo "<font color=red>错误端口：".mysql_errno($this->_link)."---".$err[mysql_errno($this->_link)]."<br>";
		echo "错误信息：".mysql_error($this->_link)."<br>";
		if(isset($args[0])){
			echo "出错语句:".$args[0]."<br></font>";
		}       
	}       
	function query($sql){
		if(!$this->_res=mysql_query($sql,$this->_link)){
			$this->error($sql);
		}       
	}       
	function getRowsNum(){
		if(@func_get_arg(0)){
			$this->query(func_get_arg(0));
		}
   		return @mysql_num_rows($this->_res);        
	}
	function is_sql(){
		$sql=trim(func_get_arg(0));
		if(eregi(" ",$sql) && strlen($sql)>10){
			return true;
		}
		return false;
	}       
	function dosql(){
		$args=func_get_args();
		if($this->is_sql($args[0])){
			$this->query($args[0]);
		}else{
			$fields=@mysql_list_fields($this->_dbname,$args[0],$this->_link);
			if(!$columns = @mysql_num_fields($fields)){
				$this->error($sql);
			}else{
				for ($i = 0; $i < $columns; $i++) {
	    			$fields_info[]=@mysql_field_name($fields, $i);
				} 
				return $fields_info;
			}
		}
		if(eregi("select|show",substr($args[0],0,10))){
 			switch (count($args)){
			case 2:
    			if($args[1]==="NUM_ROWS"){
    				return $this->getRowsNum();
    			}
    			if($args[1]===0){
    				return mysql_result($this->_res,0,0);
    			}
    			if($args[1]===1){
    				$do="mysql_fetch_{$this->_result_type}";
					return @$do($this->_res);
    			}
				if(is_string($args[1])){
					$num=$this->getRowsNum();
					for($i=0;$i<$num;$i++){
						$arr[$i]=@mysql_result($this->_res,$i,$args[1]);
        			}
        			return $arr;
				}		
			default:
    			$do="mysql_fetch_{$this->_result_type}";
				while($xx=@$do($this->_res)){
        			$row[]=$xx;
    			}
    			@mysql_free_result($this->_res);
        		return empty($row)?null:$row;
  			}
		}else{//query
			if(eregi("insert",substr($args[0],0,10))){
				return mysql_insert_id($this->_link);
			}else{
				return mysql_affected_rows($this->_link);
			}
  		}       
	}       
	function getinfo(){
		$arr['Author']="vking";
		$arr['Email']="goto999@126.com";
		$arr['Version']="2.0.0";
		$arr['DevelopDate']="2012-02-12";
		$arr['HOST']=mysql_get_host_info();
		$arr['SERVER']=mysql_get_server_info();
		$arr['CLIENT']=mysql_get_client_info();
		$arr['PROTO']=mysql_get_proto_info($this->_link);         
		$res=$this->dosql("show variables like 'character\_set\_%'");
		for($i=0;$i<count($res);$i++){
			$p[$res[$i]['Variable_name']]=$res[$i]['Value'];
		}
		$arr['CharSet']=$p;
		return $arr;       
	}       
	function __destruct(){
		@mysql_close($this->_link);       
	}   
}   
?>