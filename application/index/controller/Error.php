<?php
/**
 * File Name: Error.php
 * Author: llllljian
 * mail: 18634678077@163.com
 * Created Time: Thu 18 Jul 2019 12:22:36 AM CST
 **/
namespace app\index\controller;

use think\Controller;

class Error extends Controller
{

    public function index()
    {
        return $this->page();
    }

    public function page()
    {
		if (!config('app_debug')) {
        	return $this->view->fetch('error/page');
		}
    }
    
    // 空操作
    public function _empty()
    {
        return $this->page();
    }
}
