<?php
// $Id$

/**
 * Controller_Jackpotrank 控制器
 */
class Controller_Jackpotrank extends Controller_Abstract
{

    function actionIndex()
    {

        $person = new Person();
        $questype0 = $person->getTedeng();
        $questype1 = $person->getYideng();
        $questype2 = $person->getErdeng();
        $questype3 = $person->getSandeng();
        // show 数组是一个包括姓名 性别 抽奖年份 openid 摇奖次数的数组 且已经去重复并倒叙排列
        $show = $person->getArray($questype0, $questype1, $questype2, $questype3);

        // 为 $this->_view 指定的值将会传递数据到视图中
        # $this->_view['text'] = 'Hello!';
        $q = Jackpot::find();

        //查找activity里的activityname字段
        $q1 = Activity::find();
        $list1 = $q1->getAll()->toHashmap('id', 'activityname');
        $list = $q->getAll();

        $length_list = count($list);
        $length_show = count($show);

        $countArray = array();
        for ($i = 0; $i < $length_show; $i++) {
            for ($j = 0; $j < $length_list; $j++) {
                if ($show[$i]['openid'] == $list[$j]['openid']) {
                    $countArray[$i]['nickname'] = $show[$i]['nickname'];
                    $countArray[$i]['openid'] = $show[$i]['openid'];
                    $countArray[$i]['activity_id'] = $show[$i]['activity_id'];
                    $countArray[$i]['count'] = $show[$i]['count'];
                }
            }
        }
        // countArray 是在show数组的基础上 通过openid相当来判断  将show数组中的  姓名 openid 抽奖年份 摇奖次数 添加进countArray中
        $countArray = Helper_Array::sortByCol($countArray, 'count', SORT_DESC);


//搜索
        $nickname = '';
        $activity_tag = '';
//        dump(empty($activity_id));exit;
        if (isset($_GET['activity_id'])) {
            $activity_tag = addslashes(trim($_GET['activity_id']));
        }
        if (isset($_GET['nickname'])) {
            $nickname = addslashes(trim($_GET['nickname']));
        }

        $show_search = array();
        foreach ($countArray as $key => $value) {
            $name = $value['nickname'];
            if (!empty($nickname) && !empty($name)) {
                if (strstr($name, $nickname)) {
                    if ($countArray[$key]['activity_id'] == $activity_tag) {
                        $show_search[$key] = $countArray[$key];
//						$this->_view['countArray'] = $show_search;
                    } elseif ($countArray[$key]['activity_id'] != $activity_tag) {
//						$this->_view['countArray'] = null;
                        $show_search = null;
                    }
                }
            } elseif (empty($nickname) && !empty($activity_tag)) {
                if ($countArray[$key]['activity_id'] == $activity_tag) {
                    $show_search[$key] = $countArray[$key];
//					$this->_view['countArray'] = $show_search;
                } elseif ($show[$key]['activity_id'] != $activity_tag) {
//					$this->_view['countArray'] = null;
                    $show_search = null;
                }
            } elseif (empty($nickname) && empty($activity_tag)) {
                $show_search = $countArray;
            }
        }

//		dump($countArray);exit;
//		 dump($list);exit;
//----------------	-----------------------------------------------------------------
//		dump($list1);exit;

        $this->_view['nickname'] = stripslashes($nickname);
        $this->_view['list1'] = $list1;
        $this->_view['subject'] = "中奖人员摇动次数统计";
        $this->_view['activity_id'] = $activity_tag;
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


