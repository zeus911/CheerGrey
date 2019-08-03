<?php
namespace Common\Model;

class DevUserInfoModel extends DevDataBaseModel
{
    protected $tableName = 'erp_user_info';
	
	public function getUserInfoList()
	{
		$dataList=array();
		
		$where=array('state'=>1);
		
		$tempDataList=$this->field('username,email,realname,mobile,role_group_code,role_group_level,company')->where($where)->select();
		
		foreach($tempDataList as $item)
		{
			$checkRule='/云空间/';
			$companyName=$item['company'];

			if(!preg_match($checkRule,$companyName))
			{
				continue;
			}
			
			$dataList[]=$item;
			
		}
		
		
		return $dataList;
		
	}
	
	
}