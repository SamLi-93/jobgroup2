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
 #    2011/7/14   Gu wen         Created.
 #
 ###########################################################################
 */


/**
 * Controller_Admin 控制器
 */
class Controller_Admin extends Controller_Abstract
{

	function actionIndex()
	{
		$quser = empty($this->_context->quser)?"":$this->_context->quser;
        $qname = empty($this->_context->qname)?"":$this->_context->qname;
        $qlevel = empty($this->_context->qlevel)?"":$this->_context->qlevel;
        $qorg = empty($this->_context->qorg)?"":$this->_context->qorg;
        $qisopen = empty($this->_context->qisopen)?"":$this->_context->qisopen;
        $this->_view['quser'] = $quser;
        $this->_view['qname'] = $qname;
        $this->_view['qlevel'] = $qlevel;
        $this->_view['qorg'] = $qorg;
        $this->_view['qisopen'] = $qisopen;
        $user = $this->_app->currentUser();
		$where=" [isdelete]=0 ";
		$form = new Form_Admin('');
		$user = $this->_app->currentUser();
		$level=$user['level'];
		$page = (int)$this->_context->page;
		if ($page==0) $page++;
        $limit = $this->_context->limit ? $this->_context->limit : 15;
        $sql_where = "";
        if(!empty($quser)){
        	$sql_where .= " and username like '%".$quser."%'";
        }
        if(!empty($qname)){
        	$sql_where .= " and [name] like '%".$qname."%'";
        }
        if(!empty($qlevel)){
        	$sql_where .= " and level =".$qlevel;
        }
        if(!empty($qorg)){
        	$org_list = Org::find("name like '%".$qorg."%'")->getAll();
        	$org_arr = array();
        	$org_arr[] = 0;
        	foreach ($org_list as $key => $val) {
        		$org_arr[] = $val['id'];
        	}
        	$sql_where .= " and orgid in (".implode(',', $org_arr).") ";
        }
        if(!empty($qisopen)){
        	$sql_where .= " and valid=".($qisopen==2?0:1);
        }
        if($level!=4){
            $q = Admin::find('[isdelete]=?'.$sql_where ,0)->joinLeft('sms_org','name as oname','sms_admin.orgid=sms_org.id')->order(array(new QDB_Expr('sms_admin.level'),new QDB_Expr('sms_org.name')))->limitPage($page, $limit);
		}else{		
            $org_id=$user["orgid"];
            $sql="select id from sms_admin where orgid in (select id from sms_org where id=".$org_id." or pid=".$org_id.") and id<>".$user['id'];
            $db = QDB::getConn();
            $list = $db->getAll($sql);
            foreach ($list as $key=>$val){
                $log_id[]=$val["id"];
            }         
            if(isset($log_id)){
                $arr=implode(",", $log_id);
                $where.=" and [id] in(".$arr.")";
            }
		    $q = Admin::find($where.$sql_where)->joinLeft('sms_org','name as oname','sms_admin.orgid=sms_org.id')->order(array(new QDB_Expr('sms_admin.level'),new QDB_Expr('sms_org.name')))->limitPage($page, $limit);
        }
		$this->_view['pager'] = $q->getPagination();
		$this->_view['list'] = $q->getAll();
		$this->_view['start'] = ($page-1)*$limit;
		$level_list = Q::ini('appini/admin_levels');	
		$this->_view['level_list'] = $level_list;
		$this->_view['subject'] = "账号管理";
		$user = $this->_app->currentUser();
		$this->_view['userlevel'] = $user['level'];
		
	}
	
