# <?php die(); ?>

## 注意：书写时，缩进不能使用 Tab，必须使用空格

##############################
# 数据库设置
##############################

# devel 模式
devel:
  driver:     mysql
  host:       127.0.0.1
  login:      root
  password:   root
  database:   cjnew_nbcustoms_db
  charset:    utf8
  prefix:

# deploy 模式
deploy:
  driver:     mysql
  host:       127.0.0.1
  login:      root
  password:   root
  database:   examapp_db
  charset:    utf8
  prefix:
 
#课程中心源
course_center:
  driver:     mysql
  host:       127.0.0.1
  login:      root
  password:   root
  database:   cjnew_nbcustoms_db
  charset:    utf8
  prefix:

#课程中心源
course_center_web:
  driver: mysql
  host: 183.136.236.76
  login: demo_cc_47
  password: FeLSQtXrfufDzSatdb
  database: cc_db
  charset: utf8
  prefix: