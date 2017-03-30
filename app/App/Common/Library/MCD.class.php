<?php
namespace Common\Library;
use Common\Library\CacheConf as CacheConf;


/**
 * Memcached Class
 */
class MCD {
	/**
	 * memcache句柄
	 * @var resource
	 */
	public static $handler;
	
	/**
	 * 连接memcache
	 */
	public function connect(){  
 		/*$handler= new \Think\Cache\Driver\Memcached();//声明一个新的memcached链接
 		$handler->setOption(\Memcached::OPT_COMPRESSION, false); //关闭压缩功能
 		$handler->setOption(\Memcached::OPT_BINARY_PROTOCOL, true); //使用binary二进制协议
 		$handler->addServer(C('127.0.0.1'),C('11211')); //MEMCACHED_USER MEMCACHED_PASSWD
 		//添加OCS实例地址及端口号
 		//$handler->setSaslAuthData(C('qgzs'),C('qgzs')); //设置OCS帐号密码进行鉴权
 		return $handler;*/
	}
	
	/**
	 * 取得缓存类实例
	 * @static
	 * @access public
	 * @return mixed
	 */
	public static function getInstance(){
		if(!(self::$handler instanceof self)){
			$single=new MCD();
			self::$handler=$single->connect(); 
		}
		return $single;
	} 
	
	/**
	 * 设置
	 * @param string $key
	 * @param string $value
	 * @param int $time
	 */
	public function set($key,$value,$time=0){
 		/*$key=CacheConf::PREFIX.$key;
 		return self::$handler->set(md5($key),$value,$time);*/
	}
	
	/**
	 * 获取
	 * @param string $key
	 */
	public function get($key){
 		/*$key=CacheConf::PREFIX.$key;
 		return self::$handler->get(md5($key));*/
	}
	
	/**
	 * 删除
	 * @param string $key
	 */
	public function rm($key){
 		/*$key=CacheConf::PREFIX.$key;
 		return self::$handler->delete(md5($key));*/
	}
	
	/**
	 * 清空缓存
	 * @param string $key
	 */
	public function flush(){
		//return self::$handler->flush();
	}
	
	/**
	 * 析构方法
	 */
	public function __destruct(){
		//self::$handler->quit();
	}
}
