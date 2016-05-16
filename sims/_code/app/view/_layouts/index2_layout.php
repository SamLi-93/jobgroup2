<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>吉博控股课程研发中心</title>
<link type="text/css" href="<?=$_BASE_DIR?>index_style/css/common.css"  rel="stylesheet"/>
<link type="text/css" href="<?=$_BASE_DIR?>index_style/css/list.css"  rel="stylesheet"/>
<script src="<?=$_BASE_DIR?>index_style/js/jquery-1.7.1.min.js" type="text/javascript"></script>
</head>
<body>
<!--头部-->
<div class="topdiv">

  <a href="<?=url('index/index')?>"> <img src="<?=$_BASE_DIR?>index_style/images/top_01.jpg"  width="400" height="75" class="fl" style="margin-left:30px;"/></a>
   <div class="topr fr"><span class="lines fr"></span>
    <span class="span1 <?=$id=='course'?'spanc2':'spanc';?> fr" onclick="location='<?=url("admin/login")?>'"></span>
    <span class="lines fr"></span>
    <!-- <span class="span1 <?=$id=='4'?'spanr2':'spanr';?> fr"  onclick="location='<?=url("/clist",array("id"=>'4'))?>'"></span>
    <span class="lines fr"></span>
    <span class="span1 <?=$id=='3'?'spana3_on':'spana3';?>  fr"  onclick="location='<?=url("/clist",array("id"=>'3'))?>'"></span>
    <span class="lines fr"></span> -->
    <span class="span1 <?=$id=='2'?'spana4_on':'spana4';?>  fr"  onclick="location='<?=url("/newlist",array("id"=>'2'))?>'"></span>
    <span class="lines fr"></span>
    <span class="span1 <?=$id=='1'?'spana5_on':'spana5';?>  fr"  onclick="location='<?=url("/courseresource",array("id"=>'1'))?>'"></span>
  </div>   
  <div class="clearn"></div>
</div>
<!--主体部分-->
<div style="height:75px;"></div>
<div class="bodydiv">
  <div class="listdiv">
 

     <?php $this->_block('contents');?>


     <?$this->_endblock();?>

  </div>
</div>
<div class="clearn"></div>
<!--页脚-->
<div class="footer">
  <div class="listdiv">
  <div class="wd70 fl"></div>
    <div class="fl ft_l">
      <div class="fl"><img src="<?=$_BASE_DIR?>index_style/images/index_32.jpg" width="120" height="120" /><span>关注吉博集团微信</span></div>
    </div>
    <div class="fl ft_r"><span class="scolor1">联系我们</span> <span class="scolor2">地址：宁波市高新区江南路1689号浙大软件学院 </span> <span class="scolor3">电话：13429367936 </span> <span class="scolor4">邮箱：zjjobhr@163.com</span> </div>
  </div>
</div>
</body>
</html>






