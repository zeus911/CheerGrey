<?php
namespace Home\Controller;
class UserController extends AuthController
{
    public function user_list()
	{
		$option=array(
			'page'=>I('post.page',1,'intval'),
			'pageSize'=>I('post.pageSize',50,'intval'),
			'kw'=>I('post.kw'),
			'department_id'=>I('post.department_id',0,'intval'),
			'role_group_code'=>I('post.role_group_code','','trim'),
			'role_group_level'=>I('post.role_group_level',0,'intval'),
			'state'=>I('post.state',-1,'intval'),
		);
		
		$this->assign('option',$option);
		
		
		$dUserInfo=D('UserInfo');
		
		$pageOption=$dUserInfo->getShowMap();
		$this->assign('pageOption',$pageOption);

		$pageData=$dUserInfo->getPageShowList($option);	
		$this->assign('pageData',$pageData);
		
		$this->display('user_list');
	}
	
	public function user_info_edit_pop()
	{
		
		
		$option=array(
			'id'=>I('get.id',0,'intval'),
		);
		
		$dUserInfo=D('UserInfo');
		
		$pageOption=$dUserInfo->getShowMap();
		$this->assign('pageOption',$pageOption);
		
		$data=$dUserInfo->getData($option['id']);
		$this->assign('data',$data);
		
		$this->display('user_info_edit_pop');
	}
}