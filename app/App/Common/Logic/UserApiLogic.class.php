<?php 
namespace Common\Logic;
use Common\Logic;
use Common\Library\Curl as Curl;
use Common\Library\CacheConf as CacheConf;
use Common\Library\Tip as Tip;
use Common\Library\MCD as MCD;

class UserApiLogic extends Logic\BaseApiLogic{
	
	/**
	 * 第三方账号登录
	 * @param $data['type']             qq\sina   登录类型
	 * @param $data['thirdparty_id']    76FF96C9BAEBB31DE234D455729657FD   第三方账号id
	 * @param $data['token']            2.00suAbGDMb1g6C20326203405I2OTC   第三方返回的token
	 * @param $data['appid']		    1\2           抢购助手\表情文
	 * @param $data['device_type']      1: android    2: ios
	 * @return int\boolean                  是否登录成功
	 * @author ruansheng      
	 */
	public function ThirdPartyLogin($data=array()){
		//user表   最好情况: 1次读        最坏的情况: 1次读+1次写     
		$type=$data['type'];
		$thirdpartyId=$data['thirdparty_id'];
		$token=$data['token'];
		$deviceType=$data['device_type'];
		$deviceId=$data['device_id'];
		
		//获取第三方账号在第三方平台上面的信息
		$OpenLogin = D('OpenLogin', 'Service');
		switch($type){
			case 'sina':
				$UserInfo=$OpenLogin->getSinaUserInfo($token);
				$UserInfo['user_type']=1;
				$UserInfo['device_type']=$deviceType;
				$UserInfo['device_id']=$deviceId;
				break;
			case 'qq':
				$UserInfo=$OpenLogin->getQzoneUserInfo($token);
				$UserInfo['user_type']=2;
				$UserInfo['device_type']=$deviceType;
				$UserInfo['device_id']=$deviceId;
				break;
		}
		
		//检查登录状况
		if($UserInfo['openid']==$thirdpartyId){//根据token正确获取到第三方账号信息
			$User=D('User',"Model");
			$row=$User->getUserInfoByThirdpartyId($thirdpartyId); //判断是否是第一次登录

			if($row){  //登录过
				//更新设备号(devicetoken)
				//$this->_addUserDeviceToken($row['user_id']);
				//$this->_mergeTmpUserMoney($row['user_id']);
				
				if($row['is_del']==0){
					$userId=$row['user_id'];
					//更新用户最后一次登录时间
					$User->updateLastLoginTime($userId);
					
					$this->tip=array('errno'=>Tip::LOGIN_SUCCESS,'errmsg'=>Tip::LOGIN_SUCCESS_MSG);
					return $userId;
				}else{ //账号已被封禁、删除
					$this->tip=array('errno'=>Tip::USER_DENY,'errmsg'=>Tip::USER_DENY_MSG);
					return false;
				}
			}else{ //未登录过(第一次登录)
				//请求下载用户头像
				$avatarId=$this->_requestDownloadUserAvatar($UserInfo['userface']);
				$UserInfo['avatar_id']=$avatarId;
				//insert一条记录
				$userId=$User->addThirdPartyUser($UserInfo);
				if($userId){
					//更新设备号(devicetoken)
					//$this->_addUserDeviceToken($userId);
					
					$this->tip=array('errno'=>Tip::LOGIN_SUCCESS,'errmsg'=>Tip::LOGIN_SUCCESS_MSG);
					return $userId;
				}else{ //登录失败
					$this->tip=array('errno'=>Tip::LOGIN_FAIL,'errmsg'=>Tip::LOGIN_FAIL_MSG);
					return false;
				}
			}
		}else{  //token或thirdparty_id错误
			$this->tip=array('errno'=>Tip::OPENID_CHECK_FAIL,'errmsg'=>Tip::OPENID_CHECK_FAIL_MSG);
			return false;
		}
	}
	
