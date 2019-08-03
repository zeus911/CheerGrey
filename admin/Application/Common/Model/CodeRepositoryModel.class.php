<?php
namespace Common\Model;

class CodeRepositoryModel extends DataBaseModel
{
    protected $tableName = 'code_repository';
	
	public function searchDataList($data=array())
	{
		$where=array('state'=>1);
			
		if($data['group_uuid'])
		{
			$where['group_uuid']=$data['group_uuid'];
		}
		
		
		$dataList=$this->field('id,group_uuid,group_code,group_name,group_url,repository_uuid,repository_code,repository_name,repository_url')->where($where)->select();
		
		return $dataList;
		
	}
	
	public function getGroupDataList()
	{
		$where=array('state'=>1);
		
		$dataList=$this->field('group_uuid,group_code,group_name')->where($where)->group('group_uuid')->select();
		
		return $dataList;
	}
	
}