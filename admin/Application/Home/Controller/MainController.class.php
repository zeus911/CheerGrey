<?php
namespace Home\Controller;

class MainController extends AuthController 
{
    
	public function index()
	{
		
		$userInfo=C('CURRENT_USER_INFO');
		
		$this->assign('userInfo',$userInfo);
		
		
		$this->display('index');		
	}
	
	public function get_nav_list()
	{
		$dSystemMenu=D('SystemMenu');
		
		$dataList=$dSystemMenu->getAllShowMenuList();
		
		$this->assign('dataList',$dataList);
		
		$this->display('get_nav_list');
	}
	
	public function main()
	{
		
		$dAuthInfo=D('AuthInfo');
		$currentUserInfo=$dAuthInfo->getCurrentUserInfo();
		
		$this->assign('currentUserInfo',$currentUserInfo);
		
		$this->display('main');
	}
}