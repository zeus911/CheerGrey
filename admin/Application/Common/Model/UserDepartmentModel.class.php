<?php
namespace Common\Model;

class UserDepartmentModel extends DataBaseModel
{
    protected $tableName = 'user_department';
	
	public function getDepartmentKvList()
	{
		$dataList=array();
		
		$where=array('level_code'=>'department','state'=>1);
		
		$tempList=$this->where($where)->order('pid asc')->select();
		
		foreach($tempList as $item)
		{
			$dataList[$item['id']]=$item['title'];
		}
		
		return $dataList;
	}
	
	public function getProductKvList()
	{
		$dataList=array();
		
		$where=array('level_code'=>'product','state'=>1);
		
		$tempList=$this->where($where)->order('id asc')->select();
		
		foreach($tempList as $item)
		{
			$dataList[$item['id']]=$item['title'];
		}
		
		return $dataList;
	}
	
	public function getDepartmentList()
	{
		$dataList=array();
		
		$where=array('level_code'=>'department','state'=>1);
		
		$dataList=$this->where($where)->order('pid asc')->select();
		
		return $dataList;
	}
	
	public function getDepartmentProduct($department_id)
	{
		$data=array();
		
		$departmentData=$this->getData($department_id);
		
		if(!$departmentData)
		{
			return $data;
		}
		
		$data=$this->getData($departmentData['pid']);
		
		return $data;
	}
}