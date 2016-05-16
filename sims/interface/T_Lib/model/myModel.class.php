<?php
/* 基类
 * 
 */
class myModel{
	/*属性*/
	public $_mydb;//数据库实例
	/*接口*/	
	//构造函数
	public function __construct(){
		$this -> _mydb = new DbManager();
		//$this -> _mydb2 = new DbManager(array('host'=>'192.168.106.35','db'=>'nbfet2009'));	
	}
	/*	得到信息列表
	 * 
	 * @prama $limit = array('offset'=>'','numbers'=>'');空就不限制
	 */
	public function get_list($select,$table,$where='',$order=array(),$limit=array(),$orwhere=array()){
		$sql_where = '';
		$sql_order = '';
		$sql_limit = '';
		$sql_orwhere = '';
		if(!empty($where)){
			if(is_array($where)){
				foreach($where as $key => $value){
					$str = '';
					if(strpos($key,'>') === FALSE && strpos($key,'<') === FALSE && strpos($key,'like') === FALSE){
						$str = "$key = '$value'";
					}else{
						$str = "$key '$value'";
					}
					if(strpos($sql_where,'where') === FALSE){
						
						$sql_where = " where $str";
					}else{
						$sql_where .= " and $str";
					}
				}
			}else{
				$sql_where = $where;	
			}
		}
		if(!empty($order)){
			foreach($order as $k => $v){
				if(strpos($sql_order,'order') === FALSE){
					$sql_order = " order by $k $v";
				}else{
					$sql_order .= " ,$k $v";
				}
			}
		}
		if(!empty($limit)){
			$sql_limit = ' limit '.$limit['offset'].','.$limit['numbers'];
		}
		$sql_where = $sql_where.$sql_order.$sql_limit;
		//echo $sql_where.'<br>';
		return $this -> _mydb->db_getList($select,$table,$sql_where);
		
	}
	/*	得到信息列表
	 * 
	 * @prama $sql sql语句
	 */
	public function get_list_bysql($sql){
		return $this -> _mydb->db_getList_bysql($sql);
	}
	/* 根据条件，得到一条信息
	 * 
	 * 
	 * */
	public function get_one($select,$table,$where=array(),$order=array(),$limit=array()){
		$sql_where = '';
		$sql_order = '';
		$sql_limit = '';
		if(!empty($where)){
			if(is_array($where)){
				foreach($where as $key => $value){
					$str = '';
					if(strpos($key,'>') === FALSE && strpos($key,'<') === FALSE && strpos($key,'like') === FALSE){
						$str = "$key = '$value'";
					}else{
						$str = "$key '$value'";
					}
					if(strpos($sql_where,'where') === FALSE){
						
						$sql_where = " where $str";
					}else{
						$sql_where .= " and $str";
					}
				}
			}else{
				$sql_where = $where;	
			}
		}
		if(!empty($order)){
			foreach($order as $k => $v){
				if(strpos($sql_order,'order') === FALSE){
					$sql_order = " order by $k $v";
				}else{
					$sql_order .= " ,$k $v";
				}
			}
		}
		if(!empty($limit)){
			$sql_limit = ' limit '.$limit['offset'].','.$limit['numbers'];
		}
		$sql_limit = ' limit 1';
		$sql_where = $sql_where.$sql_order.$sql_limit;
		//echo $sql_where.'<br>';
		return $this -> _mydb->db_getOne($select,$table,$sql_where);
	}
	/*
	 * 新增
	 * */
	public function do_insert($data,$table){
		return $this -> _mydb -> db_insert($data,$table);
	}
	/*
	 * @param 表名: $table
	 * @param 条件: $where
	 * @param 是否需要前缀：$is_pre
	 * @return bool
	 */
	public function do_delete($table,$where,$is_pre = 1){
		$sql_where = '';
		if(!empty($where)){
			if(is_array($where)){
				foreach($where as $key => $value){
					$str = '';
					if(strpos($key,'>') === FALSE && strpos($key,'<') === FALSE && strpos($key,'like') === FALSE){
						$str = "$key = '$value'";
					}else{
						$str = "$key '$value'";
					}
					if(strpos($sql_where,'where') === FALSE){
						
						$sql_where = " where $str";
					}else{
						$sql_where .= " and $str";
					}
				}
			}else{
				$sql_where = $where;	
			}
		}
		return $this ->_mydb -> db_delete($table,$sql_where,$is_pre);
	}
	/* 修改
	 * 
	 * */
	public function do_update($data,$table,$where){
		$sql_where = '';
		if(!empty($where)){
			foreach($where as $key => $value){
				$str = '';
				if(strpos($key,'>') === FALSE && strpos($key,'<') === FALSE){
					$str = "$key = '$value'";
				}else{
					$str = "$key '$value'";
				}
				if(strpos($sql_where,'where') === FALSE){
					
					$sql_where = " where $str";
				}else{
					$sql_where .= " and $str";
				}
			}
		}
		return $this -> _mydb -> db_update($data,$table,$sql_where);
	}
	/*
	 * 得到数据条数
	 * */
	public function get_count($select,$table,$where=''){
		$sql_where = '';
		if(!empty($where)){
			if(is_array($where)){
				foreach($where as $key => $value){
					$str = '';
					if(strpos($key,'>') === FALSE && strpos($key,'<') === FALSE && strpos($key,'like') === FALSE){
						$str = "$key = '$value'";
					}else{
						$str = "$key '$value'";
					}
					if(strpos($sql_where,'where') === FALSE){
						
						$sql_where = " where $str";
					}else{
						$sql_where .= " and $str";
					}
				}
			}else{
				$sql_where = $where;	
			}
		}
		if(is_array($select)){
			foreach ($select as $v) {
				$sql .= "`".$v."`,";
			}
		}else{
			$sql = $select;	
		}
		//$sql = substr($sql,0,-1);
		$sql = "SELECT $sql FROM ".DB_PRE."$table $sql_where";
		//echo $sql;
		return $this->_mydb->db_count($sql);
	}
}
?>
