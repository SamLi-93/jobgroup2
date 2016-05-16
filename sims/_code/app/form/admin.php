<?php

class Form_Admin extends QForm
{
    function __construct($action)
    {
        // 调用父类的构造函数
        parent::__construct('form_admin', $action);

        // 从配置文件载入表单
        $filename = rtrim(dirname(__FILE__), '/\\') . DS . 'admin_form.yaml';
        $this->loadFromConfig(Helper_YAML::loadCached($filename));
        $this->addValidations(Admin::meta());
        
   	 	$app = Q::registry('app');
        $user = $app->currentUser();
        $level=$user['level'];
		$orgid=$user['orgid'];
		$level_name=$this->get_level();
		//管理员登录看到所有学习中心
		//主主考院校登录只能看到自己下面的主考院校和学习中心
        $this['level']->items=$level_name;		
        
        $this['newpassword']->addValidations(array($this, 'checkpassword'), null, '两次输入的密码不同！');
        $this['password']->addValidations(array($this, 'checkpwd'), null, '密码不能为空！');
        $this['newpassword']->addValidations(array($this, 'checknpwd'), null, '确认密码不能为空！');

     	$this['orgid']->addValidations(array($this, 'checkorgid'), null, '所属机构不能为空！');
     	
      	// $org = Org::find('isdelete=0 and type=1 ')->getAll();
       //  $level=QContext::instance()->level;
        
      	if($level==3){
    		 $this['orgidxx']->addValidations(array($this, 'checkorgidxx'), null, '学习中心不能为空！');
      	}
      	
    }

	function checkpassword() {
		$flag = true;
		$context=QContext::instance();
		if( $context->newpassword !=  $context->password){
		    return $flag = false;
        }else{
            return $flag;
        }		
    }

    function checkpassword_old() {
		$flag = true;
        $context=QContext::instance();
        $info = Admin::find("id=?",$context->id)->getOne();
		if( $info->password != md5($context->oldpassword)){
		    return $flag = false;
        }else{
            return $flag;
        }		
    }

	function checknpwd() {
		$context=QContext::instance();
		if( !empty($context->newpassword)){
			return true;
        }else{
            return false;
        }		
	}
	
	function checkorgidxx(){
		$flag = true;
		if(QContext::instance()->orgidxx=="0"){
			return $flag = false;
		}else{
		    return $flag;
    	}	
	}
	
	function checkorgid($v){
		$level=QContext::instance()->level;
		if($level!=1 && empty($v)){
			return false;
		}else{
			return true;
		}
	}
	
	function checkpwd(){
		$password=QContext::instance()->password;
		if($this->edit==1){
				return true;
		}else{
			if(empty($password)){
				return false;
			}else{
				return true;
			}
		}
	}
		
	function get_level() {
		$app = Q::registry('app');
        $user = $app->currentUser();
        $level=$user['level'];
        if($level==1){
       		$level_user[] = Q::ini('appini/admin_levels');
        	return $level_user[0];
        }else if($level==4){
        	$level_2[] = Q::ini('appini/admin_levels');
        	$level_user=array(
        		'0'=>$level_2[0][0],
                '2'=>$level_2[0][2],
                '3'=>$level_2[0][3],
                '5'=>$level_2[0][5]
        	);
        	return $level_user;
        }
       
    }

}