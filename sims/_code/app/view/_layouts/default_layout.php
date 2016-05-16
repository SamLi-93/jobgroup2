<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<title>管理系统</title>
        <script type="text/javascript" src="<?=$_BASE_DIR?>js/jquery.min.js"></script>
        <script type="text/javascript" src="<?=$_BASE_DIR?>js/jquery.blockUI.js"></script>
        <script type="text/javascript" src="<?=$_BASE_DIR?>js/jquery-ui.min.js"></script>
        <link href="<?=$_BASE_DIR?>css/block.css" rel="stylesheet" type="text/css" />
        <link href="<?=$_BASE_DIR?>css/sims.css" rel="stylesheet" type="text/css" />
        <link href="<?=$_BASE_DIR?>css/jquery-ui-theme.css" rel="stylesheet" type="text/css" />
        <link type="text/css" href="<?=$_BASE_DIR?>js/calendar/skins/default/theme.css" rel="stylesheet"/>
		<script type="text/javascript" src="<?=$_BASE_DIR?>js/calendar/calendar.js"></script>
		<script type="text/javascript" src="<?=$_BASE_DIR?>js/calendar/calendar-setup.js"></script>
        <script type="text/javascript" src="<?=$_BASE_DIR?>js/calendar/lang/calendar-zh-utf8.js"></script>
        <!--[if IE 6]>
            <script type="text/javascript" src="<?=$_BASE_DIR?>js/DD_belatedPNG.js"></script>
        <![endif]-->
        <script>
            if(navigator.userAgent.indexOf('MSIE 6')>-1){DD_belatedPNG.fix('.OrgStatus');}
			function resizewnd() {
				var b = document.documentElement;
				height = document.getElementById("sims_left").offsetHeight;
				var setheight = height > 700 ? height:700;
				$('#main').height(setheight);
				$('#sims_right').height(setheight);
				$('#sims_right').width(b.clientWidth-250);
				$('#main').width(b.clientWidth-250);
				//console.log(b.clientWidth);
				$('#outer_div').height(b.clientHeight);
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
				//$('.zmenu').hide();
			});

		</script> 

<style type="text/css">
.zm li.mtitle {background:#CCC;}
#menu li.mtitle a {color:#FFF;}
#menu li.mtitle a:hover {color:#FFF;text-decoration:none;}
</style>

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
	$('.left').hide('normal','swing');
	//$('.ltt').attr("class","lttclose center");
	//$(obj).attr("class","ltt center");
	$('#leftmeun_'+id).show('normal','swing');
	
}
/*
function leftsubmeun(id,obj){
	$(obj).toggleClass("ltt");
	$('#leftsubmeun_'+id).toggle('normal','swing');
	
}
*/
function showleftsubmeun(id,obj) {
	$(obj).find('>.lefts').show();
}
function hideleftsubmeun(obj) {
	$(obj).find('>.lefts').hide();
}
function showsubmeun(id,obj) {
	$(obj).find('>.submns').show();
}
function hidesubmeun(obj) {
	$(obj).find('>.submns').hide();
}

$(document).ready(function(){
	$(".left>li>a").not('.subm>a').click(function(){
		$(".left>li").removeClass('on');
		$(this).parent('li').addClass("on");		
	});
	$(".lefts>li>a").click(function(){
		$(".left li").removeClass('on');
		$(this).parent('li').addClass("on").parent().closest('li').addClass('on');
		$('#lta .lefts').hide();
	});
	//左侧栏目点击效果
	$('.sims_list').click(
		function(){
			index = $(".sims_left dt").index(this);
			
			if($(".left_cons").eq(index).css("display") == "none"){
				$(".left_cons").slideUp("slow").eq(index).slideDown("slow");
	        	$(".sims_list_on").removeClass("sims_list_on").addClass("sims_list");
	        	$(this).removeClass("sims_list").addClass("sims_list_on");
	        	
			}
		}
	);
	$('.left_con').click(
		function(){
			$('.left_con_on').removeClass("left_con_on").addClass("left_con");;
			$(this).removeClass("left_con").addClass("left_con_on");
			height = document.getElementById("sims_left").offsetHeight;
			var setheight = height > 800 ? height:800;
			//console.log(height);
			$('#main').height(setheight);
			$('#sims_right').height(setheight);
		}
	);
});
</script>
<body onload="resizewnd()">
<div class="sims_top">
	<span class="sims_top1"></span>
	<span class="sims_top3" style="width:30px;">欢迎 </span>
	<span class="sims_top2"><?php print_r($user['name']);?></span>
	<span class="sims_top3"> 访问微信抽奖平台！ </span>
	<span class="sims_top4"></span>
	<span class="sims_top5" id="stimer"></span>
