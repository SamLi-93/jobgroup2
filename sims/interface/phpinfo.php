<?php
	//phpinfo();
	$ids = "1,2,11,22";
	$ids_array = "'".str_replace(",","','",$ids)."'";
	//$ids_array = explode(',',$ids);
	print_r($ids_array);
	echo 'Hello World!';
?>