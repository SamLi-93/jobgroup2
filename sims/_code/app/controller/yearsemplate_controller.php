<?php
// $Id$

/**
 * Controller_Yearsemplate 控制器
 */
class Controller_Yearsemplate extends Controller_Abstract
{


	function actionIndex()
	{
        // 为 $this->_view 指定的值将会传递数据到视图中
		$sql_where = 'isdelete = 0 and knowledge_id=8';
		$sql_where = Control_TempageSearch::filterCond($sql_where);
		$page = intval($this->_context->page);
		if ($page < 1) $page = 1;
		$limit = $this->_context->limit ? $this->_context->limit : 15;
		$select = Templatepage::find($sql_where)->order('id desc');
		$select->limitPage($page, $limit);
		$list = $select->getAll();
		$dbo = QDB::getConn();
		$tpage = array();
		foreach ($list as $val){
			    $t_page_id = intval($val->id);
			    $tpagescore = Templatequestion::find("page_id = ?",$t_page_id)->getSum('score');
			    $tpage[$t_page_id] = ceil($tpagescore);
		}
		    
		// 将分页信息和查询到的数据传递到视图
		$this->_view['pager'] = $select->getPagination();
		$this->_view['list']      = $list;
		$this->_view['start'] = ($page-1)*$limit;
		$this->_view['tpage'] = $tpage;
		$this->_view['subject'] = '历年真题模板';
	}
	
	/**
	 * 生成模板试卷
	 * 
	 * 在模板试卷创建的时候同时发生2个事件：模板试卷的生成和模板试题的生成
	 * @return unknown
	 */
	function actionCreateBAK(){
		$form = new Form_Examtemplate('');
		if ($this->_context->isPOST() && $form->validate($_POST))
		{
			$data = $form->values();
			$templatepage = new Templatepage($data);			
			//生成模板试卷
			$templatepage->save();
			$page_id = $templatepage->id;
			$log_rec = Helper_Util::toArray($templatepage);
			Log::addlog(0, 'Templatepage', $templatepage->id(), $log_rec, '增加模板试卷：'.$templatepage->name, NULL, 'templatepage');
		
			//根据组卷策略和所属科目生成模板试题
			
			Templatequestion::generationQuestion($data['course_id'],$data['strategy_id'],$page_id);
			return $this->_redirect(url('/create',array('addtea'=>'success')));
		}
		$this->_view['form'] = $form;
		$this->_view['subject'] = "生成模板试卷";
		$form->_subject = "添加模板试卷";
	}
	
