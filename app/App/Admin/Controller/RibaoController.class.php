<?php 
namespace Admin\Controller;
use Admin\Controller\BaseController;
use Common\Library\CacheConf as CacheConf;
use Common\Library\MCD as MCD;

/**
 * 一色优选
 * @author 蔚捷宇
 *
 */
class RibaoController extends BaseController{

	 
	/*
	*文章展示页
	*/
	public function ribao(){
		header("Content-type: text/html; charset=utf-8");
		$tbName = M("ribao");//$info=$tbName->select();
		$where = $param = array();

		//默认查询设置
		$field = "*"; //要查询的字段
		$orderstyle = "desc"; //默认排序方式
		$orderparam = "id"; //默认排序字段
		$pageSize = 50; //每页显示的条数

		//用户查找设定
		if(!empty($_GET['chazhao']) && $_GET['chazhao']==1){
			$param['chazhao'] = 1;

			//查找条件的获取,设定 
			if(!empty($_GET['id']) && $_GET['id']!=-1){
				$stxt['id'] = $param['id'] = $where['id'] = $_GET['id'];
			} 

			//排序方式的获取,设定
			if(!empty($_GET['orderparam'])){//排序字段
				$stxt['orderparam'] = $param['orderparam'] = $orderparam = $_GET['orderparam'];
			}
			if(!empty($_GET['orderstyle']) && $_GET['orderstyle'] != 'default'){//正倒序
				$stxt['orderstyle'] = $param['orderstyle'] = $orderstyle = $_GET['orderstyle'];
			}
			
		}
		$this->assign("stxt",$stxt);
		
		//准备工作
		$order = $orderparam.' '.$orderstyle;
		$count = $tbName->where($where)->count();
		$Page = new \Think\Page($count,$pageSize);
		$Page->parameter = $param;
		$show = $Page->show();
		
		//获取列表
		$data_list = $tbName->field($field)->where($where)->order($order)->limit($Page->firstRow.','.$Page->listRows)->select();
		$sql = $tbName->getLastSql();

		/*数据加工*/
		//qq($sql);

		//数据拼接
		$result=array(
				'list'=>$data_list,
				'show'=>$show
		);
		
		$this->assign('list',$result['list']);
		$this->assign('pages',$result['show']);
	
		//分页信息
		$total_data = $tbName->where($where)->count();
		$pg['total_data'] = $total_data;
		$pg['pagesize'] = $pageSize;
		$pg['this_page'] = $_GET['p']?$_GET['p']:1;
		$pg['total_page'] = ceil($total_data/$pageSize);
		$this->assign('pg',$pg);
		$this->assign('pgA',$show);

		$this->display();
	}
	
	//ajax更改状态，is_del字段
	public function articleAjax(){
		//$this->ajaxDel('Article',$_GET);
	}


	/*公共方法封装*/
	//ajax更改is_del字段封装方法
	private function ajaxDel($table,$_data){
		$tbName = M($table);
		$id = isset($_data['id'])?$_data['id']:-1;
		$field = isset($_data['field'])?$_data['field']:-1;
		$value = isset($_data['value'])?$_data['value']:-1;
		if($id != -1 && $field != -1 && $value != -1){//如果操作成功
			$where['id'] = $id;
			$data[$field] = $value;
			$result = $tbName->where($where)->save($data);
			echo 1;exit;
		}else{
			echo -1;exit;
		}
	}

	//根据id删除记录封装方法
	private function deleteId($table, $data){
		if(isset($data['id']) && !empty($data['id'])){
			$where['id'] = $data['id'];
			$res = M($table)->where($where)->delete();
			$this->success("删除成功！");
		}else{
			echo '非法参数';exit;
		}
	}
}

