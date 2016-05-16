<?php
/*
 ###########################################################################
 #
 #        Zhejiang Job Educational Technology Company
 #
 ###########################################################################
 #
 #  Filename: userbc_controller.php
 #
 #  Description:
 #
 #
 ###########################################################################
 #
 #    R E V I S I O N   L O G
 #
 #    Date       Name            Description
 #    --------   --------------- -------------------------------
 #    2011/7/21   Gu wen         Created.
 #
 ###########################################################################
 */


/**
 * Admin 封装来自 sms_admin 数据表的记录及领域逻辑
 */
class Admin extends QDB_ActiveRecord_Abstract
{

    /**
     * 返回对象的定义
     *
     * @static
     *
     * @return array
     */
    static function __define()
    {
        return array
        (
            // 指定该 ActiveRecord 要使用的行为插件
            'behaviors' => 'acluser',

            // 指定行为插件的配置
            'behaviors_settings' => array
            (
                # '插件名' => array('选项' => 设置),
                'acluser' => array(
            		'username_prop' => 'username',
            		'password_prop' => 'password',
                    'acl_data_props' => 'id,username,name,level,orgid,mclassids,perms,fpower,perms,upload,audit,copen,empower',
            		'encode_type' => 'md5',
                ),
            ),

            // 用什么数据表保存对象
            'table_name' => 'sms_admin',

            // 指定数据表记录字段与对象属性之间的映射关系
            // 没有在此处指定的属性，QeePHP 会自动设置将属性映射为对象的可读写属性
            'props' => array
            (
                // 主键应该是只读，确保领域对象的“不变量”
                'id' => array('readonly' => true),

                /**
                 *  可以在此添加其他属性的设置
                 */
                # 'other_prop' => array('readonly' => true),

                /**
                 * 添加对象间的关联
                 */
                # 'other' => array('has_one' => 'Class'),
                'auths' => array(
                    'has_many' => 'Auth',
                    'target_key' => 'admin_id',
                ),
	   
                /**
                 * getter
                 */
                'orgname' => array('getter' => 'get_org'),
                'perms' => array('getter' => 'get_perms'),
				'level_name' => array('getter' => 'get_level'), //级别
                'gender_name' => array('getter' => 'get_gender_name'), //性别
                'can_delete' => array('getter' => 'get_delete'),
                
            ),

            /**
             * 允许使用 mass-assignment 方式赋值的属性
             *
             * 如果指定了 attr_accessible，则忽略 attr_protected 的设置。
             */
            'attr_accessible' => '',

            /**
             * 拒绝使用 mass-assignment 方式赋值的属性
             */
            'attr_protected' => 'id',

            /**
             * 指定在数据库中创建对象时，哪些属性的值不允许由外部提供
             *
             * 这里指定的属性会在创建记录时被过滤掉，从而让数据库自行填充值。
             */
            'create_reject' => '',

            /**
             * 指定更新数据库中的对象时，哪些属性的值不允许由外部提供
             */
            'update_reject' => '',

            /**
             * 指定在数据库中创建对象时，哪些属性的值由下面指定的内容进行覆盖
             *
             * 如果填充值为 self::AUTOFILL_TIMESTAMP 或 self::AUTOFILL_DATETIME，
             * 则会根据属性的类型来自动填充当前时间（整数或字符串）。
             *
             * 如果填充值为一个数组，则假定为 callback 方法。
             */
            'create_autofill' => array
            (
                # 属性名 => 填充值
                # 'is_locked' => 0,
            ),

            /**
             * 指定更新数据库中的对象时，哪些属性的值由下面指定的内容进行覆盖
             *
             * 填充值的指定规则同 create_autofill
             */
            'update_autofill' => array
            (
            ),

            /**
             * 在保存对象时，会按照下面指定的验证规则进行验证。验证失败会抛出异常。
             *
             * 除了在保存时自动验证，还可以通过对象的 ::meta()->validate() 方法对数组数据进行验证。
             *
             * 如果需要添加一个自定义验证，应该写成
             *
             * 'title' => array(
             *        array(array(__CLASS__, 'checkTitle'), '标题不能为空'),
             * )
             *
             * 然后在该类中添加 checkTitle() 方法。函数原型如下：
             *
             * static function checkTitle($title)
             *
             * 该方法返回 true 表示通过验证。
             */
            'validations' => array
            (
                'username' => array
                (
                    array('max_length', 30, '登录账号不能超过 30 个字符'),
					array('not_empty','登录账号不能为空')
                ),

                'name' => array
                (
                    array('max_length', 30, '姓名不能超过 30 个字符'),
					array('not_empty','姓名不能为空')
                ),

                'gender' => array
                (
                    array('is_int', '性别不能为空'),

                ),

                'level' => array
                (
                    array('is_int', '级别不能为空'),
					array('greater_than',0,'级别不能为空')
                ),

                'orgname' => array
                (
                    array('max_length', 255, '来源姓名不能超过 255 个字符'),

                ),

                'power' => array
                (
                   
					array('skip_empty')
                ),

                'update_id' => array
                (
                    array('is_int', 'update_id必须是一个整数'),

                ),

                'update_date' => array
                (
                    array('is_int', 'update_date必须是一个整数'),

                ),
            ),
        );
    }


/* ------------------ 以下是自动生成的代码，不能修改 ------------------ */

