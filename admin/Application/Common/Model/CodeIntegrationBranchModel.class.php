<?php
namespace Common\Model;

class CodeIntegrationBranchModel extends DataBaseModel
{
    protected $tableName = 'code_integration_branch';
	
	protected $_auto = array(
	   array('create_time','get_fulltime',1,'function'),
	   array('update_time','get_fulltime',3,'function'),
	   array('update_ip','get_client_addr',3,'function'),
	);
	
	protected $_validate=array( 
	   array('integration_task_id','checkIntegrationTaskId','请选择正确的集成任务!',0,'callback',3),
	   array('branch_task_id','checkBranchTaskId','请选择正确的分支任务!',0,'callback',3),
	);
	
	protected function checkIntegrationTaskId($data)
	{
		$dCodeIntegrationTask=D('CodeIntegrationTask');
		
		$taskData=$dCodeIntegrationTask->getData(intval($data));
		
		if(!$taskData)
		{
			return false;
		}
		
		return true;
	}
	
	protected function checkBranchTaskId($data)
	{
		$dCodeBranchTask=D('CodeBranchTask');
		
		$taskData=$dCodeBranchTask->getData(intval($data));
		
		if(!$taskData)
		{
			return false;
		}
		
		return true;
	}
	
	public function getIntegrationBranchList($integration_id)
	{
		$dataList=array();
			
		$where=array('integration_task_id'=>intval($integration_id),'state'=>1);
		
		$dataList=$this->where($where)->select();
		
		$branchTaskIdList=get_array_item_list($dataList,'branch_task_id');
		
		$dCodeBranchTask=D('CodeBranchTask');
		
		$branchTaskList=$dCodeBranchTask->getKvDataDetailList($branchTaskIdList);
		
		foreach($dataList as &$item)
		{
			$item['branch_task']=array();
			
			$branchData=$branchTaskList[$item['branch_task_id']];
			
			if($branchData)
			{
				$item['branch_task']=$branchData;
			}
		}
		
		return $dataList;
	}
	
	public function getIntegrationBranchKvList($idList=array())
	{
		$dataList=array();
		
		if(!$idList)
		{
			return $dataList;
		}
		
		$where=array('integration_task_id'=>array('in',$idList),'state'=>1);
		
		$tempDataList=$this->where($where)->select();
		
		foreach($tempDataList as $item)
		{
			$integrationId=$item['integration_task_id'];
			
			if(!array_key_exists($integrationId,$dataList))
			{
				$dataList[$integrationId]=array();
			}
			
			$dataList[$integrationId][]=$item;
		}
		
		return $dataList;
	}
	
	public function addData($data)
	{
		if(!$this->create($data))
		{
			$this->setErrorMsg(250,$this->getError());
			return false;
		}
		
		$nId=$this->add();
		
		$dCodeIntegrationTask=D('CodeIntegrationTask');
		$dCodeIntegrationTask->refreshCodeBranchTaskStatus($data['integration_task_id']);
		
		$dCodeBranchTask=D('CodeBranchTask');
		$dCodeBranchTask->refreshCodeBranchTaskStatus($data['branch_task_id']);
		
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
		
		$dCodeIntegrationTask=D('CodeIntegrationTask');
		$dCodeIntegrationTask->refreshCodeBranchTaskStatus($dataInfo['integration_task_id']);
		
		$dCodeBranchTask=D('CodeBranchTask');
		$dCodeBranchTask->refreshCodeBranchTaskStatus($dataInfo['branch_task_id']);
		
		return true;
	}
	
}