	//判断是否存在设备号(devicetoken)，若无则更新,rx,2015年9月16日 19:28:45
	private function _addUserDeviceToken($userId){
		$device_token = isset($_SERVER['HTTP_DEVICETOKEN'])?$_SERVER['HTTP_DEVICETOKEN'] : '';//推送所需的设备信息
		$shebei_id = $_SERVER['HTTP_DEVICEID']?$_SERVER['HTTP_DEVICEID'] : '';
		$sv_data['device_token'] = $device_token;
		$sv_data['shebei_id'] = $shebei_id;
		M("User")->where("user_id = $userId")->save($sv_data);
	}
	private function _addUserAppMsg($userId){
		//$apptype = isset($_SERVER['HTTP_APPTYPE'])?$_SERVER['HTTP_APPTYPE'] : -1;
		$versionid = isset($_SERVER['HTTP_VERSIONID'])?$_SERVER['HTTP_VERSIONID'] : -1;
		$sv_data['app_msg'] = $versionid.'|'.time();
		M("User")->where("user_id = $userId")->save($sv_data);
	}
	
	/**
	 * 获取用户信息
	 * @param $userId  用户id
	 * @return array   用户信息数组
	 * @author wjy
	 */
	public function getUserInfo($userId){
			//查找用户记录
			$row=$this->_getUserRow($userId);
			 
			//获取用户计数
			//$rowStat=$this->_getUserStat($userId);      //缓存控制
			
			//用户设置的tags,By renxing,2015年1月6日 19:02:24
			//$user_tags = $this->_getUserTags($userId);
			
			if($row){ //查到记录
				$userInfo = $row;
				$userId=$row['user_id'];
				unset($userInfo['user_id']);

				//更新app_msg字段 根据请求头信息
				$this->_addUserAppMsg($userId);
				
				//判断是否存在设备号(devicetoken)，若无则更新
				/*if(empty($row['device_token'])){
					$this->_addUserDeviceToken($row['user_id']);
				}*/
				
				$userSign=userId_Translation_userSign($userId);  //user_id 转   user_sign
				$userInfo['user_sign'] = $userSign;//返回信息添加user_sign
				
				//$userInfo['score']=($rowStat['score']!=null)?$rowStat['score']:'0';
				$userInfo['uid']=$userId;
				
				$this->tip=array('errno'=>Tip::USERINFO_GET_SUCCESS,'errmsg'=>Tip::USERINFO_GET_SUCCESS_MSG);
				return $userInfo;
			}else{  //查记录失败
				$this->tip=array('errno'=>Tip::USERINFO_GET_FAIL,'errmsg'=>Tip::USERINFO_GET_FAIL_MSG);
				return array();
			}
	}
	
	/**
	 * 获取短网址
	 */
	private function _getShortUrl($get_uid){
		$old_url = qwbcgCfg()['qgbcg_com']."/s/?m=".$get_uid;
		$ch=curl_init();
		curl_setopt($ch,CURLOPT_URL,"http://dwz.cn/create.php");
		curl_setopt($ch,CURLOPT_POST,true);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
		$data=array('url'=>$old_url);
		curl_setopt($ch,CURLOPT_POSTFIELDS,$data);
		$strRes=curl_exec($ch);
		curl_close($ch);
		$arrResponse=json_decode($strRes,true);
		if($arrResponse['status']==0)
		{
			/**错误处理*/
			echo iconv('UTF-8','GBK',$arrResponse['err_msg'])."\n";
		}
	
		/** tinyurl */
		//echo $arrResponse['tinyurl']."\n";
		return $arrResponse['tinyurl'];
	}
	
	/**
	 * 获取未登录用户信息（device）
	 * @author renxing
	 */
	public function getDeviceInfo($deviceId){
		//查找设备配置信息 qg_user_device_config
		$device_config = $this->_getUserDeviceConfig($deviceId);    //缓存控制
		$deviceTableInfo = $this->_getDeviceInfoFunc($deviceId);
		$deviceInfo['qudao_device'] = $deviceTableInfo['qudao'];
		
		if($device_config){ //查到记录
			$deviceInfo['user_type']='device';
			$deviceInfo['device_id']=$deviceId;
			$deviceInfo['sex']=($device_config['sex']==1)? '男':'女';
			
			$this->tip=array('errno'=>Tip::USERINFO_GET_SUCCESS,'errmsg'=>Tip::USERINFO_GET_SUCCESS_MSG);
			return $deviceInfo;
		}else{  //查记录失败
			$this->tip=array('errno'=>Tip::USERINFO_GET_FAIL,'errmsg'=>Tip::USERINFO_GET_FAIL_MSG);
			return array();
		}
	}

