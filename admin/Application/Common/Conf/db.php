<?php
	
	$returnConfig = array();
	
	$offlineConfig=array(

		//默认库
		'DB_MYSQL_DEFAULT'=>array(
			'DB_TYPE'               =>  'mysql',     // 数据库类型
			'DB_HOST'               =>  '127.0.0.1', // 服务器地址
			'DB_NAME'               =>  'db_cheerdevops',          // 数据库名
			'DB_USER'               =>  'root',      // 用户名
			'DB_PWD'                =>  '',          // 密码
			'DB_PORT'               =>  '3306',        // 端口
			'DB_PREFIX'             =>  't_',    // 数据库表前缀
		),
		
		//云效平台库
		'DB_MYSQL_DEV'=>array(
			'DB_TYPE'               =>  'mysql',     // 数据库类型
			'DB_HOST'               =>  '', // 服务器地址
			'DB_NAME'               =>  'db_erp',          // 数据库名
			'DB_USER'               =>  'root',      // 用户名
			'DB_PWD'                =>  '',          // 密码
			'DB_PORT'               =>  '3306',        // 端口
			'DB_PREFIX'             =>  't_',    // 数据库表前缀
		),
	
	
		//默认redis
		'REDIS_DEFAULT'=>array(
			'REDIS_HOST'=>'redis.in.myspacex.cn',
			'REDIS_PORT'=>16740,
			'REDIS_DB'=>1,
			'REDIS_PASSWORD'=>'myspacex_redis_!@#$'
		),
		
		'ACCOUNT_AUTH'=>array(
		    'TYPE'=>'DEV',
		    'LOGIN_URL'=>'',
	     ),

	);

	$onlineConfig=array(

		//默认库
		'DB_MYSQL_DEFAULT'=>array(
			'DB_TYPE'               =>  'mysql',     // 数据库类型
			'DB_HOST'               =>  '', // 服务器地址
			'DB_NAME'               =>  'db_cheerdevops',          // 数据库名
			'DB_USER'               =>  'root',      // 用户名
			'DB_PWD'                =>  '',          // 密码
			'DB_PORT'               =>  '3306',        // 端口
			'DB_PREFIX'             =>  't_',    // 数据库表前缀
		),
	
		//云效平台库
		'DB_MYSQL_DEV'=>array(
			'DB_TYPE'               =>  'mysql',     // 数据库类型
			'DB_HOST'               =>  '', // 服务器地址
			'DB_NAME'               =>  'db_erp',          // 数据库名
			'DB_USER'               =>  'root',      // 用户名
			'DB_PWD'                =>  '',          // 密码
			'DB_PORT'               =>  '3306',        // 端口
			'DB_PREFIX'             =>  't_',    // 数据库表前缀
		),
	
		  //默认redis
		'REDIS_DEFAULT'=>array(
			'REDIS_HOST'=>'127.0.0.1',
			'REDIS_PORT'=>6379,
			'REDIS_DB'=>1,
			'REDIS_PASSWORD'=>''
		),
		
		'ACCOUNT_AUTH'=>array(
		    'TYPE'=>'DEV',
		    'LOGIN_URL'=>'',
	     ),

	);
	
	
	
	if(in_local_mode())
	{
		$returnConfig=$offlineConfig;
	}
	else
	{
		$returnConfig=$onlineConfig;
	}

	return $returnConfig;