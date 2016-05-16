<?$this->_extends("_layouts/main_layout");?>

<?$this->_block("contents");?>

<form method="post" class="fsimple"/>
<table id="table_auth" class="list_table" width="96%" cellpadding="5" cellspacing="1">
	
	<tr>
		<th width="40%">功能模块</th>
		<th width="15%">查看 <input type="checkbox" id="cha" name="cha" <?php if($user_level==1 && $level==1){?>disabled="disabled"<?php }?>></th>
		<th width="15%">新增  <input type="checkbox" id="zeng" name="zeng" <?php if($user_level==1 && $level==1){?>disabled="disabled"<?php }?>></th>
		<th width="15%">修改 <input type="checkbox" id="gai" name="gai" <?php if($user_level==1 && $level==1){?>disabled="disabled"<?php }?>></th>
		<th width="15%">删除 <input type="checkbox" id="shan" name="shan" <?php if($user_level==1 && $level==1){?>disabled="disabled"<?php }?>></th>
	</tr>
	<?php $myperms = Admin::get_my_perms();?>
<?foreach ($all_perms as $group) {?>
    <?foreach ($group as $k=>$module) {?>
        <?
        if ($k == '_label') continue;
        if (isset($module['baselevel']) && !in_array($admin->level,$module['baselevel'])) continue;
        if (isset($module['levels']) && !in_array($admin->level, $module['levels'])) continue;
        if($user_level==4  && !isset($myperms[$k]) ) continue;
        
        ?>
        <tr>
            <td>
            <?=$module['name']?>
           
            </td>
            <?for ($i=1; $i<=4; $i++) {?>
            	
            <td>
            
            <?php
            if (in_array($i, $module['perms'])) {
            	if($user_level==1 ||!empty($myperms[$k]) ){
            		if($user_level==1 || in_array($i,$myperms[$k])){
                if (!isset($module['subbaselv'][$i]) || in_array($admin->level, $module['subbaselv'][$i])) {
                	
            ?>       
            <input type="checkbox" name="perms[<?=$k?>][]" id="perms[<?=$k?>][]" value="<?=$i?>"<?php if (!empty($auth_perms[$k]) && in_array($i, $auth_perms[$k])) {?> checked="checked"<?php }?> <?php if($user_level==1 && $level==1){?>disabled="disabled"<?php }?>/>
         	
            <?php 		}
            		}
            	}
            }
          
            ?>
           
            
            
            </td>
            <?}?>
        </tr>
    <?}?>
    <tr>
        <td colspan="5">&nbsp;</td>
    </tr>
<?}
?>
</table><br>
<div class="sims_sbumit">
    <?php if($level!=1){?>
    <div class="btn4 mr20" onclick="$('.fsimple').submit();">保存</div>
    <?php }?>
    <div class="btn4 mr20" onclick="history.back()">返回</div>
</div>
<br>
</form>

 <script type="text/javascript">
               $("#cha").click(function(){
            	   $("#table_auth td:nth-child(2) :checkbox").prop("checked",this.checked);
                   });
               $("#zeng").click(function(){
            	   $("#table_auth td:nth-child(3) :checkbox").prop("checked",this.checked);
                   })
            	$("#shan").click(function(){
            		$("#table_auth td:nth-child(5) :checkbox").prop("checked",this.checked);
                	})
            $("#gai").click(function(){
            		$("#table_auth td:nth-child(4) :checkbox").prop("checked",this.checked);
                	});				            	
              	<?php if($level==1){?>
              		$(" :checkbox").prop("checked",true);
              	<?php }?>
 </script>

<?$this->_endblock();?>
