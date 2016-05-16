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
 * Controller_Userbc 控制器
 */
class Controller_Userbc extends Controller_Abstract
{
	function actionIndex(){
		$page = (int)$this->_context->page;
		if ($page==0) $page++;
		$limit = $this->_context->limit ? $this->_context->limit : 15;
		$user=$this->_app->currentUser();
		$sql_where="";
		$sql_where = Control_StuSearch::filterCond($sql_where);

        $qstatus = $this->_context->qstatus;
        if ($qstatus || $qstatus==='0') {
            $qstatus = (int)$qstatus;
            $sql_where .= " and [status]=$qstatus";
		    $this->_view['qstatus'] = $qstatus;
        }
		if($user['level']==3){//主考院校用户
			$sql_where .= " and [training_id]=".$user['orgid'];
		}else if($user['level']==2 || $user['level']==4){//学习中心用户
			$sql_where .= " and [college_id]=".$user['orgid'];
        }else if($user['level']==5){
            $sql_where .= " and [classid]=".$user['orgid'];
        }else if($user['level']==6){
            $sql_where .= " and [training_id] in (".$user['mclassids'].")";
        }
        $q = Userbc::find('[status]<>3'.$sql_where)->joinInner('sms_enroll','name as ename','core_user_before_check.enroll_id=sms_enroll.id')->joinInner('sms_org','name as oname','core_user_before_check.college_id=sms_org.id')->joinInner('sms_org','name as tname','core_user_before_check.training_id=sms_org_2.id')->joinInner('sms_class','name as cname','core_user_before_check.classid=sms_class.id')->order(array(new QDB_Expr('sms_enroll.name'),new QDB_Expr('sms_org.name'),new QDB_Expr('sms_org_2.name'),new QDB_Expr('sms_class.name'),new QDB_Expr('[name]')))->limitPage($page, $limit);
		$this->_view['pager'] = $q->getPagination();
		$this->_view['list'] = $q->getAll();
		$this->_view['start'] = ($page-1)*$limit;
		$this->_view['subject'] = "报名查看";
		$this->_view['userlv'] = $user['level'];
		$this->_view['qenroll'] = $this->_context->qenroll;
		$this->_view['qorglen'] = $this->_context->qorglen;
		$this->_view['qdiscipline'] = $this->_context->qdiscipline;
		$this->_view['qclassinfo'] = $this->_context->qclassinfo;
		$this->_view['quserid'] = $this->_context->quserid;
		$this->_view['qname'] = $this->_context->qname;
		$this->_view['page'] = $page;
    }

    function actionExport() {
		$user=$this->_app->currentUser();
		$sql_where = "";
		$sql_where = Control_StuSearch::filterCond($sql_where);
        $qstatus = $this->_context->qstatus;
        if ($qstatus && $qstatus!=='') {
            $qstatus = (int)$qstatus;
            $sql_where .= " and [status]=$qstatus";
		    $this->_view['qstatus'] = $qstatus;
        }
		if($user['level']==3){//主考院校用户
			$sql_where .= " and [training_id]=".$user['orgid'];
		}else if($user['level']==2 || $user['level']==4){//学习中心用户
			$sql_where .= " and [college_id]=".$user['orgid'];
		}else if($user['level']==6){
            $sql_where .= " and [training_id] in (".$user['mclassids'].")";
        }
		$list = Userbc::find('status<>3'.$sql_where)->joinInner('sms_enroll','name as ename','core_user_before_check.enroll_id=sms_enroll.id')->joinInner('sms_org','name as oname','core_user_before_check.college_id=sms_org.id')->joinInner('sms_org','name as tname','core_user_before_check.training_id=sms_org_2.id')->joinInner('sms_class','name as cname','core_user_before_check.classid=sms_class.id')->order(array(new QDB_Expr('sms_enroll.name'),new QDB_Expr('sms_org.name'),new QDB_Expr('sms_org_2.name'),new QDB_Expr('sms_class.name'),new QDB_Expr('[name]')))->getAll();
        $model = 'userbc';
        $filename = '报名状态'.date('YmdHis').'.xls';
        $sheetname = '报名状态';
        Helper_Util::export_form_excel($model, $list, $filename, $sheetname);
    }

    //待审核列表
	function actionSlist(){
		$user = $this->_app->currentUser();
		$page = (int)$this->_context->page;
		$cla_list = empty($this->_context->cla_list)?$this->_context->cla_list:trim($this->_context->cla_list);
		$stu_code = empty($this->_context->stu_code)?$this->_context->stu_code:trim($this->_context->stu_code);
		$stu_name = empty($this->_context->stu_name)?$this->_context->stu_name:trim($this->_context->stu_name);
		$sql_where = "[status]=0";
		$sql_where .= !empty($cla_list)?" and [classid]=".$cla_list:"";
		$sql_where .= !empty($stu_code)?" and [userid] like'%".$stu_code."%'":"";
		$sql_where .= !empty($stu_name)?" and [name] like'%".$stu_name."%'":"";
		$limit = $this->_context->limit ? $this->_context->limit : 15;
		$class_where = "";
		$qq = Userbc::find('status=0');
		$cnt = $qq->getCount();
		$pageAll = ceil($cnt/$limit);
		
		if($user['level']==3){//学习中心用户
            $sql_where .= " and [training_id]=".$user['orgid'];
            $class_where = " and [training_id]=".$user['orgid'];
		}else if($user['level']==2 || $user['level']==4){//主考院校用户
            $sql_where .= " and [college_id]=".$user['orgid'];
            $class_where = " and [college_id]=".$user['orgid'];            
		}else if($user['level']==6){
            $sql_where .= " and [training_id] in (".$user['mclassids'].")";
            $class_where = " and [training_id] in (".$user['mclassids'].")";
        }
		if ($page>$pageAll) $page = $pageAll;
	
		if ($page==0) $page++;
		$q = Userbc::find($sql_where)->joinInner('sms_enroll','name as ename','core_user_before_check.enroll_id=sms_enroll.id')->joinInner('sms_org','name as oname','core_user_before_check.college_id=sms_org.id')->joinInner('sms_org','name as tname','core_user_before_check.training_id=sms_org_2.id')->joinInner('sms_class','name as cname','core_user_before_check.classid=sms_class.id')->order(array(new QDB_Expr('sms_enroll.name'),new QDB_Expr('sms_org.name'),new QDB_Expr('sms_org_2.name'),new QDB_Expr('sms_class.name'),new QDB_Expr('[name]')))->limitPage($page, $limit);
		

		$cla_where = '';
		if ($user['level']==2 || $user['level']==4) {
			$cla_where = " and college_id=".$user['orgid'];
		} elseif ($user['level']==3) {
			$cla_where = " and training_id=".$user['orgid'];
		}else if($user['level']==6){
            $cla_where .= " and training_id in (".$user['mclassids'].")";
        }
		$qcla_list = Classinfo::find('isdelete=0'.$cla_where)->order('name')->getAll()->toHashMap("id","name");

		$this->_view['qcla_list'] = $qcla_list;
		$this->_view['cla_list'] = $cla_list;
		$this->_view['stu_code'] = $stu_code;
		$this->_view['stu_name'] = $stu_name;
		$this->_view['pager'] = $q->getPagination();
		$this->_view['list'] = $q->getAll();
		$this->_view['subject'] = "报名审核";
	}

    function actionSexport() {
    	$user = $this->_app->currentUser();
    	$cla_list = $this->_context->cla_list;
    	$stu_code = $this->_context->stu_code;
    	$stu_name = $this->_context->stu_name;
		$sql_where = "[status]=0";
		$sql_where .= !empty($cla_list)?" and [classid]=".$cla_list:"";
		$sql_where .= !empty($stu_code)?" and [userid] like'%".$stu_code."%'":"";
		$sql_where .= !empty($stu_name)?" and [name] like'%".$stu_name."%'":"";
		
		if($user['level']==3){//学习中心用户
            $sql_where .= " and [training_id]=".$user['orgid'];
            //$class_where = " and [training_id]=".$user['orgid'];
		}else if($user['level']==2 || $user['level']==4){//主考院校用户
            $sql_where .= " and [college_id]=".$user['orgid'];
            //$class_where = " and [college_id]=".$user['orgid'];            
		}else if($user['level']==6){
            $sql_where .= " and [training_id] in (".$user['mclassids'].")";
        }
		$list = Userbc::find($sql_where)->joinInner('sms_enroll','name as ename','core_user_before_check.enroll_id=sms_enroll.id')->joinInner('sms_org','name as oname','core_user_before_check.college_id=sms_org.id')->joinInner('sms_org','name as tname','core_user_before_check.training_id=sms_org_2.id')->joinInner('sms_class','name as cname','core_user_before_check.classid=sms_class.id')->order(array(new QDB_Expr('sms_enroll.name'),new QDB_Expr('sms_org.name'),new QDB_Expr('sms_org_2.name'),new QDB_Expr('sms_class.name'),new QDB_Expr('[name]')))->getAll();
        $model = 'userbc';
        $filename = '报名审核'.date('YmdHis').'.xls';
        $sheetname = '报名审核';
        Helper_Util::export_form_excel($model, $list, $filename, $sheetname);
    }

    //审核
    function actionAudit() {
        $type = (int)$this->_context->type;
        $ids = $this->_context->ids;
        $status = $type == 1 ? 1 : 2;
        $idstr = "'".implode("','", $ids)."'";
        $user = $this->_app->currentUser();
        $data = array('status'=>$status, 'audit_id'=>$user['id'], 'audit_date'=>time());
        if ($status == 2) {
            $data['note'] = $this->_context->note;
        }
        Userbc::meta()->updateWhere($data, "userid in ($idstr)");
        return $this->go_back(0);
    }