	//添加组卷策略+生成模板试卷
	function actionCreate(){
		if($this->_context->isPOST()){		
			/*
			 * 保存模板试卷可以分为3步：
			 * 1、保存一条记录到模板试卷表
			 * 2、保存模板试卷的组成方式（知识点、组卷策略）
			 * 3、生成模板试题
			 */
			
			//1、生成模板试卷
			$name = $this->_context->name;
			$course_id = intval($this->_context->course_id);
			$remark = $this->_context->remark;
			$data = array('name'=>$name,'course_id'=>$course_id,'remark'=>$remark);
			$templatepage = new Templatepage($data);						
			$templatepage->save();
			$log_rec = Helper_Util::toArray($templatepage);
			Log::addlog(0, 'Templatepage', $templatepage->id(), $log_rec, '增加模板试卷信息：'.$templatepage->name, NULL, 'templatepage');
			$page_id = $templatepage->id;
			
			//2、生成组卷策略						
			$Formarr = $this->_context->post();
			$arrknowledge = array_diff($Formarr['knowid'],array('{id}'));
			$totalscore = $totalnum = $score = $pagetotalnum = 0;
			$pageprogram = array();
			foreach ($arrknowledge as $fieldset){
				$fangan = array();
				$totalnum = array_sum($Formarr["num{$fieldset}"]);
				if($totalnum > 0 ){
					$pagetotalnum += $totalnum;
					$totalscore = 0;
					$cou = count($Formarr["num{$fieldset}"]);
					for ($i = 0; $i<$cou; $i++){
						$fangan[] = array('examtype'=>$Formarr["examtype{$fieldset}"][$i],'num'=>$Formarr["num{$fieldset}"][$i],'score'=>$Formarr["score{$fieldset}"][$i]);
						$score = $Formarr["num{$fieldset}"][$i] * $Formarr["score{$fieldset}"][$i];
						$totalscore += $score;

						$pageprogram[$Formarr["examtype{$fieldset}"][$i]][] = array('num'=>intval($Formarr["num{$fieldset}"][$i]),'score'=>$Formarr["score{$fieldset}"][$i]);
					}
					$Formarr['postdate'] = time();
					$Formarr['program'] = json_encode($fangan);
					$Formarr['totalnum'] = $totalnum;
					$Formarr['totalscore'] = $totalscore;
					$Formarr['page_id'] = $page_id;
					$Formarr['knowledge_id'] = $fieldset;
					$strategy = new Strategy($Formarr);
					$strategy->save();
					
					//3、生成模板试题
					if(!empty($fangan)) Templatequestion::generationTemplateQuestion($course_id,$fieldset,$fangan,$page_id);															
				}
			}
			//得到总的组卷方案
			$jsonpageprogram = json_encode($pageprogram);
			//更新模板试卷
			$templatepage = Templatepage::find("id = ?",$page_id)->getOne();
			$templatepage->program = $jsonpageprogram;
			$templatepage->totalnum = $pagetotalnum;
			$templatepage->save();
			
			$message = "模板试卷生成成功";			
			$url = url('/index');			
			$caption = '模板试卷生成';
			return $this->_redirectMessage($caption,$message,$url);	
		}
		$type_list = Questiontype::find()->order("objective desc,id asc")->getAll()->toHashMap('id','name'); //题目类型
		$course_list = Course::find()->order("CONVERT(name USING gbk) desc")->getAll()->toHashMap('id','name'); //所属科目
		$knowledge = Knowledge::find("parentid !=?",0)->order("CONVERT(name USING gbk) desc")->getAll()->toHashMap('id','name'); //知识模块
		$certificate_list = Certificate::find()->order("id desc")->getAll()->toHashMap('id','name'); //证书
		//$project_list = Project::find('outdated = 0')->order("id desc")->getAll()->toHashMap('id','name'); //考试计划
		$this->_view['certificate'] = $certificate_list;
		$this->_view['knowledge'] = $knowledge;
		$this->_view['courselist'] = $course_list;
		$this->_view['typelist'] = $type_list;
		$this->_view['subject'] = '添加模板试卷';
	}
	
