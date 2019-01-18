<?php
/**
 * ���ݿ����--���Ű�v2.0   
 *  by vking goto999@126.com  
 *  2010-02-12   
 *  �򵥽��ܣ���Ҫ�ŵ�򻯲�ѯ������ʹ�䷵����Ҫ���ֶ�   
 *  $db=new dbsql($host,$dbuser,$dbpass,$dbname);   
 *  ���������ʾ $db->set_error_info(true);Ĭ��������ʾ   
 *  ������������ $db->set_connect_type(true);Ĭ��Ϊconnect
 *  ���÷������� $db->set_results_type("assoc");Ĭ��assocΪ����,����Ϊobject�����ض���   
 *  ��ȡ������Ϣ $db->get_info();   
 *  �л����ݿ� $db->select_db($dbname);
 *  ��ȡһ����¼ $db->dosql($sql,0);   
 *  ��ȡһ�м�¼ $db->dosql($sql,1);
 *  ��ȡ��ѯ��¼����  $db->dosql($sql,"NUM_ROWS");   
 *  ��ȡһ�м�¼ $db->dosql($sql,"tagname");tagnameҪ��ȡ���ֶ�����   
 *  ��ȡһ���¼�����ض�Ψ��������$db->dosql($sql);   
 *  ��ȡ���ݱ��ֶ� ������һά���飩$db->dosql($tablename); 
 */
class dbsql{       
	private $_link;       
	private $_dbserver;       
	private $_dbuser;       
	private $_dbpass;       
	private $_dbname;       
	private $_error=true;//������       
	private $_connect_type=false;//false=>connect       
	private $_argsnum;       
	private $_res;
	private $_retult_type;//Ĭ��assoc�������� object=>����obj����      
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
		1005=>'������ʧ��',
		1006=>'�������ݿ�ʧ��',
		1007=>'���ݿ��Ѵ��ڣ��������ݿ�ʧ��',
		1008=>'���ݿⲻ���ڣ�ɾ�����ݿ�ʧ��',
		1009=>'����ɾ�����ݿ��ļ�����ɾ�����ݿ�ʧ��',
		1010=>'����ɾ������Ŀ¼����ɾ�����ݿ�ʧ��',
		1011=>'ɾ�����ݿ��ļ�ʧ��',
		1012=>'���ܶ�ȡϵͳ���еļ�¼',
		1020=>'��¼�ѱ������û��޸�',
		1021=>'Ӳ��ʣ��ռ䲻�㣬��Ӵ�Ӳ�̿��ÿռ�',
		1022=>'�ؼ����ظ������ļ�¼ʧ��',
		1023=>'�ر�ʱ��������',
		1024=>'���ļ�����',
		1025=>'��������ʱ��������',
		1026=>'д�ļ�����',
		1032=>'��¼������',
		1036=>'���ݱ���ֻ���ģ����ܶ��������޸�',
		1037=>'ϵͳ�ڴ治�㣬���������ݿ������������',
		1038=>'����������ڴ治�㣬���������򻺳���',
		1040=>'�ѵ������ݿ���������������Ӵ����ݿ����������',
		1041=>'ϵͳ�ڴ治��',
		1042=>'��Ч��������',
		1043=>'��Ч����',
		1044=>'��ǰ�û�û�з������ݿ��Ȩ��',
		1045=>'�����������ݿ⣬�û������������',
		1046=>'���ݿⲻ����',
		1048=>'�ֶβ���Ϊ��',
		1049=>'���ݿⲻ����',
		1050=>'���ݱ��Ѵ���',
		1051=>'���ݱ�����',
		1054=>'�ֶβ�����',
		1065=>'��Ч��SQL��䣬SQL���Ϊ��',
		1081=>'���ܽ���Socket����',
		1114=>'���ݱ����������������κμ�¼',
		1116=>'�򿪵����ݱ�̫��',
		1129=>'���ݿ�����쳣�����������ݿ�',
		1130=>'�������ݿ�ʧ�ܣ�û���������ݿ��Ȩ��',
		1133=>'���ݿ��û�������',
		1141=>'��ǰ�û���Ȩ�������ݿ�',
		1142=>'��ǰ�û���Ȩ�������ݱ�',
		1143=>'��ǰ�û���Ȩ�������ݱ��е��ֶ�',
		1146=>'���ݱ�����',
		1147=>'δ�����û������ݱ�ķ���Ȩ��',
		1149=>'SQL����﷨����',
		1158=>'������󣬳��ֶ�����������������״��',
		1159=>'������󣬶���ʱ��������������״��',
		1160=>'������󣬳���д����������������״��',
		1161=>'�������д��ʱ��������������״��',
		1062=>'�ֶ�ֵ�ظ������ʧ��',
		1064=>'SQL������',
		1169=>'�ֶ�ֵ�ظ������¼�¼ʧ��',
		1177=>'�����ݱ�ʧ��',
		1180=>'�ύ����ʧ��',
		1181=>'�ع�����ʧ��',
		1203=>'��ǰ�û������ݿ⽨���������ѵ������ݿ���������������������õ����ݿ����������������ݿ�',
		1205=>'������ʱ',
		1211=>'��ǰ�û�û�д����û���Ȩ��',
		1216=>'���Լ�����ʧ�ܣ������ӱ��¼ʧ��',
		1217=>'���Լ�����ʧ�ܣ�ɾ�����޸������¼ʧ��',
		1226=>'��ǰ�û�ʹ�õ���Դ�ѳ������������Դ�����������ݿ������������',
		1227=>'Ȩ�޲��㣬����Ȩ���д˲���',
		1235=>'MySQL�汾���ͣ������б�����'          
		);
		echo "<font color=red>����˿ڣ�".mysql_errno($this->_link)."---".$err[mysql_errno($this->_link)]."<br>";
		echo "������Ϣ��".mysql_error($this->_link)."<br>";
		if(isset($args[0])){
			echo "�������:".$args[0]."<br></font>";
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