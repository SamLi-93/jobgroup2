<?php $this->_extends('_layouts/main_layout'); ?>
 
<?php $this->_block('contents'); ?>
<link rel="stylesheet" type="text/css" href="<?=$_BASE_DIR?>js/multiselect/jquery.multiselect.css">
<link rel="stylesheet" type="text/css" href="<?=$_BASE_DIR?>js/multiselect/assets/style.css" />
<link rel="stylesheet" type="text/css" href="<?=$_BASE_DIR?>js/multiselect/assets/prettify.css" />
<link rel="stylesheet" type="text/css" href="<?=$_BASE_DIR?>css/jquery-ui.css" />
<script type="text/javascript" src="<?=$_BASE_DIR?>js/jquery-ui.min.js"></script>
<script type="text/javascript" src="<?=$_BASE_DIR?>js/multiselect/assets/prettify.js"></script>
<script type="text/javascript" src="<?=$_BASE_DIR?>js/multiselect/src/jquery.multiselect.js"></script>
<script type="text/javascript" src="<?=$_BASE_DIR?>js/multiselect/src/jquery.multiselect.filter.js"></script>

<script type="text/javascript">

    function changeEdu(infovalue){
        $('#span_orgdxx').show();
        return $.ajax({
            type : "post",
            method : "post",
            dataType : "json",
            data:{"infoid":infovalue,
                  "enroll_id":"",
                  "discipline_id":""},
            url : "<?=url("org/getchangeedu")?>",
            success : function(data) {
                var classes_info = '<select class="login_input1" id="orgidxx" name="orgidxx" onchange="ch_classinfo();"><option value="0">-请选择-</option>';
                for (var i in data.len_list){
                    classes_info = classes_info + '<option value="'+i+'">'+data.len_list[i]+'</option>';
                }
                classes_info = classes_info + '</select> <font class="req">*</font>';
                
                $('#span_orgidxx').html(classes_info);
                $('#span_orgidxx').show();
                $('#span_orgidxx').next().show();

                /*
                var class_info = '<select id="classinfoid" name="classinfoid" class="login_input1" ><option value="">-请选择-</option>';
                for (var i in data.class_list){
                    class_info = class_info + '<option value="'+i+'">'+data.class_list[i]+'</option>';
                }
                class_info = class_info + '</select> <font class="req">*</font>';

                $('#span_classinfoid').html(class_info);
                */

            }
        });	
    }
    function show_orgid(){
        $('#span_orgidxx').show();
    }

    function ch_classinfo(infoid,sel_id){//获取班级名称根据
        if($("#level").val()==5){
            var class_info_text = '<select id="classinfoid" name="classinfoid[]" class="login_input1" multiple="multiple" size="6"></select> <font class="req">*</font>';
            var org_info = "";
            var arrtr;
            //console.log(sel_id);
            if(typeof(sel_id) !== "undefined"){
            infoid=(infoid==null?"":infoid);
            sel_id=(sel_id==null?[]:sel_id);
            
			var str = sel_id.substr(1,sel_id.length-2);
			
			var cdata = new Array();
			arrtr = str.split(",");
			for(var ix in arrtr){
				var nn = arrtr[ix].substr(1,arrtr[ix].length-2);
				//console.log(nn);
				cdata[ix] = nn;
			}
			//console.log(cdata);
			}
            if(typeof(infoid)=="undefined"){
                org_info = $("#orgidxx").val();
            }else{
                org_info = infoid;
            }
            if(org_info!=""){
            	//console.log(org_info);
                 $.ajax({
                    type : "post",
                    method : "post",
                    dataType : "json",
                    url : "<?php echo url('org/getClassByLenID')?>",
                    data:{"lenid":org_info},
                    success : function(data) {
                    	
                        class_info_text = '<select id="classinfoid" name="classinfoid[]" class="login_input1" multiple="multiple" size="6">';
                        for (var i in data.classList) {
                        	ii = '"'+i+'"';
                        	//var ii = i.toString();
                        	
                            if(typeof(sel_id) !== "undefined" && $.inArray(ii,arrtr)>-1){
                            	//console.log(i);
                                class_info_text += '<option value="'+i+'" selected>'+data.classList[i]+'</option>';
                            }else{
                                class_info_text += '<option value="'+i+'">'+data.classList[i]+'</option>';   
                            }
                        }
                        class_info_text += '</select> <font class="req">*</font>';
						$('#span_classinfoid').html(class_info_text).show().next().show();
						$('#classinfoid').multiselect();
                    }				
                });
            }else{
				$('#span_classinfoid').html(class_info_text).show().next().show();
				$('#classinfoid').multiselect();
			}
        }
    }

    function ch_orginfo(sel_id){//获取班级名称根据
        if($("#level").val()==6){
            var arrtr;
            if(typeof(sel_id) !== "undefined"){
            	sel_id=(sel_id==null?[]:sel_id);
            }
			var str = sel_id.substr(1,sel_id.length-2);
			var cdata = new Array();
			arrtr = str.split(",");
			$.ajax({
				type : "post",
				method : "post",
				dataType : "json",
				url : "<?php echo url('org/getAllLen')?>",
				success : function(data) {
					var classes_info = '<select  id="orgid" name="orgid[]" class="login_input1" onchange="changeEdu(this.value)" multiple="multiple">';
					
					for (var i in data.len_edu_list) {
						ii = '"'+data.len_edu_list[i]['id']+'"';
				        if(typeof(sel_id) !== "undefined" && $.inArray(ii,arrtr)>-1){
				            classes_info += '<option value="'+data.len_edu_list[i]['id']+'" selected>'+data.len_edu_list[i]['name']+"【"+data.len_edu_list[i]['org']+"】"+'</option>';
				        }else{
				            classes_info += '<option value="'+data.len_edu_list[i]['id']+'">'+data.len_edu_list[i]['name']+"("+data.len_edu_list[i]['org']+")"+'</option>';   
				        }
						
					}
					classes_info += '</select> <font class="req">*</font>';
					$('#span_orgid').html(classes_info).show().next().show();
					$('#orgid').multiselect().multiselectfilter();
					$("#tr_orgidxx").hide();
					$("#tr_classinfoid").hide();
				}				
			});
        }
    }

	function ch_classname(infovalue){
		var text_info = "";
		if(infovalue=="0"){//超级管理员
			var classes_info = '<select  id="orgid" name="orgid" class="login_input1" ><option value=""></option></select> <font class="req">*</font>';
			$("#span_orgid").html(classes_info)
			$('#span_orgidxx').prev().hide();
			$('#span_orgidxx').hide();
            $('#span_orgidxx').next().hide();

            $('#span_classinfoid').prev().hide();
			$('#span_classinfoid').hide();
            $('#span_classinfoid').next().hide();
           

		}else if(infovalue=="1"){
			text_info = '<select  id="orgid" name="orgid" class="login_input1" ><option value="0">吉博教育</option> </select> <font class="req">*</font>'
			$('#span_orgid').html(text_info);
			$("#tr_orgidxx").hide();
			$("#tr_classinfoid").hide();
			<?php if(!empty($fpower)){ ?>
            	$("#tr_fpower").show();
            <?php } ?>

		}else if(infovalue=="2"){
			
			return $.ajax({
				type : "post",
				method : "post",
				dataType : "json",
				url : "<?php echo url('org/getAllEdu')?>",
				success : function(data) {
					var classes_info = '<select  id="orgid" name="orgid" class="login_input1" ><option value="">-请选择-</option>';
					for (var i in data.org_edu_list) {
						classes_info += '<option value="'+i+'">'+data.org_edu_list[i]+'</option>';
					}
					classes_info += '</select> <font class="req">*</font>';
					$('#span_orgid').html(classes_info);
                    $("#tr_orgidxx").hide();
					$("#tr_classinfoid").hide();
				}				
			});
		}else if(infovalue=="3"){
			return $.ajax({
				type : "post",
				method : "post",
				dataType : "json",
				url : "<?php echo url('org/getAllEdu')?>",
				success : function(data) {
					var classes_info = '<select  id="orgid" name="orgid" class="login_input1" onchange="changeEdu(this.value)"><option value="">-请选择-</option>';
					for (var i in data.org_edu_list) {
						classes_info += '<option value="'+i+'">'+data.org_edu_list[i]+'</option>';
					}
					classes_info += '</select> <font class="req">*</font>';					
					$('#span_orgid').html(classes_info);	
                    $("#tr_orgidxx").show();
					$("#tr_classinfoid").hide();
				}				
			});
		}else if(infovalue=="4"){
			return $.ajax({
				type : "post",
				method : "post",
				dataType : "json",
				url : "<?php echo url('org/getAllEdu')?>",
				success : function(data) {
					
					var classes_info = '<select  id="orgid" name="orgid" class="login_input1" ><option value="">-请选择-</option>';
					for (var i in data.org_edu_list) {
						classes_info += '<option value="'+i+'">'+data.org_edu_list[i]+'</option>';
					}
					classes_info += '</select> <font class="req">*</font>';
					
					$('#span_orgid').html(classes_info);
					<?php if($edit==1 && $user_level==4){?>
					$(document).ready(function(){	
						$("#level").attr("disabled","disabled");
						$("#orgid").attr("disabled","disabled");
						$("#valid").attr("disabled","disabled");
					
					});
					$('#span_orgidxx').next().hide();
					<?php }?>
                   	$("#tr_orgidxx").hide();
					$("#tr_classinfoid").hide();
				}				
			});
		}else if(infovalue=="5"){
			return $.ajax({
				type : "post",
				method : "post",
				dataType : "json",
				url : "<?php echo url('org/getAllEdu')?>",
				success : function(data) {
					var classes_info = '<select  id="orgid" name="orgid" class="login_input1" onchange="changeEdu(this.value)"><option value="">-请选择-</option>';
					for (var i in data.org_edu_list) {
						classes_info += '<option value="'+i+'">'+data.org_edu_list[i]+'</option>';
					}
					classes_info += '</select> <font class="req">*</font>';					
					$('#span_orgid').html(classes_info);	
					$("#tr_orgidxx").show();
					$("#tr_classinfoid").show();
				}				
			});
        }else if(infovalue=="6"){
			return $.ajax({
				type : "post",
				method : "post",
				dataType : "json",
				url : "<?php echo url('org/getAllLen')?>",
				success : function(data) {
					var classes_info = '<select  id="orgid" name="orgid[]" class="login_input1" onchange="changeEdu(this.value)" multiple="multiple">';
					for (var i in data.len_edu_list) {
						classes_info += '<option value="'+data.len_edu_list[i]['id']+'">'+data.len_edu_list[i]['name']+"【"+data.len_edu_list[i]['org']+"】"+'</option>';
					}
					classes_info += '</select> <font class="req">*</font>';					
					$('#span_orgid').html(classes_info).show().next().show();
					$('#orgid').multiselect().multiselectfilter();
					$("#tr_orgidxx").hide();
					$("#tr_classinfoid").hide();
				}
			});
        }
	}
	<?php if($edit==0 && $level_user==4 ) {?>
		
	$(document).ready(function(){
		//$("#level option:last").remove();
		
	});
	<?php }?>
	<?php if($edit==1 &&  $level!=4 && $user_level==4){?>
	$(document).ready(function(){
		//$("#level option:last").remove();
		
	});
	<?php }?>

	