	//手动添加组卷策略+生成模板试卷
	function actionCreate2(){
		if($this->_context->isPOST()){
			/*
			 * 保存模板试卷可以分为3步：
			 * 1、保存一条记录到模板试卷表
			 * 2、保存模板试卷的组成方式（知识点、组卷策略）
			 * 3、生成模板试题
			 */
			
			//1、生成模板试卷
			$name = $this->_context->name;
			$course_id = intval($this->_context->course_id);
			$remark = $this->_context->remark;
			$data = array('name'=>$name,'course_id'=>$course_id,'knowledge_id'=>8,'remark'=>$remark);
			$templatepage = new Templatepage($data);						
			$templatepage->save();
			//$log_rec = Helper_Util::toArray($templatepage);
			//Log::addlog(0, 'Templatepage', $templatepage->id(), $log_rec, '增加模板试卷信息：'.$templatepage->name, NULL, 'templatepage');
			$page_id = $templatepage->id;
			
			//2、生成组卷策略						
			$Formarr = $this->_context->post();
			$arrknowledge = array_diff($Formarr['knowid'],array('{id}'));
			$totalscore = $totalnum = $score = $pagetotalnum = 0;
			$pageprogram = array();
			foreach ($arrknowledge as $fieldset){
				$fangan = array();
				$totalnum = array_sum($Formarr["num{$fieldset}"]);
				if($totalnum > 0 ){
					$pagetotalnum += $totalnum;
					$totalscore = 0;
					$cou = count($Formarr["num{$fieldset}"]);
					for ($i = 0; $i<$cou; $i++){
						$fangan[] = array('examtype'=>$Formarr["examtype{$fieldset}"][$i],'num'=>$Formarr["num{$fieldset}"][$i],'score'=>$Formarr["score{$fieldset}"][$i]);
						$score = $Formarr["num{$fieldset}"][$i] * $Formarr["score{$fieldset}"][$i];
						$totalscore += $score;

						$pageprogram[$Formarr["examtype{$fieldset}"][$i]][] = array('num'=>intval($Formarr["num{$fieldset}"][$i]),'score'=>$Formarr["score{$fieldset}"][$i]);
					}
					$Formarr['postdate'] = time();
					$Formarr['program'] = json_encode($fangan);
					$Formarr['totalnum'] = $totalnum;
					$Formarr['totalscore'] = $totalscore;
					$Formarr['page_id'] = $page_id;
					$Formarr['knowledge_id'] = $fieldset;
					$strategy = new Strategy($Formarr);
					$strategy->save();
					
					//3、生成模板试题
					//if(!empty($fangan)) Templatequestion::generationTemplateQuestion($course_id,$fieldset,$fangan,$page_id);
				}
			}
			//得到总的组卷方案
			$jsonpageprogram = json_encode($pageprogram);
			//更新模板试卷
			$templatepage = Templatepage::find("id = ?",$page_id)->getOne();
			$templatepage->program = $jsonpageprogram;
			$templatepage->totalnum = $pagetotalnum;
			$templatepage->save();
			
			return $this->_redirect(url('/savemodel',array('id'=>$page_id,'flag'=>1)));	
		}
		$type_list = Questiontype::find()->order("objective desc,id asc")->getAll()->toHashMap('id','name'); //题目类型
		$course_list = Course::find()->order("CONVERT(name USING gbk) desc")->getAll()->toHashMap('id','name'); //所属科目
		$knowledge = Knowledge::find("parentid !=?",0)->order("CONVERT(name USING gbk) desc")->getAll()->toHashMap('id','name'); //知识模块
		//$project_list = Project::find('outdated = 0')->order("id desc")->getAll()->toHashMap('id','name'); //考试计划
		$this->_view['knowledge'] = $knowledge;
		$this->_view['courselist'] = $course_list;
		$this->_view['typelist'] = $type_list;
		$this->_view['subject'] = '历年真题模板';
	}
	/**
	 * 手动添加模版试卷 选题生成试卷模版
	 * 1、根据组卷策略生成知识点数组
	 * 2、根据知识点找到对应试题id并查处对应试题，组成数组
	 * 3、将知识点对应的试题数组与基础信息，用已有方法生成模版试题
	 */
	function actionSaveModel(){
		$id = intval($this->_context->id);
		$flag	=	intval($this->_context->flag);
		$edit	=	intval($this->_context->edit);
	 	$templatepage = Templatepage::find()->getById($id);
		$strategy = Strategy::find("page_id =?",$id)->getAll();  //组卷策略
		$arrknowledge_id = array();
		$program	= array();
		//1、根据组卷策略生成知识点数组
		foreach ($strategy as $stra){
			$arrknowledge_id[$stra->knowledge_id] = $stra->knowledge->name;
			$program[$stra->knowledge_id]	= json_decode($stra['program'], true);
		}
		if ($this->_context->isPOST())
		{
			foreach($program as $knowledge_id=>$pro)
			{
				foreach($pro as $examtype)
				{
					//2、根据知识点找到对应试题id并查处对应试题，组成数组
					$question_ids	= $_REQUEST[$knowledge_id."_".$examtype['examtype']];
					$sql_question	= strlen($question_ids)?"id in ($question_ids)":"1=2";
					$questions	= Questions::find($sql_question)->asArray()->getAll();
					$qnum	= count($questions);
					if($qnum == 0) continue;
					$questionsrand['qus']	= $questions;
					//3、将知识点对应的试题数组与基础信息，用已有方法生成模版试题
					//$objective = Questiontype::getObjective($examtype['examtype']);
					//$questionsrand['examtype']	= $examtype['examtype'];
					$questionsrand['score']		= $examtype['score'];
					$questionsrand['page_id']	= $id;
					//$questionsrand['objective'] = $objective;
					$questionsrand['type_id']	= $examtype['examtype'];
					$questionsrand['knowledge_id']	= $knowledge_id;
					Templatequestion::InsertQuestion($questionsrand);
				}
			}
			$url	= url('/index');
			$caption	= '模板试卷生成';
			$message	= "模板试卷发布成功";
			return $this->_redirect(url(''));	
		}
		$type_list	= Questiontype::find()->order("objective desc,id asc")->getAll()->toHashMap('id','name'); //题目类型
		$course_list	= Course::find()->order("CONVERT(name USING gbk) desc")->getAll()->toHashMap('id','name'); //所属科目
		$knowledge	= Knowledge::find("parentid !=?",0)->order("CONVERT(name USING gbk) desc")->getAll()->toHashMap('id','name'); //知识模块
		$this->_view["flag"]	=	$flag;
		$this->_view['arrknowledge_id']	= $arrknowledge_id;
		$this->_view['courselist']	= $course_list;
		$this->_view['typelist']	= $type_list;
		$this->_view['templatepage']	= $templatepage;
		$this->_view['strategy']	= $strategy;
		$this->_view['subject'] = '历年真题模板';
	}
	/**
	 * 手动组卷修改
	 * 1、根据组卷策略生成知识点数组
	 * 2、根据知识点数组获取知识点对应的试题数目数组
	 * 3、根据试题id判断模版试卷是否已生成，未生成则保存模版试卷
	 * 4、根据知识点找到对应试题id并查处对应试题，组成数组
	 * 5、将知识点对应的试题数组与基础信息，用已有方法生成模版试题
	 **/
	function actionEdit2() {
		$id = intval($this->_context->id);
		$from = $this->_context->from;
		$tempname	=	$from == "employ"?"模板":"模版";
	 	$templatepage = Templatepage::find()->getById($id);
		$strategy = Strategy::find("page_id =?",$id)->getAll();  //组卷策略
		$arrknowledge_id = array();
		$program	= array();
		$qnum	=	array();
		//1、根据组卷策略生成知识点数组
		foreach ($strategy as $stra){
			$arrknowledge_id[$stra->knowledge_id] = $stra->knowledge->name;
			$program[$stra->knowledge_id]	= json_decode($stra['program'], true);
		}
		//2、根据知识点数组获取知识点对应的试题数目数组
		foreach($program as $knowledge_id=>$pro)
		{
			foreach($pro as $key=>$item)
			{
				//$objective = Questiontype::getObjective($item['examtype']);
				$questions = Templatequestion::find("page_id=? and type_id=? and knowledge_id=?",$id,$item['examtype'],$knowledge_id)
							->getAll()->toHashMap("id", "id");
								
				$temp = count($questions);
				if($temp<1)
					continue;
				$qnum[$knowledge_id][$item['examtype']]	= $temp;
			}
		}
		if ($this->_context->isPOST())
		{
			foreach($program as $knowledge_id=>$pro)
			{
				foreach($pro as $examtype)
				{
					//3、根据试题id判断模版试卷是否已生成，未生成则保存模版试卷
					$question_ids	= $_REQUEST[$knowledge_id."_".$examtype['examtype']];
					if(empty($question_ids))
						continue;
					//4、根据知识点找到对应试题id并查处对应试题，组成数组
					$sql_question	= strlen($question_ids)?"id in ($question_ids)":"1=2";
					$questions	= Questions::find($sql_question)->asArray()->getAll();
					$qnum	= count($questions);
					if($qnum == 0) continue;
					$questionsrand['qus']	= $questions;
					//5、将知识点对应的试题数组与基础信息，用已有方法生成模版试题
					//$objective = Questiontype::getObjective($examtype['examtype']);
					$questionsrand['examtype']	= $examtype['examtype'];
					$questionsrand['score']		= $examtype['score'];
					$questionsrand['page_id']	= $id;
					//$questionsrand['objective'] = $objective;
					$questionsrand['type_id']	= $examtype['examtype'];
					$questionsrand['knowledge_id']	= $knowledge_id;
					Templatequestion::InsertQuestion($questionsrand);
				}
			}
			
			$name = $this->_context->name;
			$remark = $this->_context->remark;
			$args = Templatepage::find('name=?&&id!=?',$name,$id)->getCount();
			if ($args>0) {
				$message = "[{$name}]-{$tempname}试卷名称已经存在";			
				$url = url('/edit2',array('id'=>$id, 'from'=>$from));				
			}else{
				$templatepage->name = $name;
				$templatepage->remark = $remark;
				$templatepage->save();
				$message = $tempname."试卷修改成功";			
				$url = $from == "employ"?url('/index'):url('/index');	
			}
			$caption = $tempname.'试卷生成';
			return $this->_redirect(url(''));
		}
		$type_list = Questiontype::find()->order("objective desc,id asc")->getAll()->toHashMap('id','name'); //题目类型
		$course_list = Course::find()->order("CONVERT(name USING gbk) desc")->getAll()->toHashMap('id','name'); //所属科目
		$knowledge = Knowledge::find("parentid !=?",0)->order("CONVERT(name USING gbk) desc")->getAll()->toHashMap('id','name'); //知识模块
		$this->_view['from']	=	$from;
		$this->_view['qnum']	=	$qnum;
		$this->_view['strategy']	=	$strategy;
		$this->_view['arrknowledge_id']	=	$arrknowledge_id;
		$this->_view['courselist']		=	$course_list;
		$this->_view['typelist']		=	$type_list;
		$this->_view['templatepage']	=	$templatepage;
		$this->_view['subject'] = "历年真题模板";
	}
	
