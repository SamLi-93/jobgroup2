<?php
/*
 ###########################################################################
 #
 #        Zhejiang Job Educational Technology Company
 #
 ###########################################################################
 #
 #  Filename: userbc_controller.php
 #
 #  Description:
 #
 #
 ###########################################################################
 #
 #    R E V I S I O N   L O G
 #
 #    Date       Name            Description
 #    --------   --------------- -------------------------------
 #    2011/7/21   Gu wen         Created.
 #
 ###########################################################################
 */


/**
 * Controller_Users 控制器
 */
class Controller_Users extends Controller_Abstract
{

	function actionIndex()
	{
		//获取过滤项的值
		$user = $this->_app->currentUser();
		//$sql_where = "sims_lev=0 and finish=0";
		$sql_where = "sims_lev=0";
		$qstatus = empty($this->_context->qstatus)?$this->_context->qstatus:trim($this->_context->qstatus);
		$q_stuname= empty($this->_context->q_stuname)?$this->_context->q_stuname:trim($this->_context->q_stuname);
        if ($qstatus || $qstatus==='0') {
            $qstatus = (int)$qstatus;
            $sql_where .= " and [valid]=$qstatus";
		    $this->_view['qstatus'] = $qstatus;
        }
      	if(isset($q_stuname)&&$q_stuname!=""){
            $sql_where .= " and [name] like '%".s($q_stuname)."%'";
		    $this->_view['q_stuname'] = $q_stuname;
		}
		/****以下是毕业状态的筛选 2014-08-05**********************************************************************************************************/
		$finish_type = empty($this->_context->finish_type)?$this->_context->finish_type:trim($this->_context->finish_type);
		//echo $qstatus.'<br />';
		if($finish_type == 1){
			$sql_where .= " and finish = '1'";
		}elseif($finish_type == 2){
			$sql_where .= " and finish = '0' and unpass_value = 0";	
		}elseif($finish_type == 3){
			$sql_where .= " and finish = '0' and unpass_value <> 0";	
		}
		$this->_view['finish_type'] = $finish_type;
		/****以上是毕业状态的筛选 2014-08-05**********************************************************************************************************/
		$sql_where = Control_StuSearch::filterCond($sql_where);
		if($user['level']==3){//主考院校用户
			$sql_where .= " and [training_id]=".$user['orgid'];
		}else if($user['level']==2 || $user['level']==4){//学习中心用户
			$sql_where .= " and [college_id]=".$user['orgid'];
		}
		$page = (int)$this->_context->page;
		if ($page==0) $page++;
		$limit = $this->_context->limit ? $this->_context->limit : 15;
		$q = Users::find($sql_where)->joinInner('sms_enroll', 'name as ename', 'core_user.enroll_id=sms_enroll.id')->joinInner('sms_org', 'name as oname', 'core_user.college_id=sms_org.id')->joinInner('sms_org', 'name as o2name', 'core_user.training_id=sms_org_2.id')->joinInner('sms_class', 'name as cname', 'core_user.classid=sms_class.id')->joinInner('sms_discipline', 'name as dname', 'core_user.discipline_id=sms_discipline.id')->order(array(new QDB_Expr('sms_enroll.name'),new QDB_Expr('sms_org.name'),new QDB_Expr('sms_org_2.name'),new QDB_Expr('sms_class.name'),new QDB_Expr('[name]')))->limitPage($page, $limit);
		$this->_view['pager'] = $q->getPagination();
		$this->_view['list'] = $q->getAll();
		
		$this->_view['start'] = ($page-1)*$limit;
		$this->_view['subject'] = '学生管理';
	}

    function actionExport() {
		$user = $this->_app->currentUser();
		$sql_where = "[sims_lev]=0";
		$sql_where = Control_StuSearch::filterCond($sql_where);
		/****以下是毕业状态的筛选 2014-08-05**********************************************************************************************************/
		$finish_type = empty($this->_context->finish_type)?$this->_context->finish_type:trim($this->_context->finish_type);
		//echo $qstatus.'<br />';
		if($finish_type == 1){
			$sql_where .= " and finish = '1'";
		}elseif($finish_type == 2){
			$sql_where .= " and finish = '0' and unpass_value = 0";	
		}elseif($finish_type == 3){
			$sql_where .= " and finish = '0' and unpass_value <> 0";	
		}
		/****以上是毕业状态的筛选 2014-08-05**********************************************************************************************************/
		if($user['level']==3){//主考院校用户
			$sql_where .= " and [training_id]=".$user['orgid'];
		}else if($user['level']==2 || $user['level']==4){//学习中心用户
			$sql_where .= " and [college_id]=".$user['orgid'];
		}
		$sql_where .= @strlen($q_stuname) ? " and name like '%{$q_stuname}%'" : "";
		$list = Users::find($sql_where)->joinInner('sms_enroll', 'name as ename', 'core_user.enroll_id=sms_enroll.id')->joinInner('sms_org', 'name as oname', 'core_user.college_id=sms_org.id')->joinInner('sms_org', 'name as o2name', 'core_user.training_id=sms_org_2.id')->joinInner('sms_class', 'name as cname', 'core_user.classid=sms_class.id')->joinInner('sms_discipline', 'name as dname', 'core_user.discipline_id=sms_discipline.id')->order(array(new QDB_Expr('sms_enroll.name'),new QDB_Expr('sms_org.name'),new QDB_Expr('sms_org_2.name'),new QDB_Expr('sms_class.name'),new QDB_Expr('sms_discipline.name'),new QDB_Expr('[name]')))->getAll();
        $model = 'users';
        $filename = '学生管理'.date('YmdHis').'.xls';
        $sheetname = '学生管理';
        Helper_Util::export_form_excel_exceptarr($model, $list, $filename, $sheetname,null,array("pass","pass1"));
    }

