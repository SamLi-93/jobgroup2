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
<script>
function resizewnd() {
                var b = document.documentElement;
                height = document.getElementById("sims_left").offsetHeight;
                /*alert(b.clientHeight);
                $('#main').height(height);*/
                $('#outer_div').height(b.clientHeight);
            }
            
            $(window).resize(resizewnd);

$('#ajaxmask').ajaxStart(function() {
	$(this).show();
}).ajaxStop(function() {
	$(this).hide();
});

$(document).ready(function()
{
	$('.list_table').tableHover();
    $('.list_table').find('tr').not(':eq(0)').each(
        function(index, element){
            if((index+1)%2){
                $(this).css('bgcolor','#fff');
            }else{
                $(this).addClass('bg3');
            }
        });
});

//发送站内短信
function sendmsg(receiverid,msgtype,title,content){
    $.get('<?=url("message/send")?>',{id:receiverid,type:msgtype,title:title,content:content},function(txt){
        $.blockUI({
            theme: true,
            draggable: true,
            title: '发送消息',
            message: txt,
            themedCSS: {
                width: '400px',
                top: '100px',
                left: '200px',
            }
        });
    });
}

/*//调用方法
sendmsg('',3,'标题','公告内容')
//消息类型
1 => '系统消息',
2 => '通知提醒',
3 => '短消息',
4 => '网站公告',*/

function returnview(id){
    if(!id){
        var id = $('#waitforcheck input:checked').map(function() {
			$(this).attr("checked",false);
		var weight	= $(this).parent().parent().css("font-weight");
			if(weight=="bold"||weight==700)
            return this.value;
        }).get();
    }
	if(id.length<1){
		alert("没有需要标记为已读的消息。");
		return false;
	}
    $.ajax({
        type: "POST",
		dataType : "json",
        url: "<?=url('message/returnview')?>",
        data: {id:id},
        success: function(data){
            if(data.msg){
                if($.isArray(id)){
                $.each(id,function(i,id){
                    $("#id"+id).css('font-weight','normal');
                });
                }else{
                    $("#id"+id).css('font-weight','normal');
                }
				alert("选中部分已更变为已读。");
            }
        }
    });
}
</script>
<style type="text/css">
td.hover, tr.hover
{
    background-color: LemonChiffon;
}
/*----------跟随预览层-------*/
.tip{BORDER:#CCC 1px solid; z-index:10;background-color:#DDF2F2;display:none;LINE-HEIGHT:18px;POSITION:absolute;top:200px;left:200px;}
.tipContent{ background-color:#fff; border:1px solid #E7EAD9; margin:5px; text-align:center; padding:10px; color:#003366}
.tipContent ul{ list-style:none; text-align:left;}
</style>
<body style="background-position: -200px;">
<!--div class="sims_subject"><span>基本信息管理&nbsp;>&nbsp;<?php echo h($subject); ?></span></div-->
<div class="sims_subject"><span><?php echo h($subject); ?></span></div>
<div class="h20"></div>
<?$this->_block('contents');?><?$this->_endblock();?>

<!--table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" style="background:#fff;" >
    
    <tr style=" background:#fff"><td height="40" colspan="4"><table width="100%" height="1" border="0" cellpadding="0" cellspacing="0" bgcolor="#CCCCCC"><tr><td></td></tr></table></td></tr>
    
    <tr style=" background:#fff">
        <td width="2%">&nbsp;</td>
        <td width="91%" class="left_txt" style="font-size:12px;">
            <img src="<?=$_BASE_DIR?>img/icon-mail2.gif" width="16" height="11"> 客户服务邮箱：
            
            <img src="<?=$_BASE_DIR?>img/icon-phone.gif" > 客服热线：0574-27719357 [08:30--17:00]　
            <span style="vertical-align:middle;"><a target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=2579321346&site=qq&menu=yes"><img border="0" src="http://wpa.qq.com/pa?p=2:2579321346:42" alt="点击这里给我发消息" title="点击这里给我发消息"></a></span>
        </td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
</table-->  
        
<iframe id="export_ifr" style="display:none"></iframe>
<div id="ajaxmask"></div>
</body>
</html>
