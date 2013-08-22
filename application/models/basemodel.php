<?php

class BaseModel extends CI_Model {
    protected $table_name = "";
    protected $obj;
    protected $is_debug;
    protected $enable_cache;

	function __construct() {	//{{{
		parent::__construct();
		$this->is_debug = false;
		$this->enable_cache = false;
	}	//}}}
	function getTableName() {	//{{{
		return $this->table_name;
	}	//}}}
	/**
	 * Get a record 
	 */
	function getById($id) {	//{{{
		
		$ret = false;
		
		if (empty($id)) {
			return false;
		}
		if ($this->is_debug) {
				log_message("error", "[BaseModel] getById ($id)");
		}
		
		$this->db->where('id', $id);	
//		echo "sql:".$this->db->last_query()."<br>";		
		$query = $this->db->get($this->table_name);
		
		if (!empty($query) && $query->num_rows() > 0) {	
			$ret = $query->row();		
		}
		
		return $ret;
	}	//}}}
	/**
	* 根据where条件获取select字段，并返回object
		例如：select username from member where email='zyb@163.com'
	*
	* @param string $select 查询的字段
	* @param string $where 查询条件 不能为空
	* @return object
	*/
	function getSelectRow($select = '*', $where = '') {	//{{{
		
		$ret = false;
	
		if ($this->is_debug) {
				log_message("error", "[BaseModel] getSelectRow ($select, $where)");
		}

		if (!empty($select)) {
			$this->db->select($select);
		}
		
		if (empty($where)) {
			return false;
		}
		
		//to add cache here
		$this->db->where($where, NULL, false);


		$query = $this->db->get($this->table_name);
		if (!empty($query) && $query->num_rows() > 0) {	
			$ret = $query->row();		
		}
		
		return $ret;
	}	//}}}
	/**
	* 根据where条件获取select字段，并返回字段的值
		例如：$id = $membermodel->getValueByField('email=test@qq.com', 'id');
	*
	* @param string $select 查询的字段
	* @param string $where 查询条件 不能为空
	* @return string
	*/
	function getValueByField($where, $select = "") {	//{{{
		if(empty($select) || empty($where)) 
		{
			return '';
		}
		$this->db->select($select);
		$this->db->where($where, NULL, false);
		$query = $this->db->get($this->table_name);
		$row = $query->row();
		if(!empty($row)) 
		{
			return $row->$select;
		}
	}	//}}}
	function getByIdForUpdate($id) {	//{{{
		if (empty($id)) {
			return false;
		}
		if ($this->is_debug) {
				log_message("error", "[BaseModel] getById ($id)");
		}
			
		//to add cache here
		
		$sql = "SELECT * FROM ".$this->table_name." WHERE id = ".addslashes($id)." FOR UPDATE";
		$query = $this->db->query($sql);
		if (!empty($query) && $query->num_rows() > 0) {
			return $query->row();
		}
		
		return false;
	}	//}}}
	function count($where="") {	//{{{
		$ret = 0;
		
		$where = trim($where);  	
		
		if (empty($where)) {
			$where = "1=1";
		}
		
		//to add cache here
		
		$this->db->where($where, NULL, false);

		$this->db->from($this->table_name);

		$ret = $this->db->count_all_results();
		
		return $ret;
	}	//}}}
	/**
	 * Get all record of specified condition
	 * 
	 * where - conditions
	 * offset - start
	 * perpage - perpage
	 * order_by - order by, e.g. 'title desc, name asc'
	 * distinct - select distinct *
	 */
	function getAll($where="", $offset=0, $perpage=0, $order_by = "", $distinct = false) {	//{{{

		$ret = array();
		
		$where = trim($where);
		
		if (empty($where)) {
			$where = "1=1";
		}
		
		//to add cache here
		
		$this->db->where($where, NULL, false);
		
		if ($perpage > 0) {
			$this->db->limit($perpage, $offset);
		}
		
		if (!empty($order_by)) {
			$this->db->order_by($order_by); 
		}
		
		if($distinct){
			$this->db->distinct();
		}
		$query = $this->db->get($this->table_name);
//		echo "sql:".$this->db->last_query()."<br>";		
		if (!empty($query) && $query->num_rows() > 0) {
			$ret = $query->result();
		}

		return $ret;
	}	//}}}
	/**
	 * Get all record of specified condition
	 * 
	 * select - files name
	 * where - conditions
	 * offset - start
	 * perpage - perpage
	 * order_by - order by, e.g. 'title desc, name asc'
	 * distinct - select distinct *
	 */
	function getSelectAll($select="",$where="", $offset=0, $perpage=0, $order_by = "", $distinct = false) {	//{{{

		$ret = array();
		
		$select = trim($select);
		$where = trim($where);
		
		if (!empty($select)) {
			$this->db->select($select);
		}
		
		if (empty($where)) {
			$where = "1=1";
		}
		
		//to add cache here
		$this->db->where($where, NULL, false);
		
		if ($perpage > 0) {
			$this->db->limit($perpage, $offset);
		}
		
		if (!empty($order_by)) {
			$this->db->order_by($order_by); 
		}
		
		if($distinct){
			$this->db->distinct();
		}
		$query = $this->db->get($this->table_name);
//		echo "sql:".$this->db->last_query()."<br>";	exit;				
		if (!empty($query) && $query->num_rows() > 0) {
			$ret = $query->result();
		}

		return $ret;
	}		//}}}
	function getFields($cols, $where="", $offset=0, $perpage=0, $order_by = "", $distinct = false) {	//{{{

		$ret = array();
		
		$where = trim($where);
		
		if (empty($where)) {
			$where = "1=1";
		}
		
		foreach($cols as $col){
			$this->db->select($col);
		}
		
		//to add cache here
		
		$this->db->where($where, NULL, false);
		
		if ($perpage > 0) {
			$this->db->limit($perpage, $offset);
		}
		
		if (!empty($order_by)) {
			$this->db->order_by($order_by); 
		}
		
		if($distinct){
			$this->db->distinct();
		}
		$query = $this->db->get($this->table_name);
				
		if (!empty($query) && $query->num_rows() > 0) {
			$ret = $query->result();
		}
		
		return $ret;
	}		//}}}
	function _log($msg) {	//{{{
		if ($this->is_debug) {
			log_message("debug", $msg);
		}
	}	//}}}
	/**
	 * Add a record without checking id exists, 
	 * For example, it's for auto_increment which mainly happens in global config
	 */
	function add($obj) {	//{{{
		
		//to add cache here
		
		$this->_log($this->table_name.": insert");

		$this->db->insert($this->table_name, $obj);

		$this->_log($this->table_name.": ".$this->db->last_query());
		
		if($this->db->affected_rows() > 0) {
			if (is_array($obj)) {
				$obj['id'] = $this->db->insert_id();
			}else{
				$obj->id = $this->db->insert_id();
			}
			
			if($this->enable_cache)
			{
				$this->cleanAllCache();
			}
							
			return $obj;
		} else {
			$this->_log($this->table_name.": insert return false");
			return false;
		}
	}	//}}}
	/**
	 * Delete a record 
	 */
	function delete($id) {	//{{{
		if (empty($id)) {
			return false;
		}
		
		//to add cache here
		$this->db->where('id', $id);
		$this->db->delete($this->table_name);
		
		if($this->db->affected_rows() > 0) {
			if($this->enable_cache)
			{
				$this->cleanAllCache();
			}
			
			return true;
		} else {
			return false;
		}
	}	//}}}
	function deleteByWhere($where) {	//{{{
		if (empty($where)) {
			return false;
		}
		
		//to add cache here
		$this->db->where($where, NULL, false);
		$this->db->delete($this->table_name);
		
		if($this->db->affected_rows() > 0) {
			if($this->enable_cache)
			{
				$this->cleanAllCache();
			}
			return true;
		} else {
			return false;
		}
	}		//}}}
	
