<link rel="stylesheet" type="text/css" href="<?=$_BASE_DIR?>js/multiselect/jquery.multiselect.css">
<link rel="stylesheet" type="text/css" href="<?=$_BASE_DIR?>js/multiselect/assets/style.css" />
<link rel="stylesheet" type="text/css" href="<?=$_BASE_DIR?>js/multiselect/assets/prettify.css" />
<link rel="stylesheet" type="text/css" href="<?=$_BASE_DIR?>css/jquery-ui.css" />
<script type="text/javascript" src="<?=$_BASE_DIR?>js/multiselect/src/jquery.multiselect.js"></script>
<script type="text/javascript" src="<?=$_BASE_DIR?>js/multiselect/src/jquery.multiselect.filter.js"></script>
<script type="text/javascript" src="<?=$_BASE_DIR?>js/multiselect/assets/prettify.js"></script>
<script type="text/javascript">

$(document).ready(function(){  
    $(".query_sel").multiselect({ 
       multiple: false, 
       header: "", 
       noneSelectedText: "请选择", 
       selectedList: 1 
    });
    $(".query_sel").multiselectfilter({width:'100'});
});

function isdelete(can){
	if (can) {
		return confirm("确定要删除该班级？");
	}
	alert("该班级下有学生在读，不能删除该班级，请删除该班级下所有学生后进行该操作！");
	return false;
}

function showLmsCourse(classID){
     $.ajax({
            type: "post",
            method: "post",
            dataType: "json",
            data:{"classID":classID},
            url:"<?=url("class/getlmsCourseInfoByClassID") ?>",
            success: function(data){             
                $('#courses_info').html(data.info_txt);
                top.$.blockUI({
                    theme: true,
                    draggable: true,
                    title: "学习平台开课情况", 
                    message: $('#courses_info').html(),
                    themedCSS:{
                        width:"800px",
                        top:"40px",
                        left:"170px"
                    }
                });                
            }
		});
}
</script>
<div class="sims_sbumit">
<form class="fsimple" action="" method="GET">
<div style="height:30px;">
<span>
所属科目：
<select class="query_sel" id="course_list" name="course_list" onchange="" style="width:150px;">
	<option value="">-请选择-</option>
	<?php foreach ($courselist as $k=>$v){?>
	<option value="<?php echo $k;?>"<?php if(!empty($course_list)&&$course_list==$k){echo "selected='selected'";}?>><?php echo $v;?></option>
	<?php }?>
</select>
</span>
<!--
<span >
所属策略：
<select class="query_sel" id="strategy_list" name="strategy_list" onchange="" >
	<option value="">-请选择-</option>
	<?php foreach ($strategylist as $k=>$v){?>
	<option value="<?php echo $k;?>"<?php if(!empty($strategy_list)&&$strategy_list==$k){echo "selected='selected'";}?>><?php echo $v;?></option>
	<?php }?>
</select>
</span>
-->
<span>
模板试卷名称：<input class="query_text" type="text" id="name" name="name" value="<?php echo !empty($name)?$name:'';?>">
</span></div>

<div style="height:30px;">
<div class="btn2 mr20" onclick="$('.fsimple').submit();"><span class="shadow white">查询</span></div>
<div class="btn2 mr20" onclick="window.location.href='<?php echo url('');?>';"><span class="shadow white">重置</span></div>

<?php if (hp('examtemplate.2')) {
    if($add){?>
	<div class="btn2 mr20" onclick="window.location.href='<?php echo url('/create2');?>';"><span class="shadow white"><?=$add?></span></div>
    <?php }else{?>
	<div class="btn2 mr20" onclick="window.location.href='<?php echo url('/create');?>';"><span class="shadow white">添加</span></div>
<?php }}?>
</div>
</form>
</div>