    /**
     * 开启一个查询，查找符合条件的对象或对象集合
     *
     * @static
     *
     * @return QDB_Select
     */
    static function find()
    {
        $args = func_get_args();
        return QDB_ActiveRecord_Meta::instance(__CLASS__)->findByArgs($args);
    }

    /**
     * 返回当前 ActiveRecord 类的元数据对象
     *
     * @static
     *
     * @return QDB_ActiveRecord_Meta
     */
    static function meta()
    {
        return QDB_ActiveRecord_Meta::instance(__CLASS__);
    }


/* ------------------ 以上是自动生成的代码，不能修改 ------------------ */
    
	function get_level() {
        $level = Q::ini('appini/admin_levels');
        return $level[$this->level];
    }
	function get_gender_name() {
		$genders = Q::ini('appini/genders');
		if ($this->gender===NULL) return '';
        return $genders[$this->gender];
    }
    function get_valid(){
    	$valid=array('无效','有效');
    	return $valid[$this->valid];
    }
    function get_org(){
    	if($this->level==1){
    		$orgname="吉博教育";
    		return $orgname;
        }else if($this->level==5){
            $id=$this->mclassids;
			if (!$id) return '';
            $orgname=Classinfo::find("id in ($id)")->getAll()->values('name');
            return implode('，', $orgname);
        }else{
            $id=$this->orgid;
            $orgname=Org::find()->getById($id);
            return $orgname->name;
    	}
    }
    
    //获得权限（从原始数据库读取）
    function get_perms() {
        $auths = $this->auths;
        $perms = array();
        foreach ($auths as $auth) {
            if ($auth->perms == '') continue;
            $perms[$auth->app] = explode(',', $auth->perms);
        }
        return $perms;
    }

    //获得所有权限
    public static function get_my_perms() {
        $app = Q::registry('app');
        $user = $app->currentUser();
        $my_perms = $user['perms'];
        return $my_perms;
    }

    //判断是否有权限
    /*
     * @perm 权限类型 格式为 模块名.权限 权限1查看 2新增 3修改 4删除
     */
    public static function has_perm($perm) {
        $app = Q::registry('app');
        $user = $app->currentUser();
        if (!$user) return false;
		$adminlevel = !defined('__LMS__') ? 1 : 0; //LMS中超管为0
		if ($user['level'] == $adminlevel) return true; //超管
        $my_perms = $user['perms'];
        $parr = explode('.', $perm);
        $module = $parr[0];
        if (empty($my_perms[$module])) return false;
        if (isset($parr[1])) {
            if (!in_array($parr[1], $my_perms[$module])) return false;
        }
        return true;
    }

    //判断是否对模块组有权限
    public static function has_mg_perm($mg, $all_perms=null) {
        $app = Q::registry('app');
        $user = $app->currentUser();
		$adminlevel = !defined('__LMS__') ? 1 : 0; //LMS中超管为0
		if ($user['level'] == $adminlevel) return true; //超管
        if (!$all_perms) {
            $all_perms = self::get_all_perms();
        }
        $group = $all_perms[$mg];
        foreach ($group as $k=>$module) {
            if ($k == '_lable') continue;
            if (self::has_perm($k)) {
                return true;
            }
        }
        return false;
    }

    public static function get_all_perms() {
        $filename = Q::ini('app_config/CONFIG_DIR').'/auth.yaml.php';
        $all_perms = Helper_YAML::loadCached($filename);
        return $all_perms;
    }

	protected function _after_update() {
		$authname="admin";
		if ($this->changed()) {
			if (!$this->isdelete) {
				$isprofile = QContext::instance()->isprofile;
				if ($isprofile)
					Log::addlog(1, __CLASS__, $this->id(), $this, '修改我的账户',NULL,'profile');
				else
					Log::addlog(1, __CLASS__, $this->id(), $this, '修改账号：'.$this->name,NULL,$authname);
			} else {
				Log::addlog(2, __CLASS__, $this->id(), $this, '删除账号：'.$this->name,NULL,$authname);
			}
		}
	}

}