	function actionCreate(){
		$this->_pagecode = $this->_context->isGET() ? '03802' : '03803';
		// 构造表单对象
		$form = new Form_Admin('');
		$user = $this->_app->currentUser();
		$level_user=$user['level'];
	 	
		if ($this->_context->isPOST() && $form->validate($_POST))
		{
			// 创建 admin 对象并保存
			$admin = new Admin($form->values());				
		    // 	创建成功后，重定向浏览器
			$args = Admin::find('username=?&&isdelete=0',$admin->username)->getAll();
			$num = count($args);
			$user = $this->_app->currentUser();
			$admin->update_id = $user['id'];
			$admin->update_date = time();
			if($form['newpassword']==""){
				$form['newpassword']->invalidate("确认密码不为空");
            }

			if ($num>0) {
				$form['username']->invalidate("{$admin->username}已经存在");
			}else{
				if($admin['level']==3){
					$admin['orgid']=$form['orgidxx']->value;
                }else if($admin['level']==5){//这里应该以班主任的主考院校ID作为
                    $admin['orgid']=$form['orgid']->value;
					$mclassids = $form['classinfoid']->value;
                    $admin['mclassids']=$mclassids ? implode(',', $mclassids) : '';
                }else if($admin['level']==6){//这里应该以次级管理员对应的主考院校ID作为
                    $admin['orgid']=0;
                    $mclassids = $form['orgid']->value;
                    $admin['mclassids']=$mclassids ? implode(',', $mclassids) : '';
                }
				$admin->save();//创建成功后，重定向浏览器
				return $this->_redirect(url(''));
			}
		}
		// 将表单对象传递给视图
		$this->_view["level_user"]=$level_user;
		$this->_view['form'] = $form;
		$this->_view['subject'] = "系统账号";
		$this->_view['edit']=0;
        $this->_view['fpower']=$user['fpower'];
	}
	
	
	function actionEdit(){
		$this->_pagecode = $this->_context->isGET() ? '03804' : '03805';
		$id = $this->_context->id;	
	 	$admin = Admin::find()->getById($id);	 	
	 	$admin_level=$admin["level"];	 	
	 	$user = $this->_app->currentUser();	 	
		// 构造表单对象
		$form = new Form_Admin('');
		// 修改表单标题
        $this->_view['fpower']=$user['fpower'];
		$this->_view['subject'] = "账号管理";
	    $form->_subject = "修改账号";
	    $form->edit=1;
		if ($this->_context->isPOST())
		{
			if($form){
				unset($form['newpassword']);
			}
			// changeProps() 方法可以批量修改对象的属性，但不会修改只读属性的值
			if($form->validate($_POST)){
				$data = $form->values();
				if($form['orgid']->value==0){
					unset($data['orgid']);
				}
				if($form['password']->value==""){
					unset($data['password']);
				}
				$admin->changeProps($data);
			    // 保存并重定向浏览器
				$args = Admin::find('username=?&&isdelete=0?&&id!=?',$admin->username,0,$id)->getCount();
				if ($args>0) {
					$form['username']->invalidate("{$admin->username}已经存在了");
				}else{
					if($admin['level']==3){
					    $admin['orgid']=$form['orgidxx']->value;
                    }else if($admin['level']==5){
						//$admin['orgid']=0;//这里应该以班主任的主考院校ID作为
                        $admin['orgid']=$form['orgid']->value;
						$mclassids = $form['classinfoid']->value;
						$admin['mclassids']=$mclassids ? implode(',', $mclassids) : '';
                    }else if($admin['level']==6){//这里应该以次级管理员对应的主考院校ID作为
                        $admin['orgid']=0;
                        $mclassids = $form['orgid']->value;
                        $admin['mclassids']=$mclassids ? implode(',', $mclassids) : '';
                    }
                    $admin->save();
					// 	创建成功后，重定向浏览器
					return $this->_redirect(url('admin'));
				}
			}else{
				// 如果不是 POST 提交，则把对象值导入表单
				if(isset($form['password'])){
				$form['password']->value="";
				}
			}
				
		}elseif (!$this->_context->isPOST()){
			// 如果不是 POST 提交，则把对象值导入表单
			$form->import($admin);
			if($admin->level==3){  //学习中心
				$form['orgidxx']->value=$admin->orgid;
				$orgid_temp=Org::find("id=?",$admin->orgid)->getOne();
				$form['orgid']->value=$orgid_temp->pid;
            }else if($admin->level==5){  //班主任
				$mclassids = $admin->mclassids ? explode(',', $admin->mclassids) : array();
                $form['classinfoid']->value=json_encode($mclassids);
				if ($mclassids) {
					$class = Classinfo::find("id=?",$mclassids[0])->getOne();
					$form['orgid']->value=$class->college_id;
					$form['orgidxx']->value=$class->training_id;
				}
            }else if($admin->level==6){  //二级管理员
                $mclassids = $admin->mclassids ? explode(',', $admin->mclassids) : array();
                $form['orgid']->value=json_encode($mclassids);
            }
			$form['password']->value="";
        }
		$this->_view['form'] = $form;
		$this->_view['edit']=1;
		$this->_view['level']=$admin_level;
		$this->_view['user_level']=$user['level'];
		// 重用 create 动作的视图
		$this->_viewname = 'create';
    }

