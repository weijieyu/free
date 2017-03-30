<?php 
namespace Common\Model;
use Think\Model;

/**
 * 用户Model
 * @author ruansheng
 * 负责处理用户相关的数据操作业务
 */
class UserModel extends Model {
	
	/**
	 * 查看第三方账号是否登录过
	 * @param $thirdpartyId   第三方平台账号的id
	 * @return array
	 */
    public function getUserInfoByThirdpartyId($thirdpartyId){
    	return M('User')->where(array('thirdparty_id'=>$thirdpartyId))->find();
    }
    
    /**
     * 查看第三方账号是否注册过
     * @param $email    邮箱
     * @return array
     */
    public function getUserInfoByEmail($email){
    	return M('User')->where(array('email'=>$email))->find();
    }
    
    /**
     * 更新最后一次登录时间
     * @param $userId   用户id
     * @return array
     */
    public function updateLastLoginTime($userId){
    	return M('User')->where(array('user_id'=>$userId,'is_del'=>'0'))->save(array('last_login_time'=>time()));
    }
    
    /**
     * 保存首次登录的第三方账号信息
     * @param $UserInfo   第三方账号的信息
     * @return int\boolean
     */
    public function addThirdPartyUser($UserInfo=array()){
    	//$device_token = isset($_SERVER['HTTP_DEVICETOKEN'])?$_SERVER['HTTP_DEVICETOKEN'] : '';//推送所需的设备信息
    	$data=array(
    			'user_name'=>$UserInfo['user_name'],
    			'password'=>'',
    			'register_time'=>time(),
    			'email'=>'',
    			'sex'=>$UserInfo['sex'],
    			//'location'=>'',
    			'intro'=>'',
    			'user_type'=>$UserInfo['user_type'],   //1:sina   2:qq
    			'thirdparty_id'=>$UserInfo['openid'],
    			'register_ip'=>ip2long(get_client_ip()),
    			'is_del'=>0,
    			'last_login_time'=>time(),
    			'device_type'=>$UserInfo['device_type'],
    			'device_id'=>$UserInfo['device_id'],
    			'update_time'=>time(),
    			'avatar_id'=>$UserInfo['avatar_id'],  //by ruansheng 2015/3/3
    			//'device_token'=>$device_token, //rx,2015年9月16日 20:00:44
    	);
    	
    	$userId=M('User')->add($data);
    	
    	return $userId;
    }
    
    /**
     * 保存注册账号信息
     * @param $UserInfo   注册账号的信息
     * @return int\boolean
     */
    public function addNativeUser($UserInfo=array()){
    	//$device_token = isset($_SERVER['HTTP_DEVICETOKEN'])?$_SERVER['HTTP_DEVICETOKEN'] : '';//推送所需的设备信息
    	$data=array(
    			'user_name'=>$UserInfo['user_name'],
    			'password'=>md5($UserInfo['password']),
    			'register_time'=>time(),
    			'email'=>$UserInfo['email'],
    			'sex'=>0,
    			//'location'=>'',
    			'intro'=>'',
    			'user_type'=>'3',   //3:普通账户
    			'thirdparty_id'=>'',
    			'register_ip'=>ip2long(get_client_ip()),
    			//'app_id'=>$UserInfo['app_id'],
    			'is_del'=>0,
    			'last_login_time'=>time(),
    			'device_type'=>$UserInfo['device_type'],
    			'device_id'=>$UserInfo['device_id'],
    			'update_time'=>time(),
    			//'device_token'=>$device_token, //rx,2015年9月16日 20:00:44
    	);
    	
    	$userId=M('User')->add($data);
    	
    	return $userId;
    }
    
    /**
     * 保存注册手机账号信息
     * @param $UserInfo   注册账号的信息
     * @return int\boolean
     */
    public function addMobileUser($UserInfo=array()){
    	//$device_token = isset($_SERVER['HTTP_DEVICETOKEN'])?$_SERVER['HTTP_DEVICETOKEN'] : '';//推送所需的设备信息
	    	$data=array(
	    			'user_name'=>$UserInfo['mobile_number'],
	    			'password'=>md5($UserInfo['password']),
	    			'register_time'=>time(),
	    			'email'=>'',
	    			'mobile_number'=>$UserInfo['mobile_number'],
	    			'sex'=>'',
                    //'location'=>'',
	    			'avatar_id'=>$UserInfo['avatar_id'],
	    			'intro'=>'',
	    			'user_type'=>'3',   //3:普通账户
	    			'thirdparty_id'=>'',
	    			'register_ip'=>ip2long(get_client_ip()),
	    			'is_del'=>0,
	    			'last_login_time'=>time(),
	    			'device_type'=>$UserInfo['device_type'],
	    			'device_id'=>$UserInfo['device_id'],
	    			'update_time'=>time()
	    	);
	    	
	    	$userId=M('User')->add($data);
	    	return $userId;
    }
    
    /**
     * 获取用户记录
     * @param $userId   用户id
     * @return mixed
     */
    public function getUserInfo($userId){
    	return M('User')->field('user_id,sex,avatar_id,user_type,intro,user_name,mobile_number,avatar_id')->where(array('user_id'=>$userId,'is_del'=>'0'))->find();
    }
    
    /**
     * 获取用户一个字段 renxing 2014年12月21日 13:48:18
     * @param $userId   用户id
     * @return mixed
     */
    public function getUserField($userId,$field){
    	return M('User')->where(array('user_id'=>$userId,'is_del'=>'0'))->getField($field);
    }
    
    
    /**
     * 获取用户资料通过手机号码
     * @param $userId     用户id
     * @param $userName   用户昵称
     * @param $intro      用户简介
     * @return mixed
     */
    public function getUserInfoByMobileNumber($mobileNumnber){
    	return M('User')->where(array('mobile_number'=>$mobileNumnber))->find();
    }

    /**
     * 修改用户密码
     * @param $userId     用户id
     * @param $password      用户密码
     * @return mixed
     */
    public function updateUserPassword($userId,$password){
    		$data=array(
    			'password'=>md5($password),
    			'update_time'=>time()
    		);
    		return M('User')->where(array('user_id'=>$userId))->save($data);
    }
    
    /**
     * 获取用户列表
     * @param $userIds   用户id数组
     * @return mixed
     */
    public function getUserInfoList($userIds){
    		$map=array(
    			'user_id'=>array('in',$userIds)
    		);
    		return M('User')->field(array('user_id','user_name','signature'))->where($map)->select();
    }
    
    /**
     * 添加手机绑定
     * @param unknown $userId
     * @param unknown $mobileNumber
     */
    public function addMobileNumberBang($userId,$mobileNumber){
	    	$data=array(
    			'mobile_number'=>$mobileNumber,
    			'update_time'=>time()
	    	);
	    	return M('User')->where(array('user_id'=>$userId))->save($data);
    }
    
    /**
     * 更新用户头像id
     * @param $userId      用户id
     * @param $avatarId   头像id
     * @author ruansheng
     */
    public function uploadAvatar($userId,$avatarId){
    		$data=array(
    			'avatar_id'=>$avatarId,
    			'update_time'=>time()
    		);
    		return M('User')->where(array('user_id'=>$userId))->save($data);
    }
    
}
