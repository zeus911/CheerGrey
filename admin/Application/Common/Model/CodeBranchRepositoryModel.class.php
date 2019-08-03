<?php
namespace Common\Model;

class CodeBranchRepositoryModel extends DataBaseModel
{
    protected $tableName = 'code_branch_repository';
	
	protected $_auto = array(
	   array('create_time','get_fulltime',1,'function'),
	   array('update_time','get_fulltime',3,'function'),
	   array('update_ip','get_client_addr',3,'function'),
	);
	
	
	protected $_validate=array( 
	   array('task_id','checkTaskId','请选择正确的分支任务!',0,'callback',3),
	   array('repository_id','checkRepositoryId','请选择正确的代码仓库!',0,'callback',3),
	);
	
	protected function checkTaskId($data)
	{
		$dCodeBranchTask=D('CodeBranchTask');
		
		$taskData=$dCodeBranchTask->getData(intval($data));
		
		if(!$taskData)
		{
			return false;
		}
		
		return true;
	}
	
	protected function checkRepositoryId($data)
	{
		$dCodeRepository=D('CodeRepository');
		
		$taskData=$dCodeRepository->getData(intval($data));
		
		if(!$taskData)
		{
			return false;
		}
		
		return true;
	}
	
	public function addData($data)
	{
		
		$findWhere=array('task_id'=>$data['task_id'],'repository_id'=>$data['repository_id'],'state'=>1);
		
		$findData=$this->where($findWhere)->find();
		
		if($findData)
		{
			$this->setErrorMsg(250,'选择的仓库已经与此分支任务关联!');
			return false;
		}
		
		
		
		$dCodeRepository=D('CodeRepository');
		
		$repositoryData=$dCodeRepository->getData($data['repository_id']);
		
		if(!$repositoryData)
		{
			$this->setErrorMsg(251,'选择的代码仓库不存在,请联系管理员!');
			return false;
		}
		
		$data['group_code']=$repositoryData['group_code'];
		$data['group_name']=$repositoryData['group_name'];
		$data['group_url']=$repositoryData['group_url'];
		$data['repository_code']=$repositoryData['repository_code'];
		$data['repository_name']=$repositoryData['repository_name'];
		$data['repository_url']=$repositoryData['repository_url'];
		$data['feature_code']='';
		$data['feature_url']='';
		
		if(!$this->create($data))
		{
			
			$this->setErrorMsg(252,$this->getError());
			return false;
		}
		
		$nId=$this->add();
		
		$dCodeBranchTask=D('CodeBranchTask');
		$dCodeBranchTask->refreshCodeBranchTaskStatus($data['task_id']);
		
		return $nId;
		
	}
	
	public function delData($data)
	{
		$dataInfo=$this->getData($data['id']);
		
		if(!$dataInfo)
		{
			$this->setErrorMsg(250,'操作失败,数据库不存在此关联信息,请联系管理员!');
			return false;
		}
		
		
		$dataInfo['state']=0;
		
		if(!$this->create($dataInfo))
		{
			
			$this->setErrorMsg(252,$this->getError());
			return false;
		}
		
		$this->save();
		
		$dCodeBranchTask=D('CodeBranchTask');
		$dCodeBranchTask->refreshCodeBranchTaskStatus($dataInfo['task_id']);
		
		return true;
	}
	
	public function getDataList($taskId)
	{
		$where=array('task_id'=>intval($taskId),'state'=>1);
		
		$dataList=$this->where($where)->select();
		
		return $dataList;
		
	}
}