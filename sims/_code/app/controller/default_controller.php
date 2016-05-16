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
 * 默认控制器
 */
class Controller_Default extends Controller_Abstract{
	function actionIndex(){
        /*if ($this->_app->currentUserRoles()){// 如果已经登录，就转到任务列表页面
            return $this->_redirect(url('default/main'));
        }else{// 未登录则转到登录页面
            return $this->_redirect(url('admin/login'));
        }*/
        return $this->_redirect(url('default/main'));
    }
	
	function actionChecklogin(){
        if ($this->_app->currentUserRoles()){// 如果已经登录，就转到任务列表页面
            echo 1;
        }else{// 未登录则转到登录页面
            echo 0;
        }
		exit;
	}
	
	// function actionMessagebox(){
	//     $content = $this->_context->content;
	//     $this->_view['content'] = $content;
	//     $this->_view['subject'] = '消息提示';
	// }
	
	// function actionFootnews(){
	// 	$db = QDB::getConn('zjnep_cms');
	// 	$sql = "select * from group_news where type_id=58 order by release_date desc limit 0,8";
	// 	$this->_view['news'] = $db->getAll($sql);
	// 	$this->_view['Itemsbugdb'] = Itemsbug::find()->order('qstatus,create_date desc')->limit(0,8)->getAll();
	// 	$this->_view['Coursecommentdb'] = Coursecomment::find('show_flag=1')->order('create_time desc')->limit(0,8)->getAll();
 //    }
    
	function actionMain(){
		if ($this->_app->currentUserRoles()){// 如果已经登录，就转到任务列表页面
			$user = $this->_app->currentUser();
            $this->_view['fpower']=$user['fpower'];
			// $userinfo = User::find()->getById($user['id']);
			// $org = Org::find()->getById($userinfo['college_id']);

			// $temp_img = Files::find("id=?",$org->logoinfo)->setColumns("path")->getOne();
			$temp_img_path = "";
			if(!empty($temp_img))$temp_img_path=$temp_img->path;
			$this->_view['logoimg'] = $temp_img_path;
			$this->_view['level']=$user['level'];
		    $this->_view['user']=$user;
		    $this->_view['now_time'] = time();
			$this->_view['show'] = "T";
			//未读信息提示
			// $msg_where = ' and [msguser.isdelete]=0 and [msguser.receiver] ="'.$user['id'].'" and ([msguser.status] is null or [msguser.status] = 0)';
	    	// $mymsg = Lcmessage::find("msgtype=3".$msg_where)->getCount();
	    	// $this->_view['mymsg'] = $mymsg;
	    	// $sysmsg = Lcmessage::find("msgtype=1".$msg_where)->getCount();
	    	// $this->_view['sysmsg'] = $sysmsg;
	    	// $notif = Lcmessage::find("msgtype=2".$msg_where)->getCount();
	    	// $this->_view['notif'] = $notif;
	    	

			//模块与权限
			$filename = Q::ini('app_config/CONFIG_DIR').'/auth.yaml.php';
			$this->_view['all_perms'] = Helper_YAML::loadCached($filename);

		}else{// 未登录则转到登录页面
			return $this->_redirect(url('admin/login'));
		}

	}
	
	// function actionWelcome(){

	// 	$user = $this->_app->currentUser();

	// 	//近期考试
	// 	$eroomids = Lcexamroomstu::find('userid=?', $user['id'])->getAll()->values('eroomid');
	// 	$exam_where = $eroomids ? 'id in ('.implode(',', $eroomids).')' : '1<>1';
	// 	$exam_rooms = Examroom::find($exam_where)->order('dates desc')->top(4)->getAll();
	// 	//用户信息
	// 	$users = Users::find()->getById($user['id']);
	// 	//通过跟剩余
	// 	$cpass_count = Score::find('userid=? and passed=1',$user['username'])->getCount();
		
