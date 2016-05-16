<?php $_hidden_elements = array(); ?>
<div class="sims_sbumit">
<form class="fsimple" <?php if(!empty($is_file)&&$is_file=='T'){ echo "enctype=\"multipart/form-data\"";} foreach ($form->attrs() as $attr => $value): $value = h($value); echo "{$attr}=\"{$value}\" "; endforeach;  ?>>
	<!--fieldset class="query_form" style="margin-top:10px;"-->
		<?php if ($form->_tips): ?>
	    <legend><?php echo h($form->_subject); ?></legend>
	    <?php endif; ?>
		<table class="<?php if(isset($ck_clash)){if($ck_clash){echo '';}else{echo 'form_table';}}else{echo 'form_table';}//解决ckedit冲突?>" width="100%" cellpadding="0" cellspacing="0">
			<?php
			$count=0;$count_all=0;$all=count($form->elements());
			foreach ($form->elements() as $element):
				$count_all++;
			    if ($element->_ui == 'hidden')
			    {
			        $_hidden_elements[] = $element;
			        continue;
			    }
			    $id = $element->id;
			    if(!empty($image_show)){
			    	$img_arr = explode('_', $image_show);
			    	if($img_arr[0].'_upload'==$id)echo "<tr height='90'><td><img style='margin-left:70px;' src='".$_BASE_DIR."/img/none_p.jpg' id='".$image_show."' name='".$image_show."' style='width:90px;height:120px;' /><font color='red'>&nbsp;&nbsp; 照片比例3:4 尺寸:120*90像素 大小:小于512KB</font></td></tr>";
				}
			?>
            <?php if (!$element->_nobrb){?>
			<tr height="30" <?=$element->_hide ? 'style="display:none" id="'.$element->_hide.'"' : '';?> id="tr_<?php echo $id;?>">
				<td >
            <?php } ?>
					<?php if ($element->_label){?><span style="width:75px;"><?php echo h($element->_label); ?>：</span><?php }?>
					<span id="span_<?php echo $id;?>"><?php echo Q::control($element->_ui, $id, $element->attrs()); if($element->_ui!="dropdownlist"){?>&nbsp;<?php }if ($element->_req): ?><font class="req">*</font><?php endif; ?></span>
					<?php if (!$element->isValid()): ?>
				    <span class="error"><?php echo nl2br(h(implode("，", $element->errorMsg()))); ?></span>
				    <?php endif; ?>
            <?php if (!$element->_nobra){?>
				</td>
			</tr>
            <?php }?>
			<?php endforeach;?>
			<tr height="80">
				<td>
					<div class="btn4 mr20" onclick="javascript:$('.fsimple').submit();">保存</div>
					<?php if (!empty($backurl)){?>
					<div class="btn4 mr20" onclick="javascript:location='<?=$backurl?>';">返回</div>
					<?php }?>
				</td>
			</tr>
		</table>
		<?php foreach ($_hidden_elements as $element): ?>
		<input type="hidden" name="<?php echo $element->id; ?>" id="<?php echo $element->id; ?>" value="<?php echo h($element->value); ?>" />
		<?php endforeach; ?>
	<!--/fieldset-->
</form>
</div>