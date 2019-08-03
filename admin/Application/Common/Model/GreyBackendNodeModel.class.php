<?php
namespace Common\Model;

class GreyBackendNodeModel extends DataBaseModel
{
    protected $tableName = 'grey_backend_node';
	
	protected $_validate=array( 
	   array('config_id','checkConfigId','请选择正确的配置服务!',0,'callback',3),
	   array('backend_id','checkBackendId','请选择正确的后端节点!',0,'callback',3),
	   array('server_ip','checkServerIp','请输入正确的服务器地址!',0,'callback',3),
	   array('server_port','checkServerPort','请输入正确的服务器端口!',0,'callback',3),
	);
	
	protected $_auto = array(
	   array('create_time','get_fulltime',1,'function'),
	   array('update_time','get_fulltime',3,'function'),
	   array('update_ip','get_client_addr',3,'function'),
	);
	
	protected function initShowMap()
	{
		$this->setShowMap('stateList',array(0=>'禁用',1=>'启用'));	
	}
	
	
	public function checkConfigId($data)
	{
		$checkRule='/^[0-9]+$/i';
		if(!preg_match($checkRule,$data))
		{
			return false;
		}
		
		$dGreyRuleConfig=D('GreyRuleConfig');
		
		$dataInfo=$dGreyRuleConfig->getData($data);
		if(!$dataInfo)
		{
			return false;
		}
		
		return true;
	}
	
	public function checkBackendId($data)
	{
		$checkRule='/^[0-9]+$/i';
		if(!preg_match($checkRule,$data))
		{
			return false;
		}
		
		$dGreyBackend=D('GreyBackend');
		
		$dataInfo=$dGreyBackend->getData($data);
		if(!$dataInfo)
		{
			return false;
		}
		
		return true;
	}
	
	public function checkServerIp($data)
	{
		$checkRule='/^[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}$/i';
		if(!preg_match($checkRule,$data))
		{
			return false;
		}
		
		return true;
	}
	
	public function checkServerPort($data)
	{
		$checkRule='/^[0-9]+$/i';
		if(!preg_match($checkRule,$data))
		{
			return false;
		}
		
		$data=intval($data);
		if($data>1&&$data<65536)
		{
			return true;
		}
		return false;
	}
	
	public function addData($data)
	{
		$data['config_id']=0;
		
		if($data['backend_id']>0)
		{
			$dGreyBackend=D('GreyBackend');
			$backendInfo=$dGreyBackend->getData($data['backend_id']);
			
			if($backendInfo)
			{
				$data['config_id']=$backendInfo['config_id'];
			}
			
		}
		
		if(!$this->create($data))
		{
			$this->setErrorCode(250);
			return false;
		}

		$id=$this->add();
		
		return $id;
		
	}
	

	
	public function delData($data)
	{
		$id=$data['id'];
		
		$this->where(array('id'=>intval($id)))->delete();
		
		return true;
	}
	
	
	public function getPageShowList($option=array())
	{
		
		$order='update_time desc';
		$page=intval($option['page']);
		$pageSize=intval($option['pageSize']);
		if($pageSize<1)
		{
			$pageSize=1000;
		}
		
		$where=array('id'=>array('gt',0));
		
		if($option['backend_id']>0)
		{
			$where['backend_id']=$option['backend_id'];
		}
		
		$dataReturn=$this->getPageList($where,$order,$page,$pageSize);
		
		return $dataReturn;
		
	}
}