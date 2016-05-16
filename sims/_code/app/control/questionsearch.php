<?php


class Control_QuestionSearch extends QUI_Control_Abstract{
	function render(){
		foreach ($_POST as $key => $val){
			$this->_view[$key] = $val;
		}
		$knowledge_ids = $this->_context->knowledge_ids;
		$itemno = $this->_context->itemno;
		$course_id = $this->_context->course_id;
		$type_id = $this->_context->type_id;
		$this->_view['knowledge_ids'] = $knowledge_ids;
		$this->_view['itemno'] = $itemno;
		$this->_view['course_id'] = $course_id;
		$this->_view['type_id'] = $type_id;
		$type_list = Questiontype::find()->order("objective desc,id asc")->getAll()->toHashMap('id','name'); //题目类型
		$course_list = Course::find("isdelete=0")->order("CONVERT(name USING gbk) desc")->getAll()->toHashMap('id','name'); //所属题库
		$knowledgelist = "";
		$knowledge_arr = $knowledge_ids ? explode(',', $knowledge_ids) : array();
		if($course_id==''){
			$list = Knowledge::find()->order('id desc')->group('parentid')->getAll();
			foreach($list as $k => $v){
				$knowledgelist .= "<option value='{$v->course->id}' disabled>{$v->course->name}</option>";
				
					$rows = Knowledge::find("parentid={$v['parentid']}")->order('id desc')->getAll()->toArray();
					foreach($rows as $k => $v){
						if (in_array($v['id'],$knowledge_arr)){
							$selected = " selected";
						}else{
							$selected = " ";
						}
						$knowledgelist .= "<option value='{$v['id']}' $selected>┕━{$v['name']}</option>";
					}
				
			}
		}else{
			$rows = Knowledge::find("parentid=?",$course_id)->order('id desc')->getAll()->toArray();
			foreach($rows as $k => $v){
				if (in_array($v['id'],$knowledge_arr)){
					$selected = " selected";
				}else{
					$selected = " ";
				}
				$knowledgelist .= "<option value='{$v['id']}' $selected>┕━{$v['name']}</option>";
			}
		}
		$this->_view['knowledgelist'] = $knowledgelist;
		$this->_view['typelist'] = $type_list;
		$this->_view['courselist'] = $course_list;
		$out = $this->_fetchView(dirname(__FILE__) . '/questionsearch_view');
		return $out;
	}
	
	 public static function filterCond($sql_where, $tbl='') {
		if ($tbl) $tbl.='.';
		$app = Q::registry('app');
		$context = QContext::instance();
		$course_id = empty($context->course_id)?$context->course_id:trim($context->course_id);
		$type_id = empty($context->type_id)?$context->type_id:trim($context->type_id);
		$itemno = empty($context->itemno)?$context->itemno:trim($context->itemno);
		if($course_id!=null&&$course_id!=""){
		    $sql_where .= " and [{$tbl}course_id] = '$course_id'";
		}
		if($type_id!=null&&$type_id!=""){
		    $sql_where .= " and [{$tbl}type_id] = '$type_id'";
		}
		if($itemno!=null&&$itemno!=""){
		    $sql_where .= " and [{$tbl}itemno] like '%".addslashes($itemno)."%'";
		}
		return $sql_where;
	 	
	 }
	
}