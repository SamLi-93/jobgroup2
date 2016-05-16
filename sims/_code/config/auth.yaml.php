#<?php die(); ?>

#############################
# 权限模块
#############################

#模块分组显示
#name 模块名称
#perms 模块权限 分成4个：1查看 2新增 3修改 4删除
#url 模块首页地址
#baselevel 此模块在指定管理员等级及以上有权限
#levels 数组，此模块只有指定等级的管理员有权限
#subbaselv 子功能在指定管理员等级及以上有权限

#-
#  _label: 基本信息管理
#  _group: 信息管理
#  
#  enroll:
#    name: 入学批次
#    perms: [1, 2, 3, 4]
#    url: enroll
#    subbaselv:
#      2: [1]
#      3: [1]
#      4: [1]
#  org:
#    name: 主考院校
#    perms: [1, 2, 3, 4]
#    url: org
#    subbaselv:
#      2: [1]
#      3: [2, 4]
#      4: [1]
#  org2:
#    name: 学习中心
#    perms: [1, 2, 3, 4]
#    url: org/index2
#    subbaselv:
#      2: [2, 4 ,6]
#      3: [2, 3, 4 ,6]
#      4: [2, 4 ,6]
#  discipline:
#    name: 专业设置
#    perms: [1, 2, 3, 4]
#    url: discipline
#    subbaselv:
#      2: [2, 4]
#      3: [2, 4]
#      4: [2, 4]
#  class:
#    name: 班级设置
#    perms: [1, 2, 3, 4]
#    url: class
#  coursetype:
#    name: 课程类型
#    perms: [1, 2, 3, 4]
#    url: coursetype
#    subbaselv:
#      2: [1, 2, 3, 4]
#      3: [1, 2, 3, 4]
#      4: [1, 2, 3, 4]
#  feetype:
#    name: 预收考试费类别
#    perms: [1, 2, 3, 4]
#    url: feetype
#    subbaselv:
#      2: [2,3,4,5]
#      3: [2,3,4,5]
#      4: [2,3,4,5]
#-
#  _label: 课程管理
#  _group: 信息管理

#  course:
#    name: 平台课程
#    perms: [1, 2, 3, 4]
#    url: course
#    subbaselv:
#      2: [1, 2, 3, 4]
#      3: [1, 2, 3, 4]
#      4: [1, 2, 3, 4]

#  platformcourse:
#    name: 课程管理
#    perms: [1, 2, 3, 4]
#    url: course/platformcourse
#    subbaselv:
#      2: [1, 2, 3, 4,6]
#      3: [1, 2, 3, 4,6]
#      4: [1, 2, 3, 4,6]
      
#  citems:
#    name: 课件管理
#    perms: [1, 2, 3, 4]
#    url: course/items
#    subbaselv:
#      2: [1, 2, 3, 4,6]
#      3: [1, 2, 3, 4,6]
#      4: [1, 2, 3, 4,6]
#  ctools:
#    name: 课件辅助工具
#    perms: [1, 2, 3, 4]
#    url: course/tools
#    subbaselv:
#      2: [1, 2, 3, 4,6]
#      3: [1, 2, 3, 4,6]
#      4: [1, 2, 3, 4,6]


#-
#  _label: 学生信息管理
#  _group: 信息管理
 
#  stubm:
#    name: 学生添加
#    perms: [2]
#    url: userbc/import
#  bmview:
#    name: 报名查看
#    perms: [1, 3, 4]
#    url: userbc
#  bmaudit:
#    name: 报名审核
#    perms: [1, 3]
#    url: userbc/slist
#    baselevel: [1, 2, 3, 4,5]
#  stu:
#    name: 学生管理
#    perms: [1, 3, 4]
#    url: users
#    subbaselv:
#      3: [2, 3,4,5,6]
#      4: [2, 3,4,5,6]
  #stuverify:
#    name: 系统确认
#    perms: [1, 3]
#    url: userbc/vlist
#    baselevel: [1]


#-
#  _label: 学习管理
#  planteach:
#    name: 教学计划
#    perms: [1, 2, 3, 4]
#    url: planteach
#  scorein:
#    name: 成绩录入
#    perms: [2]
#    url: score/import
#  scorerep:
#    name: 证书顶替
#    perms: [2,3,4]
#    url: score/replace
#    subbaselv:
#      2: [2, 3,4,5,6]
#      3: [2, 3,4,5,6]
#      4: [2, 3,4,5,6]
#  scoremk:
#    name: 免考免修
#    perms: [2]
#    url: score/miankao
#  score:
#    name: 成绩管理
#    perms: [1, 3, 4]
#    url: score
#    subbaselv:
#      3: [2,3, 4,5]
#      4: [2,3, 4,5]
#  learnprocess:
#    name: 学习过程
#    perms: [1]
#    url: learnprocess
#  itemsbug:
#    name: 课程纠错
#    perms: [1, 2, 3, 4]
#    url: itemsbug
#  coursecomments:
#    name: 课程评论
#    perms: [1, 2, 3, 4]
#    url: coursecomments

