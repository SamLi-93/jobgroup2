<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<title>管理系统</title>
<link href="<?=$_BASE_DIR?>css/login.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="<?=$_BASE_DIR?>js/jquery.min.js"></script>
<script>
	if(navigator.userAgent.indexOf('MSIE 6')>-1){DD_belatedPNG.fix('.OrgStatus');}
        function resizewnd() {
        var b = document.documentElement;
    	$('#login_main_table').height(b.clientHeight);
    	
	}
		
	$(window).resize(resizewnd);
		window.onload=function(){
		resizewnd();
	}
	$(function() {
		correctPNG();
	});
	//处理IE6下面png透明图片
	function correctPNG() // correctly handle PNG transparency in Win IE 5.5 & 6.
	{
	    var arVersion = navigator.appVersion.split("MSIE")
	    var version = parseFloat(arVersion[1])
	    if ((version >= 5.5) && (document.body.filters))
	    {
	       for(var j=0; j<document.images.length; j++)
	       {
	          var img = document.images[j]
	          var imgName = img.src.toUpperCase()
	          if (imgName.substring(imgName.length-3, imgName.length) == "PNG")
	          {
	             var imgID = (img.id) ? "id='" + img.id + "' " : ""
	             var imgClass = (img.className) ? "class='" + img.className + "' " : ""
	             var imgTitle = (img.title) ? "title='" + img.title + "' " : "title='" + img.alt + "' "
	             var imgStyle = "display:inline-block;" + img.style.cssText
	             if (img.align == "left") imgStyle = "float:left;" + imgStyle
	             if (img.align == "right") imgStyle = "float:right;" + imgStyle
	             if (img.parentElement.href) imgStyle = "cursor:hand;" + imgStyle
	             var strNewHTML = "<span " + imgID + imgClass + imgTitle
	             + " style=\"" + "width:" + img.width + "px; height:" + img.height + "px;" + imgStyle + ";"
	             + "filter:progid:DXImageTransform.Microsoft.AlphaImageLoader"
	             + "(src=\'" + img.src + "\', sizingMethod='scale');\"></span>"
	             img.outerHTML = strNewHTML
	             j = j-1
	          }
	       }
	    }    
	}
</script>
</head>
<body style="background: url('<?=$_BASE_DIR?>images/login_back_body.png') repeat;">
<div class="login_bg">
<?php $this->_block('contents'); ?><?php $this->_endblock(); ?>
</div>
</body>
</html>