<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>管理系统</title>
<script type="text/javascript" src="<?=$_BASE_DIR?>js/jquery.min.js"></script>
<script type="text/javascript" src="<?=$_BASE_DIR?>js/common.js"></script>
<link href="<?=$_BASE_DIR?>css/pagination.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?=$_BASE_DIR?>js/jquery-ui.min.js"></script>
<link href="<?=$_BASE_DIR?>css/style.css" rel="stylesheet" type="text/css" />

<link href="<?=$_BASE_DIR?>css/sims.css" rel="stylesheet" type="text/css">
<!--日历-->
<link href="<?=$_BASE_DIR?>js/DatePicker/skin/WdatePicker.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="<?=$_BASE_DIR?>js/DatePicker/WdatePicker.js"></script>

</head>

<script>
top.$('body').add('body', document).ajaxStart(function() {
	top.$('#ajaxmask').show();
}).ajaxStop(function() {
	top.$('#ajaxmask').hide();
});
</script>
<body style="background-position: -200px; margin:0px; padding-left:20px;">


<div class="border3">
<?php 
$i=0;
foreach ($subject as $k=>$v){ 
$i++;
?>
<div id="<?php echo $v['id'];?>" <?php if($v['flag']=="T"){echo "class=\"fleft cbg3 center line30\""; }else{ echo "class=\"fleft cbg4 center line30\"";} echo $v['other_parm']; ?>>
<?php echo $v['name']; ?>			
</div>
<?php }?>
</div>

<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" style="background:#F8F9F9;" >
    <tr>
        <td colspan="4" >
            <?$this->_block('contents');?><?$this->_endblock();?>
        </td>
    </tr>
</table>
<!--table width="100%" border="0" alceign="nter" cellpadding="0" cellspacing="0" style="background:#fff;" >
    
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
</body>
</html>
