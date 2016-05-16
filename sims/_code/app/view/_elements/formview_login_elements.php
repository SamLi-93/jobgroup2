<?php $_hidden_elements = array(); ?>

<form <?php foreach ($form->attrs() as $attr => $value): $value = h($value); echo "{$attr}=\"{$value}\" "; endforeach; ?>>
	
	<table cellSpacing="0" cellPadding="0" width="100%" border="0" height="103" id="table212">
		<?php
		foreach ($form->elements() as $element):
		    if ($element->_ui == 'hidden'){
		        $_hidden_elements[] = $element;
		        continue;
		    }
		    $id = $element->id;
		?>
		<tr height="45">
			<td width="13%" class="top_hui_text"><span class="login_txt"><?php echo h($element->_label); ?>:</span></td>
			<td colspan="2" class="top_hui_text">
				<?php echo Q::control($element->_ui, $id, $element->attrs()); ?>&nbsp;<?php if ($element->_req): ?><font class="req">*</font><?php endif; ?>
				<?php if (!$element->isValid()): ?>
			    	<span class="error"><?php echo nl2br(h(implode("，", $element->errorMsg()))); ?></span>
			    <?php endif; ?>
			</td>
		</tr>
		<?php endforeach; ?>
		<tr height="33">
			<td width="13%" height="35" class="top_hui_text">&nbsp;</td>
			<td colspan="2" class="top_hui_text" style="text-align:left;"><input type="submit" value="" class="login_button"></td>
		</tr>
	</table>
	<?php foreach ($_hidden_elements as $element): ?>
		<input type="hidden" name="<?php echo $element->id; ?>" id="<?php echo $element->id; ?>" value="<?php echo h($element->value); ?>" />
	<?php endforeach; ?>
</form>
<script>
	document.getElementById('username').focus();
</script>