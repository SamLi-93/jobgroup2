<?php

class Control_StuSearch extends QUI_Control_Abstract
{
    function render()
    {
        foreach ($_GET as $k=>$v) {
            $this->_view[$k] = $v;
        }
        $app = Q::registry('app');
		$user = $app->currentUser();
		$edu_where = "";
        $len_where = "";
        $dis_where = "";
		$classinfo_where ="";
		$enroll_where = "";
		if($user['level']==2 || $user['level']==4){//主考院校
			$edu_where = " and id=".$user['orgid'];
            $len_where = " and pid=".$user['orgid'];
            $dis_where = " and college_id=".$user['orgid'];
            $classinfo_where = " and college_id=".$user['orgid'];
			$this->_view['ulev'] = 2;
			$this->_view['qorgedu'] = $user['orgid'];
		}else if($user['level']==3){
			$edu_where = " and id=(select pid from sms_org where id=".$user['orgid'].")";
            $len_where = " and id=".$user['orgid'];
            $dis_where = " and college_id=".(Org::find("id=?",$user['orgid'])->getOne()->pid);
			$classinfo_where = " and training_id=".$user['orgid'];
			$this->_view['ulev'] = 3;
			$this->_view['qorgedu'] = Org::find()->getById($user['orgid'])->pid;
			$this->_view['qorglen'] = $user['orgid'];
        }else if($user['level']==5){//班主任
			$classes = Classinfo::find("id in (".$user['mclassids'].")")->getAll();
			$class = $classes[0];
            //$class = Classinfo::find("id=?",$user['orgid'])->getOne();
			$edu_where = " and id=".$class->college_id;
            $len_where = " and id=".$class->training_id;
            $dis_where = " and id in (".implode(',', $classes->values('discipline_id')).")";
            $enroll_where = " and id in (".implode(',', $classes->values('enroll_id')).")";
			$classinfo_where = " and id in (".$user['mclassids'].")";
			$this->_view['ulev'] = 5;
			$this->_view['qorgedu'] = $class->college_id;
            $this->_view['qorglen'] = $class->training_id;
        }else if($user['level']==6){//二级管理员
            $edu_arr = Helper_Util::getCollegeByOrgids($user['mclassids']);
            $dis_arr = Helper_Util::getDisplinByOrgids($user['mclassids']);
            $cls_arr = Helper_Util::getClassinfoByOrgids($user['mclassids']);
            
            $edu_where = empty($edu_arr)?" and 1=0":" and id in (".implode(',', $edu_arr).")";
            $len_where = " and id in(".$user['mclassids'].")";
            $dis_where = empty($dis_arr)?" and 1=0":" and id in (".implode(',', $dis_arr).")";
            $classinfo_where = empty($cls_arr)?" and 1=0":" and id in (".implode(',', $cls_arr).")";
            $this->_view['ulev'] = 6;
        }
		$enroll_list = Enroll::find('isdelete=0'.$enroll_where)->order("name")->getAll()->toHashMap("id","name");//入学批次
		$org_edu_list = Org::find('isdelete=0 and type=1 '.$edu_where)->order("name")->getAll()->toHashMap("id","name");//主考院校
		$org_len_list = Org::find('isdelete=0 and type=2 '.$len_where)->order("name")->getAll()->toHashMap("id","name");//学习中心
		$discipline_list = Discipline::find('isdelete=0'.$dis_where)->order("name")->getAll();//专业
		$discipline_array = array();
        foreach ($discipline_list as $k=>$v){
            if($user['level']==1||$user['level']==6){
                $discipline_array[$v->id] = $v->college->shortname." | ".$v->name;
            }else{
                $discipline_array[$v->id] = $v->name;
            }
			
		}

		$discipline_list = $discipline_array;
		$classinfo_list = Classinfo::find('isdelete=0 '.$classinfo_where)->order("name")->getAll()->toHashMap("id","name");//班级
		$this->_view['enroll_list']=$enroll_list;
		$this->_view['org_edu_list']=$org_edu_list;//主考院校
		$this->_view['org_len_list']=$org_len_list;//学习中心 第一个
		$this->_view['discipline_list']=$discipline_list;
		$this->_view['classinfo_list']=$classinfo_list;
		$edu_len_list = array();
		foreach ($org_edu_list as $k=>$v){
			$edu_len_list[$k] = Org::find('isdelete=0 and type=2 '.$len_where)->order("name")->getAll()->toHashMap("id","name");//学习中心
		}
		$this->_view['edu_len_list']=$edu_len_list;//其他对应主考院校的学习中心

        //page
        $this->_view['uri'] = preg_replace('/\/page\/\d+|\?.+/', '', QContext::instance()->requestURI());

        //export
        $this->_view['export'] = $this->get('export');
        $this->_view['exportfeemodel'] = $this->get('exportfeemodel');
        $this->_view['btn_prama'] = $this->get('btn_prama');
        $exurl = $this->get('exurl');
        $exurl = $exurl ? $exurl : url('/export');
        $this->_view['exurl'] = $exurl . '?' . $_SERVER['QUERY_STRING'];
        $exfeemodelurl = $this->get('exfeemodelurl');
        $exfeemodelurl = $exfeemodelurl ? $exfeemodelurl : url('/exportfeemodel');
        $this->_view['exfeemodelurl'] = $exfeemodelurl . '?' . $_SERVER['QUERY_STRING'];
        $reseturl=$this->get('reseturl');
        $add=$this->get('add');
        $this->_view['add']=isset($add)?true : false;
        $this->_view['reseturl'] = empty($reseturl) ? url('') : $reseturl;
        $out = $this->_fetchView(dirname(__FILE__) . '/stusearch_view');

        return $out;
    }