	/**
	 * 复制模板试卷、组卷策略、模板试题
	 *
	 */
	function actionCopy(){
		$id = intval($this->_context->id);
		$templatepage = Templatepage::find("id = ?",$id)->asArray()->getOne();
		//生成模板试卷
		$templatepage['name'] = $templatepage['name'].'-'.$id;
		$newtemplatepage = new Templatepage($templatepage);
		$newtemplatepage->save();
		$newid = $newtemplatepage->id;
		
		//生成组卷策略
		$strategy = Strategy::find("page_id = ?",$id)->asArray()->getAll();
		if(!empty($strategy)){
			foreach ($strategy as $stra){
				$stra['page_id'] = $newid;
				$newstrategy = new Strategy($stra);
				$newstrategy->save();
			}
		}
		
		//生成模板试题
		$templatequestion = Templatequestion::find("page_id = ?",$id)->asArray()->getAll();
		if(!empty($templatequestion)){
			foreach ($templatequestion as $question){
				$question['page_id'] = $newid;
				$newtemplatequestion = new Templatequestion($question);
				$newtemplatequestion->save();
			}
		}
		$message = "模板试卷复制成功";			
		$url = url('/index');	
		$caption = '模板试卷复制';
		return $this->_redirectMessage($caption,$message,$url);	
	}
	