    //待确认列表
	function actionVlist(){
		//Userbc::meta()->updateWhere(array('status'=>0), 'status=1');
		$user=$this->_app->currentUser();
		$cla_list = empty($this->_context->cla_list)?$this->_context->cla_list:trim($this->_context->cla_list);
		$stu_code = empty($this->_context->stu_code)?$this->_context->stu_code:trim($this->_context->stu_code);
		$stu_name = empty($this->_context->stu_name)?$this->_context->stu_name:trim($this->_context->stu_name);
		$sql_where = "[valid]=0";
		$sql_where .= !empty($cla_list)?" and [classid]=".$cla_list:"";
		$sql_where .= !empty($stu_code)?" and [userid] like'%".$stu_code."%'":"";
		$sql_where .= !empty($stu_name)?" and [name] like'%".$stu_name."%'":"";
		$page = (int)$this->_context->page;
		$limit = $this->_context->limit ? $this->_context->limit : 15;
		$qq = Users::find('valid=0')->order('name');
		$cnt = $qq->getCount();
		$pageAll = ceil($cnt/$limit);
		if ($page>$pageAll) $page = $pageAll;
		
		if ($page==0) $page++;
		$q = Users::find($sql_where)->joinLeft('sms_enroll','name as ename','core_user.enroll_id=sms_enroll.id')->joinInner('sms_org','name as oname','core_user.college_id=sms_org.id')->joinLeft('sms_org','name as tname','core_user.training_id=sms_org_2.id')->joinLeft('sms_class','name as cname','core_user.classid=sms_class.id')->order(array(new QDB_Expr('sms_enroll.name'),new QDB_Expr('sms_org.name'),new QDB_Expr('sms_org_2.name'),new QDB_Expr('sms_class.name'),new QDB_Expr('[name]')))->limitPage($page, $limit);
		$qcla_list = Classinfo::find('isdelete=0')->order('name')->getAll()->toHashMap("id","name");
		$this->_view['level']=$user['level'];
		$this->_view['pager'] = $q->getPagination();
		$this->_view['qcla_list'] = $qcla_list;
		$this->_view['cla_list'] = $cla_list;
		$this->_view['stu_code'] = $stu_code;
		$this->_view['stu_name'] = $stu_name;
		$this->_view['list'] = $q->getAll();
		$this->_view['subject'] = "系统确认";
	}

    function actionVexport() {
		$user = $this->_app->currentUser();
    	$cla_list = $this->_context->cla_list;
    	$stu_code = $this->_context->stu_code;
    	$stu_name = $this->_context->stu_name;
		$sql_where = "[valid]=0";
		$sql_where .= !empty($cla_list)?" and [classid]=".$cla_list:"";
		$sql_where .= !empty($stu_code)?" and [userid] like'%".$stu_code."%'":"";
		$sql_where .= !empty($stu_name)?" and [name] like'%".$stu_name."%'":"";
		
		if($user['level']==3){//学习中心用户
            $sql_where .= " and [training_id]=".$user['orgid'];
            //$class_where = " and [training_id]=".$user['orgid'];
		}else if($user['level']==2 || $user['level']==4){//主考院校用户
            $sql_where .= " and [college_id]=".$user['orgid'];
            //$class_where = " and [college_id]=".$user['orgid'];            
		}else if($user['level']==6){
            $sql_where .= " and [training_id] in (".$user['mclassids'].")";
        }
		$list = Users::find($sql_where)->joinInner('sms_enroll','name as ename','core_user.enroll_id=sms_enroll.id')->joinInner('sms_org','name as oname','core_user.college_id=sms_org.id')->joinInner('sms_org','name as tname','core_user.training_id=sms_org_2.id')->joinInner('sms_class','name as cname','core_user.classid=sms_class.id')->order(array(new QDB_Expr('sms_enroll.name'),new QDB_Expr('sms_org.name'),new QDB_Expr('sms_org_2.name'),new QDB_Expr('sms_class.name'),new QDB_Expr('[name]')))->getAll();
        $model = 'users';
        $filename = '系统确认'.date('YmdHis').'.xls';
        $sheetname = '系统确认';
        Helper_Util::export_form_excel($model, $list, $filename, $sheetname);
    }

    /**
     * 导出电话本
     * @return [type] [description]
     */
    function actionvexportetel(){
		$user = $this->_app->currentUser();
    	$cla_list = $this->_context->cla_list;
    	$stu_code = $this->_context->stu_code;
    	$stu_name = $this->_context->stu_name;
		$sql_where = "[valid]=0";
		$sql_where .= !empty($cla_list)?" and [classid]=".$cla_list:"";
		$sql_where .= !empty($stu_code)?" and [userid] like'%".$stu_code."%'":"";
		$sql_where .= !empty($stu_name)?" and [name] like'%".$stu_name."%'":"";
		
		if($user['level']==3){//学习中心用户
            $sql_where .= " and [training_id]=".$user['orgid'];
            //$class_where = " and [training_id]=".$user['orgid'];
		}else if($user['level']==2 || $user['level']==4){//主考院校用户
            $sql_where .= " and [college_id]=".$user['orgid'];
            //$class_where = " and [college_id]=".$user['orgid'];            
		}else if($user['level']==6){
            $sql_where .= " and [training_id] in (".$user['mclassids'].")";
        }
		$list = Users::find($sql_where)->getAll();

		foreach ($list as $key => $val) {
	    	//$val = $val->toArray();
	    	$newval = array();
	    	//$newval = $val->toArray();
	    	$newval['name'] = $val->name;
	    	$newval['mobile'] = $val->mobile;
	    	$newval['phone'] = $val->phone;
	    	$tellist[] = $newval;
	    }
	    //var_dump($tellist);
		$title = array(
			'name' => '学生',
			'mobile' => '手机号码',
			'phone' => '固话',
		);
        
        $filename = '学生电话本'.date('YmdHis').'.xls';
	    $sheetname = '学生电话本';
        Helper_Util::export_excel($title, $tellist, $filename, $sheetname);
    }

    //开通确认
    function actionVerify(){
        $ids = $this->_context->ids;
        $idstr = "'".implode("','", $ids)."'";
        Users::meta()->updateWhere(array('valid'=>1), "userid in ($idstr)");
		/*
        foreach ($ids as $id) {
			$this->sendmsg($id);
		}
        */
        return $this->go_back(0);
    }


    function actionEdit(){
		// 查询指定 ID
		$id = $this->_context->id;
		$qenroll = $this->_context->qenroll;
		$qorglen = $this->_context->qorglen;
		$qdiscipline = $this->_context->qdiscipline;
		$qclassinfo = $this->_context->qclassinfo;
		$quserid = $this->_context->quserid;
		$qname = $this->_context->qname;
		$qstatus = $this->_context->qstatus;
		$page = $this->_context->page;
		$userbc = Userbc::find()->getById($id);	 
		// 构造表单对象
		$form = new Form_Userbc('');
		// 修改表单标题
		$this->_view['subject'] = "学生报名状态";
        // 修改登录账号为只读
        $form['userid']->readonly = true;
        $form->modle = $userbc;
		if ($userbc->status!=1){
			$form['enroll_id']->items=array(""=>"-请选择-")+Enroll::find("isdelete=0 ")->getAll()->toHashMap("id","name");
			$form['college_id']->items=array(""=>"-请选择-")+Org::find("isdelete=0 and type=1")->getAll()->toHashMap("id","name");
			$form['discipline_id']->items=array(""=>"-请选择-")+Discipline::find("isdelete=0 ")->getAll()->toHashMap("id","name");
			$form['training_id']->items=array(""=>"-请选择-")+Org::find("isdelete=0 and type=2")->getAll()->toHashMap("id","name");
			$form['classid']->items=array(""=>"-请选择-")+Classinfo::find("isdelete=0 ")->getAll()->toHashMap("id","name");
			
		}
		if ($this->_context->isPOST() && $form->validate($_POST))
		{
			$cbox = @$_POST['cbox'];//绑定学生的课程
			if (!$cbox) {
				$this->_view['diserr'] = '学生对应课程不能为空';
				$isvalid = false;
			} else {
				// changeProps() 方法可以批量修改对象的属性，但不会修改只读属性的值

				$userbc->changeProps($form->values());			
				//审核未通过则改成待审核状态
				if ($userbc->status == 2) $userbc->status = 0;
				$info_time = time();
				//修改人与修改日期
				$user = $this->_app->currentUser();
				$userbc->update_id = $user['username'];
				$userbc->update_date = $info_time;
				// 保存并重定向浏览器
				$userbc->save();
				// 	创建成功后，重定向浏览器
				$userid = $userbc->userid;//学生iD
				//删除所有对应的课程
				Usercourse::meta()->destroyWhere('username=?',$userid);
				//绑定学生的课程
			  	if(!empty($_POST['cbox'])){
			  		$user_info = User::find("userid=?",$userbc->userid)->getOne();
					foreach($_POST['cbox'] as $k=>$v){
						$uc = new Usercourse();
						if($user_info->idst){
							$uc->userid = $user_info->idst;
						}
						$uc->username = $userbc->userid;
						$uc->courseid = $v;
						$uc->date_get = $info_time;
						$uc->class_id = $userbc['classid'];
                    	$uc->training_id = $userbc['training_id'];
						$uc->back_only = 1;
						
						$uc->save();
					}
			  	}
				return $this->_redirect(url('', array('qenroll'=>@$qenroll,'qorglen'=>@$qorglen,'qdiscipline'=>@$qdiscipline,'qclassinfo'=>@$qclassinfo,'quserid'=>@$quserid,'qname'=>@$qname,'qstatus'=>@$qstatus,'page'=>@$page)));    
			}
		}
		elseif (!$this->_context->isPOST())
		{
			// 如果不是 POST 提交，则把对象值导入表单
			$form->import($userbc);
            //设置返回的地址
            $this->set_back();
		} else {
			$cbox = @$_POST['cbox'];//绑定学生的课程
			if (!$cbox) {
				$this->_view['diserr'] = '学生对应课程不能为空';
				$isvalid = false;
			}
		}
		$this->_view['form'] = $form;
		// 重用 create 动作的视图
		$this->_viewname = 'create';
		$this->_view['qenroll'] = $qenroll;
		$this->_view['qorglen'] = $qorglen;
		$this->_view['qdiscipline'] = $qdiscipline;
		$this->_view['qclassinfo'] = $qclassinfo;
		$this->_view['quserid'] = $quserid;
		$this->_view['qname'] = $qname;
		$this->_view['qstatus'] = $qstatus;
		$this->_view['page'] = $page;
	}
	
	
	function actionDelete(){
		$userbc = Userbc::find()->getById($this->_context->id);
		$db=QDB::getConn();
		$sql = "delete from lc_courseuser where username = '".$userbc->userid."'";//生成idst 获取并返回
		$db->execute($sql);
	 	$userbc->status=3;	 	
	 	$userbc->save();
		return $this->go_back(0);
	}
	
