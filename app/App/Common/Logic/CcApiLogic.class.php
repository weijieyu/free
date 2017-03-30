<?php 
namespace Common\Logic;
use Common\Logic;
use Common\Library\Curl as Curl;
use Common\Library\CacheConf as CacheConf;
use Common\Library\Tip as Tip;
use Common\Library\MCD as MCD;

class CcApiLogic extends Logic\BaseApiLogic{

	/**
	 * 评论文章
	 */
	public function commentArticleLg($data){
		$tb = M('articleComment');
		//完整入库数据添加
		$data['add_t'] = time();
		//楼层获取
		$flor = $tb->where(array('a_id'=>$data['a_id']))->count('id') + 1;
		$data['flor'] = $flor;
		//入库
		$res = $tb->add($data);
		//对应文章评论统计字段加1
		M('article')->where(array('id'=>$data['a_id']))->setInc('comment_count');
		
		$memcached=MCD::getInstance();
		$meminfo = $memcached->get('ysyx');
		$mKey = $meminfo['article_info']['value'];
		$memcached->rm($mKey.$data['a_id']);//删除该文章缓存

		return $res;
	}

	/**
	 * 评论商品
	 */
	public function commentShopLg($data){
		$tb = M('shopComment');
		//完整入库数据添加
		$data['add_t'] = time();
		//楼层获取
		$flor = $tb->where(array('s_id'=>$data['s_id']))->count('id') + 1;
		$data['flor'] = $flor;
		//入库
		$res = $tb->add($data);
		//商品对应评论统计字段加1
		M('shop')->where(array('id'=>$data['s_id']))->setInc('comment_count');
		//删除该商品缓存
		$memcached=MCD::getInstance();
		$meminfo = $memcached->get('ysyx');
		$mKey = $meminfo['shop_info']['value'];
		$memcached->rm($mKey.$data['a_id']);

		return $res;
	}

	/**
	 * 获取文章评论
	 */
	public function getArticleCommentLg($data){
		$tb = M('ArticleComment');
		//$memcached=MCD::getInstance();
		

		//获取条件设定
		$field = "id,pid,flor,author_uid,word,img_id,add_t";
		$order = "id";
		$limit = (($data['page']-1)*$data['limit']).','.$data['limit'];
		$map['is_del'] = 0;
		$map['a_id'] = $data['a_id'];

		//$memKey = 'cc_getArticleComment'.$data['a_id'].$limit;
		
	
		//获取数据
		/*if ($memcached->get($memKey)) {//如果有缓存
			$list = $memcached->get($memKey);
			return $list;
		}*/

		$list = $tb->field($field)->where($map)->order($order)->limit($limit)->select();

		if(!$list){
			retJson(2001, '没有获取到数据');
		};
		
		//数据加工  
		foreach ($list as $key=>$val) {
			if ($val['img_id'] != 0) {//将img_id换为url
				$list[$key]['img'] = showImgPathV2("ysyx", $val['img_id']);
			}
			if ($val['author_uid'] != 0) {//将author_uid换为信息
				$list[$key]['author'] = $this->getAuthorInfo($val['author_uid']);
			}
			if ($val['pid'] != 0) {//获取引用的评论信息
				$tmpInfo = $tb->field($field)->where(array('id'=>$val['pid'],'is_del'=>0))->find();
				if ($tmpInfo['img_id'] != 0) {//引用信息处理之图片
					$tmpInfo['img'] = showImgPathV2("ysyx", $val['img_id']);
				}
				if ($tmpInfo['author_uid'] != 0) {//引用信息处理之作者
					$tmpInfo['author'] = $this->getAuthorInfo($tmpInfo['author_uid']);
				}
				unset($tmpInfo['img_id']);
				unset($tmpInfo['author_uid']);
				$list[$key]['p_info'] = $tmpInfo;
			}
			//无用字段剔除
			unset($list[$key]['img_id']);
			unset($list[$key]['author_uid']);
		};

		//缓存起来
		//$memcached->set($memKey, $list, 900);
	
		return $list;
	}

