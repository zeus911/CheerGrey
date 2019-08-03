<?php
namespace Addon\ajax\Controller;
use Common\Controller\CommonController;

class BranchController extends AuthController
{
	public function _initialize()
	{
		parent::_initialize();
	}
	
	public function get_code_story_list()
	{
		$department_id=I('post.department_id',0,'intval');
		
		$dCodeStory=D('CodeStory');
		
		$departmentCodeStoryList=$dCodeStory->getDepartmentCodeStoryList($department_id);
		
		$data=array('count'=>count($departmentCodeStoryList),'dataList'=>$departmentCodeStoryList);
		
		
		$this->ajaxCallMsg(0,'succ.',$data);
		
	}
	
	public function get_code_story_user_list()
	{
		$id=I('post.id',0,'intval');
		
		$dCodeStory=D('CodeStory');
		
		$data=$dCodeStory->getRoleUserList($id);
		
		$this->ajaxCallMsg(0,'succ.',$data);
	}
	
	//添加分支任务
	public function code_branch_task_add_pop()
	{
		$data=array(
		    'department_id'=>I('post.department_id',0,'intval'),
			'story_id'=>I('post.story_id',0,'intval'),
			'rd_uid'=>I('post.rd_uid',0,'intval'),
			'qa_uid'=>I('post.qa_uid',0,'intval'),
			'task_type'=>I('post.task_type','none','trim')
		);
			
		$dCodeBranchTask=D('CodeBranchTask');
		
		$nId=$dCodeBranchTask->addData($data);
		
		$msgData=$dCodeBranchTask->getErrorMsg();
		
		if(!$nId)
		{
			$this->ajaxCallMsg($msgData['error_code'],$msgData['msg']);
		}
		
		$this->ajaxCallMsg(0,$msgData['msg']);
	}
	
	//修改分支任务
	public function code_branch_task_edit_pop()
	{
		$data=array(
			'id'=>I('post.id',0,'intval'),
			'rd_uid'=>I('post.rd_uid',0,'intval'),
			'qa_uid'=>I('post.qa_uid',0,'intval')
		);
			
		$dCodeBranchTask=D('CodeBranchTask');
		
		$ret=$dCodeBranchTask->editData($data);
		
		$msgData=$dCodeBranchTask->getErrorMsg();
		
		if(!$ret)
		{
			$this->ajaxCallMsg($msgData['error_code'],$msgData['msg']);
		}
		
		$this->ajaxCallMsg(0,'操作成功!');
	}
	
	//添加分支关联仓库
	public function code_branch_repository_add_pop()
	{
		$data=array(
		    'task_id'=>I('post.task_id',0,'intval'),
			'repository_id'=>I('post.repos_id',0,'intval'),
		);
		
		$dCodeBranchRepository=D('CodeBranchRepository');
		
		$nId=$dCodeBranchRepository->addData($data);	
		$msgData=$dCodeBranchRepository->getErrorMsg();
		
		if(!$nId)
		{
			$this->ajaxCallMsg($msgData['error_code'],$msgData['msg']);
		}
		
		$this->ajaxCallMsg(0,'操作成功!');
		
	}
	
	//删除分支关联的仓库
	public function code_branch_repository_del_pop()
	{
		$data=array(
		    'id'=>I('get.id',0,'intval'),
		);
		
		$dCodeBranchRepository=D('CodeBranchRepository');
		
		$oppRet=$dCodeBranchRepository->delData($data);
		
		$msgData=$dCodeBranchRepository->getErrorMsg();
		
		if(!$oppRet)
		{
			$this->ajaxCallMsg($msgData['error_code'],$msgData['msg']);
		}
		
		$this->ajaxCallMsg(0,'操作成功!');
	}
	
	//添加集成任务
	public function code_integration_task_add_pop()
	{
		$data=array(
		    'department_id'=>I('post.department_id',0,'intval'),
			'title'=>I('post.title','','trim'),
		);
		
		$dCodeIntegrationTask=D('CodeIntegrationTask');
		
		$oppRet=$dCodeIntegrationTask->addData($data);
		
		$msgData=$dCodeIntegrationTask->getErrorMsg();
		
		if(!$oppRet)
		{
			$this->ajaxCallMsg($msgData['error_code'],$msgData['msg']);
		}
		
		$this->ajaxCallMsg(0,'操作成功!');
		
	}
	
	//添加集成任务与分支任务的关联
	public function code_integration_branch_add_pop()
	{
		$data=array(
		    'integration_task_id'=>I('post.code_integration_task_id',0,'intval'),
			'branch_task_id'=>I('post.code_branch_task_id',0,'intval'),
		);
		
		$dCodeIntegrationBranch=D('CodeIntegrationBranch');
		
		$oppRet=$dCodeIntegrationBranch->addData($data);
		
		$msgData=$dCodeIntegrationBranch->getErrorMsg();
		
		if(!$oppRet)
		{
			$this->ajaxCallMsg($msgData['error_code'],$msgData['msg']);
		}
		
		$this->ajaxCallMsg(0,'操作成功!');
	}
	
	public function code_integration_branch_del_pop()
	{
		$data=array(
		    'id'=>I('get.id',0,'intval'),
		);
		
		$dCodeIntegrationBranch=D('CodeIntegrationBranch');
		
		$oppRet=$dCodeIntegrationBranch->delData($data);
		
		$msgData=$dCodeIntegrationBranch->getErrorMsg();
		
		if(!$oppRet)
		{
			$this->ajaxCallMsg($msgData['error_code'],$msgData['msg']);
		}
		
		$this->ajaxCallMsg(0,'操作成功!');
	}
}