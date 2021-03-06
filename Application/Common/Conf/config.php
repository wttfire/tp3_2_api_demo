<?php
return array(
	//'配置项'=>'配置值'
	'DB_TYPE'   => 'mysql', // 数据库类型

	'DB_HOST'   => 'localhost', // 服务器地址
	'DB_NAME'   => 'db_yx', // 数据库名
	'DB_USER'   => 'root', // 用户名
	'DB_PWD'    => 'root', // 密码
    
    'DB_PORT'   => 3306, // 端口
    'DB_PREFIX' => 'yx_', // 数据库表前缀 
    'DB_CHARSET'=> 'utf8', // 字符集
    'DB_DEBUG'  =>  TRUE, // 数据库调试模式 开启后可以记录SQL日志 3.2.3新增
    
	'MULTI_MODULE'            =>  true, // 是否允许多模块 如果为false 则必须设置 
    'MODULE_ALLOW_LIST'     =>  array('Home','Yxbackstage','Engineer','Partner','Api','Store'),//模块管理
    'URL_MODEL' => 2,
    'URL_CASE_INSENSITIVE'  =>  false,//开启大小写
    // 'DEFAULT_MODULE'        =>  'Admin',  // 默认模块
    // 'DEFAULT_CONTROLLER'    =>  'Index', // 默认控制器名称
    // 'DEFAULT_ACTION'        =>  'index', // 默认操作名称
);