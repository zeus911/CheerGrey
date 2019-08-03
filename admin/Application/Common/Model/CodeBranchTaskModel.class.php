<?php
namespace Common\Model;

class CodeBranchTaskModel extends DataBaseModel
{
    protected $tableName = 'code_branch_task';
	
	protected $_auto = array(
	   array('create_time','get_fulltime',1,'function'),
	   array('update_time','get_fulltime',3,'function'),
	   array('update_ip','get_client_addr',3,'function'),
	);
	
	
	protected $_validate=array( 
	   array('product_id','checkProductId','请选择正确的产品产品线!',0,'callback',3),
	   array('department_id','checkDepartmentId','请选择正确的产品组!',0,'callback',3),
	   array('task_type',array('feature_task','fix_task'),'请选择正确的分支任务类型!',0,'in',3),
	   array('title','1,150','关联需求名称长度范围1-150!',0,'length',3),
	   array('story_id','checkStoryId','请选择正确的需求!',0,'callback',3),
	   array('state','checkState','请选择正确的账号状态!',0,'callback',3),
	);
	
	protected function initShowMap()
	{
		$dDepartment=D('UserDepartment');
		$departmentList=$dDepartment->getDepartmentKvList();
		$this->setShowMap('departmentList',$departmentList);
		
		
		$this->setShowMap('taskTypeList',array('feature_task'=>'特性分支','fix_task'=>'修复分支'));
		
		$stateList=array(1=>'任务待完善',10=>'仓库待关联',12=>'分支创建中',20=>'任务待集成',30=>'集成待审核',40=>'任务待发布',80=>'任务已发布',100=>'任务已关闭');
		
		$this->setShowMap('stateList',$stateList);
	}
	
	protected function checkProductId($data)
	{
		$dCheck=D('UserDepartment');
		$checkData=$dCheck->getData(intval($data));
		
		if(!$checkData)
		{
			return false;
		}
		
		return true;
	}
	
	protected function checkDepartmentId($data)
	{
		$dCheck=D('UserDepartment');
		$checkData=$dCheck->getData(intval($data));
		
		if(!$checkData)
		{
			return false;
		}
		
		return true;
	}
	
	protected function checkStoryId($data)
	{
		$dCheck=D('CodeStory');
		$checkData=$dCheck->getData(intval($data));
		
		if(!$checkData)
		{
			return false;
		}
		
		return true;
	}
	
	protected function checkState($data)
	{	
		return true;
	}
	
	public function getTaskData($id)
	{
		$data=$this->getData($id);
		
		if(!$data)
		{
			return $data;
		}
		
		$dDepartment=D('UserDepartment');
		
		$departmentData=$dDepartment->getData($data['department_id']);
		
		$data['department_name']=$departmentData['title'];
		
		
		return $data;
	}
	
	
	public function addData($data)
	{		
		$dUserInfo=D('UserInfo');
		
		$rdUserInfo=$dUserInfo->getData($data['rd_uid']);
		if(!$rdUserInfo)
		{
			$this->setErrorMsg(250,'请选择正确的RD工程师!');
			return false;
		}
		
		
		$data['rd_username']=$rdUserInfo['user_name'];
		$data['rd_realname']=$rdUserInfo['realname'];
		
		$qaUserInfo=$dUserInfo->getData($data['qa_uid']);
		if(!$qaUserInfo)
		{
			$this->setErrorMsg(250,'请选择正确的QA工程师!');
			return false;
		}
		
		$data['qa_username']=$qaUserInfo['user_name'];
		$data['qa_realname']=$qaUserInfo['realname'];
		
		$data['product_id']=0;
		
		//获取产品线信息
		$dDepartment=D('UserDepartment');
		$productInfo=$dDepartment->getDepartmentProduct($data['department_id']);
		
		if($productInfo)
		{
			$data['product_id']=$productInfo['id'];
		}
		
		
		//获取需求信息
		$dCodeStory=D('CodeStory');
		$storyInfo=$dCodeStory->getData($data['story_id']);
		
		if(!$storyInfo)
		{
			$this->setErrorMsg(251,'请选择正确的关联需求!');
			return false;
		}
		
		if($storyInfo['state']>1||$storyInfo['state']<1)
		{
			$this->setErrorMsg(252,'当前选择关联的需求已被其他任务关联或者不可用!');
			return false;
		}
		
		$data['title']=$storyInfo['story_name'];
		$data['story_code']=$storyInfo['story_code'];
		$data['story_url']=$storyInfo['story_url'];
		
		//创建任务	
		if(!$this->create($data))
		{
			$this->setErrorMsg(253,$this->getError());
			return false;
		}
		
		$nId=$this->add();
		
		if(!$nId)
		{
			$this->setErrorMsg(254,'创建分支任务写入数据库失败,请联系系统管理员!');
			return false;
		}
		
		
		//更新原始需求状态
		$storyUpateData=array('id'=>$storyInfo['id'],'state'=>10);
		$dCodeStory->saveData($storyUpateData);
		
		$this->refreshCodeBranchTaskStatus($nId);
		
		$this->setError('创建分支任务成功,下一步请关联相关代码仓库!');
		return true;
	}
	
