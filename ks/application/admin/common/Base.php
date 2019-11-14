<?php
namespace app\admin\common;
use think\Controller;
use think\Session;


class Base extends Controller
{
	protected function _initialize()
	{
		//初始化方法中，创建一个常量用来判断用户是否已登录
		define('USER_ID',Session::get('user_id'));
	}
	//判断用户是否登录
	protected function isLogin()
	{
		if(is_null(USER_ID))
		{
			$this->error('未登录,请先登录！','login/index');

		}
	}
	protected function alreadyLogin()
	{
		if(!is_null(USER_ID))
		{
			$this->error('已登录，请不要重复登录！','index/index');

		}
	}
}