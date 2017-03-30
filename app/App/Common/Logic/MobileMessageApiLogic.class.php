<?php 
namespace Common\Logic;
use Common\Logic;
use Common\Library\CacheConf as CacheConf;
use Common\Library\Tip as Tip;
use Common\Library\MCD as MCD;

class MobileMessageApiLogic extends Logic\BaseApiLogic{
	
	/**
	 * 发送短信
	 * @param $data
	 * @return array
	 * @author renxing
	 * @since 2015年06月10日17:16:31
	 */
	public function sendPhoneMsgLg($data){
		$phone = $data['phone'];
		$msg = $data['msg'];
		$markId = $data['markId'];
		$mark = $data['mark'];
		
		//执行发送短信
		$flag=$this->_sendPhoneMsgByRx($phone,$msg);
		
		
		//发送结果
		if($flag){
			//将本次发送短信的记录存到数据库
			$this->_addSendMsgRecord($markId,$mark,$phone,$msg,$flag);
			$this->tip=array('errno'=>Tip::GET_SEND_MOBILE_MESSAGE_SUCCESS,'errmsg'=>Tip::GET_SEND_MOBILE_MESSAGE_SUCCESS_MSG);
		}else{
			$this->tip=array('errno'=>Tip::GET_SEND_MOBILE_MESSAGE_FAIL,'errmsg'=>Tip::GET_SEND_MOBILE_MESSAGE_FAIL_MSG);
		}
		
		return $flag;
		
	}
	
	/**
	 * 
	 * @param  $markId	说明备注编号
	 * @param  $mark	说明备注
	 * @param  $phone	手机号码
	 * @param  $msg		短信完整内容
	 * @param  $flag	是否发送成功,0 OR 1
	 */
	private function _addSendMsgRecord($markId,$mark,$phone,$msg,$flag){
		$data['markId'] = $markId;
		$data['mark'] = $mark;
		$data['phone'] = $phone;
		$data['msg'] = $msg;
		$data['flag'] = $flag;
		$data['create_time'] = time();
		M("SendPhoneMsgRecord")->add($data);
	}
	
	/**
	 * 发送短信
	 * @author renxing
	 * @since 2015年06月11日17:50:57
	 */
	private function _sendPhoneMsgByRx($phone,$msg){
		$MobileMessage=D('MobileMessage','Service');
		$flag=$MobileMessage->sendPhoneMsgSvc($phone,$msg);
		return $flag;
	}
	
	/*---------------------------------------------------------------------*/
	
	/**
	 * 发送短信验证码
	 * @param $data  
	 * @return array   
	 * @author wjy
	 */
	public function sendMobileMessage($data){
		////签名校验
		//checkServerSign();
		
		$type=$data['type'];
		$mobileNumber=$data['mobile_number'];
		if($type==1){
			//获取缓存中的验证码
			$code=$this->_getMobileMessageCode($mobileNumber);
			if($code){
				$flag=$this->_sendMobileMessage($mobileNumber,$code);//发送旧code
			}else{
				$code=$this->_createMobileMessageCode();//生成验证码
				$this->_setMobileMessageCode($mobileNumber, $code);//写入缓存省去一次读库
				$flag=$this->_sendMobileMessage($mobileNumber,$code);//发送
			}
			//发送结果
			if($flag){
				$this->tip=array('errno'=>Tip::GET_SEND_MOBILE_MESSAGE_SUCCESS,'errmsg'=>Tip::GET_SEND_MOBILE_MESSAGE_SUCCESS_MSG);
			}else{
				$this->tip=array('errno'=>Tip::GET_SEND_MOBILE_MESSAGE_FAIL,'errmsg'=>Tip::GET_SEND_MOBILE_MESSAGE_FAIL_MSG);
			}
		}else if($type==2){
			//查找该手机号码
			$row=D('User','Model')->getUserInfoByMobileNumber($mobileNumber);	
			if($row){
				$code=$this->_getMobileMessageCode($mobileNumber);//走缓存取code
				if($code){
					$flag=$this->_sendMobileMessage($mobileNumber,$code);//发送旧的
				}else{
					$code=$this->_createMobileMessageCode();//新code
					$this->_setMobileMessageCode($mobileNumber, $code);//缓存起来
					$flag=$this->_sendMobileMessage($mobileNumber,$code);//发送
				}
				//发送结果
				if($flag){
					$this->tip=array('errno'=>Tip::GET_SEND_MOBILE_MESSAGE_SUCCESS,'errmsg'=>Tip::GET_SEND_MOBILE_MESSAGE_SUCCESS_MSG);
				}else{
					$this->tip=array('errno'=>Tip::GET_SEND_MOBILE_MESSAGE_FAIL,'errmsg'=>Tip::GET_SEND_MOBILE_MESSAGE_FAIL_MSG);
				}
			}else{
				//该手机号码未绑定过
				$flag=false;
				$this->tip=array('errno'=>Tip::GET_NOT_EXISTS_MOBILE_NUMBER_BANG,'errmsg'=>Tip::GET_NOT_EXISTS_MOBILE_NUMBER_BANG_MSG);
			}		
		}
		
		//记录发送短信的记录
		if($flag){
			$this->_mobileMessageNotes($mobileNumber,$type,1,$code);
		}else{
			$this->_mobileMessageNotes($mobileNumber,$type,2,$code);
		}
		
		return $flag;
	}

	//----------------------------------私有调用 -------------------------------------------------
	
	/**
	 * 发送 验证码
	 * @param $mobileNumber  手机号码
	 * @param $code         验证码
	 * @author wjy
	 */
	private function _sendMobileMessage($mobileNumber,$code){
		//签名校验
		//checkServerSign();
		
		$MobileMessage=D('MobileMessage','Service');
		$flag=$MobileMessage->sendMobileMessage($mobileNumber,$code);
		return $flag;
	}
	
	/**
	 * 创建 验证码
	 * @return string         验证码
	 * @author ruansheng
	 */
	private function _createMobileMessageCode(){
		$code = mt_rand(0, 9) . mt_rand(0, 9) . mt_rand(0, 9) . mt_rand(0, 9) . mt_rand(0, 9) . mt_rand(0, 9);
		return $code;
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
	 * 存储验证码到缓存中
	 * @param  $mobileNumber 手机号码
	 * @return array         
	 * @author wjy
	 */
	private function _setMobileMessageCode($mobileNumber,$code){
		$memcached=MCD::getInstance();
		$flag=$memcached->set('ys_mobile_vcode'.$mobileNumber,$code,600);  //10分钟缓存
		return $flag;
	}
	
	/**
	 * 统计手机短信验证码
	 * @author ruansheng
	 */
	private function _mobileMessageNotes($mobileNumber,$type,$status,$code){
		$data=array(
				'mobile_number'=>$mobileNumber,
				'source_type'=>$type,
				'status'=>$status,
				'create_time'=>time(),
				'is_del'=>0,
				'code'=>$code
		);
		D('MobileMessageNotes','Model')->addMobileMessageNotes($data);
	}
	
}
