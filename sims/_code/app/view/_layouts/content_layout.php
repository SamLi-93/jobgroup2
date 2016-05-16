<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>管理系统</title>
<script type="text/javascript" src="<?=$_BASE_DIR?>js/jquery.min.js"></script>
<script type="text/javascript" src="<?=$_BASE_DIR?>js/common.js"></script>
<script type="text/javascript" src="<?=$_BASE_DIR?>js/jquery-ui.min.js"></script>
<script type="text/javascript" src="<?=$_BASE_DIR?>js/jquery.tablehover.js"></script>
<script type="text/javascript" src="<?=$_BASE_DIR?>js/jquery.blockUI.js"></script>
<link href="<?=$_BASE_DIR?>css/pagination.css" rel="stylesheet" type="text/css" />
<link href="<?=$_BASE_DIR?>css/style.css" rel="stylesheet" type="text/css" />
<link href="<?=$_BASE_DIR?>css/sims.css" rel="stylesheet" type="text/css">
<!--日历-->
<link href="<?=$_BASE_DIR?>js/DatePicker/skin/WdatePicker.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="<?=$_BASE_DIR?>js/DatePicker/WdatePicker.js"></script>
<script type="text/javascript" src="<?=$_BASE_DIR?>js/jquery.PrintArea.js"></script>

<script type="text/javascript" src="<?=$_BASE_DIR?>uploadify/swfobject.js"></script>
<script type="text/javascript" src="<?=$_BASE_DIR?>uploadify/jquery.uploadify.v2.1.4.min.js"></script>
<link href="<?=$_BASE_DIR?>uploadify/uploadify.css" rel="stylesheet" type="text/css" />
<link href="<?=$_BASE_DIR?>css/block.css" rel="stylesheet" type="text/css">
<link href="<?=$_BASE_DIR?>css/jquery-ui-theme.css" rel="stylesheet" type="text/css">

</head>
<script type="text/javascript">
$(function() {
	//var b = document.documentElement;
	//控制左边表格的宽度，适应各个分辨率
	width = document.getElementById("default_main").offsetWidth;
	//console.log(width);
	$('#default_left').width(width-340);
});
function replyquiz(){
	var id = top.$("#reply_id").val();
	var content = top.$("#reply_con").val();
	
	$.ajax({
        type : "post",
        method : "post",
        dataType : "json",
        data:{"id":id,"content":content},
        url : "<?php echo url('default/reply')?>",
        success : function(data) {
            top.$("#reply_con").val('');//清空回复内容
            alert("回复成功！");
            var html_list = "<tr>";
                    html_list += "<th>学生</th>";
                    html_list += "<th>标题</th>";
                    html_list += "<th>回复</th>";
                    html_list += "<th>时间</th>";
                    html_list += "<th width='50px'>操作</th></tr>";
                for(i in data.list){
                    //html_list +="<tr><td>"+data.class_name+"</td>";
                    html_list +="<td>"+data.list[i]['name']+"</td>";
                    html_list +="<td>"+data.list[i]['title']+"</td>";
                    html_list +="<td>"+data.list[i]['answer_num']+"</td>";
                    html_list +="<td>"+data.list[i]['time']+"</td>";
                    html_list +="<td onclick='getquiz("+data.list['id']+")'><div class='default_submit' style='margin-right:0px;'>答疑</div></td></tr>";
                    //alert(html_list);return;
                }

                $('#show3').html(html_list);
                top.$.unblockUI();
        }
    });
}
function getquiz(id) {
	top.$.blockUI({
        theme: true,
        title: '答疑回复',
        draggable: true,
        message: $('#stu_quiz').html(),
        themedCSS:{
            width: '500px',
            top: '25%',
            left: '35%'
        }
    });
    $.ajax({
        type : "post",
        method : "post",
        dataType : "json",
        data:{"id":id},
        url : "<?php echo url('default/getquiz')?>",
        success : function(data) {
            top.$("#quiz_title").html(data.title);
            top.$("#quiz_content").html(data.content);
            top.$("#quiz_sender").html(data.sender);
            top.$("#reply_id").val(id);//答疑的id
        }
    });

}
function showpanel(){
    top.$.blockUI({
        theme: true,
        title: '选择学生',
        draggable: true,
        message: $('#stu_panel').html(),
        themedCSS:{
            width: '780px',
            top: '100px',
            left: '25%'
        }
    });
}
function showmsg(id){
    top.$.blockUI({
        theme: true,
        title: '新闻公告',
        draggable: true,
        message: $('#stu_msg').html(),
        themedCSS:{
            width: '500px',
            top: '25%',
            left: '35%'
        }
    });
     $.ajax({
        type : "post",
        method : "post",
        dataType : "json",
        data:{"id":id},
        url : "<?php echo url('default/getmsg')?>",
        success : function(data) {
            top.$("#msg_title").html(data.title);
            top.$("#msg_content").html(data.content);
            if(!data.sender && typeof(data.sender)!="undefined" && data.sender!=0){
            	top.$("#msg_sender").html(data.sender);
	        }else{
	        	top.$("#msg_sender").html("学生");
	        }
        }
    });
}
/**
 * list students
 * 列出学生 
 */
