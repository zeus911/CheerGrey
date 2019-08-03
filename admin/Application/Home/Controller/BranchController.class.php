<?php
namespace Home\Controller;

class BranchController extends AuthController 
{
	
	public function code_branch_task_list()
	{
		$option=array(
			'page'=>I('post.page',1,'intval'),
			'pageSize'=>I('post.pageSize',50,'intval'),
			'department_id'=>I('post.department_id',0,'intval'),
			'task_type'=>I('post.task_type','','trim'),
			'state'=>I('post.state',-1,'intval'),
			'kw'=>I('post.kw')
		);
		
		$this->assign('option',$option);
		
		$dCodeBranchTask=D('CodeBranchTask');
		
		$pageOption=$dCodeBranchTask->getShowMap();
		$this->assign('pageOption',$pageOption);

		$pageData=$dCodeBranchTask->getPageShowList($option);	
		$this->assign('pageData',$pageData);
		
		$this->display('code_branch_task_list');
	}
	
	public function feature_task_add_pop()
	{
		
		$dUserDepartment=D('UserDepartment');
		
		$departmentKvList=$dUserDepartment->getDepartmentKvList();
		
		$pageOption=array('departmentKvList'=>$departmentKvList);
		
		
		
		$dUserInfo=D('UserInfo');
		
		$rdOption=array('role_group_type'=>'rd');
		$rdDataList=$dUserInfo->getKvList($rdOption);
		
		$pageOption['rdDataList']=$rdDataList;

		$qaOption=array('role_group_type'=>'qa');
		$qaDataList=$dUserInfo->getKvList($qaOption);
		
		$pageOption['qaDataList']=$qaDataList;

		$this->assign('pageOption',$pageOption);
		
		$this->display('feature_task_add_pop');
	}
	
	public function fix_task_add_pop()
	{
		
		$dUserDepartment=D('UserDepartment');
		
		$departmentKvList=$dUserDepartment->getDepartmentKvList();
		
		$pageOption=array('departmentKvList'=>$departmentKvList);
		

		$dUserInfo=D('UserInfo');
		
		$rdOption=array('role_group_type'=>'rd');
		$rdDataList=$dUserInfo->getKvList($rdOption);
		
		$pageOption['rdDataList']=$rdDataList;

		$qaOption=array('role_group_type'=>'qa');
		$qaDataList=$dUserInfo->getKvList($qaOption);
		
		$pageOption['qaDataList']=$qaDataList;

		$this->assign('pageOption',$pageOption);
		
		$this->display('fix_task_add_pop');
	}
	
	public function code_branch_task_edit_pop()
	{
		$id=I('get.id',0,'intval');
			
		$pageOption=array();
		
		
		$dUserInfo=D('UserInfo');
		
		$rdOption=array('role_group_type'=>'rd');
		$rdDataList=$dUserInfo->getKvList($rdOption);
		
		$pageOption['rdDataList']=$rdDataList;

		$qaOption=array('role_group_type'=>'qa');
		$qaDataList=$dUserInfo->getKvList($qaOption);
		
		$pageOption['qaDataList']=$qaDataList;

		$this->assign('pageOption',$pageOption);
		
		$dCodeBranchTask=D('CodeBranchTask');
		$data=$dCodeBranchTask->getTaskData($id);
		
		
		$this->assign('data',$data);
		
		$this->display('code_branch_task_edit_pop');
	}
	
	public function code_branch_repository_manage_pop()
	{
		$taskId=I('get.id',0,'intval');
		
		$this->assign('taskId',$taskId);
		
		$dCodeBranchRepository=D('CodeBranchRepository');
		
		$dataList=$dCodeBranchRepository->getDataList($taskId);
		
		$this->assign('dataList',$dataList);
		
		$this->display('code_branch_repository_manage_pop');
	}
	
	public function code_branch_repository_add_pop()
	{
		
		$option=array(
			'task_id'=>I('task_id',0,'intval'),
			'group_uuid'=>I('group_uuid',"",'trim')
		);
		
		$this->assign('option',$option);
		
		$dCodeRepository=D('CodeRepository');
		
		$groupDataList=$dCodeRepository->getGroupDataList();		
		$respDataList=$dCodeRepository->searchDataList($option);
		
		$this->assign('groupDataList',$groupDataList);
		$this->assign('respDataList',$respDataList);
		
		$this->display('code_branch_repository_add_pop');
	}
	
	public function code_integration_task_list()
	{
		$option=array(
			'page'=>I('post.page',1,'intval'),
			'pageSize'=>I('post.pageSize',50,'intval'),
			'department_id'=>I('post.department_id',0,'intval'),
			'task_type'=>I('post.task_type','','trim'),
			'state'=>I('post.state',-1,'intval'),
			'kw'=>I('post.kw')
		);
		
		$this->assign('option',$option);
		
		$dCodeIntegrationTask=D('CodeIntegrationTask');
		
		$pageOption=$dCodeIntegrationTask->getShowMap();
		$this->assign('pageOption',$pageOption);

		$pageData=$dCodeIntegrationTask->getPageShowList($option);
		$this->assign('pageData',$pageData);
		
		$this->display('code_integration_task_list');
	}
	
	public function code_integration_task_add_pop()
	{
		$dUserDepartment=D('UserDepartment');
		
		$departmentKvList=$dUserDepartment->getDepartmentKvList();
		
		$pageOption=array('departmentKvList'=>$departmentKvList);
		
		$this->assign('pageOption',$pageOption);
		
		$this->display('code_integration_task_add_pop');
	}
	
	public function code_integration_branch_manage_pop()
	{
		$integrationTaskId=I('get.id',0,'intval');
		
		$this->assign('integrationTaskId',$integrationTaskId);
		
		$dCodeIntegrationBranch=D('CodeIntegrationBranch');
		
		$dataList=$dCodeIntegrationBranch->getIntegrationBranchList($integrationTaskId);
		
		$this->assign('dataList',$dataList);
		
		$this->display('code_integration_branch_manage_pop');
	}
	
	public function code_integration_branch_add_pop()
	{
		$option=array(
			'integration_task_id'=>I('integration_task_id',0,'intval'),
			'department_id'=>I('department_id',0,'intval')
		);

		
		$this->assign('option',$option);
		
		
		$dUserDepartment=D('UserDepartment');
		
		$departmentKvList=$dUserDepartment->getDepartmentKvList();
		
		$pageOption=array('departmentKvList'=>$departmentKvList);
		
		$dCodeBranchTask=D('CodeBranchTask');
		$codeBranchTaskList=$dCodeBranchTask->getDepartmentTaskList($option['department_id']);
		
		$pageOption=array('departmentKvList'=>$departmentKvList,'codeBranchTaskList'=>$codeBranchTaskList);

		$this->assign('pageOption',$pageOption);
		
		
		$this->display('code_integration_branch_add_pop');
	}
	
	public function code_integration_task_edit_pop()
	{
	   $this->display('code_integration_task_edit_pop');
	}
	
	
}