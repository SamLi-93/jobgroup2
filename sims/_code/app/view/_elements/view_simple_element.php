<?php $_hidden_elements = array(); ?>

    <table class="form_table" width="50%" cellpadding="0" cellspacing="0">
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
        <tr height="30">
            <td>
                <span><?php echo h($element['_label']); ?>：</span><span id="span_<?php echo $id;?>"><?php echo h($view->attrs[$id]); ?></span>
            </td>
        </tr>
        <?php endforeach;?>

    </table>