#  resource:
#    name: 资源管理
#    perms: [1, 2, 3, 4]
#    url: resource
#
#  talk:
#    name: 课堂讨论
#    perms: [1, 2, 3, 4]
#    url: talk
#    baselevel: [1]
#
#  quiz:
#    name: 问答管理
#    perms: [1, 2, 3, 4]
#    url: quiz/index
#  talk:
#    name: 课堂讨论
#    perms: [1, 2, 3, 4]
#    url: talk
#    baselevel: [1]  


#-
#  _label: 计划安排

#  planteach:
#    name: 教学计划
#    perms: [1, 2, 3, 4]
#    url: planteach
#  planscore:
#    name: 班级考试成绩
#    perms: [2]
#    url: planscore
#  planscoresearch:
#    name: 总成绩查询
#    perms: [2]
#    url: planscore/search
#  planarrange:
#    name: 考场安排
#    perms: [2]
#    url: planarrange
    
#-
#  _label: 过程性评价
#  _group: 系统设置
#  teaxjch:
#    name: 测评设置
#    url: inepconfig
#    perms: [1,2,4]
#  teascore:
#    name: 学习表现
#    perms: [1, 3]
#    url: teascore
#  totalscore:
#    name: 综合成绩
#    url: evaluating
#    perms: [1,2,4]
#  pcourse:
#    name: 申报表
#    url: evaluating/pcourse
#    perms: [1,2,4]
#  pcprocess:
#    name: 评价记录表
#    url: evaluating/pcprocess
#    perms: [1,2,4]

#-
#  _label: 学籍管理
#  stuxjch:
#    name: 学籍变更
#    perms: [2]
#    url: graduate
#  stugra:
#    name: 毕业学生
#    perms: [1]
#    url: leaveschool
#  stufin:
#    name: 结业学生
#    perms: [1, 3]
#    url: leaveschool/finish
#  stuback:
#    name: 退学学生
#    perms: [1]
#    url: leaveschool/back
#  stususpension:
#    name: 休学学生
#    perms: [1,3]
#    url: leaveschool/suspension
#  changezy:
#    name: 专业变更
#    perms: [1,2,3]
#    url: changezy


#-
#  _label: 题库管理
#  knowledge:
#    name: 知识点管理
#    perms: [1, 2, 3, 4]
#    url: knowledge
#  questiontype:
#    name: 题型管理
#    perms: [1, 2, 3, 4]
#    url: questiontype
#  questions:
#    name: 题库详情
#    perms: [1, 2, 3, 4]
#    url: questions/list
#  practicetemplate:
#    name: 阶段测验模板
#    perms: [1, 2, 3, 4]
#    url: practicetemplate
#  examtemplate:
#    name: 综合测验模板
#    perms: [1, 2, 3, 4]
#    url: examtemplate
#  coursetemplate:
#    name: 课程作业模板
#    perms: [1, 2, 3, 4]
#    url: coursetemplate
#  phaseemplate:
#    name: 预测模拟模板
#    perms: [1, 2, 3, 4]
#    url: phaseemplate
#  yearsemplate:
#    name: 历年真题模板
#    perms: [1, 2, 3, 4]
#    url: yearsemplate
#  stupage:
#    name: 考核课程
#    perms: [1, 2, 3, 4]
#    url: stupage



#-
#  _label: 自主选课
#
#  lccoursetype:
#    name: 选课分类
#    perms: [1, 2, 3, 4]
#    url: lccoursetype
#  coursemanagement:
#    name: 课程管理
#    perms: [1, 2, 3, 4]
#    url: coursemanagement
#  choosing:
#    name: 选课管理
#    perms: [1, 2, 3, 4]
#    url: choosing
#  dropcourse:
#    name: 退课管理
#    perms: [1, 2, 3, 4]
#    url: dropcourse

#-
#  _label: 学费管理
#  _group: 收费管理
#  feein:
#    name: 学费缴纳
#    perms: [1, 3, 4]
#    url: fee/import
#  feecx:
#    name: 学费查询
#    perms: [1, 3, 4]
#    url: fee
#  feedetailsh:
#    name: 学费审核
#    perms: [2,3]
#    url: report/feedetailsh
#  feeback:
#    name: 学费减免
#    perms: [1, 2,3]
#    url: fee/jlist
#    baselevel: [2,3, 4,5]
#  feeremain:
#    name: 欠费查询
#    perms: [1, 3]
#    url: fee/rlist
#    subbaselv:
#      3: [2,3, 4,5,6]
#  feepback:
#    name: 补缴查询
#    perms: [1]
#    url: fee/plist
#  feepb:
#    name: 退款查询
#    perms: [1]
#    url: fee/pblist
#  report:
#    name: 学费上报
#    perms: [1,2,3,4]
#    url: report/index
#-
#  _label: 学费统计
#  _group: 收费管理
  
