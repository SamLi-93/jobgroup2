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
    function changeEn_ni(enroll_id){
    	$.ajax({
    		type : "post",
    		method : "post",
    		dataType : "json",
    		data:{"enroll_id":enroll_id,"college_id":$("#qorgedu").val(),"training_id":$("#qorglen").val(),"discipline_id":$("#qdiscipline").val()},
    		url : "<?php echo url('org/getchangeenroll')?>",
    		success : function(data) {
    			var classes_info = '<select class="query_sel" name="qclassinfo" id="qclassinfo" onchange="changeClass_ni(this.value)"><option value="">-请选择-</option>';
    			for (var i in data.class_list) {
    				classes_info += '<option value="'+i+'">'+data.class_list[i]+'</option>';
    			}
    			classes_info += '</select>';
    			$('#span_classid').html(classes_info);
    		}				
    	});
    }
    
    function changeEdu_ni(infovalue){
    	$.ajax({
    		type : "post",
    		method : "post",
    		dataType : "json",
    		data:{"infoid":infovalue,"enroll_id":$("#qenroll").val(),"discipline_id":$("#qdiscipline").val()},
    		url : "<?=url("org/getchangeedu")?>",
    		success : function(data) {
                var classes_info = '<select class="query_sel" name="qorglen" id="qorglen" onchange="changeLen_ni(this.value)" style="margin-right: 0px;"><option value="">-请选择-</option>';
    			for (var i in data.len_list){
    				classes_info = classes_info + '<option value="'+i+'">'+data.len_list[i]+'</option>';
    			}
    			classes_info = classes_info + '</select>';
    			
    			$('#span_training_id').html(classes_info);
    
    			var classes_info = '<select class="query_sel" name="qclassinfo" id="qclassinfo" onchange="changeClass_ni(this.value)"><option value="">-请选择-</option>';
                for (var i in data.class_list){
    				classes_info = classes_info + '<option value="'+i+'">'+data.class_list[i]+'</option>';
    			}
    			classes_info = classes_info + '</select>';
    			$('#span_classid').html(classes_info);
    			
    			var discipline_info = '<select class="query_sel" name="qdiscipline" id="qdiscipline" onchange="changeDis_ni(this.value)"><option value="">-请选择-</option>';
                for (var i in data.discipline_list){
                	discipline_info = discipline_info + '<option value="'+i+'">'+data.discipline_list[i]+'</option>';
    			}
                discipline_info = discipline_info + '</select>';
    			$('#span_discipline_id').html(discipline_info);
    		}
    	});	
    }
    
    function changeLen_ni(infovalue){
    	$.ajax({
    		type : "post",
    		method : "post",
    		dataType : "json",
    		data:{"infoid":infovalue,"infopid":$("#qorgedu").val(),"enroll_id":$("#qenroll").val(),"discipline_id":$("#qdiscipline").val()},
    		url : "<?=url("org/getchangelen")?>",
    		success : function(data) {
    			if(infovalue!=""){//不为空 class 引入
    				$("#qorgedu").prop("value",data.org);
    			}
                var classes_info = '<select class="query_sel" name="qclassinfo" id="qclassinfo" onchange="changeClass_ni(this.value)"><option value="">-请选择-</option>';
    			for (var i in data.class_list){
    				classes_info = classes_info + '<option value="'+i+'">'+data.class_list[i]+'</option>';
    			}
    			classes_info = classes_info + '</select>';
    			$('#span_classid').html(classes_info);
    		}
    	});	
    }
    
    function changeDis_ni(discipline_id) {//变更专业
    	$.ajax({
    		type : "post",
    		method : "post",
    		dataType : "json",
    		data: {"enroll_id":$('#qenroll').val(),
    			   "college_id":$('#qorgedu').val(),
    			   "training_id":$('#qorglen').val(),
    			   "discipline_id":$('#qdiscipline').val()
    			  },
    		url : "<?php echo url("org/getchangedis")?>",
    		success : function(data) {
        		//学习中心
    			var training_info = '<select class="query_sel" name="qorglen" id="qorglen" onchange="changeLen_ni(this.value);"><option value="">-请选择-</option>';
    			for (var i in data.training) {
    				training_info +='<option value="'+i+'">'+data.training[i]+'</option>';
				}
    			training_info +='</select>';
    			$("#span_training_id").html(training_info); 
    			//班级
    			var class_info = '<select class="query_sel" name="qclassinfo" id="qclassinfo" onchange="changeClass_ni(this.value)"><option value="">-请选择-</option>';
    			for (var i in data.class_list) {
    				class_info = class_info + '<option value="'+i+'">'+data.class_list[i]+'</option>';
    			}
    			class_info = class_info + '</select>';
    			$('#span_classid').html(class_info);
    			//主考院校               
                if(data.college_id!=null){     			
    				$("#qorgedu").prop("value",data.college_id);
                }
                if(data.training_id!=null){     			
    				$("#qorglen").prop("value",data.training_id);
    			}
                //科目
                var cc = $('#search_h').html();
                if(cc){
                    var course_info = '打分科目：<select id="courseid" name="courseid"><option value="">请选择</option>';
                    for (var i in data.course_list) {
                        course_info = course_info + '<option value="'+i+'">'+data.course_list[i]+'</option>';
                    }
                    course_info += '</select>';
                    $('#form_users_other').html(course_info);
                }
    		}
    	});
    }
    
    function changeClass_ni(infovalue){
    	if(infovalue!=""){
    		$.ajax({
    			type : "post",
    			method : "post",
    			dataType : "json",
    			data:{"infoid":infovalue},
    			url : "<?=url("org/getchangeclass")?>",
    			success : function(data) {
    			    var classes_info = '<select class="query_sel" name="qorglen" id="qorglen" onchange="changeLen_ni(this.value)" style="margin-right: 0px;"><option value="">-请选择-</option>';
    				for (var i in data.org_len_list){
    					classes_info = classes_info + '<option value="'+i+'">'+data.org_len_list[i]+'</option>';
    				}
    				classes_info = classes_info + '</select>';
    				$('#span_training_id').html(classes_info);
    				$('#qenroll').prop("value",data.enroll_id);
    				$("#qorglen").prop("value",data.len_id);
    				$("#qorgedu").prop("value",data.edu_id);
    				$("#qdiscipline").prop("value",data.discipline_id);
                    //changeDis_ni(data.discipline_id);
                    //科目
                    var cc = $('#search_h').html();
                    if(cc){
                        var cid = $('#courseid').val();
                        var course_info = '打分科目：<select id="courseid" name="courseid"><option value="">请选择</option>';
                        for (var i in data.course_list) {
                            var sel = "";
                            if(cid==i) sel = " selected ";
                            course_info = course_info + '<option value="'+i+'"'+sel+'>'+data.course_list[i]+'</option>';
                        }
                        course_info += '</select><span style="color:red;font-size:16px;line-height:32px;margin-left:1px;">*</span>';
                        $('#form_users_other').html(course_info);
                    }
    			}
    		});
    	}
    }
	
	function aloneExcel(){
        if($("#courseid").val()==''||$("#courseid").val()==null){
            alert('请选中您需要导出的课程！');
        
        }else{
            var qstr = $('#form_users').serialize();
            window.open("<?=url('/scexport')?>?"+qstr,"_blank");
        }

        

    }
