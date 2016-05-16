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
 * Users 封装来自 core_users 数据表的记录及领域逻辑
 */
class Users extends QDB_ActiveRecord_Abstract
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
            'behaviors' => '',

            // 指定行为插件的配置
            'behaviors_settings' => array
            (
                # '插件名' => array('选项' => 设置),
            ),

            // 用什么数据表保存对象
            'table_name' => 'core_user',

            // 指定数据表记录字段与对象属性之间的映射关系
            // 没有在此处指定的属性，QeePHP 会自动设置将属性映射为对象的可读写属性
            'props' => array
            (
                // 主键应该是只读，确保领域对象的“不变量”
                'idst' => array('readonly' => true),

                /**
                 *  可以在此添加其他属性的设置
                 */
                # 'other_prop' => array('readonly' => true),

                /**
                 * 添加对象间的关联
                 */
                # 'other' => array('has_one' => 'Class'),
                'userbc' => array(
                    'belongs_to' => 'Userbc',
                    'source_key' => 'userid',
            		'target_key' => 'userid'
                ),
                
            	'enroll' => array(
            		'belongs_to' => 'Enroll',
            		'source_key' => 'enroll_id',
            	),
            	
            	'orgedu' => array(
            		'belongs_to' => 'Org',
            		'source_key' => 'college_id',
            	),
            	
            	'orglen' => array(
            		'belongs_to' => 'Org',
            		'source_key' => 'training_id',
            	),
            	
            	'classinfo' => array(
            		'belongs_to' => 'Classinfo',
            		'source_key' => 'classid',
            	),
            	
            	'discipline' => array(
            		'belongs_to' => 'Discipline',
            		'source_key' => 'discipline_id',
            	),
            	
            	'fee' => array(
                    'has_one' => 'Fee',
            		'source_key' => 'userid',
                    'target_key' => 'userid',
                ),

                'courses' => array(
                    'many_to_many' => 'Course',
                    'source_key' => 'idst',
                    'mid_table_name' => 'lc_courseuser',
                    'mid_source_key' => 'userid',
                    'mid_target_key' => 'courseid',
                ),

                'user_course' => array(
                    'has_many' => 'Usercourse',
                    'source_key' => 'idst',
                    'target_key' => 'userid',
                ),
            	
            	'courseuserdb' => array(
                    'has_many' => 'Courseuserdb',
            		'source_key' => 'idst',
                    'target_key' => 'idUser',
                ),
            	'Courserollback' => array(
                    'has_many' => 'Courserollback',
            		'source_key' => 'idst',
                    'target_key' => 'idUser',
                ),
            	'courserollbacknum' => array(
                    'has_many' => 'Courserollback',
            		'source_key' => 'idst',
                    'target_key' => 'idUser',
					'on_find_where' => 'status=3',
                ),
            	
            	/*
            	'scores' => array(
            		'has_many' => 'Score',
            		'source_key' => 'userid',
            		'target_key' => 'userid',
            	),*/
            	'allrbnum' => array('getter' => 'get_allrbnum'), //退课情况
            	'courseshow' => array('getter' => 'get_courseshow'), //默认学习中心选课数据
				
            	'gender_name' => array('getter' => 'get_gender_name'), //性别
            	
            	'stu_status' => array('getter' => 'get_stu_status'), //学生状态
                
                'remain' => array('getter' => 'get_remain'),
                
                'totalfee' => array('getter' => 'get_totalfee'),
                'enroll_name' => array('getter' => 'get_enroll_name'),
                'college_name' => array('getter' => 'get_college_name'),
                'training_name' => array('getter' => 'get_training_name'),
                'discipline_name' => array('getter' => 'get_discipline_name'),
                'class_name' => array('getter' => 'get_class_name'),
				'hasdegree_t' => array('getter' => 'get_hasdegree'),
                'politic_name' => array('getter' => 'get_politic_name'),//政治面貌
                'edu_name' => array('getter' => 'get_edu_name'),//文化程度
				/****以下变量，为成绩管理模块服务。2014-08-05*****************************************************************************/
				'unpass_number' => array('getter' => 'get_unpass_number'),	//得到未通过的课程门数
				'pass_number'	=> array('getter' => 'get_pass_number'),	//得到通过的课程门数
				'finish_flag'	=> array('getter' => 'get_finish_flag'),	//得到毕业状态
				/*********************************************************************************/

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
            'attr_protected' => 'idst',

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
    
	function get_gender_name() {
        $genders = Q::ini('appini/genders');
        if (empty($genders[$this->gender])) return '';
        return $genders[$this->gender];
    }
    
    function get_stu_status(){
    	$names = array(
    		-1 => '冻结账号',
            0 => '审核通过',
            1 => '系统确认'
        );
        return $names[$this->valid];
    }
    
    function get_remain() {
    	if (!$this->fee->id()) return -1;
    	return $this->fee->remain;
    }
    
    function get_totalfee() {
    	if (!$this->fee->totalfee) return -1;
    	return $this->fee->totalfee;
    }
    
    function notpassAll()
    {
    	$arr = $this->user_course;
    	foreach ($arr as $i => $course) {
            $info = Course::find("id=?",$course->courseid)->getOne();
            if(!$info->isdelete){
                $status = Score::find('userid=?&&courseid=?&&passed=1',$this->userid,$course->courseid)->getOne();
                if ($status->id) continue; 
                else return 1;  //1为没有通过全部课程
            }
    	}
    	return 2; 
    }
    function get_enroll_name() {
        return $this->enroll->name;
    }

    function get_politic_name() {
        $info = array('0'=>'未选','1'=>'群众','2'=>'团员','3'=>'党员','4'=>'预备党员');
        if(in_array($this->politic_status, $info)){
            return $info[$this->politic_status];
        }else{
            //return "未知";
            return $this->politic_status;
        }
    }

    function get_edu_name() {
        $info = array('0'=>'未选','1'=>'初中以下','2'=>'初中','3'=>'高中或中专中技','4'=>'大专','5'=>'本科','6'=>'本科以上');
        return $info[$this->education];
    }

    function get_college_name() {
        return $this->orgedu->name;
    }

    function get_training_name() {
        return $this->orglen->name;
    }

    function get_discipline_name() {
        return $this->discipline->name;
    }

    function get_class_name() {
        return $this->classinfo->name;
    }

    function get_hasdegree() {
    	$info = array(0=>'无',1=>'有',2=>'未知');
        return $info[$this->hasdegree];
    }
    protected $old_valid;
    protected function _after_initialize() {
        $this->old_valid = $this->valid;
    }
    public $save_user = true;
    public $save_log = true;
	protected function _after_update() {
		if ($this->changed() && $this->save_log) {
			$other_data = array('user_course', $this->user_course);
			if($this->old_valid != $this->valid){
				$authname="stuverify";
				
			}else{
				$authname="stu";
			}
			Log::addlog(1, __CLASS__, $this->id(), $this, '修改学生：'.$this->name, $other_data,$authname);
		}
		if($this->save_user){
			$this->update_userbc();
		}
    }
    
    
    function update_userbc() {
		$users = $this->userbc;
    	if($users->id()){
			$users->save_user = false;
    		$users->name = $this->name;
			$users->age = $this->age;
			$users->gender = $this->gender;
			$users->cid = $this->cid;
			$users->eid = $this->eid;
			$users->stuno = $this->stuno;
			$users->email = $this->email;
			$users->mobile = $this->mobile;
			$users->phone = $this->phone;
			$users->address = $this->address;
			$users->postcode = $this->postcode;
			$users->hasdegree = $this->hasdegree;
			$users->corpname = $this->corpname;
			$users->enroll_id = $this->enroll_id;
			$users->college_id = $this->college_id;
			$users->training_id = $this->training_id;
			$users->discipline_id = $this->discipline_id;
			$users->classid = $this->classid;
			$users->dazhuan = $this->dazhuan;
			$users->hometown = $this->hometown;
            //后加的民族、出生年月、政治面貌、学历

            $users->nation = $this->nation;
            $users->birth = $this->birth;
            $users->education = $this->education;
            $users->politic_status = $this->politic_status;
			$users->save();
    	}
    }

    function _after_destroy() {
		if ($this->save_log) {
			Log::addlog(2, __CLASS__, $this->id(), $this, '删除学生：'.$this->name,null,'stu');
		}
        if ($this->save_user && $this->userbc->id()) {
        	$sql = "update core_user_before_check set status=3,userid='__".time()."_".$this->userid."' where userid = '".$this->userid."'";//生成idst 获取并返回
			$db = QDB::getConn();
			$db->execute($sql);

			$sql = "delete from sms_user_course where userid = '".$this->userid."'";//生成idst 获取并返回
			$db->execute($sql);

            $sql = "delete from lc_courseuser where userid = '".$this->idst."'";//生成idst 获取并返回

            $db->execute($sql);

			$sql = "delete from sms_score where userid = '".$this->userid."'";//生成idst 获取并返回
			$db->execute($sql);

			$sql = "delete from sms_fee where userid = '".$this->userid."'";//生成idst 获取并返回
			$db->execute($sql);

			$sql = "delete from sms_fee_detail  where userid = '".$this->userid."'";//生成idst 获取并返回
			$db->execute($sql);		
		
        }
    }
	
	
	function get_courseshow(){
	
		$listdb = Courseshow::find("college_id='$this->college_id' and training_id='$this->training_id' and discipline_id='$this->discipline_id'")->getOne();
		return $listdb;
		
	
	}
	
	function get_allrbnum(){
		$idUser = $this->idst;
		$returndb=array();
		$listdb = Courserollback::find("iduser='$idUser'")->getAll();
		foreach($listdb as $key=>$row){
		$returndb[$row['status']][] = $row;
		}
		return $returndb;
	}
	
	
   static function iscid($data){
    	$len = strlen($data);
    	if($len==15||$len==18){
    		if($len==15){
    			$preg="/^[1-9]\d{7}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])((\d{3})|(\d{2}[x])|(\d{2}[X]))$/";
    			if(preg_match($preg,$data)){
    				return true ;
    			}else{
    				return false;
    			}
    		}else if($len==18){
    			$preg="/^[1-9]\d{5}[1-9]\d{3}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])((\d{4})|(\d{3}[x])|(\d{3}[X]))$/";
    			
    			if(preg_match($preg,$data)){
    				
    				return true;
    			}else{
    				return false;
    			}
    		}
    	 }else{
    	  	return false;
    	 }
    }
	function get_unpass_number(){
		$all_course_number = Usercourse::find("userid = '".$this->idst."'")->getCount();
		return $all_course_number - $this->pass_number;
	}
	function get_pass_number(){
		return Score::find("userid = '".$this->userid."' and passed = 1 ")->group('courseid')->getCount();
	}
	function get_finish_flag(){
		$return_value = '';
		if($this->finish == 1){
			$return_value = '已结业';
		}else{
			if($this->unpass_number == 0){
				$return_value = '可毕业';
			}else{
				$return_value = '在学习';
			}
		}
		return $return_value;
	}    
}

