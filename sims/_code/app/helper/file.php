<?php
/**
 * FILE_NAME : file.php
 * 字符串处理类
 *
 * @copyright Copyright (c) 2012 – 2014 吉博科技
 * @author S.exp
 * @package
 * @subpackage
 * @data 2012-7-30
 */

class Helper_File{
	
	/**
	 * 读取文件的内容
	 * 
	 * @param string $filename
	 */
	function readformfile($filename){
		if(!is_readable($filename))
			return; 
		return file_get_contents($filename);
	}
	
	/**
	 * 递归创建目录
	 *
	 * @param string $dirname
	 */
	function fmkdir($dirname){
		$patharr = explode('/',$dirname);
		if(count($patharr) == 0){
			mkdir($dirname);
		}else {
			array_pop($patharr);
			$path = @implode('/', $patharr);
			if(is_dir($path.'/')) {
				@mkdir($dirname);
			} else {
				$this->fmkdir($path);
				@mkdir($dirname);
			}
		}
	}
	
	/**
	 * 递归复制文件夹及里面的文件
	 *
	 * @param string $dirf
	 * @param string $dirt
	 * @return bool
	 */
	function copydir($dirf, $dirt) {
		if(!is_dir($dirf)) return false;
		$mydir = @opendir($dirf);
		
		@mkdir($dirt);
		while(false !== ($file = @readdir($mydir))) {
			if((is_dir($dirf."/".$file)) && ($file!=".") && ($file!="..")) {
				if(!@copydir("$dirf/$file","$dirt/$file"))return false;
			} elseif(is_file("$dirf/$file")) {
				if(!@copy("$dirf/$file","$dirt/$file"))return false;
			}
		}
		return true;
	
	}
	
		
	/**
	 * 写入文件的内容
	 * 
	 * @param string $text;
	 * @param string $filename
	 */
	function writetofile($text,$filename){
		$path = pathinfo($filename);
		if(!is_dir($path['dirname']))
			fmkdir($path['dirname']);
		if(!$hand = fopen($filename,'w+')){
			return false;
		}else{
			flock($hand,LOCK_EX);
			fwrite($hand,$text);
			fclose($hand);
		}
	}
	
	/**
	 * 写入文件内容
	 *
	 * @param string $log
	 * @param string $file
	 */
	function log($log, $file) {
		$log = $log."\n";
		if(function_exists('error_log')) {
			error_log($log, 3, $file);
		} else {
			if(!$fp = @fopen($file, 'a')) return false;
			flock($fp, LOCK_EX + LOCK_NB);
			fwrite($fp, $log);
			flock($fp, LOCK_UN);
			return fclose($fp);
		}
	}
	
	/*
	function unzip($zip, $to = '.') {
		$size = filesize($zip);
		$maximum_size = min(277, $size);
		$fp = fopen($zip, 'rb');
		fseek($fp, $size - $maximum_size);
		$pos = ftell($fp);
		$bytes = 0x00000000;
		while($pos < $size) {
			$byte = fread($fp, 1);
			if(PHP_INT_MAX > 2147483647) {
				$bytes = ($bytes << 32);
				$bytes = ($bytes << 8);
				$bytes = ($bytes >> 32);
			} else {
				$bytes = ($bytes << 8);
			}
			$bytes = $bytes | ord($byte);
			if($bytes == 0x504b0506) {
				$pos ++;
				break;
			}
			$pos ++;
		}
		$fdata = fread($fp, 18);
		$data = @unpack('vdisk/vdisk_start/vdisk_entries/ventries/Vsize/Voffset/vcomment_size',$fdata);
		$pos_entry = $data['offset'];
		for($i=0; $i < $data['entries']; $i++) {
			fseek($fp, $pos_entry);
			$header = ReadCentralFileHeaders($fp);
			$header['index'] = $i;
			$pos_entry = ftell($fp);
			rewind($fp);
			fseek($fp, $header['offset']);
			$stat[$header['filename']] = ExtractFile($header, $to, $fp);
		}
		fclose($fp);
	}
	*/
	
