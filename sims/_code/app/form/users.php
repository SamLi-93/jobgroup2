<?php

class Form_Users extends QForm
{
    function __construct($action)
    {
        // 调用父类的构造函数
        parent::__construct('form_users', $action);

        // 从配置文件载入表单
        $filename = rtrim(dirname(__FILE__), '/\\') . DS . 'users_form.yaml';
        $this->loadFromConfig(Helper_YAML::loadCached($filename));
        $this->addValidations(Users::meta());
        $this['dazhuan']->addValidations(array($this, 'checkdazhuan'), null, '大专专业不能为空');
			
       //目前学历
        $this['education']->set('items', array(""=>"--请选择--",'1'=>'初中以下','2'=>'初中','3'=>'高中或中专中技','4'=>'大专','5'=>'本科','6'=>'本科以上'));
        
        //政治面貌
        $this['politic_status']->set('items', array(""=>"--请选择--",'1'=>'群众','2'=>'团员','3'=>'党员'));

        
        $user = Q::registry('app')->currentUser();
		$level = $user['level'];
        $user_info = Users::find("idst=?",QContext::instance()->idst)->getOne();
        $this['enroll_id']->items = Enroll::find("id=?",$user_info->enroll_id)->getAll()->toHashMap("id","name");
        $this['college_id']->items = Org::find("id=?",$user_info->college_id)->getAll()->toHashMap("id","name");        
        $this['training_id']->items = Org::find("id=?",$user_info->training_id)->getAll()->toHashMap("id","name");
       	
       $discipline = Discipline::find("id=?",$user_info->discipline_id)->getAll();
        $q1_list = array();
        foreach ($discipline as $v=>$k){
            if($level!=1){
                $q1_list[$k->id] = $k->name; 
            }else{
                $q1_list[$k->id] = $k->college->shortname." | ".$k->name;
            }
		}
        $this['discipline_id']->items=$q1_list;        
        $this['classid']->items = Classinfo::find("id=?",$user_info->classid)->getAll()->toHashMap("id","name");
    }

	function checkdazhuan(){
    		$context=QContext::instance();
			return $context->hasdegree!=1 || !empty($context->dazhuan);
    }
 
    
}