	function actionExportFeeModel()
	{
		$user = $this->_app->currentUser();
		$sql_where = "[sims_lev]=0";
		$sql_where = Control_StuSearch::filterCond($sql_where);
		if($user['level']==3){//主考院校用户
			$sql_where .= " and [training_id]=".$user['orgid'];
		}else if($user['level']==2 || $user['level']==4){//学习中心用户
			$sql_where .= " and [college_id]=".$user['orgid'];
		}
		$sql_where .= @strlen($q_stuname) ? " and name like '%{$q_stuname}%'" : "";
		$list = Users::find($sql_where)->joinInner('sms_enroll', 'name as ename', 'core_user.enroll_id=sms_enroll.id')->joinInner('sms_org', 'name as oname', 'core_user.college_id=sms_org.id')->joinInner('sms_org', 'name as o2name', 'core_user.training_id=sms_org_2.id')->joinInner('sms_class', 'name as cname', 'core_user.classid=sms_class.id')->joinInner('sms_discipline', 'name as dname', 'core_user.discipline_id=sms_discipline.id')->order(array(new QDB_Expr('sms_enroll.name'),new QDB_Expr('sms_org.name'),new QDB_Expr('sms_org_2.name'),new QDB_Expr('sms_class.name'),new QDB_Expr('sms_discipline.name'),new QDB_Expr('[name]')))->getAll();
		$this->_view['list'] = $list;
	}
	
	function actionGetclassinfo(){
		$type = $_POST['type'];
		$infoid = $_POST['infoid'];
		$infocid = $_POST['infocid'];
		$sql_where = "";
		if($type==1){//主考院校
			if(!empty($infoid)){
				$sql_where = " and college_id=".$infoid;
			}
		}else if($type==2){
			if(!empty($infoid)){
				$sql_where = " and training_id=".$infocid;
			}else{
				if(!empty($infocid)){
					$sql_where = " and college_id=".$infocid;
				}
			}
		}
		$class_info = Classinfo::find(" isdelete=0 ".$sql_where)->getAll()->toArray();
		$return_info = json_encode($class_info);
		echo $return_info;
		exit;
	}
	
	function actionEdit(){
		// 查询指定 ID
		$id = $this->_context->idst;
		$user = Users::find('idst = ?', $id)->query();
		$user_info = $this->_app->currentUser();
		$level = $user_info['level'];
		// 构造表单对象
		$form = new Form_Users('');
		// 修改表单标题
		$this->_view['subject'] = "学生管理";
		$form->import($user);
		if ($this->_context->isPOST() && $form->validate($_POST)){
			$cbox = @$_POST['cbox'];//绑定学生的课程
			$copen = @$_POST['open'];//绑定学生的课程开放
			if (!$cbox) {
				$this->_view['diserr'] = '学生对应课程不能为空';
				$isvalid = false;
			} else {
				$data = $form->values();				
				// changeProps() 方法可以批量修改对象的属性，但不会修改只读属性的值
			

				$user->changeProps($data);
                if(!empty($data['pass1'])){
                    $user['pass'] = md5($data['pass1']);
                }
				$user->save();
				//课程附加的时候不能批量删除后重新添加，需要进行过滤
				$uc_list = Usercourse::find('userid=?',$user->idst)->getAll();
				$uc_id_list = array();
				foreach ($uc_list as $key => $uc) {
					$uc_id_list[] = $uc->courseid;
				}
				if(isset($cbox)){
					$unbind_courseid = array_diff($uc_id_list, array_intersect($uc_id_list,$cbox));//获取post中没有勾选的课程，但是在原来的绑定表中却又，需要删除
					foreach($unbind_courseid as $k=>$v){
						$uc = Usercourse::find('userid=? and courseid=?',$user->idst,$v)->getOne();
						$uc->destroy();
					}
					$bind_courseid = array_diff($cbox, array_intersect($uc_id_list,$cbox));//获取post中新需要绑定的课程
					foreach($bind_courseid as $k=>$v){
						$openflag = 0 ;
						if(!empty($copen)&&in_array($v, $copen)){$openflag = 1 ;}
						$uc = new Usercourse();
						$uc->userid = $user->idst;
						$uc->class_id = $user['classid'];
                    	$uc->training_id = $user['training_id'];
						$uc->courseid = $v;
						$uc->openflag = $openflag;
						$uc->username = $user->userid;
						$uc->back_only = $user->classinfo->get_back_only($v);
						$uc->save();
					}
				}
				//设置开课状态
				$db = QDB::getConn();
				$sql = "update lc_courseuser set openflag=0 where userid=".$user->idst;
				$db->execute($sql);
				if(!empty($copen)){
					$sql = "update lc_courseuser set openflag=1 where userid=".$user->idst." and courseid in (".implode(',', $copen).")";
					$db->execute($sql);
				}
				if($this->_context->backurl){
					return $this->_redirect($this->_context->backurl);
				}else{
					return $this->_redirect(url('users/'));    
				}
			}
		}elseif ($this->_context->isPOST()){
		
		}elseif (!$this->_context->isPOST()){
			// 如果不是 POST 提交，则把对象值导入表单
		} else {
			$cbox = @$_POST['cbox'];//绑定学生的课程
			if (!$cbox) {
				$this->_view['diserr'] = '学生对应课程不能为空';
				$isvalid = false;
			}
		}
		$this->_view['form'] = $form;
        if($this->_context->backurl){
            $this->_view['backurl'] = $this->_context->backurl;
        }
		// 重用 create 动作的视图
		$this->_viewname = 'create';
	}
	
