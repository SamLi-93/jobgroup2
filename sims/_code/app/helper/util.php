<?php

class Helper_Util {
	/**
	 * add_dl_header
	 *
	 * 添加下载文件的header
	 * $filename 文件名
	 */
	public static function add_dl_header($filename) {
		header ( "Content-Type: application/octet-stream" );
		header ( 'Content-Disposition: attachment; filename="' . iconv ( "utf-8", "gb2312", $filename ) . '"' );
		header ( "Content-Transfer-Encoding: binary" );
		header ( "Last-Modified: " . gmdate ( "D, d M Y H:i:s" ) . " GMT" );
		header ( "Cache-Control: must-revalidate, post-check=0, pre-check=0" );
		header ( "Pragma: no-cache" );
	}
    /**
     +----------------------------------------------------------
     * 下载文件
     * 可以指定下载显示的文件名，并自动发送相应的Header信息
     * 如果指定了content参数，则下载该参数的内容
     +----------------------------------------------------------
     * @static
     * @access public
     +----------------------------------------------------------
     * @param string $filename 下载文件名
     * @param string $showname 下载显示的文件名
     * @param string $content  下载的内容
     * @param integer $expire  下载内容浏览器缓存时间
     */
    public static function download ($filename, $showname='',$content='',$expire=180) {
        if(is_file($filename)) {
            $length = filesize($filename);
        }elseif(is_file(UPLOAD_PATH.$filename)) {
            $filename = UPLOAD_PATH.$filename;
            $length = filesize($filename);
        }elseif($content != '') {
            $length = strlen($content);
        }else {
            //'下载文件不存在！'
        }
        if(empty($showname)) {
            $showname = $filename;
        }
        $showname = basename($showname);
		if(!empty($filename)) {
	        $type = mime_content_type($filename);
		}else{
			$type	 =	 "application/octet-stream";
		}
        //发送Http Header信息 开始下载
        header("Pragma: public");
        header("Cache-control: max-age=".$expire);
        //header('Cache-Control: no-store, no-cache, must-revalidate');
        header("Expires: " . gmdate("D, d M Y H:i:s",time()+$expire) . "GMT");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s",time()) . "GMT");
        header("Content-Disposition: attachment; filename=".$showname);
        header("Content-Length: ".$length);
        header("Content-type: ".$type);
        header('Content-Encoding: none');
        header("Content-Transfer-Encoding: binary" );
        if($content == '' ) {
            readfile($filename);
        }else {
        	echo($content);
        }
        exit();
    }
	/**
	 * cannot_null
	 *
	 * 验证字段不能为空
	 */
	public static function cannot_null($info) {
		$flag = true;
		if ($info === null || $info === "") {
			$flag = false;
		}
		return $flag;
    }

	/**
	 * cannot_null
	 *
	 * 将秒数转换为时间
	 */
	public static function second2string($info) {
		$info_m = $info % 3600;
		$h = ($info-$info_m) / 3600;
		$s = $info % 60;
		$m = ($info_m-$s) / 60;
		
		$return_txt = "";
		if($h<10){
			$return_txt .= empty($h)?"00":("0".$h);
		}else{
			$return_txt .= $h;
		}
		if($m<10){
			$return_txt .= ":".(empty($m)?"00":("0".$m));
		}else{
			$return_txt .= ":".$m;
		}
		if($s<10){
			$return_txt .= ":".(empty($s)?"00":("0".$s));
		}else{
			$return_txt .= ":".$s;
		}
		return $return_txt;
    }
    
    
	public static function DES_Encrypt($input,$key)
	{
	    $input=trim($input);
	    $key=substr(md5($key),0,24);
	    $td=mcrypt_module_open('tripledes','','ecb','');
	    $iv=mcrypt_create_iv(mcrypt_enc_get_iv_size($td),MCRYPT_RAND);
	    mcrypt_generic_init($td,$key,$iv);
	    $encrypted_data=mcrypt_generic($td,$input);
	    mcrypt_generic_deinit($td);
	    mcrypt_module_close($td);
	    return base64_encode($encrypted_data);
	}
	
