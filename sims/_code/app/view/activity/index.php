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
                                <span><input name="activityname" type="text" aria-controls="DataTables_Table_0" name="activityname"  id="activityname" value="<?php echo $activityname; ?>" placeholder="标题包含文字" /></span>
                               
                                <div>
                                    <div class="btn2 ml20" onclick="$('.fsimple').submit();"><span class="shadow white">查询</span></div>
                                    <div class="btn2 ml20" onclick="window.location.href='<?php echo url('');?>';"><span class="shadow white">重置</span></div>
        
                                    <?php if (hp('activity.2')) { ?>
                                        <div  class="btn2 ml20" onclick="window.location.href = '<?php echo url('activity/create'); ?>';" />添加</div>
                                    <?php } ?>
                                </div>
                        </div>
                    </div>
                </form>		
            </div>

                <table class="list_table" width="100%" cellpadding="0" cellspacing="0">
                    <thead>
                    <tr>
                        <th style="width:10%">序号</th>
                        <th style="width:20%">活动名称</th>
                        <th style="width:10%">活动组织</th>
                        <th style="width:40%">活动说明</th>
                        <?php if (hp('activity.3') || hp('activity.4')) { ?>
                            <th style="width:20%">操作</th>
                        <?php } ?>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    //print_r($list);
                    foreach ($list as $i => $row) {
                        // var_dump($list);exit;
                        ?>
                        <tr>
                            <td><?php echo $i + $start + 1 ?></td>
                            <td><?php echo stripslashes($row['activityname']); ?></td>
                            <td><?php echo stripslashes($row['actadmin']); ?></td>
                            <td><?php echo stripslashes($row['detail']); ?></td>
                            <!-- <td><?php echo date('Y-m-d H:i:s', $row['release_date']) ?></td> -->
                                <?php if (hp('activity.3') || hp('activity.4')) { ?>
                                <td>
                                    <?php if (hp('activity.3')) { ?>
                                        <a class="edit" style="color:#e7613d;" href="<?= url('activity/edit', array('id' => $row['id'])) ?>" title="修改"><i class="icon-edit icon-white"></i>修改</a>&nbsp;
                                <?php } ?>
                                <?php if (hp('activity.4')) { ?>
                                        <a class="delete" style="color:#e7613d;" href="<?= url('activity/delete', array('id' => $row['id'])) ?>" onclick="return check()" title="删除"><i class="icon-trash icon-white"></i>删除</a>
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
