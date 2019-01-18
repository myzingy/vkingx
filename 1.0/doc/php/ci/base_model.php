<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * BackendPro
 *
 * A website backend system for developers for PHP 4.3.2 or newer
 *
 * @package         BackendPro
 * @author          Adam Price
 * @copyright       Copyright (c) 2008
 * @license         http://www.gnu.org/licenses/lgpl.html
 * @link            http://www.kaydoo.co.uk/projects/backendpro
 * @filesource
 */

// ---------------------------------------------------------------------------

/**
 * Base_model
 *
 * Sets up basic model functions. All user created model classes should
 * extend this to gain access to its basic database model functions.
 *
 * @package			BackendPro
 * @subpackage		Models
 */
class Base_model extends Model
{
	function Base_model()
	{
		parent::Model();

		// Create empty function array
		$this->_TABLES = array();
		require_once "BaeLog.class.php";
		$this->log=BaeLog::getInstance();
		log_message('debug','BackendPro : Base_model class loaded');
	}
	function console($msg,$type='logDebug'){
		if(in_array($type,array('logWrite','logTrace','logNotice','logDebug','logWarning','logFatal'))){
			$this->log->$type($msg);
		}
	}
	/**
	 * Fetch
	 *
	 * Fetch table rows from table related to $name. Check no custom
	 * fetch method exists before hand.
	 *
	 * @access public
	 * @param string $name Table Name
	 * @param mixed $fields Fields to return from table
	 * @param array $limit Rows to limit search to
	 * @param mixed $where Return rows that match this
	 * @return Query Object
	 */
	function fetch($name, $fields=null, $limit=null, $where=null,$order=null)
	{
		$func = '_fetch_'.$name;
		if(method_exists($this,$func))
		{
			// There is an overide function
			return call_user_func_array(array($this,$func), array($fields,$limit,$where));
		}
		else
		{
			// No override function, procede with fetch
			($fields!=null) ? $this->db->select($fields) : '';
			($where!=null) ? $this->db->where($where) : '';
			($limit!=null) ? $this->db->limit($limit['rows'],$limit['offset']) : '';
			($order!=null) ? $this->db->order_by($order[0],$order[1]) : '';
			return $this->db->get($this->_TABLES[$name]);
		}
	}

	/**
	 * Insert
	 *
	 * Insert new table data into table related to by $name
	 * Check no custom insert method exists before hand.
	 *
	 * @access public
	 * @param string $name Table Name
	 * @param array $data Data to insert
	 * @return Query Object
	 */
	function insert($name, $data)
	{
		$func = '_insert_' . $name;
		if(method_exists($this,$func))
		{
			// There is an overide function
			return call_user_func_array(array($this,$func), array($data));
		}
		else
		{
			// No override function, procede with insert
			$this->db->insert($this->_TABLES[$name],$data);
			return $this->db->insert_id();
		}
	}

	/**
	 * Update
	 *
	 * Update data in table related to by $name
	 * Check no custom update method exists before hand.
	 *
	 * @access public
	 * @param string $name Table Name
	 * @param array $values Data to change
	 * @param mixed $where Rows to update
	 * @return Query Object
	 */
	function update($name, $values, $where)
	{
		$func = '_update_' . $name;
		if(method_exists($this,$func))
		{
			// There is an overide function
			return call_user_func_array(array($this,$func), array($values,$where));
		}
		else
		{
			// No overside function, procede with general insert
			$this->db->where($where);
			$this->db->update($this->_TABLES[$name],$values);
			return $this->db->affected_rows();
		}
	}

	/**
	 * Delete
	 *
	 * Delete rows from table related to by $name
	 * Check no custom delete method exists before hand.
	 *
	 * @access public
	 * @param string $name Table Name
	 * @param mixed $where Rows to delete
	 * @return Query Object
	 */
	function delete($name, $where)
	{
		$func = '_delete_' . $name;
		if(method_exists($this, $func))
		{
			// There is an overide function
			return call_user_func_array(array($this,$func), array($where));
		}
		else
		{
			// No overside function, procede with general insert
			$this->db->where($where);
			return $this->db->delete($this->_TABLES[$name]);
		}
	}
	/**
	*auto pagination
	* fields   	and
				ae- and equal
				ol- or like
				
	*/
	function autowhere($fields){
		$url_param=$this->uri->uri_string();
		preg_match_all("/([a|o][eoligt]{1,2}-[a-z\.]+)\/([^\/]+)/i",$url_param,$url_xxx);
		if(!$url_xxx) return;
		$url_param=$url_xxx[1];
		$url_value=$url_xxx[2];
		$where='';
		foreach($url_value as $k=>$v){
			if(!$v){continue;}
			if(strpos($v,'%')!==false){
				$v=urldecode($v);
			}
			$key=$url_param[$k];
			list($a,$b)=explode('-',$key);
			if(!in_array($b,$fields)){continue;}
			switch($a){
				case "ae":
					$this->db->where($b,$v);
					$where.="\$this->db->where('$b',$v);";
				break;
				case "oe":
					$this->db->or_where($b,$v);
					$where.="\$this->db->or_where('$b',$v);";
				break;
				case "al":
					$this->db->like($b,$v);
					$where.="\$this->db->like('$b','{$v}');";
				break;
				case "ol":
					$this->db->or_like($b,$v);
					$where.="\$this->db->or_like('$b','{$v}');";
				break;
				case "ai":
					$v=explode(",",$v);
					$this->db->where_in($b,$v);
					$v=var_export($v,true);
					$where.="\$this->db->where_in('$b',$v);";
				break;
				case "agt":
					$this->db->where("$b >",$v);
					$where.="\$this->db->where('$b >',$v);";
				break;
				case "alt":
					$this->db->where("$b <",$v);
					$where.="\$this->db->where('$b <',$v);";
				break;
				case "oo":
					$this->db->order_by($b,$v);
					$where.="\$this->db->order_by('$b',$v);";
				break;
			}
		}
		return empty($where)?NULL:$where;
	}
	/**
	*auto pagination
	*/
	function autopage($datanum,$per_page=20){
		$url_param=$this->uri->uri_string();
		preg_match("/(\/page)\/([^\/]*)/",$url_param,$page);
		$this->page=0;
		if($page){
			if($page[2]>0){
				$this->page=$page[2];
			}
			$url_param=str_replace($page[0],"",$url_param);
		}
		//�����ַ�urlencode
		$url_param=preg_replace_callback("/[^a-z0-9-_,\/]/i",create_function(
		 	'$matches',
        	'return urlencode($matches[0]);'
        ),$url_param);
		if(!ereg("/[^/]+/",$url_param)){//�Զ���index
			$url_param.="/index";
		}
		$this->load->library('pagination');
		$config['base_url'] = base_url()."/index.php/{$url_param}/page";
		$config['total_rows'] = $datanum;
		$config['per_page'] = $per_page;
		$config['cur_page'] = $this->page;
		$this->pagination->initialize($config);
		return $this->pagination->create_links();
	}
}
/* End of file base_model.php */
/* Location: ./system/application/models/base_model.php */