<?php
namespace Addon\ajax\Controller;
use Common\Controller\CommonController;

class GreyController extends AuthController
{
	public function _initialize()
	{
		parent::_initialize();
	}
	
	
	public function config_add_pop()
	{
		$data=array(
		    'config_name'=>I('post.config_name','','trim'),
			'server_host'=>I('post.server_host','','trim'),
			'server_port'=>I('post.server_port',6379,'intval'),
			'server_db'=>I('post.server_db',0,'intval'),
			'server_password'=>I('post.server_password','','trim'),
		);
		
		
		$dGreyRuleConfig=D('GreyRuleConfig');
		
		$nId=$dGreyRuleConfig->addData($data);
		
		if(!$nId)
		{
			$msgData=$dGreyRuleConfig->getErrorMsg();
			$this->ajaxCallMsg($msgData['error_code'],$msgData['msg']);
		}
		
		$this->ajaxCallMsg(0,'操作成功!');
	}
	
	public function config_edit_pop()
	{
		$data=array(
			'id'=>I('post.id',0,'intval'),
		    'config_name'=>I('post.config_name','','trim'),
			'server_host'=>I('post.server_host','','trim'),
			'server_port'=>I('post.server_port',6379,'intval'),
			'server_db'=>I('post.server_db',0,'intval'),
			'server_password'=>I('post.server_password','','trim'),
		);
		
		
		$dGreyRuleConfig=D('GreyRuleConfig');
		
		$nId=$dGreyRuleConfig->editData($data);
		
		if(!$nId)
		{
			$msgData=$dGreyRuleConfig->getErrorMsg();
			$this->ajaxCallMsg($msgData['error_code'],$msgData['msg']);
		}
		
		$this->ajaxCallMsg(0,'操作成功!');
	}
	
	public function config_del()
	{
		$data=array(
			'id'=>I('get.id',0,'intval'),
		);
		
		
		$dGreyRuleConfig=D('GreyRuleConfig');
		
		$nId=$dGreyRuleConfig->delData($data);
		
		if(!$nId)
		{
			$msgData=$dGreyRuleConfig->getErrorMsg();
			$this->ajaxCallMsg($msgData['error_code'],$msgData['msg']);
		}
		
		$this->ajaxCallMsg(0,'操作成功!');
	}
	
	public function site_add_pop()
	{
		$data=array(
			'config_id'=>I('post.config_id',0,'intval'),
		    'rule_site_name'=>I('post.rule_site_name','','trim'),
			'http_host'=>I('post.http_host','','trim'),
			'http_host_type'=>I('post.http_host_type','','trim'),
			'mode'=>I('post.mode','','trim'),
			'prod_node'=>I('post.prod_node','','trim'),
			'grey_node'=>I('post.grey_node','','trim'),
		);
		
		
		$dGreyRuleSite=D('GreyRuleSite');
		
		$nId=$dGreyRuleSite->addData($data);
		
		if(!$nId)
		{
			$msgData=$dGreyRuleSite->getErrorMsg();
			$this->ajaxCallMsg($msgData['error_code'],$msgData['msg']);
		}
		
		$this->ajaxCallMsg(0,'操作成功!');
	}
	
	public function site_edit_pop()
	{
		$data=array(
			'id'=>I('post.id',0,'intval'),
			'config_id'=>I('post.config_id',0,'intval'),
		    'rule_site_name'=>I('post.rule_site_name','','trim'),
			'http_host'=>I('post.http_host','','trim'),
			'http_host_type'=>I('post.http_host_type','','trim'),
			'mode'=>I('post.mode','','trim'),
			'prod_node'=>I('post.prod_node','','trim'),
			'grey_node'=>I('post.grey_node','','trim'),
			'state'=>I('post.state',0,'intval'),
		);
		
		
		$dGreyRuleSite=D('GreyRuleSite');
		
		$nId=$dGreyRuleSite->editData($data);
		
		if(!$nId)
		{
			$msgData=$dGreyRuleSite->getErrorMsg();
			$this->ajaxCallMsg($msgData['error_code'],$msgData['msg']);
		}
		
		$this->ajaxCallMsg(0,'操作成功!');
	}
	
	public function site_pub()
	{
		$data=array(
			'id'=>I('get.id',0,'intval'),
		);
		
		
		$dGreyRuleSite=D('GreyRuleSite');
		
		$nId=$dGreyRuleSite->pubData($data);
		
		if(!$nId)
		{
			$msgData=$dGreyRuleSite->getErrorMsg();
			$this->ajaxCallMsg($msgData['error_code'],$msgData['msg']);
		}
		
		$this->ajaxCallMsg(0,'操作成功!');
	}
	
	public function site_del()
	{
		$data=array(
			'id'=>I('get.id',0,'intval'),
		);
		
		
		$dGreyRuleSite=D('GreyRuleSite');
		
		$nId=$dGreyRuleSite->delData($data);
		
		if(!$nId)
		{
			$msgData=$dGreyRuleSite->getErrorMsg();
			$this->ajaxCallMsg($msgData['error_code'],$msgData['msg']);
		}
		
		$this->ajaxCallMsg(0,'操作成功!');
	}
	
	public function rule_add_pop()
	{
		$data=array(
			'site_id'=>I('post.site_id',0,'intval'),
			'tenant_code'=>I('post.tenant_code','','trim'),
			'mode'=>I('post.mode','','trim'),
		);
		
		
		$dGreyRuleNode=D('GreyRuleNode');
		
		$nId=$dGreyRuleNode->addData($data);
		
		if(!$nId)
		{
			$msgData=$dGreyRuleNode->getErrorMsg();
			$this->ajaxCallMsg($msgData['error_code'],$msgData['msg']);
		}
		
		$this->ajaxCallMsg(0,'操作成功!');
	}
	
	public function rule_edit_pop()
	{
		$data=array(
			'id'=>I('post.id',0,'intval'),
		   	'site_id'=>I('post.site_id',0,'intval'),
			'tenant_code'=>I('post.tenant_code','','trim'),
			'mode'=>I('post.mode','','trim'),
		);
		
		
		$dGreyRuleNode=D('GreyRuleNode');
		
		$nId=$dGreyRuleNode->editData($data);
		
		if(!$nId)
		{
			$msgData=$dGreyRuleNode->getErrorMsg();
			$this->ajaxCallMsg($msgData['error_code'],$msgData['msg']);
		}
		
		$this->ajaxCallMsg(0,'操作成功!');
	}
	
	public function rule_del()
	{
		$data=array(
			'id'=>I('get.id',0,'intval'),
		);
		
		
		$dGreyRuleNode=D('GreyRuleNode');
		
		$nId=$dGreyRuleNode->delData($data);
		
		if(!$nId)
		{
			$msgData=$dGreyRuleNode->getErrorMsg();
			$this->ajaxCallMsg($msgData['error_code'],$msgData['msg']);
		}
		
		$this->ajaxCallMsg(0,'操作成功!');
	}
	
	
}