	public function editData($option)
	{
		$data=$this->getData($option['id']);
		
		if(!$data)
		{
			$this->setErrorMsg(510,'当前的任务不存在,请联系管理员!');
			return false;
		}

        $dUserInfo=D('UserInfo');
		
		$rdUserInfo=$dUserInfo->getData($option['rd_uid']);
		if(!$rdUserInfo)
		{
			$this->setErrorMsg(250,'请选择正确的RD工程师!');
			return false;
		}
		
		$data['rd_uid']=$rdUserInfo['id'];
		$data['rd_username']=$rdUserInfo['user_name'];
		$data['rd_realname']=$rdUserInfo['realname'];
		
		$qaUserInfo=$dUserInfo->getData($option['qa_uid']);
		if(!$qaUserInfo)
		{
			$this->setErrorMsg(250,'请选择正确的QA工程师!');
			return false;
		}
		
		$data['qa_uid']=$qaUserInfo['id'];
		$data['qa_username']=$qaUserInfo['user_name'];
		$data['qa_realname']=$qaUserInfo['realname'];

		
		if(!$this->create($data))
		{
			$this->setErrorMsg(251,$this->getError());
			return false;
		}
		
		$this->save();
		
		
		$this->refreshCodeBranchTaskStatus($nId);
		
		$this->setError('操作成功!');
		return true;
	}
	
	public function getPageShowList($option=array())
	{
		
		$order='state asc,update_time desc';
		$page=intval($option['page']);
		$pageSize=intval($option['pageSize']);
		if($pageSize<1)
		{
			$pageSize=50;
		}
		
		$where=array('state'=>array('gt',0));
		
		if($option['department_id']>0)
		{
			$where['department_id']=$option['department_id'];
		}
		
		
		if($option['task_type'])
		{
			$where['task_type']=$option['task_type'];
		}
		
		
		if($option['state']>0)
		{
			$where['state']=$option['state'];
		}
		
		
		
		if($option['kw'])
		{
			$where['title']=array('like','%'.$option['kw'].'%');
		}
		
		$dataReturn=$this->getPageList($where,$order,$page,$pageSize);
		
		
		$showMap=$this->getShowMap();
		
		$engineerList=array();
		$storyList=array();
		$repositoryList=array();
		
		$idList=get_array_item_list($dataReturn['dataList'],'id');
		
		if($idList)
		{	
			$dCodeBranchRepository=D('CodeBranchRepository');
			$repositoryList=$dCodeBranchRepository->where(array('task_id'=>array('in',$idList),'state'=>1))->select();
		}
		

		foreach($dataReturn['dataList'] as &$item)
		{
			$item['task_type_name']=$showMap['taskTypeList'][$item['task_type']];
			$item['department_name']=$showMap['departmentList'][$item['department_id']];
			$item['state_name']=$showMap['stateList'][$item['state']];
			
			$item['repository_count']=0;
			
			foreach($repositoryList as $repositoryItem)
			{
				if($item['id']==$repositoryItem['task_id'])
				{
					$item['repository_count']++;
				}
			}

		}
		
		
		return $dataReturn;
		
	}
	
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
	
	public function getDepartmentTaskList($department_id)
	{
		$where=array('department_id'=>intval($department_id),'state'=>20);
		
		$dataList=$this->where($where)->select();
		
		return $dataList;
	}
	
	public function getKvDataDetailList($idList=array())
	{
		$dataList=array();
		
		if(!$idList)
		{
			return $dataList;
		}
		
		$where=array('id'=>array('in',$idList));
		$tempDataList=$this->where($where)->select();
		
		$showMap=$this->getShowMap();
		
		foreach($tempDataList as &$item)
		{
			$item['task_type_name']=$showMap['taskTypeList'][$item['task_type']];
			$item['department_name']=$showMap['departmentList'][$item['department_id']];
			$item['state_name']=$showMap['stateList'][$item['state']];
			
			$dataList[$item['id']]=$item;
		}
		
		return $dataList;
	}
	
	
	
	//刷新代码分支任务状态
	public function refreshCodeBranchTaskStatus($id)
	{
		$state=1;
		
		$data=array('id'=>intval($id));
		
		
		
		
		
		$repositoryFinished=false;
		
		$dCodeBranchRepository=D('CodeBranchRepository');
		
		$repositoryWhere=array('task_id'=>intval($id),'state'=>array('gt',0));
		
		$repositoryList=$dCodeBranchRepository->where($repositoryWhere)->select();
		
		//仓库待关联
		if(count($repositoryList)<1)
		{
			$state=10;
			
			$data['state']=$state;
			$this->saveData($data);
			
			return;
		}
		
		$bAllReposCreated=true;
		
		foreach($repositoryList as $reposItem)
		{
			if($reposItem['state']==1)
			{
				$bAllReposCreated=false;
				break;
			}
		}
		
		//分支创建中
		if(!$bAllReposCreated)
		{
			$state=12;
			
			$data['state']=$state;
			$this->saveData($data);
			
			return;
		}
		
		
		
		$dCodeIntegrationBranch=D('CodeIntegrationBranch');
		$dataListIntegrationBranch=$dCodeIntegrationBranch->where(array('branch_task_id'=>intval($id),'state'=>1))->select();
		
		//分支待集成
		if(count($dataListIntegrationBranch)<1)
		{
			$state=20;
			
			$data['state']=$state;
			$this->saveData($data);
			
			return;
		}
		
		

		$state=30;

		$data['state']=$state;
		$this->saveData($data);
		
		
	}
	
	
}