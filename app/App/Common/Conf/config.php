<?php

//vision  1.线上只读库 2.201 3.staging 4.localhost
$vision = 4;

if ($vision == 1) {//读写分离版的*
    $dbHost="192.168.1.201,rr-m5e0pv62m8jk57mkc.mysql.rds.aliyuncs.com";
    $dbUser="root,qiaosuan";
    $dbPwd="qwbcgserver,4DszV34xfsqIsOfi19lj0";
} else if ($vision == 2) {//普通版的
    $dbHost="192.168.1.201";
    $dbUser="root";
    $dbPwd="qwbcgserver";
}  else if ($vision == 3) {//staging版
    $dbHost="qiaosuan-staging-db-inter.mysql.rds.aliyuncs.com";
    $dbUser="qiaosuan";
    $dbPwd="4Dfd8FaDsV3IO1j0";
}   else if ($vision == 4) {//localhost版的
    $dbHost="localhost";
    $dbUser="root";
    $dbPwd="root";
}

$dbPort=3306;
$dbName = "free";
$dbPre = 'free_';


return array(
	//'配置项'=>'配置值'
	//读写分离设置
	'DB_DEPLOY_TYPE' => 1, 
	'DB_RW_SEPARATE'=>true,

    //
    'MAX_PLATFORM' => 40,

    //禁止打散
    'NO_DASAN' => true,

	//session过期设置
	'SESSION_OPTIONS'         =>  array(
        'name'                =>  'Qs_SESSION',                    //设置session名
        'expire'              =>  2*86400,                      //SESSION保存2天
        'use_trans_sid'       =>  1,                               //跨页传递
        'use_only_cookies'    =>  0,                               //是否只开启基于cookies的session的会话方式
    ),

    //redis配置
    'REDIS_HOST' => '127.0.0.1',
    'REDIS_PWD' => 'wIo3v7bALpsDz7x37Vf2qH8',

    //主数据库配置
    'priDbHost' => 'rdsira3afuieii2.mysql.rds.aliyuncs.com',
    'priDbPw' => '4DszV34xfsqIsOfi19lj0',

    //日报数据库配置
    //'RB_DB_CONFIG' => 'mysql://qiaosuan_rb:dVszM4xsDqssOki1lj273@qiaosuan-staging-db-inter.mysql.rds.aliyuncs.com:3306/qiaosuan_rb',    //staging版的
    'RB_DB_CONFIG' => 'mysql://root:root@localhost:3306/qiaosuan_rb',    //localhost版的

	'URL_CASE_INSENSITIVE'  =>  false,
	'DB_TYPE' => 'mysql',
	'DB_HOST' => $dbHost,
	'DB_PORT' => $dbPort,
	'DB_NAME' => $dbName,
	'DB_USER' => $dbUser,
	'DB_PWD' => $dbPwd,
	'DB_PREFIX' => $dbPre,
	'DB_CHARSET'=> 'utf8', // 字符集

	'VAR_MODULE'            =>  'app',     // 默认模块获取变量
	'VAR_CONTROLLER'        =>  'mod',    // 默认控制器获取变量
	'VAR_ACTION'            =>  'act',    // 默认操作获取变量
		
	/* 数据缓存设置 */
	'DATA_CACHE_TIME'       =>  0,      // 数据缓存有效期 0表示永久缓存
	'DATA_CACHE_COMPRESS'   =>  true,   // 数据缓存是否压缩缓存
	'DATA_CACHE_CHECK'      =>  true,   // 数据缓存是否校验缓存
	'DATA_CACHE_PREFIX'     =>  '',     // 缓存前缀
	'DATA_CACHE_TYPE'       =>  'Memcache',  // 数据缓存类型,支持:File|Db|Apc|Memcache|Shmop|Sqlite|Xcache|Apachenote|Eaccelerator
	/*'MEMCACHE_HOST'   =>  'tcp://127.0.0.1:11211',
	'DATA_CACHE_TIME' => '3600',*/
	'DATA_CACHE_PATH'       =>  TEMP_PATH,// 缓存路径设置 (仅对File方式缓存有效)
	'DATA_CACHE_SUBDIR'     =>  false,    // 使用子目录缓存 (自动根据缓存标识的哈希创建子目录)
	'DATA_PATH_LEVEL'       =>  1,        // 子目录缓存级别
	
	'MEMCACHED_SERVER'   =>'localhost',
	'MEMCACHED_PORT'=>11211,
	'MEMCACHED_USER'   => 'qgzs',
	'MEMCACHED_PASSWD'   => 'qgzs',
		
		
	'URL_MODE' => 1,
	'DEFAULT_M_LAYER'=>'Logic',  //Mobile应用下面默认M为Logic  (可选值为Model、Logic、Service)
	
	'USER_SIGN_KEY'=>'qiaos_user',    //user_sign 秘钥
	
	'avatar_host'=>'http://assets.qwbcg.mobi',
	'tag_host'=>'http://assets0.qwbcg.mobi',
	'shop_logo_host'=>'http://assets0.qwbcg.mobi',
	'channel_icon_host'=>'http://assets0.qwbcg.mobi',
	'download_avatar_api'=>'http://assets.qwbcg.mobi/download.php',  //下载头像图片 服务器接口
	'get_avatar_api'=>'http://assets.qwbcg.mobi/getAvatar.php',  //获取头像图片 服务器接口
	
	'machine_env'=>'local',  //用户判断是线上服务器还是测试服务器

    //其他服务配置
    'IMAGES_SERVER_HOST'    =>  'images.staging.caihongip.com',
    'DOWNLOAD_SERVER_HOST'  =>  'download.staging.caihongip.com',
    'ASSETS_PORTAL_HOST'    =>  'portal-intra.assets.staging.caihongip.com:8081'
);