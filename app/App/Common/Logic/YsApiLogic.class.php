<?php 
namespace Common\Logic;
use Common\Logic;
use Common\Library\Curl as Curl;
use Common\Library\CacheConf as CacheConf;
use Common\Library\Tip as Tip;
use Common\Library\MCD as MCD;

class YsApiLogic extends Logic\BaseApiLogic{
	
	/**
	 * 获取文章列表
	 */
	public function getArticleListLg($data){
		$memcached=MCD::getInstance();

		//获取条件设定
		$field = "id,img_id,title";
		$order = "id desc";
		$limit = (($data['page']-1)*$data['limit']).','.$data['limit'];
		$map['is_del'] = 0;
		
	
		//获取数据
		/*if ($memcached->get('ys_getArticleList'.$limit)) {//如果有缓存
			$list = $memcached->get('ys_getArticleList'.$limit);
			return $list;
		}*/

		$list = M('Article')->field($field)->where($map)->order($order)->limit($limit)->select();

		if(!$list){
			retJson(2001, '没有获取到数据');
		};
		
		//将图片id换为url
		foreach ($list as $key=>$val) {
			if ($val['img_id'] != 0) {
				$list[$key]['img'] = showImgPathV2("ysyx", $val['img_id']);
			}
			unset($list[$key]['img_id']);
		};

		//缓存起来
		//$memcached->set('ys_getArticleList'.$limit, $list, 900);
	
		return $list;
	}
	
	/**
	 * 获取文章详情
	 */
	public function getArticleInfoLg($data){
		$memcached=MCD::getInstance();
		$meminfo = $memcached->get('ysyx');
		$mKey = $meminfo['article_info']['value'];
		$mTime = $meminfo['article_info']['time'];
		
		//获取条件设定
		$field="*";
		$order="id desc";
		$map['is_del'] = 0;
		$map['id'] = $data['a_id'];
		$map['start_t'] = array('lt', time());
		
	
		//获取数据
		if ($memcached->get($mKey.$map['id'])) {//如果有缓存
			$list = $memcached->get($mKey.$map['id']);
			return $list;
		}

		$list = M('Article')->field($field)->where($map)->order($order)->find();
		if(!$list){
			retJson(2001, '没有获取到数据');
		}
		
		//数据加工
		if ($list['img_id'] != 0) {//将图片id换为url
			$list['img'] = showImgPathV2("ysyx", $list['img_id']);
		}
		
		if ($list['author_uid'] != 0) {//根据作者id返回作者头像和昵称
			$list['author_info'] = $this->getUserInfo($list['author_uid']);
		}

		$list['content'] = json_decode($list['content'], true);

		//添加有无商品的字段
		$list['has_shop'] = 0;
		foreach ($list['content'] as $val) {
			if ($val['type'] == 7) {
				$list['has_shop'] = 1;
				break;
			}
		}

		//非必需字段去除
		unset($list['img_id']);
		unset($list['author_uid']);
		unset($list['is_del']);
		unset($list['start_t']);

		$memcached->set($mKey.$map['id'], $list, $mTime);

		return $list;
	}

	/**
	 * 获取商品一二级标签
	 */
	public function getShopTagLg($data){
		$memcached=MCD::getInstance();

		//数据获取
		if ($memcached->get('ys_getShopTag')) {//如果有缓存
			$list = $memcached->get('ys_getShopTag');
			return $list;
		}

		//获取一级标签数据
		$list = M('ShopTag1')->field('id,name')->where(array('is_del'=>0))->order('id asc')->select();
		if(!$list){
			retJson(2001, '没有获取到数据');
		};
		
		//根据一级标签获取二级标签
		foreach ($list as $key=>$val) {
			$tag2 = M('ShopTag2');
			$map['is_del'] = 0;
			$map['tag1_id'] = $val['id'];
			$tmpInfo = $tag2->field('id,name')->where($map)->order('order_num asc')->select();
			$list[$key]['tag2'] = $tmpInfo;
		}

		$memcached->set('ys_getShopTag', $list, 900);
	
		return $list;
	}

	/**
	 * 根据文章id获取商品列表
	 */
	public function getShopListLg($data){
		$memcached=MCD::getInstance();

		//数据获取
		/*if ($memcached->get('ys_getShopList'.$data['a_id'])) {//如果有缓存
			$list = $memcached->get('ys_getShopList'.$data['a_id']);
			return $list;
		}*/
		//从文章商品表中找出文章下的全部商品的id
		$shopMap['is_del'] = 0;
		$shopMap['a_id'] = $data['a_id'];
		$shopInfo = M('ArticleShop')->field('shop_id')->where($shopMap)->select();
		$limit = (($data['page']-1)*$data['limit']).','.$data['limit'];

		//根据商品的id获取商品缩略信息
		$field="id,name,brand,url,img_id,price";
		$order="id desc";
		$map['is_del'] = 0;
		$map['id'] = array('in',$this->arrayChange($shopInfo));

		//获取数据
		$list = M('Shop')->field($field)->where($map)->limit($limit)->order($order)->select();
		if(!$list){
			retJson(2001, '没有获取到数据');
		};
		
		//将图片id换为url
		foreach ($list as $key=>$val) {
			if ($val['img_id'] != 0) {
				$tmpImg = explode(',', $val['img_id']);
				$list[$key]['img'] = array(showImgPathV2("ysyx", $tmpImg[0]));
			}
			unset($list[$key]['img_id']);
		};

		//数据缓存
		//$memcached->set('ys_getShopList'.$data['a_id'], $list, 900);
	
		return $list;
	}