	function actionGetNameByID(){
		$userID = $_POST['infoid'];
		//如果是学习中心 或者主考院校的话 先获取所有的自己的学生 
		$user_admin = $this->_app->currentUser();
		$info_user = "";
		if($user_admin['level']==2 || $user_admin['level']==4){//主考院校
			$userList = Users::find("college_id=".$user_admin['orgid'])->order('name')->setColumns("userid")->getAll();
			foreach ($userList as $k=>$v){
				$info_user .= "'".s($v->userid)."',";
			}
		}else if($user_admin['level']==3){
			$userList = Users::find("training_id=".$user_admin['orgid'])->order('name')->setColumns("userid")->getAll();
			foreach ($userList as $k=>$v){
				$info_user .= "'".s($v->userid)."',";
			}
		}
		$sql_where="";
		if($info_user!=""){
			$sql_where = " and userid in (".mb_substr($info_user, 0,mb_strlen($info_user)-1).")";
		}
		$user_info = Users::find("userid='".$userID."'".$sql_where)->order('name')->setColumns("idst,name,discipline_id")->getOne();
		$return_name = "";
		$return_discipline = array();
		$flag = "T";
		if(!empty($user_info->idst)){
			$return_name = $user_info->name;
            $sql = "select sce.id as id,sce.name as name from lc_courseuser as uc,lc_course as sce where sce.liberty_type=0 and uc.userid='".$user_info->idst."' and uc.courseid=sce.id";
        	$list_temp = QDB::getConn()->getAll($sql);
            foreach($list_temp as $k=>$v){
                $return_discipline[$v['id']] = $v['name'];
            }
			asort($return_discipline);
		}else{
			$return_name = "登录账号不存在或非系统确认用户,请重新输入。";
			$flag = "F";
		}
		$return_info = json_encode(array("username"=>$return_name,"flag"=>$flag,"discipline"=>$return_discipline));
		echo $return_info;
		exit;
	}
	
	function actionDelete(){
		$user = Users::find("idst=".$this->_context->idst)->query();
		$userid = $user->userid;
		$db=QDB::getConn();
		$sql = "delete from lc_courseuser where userid = '".$this->idst."'";//生成idst 获取并返回
		$db->execute($sql);
		Feedg::meta()->destroyWhere('userid = ? ',$userid);
		Feeincome::meta()->destroyWhere('userid = ? ',$userid);
		$user->destroy();
		return $this->go_back(0);
	}
	
    function actionDongjie(){        
        $user = Users::find("idst=".$this->_context->idst)->getOne();
        $user->valid = -1;
        $user->save();	
		return $this->_redirect(url('users'));
    }

	function actionJiechu(){
		$user = Users::find("idst=".$this->_context->idst)->getOne();
		$user->valid=1;
		$user->save();
		return $this->_redirect(url('users'));
	}
	