	// 	//$cpass_count = Courseuser::find('userid=? and status=2', $user['id'])->getCount();
	// 	//$cleft_count = Courseuser::find('userid=? and (status is null or status<>2)', $user['id'])->getCount();
	// 	$sql = "select count(lcu.id) from lc_courseuser as lcu,lc_course as lc where lc.liberty_type=0 and lc.id=lcu.courseid and lcu.userid=".$user['id'];
	// 	$db=QDB::getConn();
	// 	$cleft_count = $db->getOne($sql)-$cpass_count;
	// 	//课程列表
	// 	$course_limit = $this->_context->limit ? $this->_context->limit : 10;
	// 	$page=$this->_context->page;
	// 	if ($page==0) $page++;
	// 	$course_pager = Courseuser::find('lc_courseuser.userid=?', $user['id'])->joinInner('lc_course','',"lc_course.isdelete=0 and lc_course.id=lc_courseuser.courseid")->order('lc_courseuser.openflag desc, lc_courseuser.date_get desc')->limitPage($page, $course_limit);
	// 	$this->_view['course_pager'] = $course_pager->getPagination();
	// 	$this->_view['course_list'] = $course_pager->getAll();
	// 	$this->_view['course_states'] = Q::ini('appini/course_states');
	// 	$this->_view['cpass_count'] = $cpass_count;
	// 	$this->_view['cleft_count'] = $cleft_count;

	// 	//新闻公告
	// 	$msg_where = '[isdelete]=0 and [msguser.isdelete]=0 and [msguser.receiver] ="'.$user['id'].'"';
	// 	$msg = Lcmessage::find($msg_where)->top(8)->getAll();
	//     $this->_view['msg'] = $msg;

	//     //答疑
	//     $qid_arr = Answer::find('userid=?',$user['id'])->order('atime desc')->group('qid')->getAll()->values('qid');
	//    	if(count($qid_arr)>0){
	//    		$qid_str = implode(',', $qid_arr);
	//    		$quiz_where = '[isclose]=0 and id not in('.$qid_str.')';
	//    	}else{
	//    		$quiz_where = '[isclose]=0';
	//    	}
	// 	$quiz = Quiz::find($quiz_where)->order('sendtime desc')->top(4)->getAll();
	//     $this->_view['quiz'] = $quiz;
	//     $this->_view['condition'] = $this->panelinit();
	//     $class = Classinfo::find('training_id=?',$user['orgid'])->getAll();
	//     $info_ids = ($user['level']==5)?$user['mclassids']:(($user['level']==1)?67:$user['orgid']);
	// 	$this->_view['exam_rooms'] = $exam_rooms;
	// 	$this->_view['users'] = $users;
	// 	$this->_view['user'] = $user;
	// 	$userinfo['oid'] = $info_ids;
	// 	$userinfo['level'] = $user['level'];
	// 	$this->_view['userinfo'] = $userinfo;
	// 	$this->_view['subject'] = "欢迎界面";
	// }

	// function actionGetcourselist() {
	// 	$user = $this->_app->currentUser();
	// 	$page=$this->_context->page;
	// 	if ($page==0) $page++;
	// 	$limit = 8;
	// 	$n = Courseuser::find('userid=?', $user['id'])->order('date_get desc')->limitPage($page, $limit);
	// 	$this->_view['list'] = $n->getAll();
	// }
	/**
     * 弹出框的初始条件
     * 初始化专业、班级、所属学年
     * 返回所属学年、专业、班级的id，name键值对
     */
    // function panelinit(){
    //     $user = $this->_app->currentUser();
    //     $disc = Discipline::find("isdelete=0")->order("name")->getAll()->toHashMap("id","name");
    //     $class_sql = "isdelete=0";
    //     $clas = Classinfo::find($class_sql)->order("name DESC")->getAll()->toHashMap("id","name");
        
    //     $condition = array(
    //         "disc"=>$disc,
    //         "clas"=>$clas               
    //     );
    //     return $condition;
    // }