</div>
<div class="sims_tip">
	<div class="sims_tip1">
		<?php if($show=="T"&& file_exists(Q::ini("app_config/ROOT_DIR").preg_replace('/.*upload/', '/upload', $logoimg)) ){  ?>
        <!--<img src="<?php echo $logoimg;?>" class="OrgStatus" style="heigth:78px;max_width:391px;"/>-->       
        <?php }else{ ?>
            <img src="<?=$_BASE_DIR?>images/main_5.png"/>
        <?php }
            
        ?>
        <img src="<?=$_BASE_DIR?>images/main_5.png" style="cursor: pointer;    width: 150px;" onclick="javascript:location.href='<?=url('default/main')?>'"/>
		</div>
	<div class="sims_tip2"><img src="<?=$_BASE_DIR?>images/main_6.png" border="0" usemap="#Map">
      <map name="Map" id="Map">
        <area shape="rect" coords="22,42,64,86" href="<?=url('default/index')?>" target="_blank"/>
        <area shape="rect" coords="92,44,135,88" href="<?php echo url("admin/logout");?>" target="_self" />
      </map>
	</div>
</div>	

<div class="h20"></div>

<div class="sims_left" id="sims_left">
	<?
	$i=0;
	$j=0;
	foreach ($all_perms as $gk=>$group) {
		if ($level != 1  && $level != 6  && $group['_label'] == '学费管理') continue;
		if (!Admin::has_mg_perm($gk)) continue;
		$divclass = $gk ? 'lttclose' : 'ltt';
		$i++;
	?>
	<dt class="sims_list"><?=$group['_label']?></dt>
	<dd class="left_cons" style="display:none;">
		<?
        foreach ($group as $k=>$module) { 
			if (substr($k, 0, 1) == '_') continue;
            if ($level != 1 && !hp($k)) continue;
            if (empty($module['_sub']) && $module['url']=="help"){
                if($level==1){
        ?>
		<a href="<?=url($module['url'])?>" class="left_con" target="main"><?=$module['name']?></a>
				<?}
			}else{
				if (empty($module['_sub'])) {?>
				<a href="<?=url($module['url'])?>" class="left_con" target="main"><?=$module['name']?></a>
				<?}else{?>
		        <dt class="sims_list"><?=$group['_label']?></dt>
					<?
					foreach ($module['_sub'] as $k2=>$module2) {
						if (substr($k2, 0, 1) == '_') continue;
						if ($level != 1 && !hp($k2)) continue;
					?>
						<dd><a href="<?=url($module2['url'])?>" target="main"><?=$module2['name']?></a></dd>
					<? } ?>
		        <?
				}
			}
		}?>
	</dd>
	<?}?>
</div>


<div class="sims_right" id="sims_right">
	<iframe src="<?php echo url("activity/");?>" style="width:1191px;" scrolling="auto" frameborder="0" id="main" name="main"></iframe> 
</div>

<div class=" clear" style="height:40px;"></div>
<div id="ajaxmask"></div>


<div class="main"></div>

<div class="clear"></div>

        
</body>
<script>resizewnd();</script>
</html>
