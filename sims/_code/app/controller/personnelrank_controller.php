<?php
// $Id$

/**
 * Controller_Personnelrank 控制器
 */
class Controller_Personnelrank extends Controller_Abstract
{

    function actionIndex()
    {

        $prize = Q::ini('appini/prize');
        $this->_view['prize'] = $prize;

        $sql3 = "SELECT openid, COUNT(openid),activity_id FROM sandeng WHERE activity_id = '1' GROUP BY openid ";
        $dbo = QDB::getConn();
        $questype3 = $dbo->getAll($sql3);
        $countSandeng = count($questype3);

        $sql2 = "SELECT openid, COUNT(openid),activity_id FROM erdeng WHERE activity_id = '1' GROUP BY openid";
        $dbo = QDB::getConn();
        $questype2 = $dbo->getAll($sql2);
        $countErdeng = count($questype2);

        $sql1 = 'SELECT openid, COUNT(openid),activity_id FROM yideng WHERE activity_id = \'1\' GROUP BY openid';
        $questype1 = $dbo->getAll($sql1);
        $countYideng = count($questype1);

        $sql0 = 'SELECT openid, COUNT(openid),activity_id FROM tedeng WHERE activity_id = \'1\' GROUP BY openid';
        $questype0 = $dbo->getAll($sql0);
        $countTedeng = count($questype0);
        $year = $questype0[0]['activity_id'];

        //把countPerson 是页面显示奖项等级的数组
        $countPerson = array();

//        $countPerson[$year]['特等奖'] = $countTedeng;
//        $countPerson[$year]['一等奖'] = $countYideng;
//        $countPerson[$year]['二等奖'] = $countErdeng;
//        $countPerson[$year]['三等奖'] = $countSandeng;

        // showTedeng 等数组  通过Person类下的方法processData来添加对应的 姓名 性别 抽奖年份 抽奖等级
        $showTedeng = array();
        $showYideng = array();
        $showErdeng = array();
        $showSandeng = array();
        $person = new Person();

        $showSandeng = $person->processData($countSandeng, $questype3, $showSandeng, 1);
        $showErdeng = $person->processData($countErdeng, $questype2, $showErdeng, 2);
        $showYideng = $person->processData($countYideng, $questype1, $showYideng, 3);
        $showTedeng = $person->processData($countTedeng, $questype0, $showTedeng, 4);

        $arr = array_merge($showTedeng, $showYideng, $showErdeng, $showSandeng);


        //搜索
        $nickname = '';
        $activity_tag = '';
        $search_prize = '';

        if (isset($_GET['activity_id'])) {
            $activity_tag = addslashes(trim($_GET['activity_id']));
        }
        if (isset($_GET['prize'])) {
            $search_prize = addslashes(trim($_GET['prize']));
        }
//		$this->_view['arr'] = $arr;
        $show_search = array();

        foreach ($arr as $key => $value) {
            if (empty($activity_tag) && empty($search_prize)) {
                $show_search = $arr;
//				$this->_view['arr'] = $arr;
            } elseif (!empty($activity_tag) && empty($search_prize)) {
                if ($activity_tag == $arr[$key]['activity_id']) {
                    $show_search[$key] = $arr[$key];
//					$this->_view['arr'] = $show_search;
                } else {
                    $this->_view['arr'] = null;
                }
            } elseif (!empty($activity_tag) && !empty($search_prize)) {
                if ($activity_tag == $arr[$key]['activity_id'] && $search_prize == $arr[$key]['prize']) {
                    $show_search[$key] = $arr[$key];
//					$this->_view['arr'] = $show_search;
                } elseif ($activity_tag != $arr[$key]['activity_id']) {
//					$this->_view['arr'] = null;
                    $show_search = null;
                }
            } elseif (empty($activity_tag) && !empty($search_prize)) {
//				$this->_view['arr'] = null;
                $show_search = null;
            }

        }

        if($activity_tag == 1 || empty($activity_tag) ) {
            $countPerson[$year]['特等奖'] = $countTedeng;
            $countPerson[$year]['一等奖'] = $countYideng;
            $countPerson[$year]['二等奖'] = $countErdeng;
            $countPerson[$year]['三等奖'] = $countSandeng;
        }




        $q1 = Activity::find();
        $list1 = $q1->getAll()->toHashmap('id', 'activityname');

        $this->_view['activity_id'] = $activity_tag;
        $this->_view['search_prize'] = $search_prize;

        $this->_view['nickname'] = stripslashes($nickname);

        $this->_view['list1'] = $list1;
        $this->_view['subject'] = "各奖项摇动人数统计";
        $this->_view['countPerson'] = $countPerson;


        $page = $this->_context->page;
        if ($page == 0) $page++;

        $limit = 15;
        $num = count($show_search);
        $start = ($page - 1) * $limit;
        if (!empty($show_search)) {
            $listshow = array_slice($show_search, $start, $limit);
            $this->_view['list'] = $listshow;
        } else $this->_view['list'] = null;
//		dump($show_search);
        $help_string = new Helper_String();
        $pager = $help_string->getPage($num, $limit, $page);
        $this->_view['pager'] = $pager;
        $this->_view['start'] = $start;
//		dump($list1);exit;

    }
}


