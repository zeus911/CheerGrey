<?php
namespace Home\Controller;
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
		
		C('CURRENT_USER_INFO',$currentUserInfo);
		
		$userName=$currentUserInfo['username'];
		
		$authConfig=C('ACCOUNT_AUTH');
		
		$loginUrl=sprintf('%s%s',$authConfig['LOGIN_URL'],base64_encode($this->getCurrentUrl()));
		
		if(!$userName)
		{
			$this->show('<script language="javascript">window.top.location.href="'.$loginUrl.'";</script>','utf-8');
			exit();
		}
	}
	
	private function getCurrentUrl()
	{
		$url='/';
		
		$siteHost=get_current_domain();
		
		$url=sprintf('http://%s%s',$siteHost,__SELF__);
		
		return $url;
		
	}
}