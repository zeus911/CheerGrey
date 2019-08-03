<?php
namespace Addon\ajax\Controller;
use Common\Controller\CommonController;

class UserController extends AuthController
{
	public function _initialize()
	{
		parent::_initialize();
	}
	
	public function user_info_sync()
	{
		$dUserInfo=D('UserInfo');
		
		$dUserInfo->syncUserInfo();
		
		$this->ajaxCallMsg(0,'操作成功!');
	}
	
	
	public function user_info_edit_pop()
	{
		$data=array(
			'id'=>I('post.id',0,'intval'),
		    'role_group_code'=>I('post.role_group_code','visitor','trim'),
			'role_group_level'=>I('post.role_group_level',1,'intval'),
			'department_id'=>I('post.department_id',0,'intval'),
			'state'=>I('post.state',0,'intval'),
		);
		
		
		$dUserInfo=D('UserInfo');
		
		$nId=$dUserInfo->editData($data);
		
		if(!$nId)
		{
			$msgData=$dUserInfo->getErrorMsg();
			$this->ajaxCallMsg($msgData['error_code'],$msgData['msg']);
		}
		
		$this->ajaxCallMsg(0,'操作成功!');
	}
	
	public function get_user_kv_list()
	{
		$option=array(
			'role_group_code'=>I('post.role_group_code','','trim'),
		);
		
		$dUserInfo=D('UserInfo');
		$dataList=$this->getKvList($option);
		
		$this->ajaxCallMsg(0,'succ.',$dataList);
	}
	
}