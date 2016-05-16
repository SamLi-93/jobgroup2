
<?php $_hidden_elements = array(); ?>

<form class="fsimplex" <?php foreach ($form->attrs() as $attr => $value): $value = h($value); echo "{$attr}=\"{$value}\" "; endforeach; ?>>
	<fieldset class="query_form" style="margin-top:10px;">
		<?php if ($form->_tips): ?>
	    <legend><?php echo h($form->_subject); ?></legend>
	    <?php endif; ?>
		<table class="form_table" width="100%" cellpadding="0" cellspacing="0">
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
			<?php if($count==0){?><tr height="30"><?php } 
				if($element->_ui=="memo" || $element->_nextline){
					?><tr height="30"><td<?php if($element->_ui=="memo"){?> colspan=6<?php }?>>
					<span style="width:75px;"><?php echo h($element->_label); ?>：</span><span id="span_<?php echo $id;?>"><?php echo trim(Q::control($element->_ui, $id, $element->attrs()));?><?php if ($element->_req):?>&nbsp;<font class="req">*</font><?php endif; ?></span>
					<?php if (!$element->isValid()): ?>
				    <br><span class="error"><?php echo nl2br(h(implode("，", $element->errorMsg()))); ?></span>
				    <?php endif; ?>
					</td><?php if($element->_ui=="memo"){?></tr><?php 
					$count==0;}else{$count=1;}
				}else{$count++;?>
				<td>
					<span style="width:75px;"><?php echo h($element->_label); ?><?php if(h($element->_label)!="")echo "：";?></span><span id="span_<?php echo $id;?>"><?php echo trim(Q::control($element->_ui, $id, $element->attrs()));?><?php if ($element->_req):?>&nbsp;<font class="req">*</font><?php endif; ?></span>
					<?php if (!$element->isValid()): ?>
				    <br><span class="error"><?php echo nl2br(h(implode("，", $element->errorMsg()))); ?></span>
				    <?php endif; ?>
				</td>
			<?php if($count==3){ $count=0; ?></tr><?php  }else if($count_all==$all){?></tr><?php }}?>
			<?php endforeach;?>
			<tr height="40">
				<td>
                
                <div class="btn2 fleft center ml20" onclick="$('.fsimplex').submit();"><span class="shadow white">保存</span></div>
				<?php if (!empty($backurl)){?>
                <div class="btn2 fleft center ml20" onclick="javascript:history.go(-1);"><span class="shadow white">返回</span></div>
				<?php }?>
                
				</td>
			</tr>
		</table>
		<?php foreach ($_hidden_elements as $element): ?>
		<input type="hidden" name="<?php echo $element->id; ?>" id="<?php echo $element->id; ?>" value="<?php echo h($element->value); ?>" />
		<?php endforeach; ?>
	</fieldset>
</form>
