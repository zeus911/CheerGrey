<?php
namespace Common\Model;

class CodeStoryModel extends DataBaseModel
{
    protected $tableName = 'code_story';
	
	protected $_auto = array(
	   array('create_time','get_fulltime',1,'function'),
	   array('update_time','get_fulltime',3,'function'),
	   array('update_ip','get_client_addr',3,'function'),
	);
	
	
	public function saveData($data)
	{
		if(!$this->create($data))
		{
			$this->setErrorMsg(251,$this->getError());
			return false;
		}
		
		$this->save();
		
		return true;
	}
	
	
	public function getDepartmentCodeStoryList($department_id)
	{
		
		$where=array('department_id'=>intval($department_id),'state'=>1);
		
		$dataList=$this->field('id,story_code,story_name,story_url')->where($where)->select();
		
		return $dataList;
		
	}
	
	public function getRoleUserList($id)
	{
		$data=array('rd_uid'=>0,'qa_uid'=>0);
		
		$dataInfo=$this->where(array('id'=>intval($id)))->find();
		
		$dUserInfo=D('UserInfo');
		
		$rdOption=array('user_name'=>$dataInfo['recv_username']);
		$rdUserInfo=$dUserInfo->getUserInfoByOption($rdOption);
		
		if(!$rdUserInfo)
		{
			$rdOption=array('department_id'=>intval($dataInfo['department_id']),'role_group_code'=>'manager');
		    $rdUserInfo=$dUserInfo->getUserInfoByOption($rdOption);
		}
		
		if($rdUserInfo)
		{
			$data['rd_uid']=$rdUserInfo['id'];
		}
		
		$qaOption=array('department_id'=>intval($dataInfo['department_id']),'role_group_code'=>'test');
		$qaUserInfo=$dUserInfo->getUserInfoByOption($qaOption);
		
		if($qaUserInfo)
		{
			$data['qa_uid']=$qaUserInfo['id'];
		}
		
		return $data;
	}
	
	
}