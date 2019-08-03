<?php
namespace Common\Model;

class AuthInfoModel
{
	public function getCurrentUserInfo()
	{
		$data=array();
		
		$authConfig=C('ACCOUNT_AUTH');
		
		if(!$authConfig['TYPE'])
		{
			return $data;
		}
		
		if($authConfig['TYPE']=='INNER')
		{
			$data=$this->getUserInfoFromInner();
			return $data;
		}
		
		
		if($authConfig['TYPE']=='DEV')
		{
			$data=$this->getUserInfoFromDev();
			return $data;
		}
		
		return $data;
	}
	
	
	public function clearCurrentUserLogin()
	{
		$authConfig=C('ACCOUNT_AUTH');	
		
		if($authConfig['TYPE']=='DEV')
		{
			$userTokenId=cookie('myspacex_erp_token');
			
			$redisHandle=\Com\Chw\RedisLib::getInstance('REDIS_DEFAULT');	
			$redisHandle->delete($userTokenId);
		}
		
		cookie('myspacex_erp_token',null);
		session('admin_login_data','null');
	}
	
	public function getUserInfoFromDev()
	{
		$data=array();
		
		$userTokenId=cookie('myspacex_erp_token');
		
		if(!$userTokenId)
		{
			return $data;
		}
		
		$cacheKey=sprintf('getUserInfoFromDev_%s',$userTokenId);
		$cacheData=S($cacheKey);
		

		if($cacheData)
		{
			$data=$cacheData;
			return $data;
		}
		
		$redisHandle=\Com\Chw\RedisLib::getInstance('REDIS_DEFAULT');
		
		$userTokenData=$redisHandle->hgetall($userTokenId);
		
		if(!$userTokenData)
		{
			return $data;
		}
		
		$data=array(
		   'username'=>$userTokenData['username'],
		   'email'=>$userTokenData['email'],
		   'role_group_code'=>$userTokenData['role_group_code'],
		   'role_group_level'=>$userTokenData['role_group_level'],
		   'realname'=>$userTokenData['realname'],
		);
		
		
		S($cacheKey,$data,array('type'=>'file','expire'=>60*60*24));
		
		return $data;
	}
	
	public function getUserInfoFromInner()
	{
		$data=array();
		
		$sessionData=session('admin_login_data');
		
		if(!$sessionData)
		{
			return $data;
		}
		
		$data=array(
		   'username'=>$sessionData['username'],
		   'email'=>$sessionData['email'],
		   'role_group_code'=>$sessionData['role_group_code'],
		   'role_group_level'=>$sessionData['role_group_level'],
		   'realname'=>$sessionData['realname'],
		);
		
		return $data;
	}
}