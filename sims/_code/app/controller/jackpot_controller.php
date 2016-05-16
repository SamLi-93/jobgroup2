<?php
// $Id$

/**
 * Controller_Jackpot 控制器
 */
class Controller_Jackpot extends Controller_Abstract
{
	// function actionIndex() {
 //        // 为 $this->_view 指定的值将会传递数据到视图中
 //        # $this->_view['text'] = 'Hello!';

 //        $prize = Q::ini('appini/prize');
 //        $this->_view['prize'] = $prize;
 //        $page = (int) $this->_context->page;
 //        if ($page == 0)
 //            $page++;
 //        //echo $page;exit;
 //        $limit = $this->_context->limit ? $this->_context->limit : 15;

 //        //搜索
 //        $search_where = "";
 //        $search_list_temp = array();
 //        $grade = ''; //中奖等级

 //        $nickname = '';
 //        // var_dump($nickname);
 //        $grade = '';
 //        $openid = '';
 //        $this->_view['nickname'] = stripslashes($nickname);
 //        $this->_view['openid'] = stripslashes($openid);
 //        $this->_view['grade'] = stripslashes($grade);
        
 //        //得到该用户可操作的新闻类型

 //        //-----------------------------------------------
 //        if (isset($_GET['nickname'])) {
 //            $nickname = addslashes(trim($_GET['nickname']));
 //            if (strlen($nickname)) {
 //                // $search_where = "nickname like '%$nickname%'";
 //                array_push($search_list_temp, " nickname like '%$nickname%'");
 //            }
 //            if (strlen($grade)) {
 //                array_push($search_list_temp, " grade=" . $grade);
 //            }
 //        }

 //        $search_where = implode(' and ', $search_list_temp);

 //        $q = Jackpot::find($search_where)->order('id desc')->limitPage($page, $limit);
 //        // print_r($q);exit();
 //        $list = $q->getAll()->toArray();
 //        // var_dump($list);
        
 //        $this->_view['pager'] = $q->getPagination();
 //        $this->_view['list'] = $list;
 //        $this->_view['start'] = ($page - 1) * $limit;
 //        $this->_view['subject'] = "中奖人员管理";
 //        // var_dump($search_where);
 //    }
    function actionIndex() {
        // 为 $this->_view 指定的值将会传递数据到视图中
        # $this->_view['text'] = 'Hello!';

        $prize = Q::ini('appini/prize');
        $this->_view['prize'] = $prize;
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
            $grade = $_GET['grade'];
            if (isset($_GET['activity_id'])) {
                $activity_id = addslashes(trim($_GET['activity_id']));
                if (strlen($activity_id)) {
                    array_push($search_list_temp, " activity_id like '%$activity_id%'");
                }
            }
//            $search_list = array();
            if (strlen($nickname)) {
                array_push($search_list_temp, " nickname like '%$nickname%'");
            }
            if (strlen($grade)) {
                array_push($search_list_temp, " grade=" . $grade);
            }
            
            if (strlen($activity_id)) {
                array_push($search_list_temp, " activity_id like '%$activity_id%'");
            }
        }
        $openid = '';
        $grade = '';
        // $activityname='';
        // $this->_view['activityname'] = stripcslashes($activityname);
        $this->_view['nickname'] = stripslashes($nickname);
        $this->_view['openid'] = stripslashes($openid);
        $this->_view['grade'] = stripslashes($grade);
        $this->_view['activity_id'] = $activity_id;
        //得到该用户可操作的新闻类型
        
        //-----------------------------------------------
        $search_where = implode(' and ', $search_list_temp);
        $q = Jackpot::find($search_where)->order('id desc')->limitPage($page, $limit);
        

        //查找activity里的activityname字段
        $q1 = Activity::find()->limitPage($page, $limit);
        $list1 = $q1->getAll()->toHashmap('id', 'activityname');
        $list = $q->getAll();
        // dump($list1);exit;
        //---------------------------------------------------------------------------------