	//查看模板试卷
	function actionEditBAK(){
		$id = intval($this->_context->id);	
	 	$templatepage = Templatepage::find()->getById($id);

		$form = new Form_Examtemplate('');
		$_POST['strategy_id'] = $templatepage['strategy_id'];
		$_POST['course_id'] = $templatepage['course_id'];
		if ($this->_context->isPOST()&&$form->validate($_POST))
		{
			$data = $form->values();
			$templatepage->changeProps($data);
			$args = Templatepage::find('name=?&&id!=?',$templatepage->name,$id)->getCount();
			if ($args>0) {
				$form['name']->invalidate("{$course->name}已经存在了");
			}else{
				$templatepage->save();
				return $this->_redirect(url(''));
			}
		}elseif (!$this->_context->isPOST()){
			$form->import($templatepage);
		}
		$this->_view['form'] = $form;
		$this->_view['subject'] = "模板试卷";
		$form->_subject = "查看模板试卷";
		$this->_viewname = 'view';
	}
	
	//查看模板试卷
	function actionEdit(){
		$id = intval($this->_context->id);	
		$from = $this->_context->from;
		$temp	=	$from == "employ"?"模板":"模版";
	 	$templatepage = Templatepage::find()->getById($id);
	
		if ($this->_context->isPOST())
		{
			$name = $this->_context->name;
			$remark = $this->_context->remark;
			$args = Templatepage::find('name=?&&id!=?',$name,$id)->getCount();
			if ($args>0) {
				$message = "[{$name}]-{$temp}试卷名称已经存在";			
				$url = url('/edit',array('id'=>$id));				
			}else{
				$templatepage->name = $name;
				$templatepage->remark = $remark;
				$templatepage->save();
				$message = $temp."试卷修改成功";
				$url = $from == "employ"?url('employtemplate/index'):url('/index');	
			}
			$caption = $temp."试卷生成";
			return $this->_redirectMessage($caption,$message,$url);	
		}
		$type_list = Questiontype::find()->order("objective desc,id asc")->getAll()->toHashMap('id','name'); //题目类型
		$course_list = Course::find()->order("CONVERT(name USING gbk) desc")->getAll()->toHashMap('id','name'); //所属科目
		$knowledge = Knowledge::find("parentid !=?",0)->order("CONVERT(name USING gbk) desc")->getAll()->toHashMap('id','name'); //知识模块
		$strategy = Strategy::find("page_id =?",$id)->getAll();  //组卷策略
		$arrknowledge_id = array();
		foreach ($strategy as $stra){
			$arrknowledge_id[$stra->knowledge_id] = $stra->knowledge->name;
		}
		
		$this->_view['from'] = $from;
		$this->_view['arrknowledge_id'] = $arrknowledge_id;
		$this->_view['courselist'] = $course_list;
		$this->_view['typelist'] = $type_list;
		$this->_view['templatepage'] = $templatepage;
		$this->_view['strategy'] = $strategy;
		$this->_view['subject'] = "编辑{$temp}试卷";
		$this->_viewname = 'edit';
	}
	
