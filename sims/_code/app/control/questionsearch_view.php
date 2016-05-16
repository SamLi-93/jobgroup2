<link rel="stylesheet" type="text/css" href="<?=$_BASE_DIR?>js/multiselect/jquery.multiselect.css">
<link rel="stylesheet" type="text/css" href="<?=$_BASE_DIR?>js/multiselect/assets/style.css" />
<link rel="stylesheet" type="text/css" href="<?=$_BASE_DIR?>js/multiselect/assets/prettify.css" />
<link rel="stylesheet" type="text/css" href="<?=$_BASE_DIR?>css/jquery-ui.css" />
<script type="text/javascript" src="<?=$_BASE_DIR?>js/jquery-ui.min.js"></script>
<script type="text/javascript" src="<?=$_BASE_DIR?>js/multiselect/assets/prettify.js"></script>
<script type="text/javascript" src="<?=$_BASE_DIR?>js/multiselect/src/jquery.multiselect.js"></script>
<script type="text/javascript">
$(function() {
	$("#knowledge_id").multiselect({
		close: function(){
			var knowledge_ids = $(this).val();
			$("#knowledge_ids").val(knowledge_ids);
			
		}
	});

});

function showcourse(id){
		$.ajax({
			  type : "post",
			  method : "post",
			  dataType : "json",
			  data:{"course_id":id},
			  url : "<?php echo url('/getcourse')?>",
			  success : function(data) {
				$("#span_knowledge_id").html(data);
				$("#knowledge_id").multiselect({
					close: function(){
						var knowledge_ids = $(this).val();
						$("#knowledge_ids").val(knowledge_ids);
						
					}
				});
			  }
		   });
}
</script>
<form class="fsimple" action="<?php echo url('');?>" method="POST" >
<div class="sims_sbumit">
	<div style="height:30px;">
<span>
所属科目：
<select class="query_sel" id="course_id" name="course_id"  onchange="showcourse(this.value)" >
	<option value="">-请选择-</option>
	<?php foreach ($courselist as $k=>$v){?>
	<option value="<?php echo $k;?>"<?php if(!empty($course_id)&&$course_id==$k){echo "selected='selected'";}?>><?php echo $v;?></option>
	<?php }?>
</select>
</span>
<span >
题目类型：
<select class="query_sel" id="type_id" name="type_id" onchange="" >
	<option value="">-请选择-</option>
	<?php foreach ($typelist as $k=>$v){?>
	<option value="<?php echo $k;?>"<?php if(!empty($type_id)&&$type_id==$k){echo "selected='selected'";}?>><?php echo $v;?></option>
	<?php }?>
</select>
</span>
<span >
知识点：
<span id="span_knowledge_id">
<select class="query_sel" id="knowledge_id" name="knowledge_id" multiple="multiple" style="width:180px">
  <?=$knowledgelist?>
</select>
</span>
<input type="hidden" id="knowledge_ids" name="knowledge_ids"  value="<?=$knowledge_ids?>">
</span>
<span>
题目编号：<input class="query_text" type="text" id="itemno" name="itemno" value="<?php echo !empty($itemno)?$itemno:'';?>">
</span>
</div>
<div style="height:30px;">
<div class="btn2 mr20" onclick="$('.fsimple').submit();"><span class="shadow white">查询</span></div>
<div class="btn2 mr20" onclick="window.location.href='<?php echo url('');?>';"><span class="shadow white">重置</span></div>

<?php if (hp('questions.2')) {?>
<div class="btn2 mr20" onclick="window.location.href='<?php echo url('/create', array('course_id'=>$course_id,'type_id'=>$type_id,'tknowledge_ids'=>$knowledge_ids,'titemno'=>$itemno));?>';"><span class="shadow white">添加</span></div>
<?php }?>
<div class="btn2 mr20" onclick="export_xls('<?php echo url('/export', array('course_id'=>!empty($course_id)?$course_id:'','type_id'=>!empty($type_id)?$type_id:'','knowledge_ids'=>!empty($knowledge_ids)?$knowledge_ids:'','itemno'=>!empty($itemno)?$itemno:''))?>')"><span class="shadow white">导出Excel</span></div>
<div class="btn2 mr20" onclick="javascript:location='<?php echo url('/list')?>';"><span class="shadow white">返回</span></div>
</div>
</div>
</form>