<?php 
namespace Common\Model;
use Think\Model;

/**
 * 统计短信Model
 * @author wjy
 * 负责处理统计短信相关的数据操作业务
 */
class MobileMessageNotesModel extends Model {
	
	/**
	 * 插入统计记录
	 * @return array
	 */
    public function addMobileMessageNotes($data){
	    	$flag=M('MobileMessageNotes')->add($data);
	    	return $flag;
    }
   
}