    /*控件的过滤条件
     *@sql_where 传入的初始条件
     *@tbl 关联表 默认为空
     */
    public static function filterCond($sql_where, $tbl='') {
        if ($tbl) $tbl.='.';
        $app = Q::registry('app');
        $user = $app->currentUser();
        if($user['level']==2 || $user['level']==4){//主考院校
            $sql_where .= " and [{$tbl}college_id]=".$user['orgid'];
        }else if($user['level']==3){
            $sql_where .= " and [{$tbl}training_id]=".$user['orgid'];
        }else if($user['level']==5){
            $sql_where .= " and [{$tbl}classid] in (".$user['mclassids'].")";
        }else if($user['level']==6){
            $sql_where .= " and [{$tbl}training_id] in (".$user['mclassids'].")";
        }
        $context = QContext::instance();

        $qenroll = empty($context->qenroll)?$context->qenroll:trim($context->qenroll);
        $qorgedu = empty($context->qorgedu)?$context->qorgedu:trim($context->qorgedu);
        $qorglen = empty($context->qorglen)?$context->qorglen:trim($context->qorglen);
        $qdiscipline = empty($context->qdiscipline)?$context->qdiscipline:trim($context->qdiscipline);
        $qclassinfo = empty($context->qclassinfo)?$context->qclassinfo:trim($context->qclassinfo);
        $qname = empty($context->qname)?$context->qname:trim($context->qname);
        $quserid = empty($context->quserid)?$context->quserid:trim($context->quserid);
        $qcid = empty($context->qcid)?$context->qcid:trim($context->qcid);
        $paycd = empty($context->paycd)?$context->paycd:trim($context->paycd);
        if($qenroll!=null&&$qenroll!=""){
            $sql_where .= " and [{$tbl}enroll_id]=".$qenroll;
        }
        if($qorgedu!=null&&$qorgedu!=""){
            $sql_where .= " and [{$tbl}college_id]=".$qorgedu;
        }
        if($qorglen!=null&&$qorglen!=""){
            $sql_where .= " and [{$tbl}training_id]=".$qorglen;
        }
        if($qdiscipline!=null&&$qdiscipline!=""){
            $sql_where .= " and [{$tbl}discipline_id]=".$qdiscipline;
        }
        if($qclassinfo!=null&&$qclassinfo!=""){
            $sql_where .= " and [{$tbl}classid]=".$qclassinfo;
        }
        if($qname!=null&&$qname!=""){
            $sql_where .= " and [{$tbl}name] like '%".s($qname)."%'";
        }
        if($quserid!=null&&$quserid!=""){
            $sql_where .= " and [{$tbl}userid] like '%".s($quserid)."%'";
        }
        if($qcid!=null&&$qcid!=""){
            $sql_where .= " and [{$tbl}cid] like '%".s($qcid)."%'";
        }
        return $sql_where;
    }
}