</script>
<?php if(!empty($suc) && $suc=="suc"){?>
		<script type="text/javascript">alert("保存成功！")</script>
<?php }?>

<?php 
if($edit==2){
        //dump($form);exit;
		$arr=array('form' => $form,);
	}else{
		$arr=array('form' => $form, 'backurl'=>url(''));
	}
    $this->_element('formview_simple', $arr); 
?>

<script type="text/javascript">
$("#tr_fpower").hide();
<?php if( $edit==1 || $edit==0){?>

var xhr = ch_classname($("#level").val());

if(xhr){
	xhr.success(function(){

		<?php if($form['level']->value != 1&&$form['level']->value != 6){ ?>
		$("#orgid").val("<?php echo $form['orgid']->value; ?>");
		<?php } ?>

		<?php if($form['level']->value == 3){//需要获取学习中心并且显示?>
			var xhr2 = changeEdu("<?php echo $form['orgid']->value; ?>");
			if(xhr2){
				xhr2.success(function(){
					$("#orgidxx").val("<?php echo $form['orgidxx']->value; ?>");
				});
			}
        <?php }?>

        <?php if($form['level']->value == 5){//需要获取班级并且显示?>
			var xhr2 = changeEdu("<?php echo $form['orgid']->value; ?>");
			if(xhr2){
                xhr2.success(function(){
                    ch_classinfo("<?php echo $form['orgidxx']->value; ?>",'<?php echo $form["classinfoid"]->value; ?>');
                    $("#orgidxx").val("<?php echo $form['orgidxx']->value; ?>");
				});
            }
        <?php }?>

        <?php if($form['level']->value == 6){//需要重新获取主考院校信息并显示?>
			ch_orginfo('<?php echo $form["orgid"]->value; ?>');
        <?php }?>
        
	});
}
<?php }?>
$('#span_orgidxx').prev().hide();
$('#isprofile').val('1');
</script>
<?php $this->_endblock(); ?>
