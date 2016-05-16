<?php

class Helper_Msgfactory {
	/**
	 * remoteNotice
	 *
	 * 远程保存短信
	 * 如果是多条短信，调用此方法前已经遍历成单条
	 * $post_msgs	Array		单条短信内容
	 */
	public static function remoteNotice(){
		$url = "http://om.zjnep.com/_code/app/helper/msgsoap.php";
		try{
			$soap = new SoapClient(null,array(
				"location" => $url,
				"uri"      => "abcd",  //资源描述符服务器和客户端必须对应
				"style"    => SOAP_RPC,
				"use"      => SOAP_ENCODED
			));
			$msgs = $soap->getNotice();
			return $msgs;
		}catch(Exception $e){
			echo print_r($e->getMessage(),true);
		}
	}/**
	 * remoteSave
	 *
	 * 远程保存短信
	 * 如果是多条短信，调用此方法前已经遍历成单条
	 * $post_msgs	Array		单条短信内容
	 */
	public static function remoteSave($post_msgs){
		$url = "http://om.zjnep.com/_code/app/helper/msgsoap.php";
		try{
			$soap = new SoapClient(null,array(
				"location" => $url,
				"uri"      => "abcd",  //资源描述符服务器和客户端必须对应
				"style"    => SOAP_RPC,
				"use"      => SOAP_ENCODED
			));
			$msgs = $soap->saveMsgs($post_msgs);
			return $msgs;
		}catch(Exception $e){
			echo print_r($e->getMessage(),true);
		}
	}
	/**
	 * remoteGetone
	 *
	 * 远程保存短信
	 * 如果是多条短信，调用此方法前已经遍历成单条
	 * $post_msgs	Array		单条短信内容
	 */
	public static function remoteGetone($get_sql){
		$url = "http://om.zjnep.com/_code/app/helper/msgsoap.php";
		try{
			$soap = new SoapClient(null,array(
				"location" => $url,
				"uri"      => "abcd",  //资源描述符服务器和客户端必须对应
				"style"    => SOAP_RPC,
				"use"      => SOAP_ENCODED
			));
			$msgs = $soap->getOne($get_sql);
			return $msgs;
		}catch(Exception $e){
			echo print_r($e->getMessage(),true);
		}
	}
	/**
	 * remoteGetSome
	 *
	 * 远程保存短信
	 * 如果是多条短信，调用此方法前已经遍历成单条
	 * $post_msgs	Array		数条短信内容
	 */
	public static function remoteGetSome($get_sql){
		$url = "http://om.zjnep.com/_code/app/helper/msgsoap.php";
		try{
			$soap = new SoapClient(null,array(
				"location" => $url,
				"uri"      => "abcd",  //资源描述符服务器和客户端必须对应
				"style"    => SOAP_RPC,
				"use"      => SOAP_ENCODED
			));
			$msgs = $soap->getSome($get_sql);
			return $msgs;
		}catch(Exception $e){
			echo print_r($e->getMessage(),true);
		}
	}
	/**
	 * remoteExport
	 *
	 * 远程保存短信
	 * 如果是多条短信，调用此方法前已经遍历成单条
	 * $post_msgs	Array		单条短信内容
	 */
	public static function remoteExport($export_sql){
		$url = "http://om.zjnep.com/_code/app/helper/msgsoap.php";
		try{
			$soap = new SoapClient(null,array(
				"location" => $url,
				"uri"      => "abcd",  //资源描述符服务器和客户端必须对应
				"style"    => SOAP_RPC,
				"use"      => SOAP_ENCODED
			));
			$msgs = $soap->Export($export_sql);
			return $msgs;
		}catch(Exception $e){
			echo print_r($e->getMessage(),true);
		}
	}
	/**
	 * remoteDel
	 *
	 * 远程保存短信
	 * 如果是多条短信，调用此方法前已经遍历成单条
	 * $post_msgs	Array		单条短信内容
	 */
	public static function remoteDel($del_sql){
		$url = "http://om.zjnep.com/_code/app/helper/msgsoap.php";
		try{
			$soap = new SoapClient(null,array(
				"location" => $url,
				"uri"      => "abcd",  //资源描述符服务器和客户端必须对应
				"style"    => SOAP_RPC,
				"use"      => SOAP_ENCODED
			));
			$msgs = $soap->msgDel($del_sql);
			return $msgs;
		}catch(Exception $e){
			echo print_r($e->getMessage(),true);
		}
	}
	/**
	 * remoteGetRecord
	 *
	 * 远程获取短信记录
	 * $filter_sql		String		搜索过滤条件sql
	 * $start			Int			分页开始数
	 * $limit			Int			分页单页数
	 * $s_month			String		本月发送成功短信数sql
	 * $s_month			String		本月发送失败短信数sql
	 * $s_all			String		历史发送成功短信数sql
	 * $f_all			String		历史发送失败短信数sql
	 *
	 * return			$msgs		Array		短信信息集合
	 */
	public static function remoteGetRecord($filter_sql, $start, $limit, $s_month, $f_month, $s_all, $f_all){
		$url = "http://om.zjnep.com/_code/app/helper/msgsoap.php";
		try{
			$soap = new SoapClient(null,array(
				"location" => $url,
				"uri"      => "abcd",  //资源描述符服务器和客户端必须对应
				"style"    => SOAP_RPC,
				"use"      => SOAP_ENCODED
			));
			$msgs = $soap->getMsgs($filter_sql, $start, $limit, $s_month, $f_month, $s_all, $f_all);
			return $msgs;
		}catch(Exception $e){
			echo print_r($e->getMessage(),true);
		}
	}
	//生成pagination
	static function gen_pagination($total, $limit) {
		$current = QContext::instance()->page;
		if (!$current) $current = 1;
		$last = ceil($total/$limit);
		if (!$last) $last = 1;
		$prev = $current - 1;
		if (!$prev) $prev = 1;
		$next = $current + 1;
		if ($next > $last) $next = $last;
		$pagination = array(
			'first' => 1,
			'last' => $last,
			'current' => $current,
			'record_count' => $total,
			'prev' => $prev,
			'next' => $next,
		);
		return $pagination;
	}
	/**
	 * remoteLogin
	 *
	 * 远程登录有短信发送接口的网站
	 * $login_url	String		登录地址
	 * $login_param	Array		登录帐号密码
	 * return cookies和session
	 
	public static function remoteLogin($login_url,$login_param){
		
		$ch = curl_init();
		// 设置URL和相应的选项
		curl_setopt($ch, CURLOPT_URL, $login_url);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $login_param);
		// 获取头部信息
		curl_setopt($ch, CURLOPT_HEADER, 1);
		//设置curl最长执行时间
		curl_setopt($ch, CURLOPT_TIMEOUT, 23); 
		//返回原声raw输出
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); 
		// 执行并获取返回结果
		$content = curl_exec($ch);  				//返回结果的【编码格式没设】
		// 解析HTTP数据流
		@list($header, $body) = explode("\r\n\r\n\r\n", $content);
		**
		 * 获取sessionId 和 编码格式
		 *
		preg_match("/set\-cookie:([^\r\n]*)/i", $header, $cookies);
		preg_match("/content\-type:([^\r\n]*)/i", $header, $contentType);
		$cookie = $cookies[1];
		$contentType = $contentType[1];
		if(!$cookie)
			dump("cookie infomation is not found");
		if(!$contentType)
			dump("contentType infomation is not found");
		if($cookie!=null){
			$sessionId = explode(";", $cookie);
			$sessionId = $sessionId[0];
		}
		if($contentType!=null){
			$requestEncoding = explode(";",$contentType);
		}
		$info = array("cookie"=>$cookie,"sessionId"=>$sessionId);
		curl_close($ch);
		return $info;
	}*/