	/**
	* DES Decrypt
	*
	* @param $input - stuff to decrypt
	* @param $key - the secret key to use
	* @return string
	**/
	public static function DES_Decrypt($input,$key)
	{
	    $input=base64_decode($input);
	    $td=mcrypt_module_open('tripledes','','ecb','');
	    $key=substr(md5($key),0,24);
	    $iv=mcrypt_create_iv(mcrypt_enc_get_iv_size($td),MCRYPT_RAND);
	    mcrypt_generic_init($td,$key,$iv);
	    $decrypted_data=mdecrypt_generic($td,$input);
	    mcrypt_generic_deinit($td);
	    mcrypt_module_close($td);
	    return trim(chop($decrypted_data));
	}


    /**
	 * is_Real_Mobile
	 *
	 * 验证字段不能为空
	 */
	public static function is_Real_Mobile($info) {
		$flag = false;
		if (preg_match("/^(\+86)?(13\d|15[0-3]|18[5-9]|15[5-9]|180|182|183|147|145)\d{8}$/", $info)) {
			$flag = true;
		}
		return $flag;
	}
	
	/**
	 * export_excel
	 *
	 * 导出Excel
	 * $titles 标题数组（必要是k-v形式的哈希表，key为字段名）
	 * $records 内容数组
	 * $filename 下载的文件名称
	 * $sheetname 工作表名称
	 */
	public static function export_excel($titles, $records, $filename = "data.xls", $sheetname = "Sheet1") {
		require_once Q::ini ( 'app_config/LIB_DIR' ) . '/PHPExcel.php';
		require_once Q::ini ( 'app_config/LIB_DIR' ) . '/PHPExcel/Writer/Excel5.php';
		$cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_in_memory;
		if (! PHPExcel_Settings::setCacheStorageMethod ( $cacheMethod ))
			die ( 'CACHEING ERROR' );
		ini_set ( 'memory_limit', '2048M' );
		set_time_limit ( 600 );
		// 创建一个处理对象实例  
		$objExcel = new PHPExcel ();
		$objWriter = new PHPExcel_Writer_Excel5 ( $objExcel );
		$objActSheet = $objExcel->getActiveSheet ();
		$objActSheet->setTitle ( $sheetname );
		
		//设置表头
		$zm = ord ( 'A' );
		foreach ( $titles as $title ) {
			$objActSheet->setCellValue ( chr ( $zm ) . '1', $title );
			//表头加粗
			$objStyleA5 = $objActSheet->getStyle ( chr ( $zm ++ ) . '1' );
			$objFontA5 = $objStyleA5->getFont ();
			$objFontA5->setBold ( true );
		}
		
		//内容
		foreach ( $records as $i => $rec ) {
			$zm = ord ( 'A' );
			foreach ( $titles as $k => $v ) {
				$objActSheet->setCellValueExplicit ( chr ( $zm ++ ) . ($i + 2), $rec [$k], PHPExcel_Cell_DataType::TYPE_STRING );
			}
		}
		
		//输出内容
		Helper_Util::add_dl_header ( $filename );
		
		$objWriter->save ( 'php://output' );
	}
	public static function export_excel2($titles, $records, $filename = "data.xls", $sheetname = "Sheet1",$coursename='') {
		require_once Q::ini ( 'app_config/LIB_DIR' ) . '/PHPExcel.php';
		require_once Q::ini ( 'app_config/LIB_DIR' ) . '/PHPExcel/Writer/Excel5.php';
		$cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_in_memory;
		if (! PHPExcel_Settings::setCacheStorageMethod ( $cacheMethod ))
			die ( 'CACHEING ERROR' );
		ini_set ( 'memory_limit', '2048M' );
		set_time_limit ( 600 );
		// 创建一个处理对象实例  
		$objExcel = new PHPExcel ();
		$objWriter = new PHPExcel_Writer_Excel5 ( $objExcel );
		$objActSheet = $objExcel->getActiveSheet ();
		$objActSheet->setTitle ( $sheetname );
		
		//设置表头
		$zm = ord ( 'A' );

		$objActSheet->mergeCells('A1:I1'); 
		$objActSheet->setCellValue ( chr ( $zm ) . '1', $coursename);

		foreach ( $titles as $title ) {
			$objActSheet->setCellValue ( chr ( $zm ) . '2', $title );
			//表头加粗
			$objStyleA5 = $objActSheet->getStyle ( chr ( $zm ++ ) . '2' );
			$objFontA5 = $objStyleA5->getFont ();
		
		}
		
		//内容
		foreach ( $records as $i => $rec ) {
			$zm = ord ( 'A' );
			foreach ( $titles as $k => $v ) {
				$objActSheet->setCellValueExplicit ( chr ( $zm ++ ) . ($i + 3), $rec [$k], PHPExcel_Cell_DataType::TYPE_STRING );
			}
		}
		
		//输出内容
		Helper_Util::add_dl_header ( $filename );
		
		$objWriter->save ( 'php://output' );
	}
	/**
	 * export_excel
	 *
	 * 导出Excel
	 * $titles 标题数组（必要是k-v形式的哈希表，key为字段名）
	 * $records 内容数组
	 * $filename 下载的文件名称
	 * $sheetname 工作表名称
	 */
	public static function export_excel_change($titles, $records, $filename = "data.xls", $sheetname = "Sheet1",$changeDate,$dateStyle) {
		require_once Q::ini ( 'app_config/LIB_DIR' ) . '/PHPExcel.php';
		require_once Q::ini ( 'app_config/LIB_DIR' ) . '/PHPExcel/Writer/Excel5.php';
		$cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_in_memory;
		if (! PHPExcel_Settings::setCacheStorageMethod ( $cacheMethod ))
			die ( 'CACHEING ERROR' );
		ini_set ( 'memory_limit', '2048M' );
		set_time_limit ( 600 );
		// 创建一个处理对象实例  
		$objExcel = new PHPExcel ();
		$objWriter = new PHPExcel_Writer_Excel5 ( $objExcel );
		$objActSheet = $objExcel->getActiveSheet ();
		$objActSheet->setTitle ( $sheetname );
		
		//设置表头
		$zm = ord ( 'A' );
		foreach ( $titles as $title ) {
			$objActSheet->setCellValue ( chr ( $zm ) . '1', $title );
			//表头加粗
			$objStyleA5 = $objActSheet->getStyle ( chr ( $zm ++ ) . '1' );
			$objFontA5 = $objStyleA5->getFont ();
			$objFontA5->setBold ( true );
		}
		//内容
		foreach ( $records as $i => $rec ) {
			$zm = ord ( 'A' );
			foreach ( $titles as $k => $v ) {
				$val = $rec [$k];
				if(in_array($k, $changeDate)){
					//dump($k);
					$value = $val;
					switch ($k){
						case 'examdate':
							$val = date($dateStyle[$k],$value);
							continue;
						case 'isreplace':
							if ($value == 0)
								$val = "否";
							else if ($value == 2)
								$val = "免考免修";
							continue;
						case 'passed':
							$val = $value==0?"未通过":"通过";
							continue;
						case 'dingti':
							$val = $value==1?"是":"否";
							continue;
						default:
							$val = "录入数据出错";
							continue;
					}
					$value="";
				}
				
				$objActSheet->setCellValueExplicit ( chr ( $zm ++ ) . ($i + 2), $val, PHPExcel_Cell_DataType::TYPE_STRING );
				$val = "";
			}
		}
		
		//输出内容
		Helper_Util::add_dl_header ( $filename );
		
		$objWriter->save ( 'php://output' );
	}
	