	/**
	 * 检查手机验证码是否正确
	 * @author wjy
	 */
	public function checkMobileCode($data){
		$mobileNumber = $data['mobile_number'];
		$code = $data['code'];
		
		//判断手机号码是否曾经注册过或者绑定过
		$row=D('User',"Model")->getUserInfoByMobileNumber($data['mobile_number']);
		if (!$row) {
			$res['errno'] = 1115;
			$res['errmsg'] = "手机号码没有注册过";
			return $res;
		}
		
		$cacheCode=$this->_getMobileMessageCode($mobileNumber);
		if (!$cacheCode) {//没缓存只能走表
			$cacheCode=$this->getMobileMessageCode($mobileNumber);
		}
		
		if($cacheCode!=$code){
			$res['errno']=1116;
			$res['errmsg']="验证码错误";
		}else{
			$res['errno']=0;
			$res['errmsg']="验证成功";
		}
		return $res;
	}
	
	/**
	 * 手机账号注册处理
	 * @param $data['mobile_number']   手机号码
	 * @param $data['password']        密码
	 * @param $data['repeat_password'] 重复密码
	 * @param $data['code']            短信验证码
	 * @param $data['device_type']     1: android    2: ios
	 * @return int\boolean             是否注册成功
	 * @author wjy
	 */
	public function mobileRegister($data){
		$mobileNumber=$data['mobile_number'];
		$password=$data['password'];
		$repeatPassword=$data['repeat_password'];
		$code=$data['code'];
		
		$User=D('User',"Model");
		
		
		//判断两次密码是否相同
		if($password!=$repeatPassword){
			$this->tip=array('errno'=>Tip::USER_PASSWORD_NO_EQUEL,'errmsg'=>Tip::USER_PASSWORD_NO_EQUEL_MSG);
			return false;
		}

		//判断验证码是否正确
		if(strstr($_SERVER['HTTP_HOST'], 'yise') || strstr($_SERVER['HTTP_HOST'], '192.168.1.201') || strstr($_SERVER['HTTP_HOST'], 'localhost')){
			$cacheCode=$this->_getMobileMessageCode($mobileNumber);
			if (!$cacheCode) {//没缓存只能走表
				$cacheCode=$this->getMobileMessageCode($mobileNumber);
			}
			if($cacheCode!=$code){
				$this->tip=array('errno'=>Tip::MOBILE_MESSAGE_CODE_IS_ERROR,'errmsg'=>Tip::MOBILE_MESSAGE_CODE_IS_ERROR_MSG);
				return false;
			}
		}
		
		//判断手机号码是否曾经注册过或者绑定过
		$row=$User->getUserInfoByMobileNumber($mobileNumber);
		if($row){
			$return = array('errno'=>Tip::MOBILE_NUMBER_IS_EXISTS,'errmsg'=>Tip::MOBILE_NUMBER_IS_EXISTS_MSG);
			
			$this->tip = $return;
			return false;
		}
		
		//给用户初始化一个头像
		$tmpAv = array(30,31,32,33,34,35,36,37,25,26);
		$data['avatar_id'] = $tmpAv[array_rand($tmpAv)];
		
		//添加注册
		$userId=$User->addMobileUser($data);    //insert一条记录
		if($userId){
			$return=array('errno'=>Tip::REGISTER_SUCCESS,'errmsg'=>Tip::REGISTER_SUCCESS_MSG);
			$return['wsno'] = 1; //成功申请（新用户时）恭喜你，已经申请成功！
			$this->tip=$return;
			return $userId;
		}else{
			$this->tip=array('errno'=>Tip::REGISTER_FAIL,'errmsg'=>Tip::REGISTER_FAIL_MSG);
			return false;
		}
		
	}
	