	/**
	 * remoteLogin
	 *
	 * 远程登录有短信发送接口的网站
	 * $login_url	String		登录地址
	 * $login_info	Array		登录帐号密码
	 * return cookies和session
	public static function remotePost($post_url, $post_param, $remoteInfo){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $post_url);
		if($remoteInfo['cookie']&&$remoteInfo['sessionId'])
			curl_setopt($ch, CURLOPT_COOKIE, $remoteInfo['sessionId']);
		curl_setopt($ch, CURLOPT_TIMEOUT, 23);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post_param);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_ENCODING ,'utf8');
		//获取回复页面内容$content
		$response = curl_exec($ch);
		curl_close($ch);
	}*/

    /**
	 * remoteSwitchStatus
	 *
	 * 远程保存短信
	 * 如果是多条短信，调用此方法前已经遍历成单条
	 * $post_msgs	Array		单条短信内容
	 */
	public static function remoteSwitchStatus($app_id){
		$url = "http://om.zjnep.com/_code/app/helper/msgsoap.php";
		try{
			$soap = new SoapClient(null,array(
				"location" => $url,
				"uri"      => "abcd",  //资源描述符服务器和客户端必须对应
				"style"    => SOAP_RPC,
				"use"      => SOAP_ENCODED
			));
			$status = $soap->getSwitchStatus($app_id);
			return $status;
		}catch(Exception $e){
			echo print_r($e->getMessage(),true);
		}
	}


}