	function actionImport(){
		$subject_1 = array();//tab初始化
		$subject_2 = array();//tab初始化
		$subject_3 = array();//tab初始化
        $cbox = array();
		$user = $this->_app->currentUser();
		$form = new Form_Userbc('');// 构造表单对象
		if ($this->_context->isPOST()){// 修改表单标题
			$act = $_POST['act'];
			if($act=="import_sub"){
				$info_list = $_POST['data'];
				foreach ($info_list as $k=>$v){
					$value = explode(",", $v);
					$d_where = "name ='".$value[2]."'";
					$dis = Discipline::find($d_where)->getOne();
					$org = Org::find('name=? and pid!=0',$value[4])->getOne();
					$userbc = new User();
					if(!empty($org['id'])){//所属学院存在
						$userbc->college_id=$org['pid'];
						$userbc->training_id=$org['id'];
					}else{//所属学院不存在
						$orgs= Org::find('pid=0 and name=?',$value[3])->getOne();
						$org_new = new Org();
						$org_new->name = $value[10];
						$org_new->type = 2;
						$org_new->pid = $orgs['id'];
						$org_new->update_id = $user['id'];
						$org_new->update_date = time();
						//echo "生成学习中心";
						$org_new->save();
						$userbc->college_id=$orgs['id'];
						$userbc->training_id=$org_new['id'];
					}

					if(!empty($dis['id'])){//专业存在
						$userbc->discipline_id=$dis['id'];
					}else{//专业不存在
						$discipline = new Discipline();
						$discipline->name = $value[9];
						$discipline->shortname = substr($value[9], 0,4);
						$discipline->code = !empty($value[8])?$value[8]:time();
						$discipline->college_id = !empty($org['pid'])?$org['pid']:$orgs['id'];
						$discipline->isdelete = 0;
						$discipline->note = "自动生成";
						$discipline->save();
						//echo "生成专业";
						$userbc->discipline_id=$discipline['id'];
					}
					$userbc->name=$value[1];
					$userbc->pass='111111';
					$userbc->userid=$value[0];
					$userbc->save(99,"create");

					$dis = Discipline::find("id=?",$userbc->discipline_id)->getOne();
					$course = $dis->dis_courses;
                    foreach($course as $k=>$v){
	                        $uc = new Usercourse();
	                        $uc->username = $userbc->userid;
	                        $uc->userid = $userbc->idst;
	                        $uc->courseid = $v['cid'];
	                        $uc->training_id = $userbc['training_id'];
							$uc->back_only = 1;
	                        $uc->save();
	                    }
				}
				$this->_view['tab_show'] = "form_show";
				$subject_1['flag']="T";
				$subject_2['flag']="F";
				$subject_3['flag']="F";
				$this->_view['succeed'] = "succeed";
			}else if($act=="importcid_sub"){
                $info_list = $_POST['data'];
				foreach ($info_list as $k=>$v){
					$value = explode(",", $v);
					$userbc = Userbc::find("userid='".$value[0]."' and cid='".$value[1]."'")->getOne();
                    $userbc->eid = $value[2];
                    $userbc->save();
				}
				$this->_view['tab_show'] = "form_show";
				$subject_1['flag']="T";
                $subject_2['flag']="F";
				$subject_3['flag']="F";
				$this->_view['succeed'] = "succeed";
            }else if($act=="importcid"){//excel 提交检查 学生准考证
                
                require_once Q::ini('app_config/LIB_DIR').'/PHPExcel/IOFactory.php';
				$reader = PHPExcel_IOFactory::createReader('Excel5'); // 读取 excel档案
				$PHPExcel = $reader->load($_FILES["file2"]["tmp_name"]); // excel名称
				$sheet = $PHPExcel->getSheet(0); // 第一个工作表
				$highestRow = $sheet->getHighestRow(); // rows
				$user_can_bm = array();
				$user_canot_bm = array();
                $ubc_new = array();
                $user_where = "";
                //根据权限获取菜单
				if($user['level']==2 ||$user['level']==4){//主考院校
					$user_where = " and college_id=".$user['orgid'];
				}else if($user['level']==3){
					$user_where = " and training_id=".$user['orgid'];
				}
                
                for ($row = 2; $row <=$highestRow; $row++) {//从第2行开始
					$t_user=array("flag"=>"T","error"=>"");
                    $data_value="";
                    for ($column = 0; $column <3; $column++) {
                        $val = trim($sheet->getCellByColumnAndRow($column, $row)->getValue());
                        $t_user[$column] = $val;
                        if($column==0){//判断是否在 users 和 userbc中有这个登录账号的记录]
				    		$t_user[$column] = $val;
				    		$userbc_list = Userbc::find(" userid='".$val."'".$user_where)->getOne();
				    		if(!$userbc_list->userid){//重复数据
				    			$t_user['flag'] =  "F";
				    			$t_user['error'] = $row."行,对应帐号非法或不存在。";
                            }else{
                                $t_user['name'] = $userbc_list->name;
                                $t_user['tel'] = $userbc_list->mobile;
                            }
                        }

                        if($column==1){//判断帐号对应身份证是否正确
                            $t_user[$column] = $val;
				    		$uinfo = Userbc::find("userid='".$t_user[0]."' and cid='".$val."'")->one()->get()->id();
				    		if(!$uinfo){//为空则非法数据
				    			$t_user['flag'] =  "F";
				    			$t_user['error'] = $row."行,帐号与身份证不匹配。";
                            }
                        }

                        if($column==2){//判断帐号对应身份证是否正确
				    		$t_user[$column] = $val;
				    		if(empty($val)){//为空则非法数据
				    			$t_user['flag'] =  "F";
				    			$t_user['error'] = $row."行,准考证号码不能为空。";
                            }
                        }
                        $data_value = $data_value.$val.",";
                    }

                    $t_user['dv'] = mb_substr($data_value, 0,mb_strlen($data_value)-1);
				    
				    if($t_user['flag']=="F"){
				    	$user_canot_bm[]=$t_user;
				    }else{
				    	$user_can_bm[]=$t_user;
				    }
				    $excel_show['user_canot_bm']=$user_canot_bm;
                    $excel_show['user_can_bm']=$user_can_bm;
                }

                $this->_view['tab_show'] = "importcid_check_over";
				$subject_1['flag']="F";
                $subject_2['flag']="F";
                $subject_3['flag']="T";
				$this->_view['excel_show'] = $excel_show;

            }else if($act=="import"){//批量导入
            	$excel_show =array();
				require_once Q::ini('app_config/LIB_DIR').'/PHPExcel/IOFactory.php';
				$reader = PHPExcel_IOFactory::createReader('Excel5'); // 读取 excel档案
				$PHPExcel = $reader->load($_FILES["file"]["tmp_name"]); // excel名称
				$sheet = $PHPExcel->getSheet(0); // 第一个工作表
				$highestRow = $sheet->getHighestRow(); // rows
				$user_can_bm = array();
				$user_canot_bm = array();
				$stuno_arr=array();//学号数组
				//dump($highestRow);
				for ($row = 3; $row <=$highestRow; $row++) {//从第2行开始
					$t_user=array("flag"=>"T","error"=>"");
					$data_value="";
				    for ($column = 0; $column <7; $column++) {
				    	$val = $sheet->getCellByColumnAndRow($column, $row)->getValue();
				    	$t_user[$column] = $val;
				    	
				    	if($column==0 && !empty($val) && $t_user['flag']=="T"){//学号验证
				    		$user_list = Users::find("stuno='".$val."'")->one()->get()->id();
				    		if($user_list||in_array($val, $stuno_arr)){//重复数据
				    			$t_user['flag'] =  "F";
				    			$t_user['error'] = $row."行,学号重复";
				    		}else{
				    			$stuno_arr[]=$val;
				    		}
				    	}
				    	
				    	if($column==1){//学生姓名不能为空
				    		if(empty($val)){//空数据
				    			$t_user['flag'] = "F";
				    			$t_user['error'] = $row."行,学生姓名为空";
				    		}
				    	}
				    	
				    	if($column==2){//专业名称
				    		if(empty($val)){
				    			$t_user['flag'] = "F";
                                $t_user['error'] = $row."行,所属专业为空"; 
				    		}else{
					    		$dis_name = Discipline::find('name=?',$val)->getOne();
					    		if(!empty($dis_name)){
					    			$dis_college_id = $dis_name['college_id'];
					    		}
				    		}
				    	}
				    	if ($column==3){
				    		if(empty($val)){
				    			$t_user['flag'] = "F";
                                $t_user['error'] = $row."行,学校为空"; 
				    		}else{
					    		$college_name = Org::find('name=? and type=1',$val)->getOne();
					    		if(empty($college_name)){
						    		$t_user['flag'] = "F";
		                            $t_user['error'] = $row."行,学校不存在"; 
					   			}else{
					   				$college_id = $college_name['id'];
					   				if($dis_college_id!=$college_id){
					   					$t_user['flag'] = "F";
                                		$t_user['error'] = $row."行, 学校和专业不对应"; 
					   				}
					   			}
				    		}
				    	}
				    	if ($column==4){
				    		if(empty($val)){
				    			$t_user['flag'] = "F";
                                $t_user['error'] = $row."行,所属学院为空"; 
				    		}else{
					    		$college_name = Org::find('name=? and type=2',$val)->getOne();
					    		if(!empty($college_name)){
					   				if($college_id!=$college_name['pid']){
					   					$t_user['flag'] = "F";
		                          		$t_user['error'] = $row."行,所属学院和学校不对应"; 
					   				}
					   			}
				    		}
				    	}

				    	$data_value = $data_value.$val.",";
				    }
				    $t_user['dv'] = mb_substr($data_value, 0,mb_strlen($data_value)-1);
				    
				    if($t_user['flag']=="F"){
				    	$user_canot_bm[]=$t_user;
				    }else{
				    	$user_can_bm[]=$t_user;
				    }
				    $excel_show['user_canot_bm']=$user_canot_bm;
				    $excel_show['user_can_bm']=$user_can_bm;
				}
				//数据检测完毕 呈现
				$this->_view['tab_show'] = "import_check_over";
				$subject_1['flag']="F";
				$subject_2['flag']="T";
				$subject_3['flag']="F";
				$this->_view['excel_show'] = $excel_show;
				$edu_where = "";
                $len_where = "";
                $dis_where = "";
				$classinfo_where ="";
				
				$leveltoview = 1;
				$eduid = "";
				$lenid = "";
				//根据权限获取菜单
				if($user['level']==2 ||$user['level']==4){//主考院校
					$edu_where = " and id=".$user['orgid'];
					$leveltoview = 2;
					$eduid = $user['orgid']; 
                    $len_where = " and pid=".$user['orgid'];
                    $dis_where = " and college_id=".$user['orgid'];
					$classinfo_where = " and college_id=".$user['orgid'];
				}else if($user['level']==3){
					$edu_where = " and id=(select pid from sms_org where id=".$user['orgid'].")";
                    $len_where = " and id=".$user['orgid'];
                    $dis_where = " and college_id=".(Org::find("id=?",$user['orgid'])->getOne()->pid);
					$leveltoview = 3;
					$var = Org::find('isdelete=0 and id=?',$user['orgid'])->getOne();
					$eduid = $var['pid'];
					$lenid = $user['orgid'];
					$classinfo_where = " and training_id=".$user['orgid'];
				}
				
				$this->_view['leveltoview'] = $leveltoview;

				$this->_view['eduid'] = $eduid;
				$this->_view['lenid'] = $lenid;
				
			}else if($act=="form_sub"&&$form->validate($_POST)){
				$isvalid = true;
				$cbox = @$_POST['cbox'];//绑定学生的课程
				if (!$cbox) {
					$this->_view['diserr'] = '学生对应课程不能为空';
					$isvalid = false;
				}
				$info_user = User::find("userid='".$_POST['userid']."'")->getOne();
				if(!empty($info_user->userid)){
					$form['userid']->invalidate($_POST['userid']."已经存在");
					$isvalid = false;
				}
				if ($isvalid) {
					$user_add = new User();
					$user_add->changeProps($form->values());
					if($_POST['pass']==""){//密码没有填写 应该获取身份证后6位
						$cid = $_POST['cid'];
						if(mb_strlen($cid)>6){
							$user_add->pass = mb_substr($cid,mb_strlen($cid)-6,mb_strlen($cid));
						}else{
							$user_add->pass = $cid;
						}
					}
					$user_add->changePropForce('userid',$_POST['userid']);
					$user_add->save(99,"create");
					$this->_view['succeed'] = "succeed2";
					
                   $cbox = @$_POST['cbox'];//绑定学生的课程
                   if(!empty($cbox)){
                    foreach($cbox as $k=>$v){
                        $uc = new Usercourse();
                        $uc->username = $_POST['userid'];
                        $uc->courseid = $v;
                        $uc->class_id = $user_add['classid'];
						$uc->back_only = 1;
                        $uc->save();
                    }
                   }
					$form = new Form_Userbc('');
				}
				$this->_view['tab_show'] = "form_show";
				$subject_1['flag']="T";
				$subject_2['flag']="F";
				$subject_3['flag']="F";
			}else{
				if($act=="form_sub") {
					$cbox = @$_POST['cbox'];//绑定学生的课程
					if (!$cbox) {
						$this->_view['diserr'] = '学生对应课程不能为空';
						$isvalid = false;
					}
				}
				$this->_view['tab_show'] = "form_show";
				$subject_1['flag']="T";
				$subject_2['flag']="F";
				$subject_3['flag']="F";
			}
		}else{
			$this->_view['tab_show'] = "form_show";
			$subject_1['flag']="T";
			$subject_2['flag']="F";
			$subject_3['flag']="F";
		}
		$subject_1['name'] = "学生添加";
		$subject_1['other_parm'] = "onclick=\"showTab('one');\"";
		$subject_1['id'] = "one_tab";
		
		$subject_2['name'] = "批量导入";
		$subject_2['other_parm'] = "onclick=\"showTab('import');\"";
		$subject_2['id'] = "import_tab";
		
		$subject_3['name'] = "准考证导入";
		$subject_3['other_parm'] = "onclick=\"showTab('importcid');\"";
		$subject_3['id'] = "importcid_tab";
		$this->_view['subject'] = array($subject_1,$subject_2,$subject_3);
		$this->_view['form'] = $form;
	}
	function actionOldImport(){
		$subject_1 = array();//tab初始化
		$subject_2 = array();//tab初始化
		$subject_3 = array();//tab初始化
        $cbox = array();
		$user = $this->_app->currentUser();
		$form = new Form_Userbc('');// 构造表单对象
		if ($this->_context->isPOST()){// 修改表单标题
			$act = $_POST['act'];
			if($act=="import_sub"){
				$info_list = $_POST['data'];
				$enroll_id = $_POST['enroll_id'];
				$college_id = $_POST['college_id'];
				$training_id = $_POST['training_id'];
				$discipline_id = $_POST['discipline_id'];
				$classid =  $_POST['classid'];
				$dazhuan=@$_POST["dazhuan"];
                $cbox = $_POST['cbox'];
				foreach ($info_list as $k=>$v){
					$value = explode(",", $v);
					$userbc = new User();
					$userbc->name=$value[2];
					$userbc->pass=$value[1];
					$userbc->gender=($value[3]=="男"?1:0);
					$userbc->age=$value[4];
					$userbc->cid=$value[5];
					$userbc->eid=$value[6];
					$userbc->email=$value[13];
					$userbc->mobile=$value[7];
					$userbc->phone=$value[8];
					$userbc->address=$value[9];
					$userbc->postcode=$value[10];
					$userbc->hasdegree=($value[11]=="有"?1:($value[11]=="未知"?2:0));
					$userbc->corpname=$value[12];
					$userbc->hometown = $value[14];
                    $userbc->dazhuan = $value[15];
                    $userbc->nation = $value[16];
					$userbc->birth = $value[17];
					$userbc->stuno = $value[18];
					$userbc->enroll_id = $enroll_id;
					$userbc->college_id = $college_id;
					$userbc->training_id = $training_id;
					$userbc->discipline_id = $discipline_id;
					$userbc->classid = $classid;
					$userbc->update_id = $user['id'];
					$userbc->update_date = time();
					$userbc->status = 0;
					$userbc->audit_id = 0;
					$userbc->audit_date = 0;
					$userbc->changePropForce('userid',$value[0]);
					$userbc->save(99,"create");
                    foreach($cbox as $k=>$v){
                        $uc = new Usercourse();
                        $uc->username = $userbc->userid;
                        $uc->courseid = $v;
                        $uc->training_id = $training_id;
						$uc->back_only = 1;
                        $uc->save();
                    }
				}
				$this->_view['tab_show'] = "form_show";
				$subject_1['flag']="T";
				$subject_2['flag']="F";
				$subject_3['flag']="F";
				$this->_view['succeed'] = "succeed";
			}else if($act=="importcid_sub"){
                $info_list = $_POST['data'];
				foreach ($info_list as $k=>$v){
					$value = explode(",", $v);
					$userbc = Userbc::find("userid='".$value[0]."' and cid='".$value[1]."'")->getOne();
                    $userbc->eid = $value[2];
                    $userbc->save();
				}
				$this->_view['tab_show'] = "form_show";
				$subject_1['flag']="T";
                $subject_2['flag']="F";
				$subject_3['flag']="F";
				$this->_view['succeed'] = "succeed";
            }else if($act=="importcid"){//excel 提交检查 学生准考证
                
                require_once Q::ini('app_config/LIB_DIR').'/PHPExcel/IOFactory.php';
				$reader = PHPExcel_IOFactory::createReader('Excel5'); // 读取 excel档案
				$PHPExcel = $reader->load($_FILES["file2"]["tmp_name"]); // excel名称
				$sheet = $PHPExcel->getSheet(0); // 第一个工作表
				$highestRow = $sheet->getHighestRow(); // rows
				$user_can_bm = array();
				$user_canot_bm = array();
                $ubc_new = array();
                $user_where = "";
                //根据权限获取菜单
				if($user['level']==2 ||$user['level']==4){//主考院校
					$user_where = " and college_id=".$user['orgid'];
				}else if($user['level']==3){
					$user_where = " and training_id=".$user['orgid'];
				}
                
                for ($row = 2; $row <=$highestRow; $row++) {//从第2行开始
					$t_user=array("flag"=>"T","error"=>"");
                    $data_value="";
                    for ($column = 0; $column <3; $column++) {
                        $val = trim($sheet->getCellByColumnAndRow($column, $row)->getValue());
                        $t_user[$column] = $val;
                        if($column==0){//判断是否在 users 和 userbc中有这个登录账号的记录]
				    		$t_user[$column] = $val;
				    		$userbc_list = User::find(" userid='".$val."'".$user_where)->getOne();
				    		if(!$userbc_list->userid){//重复数据
				    			$t_user['flag'] =  "F";
				    			$t_user['error'] = $row."行,对应帐号非法或不存在。";
                            }else{
                                $t_user['name'] = $userbc_list->name;
                                $t_user['tel'] = $userbc_list->mobile;
                            }
                        }

                        if($column==1){//判断帐号对应身份证是否正确
                            $t_user[$column] = $val;
				    		$uinfo = User::find("userid='".$t_user[0]."' and cid='".$val."'")->one()->get()->id();
				    		if(!$uinfo){//为空则非法数据
				    			$t_user['flag'] =  "F";
				    			$t_user['error'] = $row."行,帐号与身份证不匹配。";
                            }
                        }

                        if($column==2){//判断帐号对应身份证是否正确
				    		$t_user[$column] = $val;
				    		if(empty($val)){//为空则非法数据
				    			$t_user['flag'] =  "F";
				    			$t_user['error'] = $row."行,准考证号码不能为空。";
                            }
                        }
                        $data_value = $data_value.$val.",";
                    }

                    $t_user['dv'] = mb_substr($data_value, 0,mb_strlen($data_value)-1);
				    
				    if($t_user['flag']=="F"){
				    	$user_canot_bm[]=$t_user;
				    }else{
				    	$user_can_bm[]=$t_user;
				    }
				    $excel_show['user_canot_bm']=$user_canot_bm;
                    $excel_show['user_can_bm']=$user_can_bm;
                }

                $this->_view['tab_show'] = "importcid_check_over";
				$subject_1['flag']="F";
                $subject_2['flag']="F";
                $subject_3['flag']="T";
				$this->_view['excel_show'] = $excel_show;

            }else if($act=="import"){//excle 提交
				require_once Q::ini('app_config/LIB_DIR').'/PHPExcel/IOFactory.php';
				$reader = PHPExcel_IOFactory::createReader('Excel5'); // 读取 excel档案
				$PHPExcel = $reader->load($_FILES["file"]["tmp_name"]); // excel名称
				$sheet = $PHPExcel->getSheet(0); // 第一个工作表
				$highestRow = $sheet->getHighestRow(); // rows
				$user_can_bm = array();
				$user_canot_bm = array();
				$ubc_new=array();
				for ($row = 2; $row <=$highestRow; $row++) {//从第2行开始
					$t_user=array("flag"=>"T","error"=>"");
					$data_value="";
				    for ($column = 0; $column <19; $column++) {
				    	$val = $sheet->getCellByColumnAndRow($column, $row)->getValue();
				    	$t_user[$column] = $val;
				    	
				    	if($column==0){//判断是否在 users 和 userbc中有这个登录账号的记录]
				    		//$val=$sheet->getCellByColumnAndRow(6, $row)->getValue();
				    		$t_user[$column] = $val;
				    		$user_list = Users::find("userid='".$val."'")->one()->get()->id();
				    		$userbc_list = Userbc::find(" userid='".$val."'")->one()->get()->id();
				    		if($user_list||$userbc_list||in_array($val, $ubc_new)){//重复数据
				    			$t_user['flag'] =  "F";
				    			$t_user['error'] = $row."行,登录账号重复";
				    		}else{
				    			$ubc_new[]=$val;
				    		}
				    	}
				    	
				    	if($column==1){//密码校验
				    		if(empty($val)){//空数据
				    			$cid = $sheet->getCellByColumnAndRow(5, $row)->getValue();//获取第5列身份证数据
				    			if(mb_strlen($cid)<15){
				    				$t_user['flag'] = "F";
				    				$t_user['error'] = $row."行,身份证少于15位";
				    			}else{
				    				$cid = mb_substr($cid,mb_strlen($cid)-6,mb_strlen($cid));
				    				$t_user[$column] = $cid;
				    				$val = $cid;
				    			}
				    		}
				    	}
				    	
				    	if($column==2){//姓名不能为空
				    		if(empty($val)&&$t_user['flag']=="T"){//空数据
				    			$t_user['flag'] = "F";
				    			$t_user['error'] = $row."行,姓名为空";
				    		}
				    	}
				    	
				    	if($column==3){//性别
				    	
				    		if(empty($val)&&$t_user['flag']=="T"){//空数据
				    			$t_user['flag'] = "F";
				    			$t_user['error'] = $row."行,性别不能为空";
				    		}
				    		if(trim($val)=="男" || trim($val)=="女"){
				    			//$t_user['flag'] = "T";
				    			
				    		}else{
				    			$t_user['flag'] = "F";
				    			$t_user['error'] = $row."行,性别错误";
				    		}
				    	}
				    	
				    	if($column==4){//年龄校验
				    		$age = intval("".$val);
				    		
				    		//$now =date('Y',time());
				    		if(empty($val)&&$t_user['flag']=="T"){//空数据
				    			$t_user['flag'] = "F";
				    			$t_user['error'] = $row."行,年龄不能为空";
				    		}else if($age<18||$age>70){
				    			$t_user['flag'] = "F";
				    			$t_user['error'] = $row."行,年龄应该为18-70";
				    		}
				    	}
				    	
				    	if($column==5){//身份证不能空
				    		if(empty($val)&&$t_user['flag']=="T"){//空数据
				    			$t_user['flag'] = "F";
				    			$t_user['error'] = $row."行,身份证为空";
				    		}elseif(mb_strlen($val)!=15&&mb_strlen($val)!=18){
				    			$t_user['flag'] = "F";
				    			$t_user['error'] = $row."行,身份证15位或者18位";
                            }else{
                                if(mb_strlen($val)==15){
                                    $cid = Helper_Util::idcard_15to18 ( $val );
                                }else{
                                    $cid = $val;
                                }                                
                                if(!Helper_Util::idcard_checksum18 ( $cid )){
                                    $t_user['flag'] = "F";
                                    $t_user['error'] = $row."行,身份证编码错误"; 
                                }
                            }
				    	}
				    	
				    	if($column==7){//移动电话不能空
				    		
				    		if( $val==""&&$t_user['flag']=="T"){//重复数据
				    			$t_user['flag'] = "F";
				    			$t_user['error'] = $row."行,移动电话为空";
				    		}else if((!preg_match("/^(\+86)?(13\d|15[0-3]|18[5-9]|15[5-9]|180|181|182|183|147|145)\d{8}$/", $val))&&$t_user['flag']=="T"){
				    		 	$t_user['flag'] = "F";
				    			$t_user['error'] = $row."行,您输入的移动电话有误";
				    		}
				    	}
				    	if($column==11){
				    		$has_degree = $val;
				    	}
				    	if ($column==15){
				    		if (!$val){
				    			if ($has_degree=="有"){
				    				$t_user['flag'] = "F";
				    				$t_user['error'] = $row."行,大专专业不能为空";
				    			}
				    		}else{
				    			if ($has_degree!="有"){
				    				$t_user['flag'] = "F";
				    				$t_user['error'] = $row."行,是否已获大专文凭填写错误";
				    			}
				    		}
				    	}
				    	$data_value = $data_value.$val.",";
				    }
				    $t_user['dv'] = mb_substr($data_value, 0,mb_strlen($data_value)-1);
				    
				    if($t_user['flag']=="F"){
				    	$user_canot_bm[]=$t_user;
				    }else{
				    	$user_can_bm[]=$t_user;
				    }
				    $excel_show['user_canot_bm']=$user_canot_bm;
				    $excel_show['user_can_bm']=$user_can_bm;
				}
				//数据检测完毕 呈现
				$this->_view['tab_show'] = "import_check_over";
				$subject_1['flag']="F";
				$subject_2['flag']="T";
				$subject_3['flag']="F";
				$this->_view['excel_show'] = $excel_show;
				$edu_where = "";
                $len_where = "";
                $dis_where = "";
				$classinfo_where ="";
				
				$leveltoview = 1;
				$eduid = "";
				$lenid = "";
				//根据权限获取菜单
				if($user['level']==2 ||$user['level']==4){//主考院校
					$edu_where = " and id=".$user['orgid'];
					$leveltoview = 2;
					$eduid = $user['orgid']; 
                    $len_where = " and pid=".$user['orgid'];
                    $dis_where = " and college_id=".$user['orgid'];
					$classinfo_where = " and college_id=".$user['orgid'];
				}else if($user['level']==3){
					$edu_where = " and id=(select pid from sms_org where id=".$user['orgid'].")";
                    $len_where = " and id=".$user['orgid'];
                    $dis_where = " and college_id=".(Org::find("id=?",$user['orgid'])->getOne()->pid);
					$leveltoview = 3;
					$var = Org::find('isdelete=0 and id=?',$user['orgid'])->getOne();
					$eduid = $var['pid'];
					$lenid = $user['orgid'];
					$classinfo_where = " and training_id=".$user['orgid'];
				}
				$enroll_list = Enroll::find('isdelete=0')->order('name')->getAll()->toHashMap("id","name");//入学批次
				$org_edu_list = Org::find('isdelete=0 and type=1 '.$edu_where)->order('name')->getAll()->toHashMap("id","name");//主考院校
				$org_len_list = Org::find('isdelete=0 and type=2 '.$len_where)->order('name')->getAll()->toHashMap("id","name");//学习中心
				$discipline_list = Discipline::find('isdelete=0'.$dis_where)->order('name')->getAll();//专业
				$dis_array = array();
                foreach ($discipline_list as $v=>$k){
                    if($user['level']==1){
                        $dis_array[$k->id] = $k->college->shortname." | ".$k->name;
                    }else{
                        $dis_array[$k->id] = $k->name;
                    }
				}
				$discipline_list = $dis_array;
				$classinfo_list = Classinfo::find('isdelete=0 '.$classinfo_where)->order('name')->getAll()->toHashMap("id","name");//班级
				$this->_view['enroll_list']=$enroll_list;
				$this->_view['org_edu_list']=$org_edu_list;//主考院校
				$this->_view['org_len_list']=$org_len_list;//学习中心 
				$this->_view['discipline_list']=$discipline_list;
				$this->_view['classinfo_list']=$classinfo_list;
				
				$this->_view['leveltoview'] = $leveltoview;

				$this->_view['eduid'] = $eduid;
				$this->_view['lenid'] = $lenid;
				
			}else if($act=="form_sub"&&$form->validate($_POST)){
				$isvalid = true;
				$cbox = @$_POST['cbox'];//绑定学生的课程
				if (!$cbox) {
					$this->_view['diserr'] = '学生对应课程不能为空';
					$isvalid = false;
				}
				$info_user = Userbc::find("userid='".$_POST['userid']."'")->getOne();
				if(!empty($info_user->userid)){
					$form['userid']->invalidate($_POST['userid']."已经存在");
					$isvalid = false;
				}
				if ($isvalid) {
					$userbc = new Userbc();
					$userbc->changeProps($form->values());
					if($_POST['pass']==""){//密码没有填写 应该获取身份证后6位
						$cid = $_POST['cid'];
						if(mb_strlen($cid)>6){
							$userbc->pass = mb_substr($cid,mb_strlen($cid)-6,mb_strlen($cid));
						}else{
							$userbc->pass = $cid;
						}
					}
					$userbc->changePropForce('userid',$_POST['userid']);
					$userbc->update_id = $user['id'];
					$userbc->update_date = time();
					$userbc->audit_id = 0;
					$userbc->audit_date = 0;
					$userbc->status =0;
					 
					$userbc->save(99,"create");
					$this->_view['succeed'] = "succeed2";
					
                   $cbox = @$_POST['cbox'];//绑定学生的课程
                   if(!empty($cbox)){
                    foreach($cbox as $k=>$v){
                        $uc = new Usercourse();
                        $uc->username = $_POST['userid'];
                        $uc->courseid = $v;
                        $uc->class_id = $userbc['classid'];
                        $uc->training_id = $userbc['training_id'];
						$uc->back_only = 1;
                        $uc->save();
                    }
                   }
					$form = new Form_Userbc('');
				}
				$this->_view['tab_show'] = "form_show";
				$subject_1['flag']="T";
				$subject_2['flag']="F";
				$subject_3['flag']="F";
			}else{
				if($act=="form_sub") {
					$cbox = @$_POST['cbox'];//绑定学生的课程
					if (!$cbox) {
						$this->_view['diserr'] = '学生对应课程不能为空';
						$isvalid = false;
					}
				}
				$this->_view['tab_show'] = "form_show";
				$subject_1['flag']="T";
				$subject_2['flag']="F";
				$subject_3['flag']="F";
			}
		}else{
			$this->_view['tab_show'] = "form_show";
			$subject_1['flag']="T";
			$subject_2['flag']="F";
			$subject_3['flag']="F";
		}
		$subject_1['name'] = "学生报名";
		$subject_1['other_parm'] = "onclick=\"showTab('one');\"";
		$subject_1['id'] = "one_tab";
		
		$subject_2['name'] = "批量导入";
		$subject_2['other_parm'] = "onclick=\"showTab('import');\"";
		$subject_2['id'] = "import_tab";
		
		$subject_3['name'] = "准考证导入";
		$subject_3['other_parm'] = "onclick=\"showTab('importcid');\"";
		$subject_3['id'] = "importcid_tab";
		$this->_view['subject'] = array($subject_1,$subject_2,$subject_3);
		$this->_view['form'] = $form;
	}

