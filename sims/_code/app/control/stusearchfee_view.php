<link rel="stylesheet" type="text/css" href="<?=$_BASE_DIR?>js/multiselect/jquery.multiselect.css">
<link rel="stylesheet" type="text/css" href="<?=$_BASE_DIR?>js/multiselect/assets/style.css" />
<link rel="stylesheet" type="text/css" href="<?=$_BASE_DIR?>js/multiselect/assets/prettify.css" />
<link rel="stylesheet" type="text/css" href="<?=$_BASE_DIR?>css/jquery-ui.css" />
<script type="text/javascript" src="<?=$_BASE_DIR?>js/multiselect/src/jquery.multiselect.js"></script>
<script type="text/javascript" src="<?=$_BASE_DIR?>js/multiselect/src/jquery.multiselect.filter.js"></script>
<script type="text/javascript" src="<?=$_BASE_DIR?>js/multiselect/assets/prettify.js"></script>
<script type="text/javascript">
    $(document).ready(function(){ 
		qenroll_list_multi();
		qorgedu_list_multi();
		qorglen_list_multi();
		qdiscipline_list_multi();
    });
	//选中入学批次调用的方法
	function qenroll_list_multi(){
		$("#qenroll_list").multiselect({
			close: function(){
				var qenroll = $(this).val();
				$("#qenroll").val(qenroll);
				//alert($("#qenroll").val());
				if(qenroll!=""){
					getclas();
				}
			}
		});
	}
	//选中主考院校调用的方法
	function qorgedu_list_multi(){
		$("#qorgedu_list").multiselect({
			close: function(){
				var qorgedu = $(this).val();
				$("#qorgedu").val(qorgedu);
				//alert($("#qenroll").val());
				if(qorgedu!=""){
					getclas();
					gettraining();
					getdiscipline();
				}
			}
		});
	}
	//选中学习中心调用的方法
	function qorglen_list_multi(){
		$("#qorglen_list").multiselect({
			close: function(){
				var qorglen = $(this).val();
				$("#qorglen").val(qorglen);
				//alert($("#qenroll").val());
				if(qorglen!=""){
					getclas();
				}
			}
		});
	}
	//选中专业调用的方法
	function qdiscipline_list_multi(){
		$("#qdiscipline_list").multiselect({
			close: function(){
				var qdiscipline = $(this).val();
				$("#qdiscipline").val(qdiscipline);
				//alert($("#qenroll").val());
				if(qdiscipline!=""){
					getclas();
				}
			}
		});
	}
	//选中班级调用的方法
	function qclassinfo_list_multi(){
		$("#qclassinfo_list").multiselect({
			close: function(){
				var qclassinfo = $(this).val();
				$("#qclassinfo").val(qclassinfo);
			}
		});
	}
	//重新得到班级列表
	function getclas(){
		//alert($("#qenroll").val());
    	$.ajax({
    		type : "post",
    		method : "post",
    		dataType : "json",
    		data:{"enroll_id":$("#qenroll").val(),"college_id":$("#qorgedu").val(),"training_id":$("#qorglen").val(),"discipline_id":$("#qdiscipline").val()},
    		url : "<?php echo url('feestat/getclass')?>",
    		success : function(data) {
    			var old_clas = $("#qclassinfo").val();
				old_clas = old_clas.split(",");
				var clas_info = "<select class='query_sel' id='qclassinfo_list' name='qclassinfo_list' multiple='multiple' size='6'>";
				var flag;
				for(var i in data.clas_id){
					flag = false;
					for(var d in old_clas){
						if(old_clas[d]==data.clas_id[i])
							flag = true;
					}
					if(flag==true)
						clas_info += "<option value='"+data.clas_id[i]+"' selected='selected'>"+data.clas_name[i]+"</option>";
					else
						clas_info += "<option value='"+data.clas_id[i]+"'>"+data.clas_name[i]+"</option>";
				}
				clas_info += "</select>";
				$("#span_classid").html(clas_info);
				var qclassinfo = $("#qclassinfo").val();
				$("#qclassinfo").val(qclassinfo);
				qclassinfo_list_multi();
    		}				
    	});
    }
	//重新得到学习中心列表
	function gettraining(){
		//alert($("#qorgedu").val());
    	$.ajax({
    		type : "post",
    		method : "post",
    		dataType : "json",
    		data:{"college_id":$("#qorgedu").val()},
    		url : "<?php echo url('feestat/gettraining')?>",
    		success : function(data) {
    			var old_clas = $("#qorglen").val();
				old_clas = old_clas.split(",");
				var clas_info = "<select class='query_sel' id='qorglen_list' name='qorglen_list' multiple='multiple' size='6'>";
				var flag;
				for(var i in data.clas_id){
					flag = false;
					for(var d in old_clas){
						if(old_clas[d]==data.clas_id[i])
							flag = true;
					}
					if(flag==true)
						clas_info += "<option value='"+data.clas_id[i]+"' selected='selected'>"+data.clas_name[i]+"</option>";
					else
						clas_info += "<option value='"+data.clas_id[i]+"'>"+data.clas_name[i]+"</option>";
				}
				clas_info += "</select>";
				$("#span_training_id").html(clas_info);
				var qorglen = $("#qorglen").val();
				$("#qorglen").val(qorglen);
				qorglen_list_multi();
    		}				
    	});
    }
	//重新得到专业列表
	function getdiscipline(){
		//alert($("#qorgedu").val());
    	$.ajax({
    		type : "post",
    		method : "post",
    		dataType : "json",
    		data:{"college_id":$("#qorgedu").val()},
    		url : "<?php echo url('feestat/getdiscipline')?>",
    		success : function(data) {
    			var old_clas = $("#qdiscipline").val();
				old_clas = old_clas.split(",");
				var clas_info = "<select class='query_sel' id='qdiscipline_list' name='qdiscipline_list' multiple='multiple' size='6'>";
				var flag;
				for(var i in data.clas_id){
					flag = false;
					for(var d in old_clas){
						if(old_clas[d]==data.clas_id[i])
							flag = true;
					}
					if(flag==true)
						clas_info += "<option value='"+data.clas_id[i]+"' selected='selected'>"+data.clas_name[i]+"</option>";
					else
						clas_info += "<option value='"+data.clas_id[i]+"'>"+data.clas_name[i]+"</option>";
				}
				clas_info += "</select>";
				$("#span_discipline_id").html(clas_info);
				var qdiscipline = $("#qdiscipline").val();
				$("#qdiscipline").val(qdiscipline);
				qdiscipline_list_multi();
    		}				
    	});
    }

	function aloneExcel(){
        if($("#courseid").val()==''||$("#courseid").val()==null){
            alert('请选中您需要导出的课程！');
        
        }else{
            var qstr = $('#form_users').serialize();
            window.open("<?=url('/scexport')?>?"+qstr,"_blank");
        }

        

    }
	function loading(){
		var cWidth,cHeight,sWidth,sHeight,sLeft,sTop; 
		if (document.compatMode == "BackCompat") { 
			cWidth = document.body.clientWidth; 
			cHeight = document.body.clientHeight; 
			sWidth = document.body.scrollWidth; 
			sHeight = document.body.scrollHeight; 
			sLeft = document.body.scrollLeft; 
			sTop = document.body.scrollTop; 
		}else { //document.compatMode == \"CSS1Compat\" 
			cWidth = document.documentElement.clientWidth; 
			cHeight = document.documentElement.clientHeight; 
			sWidth = document.documentElement.scrollWidth; 
			sHeight = document.documentElement.scrollHeight; 
			sLeft = document.documentElement.scrollLeft == 0 ? document.body.scrollLeft : document.documentElement.scrollLeft;
			sTop = document.documentElement.scrollTop == 0 ? document.body.scrollTop : document.documentElement.scrollTop;
		}
		//alert(sTop);
		var m_top = sTop + "px";
		var m_left = sLeft  + "px";
		$('#msg_6').css({ "margin-top": m_top, "margin-left": m_left });
		$('#msg_6').show();	
	}