	/**
	 * 获取商品评论
	 */
	public function getShopCommentLg($data){
		$tb = M('ShopComment');
		//$memcached=MCD::getInstance();

		//获取条件设定
		$field = "id,pid,flor,author_uid,word,img_id,add_t";
		$order = "id";
		$limit = (($data['page']-1)*$data['limit']).','.$data['limit'];
		$map['is_del'] = 0;
		$map['s_id'] = $data['s_id'];
		
		$memKey = 'cc_getShopComment'.$data['s_id'].$limit;

		//获取数据
		/*if ($memcached->get($memKey)) {//如果有缓存
			$list = $memcached->get($memKey);
			return $list;
		}*/

		$list = $tb->field($field)->where($map)->order($order)->limit($limit)->select();

		if(!$list){
			retJson(2001, '没有获取到数据');
		};
		
		//数据加工  
		foreach ($list as $key=>$val) {
			if ($val['img_id'] != 0) {//将img_id换为url
				$list[$key]['img'] = showImgPathV2("ysyx", $val['img_id']);
			}
			if ($val['author_uid'] != 0) {//将author_uid换为信息
				$list[$key]['author'] = $this->getAuthorInfo($val['author_uid']);
			}
			if ($val['pid'] != 0) {//获取引用的评论信息
				$tmpInfo = $tb->field($field)->where(array('id'=>$val['pid'],'is_del'=>0))->find();
				if ($tmpInfo['img_id'] != 0) {//引用信息处理之图片
					$tmpInfo['img'] = showImgPathV2("ysyx", $val['img_id']);
				}
				if ($tmpInfo['author_uid'] != 0) {//引用信息处理之作者
					$tmpInfo['author'] = $this->getAuthorInfo($tmpInfo['author_uid']);
				}
				unset($tmpInfo['img_id']);
				unset($tmpInfo['author_uid']);
				$list[$key]['p_info'] = $tmpInfo;
			}
			//无用字段剔除
			unset($list[$key]['img_id']);
			unset($list[$key]['author_uid']);
		};

		//缓存起来
		//$memcached->set($memKey, $list, 900);
	
		return $list;
	}

	/**
	 * 获取用户收藏文章
	 */
	public function getUserCollectALg($data){
		//$memcached=MCD::getInstance();
		$collectTb = M('articleCollect');
		$articleTb = M('article');

		//获取条件设定
		$field = "a_id";
		$order = "id desc";
		$limit = (($data['page']-1)*$data['limit']).','.$data['limit'];
		$map['is_del'] = 0;
		$map['u_id'] = $data['u_id'];

		//$memKey1 = 'cc_getUserCollectA_idList'.$data['u_id'].$limit;
		//$memKey2 = 'cc_getUserCollectA_aInfo'.$data['u_id'].$limit;
	
		//获取收藏的文章id
		/*if ($memcached->get($memKey1)) {//如果有缓存
			$list = $memcached->get($memKey1);
		} else {*/
			$list = $collectTb->field($field)->where($map)->order($order)->limit($limit)->select();
			//$memcached->set($memKey1,$list,900);
		//}

		if(!$list){
			retJson(2001, '没有获取到数据');
		};
		
		//根据文章id list 获取文章信息  
		/*if ($memcached->get($memKey2)) {//有缓存
			$aInfo = $memcached->get($memKey2);
			return $aInfo;
		}*/ 

		$idList = $this->arrayChange($list);
		$mapA['id']  = array('in', $idList);
		$tmpAinfo = $articleTb->field('id,img_id,title')->where($mapA)->select();

		//按照收藏顺序排列
		$aInfo = array();
		foreach ($idList as $key=>$val) {
			foreach ($tmpAinfo as $k1=>$v1) {
				if ($v1['id'] == $val) {
					array_push($aInfo, $v1);
				}
			}
		}

		//数据处理
		foreach ($aInfo as $k=>$v) {
			if ($v['img_id'] != 0) {//图片id变为url
				$aInfo[$k]['img'] = showImgPathV2('ysyx', $v['img_id']);
			}
			unset($aInfo[$k]['img_id']);
		}

		//缓存起来
		//$memcached->set($memKey2, $aInfo, 900);
	
		return $aInfo;
	}