</script>
<style type="text/css">
.searchv_table {width:870px;}
.searchv_table td {width:200px;}
.searchv_table select {width:140px;}
.searchv_table .query_text {width:137px;}
#filter_show {_margin-top:-19px;_margin-bottom:-10px;}
</style>
<div class="sims_sbumit">
	<div id="filter_show">
	<form action="<?php echo $uri;?>" method="get" id="form_users" name="form_users" class="fsimple">
	<table class="searchv_table" cellpadding="0" cellspacing="0" style="text-align: center;" >
	<tr height="30">
		<td id="enroll">入学批次&nbsp;:<span id="span_enroll_id" >
			<select class="query_sel" name="qenroll" id="qenroll" onchange="changeEn_ni(this.value)" style="width:150px;">
				<option value="">-请选择-</option>
				<?php foreach ($enroll_list as $k=>$v){ ?>
				<option value="<?php echo $k ; ?>" <?php if(!empty($qenroll)&&$k==$qenroll){?>selected='selected'<?} ?>><?php echo $v;?></option>
				<?php };?>
			</select>
        </span>
		</td>
        <!--
		<td id="zhukao">
		主考院校&nbsp;:
        <span id="span_college_id" >
			<select class="query_sel" name="qorgedu" id="qorgedu" onchange="changeEdu_ni(this.value);" style="width:137px;">
				<option value="">-请选择-</option>
				<?php foreach ($org_edu_list as $k=>$v){ ?>
				<option value="<?php echo $k;?>" <?php if(!empty($qorgedu)&&$k==$qorgedu){ ?>selected='selected'<?} ?>><?php echo $v;?></option>
				<?php };?>
			</select>
        </span>
		</td>
        -->
		<td id="xuexi">
           	 学习中心&nbsp;:
            <span id="span_training_id" >
				<select class="query_sel" name="qorglen" id="qorglen" onchange="changeLen_ni(this.value)"  style="width:137px;margin-right: 0px;">
					<option value="">-请选择-</option>
					<?php foreach ($org_len_list as $k=>$v){ ?>
					<option value="<?php echo $k;?>" <?php if(!empty($qorglen)&&$k==$qorglen){ ?>selected='selected'<?} ?>><?php echo $v;?></option>
					<?php };?>
				</select>
			</span>
		</td>

		<td id="displine">
		所属专业&nbsp;:
			<span id="span_discipline_id">
    			<select class="query_sel" name="qdiscipline" id="qdiscipline" onchange="changeDis_ni(this.value)" style="width:137px;">
    				<option value="">-请选择-</option>
    				<?php foreach ($discipline_list as $k=>$v){ ?>
    				<option value="<?php echo $k;?>" <?php if(!empty($qdiscipline)&&$k==$qdiscipline){?>selected='selected'<?} ?>><?php echo $v;?></option>
    				<?php };?>
    			</select>
            </span>
		</td>
        <td id="class">
        班级名称&nbsp;:
            <span id="span_classid" >
                <select class="query_sel" name="qclassinfo" id="qclassinfo"  onchange="changeClass_ni(this.value)"  style="width:137px;">
                    <option value="">-请选择-</option>
                    <?php foreach ($classinfo_list as $k=>$v){ ?>
                    <option value="<?php echo $k;?>" <?php if(!empty($qclassinfo)&&$k==$qclassinfo){ ?>selected='selected'<?} ?>><?php echo $v;?></option>
                    <?php };?>
                </select>
            </span>
        </td>
	</tr>
	<tr>
		
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
        <td id="empty_td_ss_1">&nbsp;</td>
	</tr>
    <tr id="new_add_tr" style="display:none;"><td id="new_add_td_1"></td><td id="new_add_td_2"></td><td id="new_add_td_3"></td><td id="new_add_td_4"></td></tr>
	<tr class="userset">
		<td colspan="4" align="left">
		</td>
	</tr>
    
	<tr height="5px"></tr>
	<tr>
		<td colspan="4" style="text-align: left;"><nobr>
            <div class="btn2 mr20" id="stusearch_sub" onclick="$('.fsimple').submit();">查询</div>
            <div class="btn2 mr20" onclick="javascript:location='<?php echo $reseturl?>';">重置</div>
		    <?php if ($export) {?>
            <div class="btn2 mr20" onclick="export_xls('<?php echo $exurl?>')" >导出Excel</div>
            <!--<div class="btn2 fleft center ml20" onclick="aloneExcel()" ><span class="shadow white">成绩导出</span></div>-->
            <?php }?>
		    <?php if ($exportfeemodel) {?>
            <div class="btn5 mr20" onclick="export_xls('<?php echo $exfeemodelurl?>')" ><span>导出财务模版</span></div>
            <!--<div class="btn2 fleft center ml20" onclick="aloneExcel()" ><span class="shadow white">成绩导出</span></div>-->
            <?php }?>
		    <?php if ($add){?>
                    <div class="btn2 mr20" onclick="window.location.href='<?php echo url('course/create');?>';">添加</div>
			<?php }?>
            <?php if($btn_prama){echo $btn_prama;} ?>
   		 </nobr>
   		</td>
	</tr>
	<tr height="5px"></tr>
	</table>
    </form>
    </div>
</div>