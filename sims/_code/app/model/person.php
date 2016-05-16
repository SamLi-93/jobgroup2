<?php

/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2016/2/17
 * Time: 10:25
 */
class Person
{
    public $openid = '';
    public $count = '';

    function getSandeng()
    {
        $sql3 = 'SELECT openid, COUNT(openid) FROM sandeng GROUP BY openid';
        $dbo = QDB::getConn();
        $questype3 = $dbo->getAll($sql3);
        return $questype3;
    }

    function getErdeng()
    {
        $sql2 = 'SELECT openid, COUNT(openid) FROM erdeng GROUP BY openid';
        $dbo = QDB::getConn();
        $questype2 = $dbo->getAll($sql2);
        return $questype2;
    }

    function getYideng()
    {
        $sql1 = 'SELECT openid, COUNT(openid) FROM yideng GROUP BY openid';
        $dbo = QDB::getConn();
        $questype1 = $dbo->getAll($sql1);
        return $questype1;
    }

    function getTedeng()
    {
        $sql0 = 'SELECT openid, COUNT(openid) FROM tedeng GROUP BY openid';
        $dbo = QDB::getConn();
        $questype0 = $dbo->getAll($sql0);
        return $questype0;
    }

    public function getArray($questype0, $questype1, $questype2, $questype3)
    {

        $arr = array_merge($questype0, $questype1, $questype2, $questype3);
        $length = count($arr);
        for ($i = 0; $i < $length; $i++) {
            for ($j = $i + 1; $j < $length - 1; $j++) {
                if ($arr[$i]['openid'] == $arr[$j]['openid']) {
                    $arr[$i]['COUNT(openid)'] += $arr[$j]['COUNT(openid)'];
                    // dump($arr[$i]['COUNT(openid)']);exit;
                    $arr[$j]['COUNT(openid)'] = NULL;
                }
            }
        }

        foreach ($arr as $k => $v) {
            if ($v['COUNT(openid)'] == 0) {
                unset($arr[$k]);
            }
        }

        $show = array();
        for ($i = 0; $i < 957; $i++) {
            if (isset($arr[$i])) {
                $openid = $arr[$i]['openid'];
                $count = $arr[$i]['COUNT(openid)'];
                $sql4 = "select nickname,activity_id,sex from user WHERE openid = '$openid'";
                $dbo = QDB::getConn();
                $result = $dbo->getAll($sql4);
                $activity_id = $result[0]['activity_id'];
                $nickname = $result[0]['nickname'];
                $sex = $result[0]['sex'];
                $show[$i]['openid'] = $openid;
                $show[$i]['count'] = $count;
                $show[$i]['nickname'] = $nickname;
                $show[$i]['sex'] = $sex;
                $show[$i]['activity_id'] = $activity_id;
            }
        }
//        dump(count($show));exit;
        $show = Helper_Array::sortByCol($show, 'count', SORT_DESC);
        return $show;
    }

    public function addData($questype, $prize_id)
    {
        for ($i = 0; $i < count($questype); $i++) {
            $openid = $questype[$i]['openid'];
            $sql4 = "select nickname,activity_id,sex from user WHERE openid = '$openid'";
            $dbo = QDB::getConn();
            $result = $dbo->getAll($sql4);
            $activity_id = $result[0]['activity_id'];
            $nickname = $result[0]['nickname'];
            $sex = $result[0]['sex'];
            $questype[$i]['activity_id'] = $activity_id;
            $questype[$i]['nickname'] = $nickname;
            $questype[$i]['sex'] = $sex;
            $questype[$i]['prize'] = $prize_id;
        }
        return $questype;
    }

    public function processData($countNum, $questype, $showJideng, $prize_id)
    {
        for ($i = 0; $i < $countNum; $i++) {
            $openid = $questype[$i]['openid'];
            $sql4 = "select nickname,activity_id,sex from user WHERE openid = '$openid'";
            $dbo = QDB::getConn();
            $result = $dbo->getAll($sql4);
            $activity_id = $result[0]['activity_id'];
            $nickname = $result[0]['nickname'];
            $sex = $result[0]['sex'];
            $showJideng[$i]['nickname'] = $nickname;
            $showJideng[$i]['sex'] = $sex;
            $showJideng[$i]['activity_id'] = $activity_id;
            $showJideng[$i]['prize'] = $prize_id;
        }
        return $showJideng;
    }

    public function changePage($show_search,$page) {
        $limit = 15;

        $num = count($show_search);

        $start = ($page - 1) * $limit;
        if (!empty($show_search)) {
            $listshow = array_slice($show_search, $start, $limit);
            $this->_view['list'] = $listshow;
        } else $this->_view['list'] = null;

        $help_string = new Helper_String();

        $pager = $help_string->getPage($num, $limit, $page);

        $this->_view['pager'] = $pager;
        $this->_view['start'] = $start;
        return $this;
    }
}