	/**
	 * 多文件上传
	 *
	 * @return unknown
	 */
	public function uploadmultifile(){
		/**
		 * 初始化上传文件对象
		 * @var object
		 */
		$uploader = new Helper_Uploader();
		/* @var $uploader helper_fileuploader */
		
		$filecount = $uploader->filesCount();
		if(!empty($filecount)){
			$attacount = $uploader->allFiles();
			foreach($attacount as $key=>$uploadfile){
				if($uploadfile->isSuccessed()){
                	if($uploadfile->isValid('jpg,jpeg,png,gif,doc,docx,xls,rar,zip,txt,pdf,xlsx,ppt,pptx')){
                    	$filepath = $_SERVER['DOCUMENT_ROOT'] . QContext::instance()->baseDir() . 'upload/'.date('Ym').'/';
                    	if(!file_exists($filepath)) mkdir($filepath);
                    	$attachmentname = $key.time().'.'.$uploadfile->extname();
                    	$uploadfile->move($filepath.$attachmentname);
                    	$arrattach[] = date('Ym'). DS .$attachmentname;
                    	$arrfilename[] = $uploadfile->filename();
	                }else{
	                   return false;                                    
	                }
	        	}else{
	                return false;
	            }						
			}
			if(!empty($arrattach)){
				$Formarr['attachment'] = implode("|",$arrattach);
				$Formarr['filename'] = implode("|",$arrfilename);
			}
			return $Formarr;
		}
	}
	
	/**
	 * 单文件上传
	 *
	 * @param array $ext
	 */
	public function uploadfile($ext,$file,$fiedname){
		$exts = implode(',',$ext);
		$uploadfile = new Helper_Uploader_File($file,$fiedname);
		//$base_dir = Q::ini('app_config/ROOT_DIR');
		$import_dir = Q::ini('app_config/IMPORT_DIR');
		if($uploadfile->isSuccessed()){
	    	if($uploadfile->isValid($exts)){
	        	//$filepath = $import_dir . '/'.date('Ym').'/';
	        	$filepath = $import_dir . '/';
	        	if(!file_exists($filepath)) mkdir($filepath);
	        	$attachmentname = time().'.'.$uploadfile->extname();
	        	$uploadfile->move($filepath.$attachmentname);
	        	return $filepath.$attachmentname;
	        }else{
	           return false;                                    
	        }
		}else{
	        return false;
	    }
	}
	
	/**
	 * 创建压缩包
	 *
	 * @param string $zipname
	 * @param string $exportpath
	 * @param string $sitefolder
	 */
	public function createZip($zipname,$exportpath,$sitefolder){
		require_once Q::ini ( 'app_config/LIB_DIR' ) . '/pclzip.lib.php';
		$archive = new PclZip($zipname);
		$list =  $archive->create($exportpath,
								PCLZIP_OPT_REMOVE_PATH, $exportpath,
                             	PCLZIP_OPT_ADD_PATH, $sitefolder);
		if($list == 0){
			die("Error : ".$archive->errorInfo(true));
		}
	}
	
	/**
	 * 解压zip压缩包
	 *
	 * @param unknown_type $zipname
	 * @param unknown_type $importpath
	 */
	public function unzip($zipname,$importpath){
		require_once Q::ini ( 'app_config/LIB_DIR' ) . '/pclzip.lib.php';
		$archive = new PclZip($zipname);
		$list = $archive->extract(PCLZIP_OPT_PATH, $importpath,
                        		PCLZIP_OPT_REMOVE_PATH, $importpath);
        if($list == 0){
			die("Error : ".$archive->errorInfo(true));
		}  
		return $list;      
	}
	
	/**
	 * 把目录读到一个数组变量里
	 *
	 * @param string $path
	 * @param int $shortfilename
	 * @return array
	 */
	function readpathtoarray($path, $shortfilename = 0) {
		$return = array();
		if(!file_exists($path)) return $return;
		$fp = opendir($path);
		while (false !== ($file = readdir($fp))) {
			if($file == '.' || $file == '..') continue;
			if($shortfilename == 1) {
				$return[] = $file;
			} else {
				if(substr($path, -1) == '/') {
					$return[] = $path.$file;
				} else {
					$return[] = $path.'/'.$file;
				}
			}
		}
		closedir($fp);
		return $return;
	}

	
	/**
	 * 删除文件夹
	 * 
	 * @param  array $dirName
	 */
	function removeDir($dirName) 
	{ 
	    if(! is_dir($dirName)) 
	    { 
	        return false; 
	    } 
	    $handle = @opendir($dirName); 
	    while(($file = @readdir($handle)) !== false) 
	    { 
	        if($file != '.' && $file != '..') 
	        { 
	            $dir = $dirName . '/' . $file; 
	            is_dir($dir) ? removeDir($dir) : @unlink($dir); 
	        } 
	    } 
	    closedir($handle); 
	     
	    return rmdir($dirName) ; 
	} 

		
}
