<?php
namespace Common\Model;

class CodeIntegrationTaskModel extends DataBaseModel
{
    protected $tableName = 'code_integration_task';
	
	protected $_auto = array(
	   array('create_time','get_fulltime',1,'function'),
	   array('update_time','get_fulltime',3,'function'),
	   array('update_ip','get_client_addr',3,'function'),
	);
	
	
	protected $_validate=array( 
	   array('title','1,150','请输入正确的任务名称!',0,'length',3),
	   array('department_id','checkDepartment','请选择正确的产品组!',0,'callback',3),
	   array('state','checkState','请选择正确的账号状态!',0,'callback',3),
	);
	
	protected function initShowMap()
	{
		$dDepartment=D('UserDepartment');
		$departmentList=$dDepartment->getDepartmentKvList();
		$this->setShowMap('departmentList',$departmentList);
		
		$this->setShowMap('stateList',array(1=>'待完善',10=>'待集测',12=>'集测中',20=>'待灰测',22=>'灰测中',30=>'待发测',32=>'发测中',40=>'已发布'));
	}
	
	protected function checkDepartment($data)
	{
		
		if($data==0)
		{
			return true;
		}
		
		$dDepartment=D('UserDepartment');
		$departmentList=$dDepartment->getDepartmentKvList();
		

		if(!array_key_exists($data,$departmentList))
		{
			return false;
		}
		

		return true;
	}
	
	protected function checkState($data)
	{	
		return true;
	}
	
	
	
	
	public function addData($data)
	{
		//分支任务数据
		$taskData=array(
			'product_id'=>0,
			'department_id'=>$data['department_id'],
			'title'=>$data['title'],
			'state'=>1,
		);
		
		//获取产品线信息
		$dDepartment=D('UserDepartment');
		$productInfo=$dDepartment->getDepartmentProduct($taskData['department_id']);
		
		if($productInfo)
		{
			$taskData['product_id']=$productInfo['id'];
		}
		
		
		//创建任务
		
		if(!$this->create($taskData))
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
		
		
		$this->refreshCodeBranchTaskStatus($nId);
		
		$this->setError('创建分支任务成功,下一步请关联相关代码仓库!');
		return true;
	}
	
	public function editData($data)
	{
		$taskData=$this->getData($data['id']);
		
		if(!$taskData)
		{
			$this->setErrorMsg(510,'当前的任务不存在,请联系管理员!');
			return false;
		}
		
		
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
		
		$where=array('id'=>array('gt',0));
		
		if($option['department_id']>0)
		{
			$where['department_id']=$option['department_id'];
		}

		
		if($option['state']>-1)
		{
			$where['state']=$option['state'];
		}
		
		
		
		if($option['kw'])
		{
			$where['title']=array('like','%'.$option['kw'].'%');
		}
		
		$dataReturn=$this->getPageList($where,$order,$page,$pageSize);
		
		$showMap=$this->getShowMap();
		
	
		$idList=get_array_item_list($dataReturn['dataList'],'id');
		
		$kvListCodeIntegrationBranch=array();
		
		if($idList)
		{
            $dCodeIntegrationBranch=D('CodeIntegrationBranch');
			$kvListCodeIntegrationBranch=$dCodeIntegrationBranch->getIntegrationBranchKvList($idList);
		}
		

		foreach($dataReturn['dataList'] as &$item)
		{
		
			$item['department_name']=$showMap['departmentList'][$item['department_id']];
			$item['state_name']=$showMap['stateList'][$item['state']];
			
			$item['branch_count']=0;
			
			$id=$item['id'];
			
			if(array_key_exists($id,$kvListCodeIntegrationBranch))
			{
				$item['branch_count']=count($kvListCodeIntegrationBranch[$id]);
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
	
	//刷新代码分支任务状态
	public function refreshCodeBranchTaskStatus($id)
	{	
		$state=1;
		
		$data=$this->getData($id);
		
		$dCodeIntegrationBranch=D('CodeIntegrationBranch');
		$dataListIntegrationBranch=$dCodeIntegrationBranch->where(array('integration_task_id'=>intval($id),'state'=>1))->select();
		
		//还需要关联
		if(count($dataListIntegrationBranch)<1)
		{
			$state=1;
			
			$data['state']=$state;
			$this->saveData($data);
			
			return;
		}
			
		
		$state=10;

		$data['state']=$state;
		$this->saveData($data);
		
	}
	
	
}