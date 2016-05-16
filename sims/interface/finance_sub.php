<?php
/* 财务管理系统子程序接口
 * code by Tong
 * 2013-08-08
 */
include(dirname(__FILE__).DIRECTORY_SEPARATOR.'T_Lib'.DIRECTORY_SEPARATOR.'config.php');
class finance_sub
{
	/**
	 * 从子系统获取财务明细列表
	 * @param $detail_ids	字符串，取自stage表的detail_ids			
	 */
	public function getFinanceDetailList($classid,$detail_ids){
		$myModel = new myModel();
		$myData = array();
		if($detail_ids){
			$classid_obj 	= json_decode($classid);
			
			$entrance_year	= $classid_obj -> entrance_year;
			$edus			= $classid_obj -> eduspot_id;
			$qpid			= $classid_obj -> pid;
			$disc			= $classid_obj -> discipline_id;
			$clas			= $classid_obj -> classid;
			$feeitem		= $classid_obj -> feeitem;
			$feeitemArray	= array();
			if($feeitem){
				$feeitemArray = explode(',',$feeitem);
			}else{
				$feeitemArray = array(1,2);
			} 
			$sql_where = "";
			if($entrance_year){
				$sql_where .= " and u.enroll_id = '$entrance_year' ";
			}
			if($edus){
				$sql_where .= " and u.college_id = '$edus' ";
			}
			if($qpid){
				$sql_where .= " and u.training_id in ($qpid) ";
			}
			if($disc){
				$sql_where .= " and u.discipline_id in ($disc) ";
			}
			if($clas){
				$sql_where .= " and u.classid in($clas) ";
			}
			if($detail_ids){
				$sql_where .= " and f.order_num = '$detail_ids'";
			}
			$fee_detail_list 	= array();
			$fee_dg_list		= array();
			foreach($feeitemArray as $k => $v){
				if($v == 1){
					$sql_str ="select * from sms_fee_detail f,core_user u where f.userid = u.userid and f.sh_flag = 1 $sql_where ";
					//echo $sql_str;
					//echo '<br />';
					$fee_detail_list = $myModel->get_list_bysql($sql_str);//QDB::getConn()->getAll($sql_str);
					//dump($list);
					
	
				}
				if($v == 2){
					$sql_str ="select * from sms_fee_dg f,core_user u where f.userid = u.userid and f.check = 1 $sql_where ";
					$fee_dg_list = $myModel->get_list_bysql($sql_str);//QDB::getConn()->getAll($sql_str);
				}
			}
			$myData = array(
				'title' => array(
					'feetype'		=> '费用类型',
					'userid'		=> '登录账号',
					'uname'			=> '姓名',
					'entrance_year'	=> '入学批次',
					'college'		=> '主考院校',
					'training'		=> '学习中心',
					'discipline'	=> '专业',
					'class'			=> '班级',
					'order_num'		=> '期数',
					'fee'			=> '本次应缴',
					'pay'			=> '本次已缴',
					'remain'		=> '本次欠费',
					
				),
				'content'	=> array(),
			);
			//入学批次
			$enroll_array 	= array();
			$enroll_list 	= $myModel -> get_list('id,name','enroll');
			foreach($enroll_list as $k => $v){
				$enroll_array[$v['id']] = $v['name'];
			}
			//主考院校
			$college_array 	= array();
			$college_list 	= $myModel -> get_list('id,name','org',array('isdelete'=>0,'type'=>1));
			foreach($college_list as $k => $v){
				$college_array[$v['id']] = $v['name'];
			}
			//学习中心
			$training_array = array();
			$training_list 	= $myModel -> get_list('id,name','org',array('isdelete'=>0,'type'=>2));
			foreach($training_list as $k => $v){
				$training_array[$v['id']] = $v['name'];
			}
			//专业
			$dis_array 	= array();
			$dis_list 	= $myModel -> get_list('id,name','discipline',array('isdelete'=>0));
			foreach($dis_list as $k => $v){
				$dis_array[$v['id']] = $v['name'];
			}
			//班级
			$class_array 	= array();
			$class_list		= $myModel -> get_list('id,name','class',array('isdelete'=>0));
			foreach($class_list as $k => $v){
				$class_array[$v['id']] = $v['name'];
			} 
			$k = 1;
			$hj_fee 	= 0;
			$hj_pay 	= 0;
			$hj_remain 	= 0;
			foreach($fee_detail_list as $kk => $v){
				$myData['content'][$k]['feetype'] 	= '学费';
				$myData['content'][$k]['userid'] 	= $v['userid'];
				$myData['content'][$k]['uname'] 	= $v['name'];
				$myData['content'][$k]['entrance_year'] 	= array_key_exists($v['enroll_id'],$enroll_array)?$enroll_array[$v['enroll_id']]:'--';
				$myData['content'][$k]['college'] 	= array_key_exists($v['college_id'],$college_array)?$college_array[$v['college_id']]:'--';
				$myData['content'][$k]['training'] 	= array_key_exists($v['training_id'],$training_array)?$training_array[$v['training_id']]:'--';
				$myData['content'][$k]['discipline'] 	= array_key_exists($v['discipline_id'],$dis_array)?$dis_array[$v['discipline_id']]:'--';
				$myData['content'][$k]['class'] 	= array_key_exists($v['classid'],$class_array)?$class_array[$v['classid']]:'--';
				$myData['content'][$k]['order_num'] = $v['order_num'];
				$myData['content'][$k]['fee'] 		= $v['fee'];
				$myData['content'][$k]['pay'] 		= $v['pay'];
				$myData['content'][$k]['remain'] 	= $v['remain'];
				$hj_fee 							+= $v['fee']; 
				$hj_pay 							+= $v['pay']; 
				$hj_remain 							+= $v['remain'];
				$k ++;
			}
			foreach($fee_dg_list as $kk => $v){
				$myData['content'][$k]['feetype'] 	= '考试费';
				$myData['content'][$k]['userid'] 	= $v['userid'];
				$myData['content'][$k]['uname'] 	= $v['name'];
				$myData['content'][$k]['entrance_year'] 	= array_key_exists($v['enroll_id'],$enroll_array)?$enroll_array[$v['enroll_id']]:'--';
				$myData['content'][$k]['college'] 	= array_key_exists($v['college_id'],$college_array)?$college_array[$v['college_id']]:'--';
				$myData['content'][$k]['training'] 	= array_key_exists($v['training_id'],$training_array)?$training_array[$v['training_id']]:'--';
				$myData['content'][$k]['discipline'] 	= array_key_exists($v['discipline_id'],$dis_array)?$dis_array[$v['discipline_id']]:'--';
				$myData['content'][$k]['class'] 	= array_key_exists($v['classid'],$class_array)?$class_array[$v['classid']]:'--';
				$myData['content'][$k]['order_num'] = $v['order_num'];
				$myData['content'][$k]['fee'] 		= $v['payable'];
				$myData['content'][$k]['pay'] 		= $v['amount'];
				$myData['content'][$k]['remain'] 	= $v['payable'] - $v['amount'];
				$hj_fee 							+= $v['payable']; 
				$hj_pay 							+= $v['amount']; 
				$hj_remain 							+= $v['payable'] - $v['amount'];
				$k ++;
			}
			$myData['content'][$k]['feetype'] 	= '合计';
			$myData['content'][$k]['userid'] 	= '';
			$myData['content'][$k]['uname'] 	= '';
			$myData['content'][$k]['entrance_year'] 	= '';
			$myData['content'][$k]['college'] 	= '';
			$myData['content'][$k]['training'] 	= '';
			$myData['content'][$k]['discipline'] 	= '';
			$myData['content'][$k]['class'] 	= '';
			$myData['content'][$k]['order_num'] = '';
			$myData['content'][$k]['fee'] 		= '<font color=red><b>'.$hj_fee.'</b></font>';
			$myData['content'][$k]['pay'] 		= '<font color=red><b>'.$hj_pay.'</b></font>';
			$myData['content'][$k]['remain'] 	= '<font color=red><b>'.$hj_remain.'</b></font>';
			/*	
			$ids_where = "'".str_replace(",","','",$detail_ids)."'";
			$sql_where = "SELECT sms_fee_detail.*, sms_fee.uname, sms_fee.totalfee, sms_fee.totalpay, sms_fee.remain AS total_remain, userbc.userid, sms_enroll.name AS ename, sms_class.name AS cname FROM sms_fee_detail INNER JOIN sms_fee ON (sms_fee_detail.pid=sms_fee.id) INNER JOIN core_user_before_check userbc ON (sms_fee_detail.userid=userbc.userid) INNER JOIN sms_enroll ON (userbc.enroll_id=sms_enroll.id) INNER JOIN sms_class ON (userbc.classid=sms_class.id) WHERE sms_fee_detail.order_num='".$detail_ids."' and userbc.classid='".$classid."' ORDER BY sms_fee_detail.paycd DESC, sms_enroll.name ASC, sms_class.name ASC, sms_fee_detail.userid ASC";
			//echo $sql_where;
			//echo '<br />';
			$myModel = new myModel();
			$data = $myModel -> get_list_bysql($sql_where);
			if($data){
				$myData = array(
					'title' => array(
						'userid'	=> '登录账号',
						'uname'		=> '姓名',
						'ename'		=> '入学批次',
						'cname'		=> '班级',
						'totalfee'	=> '总应缴',
						'totalpay'	=> '总已缴',
						'feeback'	=> '减免',
						'total_remain'	=> '总欠费',
						'paycd'		=> '缴费批次',
						'fee'		=> '本次应缴',
						'pay'		=> '本次已缴',
						'remain'	=> '本次欠费',
					),
					'content'	=> array(),
				);
				$hj_totalfee 	= 0;
				$hj_totalpay 	= 0;
				$hj_feeback 	= 0;
				$hj_total_remain= 0;
				$hj_fee			= 0;
				$hj_pay			= 0;		
				$hj_remain		= 0;
				$stu_count		= 0;
				foreach($data as $k => $v){
					$stu_count++;
					$myData['content'][$k]['userid'] = $v['userid'];
					$myData['content'][$k]['uname'] = $v['uname'];
					$myData['content'][$k]['ename'] = $v['ename'];
					$myData['content'][$k]['cname'] = $v['cname'];
					$myData['content'][$k]['totalfee'] = $v['totalfee'];
					$myData['content'][$k]['totalpay'] = $v['totalpay'];
					$myData['content'][$k]['feeback'] = $v['feeback']?$v['feeback']:'0';
					$myData['content'][$k]['total_remain'] = $v['total_remain'];
					$myData['content'][$k]['paycd'] = $v['paycd'];
					$myData['content'][$k]['fee'] = $v['fee'];
					$myData['content'][$k]['pay'] = $v['pay'];
					$myData['content'][$k]['remain'] = $v['remain'];
					$hj_totalfee 	+= $myData['content'][$k]['totalfee'];
					$hj_totalpay 	+= $myData['content'][$k]['totalpay'];
					$hj_feeback 	+= $myData['content'][$k]['feeback'];
					$hj_total_remain+= $myData['content'][$k]['total_remain'];
					$hj_fee			+= $myData['content'][$k]['fee'];
					$hj_pay			+= $myData['content'][$k]['pay'];		
					$hj_remain		+= $myData['content'][$k]['remain'];	
				}
				$k++;
				$myData['content'][$k]['userid'] = '合计';
				$myData['content'][$k]['uname'] = '';
				$myData['content'][$k]['ename'] = '';
				$myData['content'][$k]['cname'] = '共 <font color=red><b>'.$stu_count.'</b></font> 人';
				$myData['content'][$k]['totalfee'] = $hj_totalfee;
				$myData['content'][$k]['totalpay'] = $hj_totalpay;
				$myData['content'][$k]['feeback'] = $hj_feeback;
				$myData['content'][$k]['total_remain'] = $hj_total_remain;
				$myData['content'][$k]['paycd'] = '';
				$myData['content'][$k]['fee'] = '<font color=red><b>'.$hj_fee.'</b></font>';
				$myData['content'][$k]['pay'] = $hj_pay;
				$myData['content'][$k]['remain'] = $hj_remain;	
			}
			*/
			//print_r($data);
		}
		return $myData;
	}
	/**
	 * 从子系统获取所有的财务明细列表
	 */
	public function getAllDetailList(){
		$myData = array();
		$sql_where = "SELECT sms_fee_detail.*, sms_fee.uname, sms_fee.totalfee, sms_fee.totalpay, sms_fee.remain AS total_remain, userbc.userid, sms_enroll.name AS ename, sms_class.name AS cname FROM sms_fee_detail INNER JOIN sms_fee ON (sms_fee_detail.pid=sms_fee.id) INNER JOIN core_user_before_check userbc ON (sms_fee_detail.userid=userbc.userid) INNER JOIN sms_enroll ON (userbc.enroll_id=sms_enroll.id) INNER JOIN sms_class ON (userbc.classid=sms_class.id) ORDER BY sms_fee_detail.paycd DESC, sms_enroll.name ASC, sms_class.name ASC, sms_fee_detail.userid ASC";
		//echo $sql_where;
		//echo '<br />';
		$myModel = new myModel();
		$data = $myModel -> get_list_bysql($sql_where);
		if($data){
			$myData = array(
				'title' => array(
					'id'		=> '请选择',
					'userid'	=> '登录账号',
					'uname'		=> '姓名',
					'ename'		=> '入学批次',
					'cname'		=> '班级',
					'totalfee'	=> '总应缴',
					'totalpay'	=> '总已缴',
					'feeback'	=> '减免',
					'total_remain'	=> '总欠费',
					'paycd'		=> '缴费批次',
					'fee'		=> '本次应缴',
					'pay'		=> '本次已缴',
					'remain'	=> '本次欠费',
				),
				'content'	=> array(),
			);
			foreach($data as $k => $v){
				$myData['content'][$k]['id'] = $v['id'];
				$myData['content'][$k]['userid'] = $v['userid'];
				$myData['content'][$k]['uname'] = $v['uname'];
				$myData['content'][$k]['ename'] = $v['ename'];
				$myData['content'][$k]['cname'] = $v['cname'];
				$myData['content'][$k]['totalfee'] = $v['totalfee'];
				$myData['content'][$k]['totalpay'] = $v['totalpay'];
				$myData['content'][$k]['feeback'] = $v['feeback'];
				$myData['content'][$k]['total_remain'] = $v['total_remain'];
				$myData['content'][$k]['paycd'] = $v['paycd'];
				$myData['content'][$k]['fee'] = $v['fee'];
				$myData['content'][$k]['pay'] = $v['pay'];
				$myData['content'][$k]['remain'] = $v['remain'];	
			}	
		}
		//print_r($data);
		return $myData;
	}
	/**
	 * 从子系统中获取搜索条件
	 */
	public function getSearchcondition(){
		$myModel = new myModel();
		$myData = array(
					'title'		=> array(
						'qenroll'		=> '入学批次',
						'qorgedu'		=> '主考院校',
						'qorglen'		=> '学习中心',
						'qdiscipline'	=> '所属专业',
						'qclassinfo'	=> '班级名称',
						'quserid'		=> '登录账号',
						'qname'			=> '学员姓名',
						'qpaycd'		=> '缴费批次',
					),
					'content'	=> array(
						'qenroll'		=> array(),
						'qorgedu'		=> array(),
						'qorglen'		=> array(),
						'qdiscipline'	=> array(),
						'qclassinfo'	=> array(),
						'quserid'		=> '',
						'qname'			=> '',
						'qpaycd'		=> '',
					),
					
		);
		$myData['content']['qenroll'] 	= $myModel -> get_list('id,name','enroll',array('isdelete'=>0));
		$myData['content']['qorgedu']	= $myModel -> get_list('id,name','org',array('isdelete'=>0,'type'=>1));
		$myData['content']['qorglen']	= $myModel -> get_list('id,name,pid','org',array('isdelete'=>0,'type'=>2)); 
		$myData['content']['qdiscipline']	= $myModel -> get_list('id,name,college_id','discipline',array('isdelete'=>0)); 
		$myData['content']['qclassinfo']	= $myModel -> get_list('id,name,enroll_id,college_id,training_id,discipline_id','class',array('isdelete'=>0)); 
		return $myData;
	}
	/**
	 * 从子系统中获取搜索内容
	 * @param condition = array()
	 */
	public function getSearchData($condition=array()){
		$myData = array();
		$where = " where 1=1";
		//入学批次
		if(array_key_exists('qenroll',$condition)){
			$qenroll = trim($condition['qenroll']);
			if($qenroll){
				$where .= " and sms_enroll.id='".$qenroll."'";
			}
		}
		//主考院校
		if(array_key_exists('qorgedu',$condition)){
			$qorgedu = trim($condition['qorgedu']);
			if($qorgedu){
				$where .= " and userbc.college_id='".$qorgedu."'";
			}
		}
		//学习中心
		if(array_key_exists('qorglen',$condition)){
			$qorglen = trim($condition['qorglen']);
			if($qorglen){
				$where .= " and userbc.training_id='".$qorglen."'";
			}
		}
		//所属专业
		if(array_key_exists('qdiscipline',$condition)){
			$qdiscipline = trim($condition['qdiscipline']);
			if($qdiscipline){
				$where .= " and userbc.discipline_id='".$qdiscipline."'";
			}
		}
		//班级名称
		if(array_key_exists('qclassinfo',$condition)){
			$qclassinfo = trim($condition['qclassinfo']);
			if($qclassinfo){
				$where .= " and userbc.classid='".$qclassinfo."'";
			}
		}
		//登陆账号
		if(array_key_exists('quserid',$condition)){
			$quserid = trim($condition['quserid']);
			if($quserid){
				$where .= " and sms_fee_detail.userid='".$quserid."'";
			}
		}
		//学生姓名
		if(array_key_exists('qname',$condition)){
			$qname = trim($condition['qname']);
			if($qname){
				$where .= " and sms_fee.uname like '%".$qname."%'";
			}
		}
		//缴费批次
		if(array_key_exists('qpaycd',$condition)){
			$qpaycd = trim($condition['qpaycd']);
			if($qpaycd){
				$where .= " and sms_fee_detail.paycd='".$qpaycd."'";
			}
		}
		$sql_where = "SELECT sms_fee_detail.*, sms_fee.uname, sms_fee.totalfee, sms_fee.totalpay, sms_fee.remain AS total_remain, userbc.userid, sms_enroll.name AS ename, sms_class.name AS cname FROM sms_fee_detail INNER JOIN sms_fee ON (sms_fee_detail.pid=sms_fee.id) INNER JOIN core_user_before_check userbc ON (sms_fee_detail.userid=userbc.userid) INNER JOIN sms_enroll ON (userbc.enroll_id=sms_enroll.id) INNER JOIN sms_class ON (userbc.classid=sms_class.id) " .$where. " ORDER BY sms_fee_detail.paycd DESC, sms_enroll.name ASC, sms_class.name ASC, sms_fee_detail.userid ASC";
		//echo $sql_where;
		//echo '<br />';
		$myModel = new myModel();
		$data = $myModel -> get_list_bysql($sql_where);
		if($data){
			$myData = array(
				'title' => array(
					'id'		=> '请选择',
					'userid'	=> '登录账号',
					'uname'		=> '姓名',
					'ename'		=> '入学批次',
					'cname'		=> '班级',
					'totalfee'	=> '总应缴',
					'totalpay'	=> '总已缴',
					'feeback'	=> '减免',
					'total_remain'	=> '总欠费',
					'paycd'		=> '缴费批次',
					'fee'		=> '本次应缴',
					'pay'		=> '本次已缴',
					'remain'	=> '本次欠费',
				),
				'content'	=> array(),
			);
			foreach($data as $k => $v){
				$myData['content'][$k]['id'] = $v['id'];
				$myData['content'][$k]['userid'] = $v['userid'];
				$myData['content'][$k]['uname'] = $v['uname'];
				$myData['content'][$k]['ename'] = $v['ename'];
				$myData['content'][$k]['cname'] = $v['cname'];
				$myData['content'][$k]['totalfee'] = $v['totalfee'];
				$myData['content'][$k]['totalpay'] = $v['totalpay'];
				$myData['content'][$k]['feeback'] = $v['feeback'];
				$myData['content'][$k]['total_remain'] = $v['total_remain'];
				$myData['content'][$k]['paycd'] = $v['paycd'];
				$myData['content'][$k]['fee'] = $v['fee'];
				$myData['content'][$k]['pay'] = $v['pay'];
				$myData['content'][$k]['remain'] = $v['remain'];	
			}	
		}
		//print_r($data);
		return $myData;
	}
	/* 
	 * 数据同步
	 * @param $data			数组，同步的数据
	 */
	public function dataSyn($data = array()){
		$myData	= array();
		if(!empty($data) && is_array($data)){
			$myModel = new myModel();
			$charge_id_arr = array();
			foreach($data as $k => $v){
				$myData[$k]['id']			= $v['id'];
				$myData[$k]['exp_income']	= $v['exp_income'];
				$myData[$k]['applicant_id']	= $v['applicant_id'];
				$myData[$k]['stage_data'] 	= $this->getSynData($v['classid']);
				array_push($charge_id_arr,$v['id']);
			}
			//return $myData;
			//return $charge_id_arr;
			if(!empty($charge_id_arr)){
				$charge_id_str = implode(',',$charge_id_arr);
				$where_delete = "  where charge_id not in ($charge_id_str)";
				$myModel -> do_delete('finance_report',$where_delete,0);
			}
		}
		return $myData;
	}
	/*
	 * 根据classid得到最新数据
	 * @param classid json结构，上报条件
	 */
	private function getSynData($classid){
		$myModel 		= new myModel();
		$classid_obj 	= json_decode($classid);
		$entrance_year	= $classid_obj -> entrance_year;
		$edus			= $classid_obj -> eduspot_id;
		$qpid			= $classid_obj -> pid;
		$disc			= $classid_obj -> discipline_id;
		$clas			= $classid_obj -> classid;
		$feeitem		= $classid_obj -> feeitem;
		$feeitemArray	= array();
		if($feeitem){
			$feeitemArray = explode(',',$feeitem);
		}else{
			$feeitem_all = array(1=>'学费',2=>'考试费');
			foreach($feeitem_all as $k_all => $v_all){
				array_push($feeitemArray,$k_all);
			}
		}
		$sql_where = "";
		if($entrance_year){
			$sql_where .= " and u.enroll_id = '$entrance_year' ";
		}
		if($edus){
			$sql_where .= " and u.college_id = '$edus' ";
		}
		if($qpid){
			$sql_where .= " and u.training_id in ($qpid) ";
		}
		if($disc){
			$sql_where .= " and u.discipline_id in ($disc) ";
		}
		if($clas){
			$sql_where .= " and u.classid in($clas) ";
		}
		
		$stage_data	= array();
		foreach($feeitemArray as $k => $v){
			if($v == 1){
				$sql_str ="select paycd,sum(fee) as total_fee,order_num from sms_fee_detail f,core_user u where f.userid = u.userid and f.sh_flag = 1 $sql_where group by order_num  order by order_num ASC";
				//echo $sql_str;
				//echo '<br />';
				$list = $myModel->get_list_bysql($sql_str);//$list = QDB::getConn()->getAll($sql_str);
				//dump($list);
				foreach($list as $key => $val){
					$stage_data[$val['order_num']]['order_num']		= $val['order_num'];
					@$stage_data[$val['order_num']]['total_fee']	+= $val['total_fee'];
					$stage_data[$val['order_num']]['paycd']			= $val['order_num'];
				}

			}
			if($v == 2){
				$sql_str ="select paycd,sum(payable) as total_fee,order_num from sms_fee_dg f,core_user u where f.userid = u.userid and f.check = 1 $sql_where group by order_num order by order_num ASC";
				$list = $myModel->get_list_bysql($sql_str);//$list = QDB::getConn()->getAll($sql_str);
				foreach($list as $key => $val){
					$stage_data[$val['order_num']]['order_num']		= $val['order_num'];
					@$stage_data[$val['order_num']]['total_fee']	+= $val['total_fee'];
					$stage_data[$val['order_num']]['paycd']			= $val['order_num'];
				}
			}
		}
		
		/*$classinfo_list = $myModel -> get_list('*','sms_class',$sql_where);//Classinfo::find($sql_where)->order('CONVERT(name USING gbk)')->getAll();
		//return $classinfo_list;
		$stage_data	= array();
		$return_data = array();
		foreach($classinfo_list as $c_key => $classinfo){
			$where_user = "select userid from core_user where classid='".$classinfo['id']."'";
			$userCount	= $myModel -> get_count_bysql($where_user);
			$enroll_id = $classinfo['enroll_id'];
			$discipline_id = $classinfo['discipline_id'];
			$enrollData = $myModel -> get_one('*','sms_enroll',array('id'=>$enroll_id));
			$entrance_year_show = intval($enrollData['name']);
			$month		= '09';
			$day		= '01';
			$day_end	= '31';
			$fee = $myModel -> get_one('*','cj_fee',array('enroll_id'=>$enroll_id,'discipline_id'=>$discipline_id));
			array_push($return_data,$fee);
			//$fee = Fee::find("entrance_year='$entrance_year' and discipline_id='$discipline_id'")->getOne();
			if($fee['receivableFee']){
				$receivablefee = json_decode($fee['receivableFee']);
				//dump($receivablefee);	
				foreach($receivablefee as $k => $v){
					if(array_key_exists($k,$stage_data)){
						//$stage_data[$k]['order_num'] = $k;
						$total_fee = 0;
						foreach($v as $key => $val){
							if(empty($feeitemArray)){
								$total_fee += $val * $userCount;
							}else{
								if(in_array($key,$feeitemArray)){
									$total_fee += $val * $userCount;
								}
							}
						}
						$stage_data[$k]['total_fee'] += $total_fee;
						//$stage_data[$k]['paycd'] = ($entrance_year+$k-1).$month;
						//$stage_data[$k]['time_start'] = ($entrance_year+$k-1).'-'.$month.'-'.$day;
					}else{
						$stage_data[$k]['order_num'] = $k;
						$total_fee = 0;
						foreach($v as $key => $val){
							if(empty($feeitemArray)){
								$total_fee += $val * $userCount;
							}else{
								if(in_array($key,$feeitemArray)){
									$total_fee += $val * $userCount;
								}
							}
						}
						$stage_data[$k]['total_fee']	= $total_fee;
						$stage_data[$k]['paycd']		= ($entrance_year_show+$k-1).$month;
						$stage_data[$k]['time_start']	= ($entrance_year_show+$k-1).'-'.$month.'-'.$day;
						$stage_data[$k]['time_end']		= ($entrance_year_show+$k-1).'-'.$month.'-'.$day_end;
					}
				}
			}
		}*/
		return $stage_data;
		//return $return_data;
	}
}
 
//本地调试
//my_finance = new finance_sub();
//$my_finance -> getFinanceDetailList('{"entrance_year":"14","eduspot_id":"67","pid":"","discipline_id":"","classid":"","feeitem":""}',1);

ini_set("soap.wsdl_cache_enabled", "0");
$data = $HTTP_RAW_POST_DATA;
$data = file_get_contents('php://input');
$server=new SoapServer(null,array('uri' => "http://localhost"));
$server->setClass("finance_sub");
$server->setPersistence(SOAP_PERSISTENCE_SESSION);
$server->handle($data);
?>