	//同上 但titles字段从form中读取
	public static function export_form_excel($model, $records, $filename = "data.xls", $sheetname = "Sheet1", $titles_add = null) {
		$titles = array ();
		if ($model) {
			$formfile = Q::ini ( 'app_config/APP_DIR' ) . '/form/' . $model . '_form.yaml';
			$form = Helper_YAML::loadCached ( $formfile );
			foreach ( $form as $k => $v ) {
				if ($k == '~form')
					continue;
				if (empty ( $v ['_label'] ))
					continue;
				if (!empty ( $v ['_noex'] ))
					continue;
				if (! empty ( $v ['_exkey'] ))
					$k = $v ['_exkey'];
				$titles [$k] = $v ['_label'];
			}
		}
		if ($titles_add) {
			$titles += $titles_add;
		}
		

		Helper_Util::export_excel ( $titles, $records, $filename, $sheetname );
	}
	
	//同上 但titles字段从form中读取并且从yaml中去掉不需要的字段
	public static function export_form_excel_exceptarr($model, $records, $filename = "data.xls", $sheetname = "Sheet1", $titles_add = null, $arr = null) {
		$formfile = Q::ini ( 'app_config/APP_DIR' ) . '/form/' . $model . '_form.yaml';
		$form = Helper_YAML::loadCached ( $formfile );
		$titles = array ();
		foreach ( $form as $k => $v ) {
			if (! in_array ( $k, $arr )) {
				if ($k == '~form')
					continue;
				if (empty ( $v ['_label'] ))
					continue;
				if (! empty ( $v ['_exkey'] ))
					$k = $v ['_exkey'];
				$titles [$k] = $v ['_label'];
			}
		}
		if ($titles_add) {
			$titles += $titles_add;
		}
		Helper_Util::export_excel ( $titles, $records, $filename, $sheetname );
	}
	
	

	
	
	
	static  function idcard_verify_number($idcard_base) {
		if (strlen ( $idcard_base ) != 17) {
			$this->showMsg ( "您输入的身份证位数不对，请您重新填写！！！", "" );
			return false;
		}
		//加权因子 
		$factor = array (7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2 );
		//校验码对应值 
		$verify_number_list = array ('1', '0', 'X', '9', '8', '7', '6', '5', '4', '3', '2' );
		$checksum = 0;
		for($i = 0; $i < strlen ( $idcard_base ); $i ++) {
			$checksum += substr ( $idcard_base, $i, 1 ) * $factor [$i];
		}
		$mod = strtoupper ( $checksum % 11 );
		$verify_number = $verify_number_list [$mod];
		
		return $verify_number;
	}
	
