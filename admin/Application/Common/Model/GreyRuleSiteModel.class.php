<?php
namespace Common\Model;

class GreyRuleSiteModel extends DataBaseModel
{
    protected $tableName = 'grey_rule_site';
	
	protected $_validate=array( 
	   array('rule_site_name','1,100','请输入正确的站点名称!',0,'length',3),
	   array('http_host','1,250','请输入正确的站点规则!',0,'length',3),
	   array('http_host_type',array('string','regex'),'请选择正确的规则类别!',0,'in',3),
	   array('mode',array('grey','prod','mix'),'请选择正确的灰度模式!',0,'in',3),
	   array('prod_node','/^@backend_prod_[a-z0-9_]+$/','请输入正确格式的全量节点!',0,'regex',3),
	   array('grey_node','/^@backend_grey_[a-z0-9_]+$/','请输入正确格式的灰度节点!',0,'regex',3),
	);
	
	protected $_auto = array(
	   array('state',1,1,'string'),
	   array('create_time','get_fulltime',1,'function'),
	   array('update_time','get_fulltime',3,'function'),
	   array('update_ip','get_client_addr',3,'function'),
	);
	
	protected function initShowMap()
	{
		$this->setShowMap('httpHostTypeList',array('string'=>'字符匹配','regex'=>'正则匹配'));
		$this->setShowMap('modeList',array('prod'=>'全量线上','grey'=>'全量灰度','mix'=>'租户混合'));
		$this->setShowMap('stateList',array(0=>'禁用',1=>'启用'));	
		
		$dGreyRuleConfig=D('GreyRuleConfig');	
		$configRuleList=$dGreyRuleConfig->getAllDataKvList();
		$this->setShowMap('configRuleList',$configRuleList);
	}
	
	public function getAllDataKvList()
	{
		$data=array();
		
		$tempData=$this->select();
		
		foreach($tempData as $item)
		{
			$data[$item['id']]=$item['rule_site_name'];
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
		
		$siteData=$this->field('id,config_id,http_host,http_host_type,mode,grey_node,prod_node,update_time')->where(array('id'=>$id))->find();
		
		if(!$siteData)
		{
			$this->setErrorMsg(251,'站点信息已经被删除,操作失败!');
			return false;
		}
		
		$updateTime=$siteData['update_time'];
		unset($siteData['update_time']);
		
		
		$dGreyRuleNode=D('GreyRuleNode');
		$ruleNodeList=$dGreyRuleNode->field('tenant_code,mode')->where(array('site_id'=>$id))->order('id asc')->select();
		
		if(!$ruleNodeList)
		{
			$this->setErrorMsg(252,'当前站点下没有任何规则,操作失败!');
			return false;
		}
		
		
		//所有站点信息
		$siteDataList=$this->field('id as site_id,http_host as host,http_host_type as rule_type,mode,grey_node,prod_node')->where(array('state'=>1))->select();
		
		$dGreyRuleConfig=D('GreyRuleConfig');
		$redisHandle=$dGreyRuleConfig->getRedisHandle($siteData['config_id']);
		
		if(!$redisHandle)
		{
			$this->setErrorMsg(253,'连接到当前站点配置库失败!');
			return false;
		}
		
		$redisHandle->set('cheer_site_mode',json_encode($siteDataList));
		$redisHandle->set(sprintf('cheer_site_tenant_%s',$siteData['id']),json_encode($ruleNodeList));

        $this->where(array('id'=>$id))->save(array('update_cache_time'=>$updateTime));
		
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
			$where['site_rule_name']=array('like','%'.$option['kw'].'%');
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