<?php
namespace Common\Model;

class GreyBackendModel extends DataBaseModel
{
    protected $tableName = 'grey_backend';
	
	protected $_validate=array( 
	   array('config_id','checkConfigId','请选择正确的配置服务器!',0,'callback',3),
	   array('tile','1,100','请输入正确的后端标题!',0,'length',3),
	   array('backend_name','/^(prod|grey|private)_[a-z0-9]+_[a-z0-9_]+$/','请输入正确的后端名称!',0,'regex',3),
	);
	
	protected $_auto = array(
	   array('create_time','get_fulltime',1,'function'),
	   array('update_time','get_fulltime',3,'function'),
	   array('update_ip','get_client_addr',3,'function'),
	);
	
	protected function initShowMap()
	{
		$dGreyRuleConfig=D('GreyRuleConfig');	
		$configRuleList=$dGreyRuleConfig->getAllDataKvList();
		$this->setShowMap('configRuleList',$configRuleList);
		
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
	
	public function getAllDataKvList($config_id=0)
	{
		$data=array();
		
		$where=array('state'=>1);
		
		if($config_id)
		{
			$where['config_id']=$config_id;
		}
		
		$tempData=$this->where($where)->select();
		
		foreach($tempData as $item)
		{
			$data[$item['id']]=$item;
		}
		
		return $data;

	}
	
	public function getAllDataKvListByConfigIds($idList=array())
	{
		$data=array();
		
		$where=array('state'=>1);
		
		if($idList)
		{
			$where['config_id']=array('in',$idList);
		}
		
		$tempData=$this->where($where)->select();
		
		foreach($tempData as $item)
		{
			$data[$item['id']]=$item;
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
		
		$dGreyBackendNode=D('GreyBackendNode');
		$nodeInfo=$dGreyBackendNode->getData($id);
		if($nodeInfo)
		{
			$this->setErrorMsg(250,'当前后端节点下还存在服务器节点,无法删除!');
			return false;
		}
		
		$this->where(array('id'=>intval($id)))->delete();
		
		return true;
	}
	
	
	public function getPageShowList($option=array())
	{
		
		$order='backend_name desc,update_time desc';
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
		
		if($option['config_id']>0)
		{
			$where['config_id']=$option['config_id'];
		}
		
		if($option['kw'])
		{
			$where['title']=array('like','%'.$option['kw'].'%');
		}
		
		$dataReturn=$this->getPageList($where,$order,$page,$pageSize);
		
		
		$showMap=$this->getShowMap();
		
		$idList=get_array_item_list($dataReturn['dataList'],'id');
		
		$backendNodeList=array();
		
		if($idList)
		{	
			$dGreyBackendNode=D('GreyBackendNode');
			$backendNodeList=$dGreyBackendNode->where(array('backend_id'=>array('in',$idList),'state'=>1))->select();
		}
		
		

		foreach($dataReturn['dataList'] as &$item)
		{
			$item['state_name']=$showMap['stateList'][$item['state']];
			
			$item['config_name']=$showMap['configRuleList'][$item['config_id']];
			
			$item['node_count']=0;
			
			foreach($backendNodeList as $backendNodeItem)
			{
				if($item['id']==$backendNodeItem['backend_id'])
				{
					$item['node_count']++;
				}
			}
		}
		
		
		return $dataReturn;
		
	}
}