    function actionGetuname() {
        $userid = $this->_context->userid;
        $admin = $this->_app->currentUser();
        $level = $admin['level'];
        $user_where = "";
        
        if ($level==2 || $level==4) {
        	$user_where .= " and college_id=".$admin['orgid'];
        } else if ($level==3) {
        	$user_where .= " and training_id=".$admin['orgid'];
        }else if($level==6){
            $user_where .= " and training_id in (".$user['mclassids'].")";
        }
        
        $userbc = Userbc::find("status<>3 and userid='".$userid."'".$user_where)->getOne();
        echo $userbc->name;die;
    }
    
    function actionView(){
    	$userid= $this->_context->userid;
    	$userbc = Userbc::find('userid=?',$userid)->query();
    	$this->_view['subject']="报名学生查看";
    	$this->_view['users']=$userbc;
    	$this->_view['backUrl']=$this->_context->referer();
        
        $user_course_list = Usercourse::find("username=?",$userid)->getAll();
        $usercourse_list = array();
        foreach($user_course_list as $k => $v){
            $usercourse_list[] = $v->courseid;
        }
        
        $core_user = Users::find('userid=?',$userid)->getOne();
        if($core_user->idst){
            $discipline_id = $core_user->discipline_id;
        }else{
            $discipline_id = $userbc->discipline_id;//专业id
        }
		$dis = Discipline::find("id=?",$discipline_id)->getOne();
		$course_list_temp = $dis->dis_courses;//获取专业对应的课程
		$course_list = array();//课程列表
		$refcode = Refcode::find("code=?","COURSE_TYPE")->order("name")->getAll();//获取ref_code
		$return_txt = "<br/><table class='list_table_course' width='100%' cellpadding='0' cellspacing='0' border='1'><tr><th colspan=6>学生对应课程</th></tr><tr><th style='width:100px;'>课程类型</th><th style='width:100px;'>课程代码</th><th>课程名称</th><th style='width:80px;'>学分</th><th style='width:100px;'>考试性质</th></tr>";
		$course_list = array();
		foreach ($course_list_temp as $k=>$v) {//创建2维数组
			$course_list[$v->ctype][]=$v->course;
		}
          
		$total_score = 0;
		foreach ($refcode as $key=>$value){//完成所有的加载
			if($value->name!=4&&$value->name!=5){
				if(isset($course_list[$value->name])){
				    $return_txt_body = "";
                    $count_course = 0;
                    $temp_count = 0;
					foreach ($course_list[$value->name] as $k=>$v){
                        if(in_array($v->id,$usercourse_list)){
                            $count_course ++ ;
    						$total_score += (int)($v->cscore==null?0:$v->cscore);
    						if($temp_count == 0){
    							$return_txt_body.= "<td style='width:100px;'>".$v->code."</td><td>".$v->name."</td><td style='width:60px;'>".$v->cscore."</td><td style='width:100px;'>".$v->type."</td></tr>";
    							$temp_count ++;
    						}else{
    							$return_txt_body.= "<tr><td style='width:100px;'>".$v->code."</td><td>".$v->name."</td><td style='width:60px;'>".$v->cscore."</td><td style='width:100px;'>".$v->type."</td></tr>";
    						}
						}
					}
                    if($return_txt_body!=""){
                        $return_txt .="<tr><td style='width:100px;' rowspan=".$count_course.">".$value->long_desc."</td>".$return_txt_body;
                    }
				}
			}
		}
		
		$return_txt .= "<tr><td colspan=3 style='font-weight:bold;'>合计</td><td style='width:60px;font-weight:bold;'>".$total_score."</td><td style='width:100px;'>&nbsp;</td></tr>";
		
        if(isset($course_list[4])){//加考课
            $value_info = $refcode[3];
            $count_course = 0;
            $temp_count = 0;
            $return_txt_body = "";
            
			foreach ($course_list[4] as $k=>$v){
			    
                if(in_array($v->id,$usercourse_list)){
                    $count_course ++;
    				if($temp_count == 0){
    					$return_txt_body .= "<td style='width:100px;'>".$v->code."</td><td>".$v->name."</td><td style='width:60px;'>".$v->cscore."</td><td style='width:100px;'>".$v->type."</td></tr>";
    				}else{
    					$return_txt_body .= "<tr><td style='width:100px;'>".$v->code."</td><td>".$v->name."</td><td style='width:60px;'>".$v->cscore."</td><td style='width:100px;'>".$v->type."</td></tr>";
    				}
                    $temp_count ++;
                }
			}
            
            if($return_txt_body!=""){
                $return_txt .="<tr><td rowspan=".$count_course.">".$value_info->long_desc."</td>".$return_txt_body;
            }
        }

        if(isset($course_list[5])){//加考课
            $value_info = $refcode[4];
            $count_course = 0;
            $temp_count = 0;
            $return_txt_body = "";
            
			foreach ($course_list[5] as $k=>$v){
			    
                if(in_array($v->id,$usercourse_list)){
                    $count_course ++;
    				if($temp_count == 0){
    					$return_txt_body .= "<td style='width:100px;'>".$v->code."</td><td>".$v->name."</td><td style='width:60px;'>".$v->cscore."</td><td style='width:100px;'>".$v->type."</td></tr>";
    				}else{
    					$return_txt_body .= "<tr><td style='width:100px;'>".$v->code."</td><td>".$v->name."</td><td style='width:60px;'>".$v->cscore."</td><td style='width:100px;'>".$v->type."</td></tr>";
    				}
                    $temp_count ++;
                }
			}
            
            if($return_txt_body!=""){
                $return_txt .="<tr><td rowspan=".$count_course.">".$value_info->long_desc."</td>".$return_txt_body;
            }
		}
		$return_txt .="</table><br/>";
        $this->_view['course_table']=$return_txt;
		if ($this->_context->detail == 'finish') {
			$this->_view['graduser'] = Leaveschool::find('userid=?', $userid)->getOne();
		}
    }
    