	//删除模板试题
	function actionDelete(){
		$id = intval($this->_context->id);
		$from = $this->_context->from;
		$temp	= "模板";
		$templatepage = Templatepage::find("id = ?",$id)->getOne();
		//$log_rec = Helper_Util::toArray($templatepage);
		$templatepage->isdelete = 1;
		$templatepage->save();
		Strategy::meta()->updateWhere(array('isdelete'=>1),"page_id = '$id'");
		//Log::addlog(2, 'Templatepage', $templatepage->id(), $log_rec, "删除{$temp}试卷：".$templatepage->name, NULL, 'templatepage');
		//return $this->_redirect(url('templatepage'));
		$caption = "删除{$temp}试卷";
		$url = url('/index');
		$message = "操作成功，{$temp}试卷已经被删除";
		return $this->_redirect(url(''));	
	}
	
	/**
	 * 显示模板试题列表
	 *
	 */
	function actionQuestions(){
		$page_id = intval($this->_context->id);	
		$from = $this->_context->from;
		$sql_where = "page_id = {$page_id}";
		//相同题型具有相同的分值		
		//$sql = "select a.type_id,b.name,a.score,count(a.id) as n from exam_template_question a left join exam_question_type b on a.type_id=b.id   WHERE ((a.page_id = {$page_id})) GROUP BY a.type_id";
		//相同题型具有不同的分值
		$sql = "select a.type_id,b.name,sum(a.score) as score,count(a.id) as n from exam_template_question a left join exam_question_type b on a.type_id=b.id   WHERE ((a.page_id = {$page_id})) GROUP BY a.type_id";
		$dbo = QDB::getConn();
		$questype = $dbo->getAll($sql);
		$questiontitle = $this->_context->questiontitle;
		//$questiontitle = quotemeta($questiontitle);
		//echo $questiontitle;
		$question = array();
		if(!empty($questiontitle)){
			$sql_where .= " and question like '%".addslashes($questiontitle)."%'";
			//echo $sql_where;
		}
		foreach ($questype as $typeline){
			$sqlwhere = $sql_where." and type_id = {$typeline['type_id']}";
			$select = Templatequestion::find($sqlwhere)->order('id asc')->asArray()->getAll();
			$question[$typeline['type_id']] = $select;
		}
		$templatepage = Templatepage::find("id = ?",$page_id)->asArray()->getOne();
		$bignum =  Q::ini('appini/bignum');
		$questiondemo =  Q::ini('appini/questiondemo');
		
		$type_list = Questiontype::find()->order("objective desc,id asc")->getAll()->toHashMap('id','name'); //题目类型
		$course_list = Course::find()->order("CONVERT(name USING gbk) desc")->getAll()->toHashMap('id','name'); //所属题库
		$this->_view['typelist'] = $type_list;
		$this->_view['courselist'] = $course_list;
		$this->_view['page_id'] = $page_id;
		$this->_view['bignum'] = $bignum;
		$this->_view['questiondemo'] = $questiondemo;
		$this->_view['templatepage'] = $templatepage;
		$this->_view['questype'] = $questype;
		$this->_view['question'] = $question;
		$this->_view['questiontitle'] = $questiontitle;
		if(!empty($from)&&$from=="employ")
		{
			$this->_view['subject'] = '历年真题模板';
			$this->_view['backurl'] = url('/index');
		}else
		{
			$this->_view['subject'] = '历年真题模板';
			$this->_view['backurl'] = url('/index');
		}
	}
	
