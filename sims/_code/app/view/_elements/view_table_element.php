<style type="text/css">
.justify{
text-align:justify;
text-justify:distribute-all-lines;
}
span.cn:after {
content: " ______________________________________________________________________________________________________________________________";
}
</style>
<?php $_hidden_elements = array(); ?>
	<table class="form_table" width="100%" cellpadding="0" cellspacing="0">
        <?php
        $count=0;$count_all=0;$all=count($view->form);
        foreach ($view->form as $id=>$element):
            $count_all++;
            if ($element['_ui'] == 'hidden')
            {
                $_hidden_elements[] = $element;
                continue;
            }
        ?>
        <?php if($count==0){?><tr height="30"><?php } 
            if($element['_ui']=="memo"){
                ?><tr height="30"><td colspan=6>
                <span><?php echo h($element['_label']); ?>：</span><span id="span_<?php echo $id;?>"><?php echo h($view->attrs[$id]); ?></span>
                </td></tr><?php 
                $count==0;
            }else{$count++;?>
            <td>
                <span　class="justify cn"><?php echo h($element['_label']); ?>：</span><span id="span_<?php echo $id;?>"><?php echo h($view->attrs[$id]); ?></span>
            </td>
        <?php if($count==3){ $count=0; ?></tr><?php  }else if($count_all==$all){?></tr><?php }}?>

        <?php endforeach;?>

    </table>