	function actionDelete(){
		$this->_pagecode = '03806';
	    $admin = Admin::find('id = ?', $this->_context->id)->getOne();
	 	$admin->isdelete = 1;
	 	$admin->username = time()."_d_".$admin->username;
	 	$admin->save();
		return $this->_redirect(url('admin'));
	}
    /**
     * 登录
     */
    function actionLogin()
    {
		$this->_pagecode = $this->_context->isGET() ? '00101' : '00102';
        $form = new Form_UserLogin(url('admin/login'));
        if ($this->_context->isPOST() && $form->validate($_POST))
        {
            try
            {
                // 使用 acluser 插件的 validateLogin() 方法验证登录并取得有效的 user 对象
                $user = Admin::meta()->validateLogin($form['username']->value, $form['password']->value);                
                if($user['isdelete']==1){
                    $form['password']->invalidate("您的账号已被删除！");
                }else if($user['valid']==0){
                    $form['password']->invalidate("您的账号处于无效状态！");
                }else{
                    // 将登录用户的信息存入 SESSION，以便应用程序记住用户的登录状态
                    $this->_app->changeCurrentUser($user->aclData(), 'MEMBER');
                    // 登录成功后，重定向浏览器
                    return $this->_redirect( url('default'));
                } 
            }
            catch (AclUser_UsernameNotFoundException $ex)
            {
                $form['username']->invalidate("您输入的登录账号 {$form['username']->value} 不存在");
            }
            catch (AclUser_WrongPasswordException $ex)
            {
                $form['password']->invalidate("您输入的密码不正确");
            }
        }

        $this->_view['form'] = $form;
        $this->_viewname = 'register';
    }

    /**
     * 注销
     */
    function actionLogout()
    {
		$this->_pagecode = '00103';
        $this->_app->cleanCurrentUser();
        return $this->_redirect( url('admin/login'));
    }

    
    
    function actionProfile(){
		$this->_pagecode = $this->_context->isGET() ? '03401' : '03402';
    	$user = $this->_app->currentUser();
    	$id = $user['id'];
	 	$admin = Admin::find()->getById($id);
		// 构造表单对象
        $form = new Form_Profile('');        
		// 修改表单标题
		$this->_view['subject'] = "账号管理";
        $form->_subject = "我的账号";
		if ($this->_context->isPOST()&&$form->validate($_POST))
		{
            //$admin = Admin::find("id=?",$this->_context->id)->getOne();
			$admin->username = $this->_context->username;
			$admin->name = $this->_context->name;
			if ($this->_context->password) {
				$admin->password = $this->_context->password;
			}
			$admin->save();
            $this->_view['suc']="suc";			    		
        }
        $admin->password = "";
        $form->import($admin);
		$this->_view['form'] = $form;
		$this->_view['edit']=2;
		// 重用 create 动作的视图
		$this->_viewname = 'create';
    }

    // function actionAdminlist(){
    //     $user = $this->_app->currentUser();
    //     $sql_admin = "";
    //     if($user['level']==5){//班主任
    //         $sql_admin = " and id=".$user['id'];
    //     }else if($user['level']==4){//主主考院校
    //         $sql_admin = "  and (orgid in ( select id from sms_org where pid=".$user['orgid'].") or orgid in (select id from sms_class where college_id=".$user['orgid'].") or id=".$user['id'].")";
    //     }else if($user['level']==2){//主考院校
    //          $sql_admin = " and type=3 and (orgid in ( select id from sms_org where pid=".$user['orgid'].") or orgid in (select id from sms_class where college_id=".$user['orgid'].") or id=".$user['id'].")";
    //     }else if($user['level']==3){//学习中心
    //         $sql_admin = "  and type=3 and (orgid in (select id from sms_class where college_id=".$user['orgid'].") or id=".$user['id'].")";           
    //     }else if($user['level']==6){//学习中心
    //         $sql_admin = "  and id=".$user['id'];
    //     }
    //     $admin_list = Admin::find("valid=1 and isdelete=0 ".$sql_admin)->getAll();

    //     $this->_view['teacher'] = $admin_list;
    // }

    
    

}


