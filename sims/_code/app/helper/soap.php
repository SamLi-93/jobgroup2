<?
/**
 * SOAP 客户端类 ，读取公告
 */
//define('FINANCE_SOAP_URL','http://10.82.97.204/jibo_finance/interface/finance.wsdl');
define('FINANCE_SOAP_URL','http://caiwu.jobgroup.cn/interface/finance.wsdl');
define('APP_KEY','APP_ZB');
class Helper_Soap
{
	
	
	//public $FINANCE_SOAP_URL = 'http://localhost/jibo_finance/interface/finance.wsdl';
	//public $APP_KEY = 'APP_ZB';
	PUBLIC static function getNew($platform)
	{
		if(!$platform) return null;
		//$url = "http://www.local/jibo_msg/_code/lib/soap.php";
		//$url = 'http://192.168.1.200/svn/jibo_msg/_code/lib/soap.php';
		$url = 'http://om.zjnep.com/_code/lib/soap.php';
		try{
			$soap = new SoapClient(null,array(
			"location" => $url,
			"uri"      => "abcd",  //资源描述符服务器和客户端必须对应
			"style"    => SOAP_RPC,
			"use"      => SOAP_ENCODED
			));
			
			$news = $soap->getNews($platform);
			return $news;
		}catch(Exception $e){
			//echo print_r($e->getMessage(),true);
			return null;
		}
	}
	/* 财务数据上报至财务管理系统
	 *
	 */
	PUBLIC static function doReport($data = array()){
		if(!empty($data)){
			//print_r($data);
			try{
				$soap_finance = new SoapClient(FINANCE_SOAP_URL);
				return $soap_finance->reportData($data,APP_KEY);
			}catch(Exception $e){
				return NULL;
			}
		}
	}
	/* 财务数据上报至财务管理系统----修改（驳回后上报）
	 *
	 */
	PUBLIC static function doEditReport($data = array()){
		if(!empty($data)){
			//print_r($data);
			try{
				$soap_finance = new SoapClient(FINANCE_SOAP_URL);
				return $soap_finance->reportEditData($data,APP_KEY);
			}catch(Exception $e){
				return NULL;
			}
		}
	}
	/*
	 * 根据APP_KEY，从财务管理系统中，获取财务数据列表
	 */ 
	PUBLIC static function getChargeList(){
		try{
			$soap_finance = new SoapClient(FINANCE_SOAP_URL);
			return $soap_finance->getChargeList(APP_KEY);		
		}catch(Exception $e){
			return NULL;
		}
	}
	/*
	 * 根据id,从财务管理系统中，获取处理记录
	 * @param $id
	 */
	PUBLIC static function getChargeRecordList($id){
		if($id){
			try{
				$soap_finance = new SoapClient(FINANCE_SOAP_URL);
				return $soap_finance->getChargeRecordList($id,APP_KEY);
			}catch(Exception $e){
				return NULL;
			}
		}
	}
	/*
	 * 根据$charge_id,从财务管理系统中，获取单条财务的详细数据
	 * @param $charge_id
	 */
	PUBLIC static function getChargeData($charge_id){
		if($charge_id){
			try{
				$soap_finance = new SoapClient(FINANCE_SOAP_URL);
				return $soap_finance->getChargeData($charge_id,APP_KEY);
			}catch(Exception $e){
				return NULL;
			}
		}
	}
	PUBLIC static function getNextItemList(){
		try{
			$soap_finance = new SoapClient(FINANCE_SOAP_URL);
			return $soap_finance->getNextItemList(APP_KEY);		
		}catch(Exception $e){
			return NULL;
		}
	}
	PUBLIC static function getProjectSel($currentId = 0){
		try{
			$soap_finance = new SoapClient(FINANCE_SOAP_URL);
			return $soap_finance->getProjectSel($currentId,APP_KEY);
		}catch(Exception $e){
			return NULL;
		}	
	}	
}