	function actionFileDownload(){
        $filename = "学生报名模板.xls";//输出内容
        Helper_Util::add_dl_header($filename);
        echo file_get_contents(Q::ini('app_config/ROOT_DIR')."/import_tmp/stu_bm.xls");
	}
	
    function actionFileDownloadcid(){
        $filename = "学生准考证导入.xls";//输出内容
        Helper_Util::add_dl_header($filename);
        echo file_get_contents(Q::ini('app_config/ROOT_DIR')."/import_tmp/stueid_bm.xls");
    }
	
	function actionGetfee(){
		$userid = $this->_context->userid;
		$fee_list	=	array();
		if (isset($userid)){
			$fees = FeeDetail::find('userid=?',$userid)->getAll();
			foreach($fees as $key=>$val){
				if(empty($val['order_num']))
					continue;
				$fee_list[$val['order_num']]['totalfee']	=	$val['tfee']['totalfee'];		
				$fee_list[$val['order_num']]['paycd']	=	$val['paycd'];
				$fee_list[$val['order_num']]['fee']	=	$val['fee'];
				$fee_list[$val['order_num']]['pay']	=	$val['pay'];
				$fee_list[$val['order_num']]['remain']	=	$val['fee']-$val['pay'];
				$fee_list[$val['order_num']]['paydate_t']	=	strlen($val['paydate'])?date('Y-m-d',$val['paydate']):"";
				$fee_list[$val['order_num']]['note']	=	$val['note'];
			}
		}
		return  json_encode(array("fee_list"=>$fee_list));
		/*
		$uname = $this->_context->uname;
		if (isset($userid)){
			$total_fee = Fee::find('userid=?',$userid)->getOne();
			$count = count($total_fee);
			if($count == 0){
				return '';
			}
		return  ''.$total_fee->totalfee;
		}
		if (isset($uname)){
			$total_fee = Fee::find('[user.name]=?',$uname)->order('uname')->getOne();
			if(!$total_fee->id()){
				return  '';
			}
			return  ''.$total_fee->totalfee;
		}*/
	}
	
