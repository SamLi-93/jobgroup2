# <?php die(); ?>

## 注意：书写时，缩进不能使用 Tab，必须使用空格。并且各条访问规则之间不能留有空行。

#############################
# 访问规则
#############################

# 访问规则示例
#模块分类在auth.yaml.php中
#模块权限 分成4个：1查看 2新增 3修改 4删除
# 如：create: enroll.2  表示create动作需要enroll模块的新增权限
# _allow表示整个controller的默认权限


users:
  index: stu
  export: stu
  view: stu
  edit: stu.3
  delete: stu.4
  dongjie: stu.3 

admin:
  profile: profile
  index: admin
  create: admin.2
  edit: admin.3
  auth: admin.3
  delete: admin.4