	function actionview(){
		$user = Users::find("idst=".$this->_context->idst)->query();
		$this->_view['users'] = $user;
		$this->_view['subject'] = "学员查看";
		$this->_view['backurl'] = $this->_context->backurl;
		
        $user_course_list = Usercourse::find("userid=?",$user->idst)->getAll();
        $usercourse_list = array();
        foreach($user_course_list as $k => $v){
            $usercourse_list[] = $v->courseid;
        }
        
        $discipline_id = $user->discipline_id;//专业id
		$dis = Discipline::find("id=?",$discipline_id)->getOne();
		$course_list_temp = $dis->dis_courses;//获取专业对应的课程
		//dump($course_list_temp);
		$course_list = array();//课程列表
		$refcode = Refcode::find("code=?","COURSE_TYPE")->order("name")->getAll();//获取ref_code
		$return_txt = "<br/><table class='list_table_course' width='100%' cellpadding='0' cellspacing='0' border='1'><tr><th colspan=6>学生对应课程</th></tr><tr><th style='width:80px;'>课程类型</th><th style='width:80px;'>课程代码</th><th style='width:80px;'>开课状态</th><th>课程名称</th><th style='width:40px;'>学分</th><th style='width:80px;'>考试性质</th></tr>";
		$course_list = array();
		foreach ($course_list_temp as $k=>$v) {//创建2维数组
			$course_list[$v->ctype][]=$v->course;
		}
		//dump($course_list);
		$uc_list = Usercourse::find("userid=? and openflag=1",$user->idst)->getAll();
        $open_arr = array();
        foreach ($uc_list as $key => $uc) {
        	$open_arr[] = $uc['courseid'];
        }

		$total_score = 0;
		foreach ($refcode as $key=>$value){//完成所有的加载
			if($value->name!=5&&$value->name!=4){
				if(isset($course_list[$value->name])){
				    $return_txt_body = "";
                    $count_course = 0;
                    $temp_count = 0;
					foreach ($course_list[$value->name] as $k=>$v){
                        if(in_array($v->id,$usercourse_list)){
                            $count_course ++ ;
    						$total_score += (int)($v->cscore==null?0:$v->cscore);
    						if($temp_count == 0){
    							$return_txt_body.= "<td style='width:100px;'>".$v->code."</td><td style='width:100px;'>".(in_array($v->id, $open_arr)?"已开课":"未开课")."</td><td>".$v->name."</td><td style='width:60px;'>".$v->cscore."</td><td style='width:100px;'>".$v->type."</td></tr>";
    							$temp_count ++;
    						}else{
    							$return_txt_body.= "<tr><td style='width:100px;'>".$v->code."</td><td style='width:100px;'>".(in_array($v->id, $open_arr)?"已开课":"未开课")."</td><td>".$v->name."</td><td style='width:60px;'>".$v->cscore."</td><td style='width:100px;'>".$v->type."</td></tr>";
    						}
						}
					}
                    if($return_txt_body!=""){
                        $return_txt .="<tr><td style='width:100px;' rowspan=".$count_course.">".$value->long_desc."</td>".$return_txt_body;
                    }
				}
			}
		}
		$return_txt .= "<tr><td colspan=4 style='font-weight:bold;'>合计</td><td style='width:60px;font-weight:bold;'>".$total_score."</td><td style='width:100px;'>&nbsp;</td></tr>";
		
        if(isset($course_list[4])){//加考课1
            $value_info = $refcode[3];
            $count_course = 0;
            $temp_count = 0;
            $return_txt_body = "";
			foreach ($course_list[4] as $k=>$v){
                if(in_array($v->id,$usercourse_list)){
                    $count_course ++;
                    $total_score += (int)($v->cscore==null?0:$v->cscore);
    				if($temp_count == 0){
    					$return_txt_body.= "<td style='width:100px;'>".$v->code."</td><td style='width:100px;'>".(in_array($v->id, $open_arr)?"已开课":"未开课")."</td><td>".$v->name."</td><td style='width:60px;'>".$v->cscore."</td><td style='width:100px;'>".$v->type."</td></tr>";
    				}else{
    					$return_txt_body.= "<tr><td style='width:100px;'>".$v->code."</td><td style='width:100px;'>".(in_array($v->id, $open_arr)?"已开课":"未开课")."</td><td>".$v->name."</td><td style='width:60px;'>".$v->cscore."</td><td style='width:100px;'>".$v->type."</td></tr>";
    				}
                    $temp_count ++;
                }
			}
            if($return_txt_body!=""){
                $return_txt .="<tr><td rowspan=".$count_course.">".$value_info->long_desc."</td>".$return_txt_body;
            }
        }

        if(isset($course_list[5])){//加考课1
            $value_info = $refcode[4];
            $count_course = 0;
            $temp_count = 0;
            $return_txt_body = "";
			foreach ($course_list[5] as $k=>$v){
                if(in_array($v->id,$usercourse_list)){
                    $count_course ++;
                    $total_score += (int)($v->cscore==null?0:$v->cscore);
    				if($temp_count == 0){
    					$return_txt_body.= "<td style='width:100px;'>".$v->code."</td><td style='width:100px;'>".(in_array($v->id, $open_arr)?"已开课":"未开课")."</td><td>".$v->name."</td><td style='width:60px;'>".$v->cscore."</td><td style='width:100px;'>".$v->type."</td></tr>";
    				}else{
    					$return_txt_body.= "<tr><td style='width:100px;'>".$v->code."</td><td style='width:100px;'>".(in_array($v->id, $open_arr)?"已开课":"未开课")."</td><td>".$v->name."</td><td style='width:60px;'>".$v->cscore."</td><td style='width:100px;'>".$v->type."</td></tr>";
    				}
                    $temp_count ++;
                }
			}
            if($return_txt_body!=""){
                $return_txt .="<tr><td rowspan=".$count_course.">".$value_info->long_desc."</td>".$return_txt_body;
            }
        }

		$return_txt .="</table>";
		
        $this->_view['course_table']=$return_txt;
	}
	
