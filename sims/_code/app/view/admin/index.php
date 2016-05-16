<?$this->_extends("_layouts/main_layout");?>

<?$this->_block("contents");?>

<script type="text/javascript">
<!--
function check(){
		return confirm('确定删除该账号吗？');
	
}
//-->
</script>

<div class="sims_sbumit">
			<div id="filter_show">
			<form class="fsimple" action="<?=url('admin/index');?>" method="get" id="form_users" name="form_users">
				<input type="hidden" name="act" value="list_query">
			<table class="searchv_table" style="text-align: center;" cellpadding="0" cellspacing="0">
			<tr  height="30px">
				<td>登录账号：
					<input type="text" class="query_text" id="quser" name="quser" value="<?php if(isset($quser))echo $quser;?>" style="width: 134px;" >
				</td>
				<td>姓名：
					<input type="text" class="query_text" id="qname" name="qname" value="<?php if(isset($qname))echo $qname;?>" style="width: 134px;" >
				</td>
				<td>级别：
					<select class="query_sel" name="qlevel" id="qlevel">
						<?php foreach ($level_list as $key => $val) {?>
							<option value="<?=$key?>" <?php if($qlevel==$key){ ?>selected='selected'<?php } ?>><?=$val?></option>
						<?php }?>
					</select>
				</td>
				<td>所属机构：
					<input type="text" class="query_text" id="qorg" name="qorg" value="<?php if(isset($qorg))echo $qorg;?>" style="width: 134px;" >
				</td>
				<td>是否有效：
					<select class="query_sel" name="qisopen" id="qisopen">
						<option value="">-请选择-</option>
						<option value="1" <?php if(isset($qisopen)&&"1"==$qisopen){ ?>selected='selected'<?} ?>>有效</option>
						<option value="2" <?php if(isset($qisopen)&&"2"==$qisopen){ ?>selected='selected'<?} ?>>无效</option>
					</select>
				</td>
			</tr>
			<tr height="30px">
				<td colspan="4" style="text-align: left;"><nobr>
			    <div class="btn2 fleft center mr20" onclick="$('.fsimple').submit();"><span class="shadow white">查询</span></div>
			    <?php if (hp('admin.2')) {?>
					<div class="btn2  center mr20"  onclick="window.location.href='<?php echo url('admin/create');?>';"><span class="shadow white">添加</span></div>
				<?php }?>
				</td>
			</tr>
		</table>
	</form>
	</div>
</div>

<table class="list_table" width="96%" cellpadding="5" cellspacing="1">
	
	<tr>
		<th width="">序号</th>
		<th width="">登录账号</th>
		<th width="">姓名</th>
		<th width="">性别</th>
		<th width="">级别</th>
		<th width="">所属机构</th>
		<th width="">是否有效</th>
        <?php if (hp('admin.3')||hp('admin.4')) {?>
		<th width="140px" >操作</th>
        <?php }?>
        <?php if (hp('admin.3')) {?>
		<th width="70px">权限</th>
        <?php }?>
	</tr>
	
<?foreach ($list as $i=>$row) {
	$css = $i%2==1 ? 'bgcolor="#f0efef"' : ''  ;
?>
	<tr <?=$css;?>>
		<td><?=$i+$start+1?></td>
		<td><?=$row['username']?></td>
		<td><?=$row['name']?></td>
		<td><?=$row->gender_name?></td>
		<td><?=$row->level_name?></td>
		<td><?=$row->orgname?></td>
		<td><?=$row->valid_name ?></td>
        <?php if (hp('admin.3')||hp('admin.4')) {?>
		<td><?php if (hp('admin.3')) {?><a href="<?=url('admin/edit', array('id'=>$row['id']))?>" style="color:#e7613d;font-size: 14px;" class="edit" title="修改">修改</a><?php }?> <?php if($userlevel==4){?>
      <?php if($row["level"]!=4 ){?><?php if (hp('admin.4')) {?><a href="<?=url('admin/delete', array('id'=>$row['id']))?>" onclick="return check()" style="color:#e7613d;font-size: 14px;" class="delete" title="删除">删除</a><?php }}}else{?>
      	<?php if (hp('admin.4')) {?><a href="<?=url('admin/delete', array('id'=>$row['id']))?>" onclick="return check()" style="color:#e7613d;font-size: 14px;" class="delete" title="删除">删除</a><?php }}?>
      	
    </td>
        <?php }?>
        <td>
        <?php if($userlevel==4){?>
      <?php if($row["level"]!=4 ){?>
		<?php if (hp('admin.3')) {?><a href="<?=url('admin/auth', array('id'=>$row['id']))?>" style="color:#e7613d;font-size: 14px;" class="edit" title="修改">修改</a><?php }?>
		<?php } 
           }else{?>
           	<?php if (hp('admin.3')) {?><a href="<?=url('admin/auth', array('id'=>$row['id']))?>"style="color:#e7613d;font-size: 14px;" class="edit" title="修改">修改</a><?php }?>
           
           <?php }?>
		
	</td>
		
	</tr>
	
<?}?>

</table><br>
<?$this->_control("pagination", "", array('pagination'=>$pager));?>


<?$this->_endblock();?>