	function actionGetid(){
		$uname = $this->_context->uname;
		
		$admin = $this->_app->currentUser();
        $level = $admin['level'];
        $user_where = "";
        
        if ($level==2 || $level==4) {
        	$user_where .= " and college_id=".$admin['orgid'];
        } else if ($level==3) {
        	$user_where .= " and training_id=".$admin['orgid'];
        }else if ($level==6) {
        	$user_where .= " and training_id in (".$user['mclassids'].")";
        }
        
        $userbc = Userbc::find("status<>3 and name='".$uname."'".$user_where)->order('name')->getAll();
		
        $flag = 1;
        $hit = "";
        $cnt = count($userbc);
        
        if ($cnt==1){
        	$hit = $userbc[0]['userid'];
        } else if ($cnt>1) {
        	$flag = 2;
        	$t = 0;
        	$tmp = "";
        	foreach ($userbc as $i => $v) {
	        	if ($t>0) {$tmp.=",";}
        		$tmp .= $v['userid']; 
        		$t++;
        	}
        	$hit = "登录账号重复，请填入正确的登录账号[".$tmp."]";
        } else if ($cnt==0) {
        	$flag = 0;
        	$hit = "登录账号不存在，请重新输入！";
        } 
        
		echo json_encode(array('flag' => $flag,'hit' => $hit));
		exit;
	}
	function actionSendMM(){
		$app_sms	=	Q::ini('appini/app_sms');
		$ids 	= $_POST['id'];
		$ids 	= explode(";", $ids);
		$user 	= $this->_app->currentUser();
		$msg_org 	= Org::find("id=?",$user['orgid'])->getOne();//用户机构名，保存到短信中

		$post_msgs 	= array();
		foreach ($ids as $i=>$id){
			/*查询用户信息*/
			$userbc 	= Userbc::find("status<>3 and userid='".$id."'")->getOne();
			$uname 		= ($userbc->name);
			$cid 		= $userbc->cid;
			$password 	= $userbc->pass;
			$mobile		= $userbc->mobile;
			$post_msgs[$i]['to_name'] 		= $userbc->name;
			$post_msgs[$i]['to_phone'] 		= $mobile;
			$post_msgs[$i]['msg'] 			= $uname."您好，网络助学平台账号已开通，账号".$userbc->userid."，密码".$password."，网址www.zjnep.com";
			$post_msgs[$i]['req_time'] 		= time();
			$post_msgs[$i]['status'] 		= 0;
			$post_msgs[$i]['retry_count']	= 0;
			$post_msgs[$i]['is_schedule']	= 0;
			$post_msgs[$i]['send_time'] 	= 0;
			$post_msgs[$i]['ispay'] 		= 0;
			$post_msgs[$i]['app_id'] 		= $app_sms['id'];
			$post_msgs[$i]['user_id'] 		= $user['id'];
			$post_msgs[$i]['user_name'] 	= $user['name'];
			$post_msgs[$i]['account'] 		= $user['username'];
			$post_msgs[$i]['app_name'] 		= $app_sms['id'];
			$post_msgs[$i]['module_name']	= $app_sms['module1'];
			$post_msgs[$i]['org_name'] 		= empty($msg_org["name"])?$app_sms['name']:$msg_org["name"];
			$post_msgs[$i]['header']		= md5(md5($mobile."appnum").$app_sms['id']);
			/*添加邮件内容*/
			$mail = new Mail();
			$mail['sendid'] = $user['id'];
			$mail['toname'] = $userbc->name;
			$mail['toaddress'] = $userbc->email;
			$mail['subject'] = $uname."您好:
	你在NEP专本衔接网络助学平台上的账号已经开通";
			$mail['content'] = "下面是您的账号信息：
	用户名：".$userbc->userid."
	密  码：".$password."
	访问地址： http://www.zjnep.com
	感谢您对NEP专本衔接网络助学平台的支持。
		浙江吉博教育科技有限公司
		宁波江南路1689号浙大软件学院北楼4F";
			$mail['status'] = 0;
			$mail['retry_count'] = 0;
			$mail['send_date'] = time();
			$mail['priority'] = 0;
			$mail['cfrom'] = $user['name'];
					
			$enroll = Enroll::find('id=?',$userbc['enroll_id'])->getOne();
			$mail['enroll'] = $enroll['name'];
						
			$org = Org::find('id=?',$userbc['college_id'])->getOne();
			$mail['college'] = $org['name'];
						
			$org = Org::find('id=?',$userbc['training_id'])->getOne();
			$mail['training'] = $org['name'];
						
			$dis = Discipline::find('id=?',$userbc['discipline_id'])->getOne();
			$mail['discipline'] = $dis['name'];
						
			$class = Classinfo::find('id=?',$userbc['classid'])->getOne();
			$mail['classname'] = $class['name'];
			
			$mail->save();
		}
		Helper_Msgfactory::remoteSave($post_msgs);
		exit;
	}