	function actiongetIDByName(){
		$userName = $_POST['infoid'];//如果是学习中心 或者主考院校的话 先获取所有的自己的学生 
		$user_admin = $this->_app->currentUser();
		$info_user = "";
		if($user_admin['level']==2 || $user_admin['level']==4){//主考院校
			$userList = Users::find("college_id=".$user_admin['orgid'])->setColumns("userid")->getAll();
			foreach ($userList as $k=>$v){
				$info_user .= "'".$v->userid."',";
			}
		}else if($user_admin['level']==3){
			$userList = Users::find("training_id=".$user_admin['orgid'])->setColumns("userid")->getAll();
			foreach ($userList as $k=>$v){
				$info_user .= "'".$v->userid."',";
			}
		}
		$sql_where="";
		if($info_user!=""){
			$sql_where = " and userid in (".mb_substr($info_user, 0,mb_strlen($info_user)-1).")";
		}
		$user_info = Users::find("sims_lev=0 and name='".$userName."'".$sql_where)->setColumns("idst,userid,discipline_id")->getAll();
		$return_name = "";
		$return_discipline = array();
		$flag = "T";
		if(count($user_info)==1){
			$return_name = $user_info[0]->userid;
            $sql = "select sce.id as id,sce.name as name from lc_courseuser as uc,lc_course as sce where uc.userid='".$user_info[0]->idst."' and uc.courseid=sce.id";
            $list_temp = QDB::getConn()->getAll($sql);
            foreach($list_temp as $k=>$v){
                $return_discipline[$v['id']] = $v['name'];
            }
		}else{
			if(count($user_info)>1){
				$userids = "";
				foreach ($user_info as $k=>$v){
					$userids .=$v->userid.",";
				}
				$return_name = "登录账号重复,请填入正确的登录账号[".mb_substr($userids,0,mb_strlen($userids)-1)."]。";
				$flag = "D";
			}else{
				$return_name = "登录账号不存在或非系统确认用户,请重新输入。";
				$flag = "F";
			}
		}
		$return_info = json_encode(array("userid"=>$return_name,"flag"=>$flag,"discipline"=>$return_discipline));
		echo $return_info;
		exit;
	}
	// 把学生毕业或者取消毕业	
	function actionDofinish(){
		$cid			= $_REQUEST['cid'];
		$finish_flag	= $_REQUEST['finish_flag']; 
		$cid_array		= explode(',',$cid);
		//print_r($cid_array);
		if(empty($cid_array[0])){
			unset($cid_array[0]);	
		}
		//print_r($cid_array);
		$idst = implode("','",$cid_array);
		$sql_where = "idst in ('$idst') "; 
		if($finish_flag == 1){
			$sql_where .= " and unpass_value = '0'";
		}
		$sql_update = "update core_user set finish ='$finish_flag' where ".$sql_where;
		//echo $sql_update;
		QDB::getConn()->execute($sql_update);
		$success = 1;
		echo json_encode(array('ok'=>$success));
		exit();
	}
	//更新学生的通过门数和未通过门数
	function actionDopassed(){
		//$runtime_start = microtime(true);
		//echo $runtime_start.'<br />';
		$user_list = Users::find()->getAll();
		//echo 'OK<br />';
		//dump($user_list);
		
			
		foreach($user_list as $k => $v){
			if($v->pass_value != $v->pass_number || $v->unpass_value != $v->unpass_number){
				$sql_update = "update core_user set pass_value ='".$v->pass_number."',unpass_value ='".$v->unpass_number."'  where idst = '".$v->idst."'";
				//echo $sql_update;
				//echo $v->idst;
				//echo '<br />';
				QDB::getConn()->execute($sql_update);
			}
		}
		//成绩通过未通过批量修改
		//"及格","申请免考","通过","合格","免考"
		$score_list = Score::find("passed = '0'")->getAll(); 
		foreach($score_list as $k => $v){
			if($v->score == '及格' || $v->score == '申请免考' || $v->score == '通过' || $v->score == '合格' || $v->score == '免考' || $v->score >= 60 || $v->isreplace == 2){
				$sql_update = "update sms_score set passed = 1 where id = '".$v->id."'";
				//echo $sql_update;
				//echo '<br />';
				QDB::getConn()->execute($sql_update);
			}
		}
		//$runtime_stop = microtime(true);
		//echo $runtime_stop.'<br />';
		//echo $runtime_stop-$runtime_start;
		//echo "<!-- Processed in ".round($runtime_stop-$runtime_start,6)." second(s) -->";
		
		$success = 1;
		echo json_encode(array('ok'=>$success));
		exit();
	}
	function actionDosyn(){
		$cid = $_REQUEST['cid'];
		$cid_array = explode(',',$cid);
		//print_r($cid_array);
		if(empty($cid_array[0])){
			unset($cid_array[0]);	
		}
		//print_r($cid_array);
		$idst = implode("','",$cid_array);
		//$userData = Users::find("idst in('$idst')")->getAll()->toArray();
		$userData = Users::find("idst in('$idst')")->getAll();//->toArray();
		//print_r($userData);
		//exit();
		//dump($userData);
		//$cid = '';
		//$cid_array = array();
		if(!empty($cid)){
			include(dirname(dirname(dirname(dirname(__FILE__)))).DIRECTORY_SEPARATOR.'datasyn'.DIRECTORY_SEPARATOR.'Snoopy.class.php');
			$snoopy = new Snoopy;
	
			$courseData = Course::find('isdelete = 0')->getAll()->toHashMap('code','id');
			//dump($courseData);
			//$userData = array(1=>array('cid'=>'330382199311300931'),2=>array('cid'=>'330302198607035229'));
			
			$submit_vars["name"] = "330302198607035229";
			//$submit_vars["type"] = iconv('utf-8','GB2312',"合格成绩");	//老地址是GB2312,需要转编码
			//$submit_vars["zkOk"] = iconv('utf-8','GB2312',"确定");		//老地址是GB2312,需要转编码
			$submit_vars["type"] = "合格成绩";
			$submit_vars["zkOk"] = " 确定 ";
			
			
			foreach($userData as $k_u => $v_u){
				//抓取的课程列表	
				$myData = array();
				//得到课程计划中该用户所有的应学的课程
				$dis_temp 	= $v_u->discipline->dis_courses;
				$my_all_course = array();
				foreach($dis_temp as $k_dis_temp => $v_dis_temp){
					$my_all_course[$v_dis_temp->course->id]['code'] 	= $v_dis_temp->course->code;
					$my_all_course[$v_dis_temp->course->id]['name'] 	= $v_dis_temp->course->name;
				}
				//dump($my_all_course);
				//得到该用户所有已经录入成绩的课程
				$my_scoreData = array();
				$my_scoreData_temp = Score::find(" userid = '".$v_u['userid']."' ") -> getAll(); 
				foreach($my_scoreData_temp as $k_score_temp => $v_score_temp){
					$my_scoreData[$v_score_temp->courseid]['id']		= $v_score_temp->id;
					$my_scoreData[$v_score_temp->courseid]['score']		= $v_score_temp->score;
					$my_scoreData[$v_score_temp->courseid]['passed']	= $v_score_temp->passed;
					$my_scoreData[$v_score_temp->courseid]['isreplace']	= $v_score_temp->isreplace;
				}
				//dump($my_scoreData);
				if($v_u->cid){
					//array_push($cid_array,$v_u);
					//$submit_url		= "http://www.zjzk.cn/servlet/zjzk.GetZkScore";//老地址
					$submit_url			= "http://www.zjks.net/app/portal/zkcx/getZkScore.htm";//新地址
					$submit_vars["name"] = $v_u->cid;
					//$snoopy->cookies["JSESSIONID"] = 'abcfggvY5mKBigo2kmwxu';
					//$snoopy->set_submit_multipart();
					$snoopy->submit($submit_url,$submit_vars);
					$html	= $snoopy->results;
					$html=preg_replace("/\s+/", "", $html); //过滤多余回车 注：老地址不需要这个
					//print_r($html);
					//$reg = "|<tr>\n*<td class=\"a1\"><div align=\"center\">\n*(.*)\n*<\/div><\/td>\n*<td class=\"a1\"><div align=\"center\">\n*(.*)\n*<\/div><\/td>\n*<td class=\"a1\"><div align=\"left\">\n*(.*)\n*<\/div><\/td>\n*<td class=\"a1\"><div align=\"center\">\n*(.*)\n*<\/div><\/td>\n*<td class=\"a1\"><div align=\"center\">\n*(.*)\n*<\/div><\/td>\n*<td class=\"a1\"><div align=\"center\">\n*(.*)\n*<\/div><\/td>\n*<\/tr>|U";//老地址的正则
					$reg = "|<tr><!--序号--><tdclass=\"a1\"><divalign=\"center\">(.*)<\/div><\/td><!--课程代码--><tdclass=\"a1\"><divalign=\"center\">(.*)<\/div><\/td><!--课程名称--><tdclass=\"a1\"><divalign=\"left\">(.*)<\/div><\/td><!--成绩--><tdclass=\"a1\"><divalign=\"center\">(.*)<\/div><\/td><!--实得学分和专业代码--><!--考试时间--><tdclass=\"a1\"><divalign=\"center\">(.*)<\/div><\/td><tdclass=\"a1\"><divalign=\"center\">(.*)<\/div><\/td><\/tr>|U";//新地址的正则
					//$reg = "|<tr><td class=\"a1\"><div align=\"center\">(.+)<\/div><\/td><td class=\"a1\"><div align=\"center\">(.+)<\/div><\/td><td class=\"a1\"><div align=\"left\">(.+)<\/div><\/td><td class=\"a1\"><div align=\"center\">(.+)<\/div><\/td><td class=\"a1\"><div align=\"center\">(.+)<\/div><\/td><td class=\"a1\"><div align=\"center\">(.+)<\/div><\/td><\/tr>|U";
					//$reg = "|<tr>\n(.*)<\/tr>|U";
					//$reg = "|<tr>\n<td class=\"a1\"><div align=\"center\">\n(.+)\n<\/div><\/td><td class=\"a1\"><div align=\"center\">\n(.+)\n<\/div><\/td><td class=\"a1\"><div align=\"left\">\n(.+)\n<\/div><\/td>\n<td class=\"a1\"><div align=\"center\">\n(.+)<\/div><\/td>\n<td class=\"a1\"><div align=\"center\">\n(.+)\n<\/div><\/td>\n<td class=\"a1\"><div align=\"center\">\n(.+)\n<\/div><\/td>\n<\/tr>|U";
					preg_match_all($reg,$html,$out,PREG_PATTERN_ORDER);
					//dump($out);
					/****筛选出课程编号相同分数最高的项 Start ************************************************************/	
					$arr1 = $out[2];
					$arr2 = $arr1;
					$arr3 = $out[4];
					$arr5 = $out[5];
					$arr6 = $out[6];
					foreach($arr1 as $k => $v){
						foreach($arr2 as $key => $val){
							if($k != $key && $v == $val){
								if(array_key_exists($k,$arr3) && array_key_exists($key,$arr3)){
									if($arr3[$k] >= $arr3[$key]){
										unset($arr1[$key]);
										unset($arr3[$key]);		
									}else{
										unset($arr1[$k]);
										unset($arr3[$k]);	
									}
								}
							}	
						}	
					}
					//print_r($arr1);
					//print_r($arr3);
					foreach($arr1 as $k_code => $v_code){
						if(array_key_exists($v_code,$courseData)){
							array_push($myData,array('userid'=>$v_u->cid,'courseid'=>$courseData[$v_code],'score'=>$arr3[$k_code],'examdate'=>$arr5[$k_code]));	
						}else{
							if(strlen($v_code) == 4){
								$v_code = '0'.$v_code;
								if(array_key_exists($v_code,$courseData)){
									array_push($myData,array('userid'=>$v_u->cid,'courseid'=>$courseData[$v_code],'score'=>$arr3[$k_code],'examdate'=>$arr5[$k_code]));	
								}
							}
						}	
					}
					/****筛选出课程编号相同分数最高的项 End *************************************************************/
				}
				
				if($v_u->eid){
					//array_push($cid_array,$v_u);
					//$submit_url		= "http://www.zjzk.cn/servlet/zjzk.zkGrade";	//老地址
					$submit_url		= "http://www.zjks.net/app/portal/zkcx/zkGrade.htm";
					$submit_vars["name"] = $v_u->eid;
					//$snoopy->cookies["JSESSIONID"] = 'abcfggvY5mKBigo2kmwxu';
					//$snoopy->set_submit_multipart();
					$snoopy->submit($submit_url,$submit_vars);
					$html	= $snoopy->results;
					$html=preg_replace("/\s+/", "", $html); //过滤多余回车 注：老地址不需要这个
					//print_r($html);
					//$reg = "|<tr>\n*<td><div align=\"center\"><span class=\"a1\">\n*(.*)\n*<\/span><\/div><\/td>\n*<td><div align=\"center\"><span class=\"a1\">\n*(.*)\n*<\/span><\/div><\/td>\n*<td><div align=\"center\"><span class=\"a1\">\n*(.*)\n*<\/span><\/div><\/td>\n*<td><div align=\"center\"><span class=\"a1\">\n*(.*)\n*<\/span><\/div><\/td>\n*<td><div align=\"center\"><span class=\"a1\">\n*(.*)\n*<\/span><\/div><\/td>\n*<\/tr>|U"; //老地址的正则
					$reg = "|<trclass=\".*\"onMouseOver=\"this.className='hover'\"onMouseOut=\"this.className='.*'\"><td><divalign=\"center\">(.*)<\/div><\/td><td><divalign=\"center\">(.*)<\/div><\/td><td><divalign=\"center\">(.*)<\/div><\/td><td><divalign=\"center\">(.*)<\/div><\/td><td><divalign=\"center\">(.*)<\/div><\/td><\/tr>|U";
					//$reg = "|<tr><td class=\"a1\"><div align=\"center\">(.+)<\/div><\/td><td class=\"a1\"><div align=\"center\">(.+)<\/div><\/td><td class=\"a1\"><div align=\"left\">(.+)<\/div><\/td><td class=\"a1\"><div align=\"center\">(.+)<\/div><\/td><td class=\"a1\"><div align=\"center\">(.+)<\/div><\/td><td class=\"a1\"><div align=\"center\">(.+)<\/div><\/td><\/tr>|U";
					//$reg = "|<tr>\n(.*)<\/tr>|U";
					//$reg = "|<tr>\n<td class=\"a1\"><div align=\"center\">\n(.+)\n<\/div><\/td><td class=\"a1\"><div align=\"center\">\n(.+)\n<\/div><\/td><td class=\"a1\"><div align=\"left\">\n(.+)\n<\/div><\/td>\n<td class=\"a1\"><div align=\"center\">\n(.+)<\/div><\/td>\n<td class=\"a1\"><div align=\"center\">\n(.+)\n<\/div><\/td>\n<td class=\"a1\"><div align=\"center\">\n(.+)\n<\/div><\/td>\n<\/tr>|U";
					preg_match_all($reg,$html,$out,PREG_PATTERN_ORDER);
					//print_r($out);
					/****筛选出课程编号相同分数最高的项 Start ************************************************************/	
					$arr1 = $out[3];
					$arr2 = $arr1;
					$arr3 = $out[5];
					//$arr5 = $out[6];
					foreach($arr1 as $k => $v){
						foreach($arr2 as $key => $val){
							if($k != $key && $v == $val){
								if(array_key_exists($k,$arr3) && array_key_exists($key,$arr3)){
									if($arr3[$k] >= $arr3[$key]){
										unset($arr1[$key]);
										unset($arr3[$key]);		
									}else{
										unset($arr1[$k]);
										unset($arr3[$k]);	
									}
								}
							}	
						}	
					}
					//print_r($arr1);
					//print_r($arr3);
					foreach($arr1 as $k_code => $v_code){
						if(array_key_exists($v_code,$courseData)){
							array_push($myData,array('userid'=>$v_u->cid,'courseid'=>$courseData[$v_code],'score'=>$arr3[$k_code],'examdate'=>''));	
						}else{
							if(strlen($v_code) == 4){
								$v_code = '0'.$v_code;
								if(array_key_exists($v_code,$courseData)){
									array_push($myData,array('userid'=>$v_u->cid,'courseid'=>$courseData[$v_code],'score'=>$arr3[$k_code],'examdate'=>''));	
								}
							}
						}	
					}
					/****筛选出课程编号相同分数最高的项 End *************************************************************/
				}
				//print_r($out);
				/******接下来进行数据同步**************************************************************/
				foreach($myData as $k => $v){
					//$hg = '合格';
					//$scoreData = $myModel -> get_one('id,userid,uname,courseid,score','sms_score'," where userid = '".$v['userid']."' and courseid = '".$v['courseid']."' and score <> '$hg'and score <> '".$v['score']."' ");
					//$scoreData = Score::find(" userid = '".$v['userid']."' and courseid = '".$v['courseid']."' and score <> '$hg' and score <> '".$v['score']."' ") -> getOne(); 
					$sql_update = '';
					if(array_key_exists($v['courseid'], $my_all_course) && array_key_exists($v['courseid'], $my_scoreData)){
						//dump(strcmp($v['score'],'免'));	
						
						//echo ord(strip_tags($v['score']));
						//echo '  ||  ';
						//echo '<br />';
						//echo ord('免');
						//echo '<br />';
						if(strip_tags($v['score']) == '免'){
							//echo $v['score'];
							//if($my_scoreData[$v['courseid']]['isreplace'] != 2){
								$sql_update = "update sms_score set passed = '1',isreplace = '2' where id ='".$my_scoreData[$v['courseid']]['id']."'";
							//}
						}else{
							if($v['score'] != $my_scoreData[$v['courseid']]['score']){
								if($v['score'] < 60){
									$passed = 0;
								}else{
									$passed = 1;
								}	
								$sql_update = "update sms_score set score ='".$v['score']."',passed = '".$passed."' where id ='".$my_scoreData[$v['courseid']]['id']."'";
							}
						}
					}elseif(array_key_exists($v['courseid'], $my_all_course)){
						$i_score 		= strip_tags($v['score']);
						$i_passed 		= 0;
						$i_isreplace 	= 0;
						$i_examdate		= '';
						if($i_score == '免'){
							$i_isreplace = 2;
						}else{
							if($v['score'] < 60){
								$i_passed = 0;
							}else{
								$i_passed = 1;
							}
						}
						if(!empty($v['examdate'])){
							$i_examdate = strtotime(substr($v['examdate'],0,4).'-'.substr($v['examdate'],4,2));
							//echo substr($v['examdate'],0,4).'-'.substr($v['examdate'],4,2);
							//echo '<br />';
							//echo date('Y-m-d',$i_examdate);
							//echo '<br />';
						}
						$sql_update = " insert into sms_score set userid='".$v_u->userid."',uname='".$v_u->name."',courseid='".$v['courseid']."',score='$i_score',passed='$i_passed',isreplace='$i_isreplace',examdate='$i_examdate'";
					}
					if(!empty($sql_update)){
						//echo $sql_update;
						//echo '<br />';
						QDB::getConn()->execute($sql_update);
					}
				}
				/******以上进行数据同步**************************************************************/
			}
			//print_r($myData);
			//exit();
			/******接下来进行数据同步**************************************************************
			foreach($myData as $k => $v){
				//$hg = '合格';
				//$scoreData = $myModel -> get_one('id,userid,uname,courseid,score','sms_score'," where userid = '".$v['userid']."' and courseid = '".$v['courseid']."' and score <> '$hg'and score <> '".$v['score']."' ");
				//$scoreData = Score::find(" userid = '".$v['userid']."' and courseid = '".$v['courseid']."' and score <> '$hg' and score <> '".$v['score']."' ") -> getOne(); 
				if(array_key_exists($v['courseid'], $my_all_course))
				if($scoreData->id){
					//print_r($scoreData);
					//print_r($v);
					//$myModel -> do_update(array('score' => $v['score']),'sms_score',array('id'=>$scoreData['id']));
					if($v['score'] < 60){
						$passed = 0;
					}else{
						$passed = 1;
					}
					$sql_update = "update sms_score set score ='".$v['score']."',passed = '".$passed."' where id ='".$scoreData->id."'";
					QDB::getConn()->execute($sql_update);
				}
			}
			/******以上进行数据同步**************************************************************/
			//$update_date_last = time();
			//print_r($cid_array);
			//$cid = implode("','",$cid_array);
			//echo $cid;
			//$sql_update = "update core_user set update_times = update_times+1,update_date_last = '$update_date_last' where idst in('$idst')";
			//echo $sql_update;
			//echo '<br />';
			//QDB::getConn()->execute($sql_update);
	
			//$snoopy->submit($submit_url,$submit_vars);
			//$html	= $snoopy->results;
			//print_r($html);
			
				
			//$cid = $_REQUEST['cid'];
			$success = 1;
		}else{
			$success = 0;
		}
		echo json_encode(array('ok'=>$success));
		exit();
	}