  //   function actionSend(){
  //   	$id_arr = $_POST['id_arr'];
  //   	$content = $_POST['content'];
  //   	$user = $this->_app->currentUser();
  //   	$data['title'] = "这是来自".$user['name']."的消息。";
  //   	$data['content'] = str_replace("</p>","",$content);
		// $data['content'] = trim(str_replace("<p>","",$content));
  //       $data['stime'] = time();
  //       $data['sender'] = $user['id'];
  //       $data['priority'] = '';
  //       $data['attach'] = '';
  //       $data['receiver'] = explode(',', $id_arr);
  //       $data['msgtype'] = 3;
  //       $msg = new Lcmessage();
  //       $msg->changeProps($data);
  //       $msg->save();
  //       $datauser = '';
  //       $datauser['msgid'] = $msg['id'];
  //       foreach ($data['receiver'] as $key => $value) {
  //           $msguser = new Lcmsguser();
  //           $datauser['receiver'] = $value;
  //           $datauser['status'] = 0;
  //           $msguser->changeProps($datauser);
  //           $msguser->save(99, 'create');
  //       }
  //       exit;
  //   }

  //   function actionGetmsg(){
  //   	$id = intval($this->_context->id);
  //   	//var_dump($id);
  //   	$msg_where = '[msguser.id]="'.$id.'"';
		// $msg = Lcmessage::find($msg_where)->getOne();
		// Lcmsguser::meta()->updateDbWhere(array('status'=>1),"id ={$id}");
		// return json_encode(array("title"=>$msg['title'],"content"=>$msg['content'],"sender"=>$msg['user']['name']));
  //   }

  //   function actionGetquiz(){
  //   	$id = intval($this->_context->id);
  //   	//var_dump($id);
  //   	$msg_where = 'id='.$id;
		// $msg = Quiz::find($msg_where)->getOne();
		// //dump($msg);
		// return json_encode(array("title"=>$msg['title'],"content"=>$msg['content'],"sender"=>$msg['user']['firstname']));
  //   }
  //   function actionReply(){
  //   	$id = intval($this->_context->id);
  //   	$content = $this->_context->content;
  //   	$user = $this->_app->currentUser();
  //       $data['qid'] = $id;
		// $data['content'] = trim(str_replace("<p>","",$content));
  //       $data['atime'] = time();
  //       $data['userid'] = $user['id'];
  //       $answer = new Answer();
  //       $answer->changeProps($data);
  //       $answer->save();
  //       $answer = Answer::find('userid=?',$user['id'])->order('atime desc')->group('qid')->getAll();
	 //    $qid_arr = array();
	 //   	foreach ($answer as $key => $value) {
	 //   		$qid_arr[] = $value['qid'];
	 //   	}
	 //   	if(count($qid_arr)>0){
	 //   		$qid_str = implode(',', $qid_arr);
	 //   		$quiz_where = '[isclose]=0 and id not in('.$qid_str.')';
	 //   	}else{
	 //   		$quiz_where = '[isclose]=0';
	 //   	}
  //       //$quiz_where = '[isclose]=0 and [answers.userid]<>'.$user['id'];
		// $quiz = Quiz::find($quiz_where)->order('sendtime desc')->top(4)->getAll();
		// $list = array();
		// foreach ($quiz as $key => $value) {
		// 	if(!empty($value['user']['firstname'])){
		// 		$list[$key]['name'] = $value['user']['firstname'];
		// 	}else{
		// 		$list[$key]['name'] = '学生';
		// 	}
		// 	$list[$key]['title'] = $value['title'];
		// 	$list[$key]['id'] = $value['id'];
		// 	if(empty($value['last_answer']['atime'])){
		// 		$list[$key]['time'] = date('m-d h:i',$value['sendtime']);
		// 	}else{
		// 		$list[$key]['time'] = date('m-d h:i',$value['last_answer']['atime']);
		// 	}
		// 	$list[$key]['answer_num'] = $value['answer_num'];
		// }
		// //dump($list);
		// return json_encode(array("list"=>$list));
  //       exit;
  //   }