	function sendmsg($id) {
		$app_sms	=	Q::ini('appini/app_sms');
		$user =	 $this->_app->currentUser();
		$msg_org 	= Org::find("id=?",$user['orgid'])->getOne();//用户机构名，保存到短信中
		$userbc 	= Userbc::find("status<>3 and userid='".$id."'")->getOne();
		$uname 		= ($userbc->name);
		$cid 		= $userbc->cid;
		$password 	= $userbc->pass;
		$mobile		= $userbc->mobile;
		$post_msgs	= array();
		$post_msgs[0]['to_name'] 		= $userbc->name;
		$post_msgs[0]['to_phone'] 		= $mobile;
		$post_msgs[0]['msg'] 			= $uname."您好，网络助学平台账号已开通，账号".$userbc->userid."，密码".$password."，网址www.zjnep.com";
		$post_msgs[0]['req_time'] 		= time();
		$post_msgs[0]['status'] 		= 0;
		$post_msgs[0]['retry_count'] 	= 0;
		$post_msgs[0]['is_schedule'] 	= 0;
		$post_msgs[0]['send_time'] 		= 0;
		$post_msgs[0]['ispay']	 		= 0;
		$post_msgs[0]['app_id'] 		= $app_sms['id'];//系统id暂时未做判断  用来判断那个系统发送
		$post_msgs[0]['user_id'] 		= $user['id'];
		$post_msgs[0]['user_name'] 		= $user['name'];
		$post_msgs[0]['account'] 		= $user['username'];
		$post_msgs[0]['app_name'] 		= $app_sms['name'];
		$post_msgs[0]['module_name'] 	= $app_sms['module1'];
		$post_msgs[0]['org_name'] 		= empty($msg_org["name"])?$app_sms['name']:$msg_org["name"];
		$post_msgs[0]['header']			= md5(md5($mobile."appnum").$app_sms['id']);
		Helper_Msgfactory::remoteSave($post_msgs);
		/*添加邮件内容*/
		$mail = new Mail();
		$mail['sendid'] = $user['id'];
		$mail['toname'] = $userbc->name;
		$mail['toaddress'] = $userbc->email;
		$mail['subject'] = $uname."您好:
您在NEP专本衔接网络助学平台上的账号已经开通";
		$mail['content'] = "同学：
您好，在线学习平台已经开通，下面是您的账号信息： 用户名：".$userbc->userid."
密  码：".$password."
访问地址： http://www.zjnep.com，请点击右上角“在线学习平台”进行登录，感谢您对NEP专本衔接网络助学平台的支持。祝你学习愉快！    
技术支持:浙江吉博教育科技有限公司 http://www.jobzj.com.cn";
		$mail['status'] = 0;
		$mail['retry_count'] = 0;
		$mail['send_date'] = time();
		$mail['priority'] = 0;
		$mail['cfrom'] = $user['name'];
				
		$enroll = Enroll::find('id=?',$userbc['enroll_id'])->getOne();
		$mail['enroll'] = $enroll['name'];
					
		$org = Org::find('id=?',$userbc['college_id'])->getOne();
		$mail['college'] = $org['name'];
					
		$org = Org::find('id=?',$userbc['training_id'])->getOne();
		$mail['training'] = $org['name'];
					
		$dis = Discipline::find('id=?',$userbc['discipline_id'])->getOne();
		$mail['discipline'] = $dis['name'];
					
		$class = Classinfo::find('id=?',$userbc['classid'])->getOne();
		$mail['classname'] = $class['name'];
		
		$mail->save();
	}
	
