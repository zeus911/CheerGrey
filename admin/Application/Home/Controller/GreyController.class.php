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
	
	
	public function backend_list()
	{
		$option=array(
			'page'=>I('post.page',1,'intval'),
			'pageSize'=>I('post.pageSize',50,'intval'),
			'config_id'=>I('post.config_id',0,'intval'),
			'kw'=>I('post.kw')
		);
		
		$this->assign('option',$option);
		
		
		$dGreyBackend=D('GreyBackend');
		
		$pageOption=$dGreyBackend->getShowMap();
		$this->assign('pageOption',$pageOption);

		$pageData=$dGreyBackend->getPageShowList($option);	
		$this->assign('pageData',$pageData);
		
	    $this->display('backend_list');
	}
	
	public function backend_add_pop()
	{
		$dGreyBackend=D('GreyBackend');
		
		$pageOption=$dGreyBackend->getShowMap();
		$this->assign('pageOption',$pageOption);
		
	   $this->display('backend_add_pop');
	}
	
	public function backend_edit_pop()
	{
		$option=array(
			'id'=>I('get.id',0,'intval'),
		);
		
		$dGreyBackend=D('GreyBackend');
		
		$pageOption=$dGreyBackend->getShowMap();
		$this->assign('pageOption',$pageOption);
		
		$data=$dGreyBackend->getData($option['id']);
		$this->assign('data',$data);
		
		$this->display('backend_edit_pop');
	}
	
	public function backend_node_manage_pop()
	{
		$option=array(
		    'page'=>I('post.page',1,'intval'),
			'pageSize'=>I('post.pageSize',1000,'intval'),
			'backend_id'=>I('get.id',0,'intval'),
		);
		
		$this->assign('option',$option);
		
		$dGreyBackendNode=D('GreyBackendNode');

		$pageData=$dGreyBackendNode->getPageShowList($option);	
		$this->assign('pageData',$pageData);
		
		
	   $this->display('backend_node_manage_pop');
	}
	
	public function backend_node_add_pop()
	{
		$option=array(
			'backend_id'=>I('get.backend_id',0,'intval'),
		);
		
		$this->assign('option',$option);
		
	   $this->display('backend_node_add_pop');
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
		$option=array(
			'config_id'=>I('get.config_id',-1,'intval'),
		);
		
		$this->assign('option',$option);
		
		$dGreyRuleSite=D('GreyRuleSite');
		
		$pageOption=$dGreyRuleSite->getShowMap();
		
		$dGreyBackend=D('GreyBackend');
		
		$backendDataList=$dGreyBackend->getAllDataKvList($option['config_id']);
		$backendList=array();
		foreach($backendDataList as $item)
		{
			$backendList[$item['id']]=sprintf('%s(%s)',$item['title'],$item['backend_name']);
		}
		$pageOption['backendList']=$backendList;
		
		
		$this->assign('pageOption',$pageOption);
		
		$this->display('site_add_pop');
	}
	
	public function site_edit_pop()
	{
		$option=array(
			'id'=>I('get.id',0,'intval'),
		);
		
		$dGreyRuleSite=D('GreyRuleSite');
		

		$data=$dGreyRuleSite->getData($option['id']);
		$this->assign('data',$data);
		
		$pageOption=$dGreyRuleSite->getShowMap();
		
		$dGreyBackend=D('GreyBackend');
		
		$backendDataList=$dGreyBackend->getAllDataKvList($data['config_id']);
		$backendList=array();
		foreach($backendDataList as $item)
		{
			$backendList[$item['id']]=sprintf('%s(%s)',$item['title'],$item['backend_name']);
		}
		
		$pageOption['backendList']=$backendList;
		
		$this->assign('pageOption',$pageOption);
		
		$this->display('site_edit_pop');
	}
	
	public function rule_list()
	{
		$option=array(
			'page'=>I('post.page',1,'intval'),
			'pageSize'=>I('post.pageSize',50,'intval'),
			'config_id'=>I('post.config_id',-1,'intval'),
			'site_id'=>I('post.site_id',0,'intval'),
			'mode'=>I('post.mode','','strval'),
			'kw'=>I('post.kw')
		);
		
		$this->assign('option',$option);
		
		
		$dGreyRuleNode=D('GreyRuleNode');
		
		$pageOption=$dGreyRuleNode->getShowMap();
		
		$dGreyRuleSite=D('GreyRuleSite');	
		$siteList=$dGreyRuleSite->getAllDataKvList($option['config_id']);
		
		$pageOption['siteList']=$siteList;
		
		
		$this->assign('pageOption',$pageOption);

		$pageData=$dGreyRuleNode->getPageShowList($option);	
		$this->assign('pageData',$pageData);
		
		$this->display('rule_list');
	}
	
	public function rule_add_pop()
	{
		$option=array(
			'config_id'=>I('get.config_id',-1,'intval'),
		);
		
		$this->assign('option',$option);
		
		$dGreyRuleNode=D('GreyRuleNode');
		
		$pageOption=$dGreyRuleNode->getShowMap();
		
		$dGreyRuleSite=D('GreyRuleSite');
		$siteDataList=$dGreyRuleSite->getAllDataKvList($option['config_id']);
		$pageOption['siteList']=$siteDataList;
		
		$dGreyBackend=D('GreyBackend');
		$backendDataList=$dGreyBackend->getAllDataKvList($option['config_id']);
		$backendList=array();
		foreach($backendDataList as $item)
		{
			$backendList[$item['id']]=sprintf('%s(%s)',$item['title'],$item['backend_name']);
		}
		$pageOption['backendList']=$backendList;
		
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
		
		$dGreyRuleSite=D('GreyRuleSite');
		$siteDataList=$dGreyRuleSite->getAllDataKvList($data['config_id']);
		$pageOption['siteList']=$siteDataList;
		
		$dGreyBackend=D('GreyBackend');
		$backendDataList=$dGreyBackend->getAllDataKvList($data['config_id']);
		$backendList=array();
		foreach($backendDataList as $item)
		{
			$backendList[$item['id']]=sprintf('%s(%s)',$item['title'],$item['backend_name']);
		}
		$pageOption['backendList']=$backendList;
		
		
		$this->assign('pageOption',$pageOption);
		
		$data=$dGreyRuleNode->getData($option['id']);
		$this->assign('data',$data);
		
		
		$this->display('rule_edit_pop');
	}
	

	
}