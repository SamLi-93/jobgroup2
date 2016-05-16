<?php
// $Id$

/**
 * Controller_Personnel 控制器
 */
class Controller_Personnel extends Controller_Abstract
{
   
	function actionIndex() {
        // 为 $this->_view 指定的值将会传递数据到视图中
        # $this->_view['text'] = 'Hello!';afa

        $gender = Q::ini('appini/gender');
        $this->_view['gender'] = $gender;
        $page = (int) $this->_context->page;
        if ($page == 0)
            $page++;
        //echo $page;exit;
        $limit = $this->_context->limit ? $this->_context->limit : 15;

        //搜索
        $search_where = "";
        $search_list_temp = array();
        $nickname = '';
        $activity_id='';
        if (isset($_GET['activity_id'])) {
                $activity_id = addslashes(trim($_GET['activity_id']));
                if (strlen($activity_id)) {
                array_push($search_list_temp, " activity_id like '%$activity_id%'");
            }
            }
        if (isset($_GET['nickname'])) {
            $nickname = addslashes(trim($_GET['nickname']));
            $sex = addslashes(trim($_GET['sex']));
            // $activity_id =  $_GET['activity_id'];
            if (isset($_GET['activity_id'])) {
                $activity_id = addslashes(trim($_GET['activity_id']));
            }

//            $search_list = array();
            if (strlen($nickname)) {
                array_push($search_list_temp, " nickname like '%$nickname%'");
            }
            if(strlen($sex)) {
                array_push($search_list_temp, " sex like '%$sex%'");
            }
            if (strlen($activity_id)) {
                array_push($search_list_temp, " activity_id like '%$activity_id%'");
            }
        }
        $sex = '';
        $openid = '';
        
        $this->_view['nickname'] = stripslashes($nickname);
        $this->_view['sex'] = stripslashes($sex);
        $this->_view['openid'] = stripslashes($openid);
        $this->_view['activity_id'] = $activity_id;
        // $this->_view['activityname'] = $activityname;
        // dump($activity_id);
        // dump($_REQUEST);
        //得到该用户可操作的新闻类型
        
        //-----------------------------------------------
        $search_where = implode(' and ', $search_list_temp);
        $q = Personnel::find($search_where)->order('id desc')->limitPage($page, $limit);

        //q1是查询activity的activity_id字段
        $q1 = Activity::find()->limitPage($page, $limit);
        $list1 = $q1->getAll()->toHashmap('id', 'activityname');
//        dump($list1);
        // dump($list1);exit;
        //------------------------------------------------------------------------
        // dump($list);exit;
        $list = $q->getAll();
        // dump($q->getOne()->activity);exit();
        $this->_view['pager'] = $q->getPagination();
        $this->_view['list'] = $list;
        $this->_view['list1'] = $list1;
        $this->_view['start'] = ($page - 1) * $limit;
        $this->_view['subject'] = "人员管理";
    }

    function actionCreate() {
       
        $q1 = Activity::find();
        $list1 = $q1->getAll()->toHashmap('id', 'activityname');
        $this->_view['list1'] = $list1;
        //-----------------------------------------------
        $gender = Q::ini('appini/gender');
        // $rootdir = dirname(dirname(dirname(dirname(__FILE__)))) . DIRECTORY_SEPARATOR;
       
        $alert = '';
        $this->_view['gender'] = $gender;
        // var_dump($gender);exit();
        // $private = Q::ini('appini/private');
        
        $this->_view['subject'] = "人员管理";
        
        if ($this->_context->isPOST() && isset($_POST['nickname'])) {
            @extract($_POST);
            $nickname = addslashes(trim($nickname));
            $sex = addslashes(trim($sex));
            $openid = addslashes(trim($openid));
            $activity_id = addslashes(trim($activity_id));
            
            // $private=intval($private);
            $user = $this->_app->currentUser();
            $form_value = array(
                'nickname' => $nickname,
                'sex' => $sex,
                'openid' => $openid,
                'activity_id' =>$activity_id,
            );
            $personnel = new Personnel($form_value);
            $id = $personnel->save()->id;
            
            $alert = "<script language='javascript'>if(confirm('人员添加成功，是否继续添加？')){window.open('" . url('personnel/create') . "','_self');}else{window.open('" . url('personnel') . "','_self');}</script>";
        }
        $this->_view['alert'] = $alert;
    }

    function actionEdit() {

        $q1 = Activity::find();
        $list1 = $q1->getAll()->toHashmap('id', 'activityname');
        $this->_view['list1'] = $list1;
        //-----------------------------------------------
        $gender = Q::ini('appini/gender');
        $alert = '';
        $this->_view['gender'] = $gender;
        
        // $private = Q::ini('appini/private');
        // $this->_view['private'] = $private;        


        $this->_view['subject'] = "人员管理";

        $id = $this->_context->id;
        $personnel = Personnel::find()->getById($id);
        if ($this->_context->isPOST() && isset($_POST['nickname'])) {
            @extract($_POST);
            $nickname = addslashes(trim($nickname));
            $sex = addslashes(trim($sex));
            $openid = addslashes(trim($openid));
            $activity_id = addslashes(trim($activity_id));
            
            // $top_flag=$top_flag;
            // $home_flag=$home_flag;
            // $now = time();
            $user = $this->_app->currentUser();
            $form_value = array(
                'nickname' => $nickname,
                'sex' => $sex,
                'openid' => $openid,
                'activity_id' =>$activity_id,
                // 'top_flag' => $top_flag,
                // 'home_flag' => $home_flag,
            );
            
            // print_r($form_value);exit()
            // $log_rec = Helper_Util::toArray($news);
            $personnel->changeProps($form_value);
            $personnel->save();
            //Log::addlog(1, 'personnel', $personnel->id(), $log_rec, '修改新闻：' . $personnel->name, NULL, 'personnel');
            
            $alert = "<script language='javascript'>if(confirm('该条信息修改成功，是否继续修改？')){window.open('" . url('personnel/edit', array('id' => $id)) . "','_self');}else{window.open('" . url('personnel') . "','_self');}</script>";
        }

        $myData = $personnel->toArray();
//        dump($myData);
        $this->_view['myData'] = $myData;
        $this->_view['alert'] = $alert;
    }

    function actionDelete()
    {
        $personnel = Personnel::find('id = ?', $this->_context->id)->query();
        $personnel->destroy();
        return $this->_redirect(url('personnel'));
    }
}