	//将15位身份证升级到18位 
	static  function idcard_15to18($idcard) {
		if (strlen ( $idcard ) != 15) {
			$this->showMsg ( "您输入的身份证位数不对，请您重新填写！！！", "" );
			return false;
		} else {
			//如果身份证顺序码是996 997 998 999,这些是为百岁以上老人的特殊编码 
			if (array_search ( substr ( $idcard, 12, 3 ), array ('996', '997', '998', '999' ) ) != false) {
				$idcard = substr ( $idcard, 0, 6 ) . '18' . substr ( $idcard, 6, 9 );
			} else {
				$idcard = substr ( $idcard, 0, 6 ) . '19' . substr ( $idcard, 6, 9 );
			}
		}
		$idcard = $idcard . self::idcard_verify_number ( $idcard );
		return $idcard;
	}
	
	//18位身份证校验码有效性检查 
	static function idcard_checksum18($idcard) {
		if (strlen ( $idcard ) != 18) {
			return false;
		}
		$aCity = array (11 => "北京", 12 => "天津", 13 => "河北", 14 => "山西", 15 => "内蒙古", 21 => "辽宁", 22 => "吉林", 23 => "黑龙江", 31 => "上海", 32 => "江苏", 33 => "浙江", 34 => "安徽", 35 => "福建", 36 => "江西", 37 => "山东", 41 => "河南", 42 => "湖北", 43 => "湖南", 44 => "广东", 45 => "广西", 46 => "海南", 50 => "重庆", 51 => "四川", 52 => "贵州", 53 => "云南", 54 => "西藏", 61 => "陕西", 62 => "甘肃", 63 => "青海", 64 => "宁夏", 65 => "新疆", 71 => "台湾", 81 => "香港", 82 => "澳门", 91 => "国外" );
		//非法地区 
		if (! array_key_exists ( substr ( $idcard, 0, 2 ), $aCity )) {
			return false;
		}
		//验证生日 
		if (! checkdate ( substr ( $idcard, 10, 2 ), substr ( $idcard, 12, 2 ), substr ( $idcard, 6, 4 ) )) {
			return false;
		}
		$idcard_base = substr ( $idcard, 0, 17 );
		if (self::idcard_verify_number ( $idcard_base ) != strtoupper ( substr ( $idcard, 17, 1 ) )) {
			return false;
		} else {
			return true;
		}
	}

