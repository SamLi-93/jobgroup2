<?php
class DbManager extends Mysql {
	/**
	 * @var Mysql
	 */
	private $Mysql;
	
	function __construct($config = array()){
		$this -> Mysql = new Mysql($config);
	}
	
	/**
	 * Enter description here...
	 *
	 * @param 字段[一维数组] $select
	 * @param 表名 $table
	 * @param 条件 $where
	 * @return array
	 */
	public function db_getOne($select, $table, $where){
		$s = '';
		$sql = '';
		if(is_array($select)){
			$i = count($select);
			foreach ($select as $v) {
				$s .= "`".$v."`,";
			}
		}else{
			$s = $select;	
		}
		//$s = substr($s,0,-1);
		$sql = "SELECT {$s} FROM `".DB_PRE."{$table}` {$where}";
		return $this->Mysql->get_one($sql);
	}
	/**
	 * Enter description here...
	 *
	 * @param 字段[一维数组] $select
	 * @param 表名 $table
	 * @param 条件 $where
	 * @return array
	 */
	public function db_getList($select, $table, $where){
		$sql = '';
		if(is_array($select)){
			foreach ($select as $v) {
				$sql .= "`".$v."`,";
			}
		}else{
			$sql = $select;	
		}
		//$sql = substr($sql,0,-1);
		$sql = "SELECT {$sql} FROM `".DB_PRE."{$table}` {$where}";
		//echo $sql;
		$db = $this->Mysql->query($sql);
		if(!$db){
			//echo "<br>Error";
			return false;	
		}
		$rss = array();
		while ($rs = $this->Mysql->get_array($db)) {
			$rss[] = $rs;
		}
		/*$j=0;
		while($temp_result=$this->Mysql->get_array($db)){
			$i=0;
			foreach($temp_result as $key=>$value){
				$rss[$j][$key]=$value;
				$rss[$j][$i]=$value;
				$i++;
			}
			$j++;
		}
		
		if (!$rss) {
			echo "<br>error2";
			return false;
		}*/
		return $rss;
	}
	/**
	 * Enter description here...
	 *
	 * @param sql语句 $sql
	 * @return array
	 */
	public function db_getList_bysql($sql){
		//echo $sql;
		$db = $this->Mysql->query($sql);
		if(!$db){
			//echo "<br>Error";
			return false;	
		}
		$rss = array();
		while ($rs = $this->Mysql->get_array($db)) {
			$rss[] = $rs;
		}
		return $rss;
	}
	/*
	 * @sqlstr 条件语句
	 * 
	 */
	public function db_count($sqlstr){
		 return $this->Mysql->num_rows($sqlstr);
	}
	/**
	 * 更新数据
	 *
	 * @param 数组 $data
	 * @param 表名 $table
	 * @param 条件 $where
	 * @return bool
	 */
	public function db_update($data,$table='',$where=''){
		if($table=='' or $where==''){
 			return false;
 		}
 		//$where=' WHERE '.$where;
		$field='';
		if(is_string($data) && $data!=''){
			$field=$data;
		}elseif(is_array($data) && count($data)>0){
			foreach($data as $k=>$v){
				$field.="`".$k."`='".$v."',";
			}
			$field = substr($field,0,-1);
		}else{
			return false;
		}
		$sql="UPDATE `".DB_PRE.$table."` SET ".$field." ".$where;
		//echo $sql.'<br>';
		return $this->update($sql);
	}
	
	/**
	 * 删除数据
	 *
	 * @param 表名: $table
	 * @param 条件: $where
	 * @return bool
	 */
	public function db_delete($table='',$where=''){
		if($table=='' || $where==''){
 			return false;
 		}
 		//$where=' WHERE '.$where;
 		$sql="DELETE FROM `".DB_PRE.$table."` ".$where;
 		return $this->query($sql);
	}
	/*
	* 新增数据
	*/
	public function db_insert($data=array(),$table=''){
		if(!is_array($data) || $table=='' || count($data)==0){
 			return false;
 		}
 		$field='';
 		$value='';
 		foreach($data as $k=>$v){
 			$field.='`'.$k.'`,';
 			$value.="'".$v."',";
 		}
 		$sql="INSERT INTO `".DB_PRE.$table."` (".substr($field,0,-1).") VALUES (".substr($value,0,-1).")";
 		//echo $sql;
 		$this->query($sql);
 		return $this->insert_id();
	}
	
	public function db_updateNum($field, $num, $table, $where=""){
		$sql = "UPDATE `".DB_PRE."{$table}` SET `{$field}`=`{$field}`{$num} {$where}";
		//echo $sql;
		$this->query($sql);
	}
	
	public function db_getSql(){
		return $this->sql;
	}
}
?>
