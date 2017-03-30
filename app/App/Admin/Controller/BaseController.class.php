<?php
namespace Admin\Controller;
use Think\Controller;

class BaseController extends Controller{
	
	public function __construct(){
		header("Content-type: text/html; charset=utf-8");
		parent::__construct();
		/*if(isset($_SESSION['uid']) && !empty($_SESSION['uid'])){
			$uinfo=M('User')->where("user_id = $_SESSION[uid]")->find();
			$this->assign('uinfo',$uinfo);
		}else{
			$this->redirect('Login/index');
		}*/
	}
	
}