	static function mkdir($filename){
		if (file_exists($filename))return ;
		self::mkdir(dirname($filename));
		mkdir($filename);
	}
	
/*
//触发提醒
//消息类型
1 => '系统消息',
2 => '通知提醒',
3 => '短消息',
4 => '网站公告',
*/
    static function sendmsg($senderid, $receiverid, $msgtype = 2, $title = null, $content = null){
        //消息提交处理
        $msg = new Lcmessage(array(
					'title'		=> $title,
					'content'	=> $content,
					'stime'		=> time(),
					'sender'	=> $senderid,
					'msgtype'	=> $msgtype,
					'isdelete'	=> '0'
				));
        $msg->save();
        //短消息处理
		$msguser = new Lcmsguser(array(
						'msgid'		=> $msg->id,
						'receiver'	=> $receiverid,
						'isdelete'	=> '0'
					));
		$msguser->save();
    }

	//截取太长的标题，适用于utf8字符
	static function utfSubStr($str,$len,$suffix='...'){
		$theStr = $str;
		for ($i=0;$i<$len;$i++) {
			$temp_str = substr($str,0,1);
			if (ord($temp_str) > 127) {
				$i++;
				if ($i < $len) {
					$new_str[]	= substr($str,0,3);
					$str		= substr($str,3);
				}
			} else {
				$new_str[]	= substr($str,0,1);
				$str		= substr($str,1);
			}
		}
		if($theStr == join($new_str)){
			return $theStr;
		} else {
			return join($new_str).$suffix;
		}
	}
	//获取对象的Array（不带自定义属性）
	static function objtoArray($recs) {
		$return = array();
		foreach($recs as $key => $rec){
			$fields = $rec->meta()->table_meta;
			$data = $rec->toArray(0);
			$data2 = array();
			foreach ($data as $k=>$v) {
				if (isset($fields[$k]))
					$data2[$k] = $v;
			}
			$return[] = $data2;
		}
		return $return;
	}
	//生成2011/12/26这种日期目录
	static function mk_date_dir($rootdir){
		$my_dir_Y = date('Y').DIRECTORY_SEPARATOR;
		$my_dir_m = date('m').DIRECTORY_SEPARATOR;
		$my_dir_d = date('d').DIRECTORY_SEPARATOR;
		self::mkdir($rootdir.$my_dir_Y);
		self::mkdir($rootdir.$my_dir_Y.$my_dir_m);
		self::mkdir($rootdir.$my_dir_Y.$my_dir_m.$my_dir_d);
		return $my_dir_Y.$my_dir_m.$my_dir_d;
	}
	