        $this->_view['pager'] = $q->getPagination();
        $this->_view['list'] = $list;
        $this->_view['list1'] = $list1;
        $this->_view['start'] = ($page - 1) * $limit;
        $this->_view['subject'] = "中奖人员管理";
    }

    function actionCreate() {

        $q1 = Activity::find();
        $list1 = $q1->getAll()->toHashmap('id', 'activityname');
        $this->_view['list1'] = $list1;
        //-----------------------------------------------
        $prize = Q::ini('appini/prize');
        // var_dump($prize);exit();
        // $rootdir = dirname(dirname(dirname(dirname(__FILE__)))) . DIRECTORY_SEPARATOR;
       
        $alert = '';
        $this->_view['prize'] = $prize;
        // var_dump($gender);exit();
        // $private = Q::ini('appini/private');
        
        $this->_view['subject'] = "中奖人员管理";
        
        if ($this->_context->isPOST() && isset($_POST['nickname'])) {
            @extract($_POST);
            $nickname = addslashes(trim($nickname));
            $openid = addslashes(trim($openid));
            $grade = addslashes(trim($grade));
            $activity_id = addslashes(trim($activity_id));
            // var_dump($grade);exit();

            // $private=intval($private);
            $user = $this->_app->currentUser();
            $form_value = array(
                'nickname' => $nickname,
                'openid' => $openid,
                'grade' => $grade,
                'activity_id' =>$activity_id,
            );
            $jackpot = new Jackpot($form_value);
            $id = $jackpot->save()->id;
            
            $alert = "<script language='javascript'>if(confirm('中奖人员添加成功，是否继续添加？')){window.open('" . url('jackpot/create') . "','_self');}else{window.open('" . url('jackpot') . "','_self');}</script>";
        }
        $this->_view['alert'] = $alert;
    }

    function actionEdit() {

        $q1 = Activity::find();
        $list1 = $q1->getAll()->toHashmap('id', 'activityname');
        $this->_view['list1'] = $list1;
        //-----------------------------------------------
        $prize = Q::ini('appini/prize');
        // $rootdir = dirname(dirname(dirname(dirname(__FILE__)))) . DIRECTORY_SEPARATOR;
        $alert = '';
        $this->_view['prize'] = $prize;
        
        // $private = Q::ini('appini/private');
        // $this->_view['private'] = $private;        

        // $this->_view['rootdir'] = $rootdir;
        $this->_view['subject'] = "中奖人员管理";

        $id = $this->_context->id;
        $jackpot = Jackpot::find()->getById($id);
        if ($this->_context->isPOST() && isset($_POST['nickname'])) {
            @extract($_POST);
            $nickname = addslashes(trim($nickname));
            $openid = addslashes(trim($openid));
            $grade = addslashes(trim($grade));
            $activity_id = addslashes(trim($activity_id));

            // $top_flag=$top_flag;
            // $home_flag=$home_flag;
            // $now = time();
            $user = $this->_app->currentUser();
            $form_value = array(
                'nickname' => $nickname,
                'openid' => $openid,
                'grade' => $grade,
                'activity_id' =>$activity_id,
                // 'top_flag' => $top_flag,
                // 'home_flag' => $home_flag,
            );
            
            // print_r($form_value);exit()
            // $log_rec = Helper_Util::toArray($news);
            $jackpot->changeProps($form_value);
            $jackpot->save();
            //Log::addlog(1, 'jackpot', $jackpot->id(), $log_rec, '修改新闻：' . $jackpot->name, NULL, 'jackpot');
            
            $alert = "<script language='javascript'>if(confirm('该条信息修改成功，是否继续修改？')){window.open('" . url('jackpot/edit', array('id' => $id)) . "','_self');}else{window.open('" . url('jackpot') . "','_self');}</script>";
        }

        $myData = $jackpot->toArray();
        
        $this->_view['myData'] = $myData;
        $this->_view['alert'] = $alert;
    }

    function actionDelete()
    {
        $jackpot = Jackpot::find('id = ?', $this->_context->id)->query();
        $jackpot->destroy();
        return $this->_redirect(url('jackpot'));
    }
}