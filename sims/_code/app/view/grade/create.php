<?php $this->_extends('_layouts/main_layout'); ?>
<?php $this->_block('contents'); ?>
<link type="text/css" href="<?=$_BASE_DIR?>js/calendar/skins/default/theme.css" rel="stylesheet"/>
<script type="text/javascript" src="<?=$_BASE_DIR?>js/calendar/calendar.js"></script>
<script type="text/javascript" src="<?=$_BASE_DIR?>js/calendar/calendar-setup.js"></script>
<script type="text/javascript" src="<?=$_BASE_DIR?>js/calendar/lang/calendar-zh-utf8.js"></script>
<script type="text/javascript" src="<?=$_BASE_DIR?>ckeditor/ckeditor.js"></script>
<script type="text/javascript">
$(function(){
	$('#form_news').submit(function(){
		if($.trim($('#name').val()) == ''){
			alert('请输入获奖等级！');
			$('#name').focus();
			return false;	
		}
    if($.trim($('#zhongjiangnum').val()) == ''){
      alert('请输入中奖楼层！');
      $('#zhongjiangnum').focus();
      return false; 
    }
        if($.trim($('#activity_id').val()) == ''){
            alert('请选择活动！');
            $('#activity_id').focus();
            return false;
        }

        if(confirm('您确定要提交吗？')){
			return true;	
		}else{
			return false;
		}
	});	
});
</script>
<?php echo $alert;?>
<style>
/*打印表格 在第43行*/
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
        <td width="146" >获奖等级</td>
        <td width="842" ><input name="name" type="text"  id="name" value="" size="50" /><font class="req">*</font></td>
      </tr>
      
      <tr height="30">
        <td width="146" >中奖楼层</td>
        <td width="842" ><input name="zhongjiangnum" type="text"  id="zhongjiangnum" value="" size="50" /><font class="req">*</font></td>
      </tr>

      <!--tr height="30">
        <td >访问权限：</td>
        <td ><select  id="private" name="private" >
            <?php
            foreach($private as $k => $v){
                echo '<option value="'.$k.'" >'.$v.'</option>';
            }
            ?>
          </select></td>
      </tr-->

        <tr height="30">
            <td>活动： </td>
            <td>
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
                <font class="req">*</font></td>
        </tr>
     
      <tr height="80">
      <td ></td>
        <td>
          <div class="btn4 mr20" onclick="javascript:$('.fsimple').submit();">保存</div>
          <div class="btn4 mr20" onclick="javascript:location='<?php echo url('grade')?>';">返回</div></td>
      </tr>
    </table>
</form>       
</div>      

            </div>
        </div>
    </div>
</div>
<?php $this->_endblock(); ?>