  //   function actionGetclass(){
  //   	$class_id = intval($this->_context->id);
		// $courseuser = Courseuser::find('class_id=?',$class_id)->top(6)->order('finish asc')->getAll();
		// //对应班级
		// $class = Classinfo::find('id=?',$class_id)->getOne();
		// //主要返回数据
		// $list = array();
		// foreach ($courseuser as $key => $value) {
		// 	//完成度
		// 	$list[$key]['finish'] = ($value['finish']*100).'%';
		// 	//对应课程
		// 	$list[$key]['course_name'] = $value['course']['name'];
		// 	//学生姓名
		// 	$list[$key]['user_name'] = $value['user']['firstname'];
		// 	//完成作业数
		// 	$work_num = Stupage::find('stu_id=? and course_id=? and status=1',$value['userid'],$value['courseid'])->getCount();
		// 	//总作业数
		// 	$max_work_num = Templatepage::find('knowledge_id=9 and course_id=?',$value['courseid'])->getCount();
		// 	if($value['finish']>=0.8){
		// 		$score1 = 20;
		// 	}else{
		// 		$score1 = $value['finish']/0.8*20;
		// 	}
		// 	if($work_num!=0&&$max_work_num!==0){
		// 		$score2 = $work_num/$max_work_num*10;
		// 	}else{
		// 		$score2 = 0;
		// 	}
		// 	//总分
		// 	$list[$key]['score'] = $score1+$score2;
		// 	$list[$key]['work'] = $work_num.'/'.$max_work_num;
		// }
		// return json_encode(array("list"=>$list,"class_name"=>$class['name']));
  //       exit;
  //   }