	/**
	 * 根据ID删除模板试题
	 */
	function actionDelquestion(){
		$id = intval($this->_context->id);
		$tquestion = Templatequestion::find()->getById($id);
		$tquestion->destroy();
		exit();
	}
	/**
	 * 手动组卷中按照课程、知识点、试题类型选择对应题目
	 **/
	function actionGetTypeQuestion() {
		foreach($_REQUEST as $key => $val){
			$$key	=	addslashes(trim($val));
		}
		$questions = Questions::getTemplateQuestions($course_id,$knowledge_id,$examtype,$page_id,'8');  //得到符合条件的题目
		$this->_view["questions"]	=	$questions;
		foreach($_REQUEST as $key => $val){
			$this->_view[$key]	=	$val;
		}
	}
	/**
	 * 搜索显示需要添加的模板试题
	 *
	 */
	function actionGetquestions(){
		$course_id = intval($this->_context->course_id);
		$type_id = intval($this->_context->type_id);
		$page_id = intval($this->_context->page_id);
		$question = $this->_context->question;
		$templatequestion = Templatequestion::find("page_id = ?",$page_id)->getAll()->toHashMap('question_id','question_id');
		$quesidstr = implode(',',$templatequestion);
		$sql = "isdelete = 0 and FIND_IN_SET('8',use_type) ";
		$sql .= !empty($course_id)?" and course_id = '{$course_id}'":"";
		$sql .= !empty($type_id)?" and type_id = '{$type_id}'":"";
		$sql .= !empty($quesidstr)?" and id not in({$quesidstr})":"";
		$sql .= strlen($question)>0?" and question like '%".addslashes($question)."%'":"";
		$ques = Questions::find($sql)->getAll();
		$question_list = array();
		$question_list["id"] = array();
		$question_list["course"] = array();
		$question_list["type"] = array();
		$question_list["question"] = array();
		$question_list["result"] = array();
		$question_list["options"] = array();
		foreach($ques as $v=>$qu){
			$result = $qu['result'];
			$options = $qu['options'];
			if($qu['type_id'] != 6){
				$arrresult = json_decode($qu['result'],true);
				$result = $arrresult[0];
	
				$arroptions = json_decode($qu['options'],true);
				if(is_array($arroptions))
					$options = implode('<br>',$arroptions);
				else
					$options = $arroptions[0];
			}
			$question_list["id"][$v] = $qu["id"];
			$question_list["course"][$v] = $qu['course']["name"];
			$question_list["type"][$v] = $qu["questiontype"]['name'];
			$question_list["question"][$v] = $qu["question"];
			$question_list['result'][$v] = $result;
			$question_list['options'][$v] = $options;
		}
		$return_info = json_encode(array("id_list"=>$question_list["id"],"course_list"=>$question_list["course"],"type_list"=>$question_list["type"],"question_list"=>$question_list["question"],"result_list"=>$question_list["result"],"options_list"=>$question_list["options"]));
		return $return_info;
		exit;
	}
	
	/**
	 * 新增试题到模板试卷
	 *
	 */
	function actionAddquestions(){
		$cour_id = trim($_POST["cour_id"]);
		$arrquid = explode(',',$cour_id);
		
		//得到组卷策略包含的方案
		$page_id = intval($this->_context->page_id);
		$templatepage = Templatepage::find('id = ?',$page_id)->getOne();
		$program = $templatepage['program'];
		$fangan = json_decode($program,true);
		if(!empty($fangan) && is_array($fangan)){
			foreach ($fangan as $k=>$val){
				$newfangan[$k]['num'] = $val['num'];
				$newfangan[$k]['score'] = $val['score'];
			}
		}
		//剔除已经在模板试题中且未被删除的试题
		$templatequestions = Templatequestion::find('page_id = ?',$page_id)->getAll()->toHashMap('question_id','question_id');
		$newquid = array_diff($arrquid,$templatequestions);
		//根据试题ID分别来得到试题信息
		if(!empty($newquid) && is_array($newquid)){
			foreach ($newquid as $k => $v){				
				$questions = Questions::find('id = ?',$v)->getOne();
				//$objective = Questiontype::getObjective($questions['type_id']);
				$score = $newfangan[$questions['type_id']]['score'];
				//插入到模板试题表中
				$data = array('knowledge_id'=>$page_id,'page_id'=>$page_id,'question'=>$questions['question'],'remark'=>$questions['remark'],'options'=>$questions['options'],'result'=>$questions['result'],'score'=>$score,'type_id'=>$questions['type_id'],'question_id'=>$v);
				$templateque = new Templatequestion($data);
				$templateque->save();
				$questionid = $templateque->id;
								
				//得到ajax字符串
				$optionstr = Helper_String::getOptionstr($questions['options'],$questions['type_id']);
				$options = Helper_String::getOptions($questions['options'],$questions['type_id'],$questions['id']);
				$question_list["id"][$k] = $questionid;
				$question_list["question"][$k] = $questions["question"];			
				$question_list["optionstr"][$k] = $optionstr;
				$question_list["options"][$k] = $options;
				$question_list["type_id"][$k] = $questions['type_id'];	
			}
			$return_info = json_encode(array("id_list"=>$question_list["id"],"optionstr"=>$question_list["optionstr"],"options"=>$question_list["options"],"question_list"=>$question_list["question"],"type_id"=>$question_list["type_id"]));
		}else {
			$return_info = json_encode(array());
		}
		
		return $return_info;		
		exit;
	}
	
