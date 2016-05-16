<?php $_hidden_elements = array(); ?>
<div class="login_logo"><img src="<?=$_BASE_DIR?>images/main_55.png" style="width:300px;"></div>
<div class="login_form"></div>
<form <?php foreach ($form->attrs() as $attr => $value): $value = h($value); echo "{$attr}=\"{$value}\" "; endforeach; ?>>
	<div class="login_text">
		<div class="login_text_1">用户登录</div>
		<?php
		foreach ($form->elements() as $element):
		    if ($element->_ui == 'hidden'){
		        $_hidden_elements[] = $element;
		        continue;
		    }
		    $id = $element->id;
		?>
			<div class="login_text_2">
				<span class="pass_text"><?php echo h($element->_label); ?>:</span>
			</div>
			<div class="login_text_2">
				<?php echo Q::control($element->_ui, $id, $element->attrs()); ?>&nbsp;<?php if ($element->_req): ?><font class="req">*</font><?php endif; ?>

			</div>
			
		<?php endforeach; ?>
		<div class="err_msg">
			<?php if (!$element->isValid()): ?>
			    <?php echo nl2br(h(implode("，", $element->errorMsg()))); ?>
			<?php endif; ?>
			<!--span class="pass_find"><a href="">找回密码</a></span-->
		</div>
		<div class="login_text_3"><input type="submit" value="" tabindex="3" class="login_button"></div>
		<!--div class="login_text_2"><span class="login_pic"></span><span class="login_left">登录信息遗忘,请致电</span><span class="login_right">0351-7668545</span></div-->
		
		<?php foreach ($_hidden_elements as $element): ?>
			<input type="hidden" name="<?php echo $element->id; ?>" id="<?php echo $element->id; ?>" value="<?php echo h($element->value); ?>" />
		<?php endforeach; ?>
	</div>
</form>
<script type="text/javascript">
document.getElementById('username').focus();
	$("#username").keyup(function(){
        if(event.keyCode == 13){
            $('#form_userlogin').submit();
        }
    });
    $("#password").keyup(function(){
        if(event.keyCode == 13){
            $('#form_userlogin').submit();
        }
    });

</script>