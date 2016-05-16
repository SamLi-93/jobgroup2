<?php 

/**
 * Controller_Index 控制器
 */
class Controller_Index extends Controller_Abstract
{
	function actionIndex() {
		$type = $this->_context->type;
		$this->_view['type'] = $type;
			

	}
	//课程资源
	function actionClist(){
		$pid=$this->_context->id;
		$type_id=$this->_context->type_id;
		$page = (int)$this->_context->page;
		if ($page==0) $page++;
		$limit = $this->_context->limit?$this->_context->limit:'15';
		$p_type = Newstype::find('id=?',$pid)->getOne();
		$newstype = Newstype::find('pid=?',$pid)->getAll();

		if(!$type_id){
			foreach ($newstype as $n => $m) {
				$type_id =$m['id'];
				break;
			}
		}
		$list = News::find('type_id=?',$type_id)->limitPage($page, $limit);
		$this->_view['pager'] = $list->getPagination();
		$this->_view['list'] = $list->getAll();
		$this->_view['start'] = ($page-1)*$limit;
		foreach ($newstype as $n => $m) {
			if($m['id']==$type_id){
				$type_name = $m['type_name'];
			}
		}
		$this->_view['newstype'] = $newstype;
		$this->_view['type_id'] = $type_id;
		$this->_view['id'] = $pid;
		$this->_view['type_name'] = $type_name;
		$this->_view['p_name'] = $p_type['type_name'];
		$this->_view['class'] ='clist';
		$this->_view['search'] ='type_id='.$type_id.'&id='.$pid;
	}

	//课程资源
	function actionContent(){
		$pid = $this->_context->id;
		$type_id = $this->_context->type_id;
		$new_id = $this->_context->new_id;
		$p_type = Newstype::find('id=?',$pid)->getOne();
		$newstype = Newstype::find('pid=?',$pid)->getAll();

		if($new_id){
			$new = News::find('id=?',$new_id)->getOne();
			$this->_view['new'] = $new;
		}else{
			return $this->_redirect(url(''));exit;
		}
		foreach ($newstype as $n => $m) {
			if($m['id']==$type_id){
				$type_name = $m['type_name'];
			}
		}
		$this->_view['newstype'] = $newstype;
		$this->_view['type_id'] = $type_id;
		$this->_view['id'] = $pid;
		$this->_view['type_name'] = $type_name;
		$this->_view['p_name'] = $p_type['type_name'];
	}
	//关于我们
	function actionAbout(){
		$type=$this->_context->type;
		$page = (int)$this->_context->page;
		if ($page==0) $page++;
		$limit = 8;
		if($type){
			$list = News::find('type_id=?',$type)->limitPage($page, $limit);
			$this->_view['class'] ='about';
			$this->_view['pager'] = $list->getPagination();
			$this->_view['list'] = $list->getAll();
			$this->_view['start'] = ($page-1)*$limit;
		}else{
			$newstype = Newstype::find()->getOne();

			$list = News::find('type_id=35')->limitPage($page, $limit);
			$this->_view['pager'] = $list->getPagination();
			$this->_view['list'] = $list->getAll();
			$this->_view['start'] = ($page-1)*$limit;
		}
		$this->_view['class'] ='about';

		$type = Newstype::find('pid = "45"')->getAll();
        $Data = $type->toArray();
        $this->_view['Data'] = $Data;
		$type =$this->_context->type;
        $course = News::find('')->getAll();
		$arr1 = array('0'=>array('id'=>'0','title'=>'1111111','name'=>'新闻列表first'),'1'=>array('id'=>'1','title'=>'222222','name'=>'新闻列表second'));
		$arr2 = array('title'=>'44444444','title'=>'5555555555','title'=>'666666');
		$this->_view['arr1'] =$arr1;
		$this->_view['arr2'] =$arr2;
		$this->_view['course'] =$course;
        
		$page = (int) $this->_context->page;
        if ($page == 0)
            $page++;
        //echo $page;exit;
        $limit = $this->_context->limit ? $this->_context->limit : 4;
        $q = News::find('type_id = "'.$type.'"')->order('id desc')->limitPage($page, $limit);
        $list = $q->getAll()->toArray();
        $this->_view['pager'] = $q->getPagination();
        $this->_view['list'] = $list;
        $this->_view['start'] = ($page - 1) * $limit;

	}
	//荣誉成果
	function actionResult(){
		$this->_view['class'] ='result';

	}
	//教师
	function actionTeacher(){
        $page = (int)$this->_context->page;
		if ($page==0) $page++;
		$limit = 8;
		$list = Teacher::find('is_open=1')->order('id desc')->limitPage($page, $limit);
		$this->_view['pager'] = $list->getPagination();
		$this->_view['list'] = $list->getAll();
		$this->_view['start'] = ($page-1)*$limit;
		$this->_view['school_list'] = School::find()->order('id desc')->getAll()->toHashMap('id','title');
		$this->_view['class'] ='teacher';
	}
	function actionCourselist(){
		$this->_view['class'] ='course';

	}
	function actionVideo(){
		$course_id =$this->_context->course_id;
		$course=Videoclass::find()->getById($course_id);
		$this->_view['video']=$course->first_video;
		$this->_view['course']=$course;
		$this->_view['class'] ='course';

		
	}
	function actionNewlist(){


        $type = Newstype::find('pid = "17"')->getAll();
        $Data = $type->toArray();
        $this->_view['Data'] = $Data;

		$type =$this->_context->type;
        $course = News::find('')->getAll();

		$this->_view['course'] =$course;
        
		$page = (int) $this->_context->page;
        if ($page == 0)
            $page++;
        //echo $page;exit;
        $limit = $this->_context->limit ? $this->_context->limit : 4;
        $q = News::find('type_id = "'.$type.'"')->order('id desc')->limitPage($page, $limit);
        $list = $q->getAll()->toArray();
        $this->_view['pager'] = $q->getPagination();
        $this->_view['list'] = $list;
        $this->_view['start'] = ($page - 1) * $limit;

	}
	
