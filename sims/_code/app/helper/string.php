<?php
/**
 * FILE_NAME : string.php
 * 字符串处理类
 *
 * @copyright Copyright (c) 2012 – 2014 吉博科技
 * @author S.exp
 * @package
 * @subpackage
 * @data 2012-7-30
 */

class Helper_String{
	/**
	 * 得到选项字符串显示
	 *
	 * @param string $options
	 * @return string
	 */
	public static function getOptionstr($options,$type_id){
		$optionstr = '';
		$optionsindex =  Q::ini('appini/optionsindex');
		$optionsArr = json_decode($options,true);
		switch ($type_id){
			case '1': //单选题			
				
				foreach ($optionsArr as $k => $v){
					$optionsNewArr[$optionsindex[$k]] = $optionsindex[$k].'. '.$v;
				}
				if(!empty($optionsNewArr) && is_array($optionsNewArr)){
					$optionstr = implode('<br>',$optionsNewArr);
				}
				break;
			case '2': //多选题
				
				foreach ($optionsArr as $k => $v){
					$optionsNewArr[$optionsindex[$k]] = $optionsindex[$k].'. '.$v;
				}
				if(!empty($optionsNewArr) && is_array($optionsNewArr)){
					$optionstr = implode('<br>',$optionsNewArr);
				}
				break;
			case '3': //判断题
				break;
			case '4': //填空题
				break;
			case '5': //问答题
				break;
			case '6': //操作题
				break;
		}
		
		return $optionstr;
	}
	
	/**
	 * 得到答题选项
	 *
	 * @param string $options
	 * @param int $type_id
	 * @param int $id
	 */
	public static function getOptions($options,$answer,$type_id,$id,$flag){
		$optionstr = '';
		$optionsindex =  Q::ini('appini/optionsindex');
		$optionsArr = json_decode($options,true);
		if($type_id != 6){
			$answerArr = json_decode($answer,true);
		}else {
			$answerArr = $answer;
		}
		$se = '';
		switch ($type_id){
			case '1': //单选题
				$optionstr = '选择答案：';
				//$input
				foreach ($optionsArr as $k => $v){
					if($v == $answerArr[0]) $se = 'checked';
					$optionsNewArr[$optionsindex[$k]] = "<span>".$optionsindex[$k].". <input type='radio' value='{$v}' name='question{$id}' {$se}></span>";
					$se = '';
				}
				if(!empty($optionsNewArr) && is_array($optionsNewArr)){
					$optionstr .= implode(' ',$optionsNewArr);
				}
				break;
			case '2': //多选题
				$optionstr = '选择答案：';
				//$input
				foreach ($optionsArr as $k => $v){
					if(!empty($answerArr) && in_array($v,$answerArr)) $se = 'checked';
					$optionsNewArr[$optionsindex[$k]] = "<span>".$optionsindex[$k].". <input type='checkbox' value='{$v}' name='question{$id}' {$se}></span>";
					$se = '';
				}
				if(!empty($optionsNewArr) && is_array($optionsNewArr)){
					$optionstr .= implode(' ',$optionsNewArr);
				}
				break;
			case '3': //判断题
				$optionstr = '选择答案：';
				$se1 = $se0 = '';
				if($answerArr[0] == 1) $se1 = 'checked';
				if(isset($answerArr[0]) && $answerArr[0] == 0) $se0 = 'checked';
				$optionstr .= "<span>√<input type='radio' value='1' name='question{$id}' {$se1}></span><span>×<input type='radio' value='0' name='question{$id}' {$se0}></span>";
				break;
			case '4': //填空题
				$optionstr = '答题区：<br>';
				$optionstr .= "<textarea name='question{$id}'  class='da_put'>{$answerArr[0]}</textarea>";
				break;
			case '5': //问答题
				$optionstr = '答题区：<br>';
				$optionstr .= "<textarea name='question{$id}'  class='da_put'>{$answerArr[0]}</textarea>";
				break;
			case '6': //上传文件
				$optionstr = '上传文件：';
				if($flag=='edit'){
					$img_array = array("gif", "jpg", "jpeg", "bmp", "png");
					$type = strtolower(substr($answer,strripos($answer,".")+1));
					if (in_array($type,$img_array)){
						$optionstr .= "<a href='$answer' title='放大' target='_blank'><img src='$answer' WIDTH='149' HEIGHT='110' /></a>";
					}else if($type=="zip"||$type=="rar"){
						$fname = explode('/', $answer);
						$optionstr .= "<a href='".url('questionwrong/download',array('id'=>$id,'type'=>'answer'))."' title='打开'><font color='blue'>".array_pop($fname)."</font></a>";
					}else{
						$fname = explode('/', $answer);
						$optionstr .= "<a href='".url('questionwrong/download',array('id'=>$id,'type'=>'answer'))."' title='打开'><font color='blue'>".array_pop($fname)."</font></a>";
					}
				}else{
					if($answerArr) $se = '文件已经上传';
					//$optionstr .= "<input type='file' name='question{$id}' id='question{$id}'><input type='button' value='上传' name='uploadfi{$id}' ><span id='uploadsp{$id}'></span>";
					$optionstr .= "<div class='fieldset flash' id='Progress{$id}'><span id='spanButton{$id}'></span><input id='btnCancel{$id}' type='button' value='取消上传' onclick='cancelQueue(upload{$id});' disabled='disabled' /><span id='divstatus{$id}'><a href='{$answerArr}'>{$se}</a></span></div>";
					$se = '';
				}
				break;
			default:
				$optionstr = '答题区：<br>';
				$optionstr .= "<textarea name='question{$id}'  class='da_put'>{$answerArr[0]}</textarea>";
				break;
		}
		return $optionstr;
	}
	