	/**
	 * 获取二级列表详情
	 */
	public function getTag2InfoLg($data){
		$memcached=MCD::getInstance();
		$meminfo = $memcached->get('ysyx');
		$mKey = $meminfo['tag2_info']['value'];
		$mTime = $meminfo['tag2_info']['time'];

		//获取条件设定
		$field="content";
		$order="id desc";
		$map['is_del'] = 0;
		$map['id'] = $data['id'];
	
		//获取数据
		if ($memcached->get($mKey.$map['id'])) {//如果有缓存
			$list = $memcached->get($mKey.$map['id']);
			return $list;
		}

		$list = M('ShopTag2')->field($field)->where($map)->find();
		if(!$list){
			retJson(2001, '没有获取到数据');
		};

		//数据加工
		$list['content'] = json_decode($list['content'], true);

		//数据缓存
		$memcached->set($mKey.$map['id'], $list, $mTime);
	
		return $list;
	}

	/**
	 * 获取商品详情
	 */
	public function getShopInfoLg($data){
		$memcached=MCD::getInstance();
		$meminfo = $memcached->get('ysyx');
		$mKey = $meminfo['shop_info']['value'];
		$mTime = $meminfo['shop_info']['time'];

		//获取条件设定
		$field="*";
		$order="id desc";
		$map['is_del'] = 0;
		$map['id'] = $data['id'];
	
		//获取数据
		if ($memcached->get($mKey.$map['id'])) {//如果有缓存
			$list = $memcached->get($mKey.$map['id']);
			return $list;
		}

		$list = M('Shop')->field($field)->where($map)->find();
		if(!$list){
			retJson(2001, '没有获取到数据');
		};

		//数据加工
		if ($list['img_id'] != 0) {
			$tmpImg = explode(",", $list['img_id']);
			foreach ($tmpImg as $key=>$val) {
				$tmpImg[$key] = showImgPathV2("ysyx", $val);
			}
			$list['img'] = $tmpImg;

		} else {
			$list['img'] = '';
		}

		$list['content'] = json_decode($list['content'], true);

		//无用数据剔除
		unset($list['is_del']);
		unset($list['img_id']);
		
		//另外附上他所在文章的缩略信息
		$a_id = M('ArticleShop')->where(array('shop_id'=>$data['id']))->getField('a_id');
		$aInfo = M('Article')->where(array('id'=>$a_id))->field('id,img_id,title')->find();
		if ($aInfo) {
			$aInfo['img'] = showImgPathV2("ysyx", $aInfo['img_id']);
			unset($aInfo['img_id']);
		}

		//整理一下
		$allDt = $list;
		$allDt['article'] = $aInfo;

		//数据缓存
		$memcached->set($mKey.$map['id'], $allDt, $mTime);

		return $allDt;
	}

	/**
	 * 获取相似单品
	 */
	public function getSameShopLg($data){
		$memcached=MCD::getInstance();

		//获取条件设定
		$field="id,name,brand,img_id,price";
		$order="id desc";
		$map['is_del'] = 0;
		$map['tag2_id'] = $data['tag2_id'];
		$limit = (($data['page']-1)*$data['limit']).','.$data['limit'];
	
		//获取数据
		if ($memcached->get('ys_getSameShop'.$map['tag2_id'].$limit)) {//如果有缓存
			$list = $memcached->get('ys_getSameShop'.$map['tag2_id'].$limit);
			return $list;
		}

		$list = M('Shop')->field($field)->where($map)->limit($limit)->select();
		if(!$list){
			retJson(2001, '没有获取到数据');
		};
		
		//数据加工
		foreach ($list as $key=>$val) {
			$tmpVal = explode(',', $val['img_id']);
			$list[$key]['img'] = array(showImgPathV2("ysyx", $tmpVal[0]));
			unset($list[$key]['img_id']);
		}

		//数据缓存
		$memcached->set('ys_getSameShop'.$map['tag2_id'].$limit, $list, 900);

		return $list;
	}

	/**
	 * 获取tag2缩略信息
	 */
	public function getMiniTag2Lg($data){
		$memcached=MCD::getInstance();
		$meminfo = $memcached->get('ysyx');
		$mKey = $meminfo['tag2_miniInfo']['value'];
		$mTime = $meminfo['tag2_miniInfo']['time'];

		//获取条件设定
		$map['is_del'] = 0;
		$map['id'] = $data['tag2_id'];

		if ($memcached->get($mKey.$map['id'])) {//如果有缓存
			$list = $memcached->get($mKey.$map['id']);
			return $list;
		}
	
		//获取tag2的简单数据
		$list = M('ShopTag2')->field('id,img')->where($map)->find();
		if(!$list){
			retJson(2001, '没有获取到数据');
		};
		
		//数据加工
		$tmpVal = explode(',', $list['img']);
		$tmpImg = array();
		foreach ($tmpVal as $k=>$v) {
			$tmp = showImgPathV2("ysyx", $v);
			array_push($tmpImg, $tmp);
			unset($tmp);
		}
		$list['img'] = $tmpImg;

		//去商品表里获取该tag2对应的商品
		$mapShop['tag2_id'] = $data['tag2_id'];
		$mapShop['is_del'] = 0;
		$field = 'id,name,brand,url,img_id,price';
		$shopInfo = M('Shop')->where($mapShop)->field($field)->order('id desc')->limit(20)->select();

		//对商品的img_id进行处理
		foreach ($shopInfo as $key=>$val) {
			$tmpSpImg = explode(",", $val['img_id']);
			$shopInfo[$key]['img'] = array(showImgPathV2("ysyx", $tmpSpImg[0]));
			unset($shopInfo[$key]['img_id']);
		}

		//数据组装
		$rtDt = $list;
		$rtDt['shop'] = $shopInfo;

		//数据缓存
		$memcached->set($mKey.$map['id'], $rtDt, $mTime);

		return $rtDt;
	}


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
}