	//创建文件夹 的函数
   public static function makeDir($path) {
        if (empty ( $path )) {
            echo "路径不能为空";
        }
        $dirs = array ();
        $path = preg_replace ( '/(\/){2,}|{\\\}{1,}/', '/', $path );
        $dirs = explode ( "/", $path );
        $path = "";
        foreach ( $dirs as $folder ) {
            $path .= $folder . "/";
            if (! is_dir ( $path )) {
                mkdir ( $path, 0700 );
            }
        }
        if (is_dir ( $path )) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
    //删除文件夹 的函数
    public static function rmdirs($dir) {
        $dir = realpath ( $dir );
        if ($dir == '' || $dir == '/' || (strlen ( $dir ) == 3 && substr ( $dir, 1 ) == '://')) {
            return false;
        }
        if (false !== ($dh = opendir ( $dir ))) {
            while ( false !== ($file = readdir ( $dh )) ) {
                if ($file == '.' || $file == '..') {
                    continue;
                }
                $path = $dir . DIRECTORY_SEPARATOR . $file;
				echo $path  ;die;
                if (is_dir ( $path )) {
                    if (! rmdirs ( $path )) {
                        return false;
                    }
                } else {
                    unlink ( $path );
                }
            }
            closedir ( $dh );
            rmdir ( $dir );
            return true;
        } else {
            return false;
        }
    }

    //远程下载图片
	public static function downloadImage($url,$filepath) {
        if($url == ''){return false;}
	    $ext_name = strrchr($url, '.'); //获取图片的扩展名
	    if($ext_name != '.gif' && $ext_name != '.jpg' && $ext_name != '.bmp' && $ext_name != '.png') {
	        return false; //格式不在允许的范围
	    }
	    //开始捕获
	    ob_start();
	    readfile($url);
	    $img_data = ob_get_contents();
	    ob_end_clean();
	    $local_file = @fopen($filepath , 'a');
	    fwrite($local_file, $img_data);
	    fclose($local_file);
    }

    public static function format_time($t) {
		$h = floor($t/3600);
		$m = floor($t%3600/60);
		$s = floor($t%60);
		return sprintf('%02d:%02d:%02d', $h, $m, $s);
	}

	//生成pagination
	public static function gen_pagination($total, $limit){
		$current = QContext::instance()->page;
		if (!$current) $current = 1;
		$last = ceil($total/$limit);
		if (!$last) $last = 1;
		$prev = $current - 1;
		if (!$prev) $prev = 1;
		$next = $current + 1;
		if ($next > $last) $next = $last;
		$pagination = array(
			'first' => 1,
			'last' => $last,
			'current' => $current,
			'record_count' => $total,
			'prev' => $prev,
			'next' => $next,
		);
		return $pagination;
	}
	//评测计算
	public static function get_Evaluation($Evaluation,$fix='B_'){
		$listdb = Inepconfig::find("")->getAll()->toArray();
		$B_Evaluation = array();
		foreach($listdb as $key=>$rs){
			$B_Evaluation[$fix.$rs['name']] = ($Evaluation[$rs['name']]*$rs['value'])/100;
		}
		$B_Evaluation['all_score_sum'] = array_sum($B_Evaluation);
		return $B_Evaluation;
	}
	
	/*xml文件转换为Array数组
     * $str:对应的值
     * $type:str的类型，f => file 即文件地址, s =>string 即Xml正文
     *
	 */
    public static function xml2arr($str,$type){
    	if('f'){
    		$xmlstr = simplexml_load_file($str);
    	}else if('s'){
    		$xmlstr = simplexml_load_string($str);
    	}
		$arrstr = array();

		$arrstrs=json_encode($xmlstr);
		$arrstr=json_decode($arrstrs,true);
	    return $arrstr;
	}
	
	/**
	 * [getTraningByOrgids 获取相关学习中心ID]
	 * @param  [type] $orgids [description]
	 * @return [type]         [description]
	 */
	public static function getCollegeByOrgids($orgids){
		$db = QDB::getConn();
		$return_list = array();
		if(!empty($orgids)){
			$sql = "select pid from sms_org where id in (".$orgids.")";
			$len_arr = $db->getAll($sql);
			foreach ($len_arr as $key => $val) {
				$return_list[] = $val['pid'];
			}
		}
		return $return_list;
	}

	/**
	 * [getClassinfoByOrgids 获取相关班级ID]
	 * @param  [type] $orgids [description]
	 * @return [type]         [description]
	 */
	public static function getClassinfoByOrgids($orgids){
		$db = QDB::getConn();
		$return_list = array();
		if(!empty($orgids)){
			$sql = "select id from sms_class where isdelete=0 and training_id in (".$orgids.")";
			$class_arr = $db->getAll($sql);
			foreach ($class_arr as $key => $val) {
				$return_list[] = $val['id'];
			}
		}
		return $return_list;
	}

	/**
	 * [getDisplinByOrgids 获取相关专业ID]
	 * @param  [type] $orgids [description]
	 * @return [type]         [description]
	 */
	public static function getDisplinByOrgids($orgids){
		$db = QDB::getConn();
		$return_list = array();
		if(!empty($orgids)){
			$edu_ids =  Helper_Util::getCollegeByOrgids($orgids);
			if(!empty($edu_ids)){
				$sql = "select id from sms_discipline where isdelete=0 and college_id in (".implode(',', $edu_ids).")";
				$dis_arr = $db->getAll($sql);
				foreach ($dis_arr as $key => $val) {
					$return_list[] = $val['id'];
				}
			}
			
		}
		return $return_list;
	}
}