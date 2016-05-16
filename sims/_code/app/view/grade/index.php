z<? $this->_extends("_layouts/main_layout"); ?>
<? $this->_block("contents"); ?>
<script type="text/javascript">
<!--
    function check() {
        return confirm('确定删除吗？');

    }
//-->
</script>
<div class="row-fluid sortable" style="width:95%">		
    <div class="box span12">
        <div class="box-content">
            <div class="row-fluid">
                <form class="fsimple" id="form_news_search" name="form_news_search" action="" method="get" enctype="application/x-www-form-urlencoded" qform_group_id="" >	
                    <div class="span10" style="margin-bottom:7px;margin-left:40px;">
                        <div id="DataTables_Table_0_filter" class="dataTables_filter">
                                <span><input name="name" type="text" aria-controls="DataTables_Table_0" name="name"  id="name" value="<?php echo $name; ?>" placeholder="中奖等级" /></span>
                                
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
                                    
                                <div>
                                    <div class="btn2 ml20" onclick="$('.fsimple').submit();"><span class="shadow white">查询</span></div>
                                    <div class="btn2 ml20" onclick="window.location.href='<?php echo url('');?>';"><span class="shadow white">重置</span></div>
        
                                    <?php if (hp('grade.2')) { ?>
                                        <div  class="btn2 ml20" onclick="window.location.href = '<?php echo url('grade/create'); ?>';" />添加</div>
                                    <?php } ?>
                                </div>
                        </div>
                    </div>
                </form>		
            </div>

                <table class="list_table" width="100%" cellpadding="0" cellspacing="0">
                    <thead>
                    <tr>
                        <th style="width:50px">序号</th>
                        <th>中奖等级</th>
                        <th>中奖楼层</th>
                        <th>活动</th>
                        
                        <?php if (hp('grade.3') || hp('grade.4')) { ?>
                            <th>操作</th>
                        <?php } ?>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    //print_r($list);
                    foreach ($list as $i => $row) {
                        // var_dump($row);exit();
                        ?>
                        <tr>
                            <td><?php echo $i + $start + 1 ?></td>
                            <td><?php echo stripslashes( $row['name']); ?></td>
                            <td><?php echo stripslashes($row['zhongjiangnum']); ?></td>
                            <td><?php if(isset($activity_id)&&$activity_id!="")  ?><?php echo stripslashes($row->activity->activityname) ?></td>
                                
                                <?php if (hp('grade.3') || hp('grade.4')) { ?>
                                <td>
                                    <?php if (hp('grade.3')) { ?>
                                        <a class="edit" style="color:#e7613d;" href="<?= url('grade/edit', array('id' => $row['id'])) ?>" name="修改"><i class="icon-edit icon-white"></i>修改</a>&nbsp;
                                <?php } ?>
                                <?php if (hp('grade.4')) { ?>
                                        <a class="delete" style="color:#e7613d;" href="<?= url('grade/delete', array('id' => $row['id'])) ?>" onclick="return check()" name="删除"><i class="icon-trash icon-white"></i>删除</a>
                            <?php } ?>
                                </td>
    <?php } ?>
                        </tr> 
            <? } ?>
                    </tbody>
                </table>

            <br/>
    <? $this->_control("pagination2", "", array('pagination' => $pager)); ?>

        </div>
    </div>
<? $this->_endblock(); ?>
