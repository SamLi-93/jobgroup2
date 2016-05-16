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
        <!--[if IE 6]>
            <script type="text/javascript" src="<?=$_BASE_DIR?>js/DD_belatedPNG.js"></script>
        <![endif]-->
        <script>
            if(navigator.userAgent.indexOf('MSIE 6')>-1){DD_belatedPNG.fix('.OrgStatus');}
			function resizewnd() {
				var b = document.documentElement;
				$('#main_div,#main').width(b.clientWidth-($("#menu_div").css("display")=="none"?15:205)).height(b.clientHeight-64);
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
				$('.zmenu').hide();
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
});
</script>
<body onload="resizewnd()">
<div id="head" >
<div class="fleft">

<?php if($show=="T"&& file_exists(Q::ini("app_config/ROOT_DIR").preg_replace('/.*upload/', '/upload', $logoimg)) ){  ?>
        <!--<img src="<?php echo $logoimg;?>" class="OrgStatus" style="heigth:78px;max_width:391px;"/>
 -->       <?php }else{ ?>
            <img src="<?=$_BASE_DIR?>image/name.png" width="357" height="31" hspace="50" vspace="25" />
        <?php }
            
        ?>
    </div>
<div class="btn1 center fright mt40 mr20"><span class="white shadow"><a href="<?php echo url("admin/logout");?>" target="_self">退 出</a></span></div>
<div class="btn1 center fright mt40 mr10"><span class="white shadow"><a href="<?php echo url("help/show");?>" target="_blank">帮 助</a></span></div>
<div class="btn1 center fright mt40 mr10"><span class="white shadow"><a href="<?=url('index')?>">首 页</a></span></div>
<div class="fright mt40 mr10" id="stimer"></div>
<div class="fright mt40 mr10">欢迎你，<?php print_r($user['name']);?>！</div>
<div class="clear"></div>
</div>

<div id="menu">
	<?
    $i=0;
	$all_perms_1 = $all_perms;
	$all_perms_2 = array();
	$group_maps = array();
	$gk2 = 0;
    foreach ($all_perms_1 as $gk=>$group) {
		if ($level != 1 && $group['_label'] == '学费管理') continue;
        if (!Admin::has_mg_perm($gk)) continue;
		if (empty($group['_group'])) {
			$all_perms_2[$gk2++] = array('_label'=>$group['_label'], '_sub'=>array($group));
		} else {
			if (!isset($group_maps[$group['_group']])) {
				$group_t = array('_label'=>$group['_group'], '_sub'=>array($group));
				$all_perms_2[$gk2] = $group_t;
				$group_maps[$group['_group']] = $gk2++;
			} else {
				$gk2o = $group_maps[$group['_group']];
				$all_perms_2[$gk2o]['_sub'][] = $group;
			}
		}
	}
	krsort($all_perms_2);
	$j = 0;
    foreach ($all_perms_2 as $gk=>$group_t) {
		$i++;
    ?>
    <div class="menubt">
    <span class="shadow"><a href="javascript:void(0)" onmouseover="mopen('m<?=$i;?>')" onmouseout="mclosetime()"><?=$group_t['_label']?></a></span>
        <ul class="zm zmenu"  id="m<?=$i;?>" style="display:none;" onmouseover="mcancelclosetime()" onmouseout="mclosetime()">
        <?
		$show_sub_label = count($group_t['_sub']) > 1;
		foreach ($group_t['_sub'] as $group) {
			if ($show_sub_label) {
		?>
		<li class="mtitle"><a href="javascript:;"><?=$group['_label']?></a></li>
		<?php
			}
        foreach ($group as $k=>$module) { 
            if (substr($k, 0, 1) == '_') continue;
            if ($level != 1 && !hp($k)) continue;
            if (empty($module['_sub']) && $module['url']=="help"){
                if($level==1){
        ?>
        <li><a href="<?=url($module['url'])?>" target="main"><?=$module['name']?></a></li>
        <?
                }
            }else{

				if (empty($module['_sub'])) {
        ?>
        <li><a href="<?=url($module['url'])?>" target="main"><?=$module['name']?></a></li>
        <?
				} else {
		?>
        <li onmouseover="showsubmeun(<?=++$j;?>,this)" onmouseout="hidesubmeun(this)"><span class="hassub"><?=$module['_label']?></span>
			<ul class="submns" id="submeun_<?=$j;?>" style="display:none">
			<?
			foreach ($module['_sub'] as $k2=>$module2) {
				if (substr($k2, 0, 1) == '_') continue;
				if ($level != 1 && !hp($k2)) continue;
			?>
				<li><a href="<?=url($module2['url'])?>" target="main"><?=$module2['name']?></a></li>
			<? } ?>
			</ul>
		</li>
		<?
				}
            }
		}
		}
        ?>
        </ul>
    </div>
    <div class="menudv fright"></div>
    <?
    }
    ?>