</script>
<style type="text/css">
.searchv_table {width:870px;}
.searchv_table td {width:200px;}
.searchv_table select {width:140px;}
.searchv_table .query_text {width:137px;}
#filter_show {_margin-top:-19px;_margin-bottom:-10px;}
</style>
<DIV class="tip" id="msg_6">
	<div class="tipContent"><img src="<?php echo $_BASE_DIR;?>image/schedule.gif"/></div>
</DIV>
<fieldset id="query_form_f" class="query_form">
	<legend><span onclick="show_fld();" style="color:blue;cursor:pointer;">过滤条件</span></legend>
	<div id="filter_show">
	<form action="<?php echo $uri;?>" method="get" id="form_users" name="form_users" class="fsimple">
	<table class="searchv_table" cellpadding="0" cellspacing="0" style="text-align: center;" >
	<tr height="30">
		<td id="enroll">入学批次&nbsp;:<span id="span_enroll_id" >
			<select class="query_sel" name="qenroll_list" id="qenroll_list"  multiple="multiple"  style="width:150px;">
				<?php foreach ($enroll_list as $k=>$v){ ?>
				<option value="<?php echo $k ; ?>" 
				<?php if(!empty($qenroll)){
						$qenroll_array = explode(',',$qenroll);
						if(in_array($k,$qenroll_array)){
							echo ' selected="selected"';
						}
					} ?>><?php echo $v;?></option>
				<?php };?>
			</select>
        </span>
		</td>
		<td id="zhukao">
		主考院校&nbsp;:
        <span id="span_college_id" >
			<select class="query_sel" name="qorgedu_list" id="qorgedu_list" multiple="multiple"  style="width:150px;">
				<?php foreach ($org_edu_list as $k=>$v){ ?>
				<option value="<?php echo $k;?>" 
				<?php if(!empty($qorgedu)){ 
					$qenroll_array = explode(',',$qorgedu);
						if(in_array($k,$qenroll_array)){
							echo ' selected="selected"';
						}
					} ?>><?php echo $v;?></option>
				<?php };?>
			</select>
        </span>
		</td>
		<td id="xuexi">
           	 学习中心&nbsp;:
            <span id="span_training_id" >
				<select class="query_sel" name="qorglen_list" id="qorglen_list" multiple="multiple"   style="width:150px;margin-right: 0px;">
					<?php foreach ($org_len_list as $k=>$v){ ?>
					<option value="<?php echo $k;?>" 
					<?php if(!empty($qorglen)){ 
					$qenroll_array = explode(',',$qorglen);
						if(in_array($k,$qenroll_array)){
							echo ' selected="selected"';
						}
					} ?>><?php echo $v;?></option>
					<?php };?>
				</select>
			</span>
		</td>
		<td id="displine">
		所属专业&nbsp;:
			<span id="span_discipline_id">
    			<select class="query_sel" name="qdiscipline_list" id="qdiscipline_list" multiple="multiple"   style="width:150px;">
    				<?php foreach ($discipline_list as $k=>$v){ ?>
    				<option value="<?php echo $k;?>" 
					<?php if(!empty($qdiscipline)){
						$qenroll_array = explode(',',$qdiscipline);
						if(in_array($k,$qenroll_array)){
							echo ' selected="selected"';
						}
					} ?>><?php echo $v;?></option>
    				<?php };?>
    			</select>
            </span>
		</td>
	</tr>
	<tr>
		<td id="class">
		班级名称&nbsp;:
			<span id="span_classid" >
				<select class="query_sel" name="qclassinfo_list" id="qclassinfo_list"  multiple="multiple"   style="width:150px;">
					<?php foreach ($classinfo_list as $k=>$v){ ?>
					<option value="<?php echo $k;?>" 
					<?php if(!empty($qclassinfo)){ 
					$qenroll_array = explode(',',$qclassinfo);
						if(in_array($k,$qenroll_array)){
							echo ' selected="selected"';
						}
					} ?>
					><?php echo $v;?></option>
					<?php };?>
				</select>
			</span>
		</td>
		<td id="sel">
		登录账号&nbsp;:
			<input class="query_text" name="quserid" value="<?php if(isset($quserid))echo $quserid;?>"style="width: 134px;">
		</td>
		<td>
		<span id="form_stu_name" style="margin-left: 0px;">
		学员姓名&nbsp;:
			<input class="query_text" name="qname" value="<?php if(isset($qname))echo $qname;?>"style="width: 134px;">
		</span>
		</td>
		<td>
		<span id="form_users_other" style="margin-left:0">
		身份证号&nbsp;:
			<input class="query_text" name="qcid" value="<?php if(isset($qcid)) echo $qcid;?>"style="width: 134px;">
		</span>
		</td>
	</tr>
    <input type="hidden" id="qenroll" name="qenroll" value="">
    <input type="hidden" id="qorgedu" name="qorgedu" value="">
    <input type="hidden" id="qorglen" name="qorglen" value="">
    <input type="hidden" id="qdiscipline" name="qdiscipline" value="">
    <input type="hidden" id="qclassinfo" name="qclassinfo" value="">
	<tr class="userset">
		<td colspan="4" align="left">
		</td>
	</tr>
    
	<tr height="5px"></tr>
	<tr>
		<td colspan="4" style="text-align: left;"><nobr>
            <div class="btn2 fleft center ml20" id="stusearch_sub" onclick="loading();$('.fsimple').submit();"><span class="shadow white">查询</span></div>
            <div class="btn2 fleft center ml20" onclick="javascript:location='<?php echo $reseturl?>';"><span class="shadow white">重置</span></div>
		    <?php if ($export) {?>
            <div class="btn2 fleft center ml20" onclick="export_xls('<?php echo $exurl?>')" ><span class="shadow white">导出Excel</span></div>
            <!--<div class="btn2 fleft center ml20" onclick="aloneExcel()" ><span class="shadow white">成绩导出</span></div>-->
            <?php }?>
		    <?php if ($add){?>
                    <div class="btn2 fleft center ml20" onclick="window.location.href='<?php echo url('course/create');?>';"><span class="shadow white">添加</span></div>
			<?php }?>
            <?php if($btn_prama){echo $btn_prama;} ?>
   		 </nobr>
   		</td>
	</tr>
	<tr height="5px"></tr>
	</table>
    </form>
    </div>
</fieldset>
<script type="text/javascript">
$('#qenroll_list').multiselect();
$('#qorgedu_list').multiselect();
$('#qorglen_list').multiselect();
$('#qdiscipline_list').multiselect();
$('#qclassinfo_list').multiselect();
</script>