	function actionGetmes() {
		$arrid = $_POST['userid'];
		$arrid = explode(',', $arrid);
		$data = array();
		
		foreach ($arrid as $i => $userid){
		    $core_user = Userbc::find('userid=?',$userid)->getOne();
			$fee = FeeDetail::find('userid=?',$userid)->order('id')->getOne();
	     	if ($fee->userid&&$fee->remain==0) continue;
	    	$data[$i]['userid'] = $userid;
	    	$data[$i]['name'] = $core_user['name'];
	    	$data[$i]['cid'] = $core_user['cid'];
	    	
	    	if (!$fee->userid) {
		    	$data[$i]['fee'] = "无缴费记录";
		    	$data[$i]['remain'] = "-";
	    	} else {
	    		$data[$i]['fee'] = $fee['fee'];
		    	$data[$i]['remain'] = $fee['remain'];
	    	}
		}
	    return json_encode(array('user' => $data));
	}
	
	function actionPreschool(){
		$this->_view['subject']="毕业学校";
		if ($this->_context->isPOST())
		{
			$stuname = $_POST['name'];
			$preschool = Users::find("firstname=?",$stuname)->getOne();
			$schname = $preschool->preschool;
			if (!empty($schname)){
				return json_encode(array("name"=>$schname));
			}else{
				return json_encode(array("name"=>"空"));
			}
			die;
		}
	}
    /**
     * **
     *批量修改 
     *
     */
    function actionsetusercoursebatch(){
        $course_info = $_POST['course_info'];
        $user_info = $_POST['user_info'];
        $courses = explode(",",$course_info);
        $users = explode(",",$user_info);
        Usercourse::meta()->destroyWhere("username in ('".(implode("','",$users))."') and courseid not in (".(implode(",",$courses)).") and courseid not in (select id from lc_course where liberty_type=1)");//删除选中的人课关系
        foreach($users as $k => $v){//循环添加
        	$user_info = User::find("userid=?",$v)->getOne();
        	$uc_temp = Usercourse::find("username=?",$v)->getAll();
        	$uc_has_arr = array();
        	foreach ($uc_temp as $key => $val) {
        		$uc_has_arr[] = $val->courseid;
        	}
            foreach($courses as $key => $value){
            	if(!in_array($value, $uc_has_arr)){
            		$usercourse = new Usercourse();
            		if($user_info->idst)$usercourse->userid = $user_info->idst;
	                $usercourse->username = $v;
	                $usercourse->courseid = $value;
					$usercourse->back_only = $user_info->class->get_back_only($value);
					$usercourse->class_id = $user_info['classid'];
                    $usercourse->training_id = $user_info['training_id'];
	                $usercourse->save();
            	}
            }
        }
    }
	
	function actiongetNameByID(){
		$userID = $_POST['infoid'];
		//如果是学习中心 或者主考院校的话 先获取所有的自己的学生 
		$user_admin = $this->_app->currentUser();
		$info_user = "";
		if($user_admin['level']==2 || $user_admin['level']==4){//主考院校
			$userList = Userbc::find("college_id=".$user_admin['orgid'])->order('name')->setColumns("userid")->getAll();
			foreach ($userList as $k=>$v){
				$info_user .= "'".$v->userid."',";
			}
		}else if($user_admin['level']==3){
			$userList = Userbc::find("training_id=".$user_admin['orgid'])->order('name')->setColumns("userid")->getAll();
			foreach ($userList as $k=>$v){
				$info_user .= "'".$v->userid."',";
			}
		}
		$sql_where="";
		if($info_user!=""){
			$sql_where = " and userid in (".mb_substr($info_user, 0,mb_strlen($info_user)-1).")";
		}
		$user_info = Userbc::find("userid='".$userID."'".$sql_where)->order('name')->setColumns("userid,name,discipline_id")->getOne();
		$return_name = "";
		$flag = "T";
		if(!empty($user_info->userid)){
			$return_name = $user_info->name;
		}else{
			$return_name = "登录账号不存在或非系统确认用户,请重新输入。";
			$flag = "F";
		}
		$return_info = json_encode(array("username"=>$return_name,"flag"=>$flag));
		echo $return_info;
		exit;
	}
	function actiongetIDByName(){
		$userName = $_POST['infoid'];//如果是学习中心 或者主考院校的话 先获取所有的自己的学生 
		$user_admin = $this->_app->currentUser();
		$info_user = "";
		if($user_admin['level']==2 || $user_admin['level']==4){//主考院校
			$userList = Userbc::find("college_id=".$user_admin['orgid'])->setColumns("userid")->getAll();
			foreach ($userList as $k=>$v){
				$info_user .= "'".$v->userid."',";
			}
		}else if($user_admin['level']==3){
			$userList = Userbc::find("training_id=".$user_admin['orgid'])->setColumns("userid")->getAll();
			foreach ($userList as $k=>$v){
				$info_user .= "'".$v->userid."',";
			}
		}
		$sql_where="";
		if($user_admin['level']==6){
			$sql_where = " and training_id in(".$user['mclassids'].")";
		}
		
		if($info_user!=""){
			$sql_where = " and userid in (".mb_substr($info_user, 0,mb_strlen($info_user)-1).")";
		}
		$user_info = Userbc::find("name='".$userName."'".$sql_where)->setColumns("userid,discipline_id")->getAll();
		$return_name = "";
		$flag = "T";
		if(count($user_info)==1){
			$return_name = $user_info[0]->userid;
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
		$return_info = json_encode(array("userid"=>$return_name,"flag"=>$flag));
		echo $return_info;
		exit;
	}

	function actionSendset() {
		$app_sms	=	Q::ini('appini/app_sms');
		$sms_set 	= Helper_Msgfactory::remoteSwitchStatus($app_sms['id']);
		$mail_set = Refcode::find('code=?', 'SWITCH_MAIL')->getOne();
		if (!$this->_context->isPOST()) {
			$data = array('sms'=>(bool)$sms_set[0]['name'], 'mail'=>(bool)$mail_set->name);
			echo json_encode($data);
			die;
		}
		$sms = empty($_POST['sms']) ? 0 : 1;
		$mail = empty($_POST['mail']) ? 0 : 1;
		$db = QDB::getConn();
		$now = time();
		Helper_Msgfactory::remoteSwitch($sms, $app_sms['id']);
		$db->execute("update sms_ref_code set name='$mail', update_date=$now where id=".$mail_set->id);
		
		/********远程保存日志********/
		$user	= $this->_app->currentUser();
		$note	= $sms==1?"开启":"关闭";
		$post_log	= array(
						"op_id"			=> $user['id'],
						"op_username"	=> $user['username'],
						"op_account"	=> $user['name'],
						"op_time"		=> time(),
						"op_ip"			=> $_SERVER['REMOTE_ADDR'],
						"app_id"		=> $app_sms['id'],
						"app_name"		=> $app_sms['name'],
						"note"			=> $note."短信开关"
						);
		Helper_Msgfactory::remoteSaveLog($post_log);
	}
}