</div>

<div id="lta">
<?
$i=0;
$j=0;
foreach ($all_perms as $gk=>$group) {
	if ($level != 1  && $level != 6  && $group['_label'] == '学费管理') continue;
	if (!Admin::has_mg_perm($gk)) continue;
	$divclass = $gk ? 'lttclose' : 'ltt';
	$i++;
?>
<div class="<?=$divclass?> center" style="cursor:pointer" onclick="leftmeun(<?=$i;?>,this)" ><span class="white shadow line30"><a href="javascript:void(0)"><?=$group['_label']?></a></span></div>
<ul class="left" id="leftmeun_<?=$i;?>" <?=$gk ? 'style="display:none"' : '';?> >
<!--<ul class="left"  >-->
        <?
        foreach ($group as $k=>$module) { 
			if (substr($k, 0, 1) == '_') continue;
            if ($level != 1 && !hp($k)) continue;
            if (empty($module['_sub']) && $module['url']=="help"){
                if($level==1){
        ?>
        <li><a href="<?=url($module['url'])?>" target="main"><?=$module['name']?></a></li>
        <?
                }
            }else{

				if (empty($module['_sub'])) {
        ?>
        <li><a href="<?=url($module['url'])?>" 
            <?php if($module['name'] == '站内消息' && $mymsg){echo "title='你有".$mymsg."条未读站内消息'";}
                //if($module['name'] == '系统公告' && $sysmsg){echo "title='你有".$sysmsg."条未读系统公告'";}
                if($module['name'] == '通知提醒' && $notif){echo "title='你有".$notif."条未读通知提醒'";}
            ?> target="main">
            <?=$module['name']?>
            <?php if($module['name'] == '站内消息' && $mymsg){echo '<font style="color:red;font-weight:bold;font-size:12px">['.$mymsg.']</font>';}
                //if($module['name'] == '系统公告' && $sysmsg){echo '<font style="color:red;font-weight:bold;font-size:20px">'.$sysmsg.'</font>';}
                 if($module['name'] == '通知提醒' && $notif){echo '<font style="color:red;font-weight:bold;font-size:12px">['.$notif.']</font>';}
            ?>
            </a></li>
        <?
				} else {
        ?>
        <li class="subm" onmouseover="showleftsubmeun(<?=++$j;?>,this)" onmouseout="hideleftsubmeun(this)"><a href="javascript:;"><?=$module['_label']?></a>
			<ul class="lefts" id="leftsubmeun_<?=$j;?>" style="display:none;">
			<?
			foreach ($module['_sub'] as $k2=>$module2) {
				if (substr($k2, 0, 1) == '_') continue;
				if ($level != 1 && !hp($k2)) continue;
			?>
				<li><a href="<?=url($module2['url'])?>" target="main"><?=$module2['name']?></a></li>
			<? } ?>
			</ul>
		</li>
        <?
				}
            }
		}
        ?>
</ul>
<?
}
?>
</div>


<div class="clear"></div>
<div class="bg0 main  center">
<iframe src="<?php echo url("default/welcome");?>" style="width:95%;height: auto;" scrolling="auto" frameborder="0" id="main" name="main"></iframe> 
</div>
<div id="ajaxmask"></div>


<div class="main"></div>

<div class="clear"></div>

        
</body>
<script>resizewnd();</script>
</html>