	/**
	 * 手机账号登录处理
	 * @param $data['mobile_number']    手机号码
	 * @param $data['password']         密码
	 * @return int\boolean              是否登录成功
	 * @author wjy
	 */
	public function mobileLogin($data){
		$mobileNumber=$data['mobile_number'];
		$password=$data['password'];
	
		//判断手机号码账户是否存在是否注册过
		$User=D('User',"Model");
		$row=$User->getUserInfoByMobileNumber($mobileNumber);
		if($row){//该账号存在
			//更新设备号(devicetoken)
			//$this->_addUserDeviceToken($row['user_id']);
			//$this->_mergeTmpUserMoney($row['user_id']);
			if($row['is_del']==0){
				if($row['password']==md5($password)){  //登录成功
					$userId=$row['user_id'];
					//更新用户最后一次登录时间
					$User->updateLastLoginTime($userId);
					
					$this->tip=array('errno'=>Tip::LOGIN_SUCCESS,'errmsg'=>Tip::LOGIN_SUCCESS_MSG);
					return $userId;
				}else{ //登录失败 密码错误
					$this->tip=array('errno'=>Tip::PASSWORD_ERROR,'errmsg'=>Tip::PASSWORD_ERROR_MSG);
					return false;
				}
			}else{ //账号已被封禁、删除
				$this->tip=array('errno'=>Tip::USER_DENY,'errmsg'=>Tip::USER_DENY_MSG);
				return false;
			}
		}else{  //该账号没有注册过
			$this->tip=array('errno'=>Tip::MOBILE_NUMBER_NOT_IS_EXISTS,'errmsg'=>Tip::MOBILE_NUMBER_NOT_IS_EXISTS_MSG);
			return false;
		}
	}
	
	/**
	 * 修改密码处理
	 * @param $data['password']        密码
	 * @param $data['repeat_password']         密码
	 * @param $data['user_id']         用户id
	 * @return int\boolean                  是否修改成功
	 * @author wjy
	 */
	public function updatePassword($data){
		$password=$data['password'];
		$repeatPassword=$data['repeat_password'];
		$userId=$data['user_id'];
		
		$User=D('User',"Model");
		
		//判断两次密码是否相同
		if($password!=$repeatPassword){
			$this->tip=array('errno'=>Tip::USER_PASSWORD_NO_EQUEL,'errmsg'=>Tip::USER_PASSWORD_NO_EQUEL_MSG);
			return false;
		}
		
		$flag=$User->updateUserPassword($userId,$password);
		if($flag){//修改成功
			$this->tip=array('errno'=>Tip::USERINFO_UPDATE_SUCCESS,'errmsg'=>Tip::USERINFO_UPDATE_SUCCESS_MSG);
			return $flag;
		}else{  //更新失败
			$this->tip=array('errno'=>Tip::USERINFO_UPDATE_FAIL,'errmsg'=>Tip::USERINFO_UPDATE_FAIL_MSG);
			return false;
		}
	}
	
	/**
	 * 找回密码处理
	 * @param $data['mobile_number']   手机号码
	 * @param $data['password']        密码
	 * @param $data['repeat_password'] 密码
	 * @param $data['code']            短信验证码
	 * @param $data['user_id']         用户id
	 * @return int\boolean             是否修改成功
	 * @author ruansheng
	 */
	public function forgetPassword($data){
		$mobileNumber=$data['mobile_number'];
		$password=$data['password'];
		$repeatPassword=$data['repeat_password'];
		$code=$data['code'];
	
		$User=D('User',"Model");
	
		//判断两次密码是否相同
		if($password!=$repeatPassword){
			$this->tip=array('errno'=>Tip::USER_PASSWORD_NO_EQUEL,'errmsg'=>Tip::USER_PASSWORD_NO_EQUEL_MSG);
			return false;
		}
	
		//判断手机号码是否曾经注册过或者绑定过
		$row=$User->getUserInfoByMobileNumber($mobileNumber);
		if(!$row){
			$this->tip=array('errno'=>Tip::MOBILE_NUMBER_NOT_IS_EXISTS,'errmsg'=>Tip::MOBILE_NUMBER_NOT_IS_EXISTS_MSG);
			return false;
		}
	
		//判断验证码是否正确
		$cacheCode=$this->_getMobileMessageCode($mobileNumber);
		if (!$cacheCode) {//没缓存只能走表
			$cacheCode=$this->getMobileMessageCode($mobileNumber);
		}
		if($cacheCode!=$code){
			$this->tip=array('errno'=>Tip::MOBILE_MESSAGE_CODE_IS_ERROR,'errmsg'=>Tip::MOBILE_MESSAGE_CODE_IS_ERROR_MSG);
			return false;
		}
	
		$flag=$User->updateUserPassword($row['user_id'],$password);
		if($flag){//修改成功
			$this->tip=array('errno'=>Tip::USERINFO_UPDATE_SUCCESS,'errmsg'=>Tip::USERINFO_UPDATE_SUCCESS_MSG);
			return $flag;
		}else{  //更新失败
			$this->tip=array('errno'=>Tip::USERINFO_UPDATE_FAIL,'errmsg'=>Tip::USERINFO_UPDATE_FAIL_MSG);
			return false;
		}
	}
	