    /**
     * [actionGetLearningProcessByInfo 根据传入的数据获取相关数据]
     * @param  [type] $info_ids  [相关ID]
     * @param  [type] $info_type [级别 1.2.4.主考院校 3.学习中心 其他.班级]
     * @return [type]            [description]
     */
  //   function getLearningProcessByInfo($info_ids,$info_type){
  //   	$return_arr = array();
  //   	$db = QDB::getConn();
  //   	$sql = "select id,code,name from lc_course where isdelete = 0";
  //   	$c_list = $db->getAll($sql);
  //   	$course_arr = array();
  //   	foreach ($c_list as $key => $val) {
  //   		$course_arr[$val['id']] = "[".$val['code']."]".$val['name'];
  //   	}
  //   	if($info_type == 1||$info_type == 2||$info_type == 4){//主考院校
		// 	$sql = "select id,name from sms_org where pid=".$info_ids;//获取学习中心
		// 	$trainings = $db->getAll($sql);
		// 	$tids = array();
		// 	$tinfo_arr = array();
		// 	foreach ($trainings as $key => $val) {
		// 		$tids[] = $val['id'];//学习中心ID_array 重组
		// 		$tinfo_arr[$val['id']] = $val['name'];
		// 	}
		// 	//根据相关ID获取所有课程相关的
		// 	$sql = "select training_id,courseid,sum(finish) as finfo,count(id) as tnum from lc_courseuser where training_id in (".implode(',', $tids).") group by training_id,courseid order by training_id,courseid";
		// 	$learn_list = $db->getAll($sql);
		// 	$course_ids = array();//course_id_array 用于获取课程作业数
		// 	$temp_cid = array();
		// 	foreach ($learn_list as $key => $val) {
		// 		$temp_cid[$val['courseid']] = 1;	
		// 	}
		// 	foreach ($temp_cid as $key => $val) {
		// 		$course_ids[] = $key;	
		// 	}
		// 	$sql = "select course_id,id from exam_template_page where course_id in (".implode(',', $course_ids).") and knowledge_id=9";//knowledge_id=9 课程作业
		// 	$course_work_list = $db->getAll($sql);//获取对应课程的作业数量
		// 	$tpageids = array();//课程作业ID集合
		// 	$couse_works_num = array();//课程作业总数
		// 	foreach ($course_work_list as $key => $val) {
		// 		$tpageids[] = $val['id'];
		// 		$num = empty($couse_works_num[$val['course_id']])?0:$couse_works_num[$val['course_id']];
		// 		$couse_works_num[$val['course_id']] = ($num+1);
		// 	}
		// 	$sql = "select idst,training_id from core_user where valid=1";
		// 	$ulist = $db->getAll($sql);
		// 	$u_arr = array();//每个学生ID的学习中心
		// 	foreach ($ulist as $key => $val) {
		// 		$u_arr[$val['idst']] = $val['training_id'];
		// 	}
  //           $course_ids = (empty($course_ids)?array('0'=>0):$course_ids);
  //           $tpageids = (empty($tpageids)?array('0'=>0):$tpageids);
		// 	$sql = "select course_id,examnum,stu_id from exam_stu_page where score>59 and course_id in (".implode(',', $course_ids).") and examnum in (".(implode(',', $tpageids)).") group by examnum,course_id,stu_id";//默认60分算完成
		// 	$stu_work_list = $db->getAll($sql);//获取学生作业完成情况
		// 	$training_course_list = array();//$_course_training_完成作业的数量
		// 	foreach ($stu_work_list as $key => $val) {
		// 		$num = empty($training_course_list[$val['course_id']][$u_arr[$val['stu_id']]])?0:$training_course_list[$val['course_id']][$u_arr[$val['stu_id']]];
		// 		$training_course_list[$val['course_id']][$u_arr[$val['stu_id']]] = ($num+1);
		// 	}
		// 	foreach ($learn_list as $key => $val) {
		// 		$temp = array();
		// 		$temp['info_id'] = $val['training_id'];
		// 		$temp['info_name'] = $tinfo_arr[$val['training_id']];
		// 		$temp['cname'] = empty($course_arr[$val['courseid']])?"未知课程":$course_arr[$val['courseid']];
		// 		$finish = round((empty($val['finfo'])?0:$val['finfo'])/$val['tnum']*100,0);
		// 		$temp['finish'] = $finish."%";
		// 		$stu_work_num = empty($training_course_list)?0:(empty($training_course_list[$val['courseid']])?0:(empty($training_course_list[$val['courseid']][$val['training_id']])?0:$training_course_list[$val['courseid']][$val['training_id']]));
		// 		$stu_work = round($stu_work_num/(empty($val['tnum'])?10000000:$val['tnum']),2);
		// 		$work_total = empty($couse_works_num[$val['courseid']])?10000000:$couse_works_num[$val['courseid']];
		// 		$temp['work'] = $stu_work."/".(($work_total==10000000)?0:$work_total);
		// 		$temp['score'] = round((20*((($finish/80)>1)?100:$finish)/100+10*round($stu_work/(empty($work_total)?100000000:$work_total),2)),2);
		// 		$return_arr[] = $temp;
		// 	}
		// }else if($info_type == 3){//学习中心
		// 	$sql = "select id,name from sms_class where training_id=".$info_ids;//获取班级
		// 	$classes = $db->getAll($sql);
		// 	$cids = array();
		// 	$cinfo_arr = array();
		// 	foreach ($classes as $key => $val) {
		// 		$cids[] = $val['id'];//学习中心ID_array 重组
		// 		$cinfo_arr[$val['id']] = $val['name'];
		// 	}
		// 	//根据相关ID获取所有课程相关的
		// 	$sql = "select class_id,courseid,sum(finish) as finfo,count(id) as tnum from lc_courseuser where class_id in (".implode(',', $cids).") group by class_id,courseid order by class_id,courseid";
		// 	$learn_list = $db->getAll($sql);
		// 	$course_ids = array();//course_id_array 用于获取课程作业数
		// 	$temp_cid = array();
		// 	foreach ($learn_list as $key => $val) {
		// 		$temp_cid[$val['courseid']] = 1;	
		// 	}
		// 	foreach ($temp_cid as $key => $val) {
		// 		$course_ids[] = $key;	
		// 	}
		// 	$sql = "select course_id,id from exam_template_page where course_id in (".implode(',', $course_ids).") and knowledge_id=9";//knowledge_id=9 课程作业
		// 	$course_work_list = $db->getAll($sql);//获取对应课程的作业数量
		// 	$tpageids = array();//课程作业ID集合
		// 	$couse_works_num = array();//课程作业总数
		// 	foreach ($course_work_list as $key => $val) {
		// 		$tpageids[] = $val['id'];
		// 		$num = empty($couse_works_num[$val['course_id']])?0:$couse_works_num[$val['course_id']];
		// 		$couse_works_num[$val['course_id']] = ($num+1);
		// 	}
		// 	$sql = "select idst,classid from core_user where valid=1";
		// 	$ulist = $db->getAll($sql);
		// 	$u_arr = array();//每个学生ID的学习中心
		// 	foreach ($ulist as $key => $val) {
		// 		$u_arr[$val['idst']] = $val['classid'];
		// 	}
  //           $course_ids = (empty($course_ids)?array('0'=>0):$course_ids);
  //           $tpageids = (empty($tpageids)?array('0'=>0):$tpageids);
		// 	$sql = "select course_id,examnum,stu_id from exam_stu_page where score>59 and course_id in (".implode(',', $course_ids).") and examnum in (".(implode(',', $tpageids)).") group by examnum,course_id,stu_id";//默认60分算完成
		// 	$stu_work_list = $db->getAll($sql);//获取学生作业完成情况
		// 	$class_course_list = array();//$_course_class_完成作业的数量
		// 	foreach ($stu_work_list as $key => $val) {
		// 		$num = empty($class_course_list[$val['course_id']][$u_arr[$val['stu_id']]])?0:$class_course_list[$val['course_id']][$u_arr[$val['stu_id']]];
		// 		$class_course_list[$val['course_id']][$u_arr[$val['stu_id']]] = ($num+1);
		// 	}
		// 	foreach ($learn_list as $key => $val) {
		// 		$temp = array();
		// 		$temp['info_id'] = $val['class_id'];
		// 		$temp['info_name'] = $cinfo_arr[$val['class_id']];
		// 		$temp['cname'] = $course_arr[$val['courseid']];
		// 		$finish = round((empty($val['finfo'])?0:$val['finfo'])/$val['tnum']*100,0);
		// 		$temp['finish'] = $finish."%";
		// 		$stu_work_num = empty($class_course_list)?0:(empty($class_course_list[$val['courseid']])?0:(empty($class_course_list[$val['courseid']][$val['class_id']])?0:$class_course_list[$val['courseid']][$val['class_id']]));
		// 		$stu_work = round($stu_work_num/(empty($val['tnum'])?10000000:$val['tnum']),2);
		// 		$work_total = empty($couse_works_num[$val['courseid']])?10000000:$couse_works_num[$val['courseid']];
		// 		$temp['work'] = $stu_work."/".(($work_total==10000000)?0:$work_total);
		// 		$temp['score'] = round((20*((($finish/80)>1)?100:$finish)/100+10*round($stu_work/(empty($work_total)?100000000:$work_total),2)),2);
		// 		$return_arr[] = $temp;
		// 	}
		// }else{//班级
		// 	$sql = "select idst,name from core_user where classid in (".$info_ids.")";//获取学生
		// 	$classes = $db->getAll($sql);
		// 	$uids = array();
		// 	$uinfo_arr = array();
		// 	foreach ($classes as $key => $val) {
		// 		$uids[] = $val['idst'];//学习中心ID_array 重组
		// 		$uinfo_arr[$val['idst']] = $val['name'];
		// 	}
		// 	//根据相关ID获取所有课程相关的
		// 	$sql = "select userid,courseid,finish from lc_courseuser where userid in (".implode(',', $uids).")";
		// 	$learn_list = $db->getAll($sql);
		// 	$course_ids = array();//course_id_array 用于获取课程作业数
		// 	$temp_cid = array();
		// 	foreach ($learn_list as $key => $val) {
		// 		$temp_cid[$val['courseid']] = 1;	
		// 	}
		// 	foreach ($temp_cid as $key => $val) {
		// 		$course_ids[] = $key;	
		// 	}
		// 	$sql = "select course_id,id from exam_template_page where course_id in (".implode(',', $course_ids).") and knowledge_id=9";//knowledge_id=9 课程作业
		// 	$course_work_list = $db->getAll($sql);//获取对应课程的作业数量
		// 	$tpageids = array();//课程作业ID集合
		// 	$couse_works_num = array();//课程作业总数
		// 	foreach ($course_work_list as $key => $val) {
		// 		$tpageids[] = $val['id'];
		// 		$num = empty($couse_works_num[$val['course_id']])?0:$couse_works_num[$val['course_id']];
		// 		$couse_works_num[$val['course_id']] = ($num+1);
		// 	}
  //           $course_ids = (empty($course_ids)?array('0'=>0):$course_ids);
  //           $tpageids = (empty($tpageids)?array('0'=>0):$tpageids);
		// 	$sql = "select course_id,examnum,stu_id from exam_stu_page where score>59 and course_id in (".implode(',', $course_ids).") and examnum in (".(implode(',', $tpageids)).") group by examnum,course_id,stu_id";//默认60分算完成
		// 	$stu_work_list = $db->getAll($sql);//获取学生作业完成情况
		// 	$user_course_list = array();//$_course_class_完成作业的数量
		// 	foreach ($stu_work_list as $key => $val) {
		// 		$num = empty($user_course_list[$val['course_id']][$val['stu_id']])?0:$user_course_list[$val['course_id']][$val['stu_id']];
		// 		$user_course_list[$val['course_id']][$val['stu_id']] = ($num+1);
		// 	}
		// 	foreach ($learn_list as $key => $val) {
		// 		$temp = array();
		// 		$temp['info_id'] = $val['userid'];
		// 		$temp['info_name'] = $uinfo_arr[$val['userid']];
		// 		$temp['cname'] = $course_arr[$val['courseid']];
		// 		$finish = round((empty($val['finish'])?0:$val['finish'])*100,0);
		// 		$temp['finish'] = $finish."%";
		// 		$stu_work_num = empty($user_course_list)?0:(empty($user_course_list[$val['courseid']])?0:(empty($user_course_list[$val['courseid']][$val['userid']])?0:$user_course_list[$val['courseid']][$val['userid']]));
		// 		$stu_work = round($stu_work_num/(empty($val['tnum'])?10000000:$val['tnum']),2);
		// 		$work_total = empty($couse_works_num[$val['courseid']])?10000000:$couse_works_num[$val['courseid']];
		// 		$temp['work'] = $stu_work."/".(($work_total==10000000)?0:$work_total);
		// 		$temp['score'] = round((20*((($finish/80)>1)?100:$finish)/100+10*round($stu_work/(empty($work_total)?100000000:$work_total),2)),2);
		// 		$return_arr[] = $temp;
		// 	}
		// }
		// //对Array进行score从低到高的排序
		// $order_arr = array();
		// $val_arr = array();
		// $count = 0;
		// foreach ($return_arr as $key => $val) {
		// 	$order_arr["a".$count] = $val['score'];
		// 	$val_arr["a".$count] = $val;
		// 	$count++;
		// }
		// asort($order_arr);
		// $return_arr_end = array();
		// foreach ($order_arr as $key => $val) {
		// 	$return_arr_end[] = $val_arr[$key];
		// }
  //   	return $return_arr_end;
  //   }
    /**
     * [ActionGetLearprocessTable 获取Table]
     */
   //  function ActionGetLearprocessTable(){
   //  	$user = $this->_app->currentUser();
   //  	$info_id = $this->_context->info_id;
   //  	$type = $this->_context->type;
   //  	$info_list = $this->getLearningProcessByInfo($info_id,$type);
   //  	$db = QDB::getConn();
   //  	$pid = 0;
   //  	if($type==3){//进入学习中心
			// $sql = "select pid from sms_org where id=".$info_id;//需要返回该学习中心的主考院校ID
			// $pid = $db->getOne($sql);
   //  	}else if($type==5){//进入具体班级
			// $sql = "select training_id from sms_class where id=".$info_id;//需要返回该班级的学习中心
			// $pid = $db->getOne($sql);
   //  	}
   //  	return json_encode(array("list"=>$info_list,"userlevel"=>$user['level'],'pid'=>$pid));
   //      exit;
   //  }
}