#  feestattotal:
#    name: 费用汇总统计
#    perms: [1,2,3,4]
#    url: feestat/index
#  feestatdetail:
#    name: 学费明细
#    perms: [1,2,3,4]
#    url: feestat/detail
#-
#  _label: 预收考试费管理
#  _group: 收费管理
#  
#  feedgin:
#    name: 预收考试费缴纳
#    perms: [2]
#    url: feeincome/import
#    baselevel: [1, 2, 3, 4,6] 
#  feeincome:
#    name: 预收考试费管理
#    perms: [1, 2, 3, 4]
#    url: feeincome
#    subbaselv:
#      3: [1,2,3,4,5,6]
#      4: [1,2,3,4,5,6]
#  dgremain:
#    name: 欠费管理
#    perms: [1, 2, 3, 4]
#    url: feedg/remain
#    subbaselv:
#
#      3: [1,2,3,4,5,6]
#      4: [1,2,3,4,5,6]
#  feedg:
#    name: 预收考试费支出
#    perms: [1, 2, 3, 4]
#    url: feedg
#    subbaselv:
#      3: [1,2,3,4,5,6]
#      4: [1,2,3,4,5,6] 
#  feedgstats:
#    name: 预收考试费统计
#    perms: [1, 2, 3, 4]
#    url: feedg/feedgstats
#    subbaselv:
#      3: [1,2,3,4,5,6]
#      4: [1,2,3,4,5,6]
#
#  feedgstatsclass:
#    name: 预收考试费按班级统计
#    perms: [1, 2, 3,4]
#    url: feedg/feedgstatsclass
#    subbaselv:
#      3: [1,2,3,4,5,6]
#      4: [1,2,3,4,5,6]

#-
#  _label: 统计查询

#  stat1:
#    name: 在读学生统计
#    perms: [1]
#    url: stat/stu
#  stat3:
#    name: 在读学生课程通过率
#    perms: [1]
#    url: stat/upass
#  stat4:
#    name: 课程未过人数统计
#    perms: [1]
#    url: stat/unpass
#  stat5:
#    name: 班级课程通过统计
#    perms: [1]
#    url: stat/cpass
#  stat2:
#    name: 毕业学生通过率
#    perms: [1]
#    url: stat/gpass

#-
#  _label: 消息中心
#  _group: 系统设置
#  teaxjch:
#    name: 站内消息
#    url: message/mymessage
#    perms: [1,2,4]
#  teagra:
#    name: 系统公告
#    url: message/sysmsg
#    perms: [1,2,4]
#  teafin:
#    name: 通知提醒
#    url: message/notif
#    perms: [1,2,4]

#-
#  _label: 系统设置
#  _group: 系统设置

  
#  message:
#    name: 站内消息
#    perms: [1, 2, 4]
#    url: message/mymessage
  #mail:
  #  name: 邮件通知
  #  perms: [2]
  #  url: mail
  #sms:
  #  name: 短信通知
  #  perms: [2]
  #  url: msglist
  #profile:
  #  name: 我的账户
  #  perms: [3]
  #  url: admin/profile
  #admin:
  #  name: 系统账号
  #  perms: [1, 2, 3, 4]
  #  url: admin
  #  baselevel: [1, 4]
#  log:
#    name: 操作日志
#    perms: [1]
#    url: log
#    baselevel: [1, 2, 3, 4]
#  help:
#    name: 使用帮助
#    perms: []
#    url: help
#    baselevel: []
#  move:
#    name: 数据迁移
#    perms: [1,2,3,4]
#    url: move
#    baselevel: [1]

#-
#  _label: 统计管理

#  singlerank:
#      name: 单次活动摇动总次数排名
#      perms: [1, 2, 3, 4]
#      url: singlerank
#  news:
#      name: 资源管理
#      perms: [1, 2, 3, 4]
#      url: newsadmin

-
  _label: 基础管理

  activity:
      name: 活动管理
      perms: [1, 2, 3, 4]
      url: activity
  grade:
      name: 奖项管理
      perms: [1, 2, 3, 4]
      url: grade
  personnel:
      name: 人员管理
      perms: [1, 2, 3, 4]
      url: personnel
  jackpot:
      name: 中奖人员管理
      perms: [1, 2, 3, 4]
      url: jackpot

-
  _label: 统计管理

  singlerank:
      name: 单次活动摇动总次数排名
      perms: [1, 2, 3, 4]
      url: singlerank
  jackpotrank:
      name: 中奖人员摇动次数排名
      perms: [1, 2, 3, 4]
      url: jackpotrank
  graderank:
      name: 各奖项摇动次数排名
      perms: [1, 2, 3, 4]
      url: graderank
  personnelrank:
      name: 各奖项摇动人数统计
      perms: [1, 2, 3, 4]
      url: personnelrank