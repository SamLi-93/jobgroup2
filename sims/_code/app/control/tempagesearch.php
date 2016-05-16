<?php


class Control_TempageSearch extends QUI_Control_Abstract{
	function render(){
		foreach ($_GET as $key => $val){
			$this->_view[$key] = $val;
		}
		
		$course_list = Course::find('isdelete=0')->order("CONVERT(name USING gbk) desc")->getAll()->toHashMap('id','name'); //所属科目
		
		$this->_view['courselist'] = $course_list;
		$this->_view['add'] = $this->get('add');
		$out = $this->_fetchView(dirname(__FILE__) . '/tempagesearch_view');
		return $out;
	}
	
	 public static function filterCond($sql_where, $tbl='') {
	 	if ($tbl) $tbl.='.';
	 	$context = QContext::instance(); 
	 	$name = empty($context->name)?$context->name:trim($context->name);
	 	
	 	$course_list = $context->course_list;
	 	
		if($name!=null&&$name!=""){
		    $sql_where .= " and [{$tbl}name] like '%".addslashes($name)."%'";
		}
		
		if(!empty($course_list)){
		   $sql_where .= " and [{$tbl}course_id] = {$course_list}";
		}
		return $sql_where;
	 }
	
}