	public static function getbzOptions($options,$answer,$type_id,$id){
		$optionstr = '';
		$optionsindex =  Q::ini('appini/optionsindex');
		$optionsArr = json_decode($options,true);
		if($type_id != 6){
			$answerArr = json_decode($answer,true);
		}else {
			$answerArr = $answer;
		}
		$se = '';
		switch ($type_id){
			case '1': //单选题
				$optionstr = '标准答案：';
				//$input
				foreach ($optionsArr as $k => $v){
					if($v == $answerArr[0]) $se = 'checked';
					$optionsNewArr[$optionsindex[$k]] = "<span>".$optionsindex[$k].". <input type='radio' value='{$v}' name='question{$id}' {$se}></span>";
					$se = '';
				}
				if(!empty($optionsNewArr) && is_array($optionsNewArr)){
					$optionstr .= implode(' ',$optionsNewArr);
				}
				break;
			case '2': //多选题
				$optionstr = '标准答案：';
				//$input
				foreach ($optionsArr as $k => $v){
					if(!empty($answerArr) && in_array($v,$answerArr)) $se = 'checked';
					$optionsNewArr[$optionsindex[$k]] = "<span>".$optionsindex[$k].". <input type='checkbox' value='{$v}' name='question{$id}' {$se}></span>";
					$se = '';
				}
				if(!empty($optionsNewArr) && is_array($optionsNewArr)){
					$optionstr .= implode(' ',$optionsNewArr);
				}
				break;
			case '3': //判断题
				$optionstr = '标准答案：';
				$se1 = $se0 = '';
				if($answerArr[0] == 1) $se1 = 'checked';
				if(isset($answerArr[0]) && $answerArr[0] == 0) $se0 = 'checked';
				$optionstr .= "<span>√<input type='radio' value='1' name='question{$id}' {$se1}></span><span>×<input type='radio' value='0' name='question{$id}' {$se0}></span>";
				break;
			case '4': //填空题
				$optionstr = '标准答案：<br>';
				$optionstr .= "<textarea name='question{$id}'  class='da_put'>{$answerArr[0]}</textarea>";
				break;
			case '5': //问答题
				$optionstr = '标准答案：<br>';
				$optionstr .= "<textarea name='question{$id}'  class='da_put'>{$answerArr[0]}</textarea>";
				break;
			case '6': //上传文件
				$optionstr = '标准答案：';
				$img_array = array("gif", "jpg", "jpeg", "bmp", "png");
				$type = strtolower(substr($answer,strripos($answer,".")+1));
				if (in_array($type,$img_array)){	
					$optionstr .= "<a href='$_BASE_DIR".substr($answer,1)."' title='放大' target='_blank'><img src='$_BASE_DIR".substr($answer,1)."' WIDTH='149' HEIGHT='110' /></a>";
				}else if($type=="zip"||$type=="rar"){
					$fname = explode('/', $answer);
					$optionstr .= "<a href='".url('questionwrong/download',array('id'=>$id,'type'=>'result'))."' title='打开'><font color='blue'>".array_pop($fname)."</font></a>";
				}else{
					$fname = explode('/', $answer);
					$optionstr .= "<a href='".url('questionwrong/download',array('id'=>$id,'type'=>'result'))."' title='打开'><font color='blue'>".array_pop($fname)."</font></a>";
				}
				//$optionstr .= "<div class='fieldset flash' id='Progress{$id}'><span id='spanButton{$id}'></span><input id='btnCancel{$id}' type='button' value='取消上传' onclick='cancelQueue(upload{$id});' disabled='disabled' /><span id='divstatus{$id}'><a href='{$answerArr}'>{$se}</a></span></div>";
				break;
			default:
				$optionstr = '标准答案：<br>';
				$optionstr .= "<textarea name='question{$id}'  class='da_put'>{$answerArr[0]}</textarea>";
				break;
		}
		return $optionstr;
	}
	
	
	/**
	 * 消息提示
	 *
	 * @param unknown_type $options
	 * @param unknown_type $type_id
	 * @param unknown_type $id
	 */
	public static function adminmsg(){
		
	}
	
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
	 * 对外部数据进行处理
	 *
	 * @param array $arr
	 */
	public function eip_addslashes($arr){
		//!defined('MAGIC_QUOTES_GPC') && define('MAGIC_QUOTES_GPC', get_magic_quotes_gpc());
		//if(!MAGIC_QUOTES_GPC) {
			foreach($arr as $_key => $_value) {
				$arr[$_key] = addslashes($_value);
			}
		//}
		return $arr;
	}
	
	/**
	 * Enter description here...
	 *
	 * @param int $num 符合条件的记录数
	 * @param int $limit
	 * @param int $page 当前页数
	 */
	public function getPage($num,$limit,$page){
	    $page_count = ceil($num/$limit);
	    $prev = $page == 1?1:($page-1);
	    $next = $page_count == $page?$page:($page+1);
	    $last = $page_count == 0?1:$page_count;
	    //$next = $page_count = $page;
	    $pager = array('record_count'=>$num,'current'=>$page,'page_size'=>$limit,'page_count'=>$page_count,'prev'=>$prev,'next'=>$next,'first'=>1,'last'=>$last,'page_base'=>1);
	    return $pager;
	}
}