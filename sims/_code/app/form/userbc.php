<?php

class Form_Userbc extends QForm
{
    public $modle;
    function __construct($action)
    {
        // 调用父类的构造函数
        parent::__construct('form_userbc', $action);

        // 从配置文件载入表单
        $filename = rtrim(dirname(__FILE__), '/\\') . DS . 'userbc_form.yaml';
        $this->loadFromConfig(Helper_YAML::loadCached($filename));
        $this->addValidations(Userbc::meta());
        $this['userid']->addValidations(array($this, 'checkuserid'), null, '登录账户已经存在');
        
        $app = Q::registry('app');
        $user = $app->currentUser();
        if(QContext::instance()->id){
            $user_info = Users::find("userid=?",QContext::instance()->id)->getOne();
			if (!$user_info->id()) $user_info = Userbc::find("userid=?",QContext::instance()->id)->getOne();

            $this['college_id']->items = Org::find("id=?",$user_info->college_id)->getAll()->toHashMap("id","name");        
            $this['training_id']->items = Org::find("id=?",$user_info->training_id)->getAll()->toHashMap("id","name");       	
            $discipline = Discipline::find("id=?",$user_info->discipline_id)->getAll();
            $q1_list = array();
            foreach ($discipline as $v=>$k){
                if($user['level']!=1){
                    $q1_list[$k->id] = $k->name; 
                }else{
                    $q1_list[$k->id] = $k->college->shortname." | ".$k->name;
                }
            }
            $this['discipline_id']->items=$q1_list;        
        }else{
            $edu_where = "";
            $len_where = "";
            $dis_where = "";
            $classinfo_where ="";
            $level = 1;
            if($user['level']==2 || $user['level']==4){//主考院校
                $edu_where = " and id=".$user['orgid'];
                $len_where = " and pid=".$user['orgid'];
                $classinfo_where = " and college_id=".$user['orgid'];
                $dis_where = " and college_id=".$user['orgid'];
                $dis_where = " and college_id=".$user['orgid'];
                $level = 2;
            }else if($user['level']==3){
                $edu_where = " and id=(select pid from sms_org where id=".$user['orgid'].")";
                $len_where = " and id=".$user['orgid'];
                $classinfo_where = " and training_id=".$user['orgid'];
                $dis_where = " and college_id=(select pid from sms_org where id=".$user['orgid'].")";
                $college_id = Org::find()->getById($user['orgid'])->pid;
                $dis_where = " and college_id=".$college_id;
                $level = 3;
            }
            
            if ($level!=1) {
                $var = Org::find('isdelete=0 and type=1 '.$edu_where)->getOne();
                $this['college_id']->set('items', array($var['id'] => $var['name']));
                $this['college_id']->set('value',$var['id']);
                if ($level!=3) {
                    $this['training_id']->set('items', array(""=>"-请选择-")+Org::find('isdelete=0 and type=2 '.$len_where)->order('name')->getAll()->toHashMap("id","name"));
                } else {
                    $var = Org::find('isdelete=0 and type=2 '.$len_where)->getOne();
                    $this['training_id']->set('items', array($var['id'] => $var['name']));
                    $this['training_id']->set('value',$var['id']);
                }
            } else {
                $this['college_id']->set('items', Org::find('isdelete=0 and type=1 '.$edu_where)->order('name')->getAll()->toHashMap("id","name"));
                $this['training_id']->set('items', array(""=>"-请选择-")+Org::find('isdelete=0 and type=2 '.$len_where)->order('name')->getAll()->toHashMap("id","name"));
            }
            $discipline = Discipline::find('isdelete=0'.$dis_where)->order('name')->getAll();
            $q1_list = array();
            if($level!=1){
                foreach ($discipline as $v=>$k){
                    $q1_list[$k->id] = $k->name;
                }
            }else{
                foreach ($discipline as $v=>$k){
                    $q1_list[$k->id] = $k->college->shortname." | ".$k->name;
                }
            }
            $discipline = $q1_list;
            $this['discipline_id']->set('items', array(""=>"-请选择-")+$discipline);
        }  
    }
    
    function checkuserid($self){//是否存在同样的id
    	if(empty($this->modle['userid'])){//新增
    		$user = User::find("userid=?",$self)->getOne();
    		if(!empty($user->userid)) return false;
    	}else{//修改
    		if($self!=$this->modle['userid']){
	    		$user = User::find("userid=?",$self)->getOne();
	    		if(!empty($user->userid)) return false;
    		}
    	}
    	return true;
    }
    
}

