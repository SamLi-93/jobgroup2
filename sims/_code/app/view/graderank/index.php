<? $this->_extends("_layouts/main_layout"); ?>
<? $this->_block("contents"); ?>
<script type="text/javascript">

</script>
<div class="row-fluid sortable" style="width:95%">		
    <div class="box span12">
        <div class="box-content">
            <div class="row-fluid">
                <form class="fsimple" id="form_news_search" name="form_news_search" action="<?=url('/index')?>" method="get" enctype="application/x-www-form-urlencoded" qform_group_id="" >
                    <div class="span10" style="margin-bottom:7px;margin-left:40px;">
                        <div id="DataTables_Table_0_filter" class="dataTables_filter">
                                <span><input name="nickname" type="text" aria-controls="DataTables_Table_0" name="nickname"  id="nickname" value="<?php echo $nickname; ?>" placeholder="微信名包含文字" /></span>
                                    <select  id="activity_id" name="activity_id" >
                                        <?php
                                        echo '<option value="">选择活动</option>';
                                        foreach ($list1 as $k => $v) {
                                            // if ($myData['activity_id'] == $k) {
                                            if (strlen($activity_id) && $activity_id == $k) {
                                                $sel = 'selected';
                                            } else {
                                                $sel = '';
                                            }

                                            echo '<option value="' . $k . '" ' . $sel . '>' . $v . '</option>';
                                        }
                                        ?>
                                    </select>

                            <select  id="prize" name="prize" >
                                <?php
                                echo '<option value="">选择中奖等级</option>';
                                foreach ($prize as $k => $v)  {
                                    // if ($myData['grade'] == $k) {
                                    if (strlen($search_prize) && $search_prize == $k) {
                                        $sel = 'selected';
                                    } else {
                                        $sel = '';
                                    }
                                    echo '<option value="' . $k . '" ' . $sel . '>' . $v . '</option>';
                                }
                                ?>
                            </select>

                                <div>
                                    <div class="btn2 ml20" onclick="$('.fsimple').submit();"><span class="shadow white">查询</span></div>
                                    <div class="btn2 ml20" onclick="window.location.href='<?php echo url('');?>';"><span class="shadow white">重置</span></div>
        

                                </div>
                        </div>
                    </div>
                </form>		
            </div>

                <table class="list_table" width="100%" cellpadding="0" cellspacing="0">
                    <thead>
                    <tr>
                        <th style="width:10%">序号</th>
                        <th style="width:30%" >微信名</th>
                        <th style="width:10%">性别</th>
                        <th style="width:20%">抽奖等级</th>
                        <th style="width:20%">活动</th>
                        <th style="width:10%">摇动总次数</th>
                    </tr>
                    </thead>
                    <tbody id="tbody">
                    <?php
                    if(!empty($list)){
                    foreach ($list as $i => $row) {
//                        dump($list[$i]['COUNT(openid)']);
                        // var_dump($row);exit();
                        // dump($row->activity->activityname);exit;
                        ?>
                        <tr>
                            <td><?php echo $i + $start + 1 ?></td>
                            <td><?php echo stripslashes($list[$i]['nickname']); ?></td>

                            <td><?php  stripslashes( $list[$i]['sex']); ?>
                                <?php switch ($list[$i]['sex']) {
                                case '0':
                                    echo "其他";
                                    break;
                                case '1':
                                    echo "男";
                                    break;
                                case '2':
                                    echo "女";
                                    break;
                                default:
                                    # code...
                                    break;
                            }?></td>
                            <td><?php  stripslashes( $list[$i]['prize']); ?>
                                <?php switch ($row['prize']) {
                                    case '1':
                                        echo "三等奖";
                                        break;
                                    case '2':
                                        echo "二等奖";
                                        break;
                                    case '3':
                                        echo "一等奖";
                                        break;
                                    case '4':
                                        echo "特等奖";
                                        break;
                                    default:
                                        break;
                                }?></td>
                            <td><?php echo stripslashes($list1[$row['activity_id']]); ?></td>
                            <td><?php echo stripslashes($list[$i]['COUNT(openid)']*5); ?></td>
                        </tr>
            <? } }?>
                    </tbody>
                </table>

            <br/>
            <? $this->_control("pagination2", "", array('pagination' => $pager)); ?>
        </div>
    </div>

<? $this->_endblock(); ?>