    /*function actionContent(){
        $id =$this->_context->id;      
        $news = News::find('id = "'.$id.'"')->getAll();
        $myData = $news->toArray();
        $this->_view['myData'] = $myData;
   
        $type_id =$this->_context->type_id;
        $type = Newstype::find('id = "'.$type_id.'"')->getAll();
        $Data = $type->toArray();
        $this->_view['Data'] = $Data;
    }*/

    function actionSocialwork(){
    	$type = Newstype::find('pid = "52"')->getAll();
        $Data = $type->toArray();
        $this->_view['Data'] = $Data;

		$this->_view['class'] ='socialwork';
		$type =$this->_context->type;
        $course = News::find('')->getAll();
		$arr1 = array('0'=>array('id'=>'0','title'=>'1111111','name'=>'新闻列表first'),'1'=>array('id'=>'1','title'=>'222222','name'=>'新闻列表second'));
		$arr2 = array('title'=>'44444444','title'=>'5555555555','title'=>'666666');
		$this->_view['arr1'] =$arr1;
		$this->_view['arr2'] =$arr2;
		$this->_view['course'] =$course;
		$page = (int) $this->_context->page;
        if ($page == 0)
            $page++;
        //echo $page;exit;
        $limit = $this->_context->limit ? $this->_context->limit : 4;
        $q = News::find('type_id = "'.$type.'"')->order('id desc')->limitPage($page, $limit);
        $list = $q->getAll()->toArray();
        $this->_view['pager'] = $q->getPagination();
        $this->_view['list'] = $list;
        $this->_view['start'] = ($page - 1) * $limit;
	}

	function actionSkills(){
		$type = Newstype::find('pid = "48"')->getAll();
        $Data = $type->toArray();
        $this->_view['Data'] = $Data;

		$this->_view['class'] ='skills';
		$type =$this->_context->type;
        $course = News::find('')->getAll();
		$arr1 = array('0'=>array('id'=>'0','title'=>'1111111','name'=>'新闻列表first'),'1'=>array('id'=>'1','title'=>'222222','name'=>'新闻列表second'));
		$arr2 = array('title'=>'44444444','title'=>'5555555555','title'=>'666666');
		$this->_view['arr1'] =$arr1;
		$this->_view['arr2'] =$arr2;
		$this->_view['course'] =$course;
		$page = (int) $this->_context->page;
        if ($page == 0)
            $page++;
        //echo $page;exit;
        $limit = $this->_context->limit ? $this->_context->limit : 4;
        $q = News::find('type_id = "'.$type.'"')->order('id desc')->limitPage($page, $limit);
        $list = $q->getAll()->toArray();
        $this->_view['pager'] = $q->getPagination();
        $this->_view['list'] = $list;
        $this->_view['start'] = ($page - 1) * $limit;
	}

	function actionCourseresource() {
		$type = Newstype::find('id = "16"')->getAll();
        $Data = $type->toArray();
        // var_dump($Data);exit();
        $this->_view['Data'] = $Data;


	}


        
   }