	/**
	 * 绑定手机号码
	 * @param $data['mobile_number']            邮箱
	 * @param $data['code']         短信验证码
	 * @param $data['user_id']         用户id
	 * @return int\boolean                  是否修改成功
	 * @author wjy
	 */
	public function bangMobileNumber($data){
		$mobileNumber=$data['mobile_number'];
		$code=$data['code'];
		$userId=$data['user_id'];
	
		$User=D('User',"Model");
	
		//判断手机号码是否曾经注册过或者绑定过
		$row=$User->getUserInfoByMobileNumber($mobileNumber);
		if($row){
			$this->tip=array('errno'=>Tip::MOBILE_NUMBER_IS_EXISTS_BANG,'errmsg'=>Tip::MOBILE_NUMBER_IS_EXISTS_BANG_MSG);
			return false;
		}
	
		if(strstr($_SERVER['HTTP_HOST'], '192') || strstr($_SERVER['HTTP_HOST'], '201') || strstr($_SERVER['HTTP_HOST'], 'staging')){ //测试环境
		
		}else{
			//判断验证码是否正确
			$cacheCode=$this->_getMobileMessageCode($mobileNumber);
			if (!$cacheCode) {//没缓存只能走表
				$cacheCode=$this->getMobileMessageCode($mobileNumber);
			}
			if($cacheCode!=$code){
				$this->tip=array('errno'=>Tip::MOBILE_MESSAGE_CODE_IS_ERROR,'errmsg'=>Tip::MOBILE_MESSAGE_CODE_IS_ERROR_MSG);
				return false;
			}
		}
		
	
		$flag=$User->addMobileNumberBang($userId,$mobileNumber);
		if($flag){//绑定成功
			$this->_clearUserCache($userId); //清除缓存
			$this->tip=array('errno'=>Tip::MOBILE_NUMBER_BANG_SUCCESS,'errmsg'=>Tip::MOBILE_NUMBER_BANG_SUCCESS_MSG);
			return $flag;
		}else{  //绑定失败
			$this->tip=array('errno'=>Tip::MOBILE_NUMBER_BANG_FAIL,'errmsg'=>Tip::MOBILE_NUMBER_BANG_FAIL_MSG);
			return false;
		}
	}
	
