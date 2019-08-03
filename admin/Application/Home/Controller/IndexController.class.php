<?php
namespace Home\Controller;
class IndexController extends BaseController 
{
    
	public function index()
	{
		redirect('/main/index.html');
	}
	
	public function login()
	{
		$this->assign('jump_url','/main/index.html');
		$this->display('login');
	}
	
	public function logout()
	{
		$dAuthInfo=D('AuthInfo');
		$dAuthInfo->clearCurrentUserLogin();
		redirect('/main/index.html');
	}
}