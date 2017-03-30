<?php 
namespace Common\Logic;
use Think\Model;

class BaseApiLogic extends Model{
	
	/**
	 *提示信息数组
	*/
	public $tip='';
	
	/**
	 * 返回提示信息数组
	 */
	public function getTip(){
		return $this->tip;
	}
	
}