function submit_check(){
    var u_class     = top.$("#u_class option:selected").val();
    var u_discipline= top.$("#u_discipline option:selected").val();
    var u_userid    = top.$("#u_userid").val();
    var u_name      = top.$("#u_name").val();
    if(u_class == '' && u_discipline == '' && u_userid == '' && u_name == ''){
        alert('请至少选择一个过滤条件！');
        return false;
    }
    $.ajax({
        type : "post",
        method : "post",
        dataType : "json",
        data:{
              "clas":u_class,
              "discipline":u_discipline,
              "userid":u_userid,
              "name":u_name},
        url : "<?php echo url('message/getstu')?>",
        success : function(data) {
            var td = "";
            for(i in data.name){
                td += "<tr><td style='width:40px;'><input type='checkbox' name='rname[]' value='"+$.trim(data.idst[i])+"'>"
                    +"<input type='hidden' id='"+$.trim(data.idst[i])+"' value='"+data.name[i]+"'>"
                    +"<input type='hidden' id='"+$.trim(data.idst[i])+"_c' value='"+data.clas_id[i]+"'>"
                     +"<td style='width:162px;'>"+data.disc[i]+"</td>"
                     +"<td style='width:217px;'>"+data.clas[i]+"</td>"
                     +"<td style='width:165px;'>"+data.userid[i]+"</td>"
                     +"<td >"+data.name[i]+"</td></tr>";
            }
            top.$("#stu_list").html(td);
        }
    });
}

/**
 * reset conditions 
 * 重置查询条件
 */
function reset_check(){
    top.$("#u_class").val("");
    top.$("#u_discipline").val("");
    top.$("#u_userid").val("");
    top.$("#u_name").val("");
    top.$("#stu_list").html("");
}

function checktopall(p, name) {
    top.$('#'+p+' :checkbox[name="'+name+'[]"]').prop('checked', this.checked);
}
/**
 * select student
 * 选择学生
 */
function setvalue(){
    var ids = "";
    var ids = top.$("input[name='rname[]']:checked").map(function() {
        return $.trim(this.value);
    }).get();
    if(ids.length>0){
    	var names = "";//名字显示出来看的
    	var id_arr = "";//id取出来操作的
        $.each(ids, function(i,id) {    //不能添加自己
            if ($('#bit'+id).length) return;
            //$('#man_show').append('<li id="bit'+id+'" class="user_select_li">'+top.$('#'+id).val()+'<span class="colose_li" onclick="del_user_li(\'bit'+id+'\')"></span><input type="hidden" value="'+id+'" name="receiver[]" class="checked_user"></li>');
        	if(i==(ids.length-1)){
        		names = names+top.$("#"+id).val();
        	}else{
        		names = names+top.$("#"+id).val()+',';
        	}
        });
        name_arr =  $('#name_arr').val();
        id_arr = $('#id_arr').val();
        if(name_arr.length>0){
            name_arr += ','+names;
            $('#name_arr').val(name_arr);
            id_arr += ','+ids;
            $('#id_arr').val(id_arr);
        }else{
            $('#name_arr').val(names);
            $('#id_arr').val(ids);
        }
        top.$.unblockUI();
    }else{
        alert("您还未选择学生，请选择！");
    }
}

