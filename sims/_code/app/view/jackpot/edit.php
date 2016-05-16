<?php $this->_extends('_layouts/main_layout'); ?>
<?php $this->_block('contents'); ?>
<link type="text/css" href="<?=$_BASE_DIR?>js/calendar/skins/default/theme.css" rel="stylesheet"/>
<script type="text/javascript" src="<?=$_BASE_DIR?>js/calendar/calendar.js"></script>
<script type="text/javascript" src="<?=$_BASE_DIR?>js/calendar/calendar-setup.js"></script>
<script type="text/javascript" src="<?=$_BASE_DIR?>js/calendar/lang/calendar-zh-utf8.js"></script>
<script type="text/javascript" src="<?= $_BASE_DIR ?>ckeditor/ckeditor.js"></script>
<script type="text/javascript">
    $(function() {
        $('#form_news').submit(function() {
            if ($.trim($('#nickname').val()) == '') {
                alert('请输入微信名！');
                $('#nickname').focus();
                return false;
            }
            if ($.trim($('#openid').val()) == '') {
                alert('请输入身份号！');
                $('#openid').focus();
                return false;
            }
            if (confirm('您确定要提交吗？')) {
                return true;
            } else {
                return false;
            }
        });
    });
</script>
<?php echo $alert; ?>
<style>
    /*打印表格*/
    .print_table {
        border-collapse:collapse;/*border:1px solid #000000;*/
    }
    .print_table th {
        border:1px solid #CCCCCC;
    }
    .print_table td {
        border:1px solid #CCCCCC;
    }
</style>
<div class="row-fluid sortable" style="width:95%;">		
    <div class="box span12">
        <div class="box-content">
            <div class="row-fluid">
                <div class="sims_sbumit">
                <form class="fsimple" id="form_news" name="form_news" action="" method="post" enctype="multipart/form-data" >
                        <table class="table table-striped table-bordered bootstrap-datatable datatable">
                            <tr height="30">
                                <td width="146" >微信名：</td>
                                <td width="842" ><input name="nickname" type="text"  id="nickname" value="<?php echo stripslashes($myData['nickname']); ?>" size="50" /><font class="req">*</font></td>
                            </tr>
                            <tr height="30">
                                <td >身份号：</td>
                                <td width="842" ><input name="openid" type="text"  id="openid" value="<?php echo stripslashes($myData['openid']); ?>" size="50" /><font class="req">*</font></td>
                            </tr>
                            <tr height="30">
                                <td width="146" >中奖等级：</td>
                                <td ><select  id="grade" name="grade" >
                                        <?php
                                        foreach ($prize as $k => $v) {
                                            if ($myData['grade'] == $k) {
                                                $sel = 'selected';
                                            } else {
                                                $sel = '';
                                            }
                                            echo '<option value="' . $k . '" ' . $sel . '>' . $v . '</option>';
                                        }
                                        ?>
                                    </select></td>



                            </tr>


                            <tr height="30">
                                <td>活动： </td>
                                <td>
                                    <select  id="activity_id" name="activity_id" >
                                        <?php
                                        echo '<option value="">选择活动</option>';
                                        foreach ($list1 as $k => $v) {
                                            // if ($myData['activity_id'] == $k) {
                                            if ($myData['activity_id'] == $k) {
                                                $sel = 'selected';
                                            } else {
                                                $sel = '';
                                            }
                                            echo '<option value="' . $k . '" ' . $sel . '>' . $v . '</option>';
                                        }
                                        ?>
                                    </select>
                                </td>
                            </tr>

                          
                            <tr height="80">
                              <td ></td>
                                <td>
                                  <div class="btn4 mr20" onclick="javascript:$('.fsimple').submit();">保存</div>
                                  <div class="btn4 mr20" onclick="javascript:location='<?php echo url('newsadmin')?>';">返回</div></td>
                              </tr>
                        </table>
                    <input type="hidden" name="id" value="<?php echo $myData['id']; ?>" />
                </form>
                </div>
            </div></div>
      
        <?php $this->_endblock(); ?>
