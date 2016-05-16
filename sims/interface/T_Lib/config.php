<?php
/**
*  配置文件
*  code by tong
*/ 
//目录常量设置
$root_dir = dirname(__FILE__);
define('T_ROOT',$root_dir.DIRECTORY_SEPARATOR);					//根目录
define('T_LIB_ROOT',T_ROOT.'lib'.DIRECTORY_SEPARATOR);			//类库目录
define('T_MODEL_ROOT',T_ROOT.'model'.DIRECTORY_SEPARATOR);		//模型目录
define('T_CONFIG_ROOT',T_ROOT.'config'.DIRECTORY_SEPARATOR);	//config目录
//引入文件
require_once(T_CONFIG_ROOT.'Config.inc.php');					//数据库配置信息
require_once(T_LIB_ROOT.'Mysql.class.php');						//mysql操作类
require_once(T_LIB_ROOT.'DbManager.class.php');					//mysql操作类，继承自Mysql.class.php
require_once(T_MODEL_ROOT.'myModel.class.php');					//数据模型类
?>
