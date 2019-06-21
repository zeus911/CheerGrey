<?php
namespace Home\Controller;

class GreyController extends AuthController 
{
	
	public function config_list()
	{
		
		$option=array(
			'page'=>I('post.page',1,'intval'),
			'pageSize'=>I('post.pageSize',50,'intval'),
			'kw'=>I('post.kw')
		);
		
		$this->assign('option',$option);
		
		
		$dGreyRuleConfig=D('GreyRuleConfig');
		
		$pageOption=$dGreyRuleConfig->getShowMap();
		$this->assign('pageOption',$pageOption);

		$pageData=$dGreyRuleConfig->getPageShowList($option);	
		$this->assign('pageData',$pageData);
		
		$this->display('config_list');
	}
	
	public function config_add_pop()
	{
		$dGreyRuleConfig=D('GreyRuleConfig');
		
		$pageOption=$dGreyRuleConfig->getShowMap();
		$this->assign('pageOption',$pageOption);
		
		$this->display('config_add_pop');
	}
	
	public function config_edit_pop()
	{
		$option=array(
			'id'=>I('get.id',0,'intval'),
		);
		
		$dGreyRuleConfig=D('GreyRuleConfig');
		
		$pageOption=$dGreyRuleConfig->getShowMap();
		$this->assign('pageOption',$pageOption);
		
		$data=$dGreyRuleConfig->getData($option['id']);
		$this->assign('data',$data);
		
		$this->display('config_edit_pop');
	}
	
	public function site_list()
	{
		
		$option=array(
			'page'=>I('post.page',1,'intval'),
			'pageSize'=>I('post.pageSize',50,'intval'),
			'config_id'=>I('post.config_id',0,'intval'),
			'state'=>I('post.state',-1,'intval'),
			'kw'=>I('post.kw')
		);
		
		$this->assign('option',$option);
		
		
		$dGreyRuleSite=D('GreyRuleSite');
		
		$pageOption=$dGreyRuleSite->getShowMap();
		$this->assign('pageOption',$pageOption);

		$pageData=$dGreyRuleSite->getPageShowList($option);	
		$this->assign('pageData',$pageData);
		
		$this->display('site_list');
	}
	
	public function site_add_pop()
	{
		$dGreyRuleSite=D('GreyRuleSite');
		
		$pageOption=$dGreyRuleSite->getShowMap();
		$this->assign('pageOption',$pageOption);
		
		$this->display('site_add_pop');
	}
	
	public function site_edit_pop()
	{
		$option=array(
			'id'=>I('get.id',0,'intval'),
		);
		
		$dGreyRuleSite=D('GreyRuleSite');
		
		$pageOption=$dGreyRuleSite->getShowMap();
		$this->assign('pageOption',$pageOption);
		
		$data=$dGreyRuleSite->getData($option['id']);
		$this->assign('data',$data);
		
		$this->display('site_edit_pop');
	}
	
	public function rule_list()
	{
		$option=array(
			'page'=>I('post.page',1,'intval'),
			'pageSize'=>I('post.pageSize',50,'intval'),
			'site_id'=>I('post.site_id',0,'intval'),
			'mode'=>I('post.mode','','strval'),
			'kw'=>I('post.kw')
		);
		
		$this->assign('option',$option);
		
		
		$dGreyRuleNode=D('GreyRuleNode');
		
		$pageOption=$dGreyRuleNode->getShowMap();
		$this->assign('pageOption',$pageOption);

		$pageData=$dGreyRuleNode->getPageShowList($option);	
		$this->assign('pageData',$pageData);
		
		$this->display('rule_list');
	}
	
	public function rule_add_pop()
	{
		$dGreyRuleNode=D('GreyRuleNode');
		
		$pageOption=$dGreyRuleNode->getShowMap();
		$this->assign('pageOption',$pageOption);
		
		$this->display('rule_add_pop');
	}
	
	public function rule_edit_pop()
	{
		$option=array(
			'id'=>I('get.id',0,'intval'),
		);
		
		$dGreyRuleNode=D('GreyRuleNode');
		
		$pageOption=$dGreyRuleNode->getShowMap();
		$this->assign('pageOption',$pageOption);
		
		$data=$dGreyRuleNode->getData($option['id']);
		$this->assign('data',$data);
		
		
		$this->display('rule_edit_pop');
	}
	
	
}