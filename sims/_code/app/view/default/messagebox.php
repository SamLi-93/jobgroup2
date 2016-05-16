<?php $this->_extends('_layouts/main_layout');?>

<?php $this->_block('contents'); ?>

<div id="flash_message">
<p>
<?php echo $content;?>
</p>
<p>
  <a href="<?php echo $redirect_url; ?>"></a>
</p>

<script type="text/javascript">
//setTimeout("window.location.href ='<?php echo $redirect_url; ?>';", <?php echo $redirect_delay * 1000; ?>);
</script>


</div>

<?php $this->_endblock(); ?>