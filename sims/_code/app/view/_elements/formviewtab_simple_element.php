<?php $_hidden_elements = array(); ?>

<form class="fsimple" <?php foreach ($form->attrs() as $attr => $value): $value = h($value); echo "{$attr}=\"{$value}\" "; endforeach; ?>>
	<fieldset class="query_form" style="margin-top:10px;">
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
			?>
            <?php if (!$element->_nobrb){?>
			<tr height="30" id="tr_<?php echo $id;?>">
				<td >
            <?php }?>
					<?php if ($element->_label){?><span style="width:75px;"><?php echo h($element->_label); ?>：</span><?php }?>
					<span id="span_<?php echo $id;?>"><?php echo Q::control($element->_ui, $id, $element->attrs()); if($element->_ui!="dropdownlist"){?>&nbsp;<?php }if ($element->_req): ?><font class="req">*</font><?php endif; ?></span>
					<?php if (!$element->isValid()): ?>
				    <span id="<?php echo $id;?>_err" class="error"><?php echo nl2br(h(implode("，", $element->errorMsg()))); ?></span>
				    <?php endif; ?>
            <?php if (!$element->_nobra){?>
				</td>
			</tr>
            <?php }?>
			<?php endforeach;?>
			<tr height="30">
				<td>
                
                <div class="btn2 fleft center ml20" onclick="$('.fsimple').submit();"><span class="shadow white">保存</span></div>
				<?php if (!empty($backurl)){?>
                <div class="btn2 fleft center ml20" onclick="javascript:location='<?=url('user/')?>';"><span class="shadow white">返回</span></div>
				<?php }?>
                
				</td>
			</tr>
		</table>
		<?php foreach ($_hidden_elements as $element): ?>
		<input type="hidden" name="<?php echo $element->id; ?>" id="<?php echo $element->id; ?>" value="<?php echo h($element->value); ?>" />
		<?php endforeach; ?>
	</fieldset>
</form>
