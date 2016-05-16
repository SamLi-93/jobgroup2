<?php
// $Id$

/**
 * Controller_User 控制器
 */
class Controller_User extends Controller_Abstract
{

	function actionIndex() {
        // 为 $this->_view 指定的值将会传递数据到视图中
        # $this->_view['text'] = 'Hello!';

        $flag = Q::ini('appini/flag');
        $this->_view['flag'] = $flag;
        $page = (int) $this->_context->page;
        if ($page == 0)
            $page++;
        //echo $page;exit;
        $limit = $this->_context->limit ? $this->_context->limit : 15;

        //搜索
        $search_where = "";
        $search_list_temp = array();
        $name = '';
        if (isset($_GET['name'])) {
            $name = addslashes(trim($_GET['name']));
            
            $search_list = array();
            if (strlen($name)) {
                array_push($search_list_temp, " name like '%$name%'");
            }
            
        }
        $this->_view['name'] = stripslashes($name);
        //得到该用户可操作的新闻类型
        $user = $this->_app->currentUser();
        $news_op = array();
        if (isset($user['news_op'])) {
            if (!empty($user['news_op'])) {
                $news_op = explode(',', $user['news_op']);
            }
        }
        $this->_view['news_op'] = $news_op;
        //-----------------------------------------------
        $search_where = implode(' and ', $search_list_temp);
        $q = Grade::find($search_where)->order('id desc')->limitPage($page, $limit);
        // print_r($q);exit();
        $list = $q->getAll()->toArray();
        // var_dump($list);exit();
        $this->_view['pager'] = $q->getPagination();
        $this->_view['list'] = $list;
        $this->_view['start'] = ($page - 1) * $limit;
        $this->_view['subject'] = "奖项管理";
    }

    function actionCreate() {
        //得到该用户可操作的新闻类型
        $user = $this->_app->currentUser();
        $news_op = array();
        if (isset($user['news_op'])) {
            if (!empty($user['news_op'])) {
                $news_op = explode(',', $user['news_op']);
                //array_push($search_list_temp," type_id in (".$user['news_op'].")");   
            }
        }
        $this->_view['news_op'] = $news_op;
        //-----------------------------------------------
        // $flag = Q::ini('appini/flag');
        $rootdir = dirname(dirname(dirname(dirname(__FILE__)))) . DIRECTORY_SEPARATOR;
       
        $alert = '';
        // $this->_view['flag'] = $flag;
        
        // $private = Q::ini('appini/private');
        
        $this->_view['subject'] = "奖项管理";
        
        if ($this->_context->isPOST() && isset($_POST['name'])) {
            @extract($_POST);
            $name = addslashes(trim($name));
            $zhongjiangnum = addslashes(trim($zhongjiangnum));
            
            // $private=intval($private);
            $user = $this->_app->currentUser();
            $form_value = array(
                'name' => $name,
                'zhongjiangnum' => $zhongjiangnum,
            );
            $grade = new Grade($form_value);
            $id = $grade->save()->id;
            
            $alert = "<script language='javascript'>if(confirm('新闻添加成功，是否继续添加？')){window.open('" . url('grade/create') . "','_self');}else{window.open('" . url('grade') . "','_self');}</script>";
        }
        $this->_view['alert'] = $alert;
    }

    function actionEdit() {
        //得到该用户可操作的新闻类型
        $user = $this->_app->currentUser();
        $news_op = array();
        if (isset($user['news_op'])) {
            if (!empty($user['news_op'])) {
                $news_op = explode(',', $user['news_op']);
                //array_push($search_list_temp," type_id in (".$user['news_op'].")");   
            }
        }
        $this->_view['news_op'] = $news_op;
        //-----------------------------------------------
        // $flag = Q::ini('appini/flag');
        $rootdir = dirname(dirname(dirname(dirname(__FILE__)))) . DIRECTORY_SEPARATOR;
        $alert = '';
        // $this->_view['flag'] = $flag;
        
        // $private = Q::ini('appini/private');
        // $this->_view['private'] = $private;        

        $this->_view['rootdir'] = $rootdir;
        $this->_view['subject'] = "奖项管理";

        $id = $this->_context->id;
        $grade = Grade::find()->getById($id);
        if ($this->_context->isPOST() && isset($_POST['name'])) {
            @extract($_POST);
            $name = addslashes(trim($name));
            $zhongjiangnum = addslashes(trim($zhongjiangnum));
            
            // $top_flag=$top_flag;
            // $home_flag=$home_flag;
            // $now = time();
            $user = $this->_app->currentUser();
            $form_value = array(
                'id' => $id,
                'name' => $name,
                'zhongjiangnum' => $zhongjiangnum,
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