function show_more(url){
	location.href=url;
}


function send () {
	var content = $('#send_con').val();
	var id_arr = $('#id_arr').val();
	if(content.length<=10){
		alert("您填写的内容过少!");
		return false;
	}
	if(id_arr.length ==0){
		alert("您还未选收信人!");
		return false;
	}
        
	$.ajax({
        type : "post",
        method : "post",
        dataType : "json",
        data:{
              "id_arr":id_arr,
              "content":content},
        url : "<?php echo url('default/send')?>",
        success : function(data) {
            alert("消息推送成功！");
            $('#name_arr').val('');
    		$('#id_arr').val('');
    		$('#send_con').val('');
        }
	});
}
function getclass(id){
	$.ajax({
	        type : "post",
	        method : "post",
	        dataType : "json",
	        data:{"id":id},
	        url : "<?php echo url('default/getclass')?>",
	        success : function(data) {
        		//var html_list = "<tr><th>班级</th>";
        		var html_list = "<tr>";
        			html_list += "<th>学生</th>";
        			html_list += "<th>课程</th>";
        			html_list += "<th>进度</th>";
        			html_list += "<th>作业</th>";
        			html_list += "<th>综合成绩</th>";
        			html_list += "<th>状态</th>";
        			html_list += "<th width='50px'>操作</th></tr>";
        		for(i in data.list){
        			if(data.score==30){
        				status="<font style='color:red'>已完成</font>";
        			}
        			if(data.score==0){
        				status="<font style='color:#5a8ee0'>未开始</font>";
        			}else{
        				status="<font style='color:#666666'>未完成</font>";
        			}
                	//html_list +="<tr><td>"+data.class_name+"</td>";
                	html_list +="<td>"+data.list[i]['user_name']+"</td>";
            		html_list +="<td>"+data.list[i]['course_name']+"</td>";
            		html_list +="<td>"+data.list[i]['finish']+"</td>";
            		html_list +="<td>"+data.list[i]['work']+"</td>";
            		html_list +="<td>"+data.list[i]['score']+"</td>";
            		html_list +="<td>"+status+"</td>";
            		html_list +="<td onclick='returnclass()'><div class='default_submit' style='margin-right:0px;'>返回</div></td></tr>";
            		//alert(html_list);return;
            	}
        		$("#show2").css("display","");

        		$('#show2').html(html_list);
        		//alert(html_list);
        		$("#show1").css("display","none");
	        }
    	});
	
}

function returnclass(){
	$("#show1").css("display","");
    $("#show2").css("display","none");
}
/**
 * [getLearprocessTable 查看学生学习过程]
 * @param  {[type]} info_id [相关单位的ID标识]
 * @param  {[type]} type    [3.查看班级情况 5.查看学生情况 其他.查看学习中心情况]
 * @return {[type]}         [description]
 */