	/**
	 * 获取用户收藏商品
	 */
	public function getUserCollectSLg($data){
		//$memcached=MCD::getInstance();
		$collectTb = M('shopCollect');
		$articleTb = M('shop');

		//获取条件设定
		$field = "s_id";
		$order = "id desc";
		$limit = (($data['page']-1)*$data['limit']).','.$data['limit'];
		$map['is_del'] = 0;
		$map['u_id'] = $data['u_id'];

		//$memKey1 = 'cc_getUserCollectS_idList'.$data['u_id'].$limit;
		//$memKey2 = 'cc_getUserCollectS_aInfo'.$data['u_id'].$limit;
	
		//获取收藏的文章id
		/*if ($memcached->get($memKey1)) {//如果有缓存
			$list = $memcached->get($memKey1);
		} else {*/
			$list = $collectTb->field($field)->where($map)->order($order)->limit($limit)->select();
			//$memcached->set($memKey1,$list,900);
		//}

		if(!$list){
			retJson(2001, '没有获取到数据');
		};
		
		//根据文章id list 获取文章信息  
		/*if ($memcached->get($memkey2)) {//有缓存
			$aInfo = $memcached->get($memkey2);
			return $aInfo;
		}*/

		$idList = $this->arrayChange($list);
		$mapA['id']  = array('in', $idList);
		$tmpAinfo = $articleTb->field('id,img_id,price,brand,name')->order('id desc')->where($mapA)->select();

		//按照收藏顺序排列
		$aInfo = array();
		foreach ($idList as $key=>$val) {
			foreach ($tmpAinfo as $k1=>$v1) {
				if ($v1['id'] == $val) {
					array_push($aInfo, $v1);
				}
			}
		}

		//数据处理
		foreach ($aInfo as $k=>$v) {
			if ($v['img_id'] != 0) {//图片id变为url
				$tmp = explode(',', $v['img_id'])[0];
				$aInfo[$k]['img'] = array(showImgPathV2('ysyx', $tmp));
			}
			unset($aInfo[$k]['img_id']);
		}

		//缓存起来
		//$memcached->set($memkey2, $aInfo, 900);
	
		return $aInfo;
	}


	/////////////////////////////////////////////////////////////////////

	/*
	* 公用方法封装
	*/

	//根据作者id返回作者头像和昵称
	private function getUserInfo($user_id){
		$memcached = MCD::getInstance();
		$meminfo = $memcached->get('ysyx');
		$mTime = $meminfo['user_nn']['time'];
		$memKey = $meminfo['user_nn']['value'].$user_id;

		if ($memcached->get($memKey)) {//先走缓存
			return $memcached->get($memKey);
		}

		$uInfo = M("User")->field("user_name,avatar_id")->where("user_id = $user_id")->find();
		$uInfo['avatar_info'] = getUserAvatar($uInfo['avatar_id']);//['avatar_200_200'];
		unset($uInfo['avatar_id']);

		//缓存起来
		$memcached->set($memKey, $uInfo, $mTime);

		return $uInfo;
	}

	//二维数组转为一维数组
	private function arrayChange($a){ 
	    static $arr2; 
	    foreach($a as $v){ 
	        if(is_array($v)){ 
	            $this->arrayChange($v); 
	        } 
	        else{ 
	            $arr2[]=$v; 
	        } 
	    } 
	    return $arr2; 
	}

	//获取作者的用户信息
	private function getAuthorInfo($user_id){
		$memcached = MCD::getInstance();
		$meminfo = $memcached->get('ysyx');
		$mTime = $meminfo['user_nn']['time'];
		$memKey = $meminfo['user_nn']['value'].$user_id;

		if ($memcached->get($memKey)) {//先走缓存
			return $memcached->get($memKey);
		}

		$uInfo = M("User")->field("user_name,avatar_id")->where("user_id = $user_id")->find();
		$uInfo['avatar_info'] = getUserAvatar($uInfo['avatar_id']);//['avatar_200_200'];
		unset($uInfo['avatar_id']);

		//缓存起来
		$memcached->set($memKey, $uInfo, $mTime);

		return $uInfo;
	}
}
