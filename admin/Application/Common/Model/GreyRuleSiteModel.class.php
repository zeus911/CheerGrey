<?php
namespace Common\Model;

class GreyRuleSiteModel extends DataBaseModel
{
    protected $tableName = 'grey_rule_site';
	
	protected $_validate=array(
	   array('config_id','checkConfigId','请选择正确的配置服务!',0,'callback',3),
	   array('rule_site_name','1,100','请输入正确的站点名称!',0,'length',3),
	   array('http_host','1,250','请输入正确的站点规则!',0,'length',3),
	   array('http_host_type',array('string','regex'),'请选择正确的规则类别!',0,'in',3),
	   array('mode',array('grey','prod','mix'),'请选择正确的灰度模式!',0,'in',3),
	   array('prod_backend_id','checkBackendId','请选择正确的全量节点!',0,'callback',3),
	   array('grey_backend_id','checkBackendId','请选择正确的灰度节点!',0,'callback',3),
	);
	
	protected $_auto = array(
	   array('state',1,1,'string'),
	   array('create_time','get_fulltime',1,'function'),
	   array('update_time','get_fulltime',3,'function'),
	   array('update_ip','get_client_addr',3,'function'),
	);
	
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
	
	protected function checkBackendId($data)
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
	
	protected function initShowMap()
	{
		$this->setShowMap('httpHostTypeList',array('string'=>'字符匹配','regex'=>'正则匹配'));
		$this->setShowMap('modeList',array('prod'=>'全量线上','grey'=>'全量灰度','mix'=>'规则混合'));
		$this->setShowMap('stateList',array(0=>'禁用',1=>'启用'));	
		
		$dGreyRuleConfig=D('GreyRuleConfig');	
		$configRuleList=$dGreyRuleConfig->getAllDataKvList();
		$this->setShowMap('configRuleList',$configRuleList);
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
			$data[$item['id']]=$item['rule_site_name'];
		}
		
		return $data;

	}
	
	public function getAllSiteKvList($idList=array())
	{
		$data=array();
		
		if(!$idList)
		{
			return $data;
		}
		
		$where=array('id'=>array('in',$idList));
		
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
		
		$dGreyRuleNode=D('GreyRuleNode');
		$nCount=$dGreyRuleNode->getCountSite($id);
		if($nCount>0)
		{
			$this->setErrorMsg(250,'当前站点还存在规则集合,无法删除!');
			return false;
		}
		
		$this->where(array('id'=>intval($id)))->delete();
		
		return true;
	}

	
	public function getCountConfig($id)
	{
		$nCount=$this->where(array('config_id'=>$id))->count();
		
		return $nCount;
	}
	
	public function pubData($data)
	{
		$id=$data['id'];
		
		$siteWhere=array('id'=>$id);
		
		$siteData=$this->where($siteWhere)->find();	
		if(!$siteData)
		{
			$this->setErrorMsg(251,'站点信息已经被删除,操作失败!');
			return false;
		}
		
		$configId=$siteData['config_id'];
		

		$dGreyRuleNode=D('GreyRuleNode');
		
		$ruleWhere=array('site_id'=>$id);
		$ruleFieldList='id,match_object,match_opp,match_value,mode,backend_id,order_no';
		$ruleOrder='site_id asc,order_no desc,update_time desc';
		
		//当前站点对应的规则列表信息
		$ruleNodeList=$dGreyRuleNode->field($ruleFieldList)->where($ruleWhere)->order($ruleOrder)->select();
		
		//所有站点列表信息
		$siteDataListFieldList='id as site_id,http_host as host,http_host_type as rule_type,mode,prod_backend_id,grey_backend_id';
		$siteDataListWhere=array('config_id'=>$configId,'state'=>1);
		$siteDataList=$this->field($siteDataListFieldList)->where($siteDataListWhere)->select();
		
		//所有后端节点信息
		$dGreyBackendNode=D('GreyBackendNode');
		$backendNodeListFieldList='id,config_id,backend_id,server_ip,server_port';
		$backendNodeListWhere=array('config_id'=>$configId,'state'=>1);
		$backendNodeList=$dGreyBackendNode->field($backendNodeListFieldList)->where($backendNodeListWhere)->select();
		
		$backendNodeKvList=array();
		foreach($backendNodeList as $item)
		{
			$dataKey=sprintf('cheer_backend_%s',$item['backend_id']);
			if(!isset($backendNodeKvList))
			{
				$backendNodeKvList[$dataKey]=array();
			}
			
			$backendNodeKvList[$dataKey][]=$item;
		}
		
		
		try
		{
			$dGreyRuleConfig=D('GreyRuleConfig');
			
			$redisHandle=$dGreyRuleConfig->getRedisHandle($siteData['config_id']);
		
			if(!$redisHandle)
			{
				$this->setErrorMsg(253,'连接到当前站点配置库失败!');
				return false;
			}
            
			foreach($backendNodeKvList as $k=>$v)
			{
				$redisHandle->set($k,json_encode($v));
			}
            
			$redisHandle->set('cheer_site_mode',json_encode($siteDataList));
			$redisHandle->set(sprintf('cheer_site_%s',$siteData['id']),json_encode($ruleNodeList));

            $updateTimeData=array('update_cache_time'=>$siteData['update_time']);
             
			$this->where($siteWhere)->save($updateTimeData);

			return true;
		}
		catch(\Exception $ex)
		{
			$this->setErrorMsg(256,sprintf('连接到当前站点配置库异常(%s)!',$ex->getMessage()));
		}
		
		return false;
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
		
		if($option['config_id']>0)
		{
			$where['config_id']=$option['config_id'];
		}
		
		
		if($option['state']>-1)
		{
			$where['state']=$option['state'];
		}
		
		
		
		if($option['kw'])
		{
			$where['rule_site_name']=array('like','%'.$option['kw'].'%');
		}
		
		$dataReturn=$this->getPageList($where,$order,$page,$pageSize);
		
		
		$showMap=$this->getShowMap();
		

		foreach($dataReturn['dataList'] as &$item)
		{
			$item['http_host_type_name']=$showMap['httpHostTypeList'][$item['http_host_type']];
			$item['mode_name']=$showMap['modeList'][$item['mode']];
			$item['state_name']=$showMap['stateList'][$item['state']];
			
			$item['config_name']=$showMap['configRuleList'][$item['config_id']];
		}
		
		
		return $dataReturn;
		
	}
	
}