	/**
	 * Update a record 
	 */
	function update($id, $obj) {	//{{{
		if (empty($id) || empty($obj)) {
			return false;
		}
		
		$this->db->where('id', $id);

		$this->db->update($this->table_name, $obj);
		
		if($this->db->affected_rows() > 0) {
			if($this->enable_cache)
			{
				$this->cleanAllCache();
			}
			return true;
		} else {
			//note: it goes here if update something and the value doesn't actually change
			return false;
		}
	}	//}}}
	/**
	 * Update a set of records 
	 */
	function updateByWhere($where, $obj) {	//{{{
		if (empty($where) || empty($obj)) {
			return false;
		}

		$this->db->where($where, NULL, false);
		$this->db->update($this->table_name, $obj);
		
//		echo "sql:".$this->db->last_query()."<br>";
		
		if($this->db->affected_rows() > 0) {
			if($this->enable_cache)
			{
				$this->cleanAllCache();
			}
			return true;
		} else {
			//note: it goes here if update something and the value doesn't actually change
			return false;
		}
	}		//}}}
	function getJoin($select='',$where='',$tablename,$joinkey,$jointype=''){	//{{{

		
		$ret = array();
		
		$select = trim($select);
		$where = trim($where);
		
		if (empty($tablename)) {
			return false;
		}
		
		if (!empty($select)) {
			$this->db->select($select);
		}
		
		if (empty($jointype)) {
			$jointype = "LEFT";
		}
		
		if (empty($where)) {
			$where = "1=1";
		}
		
		$this->db->join($tablename,$joinkey,$jointype);
		//to add cache here
		$this->db->where($where, NULL, false);

		$query = $this->db->get($this->table_name);			
		
		if (!empty($query) && $query->num_rows() > 0) {
			$ret = $query->result();
		}

		return $ret;		
	}	//}}}
	function executeQuery($sql) {	//{{{
		if (empty($sql)) {
			return array();
		}
		
		$query = $this->db->query($sql);
	
		if (!empty($query) && $query->num_rows() >0 ) {
			
			if($this->db->is_write_type($sql))
			{
				if($this->enable_cache)
				{
					$this->cleanAllCache();
				}
			}
			
			return $query->result();
		}
		
		return array();
	}	//}}}
	function execute($sql) {	//{{{
		if (empty($sql)) {
			return;
		}
		
		$this->db->query($sql);

		if($this->db->is_write_type($sql))
		{
			if($this->enable_cache)
			{
				$this->cleanAllCache();
			}
		}
		
		return;
	}	//}}}
}
?>
