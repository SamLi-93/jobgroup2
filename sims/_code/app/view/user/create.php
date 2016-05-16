<?php $this->_extends('_layouts/main_layout'); ?>
<?php $this->_block('contents'); ?>
<link type="text/css" href="<?=$_BASE_DIR?>js/calendar/skins/default/theme.css" rel="stylesheet"/>
<script type="text/javascript" src="<?=$_BASE_DIR?>js/calendar/calendar.js"></script>
<script type="text/javascript" src="<?=$_BASE_DIR?>js/calendar/calendar-setup.js"></script>
<script type="text/javascript" src="<?=$_BASE_DIR?>js/calendar/lang/calendar-zh-utf8.js"></script>
<script type="text/javascript" src="<?=$_BASE_DIR?>ckeditor/ckeditor.js"></script>
<script type="text/javascript">
function insertTabRow(witchID,file_name){
	var rowNum,rowNumNew,insertText;
	rowNum = $('#'+witchID+' tr').size();
	rowNumNew = rowNum +1;
	//alert(rowNumNew);
	insertText = "<tr><td><input name='"+file_name+"' type='file'></td></tr>";	
	//alert(insertText);
	$('#'+witchID).append(insertText);
}
function deleteTabRow(witchID){
	var rowNum,rowNumNew;
	rowNum = $('#'+witchID+' tr').size();
	rowNumNew = rowNum - 1;
	//alert(rowNumNew);
	//if(rowNum > 1){
		$('#'+witchID+' tr:eq('+rowNumNew+')').remove();

	//}
}
$(function(){
	$('#form_news').submit(function(){
		if($.trim($('#title').val()) == ''){
			alert('请输入标题！');
			$('#title').focus();
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
        <td width="146" >标题：</td>
        <td width="842" ><input name="title" type="text"  id="title" value="" size="50" /><font class="req">*</font></td>
      </tr>
      <tr height="30">
        <td >类型：</td>
        <td ><select  id="type_id" name="type_id" >
            <?php
            foreach($newstype as $k => $v){
                $show = 1;
				if(!empty($news_op)){
					if(!in_array($k,$news_op)){
						$show = 0;	
					}
				}
				if($show == 1){
					echo '<option value="'.$k.'" >'.str_replace('━━','&nbsp;&nbsp;&nbsp;&nbsp;',$v).'</option>';
				}
            }
            ?>
          </select></td>
      </tr>
      <tr height="30">
        <td width="146" >来源：</td>
        <td width="842" ><input name="source" type="text"  id="source" value="" size="50" /></td>
      </tr>
      <tr height="30">
        <td >作者：</td>
        <td ><input name="author" type="text"  id="author" value="" size="50" /></td>
      </tr>
      <tr height="30">
        <td >发布时间：</td>
        <td ><input name="release_date" type="text"  id="release_date" value="<?php echo date('Y-m-d H:i:s');?>" size="30" /></td>
      </tr>
      <tr height="30">
        <td >图片：</td>
        <td ><input type="file" name="pic" id="pic" /><span class="error"><?php echo $error_pic_msg;?></span></td>
      </tr>

      <tr height="30">
        <td >视频：</td>
        <td ><input type="file" name="video" id="video" /><span class="error"><?php echo $error_video_msg;?></span></td>        
      </tr>

      <tr height="30">
        <td >附件：</td>
        <td ><img src="<?=$_BASE_DIR?>index_style/images/+.gif" style="cursor:hand;" onClick="javascirpt:insertTabRow('fj_table','fj[]');" title="添加一个附件"> <img src="<?=$_BASE_DIR?>index_style/images/-.gif" style="cursor:hand;" onClick="deleteTabRow('fj_table');" title="删除一个附件"> &nbsp;如需增加<font color="#ff000">附件</font>按"+", 删除按"-"
          <table  id="fj_table" width="100%"  border="0">
          </table></td>
      </tr>
      <tr height="30">
        <td >是否置顶：</td>
        <td ><select  id="top_flag" name="top_flag" >
            <?php
            foreach($flag as $k => $v){
                echo '<option value="'.$k.'" >'.$v.'</option>';
            }
            ?>
          </select></td>
      </tr>
      <tr height="30">
        <td >是否在首页显示：</td>
        <td ><select  id="home_flag" name="home_flag" >
            <?php
            foreach($flag as $k => $v){
                echo '<option value="'.$k.'" >'.$v.'</option>';
            }
            ?>
          </select></td>
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
      <tr>
        <td >内容：</td>
        <td ><textarea name="content" id="content" cols="45" rows="5" height="300"></textarea></td>
      </tr>
     
      <tr height="80">
      <td ></td>
        <td>
          <div class="btn4 mr20" onclick="javascript:$('.fsimple').submit();">保存</div>
          <div class="btn4 mr20" onclick="javascript:location='<?php echo url('newsadmin')?>';">返回</div></td>
      </tr>
    </table>
</form>       
</div>      
<script language="javascript">
    Calendar.setup({inputField : "release_date",ifFormat : "%Y-%m-%d %H:%M:%S",showsTime: true});
	CKEDITOR.replace( 'content', { skin: "office2003", width:670, height:250,filebrowserBrowseUrl : '<?=$_BASE_DIR?>ckeditor/ckfinder/ckfinder.html', filebrowserImageBrowseUrl : '<?=$_BASE_DIR?>ckeditor/ckfinder/ckfinder.html?Type=Images', filebrowserFlashBrowseUrl : '<?=$_BASE_DIR?>ckeditor/ckfinder/ckfinder.html?Type=Flash', filebrowserUploadUrl : '/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files', filebrowserImageUploadUrl : '<?=$_BASE_DIR?>ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images', filebrowserFlashUploadUrl : '<?=$_BASE_DIR?>ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash' });
</script>
            </div>
        </div>
    </div>
</div>
<?php $this->_endblock(); ?>
