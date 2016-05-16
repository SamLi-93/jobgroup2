<?php
/*
 ###########################################################################
 #
 #        Zhejiang Job Educational Technology Company
 #
 ###########################################################################
 #
 #  Filename: sms.php
 #
 #  Description: according with post ,save short message content
 #      
 #
 #
 ###########################################################################
 #
 #    R E V I S I O N   L O G
 #
 #    Date       Name            Description
 #    --------   --------------- -------------------------------
 #    2012/05/23   Xu Wei         Created.
 #
 ###########################################################################
 */
class Helper_Smsplug {

	/**
	 * 统计初始条件 
	 * @params array $params 短信数组，每隔数组元素包含短信内容
	 *         短信内容包括短信主要内容和附带内容
	 		   主要内容：接收人、接受号码、接受内容、预约时间、
			   附带内容：发送人(id,name)、发送机构、发送模块
	 * @return array $conditions 
	 * 2012/05/22 XuWei Created
	 */
	public static function record($params){
		//num 计数器 用于计算真实保存到的短信条数
		$num = 0;
		foreach($params as $v=>$msg){
			$req_time = time();
			$sendtime = strtotime($msg['sendtime']);
			$msglist = new Msglist();
			$msglist['to_phone'] = $msg['tophone'];
			$msglist['to_name'] = $msg['toname'];
			$msglist['msg'] = $msg['message'];
			$msglist['req_time'] = $req_time;
			$msglist['status'] = 0;
			$msglist['retry_count'] = 0;
			$msglist['is_schedule'] = ($sendtime>$req_time)?1:0;
			$msglist['schedule_time'] = $sendtime;
			$msglist['send_time'] = 0;
			$msglist['app_id'] = 4;      
			$msglist['user_id'] = $msg['userid'];
			$msglist['user_name'] = $msg['username'];
			$msglist['app_name'] = "专本衔接sims系统";
			$msglist['module_name'] = $msg['mdlname'];
			$msglist['org_name'] = $msg['org_name'];
			$msglist->save();
			$num++;
		}
		$result = $num==count($params)?"yes":"no";
		return $result;
	}
}

