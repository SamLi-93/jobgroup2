<?php
// $Id$

/**
 * Controller_Activity 控制器
 */
class Controller_Activity extends Controller_Abstract
{

	function actionIndex() {
        // 为 $this->_view 指定的值将会传递数据到视图中
        # $this->_view['text'] = 'Hello!';

        // $flag = Q::ini('appini/flag');
        // $this->_view['flag'] = $flag;
        $page = (int) $this->_context->page;
        if ($page == 0)
            $page++;
        //echo $page;exit;
        $limit = $this->_context->limit ? $this->_context->limit : 15;

        //搜索
        $search_where = "";
        $search_list_temp = array();
        $activityname = '';
        if (isset($_GET['activityname'])) {
            $activityname = addslashes(trim($_GET['activityname']));
            $search_list = array();
            if (strlen($activityname)) {
                array_push($search_list_temp, " activityname like '%$activityname%'");
            }
        }
        $actadmin = '';
        $detail = '';
        $this->_view['activityname'] = stripslashes($activityname);
        $this->_view['actadmin'] = stripslashes($actadmin);
        $this->_view['detail'] = stripslashes($detail);
        // $this->_view['release_date'] = date("Y-m-d H:i",$release_date);
        //得到该用户可操作的新闻类型
        
        //-----------------------------------------------
        $search_where = implode(' and ', $search_list_temp);
        $q = Activity::find($search_where)->order('id desc')->limitPage($page, $limit);
        // print_r($q);exit();
        $list = $q->getAll()->toArray();
        // var_dump($list);exit();
        $this->_view['pager'] = $q->getPagination();
        $this->_view['list'] = $list;
        $this->_view['start'] = ($page - 1) * $limit;
        $this->_view['subject'] = "活动管理";
    }

    function actionCreate() {
       
        //-----------------------------------------------
        // $gender = Q::ini('appini/gender');
        // // $rootdir = dirname(dirname(dirname(dirname(__FILE__)))) . DIRECTORY_SEPARATOR;
       
        $alert = '';
        // $this->_view['gender'] = $gender;
        // var_dump($gender);exit();
        // $private = Q::ini('appini/private');
        
        $this->_view['subject'] = "活动管理";
        
        if ($this->_context->isPOST() && isset($_POST['activityname'])) {
            @extract($_POST);
            $activityname = addslashes(trim($activityname));
            $actadmin = addslashes(trim($actadmin));
            $detail = addslashes(trim($detail));
            // $private=intval($private);
            $user = $this->_app->currentUser();
            $form_value = array(
                'activityname' => $activityname,
                'actadmin' => $actadmin,
                'detail' => $detail,
            );
            $activity = new activity($form_value);
            $id = $activity->save()->id;
            
            $alert = "<script language='javascript'>if(confirm('活动添加成功，是否继续添加？')){window.open('" . url('activity/create') . "','_self');}else{window.open('" . url('activity') . "','_self');}</script>";
        }
        $this->_view['alert'] = $alert;
    }

    function actionEdit() {
       
        //-----------------------------------------------
        // $gender = Q::ini('appini/gender');
        $alert = '';
        // $this->_view['gender'] = $gender;
        
        // $private = Q::ini('appini/private');
        // $this->_view['private'] = $private;        


        $this->_view['subject'] = "活动管理";

        $id = $this->_context->id;
        $activity = Activity::find()->getById($id);
        if ($this->_context->isPOST() && isset($_POST['activityname'])) {
            @extract($_POST);
            $activityname = addslashes(trim($activityname));
            $actadmin = addslashes(trim($actadmin));
            $detail = addslashes(trim($detail));
            
            // $top_flag=$top_flag;
            // $home_flag=$home_flag;
            // $now = time();
            $user = $this->_app->currentUser();
            $form_value = array(
                'activityname' => $activityname,
                'actadmin' => $actadmin,
                'detail' => $detail,
                // 'top_flag' => $top_flag,
                // 'home_flag' => $home_flag,
            );
            
            // print_r($form_value);exit()
            // $log_rec = Helper_Util::toArray($news);
            $activity->changeProps($form_value);
            $activity->save();
            //Log::addlog(1, 'activity', $activity->id(), $log_rec, '修改新闻：' . $activity->name, NULL, 'activity');
            
            $alert = "<script language='javascript'>if(confirm('该条信息修改成功，是否继续修改？')){window.open('" . url('activity/edit', array('id' => $id)) . "','_self');}else{window.open('" . url('activity') . "','_self');}</script>";
        }

        $myData = $activity->toArray();
        
        $this->_view['myData'] = $myData;
        $this->_view['alert'] = $alert;
    }

    function actionDelete()
    {
        $activity = Activity::find('id = ?', $this->_context->id)->query();
        $activity->destroy();
        return $this->_redirect(url('activity'));
    }
}


