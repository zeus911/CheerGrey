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
	
	
	public function backend_add_pop()
	{
		$data=array(
		    'config_id'=>I('post.config_id',0,'intval'),
		    'title'=>I('post.title','','trim'),
			'backend_name'=>I('post.backend_name','','trim'),
		);
		
		
		$dGreyBackend=D('GreyBackend');
		
		$nId=$dGreyBackend->addData($data);
		
		if(!$nId)
		{
			$msgData=$dGreyBackend->getErrorMsg();
			$this->ajaxCallMsg($msgData['error_code'],$msgData['msg']);
		}
		
		$this->ajaxCallMsg(0,'操作成功!');
	}
	
	public function backend_edit_pop()
	{
		$data=array(
		    'id'=>I('post.id',0,'intval'),
		    'config_id'=>I('post.config_id',0,'intval'),
		    'title'=>I('post.title','','trim'),
			'backend_name'=>I('post.backend_name','','trim'),
		);
		
		
		$dGreyBackend=D('GreyBackend');
		
		$nId=$dGreyBackend->editData($data);
		
		if(!$nId)
		{
			$msgData=$dGreyBackend->getErrorMsg();
			$this->ajaxCallMsg($msgData['error_code'],$msgData['msg']);
		}
		
		$this->ajaxCallMsg(0,'操作成功!');
	}
	
	public function backend_del()
	{
		$data=array(
			'id'=>I('get.id',0,'intval'),
		);
		
		
		$dGreyBackend=D('GreyBackend');
		
		$nId=$dGreyBackend->delData($data);
		
		if(!$nId)
		{
			$msgData=$dGreyBackend->getErrorMsg();
			$this->ajaxCallMsg($msgData['error_code'],$msgData['msg']);
		}
		
		$this->ajaxCallMsg(0,'操作成功!');
	}
	
	public function backend_node_add_pop()
	{
		$data=array(
		    'backend_id'=>I('post.backend_id',0,'intval'),
		    'server_ip'=>I('post.server_ip','','trim'),
			'server_port'=>I('post.server_port',80,'intval'),
		);
		
		
		$dGreyBackendNode=D('GreyBackendNode');
		
		$nId=$dGreyBackendNode->addData($data);
		
		if(!$nId)
		{
			$msgData=$dGreyBackendNode->getErrorMsg();
			$this->ajaxCallMsg($msgData['error_code'],$msgData['msg']);
		}
		
		$this->ajaxCallMsg(0,'操作成功!');
	}
	
	public function backend_node_del_pop()
	{
		$data=array(
			'id'=>I('get.id',0,'intval'),
		);
		
		
		$dGreyBackendNode=D('GreyBackendNode');
		
		$nId=$dGreyBackendNode->delData($data);
		
		if(!$nId)
		{
			$msgData=$dGreyBackendNode->getErrorMsg();
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
			'prod_backend_id'=>I('post.prod_backend_id',0,'intval'),
			'grey_backend_id'=>I('post.grey_backend_id',0,'intval'),
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
		    'rule_site_name'=>I('post.rule_site_name','','trim'),
			'http_host'=>I('post.http_host','','trim'),
			'http_host_type'=>I('post.http_host_type','','trim'),
			'mode'=>I('post.mode','','trim'),
			'prod_backend_id'=>I('post.prod_backend_id',0,'intval'),
			'grey_backend_id'=>I('post.grey_backend_id',0,'intval'),
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
			'match_object'=>I('post.match_object','','trim'),
			'match_opp'=>I('post.match_opp','','trim'),
			'match_value'=>I('post.match_value','','trim'),
			'order_no'=>I('post.order_no',0,'intval'),
			'mode'=>I('post.mode','','trim'),
			'backend_id'=>I('post.backend_id',0,'intval'),
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
			'match_object'=>I('post.match_object','','trim'),
			'match_opp'=>I('post.match_opp','','trim'),
			'match_value'=>I('post.match_value','','trim'),
			'order_no'=>I('post.order_no',0,'intval'),
			'mode'=>I('post.mode','','trim'),
			'backend_id'=>I('post.backend_id',0,'intval'),
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