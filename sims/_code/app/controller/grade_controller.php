<?php
// $Id$

/**
 * Controller_Grade 控制器
 */
class Controller_Grade extends Controller_Abstract
{
    // function __construct($app) {
    //     parent::__construct($app);
    //     $user = $this->_app->currentUser();
    //     if (!$user) {
    //         header('Location:' . url('admin/index'));
    //         exit;
    //     }                 
    //     $grade = new Newstype();
    //     $arr_newstype = $grade->get_select_list();
    //     unset($arr_newstype[0]);
    //     $this->_view['grade'] = $arr_newstype;
    // }

    function actionIndex()
    {
        // 为 $this->_view 指定的值将会传递数据到视图中
        # $this->_view['text'] = 'Hello!';


        $page = (int)$this->_context->page;
        if ($page == 0)
            $page++;
        //echo $page;exit;
        $limit = $this->_context->limit ? $this->_context->limit : 15;

        //搜索
        $search_where = "";
        $search_list_temp = array();
        $name = '';
        $activity_id = '';
        if (isset($_GET['activity_id'])) {
            $activity_id = addslashes(trim($_GET['activity_id']));
            if (strlen($activity_id)) {
                array_push($search_list_temp, " activity_id like '%$activity_id%'");
            }
        }

        if (isset($_GET['name'])) {
            $name = addslashes(trim($_GET['name']));
            if (isset($_GET['activity_id'])) {
                $activity_id = addslashes(trim($_GET['activity_id']));
            }
            $search_list = array();

            if (strlen($name)) {
                array_push($search_list_temp, " name like '%$name%'");
            }
            if (strlen($activity_id)) {
                array_push($search_list_temp, " activity_id like '%$activity_id%'");
            }

        }
        $this->_view['name'] = stripslashes($name);
        $this->_view['activity_id'] = $activity_id;


        //-----------------------------------------------
        $search_where = implode(' and ', $search_list_temp);
        $q = Grade::find($search_where)->order('id desc')->limitPage($page, $limit);
        // print_r($q);exit();
        $list = $q->getAll();
        // var_dump($list);exit();
        //查找
        $q1 = Activity::find()->limitPage($page, $limit);
        $list1 = $q1->getAll()->toHashmap('id', 'activityname');
        //----------------------------------------------
        $this->_view['pager'] = $q->getPagination();
        $this->_view['list'] = $list;
        $this->_view['list1'] = $list1;
        $this->_view['start'] = ($page - 1) * $limit;
        $this->_view['subject'] = "奖项管理";
    }

    function actionCreate()
    {

        $q1 = Activity::find();
        $list1 = $q1->getAll()->toHashmap('id', 'activityname');
        $this->_view['list1'] = $list1;
        $alert = '';
        // $this->_view['flag'] = $flag;

        // $private = Q::ini('appini/private');

        $this->_view['subject'] = "奖项管理";

        if ($this->_context->isPOST() && isset($_POST['name'])) {
            @extract($_POST);
            $name = addslashes(trim($name));
            $zhongjiangnum = addslashes(trim($zhongjiangnum));
            $activity_id = addslashes(trim($activity_id));

            // $private=intval($private);
            $user = $this->_app->currentUser();
            $form_value = array(
                'name' => $name,
                'zhongjiangnum' => $zhongjiangnum,
                'activity_id' => $activity_id,
            );
            $grade = new Grade($form_value);
            $id = $grade->save()->id;

            $alert = "<script language='javascript'>if(confirm('新闻添加成功，是否继续添加？')){window.open('" . url('grade/create') . "','_self');}else{window.open('" . url('grade') . "','_self');}</script>";
        }
        $this->_view['alert'] = $alert;
    }

    function actionEdit()
    {

        $q1 = Activity::find();
        $list1 = $q1->getAll()->toHashmap('id', 'activityname');
        $this->_view['list1'] = $list1;
        $alert = '';
        // $this->_view['flag'] = $flag;

        // $private = Q::ini('appini/private');
        // $this->_view['private'] = $private;        

        $this->_view['subject'] = "奖项管理";

        $id = $this->_context->id;
        $grade = Grade::find()->getById($id);
        if ($this->_context->isPOST() && isset($_POST['name'])) {
            @extract($_POST);
            $name = addslashes(trim($name));
            $zhongjiangnum = addslashes(trim($zhongjiangnum));
            $activity_id = addslashes(trim($activity_id));

            // $top_flag=$top_flag;
            // $home_flag=$home_flag;
            // $now = time();
            $user = $this->_app->currentUser();
            $form_value = array(
                'id' => $id,
                'name' => $name,
                'zhongjiangnum' => $zhongjiangnum,
                'activity_id' => $activity_id,
                // 'top_flag' => $top_flag,
                // 'home_flag' => $home_flag,
            );

            // print_r($form_value);exit()
            // $log_rec = Helper_Util::toArray($news);
            $grade->changeProps($form_value);
            $grade->save();
            //Log::addlog(1, 'grade', $grade->id(), $log_rec, '修改新闻：' . $grade->name, NULL, 'grade');

            $alert = "<script language='javascript'>if(confirm('该条信息修改成功，是否继续修改？')){window.open('" . url('grade/edit', array('id' => $id)) . "','_self');}else{window.open('" . url('grade') . "','_self');}</script>";
        }

        $myData = $grade->toArray();

        $this->_view['myData'] = $myData;
        $this->_view['alert'] = $alert;
    }

    function actionDelete()
    {
        $grade = Grade::find('id = ?', $this->_context->id)->query();
        $grade->destroy();
        return $this->_redirect(url('grade'));
    }
}

