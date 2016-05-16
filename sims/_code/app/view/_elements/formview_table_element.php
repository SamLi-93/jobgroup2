<?php $_hidden_elements = array(); ?>
<div class="sims_create">
<form class="fsimple" <?php foreach ($form->attrs() as $attr => $value): $value = h($value); echo "{$attr}=\"{$value}\" "; endforeach; ?>>

		<table class="form_table" width="96%" cellpadding="0" cellspacing="0">
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
					<span ><?php echo h($element->_label); ?>：</span><span id="span_<?php echo $id;?>"><?php echo trim(Q::control($element->_ui, $id, $element->attrs()));?><?php if ($element->_req):?>&nbsp;<font class="req">*</font><?php endif; ?></span>
					<?php if (!$element->isValid()): ?>
				    <br><span class="error"><?php echo nl2br(h(implode("，", $element->errorMsg()))); ?></span>
				    <?php endif; ?>
					</td><?php if($element->_ui=="memo"){?></tr><?php 
					$count==0;}else{$count=1;}
				}else{$count++;?>
				<td>
					<span ><?php echo h($element->_label); ?><?php if(h($element->_label)!="")echo "：";?></span><span id="span_<?php echo $id;?>"><?php echo trim(Q::control($element->_ui, $id, $element->attrs()));?><?php if ($element->_req):?>&nbsp;<font class="req">*</font><?php endif; ?></span>
					<?php if (!$element->isValid()): ?>
				    <br><span class="error"><?php echo nl2br(h(implode("，", $element->errorMsg()))); ?></span>
				    <?php endif; ?>
				</td>
			<?php if($count==3){ $count=0; ?></tr><?php  }else if($count_all==$all){?></tr><?php }}?>
			<?php endforeach;?>
			<tr height="80">
				<td>
                
                <div class="btn4 mr20" onclick="$('.fsimple').submit();">保存</div>
				<?php if (!empty($backurl)){?>
                <div id="back_btn" class="btn4 mr20" onclick="javascript:history.go(-1);">返回</div>
				<?php }?>
                
				</td>
			</tr>
		</table>
		<?php foreach ($_hidden_elements as $element): ?>
		<input type="hidden" name="<?php echo $element->id; ?>" id="<?php echo $element->id; ?>" value="<?php echo h($element->value); ?>" />
		<?php endforeach; ?>
</form>
</div>