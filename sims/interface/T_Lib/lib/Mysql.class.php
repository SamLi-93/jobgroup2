<?php
//处理mysql数据库
class Mysql{
	private 	$upwd		='';
	private		$uname		='root';
	private		$host		='localhost';
	private 	$db			='v';
	private 	$pconnect 	= false;
	private 	$querynum	=0;
	private 	$conn;
	private 	$logsql;
	private 	$character  = 'gbk';
	public 		$sql="";
	//private     $conn;
	public function __construct($config = array()){
		$this->host			= DB_HOST;
		$this->uname		= DB_USER;
		$this->upwd			= DB_PASS;
		$this->db			= DB_DBNAME;
		$this->character	= DB_CHARACTER;
		$this->pconnect		= DB_PCCONNECT;
		$this -> initialize($config);
		//echo $this->host.'/'.$this->db.'/'.$this->uname.'/'.$this->upwd;		
		$this->connection();
	}
	public function initialize($config = array()){
		foreach ($config as $key => $val){
			if (isset($this->$key)){
				$this->$key = $val;
			}
		}
	}
	/**
	 * 连接数据库
	 *
	 */
	public function connection(){
		$error = 'Can not connect to MySQL server';
		try{
			if($this -> pconnect) {
				if(!$this->conn = @mysql_pconnect($this->host, $this->uname, $this->upwd)) {
					throw new Exception($error,101);
				}
			} else {
				if(!$this->conn = @mysql_connect($this->host, $this->uname, $this->upwd, 1)) {
					throw new Exception($error,101);
				}
			}
			if($this->version() > '4.1') {
				if($this->character) {
					@mysql_query("SET character_set_connection=$this->character, character_set_results=$this->character, character_set_client=binary", $this->conn);
				}
				if($this->version() > '5.0.1') {
					@mysql_query("SET sql_mode=''", $this->conn);
				}
			}
			$this->select_db($this->db);
		}catch(Exception $e){
			echo $e;
		}
		/*$this->pconnect==0?mysql_connect($this->host,$this->uname,$this->upwd):mysql_pconnect($this->host,$this->uname,$this->upwd);
		mysql_errno()!=0 && $this->halt("Connect($pconnect) to MySQL failed");
		
		mysql_query("SET character_set_connection=utf8, character_set_results=utf8, character_set_client=binary");
		mysql_query("SET sql_mode=''");
		mysql_query("SET NAMES ".$this->character);
		$this->select_db($this->db);*/
	}
	public function select_db($dbname){
		$error = 'Cannot use database';
		try{
			if(!@mysql_select_db($dbname,$this->conn)){
				throw new Exception($error,102);
			}
		}catch(Exception $e){
			echo $e;
		}
	}
	/**
	 * 
	 *
	 * @return unknown
	 * mysql_get_server_info : 取得 MySQL 服务器信息
	 */
	public function version(){
		return mysql_get_server_info($this->conn);
	}
	/**
	 * 查询数据库并记录日志
	 *
	 * @param string $logname
	 * @param string $sql
	 * @return resource
	 */
	public function logquery($logname,$sql){
		$this->record_log($logname,$sql);
		return $this->query($sql);
	}
	/**
	 * 查询数据库
	 *
	 * @param unknown_type $sql
	 * @param unknown_type $method
	 * @return resource
	 * mysql_unbuffered_query:向 MySQL 发送一条 SQL 查询，并不获取和缓存结果的行
	 */
	public function query($sql,$method=''){
		if($method=='U_B' && function_exists('mysql_unbuffered_query')){
			$query = mysql_unbuffered_query($sql);
		}else{
			$query = mysql_query($sql);
		}
		//$this->querynum++;
		if (!$query) return false;//$this->halt('Query Error: ' . $sql);
		return $query;
	}
	public function logget_one($logname,$sql){
		$this->record_log($logname,$sql);
		return $this->get_one($sql);
	}
	public 	function get_one($sql){
		$query=$this->query($sql,'U_B');
		//echo '<br>sql:'.$sql;
		$rs = @mysql_fetch_array($query, MYSQL_ASSOC);
		return $rs;
	}
	public function logupdate($logname,$sql){
		$this->record_log($logname,$sql);
		return $this->update($sql);
	}
	//更新
	public function update($sql,$lp=1) {
		if(function_exists('mysql_unbuffered_query')){
			$query = mysql_unbuffered_query($sql);
		}else{
			$query = mysql_query($sql);
		}
		//$this->query_num++;

		if (!$query)  $this->halt('Update Error: ' . $sql);
		return $query;
	}
	public function get_array($result,$result_type = MYSQL_ASSOC)
	{
		return @mysql_fetch_array($result,$result_type);
	}
	//关闭 MySQL 连接
	public function closesql()
	{
		return mysql_close();
	}
	//取得前一次 MySQL 操作所影响的记录行数
	public function affected_rows() {
		return mysql_affected_rows($this->conn);
	}
	//取得结果集中行的数目
	public function num_rows($sql) {
		$query = $this->query($sql);
		$rows = mysql_num_rows($query);
		return $rows;
	}
	//释放结果内存
	public function free_result($query) {
		return mysql_free_result($query);
	}
	//取得上一步 INSERT 操作产生的 ID 
	public function insert_id() {
		$id = mysql_insert_id();
		return $id;
	}
	public function halt($msg){
		//Debug::start();
		echo $msg;
		//Debug::end();
	}
	public function record_log($logname,$logsql){
		date_default_timezone_set("prc");
		$time=date("Y-m-d H:i:s");
		$sql="INSERT INTO logs(logname,logsql,logtime)VALUES('$logname','$logsql','$time')";
		$this->query($sql);
	}
	public function setLogsql($sql){
		$this->logsql=$sql;
	}

	public function getLogsql(){
		return $this->logsql;
	}
}
?>