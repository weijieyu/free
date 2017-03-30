<?php
namespace Admin\Controller;
use Admin\Controller;

class IndexController extends Controller\BaseController {
	
 	public function index() {
    	$this->display();
    }
    public function top(){
    	$this->display();
    }
	public function left(){
    	$this->display();
    }
	public function right(){
    	$this->display();
    }
}