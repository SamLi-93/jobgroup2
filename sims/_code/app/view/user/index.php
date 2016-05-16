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
                                <span><input name="title" type="text" aria-controls="DataTables_Table_0" name="title"  id="title" value="<?php echo $title; ?>" placeholder="标题包含文字" /></span>
                                <span><select  id="type_id" name="type_id" >
                                    <?php
                                    echo '<option value="">选择类型</option>';
                                    foreach ($newstype as $k => $v) {
                                        $show = 1;
                                        if (!empty($news_op)) {
                                            if (!in_array($k, $news_op)) {
                                                $show = 0;
                                            }
                                        }
                                        if ($show == 1) {
                                            if ($type_id == $k) {
                                                $sel = 'selected';
                                            } else {
                                                $sel = '';
                                            }
                                            echo '<option value="' . $k . '" ' . $sel . '>' . str_replace('━━', '&nbsp;&nbsp;&nbsp;&nbsp;', $v) . '</option>';
                                        }
                                    }
                                    ?>
                                </select>
                                    <select  id="home_flag" name="home_flag" >
                                        <?php
                                        echo '<option value="">是否在首页显示</option>';
                                        foreach ($flag as $k => $v) {
                                            if (strlen($home_flag) && $home_flag == $k) {
                                                $sel = 'selected';
                                            } else {
                                                $sel = '';
                                            }
                                            echo '<option value="' . $k . '" ' . $sel . '>' . $v . '</option>';
                                        }
                                        ?>
                                    </select>
<select  id="top_flag" name="top_flag" >
                                        <?php
                                        echo '<option value="">是否置顶</option>';
                                        foreach ($flag as $k => $v) {
                                            if (strlen($top_flag) && $top_flag == $k) {
                                                $sel = 'selected';
                                            } else {
                                                $sel = '';
                                            }
                                            echo '<option value="' . $k . '" ' . $sel . '>' . $v . '</option>';
                                        }
                                        ?>
                                    </select>
                                </span>
                                <div>
                                    <div class="btn2 ml20" onclick="$('.fsimple').submit();"><span class="shadow white">查询</span></div>
                                    <div class="btn2 ml20" onclick="window.location.href='<?php echo url('');?>';"><span class="shadow white">重置</span></div>
        
                                    <?php if (hp('news.2')) { ?>
                                        <div  class="btn2 ml20" onclick="window.location.href = '<?php echo url('newsadmin/create'); ?>';" />添加</div>
                                    <?php } ?>
                                </div>
                        </div>
                    </div>
                </form>		
            </div>

            <form class="fsimple" id="form_news" name="form_news" action="" method="get" enctype="application/x-www-form-urlencoded" qform_group_id="">
                <table class="list_table" width="100%" cellpadding="0" cellspacing="0">
                    <thead>
                    <tr>
                        <th style="width:50px">序号</th>
                        <th >标题</th>
                        <th>类型</th>
                        <th >是否在首页显示</th>
                        <th>是否置顶</th>
                        <th>添加时间</th>
                        <?php if (hp('news.3') || hp('news.4')) { ?>
                            <th>操作</th>
                        <?php } ?>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    //print_r($list);
                    foreach ($list as $i => $row) {
                        ?>
                        <tr>
                            <td><?php echo $i + $start + 1 ?></td>
                            <td><?php echo stripslashes(str_replace($title, '<font color=red>' . $title . '</font>', $row['title'])); ?></td>
                            <td><?php echo str_replace('━', '', str_replace('┕', '', $newstype[$row['type_id']])); ?></td>
                            <td><?php
                                if ($row['home_flag'] == 1) {
                                    echo '<font color=red>是</font>';
                                } else {
                                    echo '否';
                                }
                                ?></td>
                            <td><?php
                                if ($row['top_flag'] == 1) {
                                    echo '<font color=red>是</font>';
                                } else {
                                    echo '否';
                                }
                                ?></td>
                            <td><?php echo date('Y-m-d H:i:s', $row['release_date']) ?></td>
                                <?php if (hp('news.3') || hp('news.4')) { ?>
                                <td>
                                    <?php if (hp('news.3')) { ?>
                                        <a class="edit" style="color:#e7613d;" href="<?= url('newsadmin/edit', array('id' => $row['id'])) ?>" title="修改"><i class="icon-edit icon-white"></i>修改</a>&nbsp;
                                <?php } ?>
                                <?php if (hp('news.4')) { ?>
                                        <a class="delete" style="color:#e7613d;" href="<?= url('newsadmin/delete', array('id' => $row['id'])) ?>" onclick="return check()" title="删除"><i class="icon-trash icon-white"></i>删除</a>
                            <?php } ?>
                                </td>
    <?php } ?>
                        </tr> 
            <? } ?>
                    </tbody>
                </table>

            </form>
            <br/>
    <? $this->_control("pagination", "", array('pagination' => $pager)); ?>

        </div>
    </div>
<? $this->_endblock(); ?>