	/*##################################
	 * 弹框功能
	 */
	/**
	 * 导出成绩档案
	 **/
	function actionExportscore() {
		$ckbox = $_REQUEST['ckbox'];
		$type = @$_REQUEST['type'];
		if(is_array($ckbox))
			$ckbox = implode(",", $ckbox);
		$users = Users::find(" idst in ($ckbox)")->getAll();
		foreach($users as $user){
			$scores = Score::find("userid='".strtoupper($user['userid'])."'")
				->join("lc_course","","lc_course.id=sms_score.courseid and lc_course.isdelete=0")
				->order("CONVERT(lc_course.code USING GBK)")
				->getAll();
			//dump(count($scores));
			foreach($scores as $score){
				$score_userid = trim(strtoupper($score['userid']));
				if(isset($score_arr[$score_userid][$score['courseid']])) {
					$temp  = $score_arr[$score_userid][$score['courseid']];
					if($temp['isreplace'] > 0)
						continue;
				}
				$data['course_code'] = $score['course']['code'];
				$data['course_name'] = $score['course']['name'];
				$data['course_cscore'] = $score['course']['cscore'];
				$data['score'] = $score['score'];
				$data['time'] = $score['examdate']>0?date('Y-m-d',$score['examdate']):"";
				$data['place'] = $score['examadderss'];
				$data['id'] = $score['id'];
				$data['isreplace'] = $score['isreplace'];
				$score_arr[$score_userid][$score['courseid']] = $data;
				//dump($data);
			}
		}
		//dump($score_arr);
		$this->_view['users'] = $users;
		$this->_view['score_arr'] = @$score_arr;
		$this->_view['subject'] = "成绩档案";
		if($type=="show_user") {
			$this->_viewname = "showuser";
			$this->_view['course_list'] = $this->showuserCourse($ckbox);
		}elseif($type=="loadprint")
			$this->_viewname = "loadprint";
	}

	function showuserCourse($idsts=false) {
		$sql = "isdelete=0";
		if($idsts){
			$courseids = Score::find("core_user.idst in ({$idsts})")
				->join("core_user", "", "core_user.userid=sms_score.userid")
				->getAll()
				->toHashMap("courseid", "courseid");
			$usercourse_sql = count($courseids) ? " and  courseid not in(" . implode(",", $courseids) . ")" : "";
			$userCourse = Usercourse::find("userid={$idsts} " . $usercourse_sql)->getAll()->toHashMap("courseid", "courseid");
			$sql .= empty($userCourse) ? " and 1=2" : " and id in(" . implode(",", $userCourse) . ")";
		}
		$courses = Course::find($sql)->order("CONVERT(code USING GBK)")->getAll();
		$cour_list = Array();
		foreach($courses as $course) {
			$cour_list[$course['id']] = $course['code'] . " " . $course['name'];
		}
		return $cour_list;
	}
}