	/**
	 * 替换模板试题
	 *
	 */
	function actionUpdatequestion(){
		$new_id = intval($_POST["cour_id"]);
		$questionid = intval($_POST['questionid']);
		$page_id = intval($this->_context->page_id);
		$hasnum = Templatequestion::find("page_id = ? and question_id = ?",$page_id,$new_id)->getCount();
		if($hasnum == 0){
			//更新数据
			$oldquestion = Templatequestion::find("id = ?",$questionid)->getOne();			
			$newquestion = Questions::find("id = ?",$new_id)->getOne();			
			$oldquestion->question = $newquestion['question'];
			$oldquestion->remark = $newquestion['remark'];
			$oldquestion->options = $newquestion['options'];
			$oldquestion->result = $newquestion['result'];
			$oldquestion->question_id = $new_id;
			$oldquestion->save();
			
			//ajax 更新显示
			/*$optionstr = Helper_String::getOptionstr($newquestion['options'],$newquestion['type_id']);
			$options = Helper_String::getOptions($newquestion['options'],$newquestion['result'],$newquestion['type_id'],$newquestion['id']);
			$question_list["id"] = $questionid;
			$question_list["question"] = $newquestion["question"];			
			$question_list["optionstr"] = $optionstr;
			$question_list["options"] = $options;
			$question_list["type_id"] = $newquestion['type_id'];*/
			$return_info = json_encode(array());
		}else {
			$return_info = json_encode(array());
		}
		return $return_info;		
		exit;
	}
	
	/**
	 * 得到知识点下题型的题数
	 *
	 */
	function actionGettypenum(){
		$use_type = intval($this->_context->use_type);
		$knowledge_id = intval($this->_context->knowledge_id);
		$type_id = intval($this->_context->type_id);
		$course_id = intval($this->_context->course_id);
		$ts = Questions::find("isdelete=0 and type_id = '$type_id' and course_id = '$course_id' and FIND_IN_SET('$knowledge_id',knowledge_id) and FIND_IN_SET('$use_type',use_type)")->getCount();
		return $ts;
		exit;
	}

	/**
	 * ajax得到证书下的课程
	 * @return [type] [description]
	 */
	function actionGetcourse(){
		$certificate_id = intval($this->_context->certificate_id);
		//$project = Project::find("certificate_id = ? and outdated = ?",$certificate_id,0)->getAll()->toHashMap('id','name');
		//$course = Course::find()->order("CONVERT(name USING gbk) desc")->getAll()->toHashMap('id','name'); //所属科目
		$certificatecourse = CertificateCourse::find("certificate_id ={$certificate_id}")->getAll();
		$course = array();
		foreach ($certificatecourse as $key => $value) {
			$course[$value['course_id']] = $value['course']['name'];
		}
		$return_info = json_encode($course);
		exit($return_info);
	}

	/**
	 * ajax得到科目下的知识点
	 * @return [type] [description]
	 */
	function actionGetknowledge(){
		$course_id = intval($this->_context->course_id);
		//$knowledge = Project::find("certificate_id = ? and outdated = ?",$certificate_id,0)->getAll()->toHashMap('id','name');
		$knowledge = Knowledge::find("parentid =?",$course_id)->order("CONVERT(name USING gbk) desc")->getAll()->toHashMap('id','name'); //知识模块
		$return_info = json_encode($knowledge);
		exit($return_info);
	}
}


