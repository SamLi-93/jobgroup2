<?php
// $Id$

/**
 * Controller_Singlerank 控制器
 */
class Controller_Singlerank extends Controller_Abstract
{
    function actionIndex()
    {
        $person = new Person();
        $questype0 = $person->getTedeng();
        $questype1 = $person->getYideng();
        $questype2 = $person->getErdeng();
        $questype3 = $person->getSandeng();

        $show = $person->getArray($questype0, $questype1, $questype2, $questype3);

//        dump(count($show));exit;  //307条数据 +1

//搜索
        $nickname = '';
        $activity_tag = '';
        if (isset($_GET['activity_id'])) {
            $activity_tag = addslashes(trim($_GET['activity_id']));
        }
        if (isset($_GET['nickname'])) {
            $nickname = addslashes(trim($_GET['nickname']));
            if (isset($_GET['activity_id'])) {
                $activity_tag = addslashes(trim($_GET['activity_id']));
            }
        }

        $this->_view['nickname'] = stripslashes($nickname);
        $this->_view['activity_id'] = $activity_tag;

// $this->_view['activityname'] = $activityname;
// dump($activity_id);
// dump($_REQUEST);
//得到该用户可操作的新闻类型

//-----------------------------------------------
//        $this->_view['show'] = $show;
//        dump(isset($activity_id));exit;
//        dump($activity_tag);
        $show_search = array();
//       dump($nickname);
        foreach ($show as $key => $value) {
            $name = $value['nickname'];
            if (!empty($name) && !empty($nickname) && !empty($activity_tag)) {
                if (strstr($name, $nickname)) {
                    if ($show[$key]['activity_id'] == $activity_tag) {
                        $show_search[$key] = $show[$key];
//                        $this->_view['show'] = $show_search;
                    } elseif ($show[$key]['activity_id'] != $activity_tag) {
//                        $this->_view['show'] = null;
                        $show_search = null;
                    }
                }
            } elseif (empty($nickname) && !empty($activity_tag)) {
                if ($show[$key]['activity_id'] == $activity_tag) {
                    $show_search[$key] = $show[$key];
//                        $this->_view['show'] = $show_search;

                } elseif ($show[$key]['activity_id'] != $activity_tag) {
                    $show_search = null;
//                        $this->_view['show'] = null;
                }
            } elseif (empty($nickname) && empty($activity_tag)) {
                $show_search = $show;
            } elseif (!empty($nickname) && empty($activity_tag)) {
                if (strstr($name, $nickname)) {
                    $show_search[$key] = $show[$key];
                }
            }
        }

//        $page = $this->_context->page;
//        if ($page == 0) {
//            $page++;
//        }/*elseif(`){
//            $page = 1;
//        }*/

        $page = intval($this->_context->page);
        if ($page < 1) $page = 1;

//q1是查询activity的activity_id字段
        $q1 = Activity::find();
        $list1 = $q1->getAll()->toHashmap('id', 'activityname');
// dump($list1);exit;
        $this->_view['list1'] = $list1;
        $this->_view['subject'] = "摇动次数总排名";

        $limit = 15;

        $num = count($show_search);

        $start = ($page - 1) * $limit;
        if (!empty($show_search)) {
            $listshow = array_slice($show_search, $start, $limit);
            $this->_view['list'] = $listshow;
        } else $this->_view['list'] = null;

        $help_string = new Helper_String();
        $pager = $help_string->getPage($num, $limit, $page);
//        dump($num);dump($limit);dump($page);
//        dump($pager);
        $this->_view['pager'] = $pager;
        $this->_view['start'] = $start;

    }
}