function getLearprocessTable(info_id,type){
    $("#learprocess_tb").html("<tr><td><div style='position: relative;height:275px;overflow: hidden;'><img src='<?=$_BASE_DIR?>images/loading.gif' style='float: left;margin-left: 200px; '><span style='position: absolute; top: 135px; left: 440px; color: #5a8ee0;'>数据加载中...</span></div></td></tr> ");
    $.ajax({
        type : "post",
        method : "post",
        dataType : "json",
        data:{"info_id":info_id,"type":type},
        url : "<?php echo url('default/getLearprocessTable')?>",
        success : function(data) {
            var table_txt = "";
            var back_text = "";
            if(type==3){//返回学习中心
                if(data.userlevel!=3||data.userlevel!=5){
                    back_text = "<div style='background:#E4393C;position:absolute;left:5px;top:3px;' class='default_button' onclick='getLearprocessTable("+data.pid+",1)'>返回</div>";
                }
                table_txt = "<tr><th style='position:relative;width:200px;'>"+back_text+"班级名称</th><th>课程</th><th>进度</th><th>作业</th><th>综合成绩</th><th>操作</th></tr>";
                for(var i=0;i<(data.list.length-1);i++){
                    var info = data.list[i];
                    table_txt = table_txt + "<tr><td>"+info['info_name']+"</td><td>"+info['cname']+"</td><td>"+info['finish']+"</td><td>"+info['work']+"</td><td>"+info['score']+"</td><td><div style='width:70px;margin:0 auto;' class='default_button' onclick='getLearprocessTable("+info['info_id']+",5)'>查看班级</div></td></tr>";
                }
            }else if(type==5){//返回班级
                if(data.userlevel!=5){
                    back_text = "<div style='background:#E4393C;position:absolute;left:5px;top:3px;' class='default_button' onclick='getLearprocessTable("+data.pid+",3)'>返回</div>";
                }
                table_txt = "<tr><th style='position:relative;'>"+back_text+"学生姓名</th><th>课程</th><th>进度</th><th>作业</th><th>综合成绩</th></tr>";
                for(var i=0;i<(data.list.length-1);i++){
                    var info = data.list[i];
                    table_txt = table_txt + "<tr><td>"+info['info_name']+"</td><td>"+info['cname']+"</td><td>"+info['finish']+"</td><td>"+info['work']+"</td><td>"+info['score']+"</td></tr>";
                }
            }else{
                table_txt = "<tr><th>学习中心</th><th>课程</th><th>进度</th><th>作业</th><th>综合成绩</th><th>操作</th></tr>";
                for(var i=0;i<(data.list.length-1);i++){
                    var info = data.list[i];
                    table_txt = table_txt + "<tr><td>"+info['info_name']+"</td><td>"+info['cname']+"</td><td>"+info['finish']+"</td><td>"+info['work']+"</td><td>"+info['score']+"</td><td><div style='width:70px;margin:0 auto;' class='default_button' onclick='getLearprocessTable("+info['info_id']+",3)'>学习中心</div></td></tr>";
                }
            }
             $("#learprocess_tb").html(table_txt);
        }
    });
}
</script>
<body>
<div class="sims_default_main" id="default_main">
	<div style="width:100%">
		<!-- <div class="default_tip">
			<img src="<?=$_BASE_DIR?>images/default_2.png">
			<span class="main_more" onclick="show_more('<?=url('learnprocess')?>')">更多&gt;&gt;</span>
		</div> -->
		<!-- 学习过程排名开始 -->
		<!-- <span style="overflow-y:scroll; overflow-x:hidden;table-layout: fixed;word-wrap:break-word;word-break:break-all;border:1px solid #cccccc;height:285px;display:inline-block;width: 100%;">
    		<table cellpadding="5" cellspacing="0" id="learprocess_tb">
                <tr><td>
                    <div style='position: relative;height:275px;overflow: hidden;'>
                        <img src="<?=$_BASE_DIR?>images/loading.gif"/ style="float: left;margin-left: 200px; ">
                        <span style="position: absolute; top: 135px; left: 440px; color: #5a8ee0;">数据加载中...</span>
                    </div>
                </td></tr>      
            </table>
		</span> -->
		<!-- 学习过程排名结束 -->
        <div class="welcome">
            <h5>浙江医药高等专科学校</h5>
            <span>中药调剂技术课程掌上学习端</span>
        </div>
	</div>

</div>


<script type="text/javascript">
    getLearprocessTable(<?=$userinfo['oid']?>,<?=$userinfo['level']?>);
</script>
</body>
</html>