	/**
	 * 用户上传头像图片
	 * @param int $userId
	 * @param array $file
	 * @author ruansheng
	 */
	public function uploadAvatar($userId,$file){//here
		$url='http://imgadmin0.qwbcg.mobi/upload_avatar.php?token=mmkj2xai823@asin';
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_POSTFIELDS,$data = array("file"  => "@".realpath($file['tmp_name'])));
		$info = curl_exec($ch);
		curl_close($ch);
		$json=json_decode($info,true);
		if($json['status']['errno']==0){
			$flag=D('User','Model')->uploadAvatar($userId,$json['data']['picture_id']);
			if($flag){
				$this->_clearUserCache($userId); //清除缓存
				$this->tip=array('errno'=>Tip::UPLOAD_AVATAR_SUCCESS,'errmsg'=>Tip::UPLOAD_AVATAR_SUCCESS_MSG);
				return true;
			}else{
				$this->tip=array('errno'=>Tip::UPLOAD_AVATAR_FAIL,'errmsg'=>Tip::UPLOAD_AVATAR_FAIL_MSG);
				return false;
			}
		}else{
			$this->tip=array('errno'=>Tip::UPLOAD_AVATAR_FAIL,'errmsg'=>Tip::UPLOAD_AVATAR_FAIL_MSG);
			return false;
		}
	}
	
	//----------------------------------私有调用 -------------------------------------------------
	
	/**
	 * 获取用户主表记录
	 * @param  $userId       用户id
	 * @return array         用户信息
	 * @author wjy
	 */
	private function _getUserRow($userId){
		$memcached=MCD::getInstance();
		$meminfo = $memcached->get('ysyx');
		$key=$meminfo['user']['value'].$userId;
		$mTime=$meminfo['user']['time'];
		$row=$memcached->get($key);//先走缓存
		if(!$row){
			$User=D('User',"Model");
			$row=$User->getUserInfo($userId);

			//数据加工
			$row['avatar'] = getUserAvatar($row['avatar_id'])['avatar_200_200'];
			
			unset($row['avatar_id']);
			$memcached->set($key,$row,$mTime);  //5分钟缓存
		}
		return $row;
	}
	
	/**
	 * 获取用户计数表记录
	 * @param  $userId       用户id
	 * @return array         用户信息
	 * @author ruansheng
	 */
	private function _getUserStat($userId){
		$memcached=MCD::getInstance();
		$meminfo = $memcached->get('qwbc');
		$mcd_key = $meminfo['user_stat']['value'];
		$mcd_time = $meminfo['user_stat']['time'];
		
		$key=$mcd_key.$userId;
		$rowStat=$memcached->get($key);   //用户副信息缓存
		if(!$rowStat){
			$UserStat=D('UserStat',"Model");
			$rowStat=$UserStat->getUserStat($userId);
			$memcached->set($key,$rowStat,$mcd_time);  //5分钟缓存
		}
		return $rowStat;
	}
	
	/**
	 * 获取设备信息 qg_user_device_config
	 * @author renxing
	 */
	private function _getUserDeviceConfig($deviceId){
		$memcached=MCD::getInstance();
		$meminfo = $memcached->get('qwbc');
		$mcd_key = $meminfo['device_config']['value'];
		$mcd_time = $meminfo['device_config']['time'];
		
		$key=$mcd_key.$deviceId;
		$deviceInfo=$memcached->get($key);
	
		if(!$deviceInfo){
			$DeviceConfig=D('UserDeviceConfig',"Model");
			$deviceInfo=$DeviceConfig->getUserDeviceInfo($deviceId);
			$memcached->set($key,$deviceInfo,$mcd_time);
		}
		return $deviceInfo;
	}
	
	/**
	 * 获取设备信息 qg_device表
	 */
	private function _getDeviceInfoFunc($deviceId){
		$memcached=MCD::getInstance();
		$meminfo = $memcached->get('qwbc');
		$mcd_key = $meminfo['device']['value'];
		$mcd_time = $meminfo['device']['time'];
	
		$key=$mcd_key.$deviceId;
		$res = $memcached->get($key);
		if(!$res){
			$Device = D('Device',"Model");
			$field = 'qudao';
			$where['device_id'] = $deviceId;
			$where['is_del'] = 0;
			$res = $Device->getDeviceInfo($field,$where);
			if(!$res){
				$res = array();
			}
			//设置缓存
			$memcached->set($key,$res,$mcd_time); 
		}
		return $res;
	}
	
	/**
	 * 获取缓存中的验证码
	 * @param  $mobileNumber  手机号码
	 * @return string         验证码
	 * @author wjy
	 */
	private function _getMobileMessageCode($mobileNumber){
		$memcached=MCD::getInstance();
		$message=$memcached->get('ys_mobile_vcode'.$mobileNumber);  //用户主信息缓存
		return $message;
	}

	/**
	 * 获取表中的验证码
	 * @param  $mobileNumber  手机号码
	 * @return string         验证码
	 * @author wjy
	 */
	private function getMobileMessageCode($mobileNumber){//here
		$map['mobile_number'] = $mobileNumber;
		$map['create_time'] = array('gt', time()-600);
		$cacheCode = M('MobileMessageNotes')->order('id desc')->where($map)->getField('code');
		return $cacheCode;
	}
	
	/**
	 * 请求下载第三方账号头像
	 * 2015/03/02
	 * @return int   图片id
	 * @author wjy
	 */
	private function _requestDownloadUserAvatar($avatarUrl){
		$url='http://imgadmin0.qwbcg.mobi/crawl_upload_avatar_ys.php?token=mmkj2xai823@asin&pic_url='.$avatarUrl;
		$info = Curl::get($url);
		$json=json_decode($info,true);
		if($json['status']['errno']==0){
			return $json['data']['picture_id'];
		}else{
			return 0;
		}
	}

	//----------------------------缓存控制------------------------------------
	
	/**
	 * 清除用户主表缓存
	 * @param  $userId       用户id
	 * @author ruansheng
	 */
	private function _clearUserCache($userId){
		$memcached=MCD::getInstance();
		$meminfo = $memcached->get('ysyx');
		$memcached->rm($meminfo['user']['value'].$userId);  //清除缓存
		
	}
	
}
