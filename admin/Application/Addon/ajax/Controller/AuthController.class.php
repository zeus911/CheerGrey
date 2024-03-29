<?php
namespace Addon\ajax\Controller;
use Common\Controller\CommonController;

class AuthController extends BaseController
{
	public function _initialize()
	{
		parent::_initialize();
		$this->checkLogin();
	}
	
	private function checkLogin()
	{
		$dAuthInfo=D('AuthInfo');
		$currentUserInfo=$dAuthInfo->getCurrentUserInfo();
		
		$userName=$currentUserInfo['username'];
		
		if(!$userName)
		{
			$this->ajaxCallMsg(301,'你没有登录或者登录状态过期,请重新登录!');
		}
	}
}