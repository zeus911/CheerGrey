<?php
namespace Common\Model;

class GreyRuleConfigModel extends DataBaseModel
{
    protected $tableName = 'grey_config_server';
	
	protected $_validate=array( 
	   array('config_name','1,100','请输入正确的配置名称!',0,'length',3),
	   array('server_host','1,100','请输入正确的服务器地址!',0,'length',3),
	   array('server_port','checkServerPort','请输入正确的服务器端口!',0,'callback',3),
	   array('server_db','checkServerDb','请输入正确的服务器DB!',0,'callback',3),
	   array('server_password','0,100','请输入正确的服务器密码!',0,'length',3),
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
	
	public function checkServerDb($data)
	{
		$checkRule='/^[0-9]+$/i';
		if(!preg_match($checkRule,$data))
		{
			return false;
		}
		
		$data=intval($data);
		
		if($data>=0&&$data<=15)
		{
			return true;
		}
		return false;
	}
	
	public function getAllDataKvList()
	{
		$data=array();
		
		$tempData=$this->select();
		
		foreach($tempData as $item)
		{
			$data[$item['id']]=$item['config_name'];
		}
		
		return $data;

	}
	
	public function addData($data)
	{
		
		if(!$this->create($data))
		{
			$this->setErrorCode(250);
			return false;
		}

		$id=$this->add();
		
		return $id;
		
	}
	
	public function editData($data)
	{
		$id=$data['id'];
		$nCount=$this->where(array('id'=>intval($id)))->count();
		if($nCount<1)
		{
			$this->setErrorMsg(250,'当前保存的信息不存在,操作失败!');
			return false;
		}
		
		if(!$this->create($data))
		{
			$this->setErrorCode(250);
			return false;
		}
		
		$this->save();
		
		return true;
		
	}
	
	public function delData($data)
	{
		$id=$data['id'];
		
		$dGreyRuleSite=D('GreyRuleSite');
		$nCount=$dGreyRuleSite->getCountConfig($id);
		if($nCount>0)
		{
			$this->setErrorMsg(250,'当前配置下还存在站点,无法删除!');
			return false;
		}
		
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
			$pageSize=50;
		}
		
		$where=array('id'=>array('gt',0));
		
		if($option['state']>-1)
		{
			$where['state']=$option['state'];
		}
		
		if($option['kw'])
		{
			$where['site_rule_name']=array('like','%'.$option['kw'].'%');
		}
		
		$dataReturn=$this->getPageList($where,$order,$page,$pageSize);
		
		
		$showMap=$this->getShowMap();
		

		foreach($dataReturn['dataList'] as &$item)
		{
			//$item['state_name']=$showMap['stateList'][$item['state']];
		}
		
		
		return $dataReturn;
		
	}
	
	public function getRedisHandle($id)
	{
		$redisHandle=false;
		
		$id=intval($id);
		
		$data=$this->getData($id);
		
		if(!$data)
		{
			return $redisHandle;
		}
			
		$configName=sprintf('REDIS_GREY_CONFIG_%s',$id);
		
		$configData=array(
			'REDIS_HOST'=>$data['server_host'],
			'REDIS_PORT'=>intval($data['server_port']),
			'REDIS_DB'=>intval($data['server_db']),
			'REDIS_PASSWORD'=>$data['server_password'],
		);
		
		C($configName,$configData);

		$redisHandle=\Com\Chw\RedisLib::getInstance($configName);
		
		return $redisHandle;
	}
	
}