<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>管理系统</title>
	<link href="../css/style_mian.css" rel="stylesheet" type="text/css">

    <script type="text/javascript" src="<?=$_BASE_DIR?>js/jquery.min.js"></script>
    <script type="text/javascript" src="<?=$_BASE_DIR?>js/jquery.blockUI.js"></script>
    <script type="text/javascript" src="<?=$_BASE_DIR?>js/jquery-ui.min.js"></script>
    <link href="<?=$_BASE_DIR?>css/block.css" rel="stylesheet" type="text/css" />
    <link href="<?=$_BASE_DIR?>css/jquery-ui-theme.css" rel="stylesheet" type="text/css" />
    <link type="text/css" href="<?=$_BASE_DIR?>js/calendar/skins/default/theme.css" rel="stylesheet"/>
	<script type="text/javascript" src="<?=$_BASE_DIR?>js/calendar/calendar.js"></script>
	<script type="text/javascript" src="<?=$_BASE_DIR?>js/calendar/calendar-setup.js"></script>
    <script type="text/javascript" src="<?=$_BASE_DIR?>js/calendar/lang/calendar-zh-utf8.js"></script>
        
    <script>
        if(navigator.userAgent.indexOf('MSIE 6')>-1){DD_belatedPNG.fix('.OrgStatus');}
		function resizewnd() {
			var b = document.documentElement;
			//$('#main_div,#main').width(b.clientWidth-($("#menu_div").css("display")=="none"?15:205)).height(b.clientHeight-64);
			$('#outer_div').height(b.clientHeight);
			height = document.getElementById("sims_left").offsetHeight;
			$('#main_div,#main').height(height);
		}
		
		$(window).resize(resizewnd);

        function n2(n) {
            return n < 10 ? '0'+n : n;
        }
		
        $(function() {
            //服务器时间
            function show_timer() {
                var d = new Date(now_time++ *1000);
                $('#stimer').text('现在是'+d.getFullYear()+'年'+n2(d.getMonth()+1)+'月'+n2(d.getDate())+'日 '+n2(d.getHours())+':'+n2(d.getMinutes())+':'+n2(d.getSeconds()));
            }
            var now_time = <?php echo $now_time;?>;
            setInterval(show_timer, 1000);
            show_timer();
			
			$('.zmenu').hide();
		});
	</script> 

</head>
<script type="text/javascript">
var timeout         = 500;
var closetimer		= 0;
var ddmenuitem      = 0;
function mopen(id)
{	
	mcancelclosetime();
	if(ddmenuitem) ddmenuitem.style.display = 'none';
	ddmenuitem = document.getElementById(id);
	ddmenuitem.style.display = 'block';
}
function mclose()
{
	if(ddmenuitem) ddmenuitem.style.display = 'none';
}
function mclosetime()
{
	closetimer = window.setTimeout(mclose, timeout);
}
function mcancelclosetime()
{
	if(closetimer)
	{
		window.clearTimeout(closetimer);
		closetimer = null;
	}
}
document.onclick = mclose; 
function leftmeun(id,obj){
	$('.left').hide();
	$('.ltt').attr("class","lttclose center");
	$(obj).attr("class","ltt center");
	$('#leftmeun_'+id).show('slow');
	
}
$(document).ready(function(){

	$(".left li a").click(function(){		
		$(".left li").attr("class","");			
		$(this).parent('li').attr("class","on");		
	});
});
</script>
<body onload="resizewnd()" style="background:none;">
<div id="head" >
<div class="fleft"><img src="<?=$_BASE_DIR?>image/name.png" width="357" height="31" hspace="50" vspace="25" /></div>
<div class="btn1 center fright mt40 mr20"><span class="white shadow"><a href="<?php echo url("admin/logout");?>" target="_self">退 出</a></span></div>
<div class="btn1 center fright mt40 mr10"><span class="white shadow"><a href="<?php echo url("help/show");?>" target="_blank">帮 助</a></span></div>
<div class="btn1 center fright mt40 mr10"><span class="white shadow"><a href="<?=url('index')?>">首 页</a></span></div>
<div class="fright mt40 mr10" id="stimer"></div>
<div class="fright mt40 mr10">欢迎你，<?php print_r($user['name']);?>！</div>
<div class="clear"></div>
</div>
			<div id="main_div" style="width:100%;height:580px;margin: 0 auto;">
                <?php $this->_block('contents'); ?>
                <?php $this->_endblock(); ?>
			</div>
</body>
</html>
