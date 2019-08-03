<?php
namespace Common\Model;

class GreyRuleNodeModel extends DataBaseModel
{
    protected $tableName = 'grey_rule_list';
	
	protected $_validate=array( 
	   array('site_id',array(1,999999999),'请选择正确的站点!',0,'between',3),
	   array('match_object','checkMatchObject','请选择正确的匹配对象!',0,'callback',3),
	   array('match_opp','checkMatchOpp','请选择正确的匹配操作符!',0,'callback',3),
	   array('match_value','checkMatchValue','请输入正确匹配内容!',0,'callback',3),
	   array('order_no',array(1,100),'请选择正确的优先级!',0,'between',3),
	   array('mode',array('grey','prod','private'),'请选择正确的灰度模式!',0,'in',3),
	   array('backend_id','checkPrivateBackendId','请选择正确的私有节点!',0,'callback',3),
	);
	
	protected $_auto = array(
	   array('create_time','get_fulltime',1,'function'),
	   array('update_time','get_fulltime',3,'function'),
	   array('update_ip','get_client_addr',3,'function'),
	);
	
	protected function initShowMap()
	{
		
		$matchObjectList=array(
			's_http_ip'=>'客户端IP',
			's_tenant_code'=>'租户代码',
			's_http_header_useragent'=>'UA标识',
			's_http_header_url'=>'请求URL',
			's_http_header_referer'=>'来源URL'
		);
		
		$this->setShowMap('matchObjectList',$matchObjectList);
		
		
		$matchOppList=array(
			'eq'=>'等于',
			'lt'=>'小于',
			'gt'=>'大于',
			'lte'=>'小于等于',
			'gte'=>'大于等于',
			'neq'=>'不等于',
			'regex'=>'正则匹配',
			'notregex'=>'正则不匹配'
		);
		
		$this->setShowMap('matchOppList',$matchOppList);
		
		$this->setShowMap('modeList',array('prod'=>'生产','grey'=>'灰度','private'=>'私有'));
		$this->setShowMap('stateList',array(0=>'禁用',1=>'启用'));
		
		$dGreyRuleConfig=D('GreyRuleConfig');	
		$configRuleList=$dGreyRuleConfig->getAllDataKvList();
		$this->setShowMap('configRuleList',$configRuleList);
	}
	
	protected function checkMatchObject($data)
	{
		$showMap=$this->getShowMap();
		$checkList=$showMap['matchObjectList'];
		
		if(!array_key_exists($data,$checkList))
		{
			return false;
		}
		
		return true;
	}
	
	protected function checkMatchOpp($data)
	{
		$showMap=$this->getShowMap();
		$checkList=$showMap['matchOppList'];
		
		if(!array_key_exists($data,$checkList))
		{
			return false;
		}
		
		return true;
	}
	
	protected function checkMatchValue($data)
	{
		return true;
	}
	
	protected function checkPrivateBackendId($data)
	{
		$checkRule='/^[0-9]+$/i';
		if(!preg_match($checkRule,$data))
		{
			return false;
		}
		
		if($data>0)
		{
			$dGreyBackend=D('GreyBackend');	
			$dataInfo=$dGreyBackend->getData($data);
			if(!$dataInfo)
			{
				return false;
			}
		}
		
		
		return true;
	}
	
	public function getCountSite($site_id)
	{
		$nCount=$this->where(array('site_id'=>intval($site_id)))->count();
		return $nCount;
	}
	
	public function addData($data)
	{
		$dGreyRuleSite=D('GreyRuleSite');	
		$siteData=$dGreyRuleSite->getData($data['site_id']);
		if($siteData)
		{
			$data['config_id']=$siteData['config_id'];
		}
		
		if(!$this->create($data))
		{
			$this->setErrorCode(250);
			return false;
		}
		
		if($data['mode']=='private')
		{
			if($data['backend_id']<1)
			{
				$this->setErrorMsg(250,'请选择正确的私有节点!');
				return false;
			}
		}
		
		$id=$this->add();
		
		//更新站点时间
		$dGreyRuleSite->editData(array('id'=>$data['site_id']));
		
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
		
		//获取站点配置ID
		$dGreyRuleSite=D('GreyRuleSite');	
		$siteData=$dGreyRuleSite->getData($data['site_id']);
		if($siteData)
		{
			$data['config_id']=$siteData['config_id'];
		}
		
		if(!$this->create($data))
		{
			$this->setErrorCode(250);
			return false;
		}
		
		if($data['mode']=='private')
		{
			if($data['backend_id']<1)
			{
				$this->setErrorMsg(250,'请选择正确的私有节点!');
				return false;
			}
		}
		
		$this->save();
		
		//更新站点时间
		$dGreyRuleSite->editData(array('id'=>$data['site_id']));
		
		
		return true;
		
	}
	
	public function delData($data)
	{
		$id=$data['id'];
		
		$data=$this->where(array('id'=>intval($id)))->find();
		$this->where(array('id'=>intval($id)))->delete();
		
		//更新站点时间
		$dGreyRuleSite=D('GreyRuleSite');
		$dGreyRuleSite->editData(array('id'=>$data['site_id']));
		
		return true;
	}

	
	public function getPageShowList($option=array())
	{
		
		$order='site_id asc,order_no desc,update_time desc';
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
		
		if($option['site_id']>0)
		{
			$where['site_id']=$option['site_id'];
		}
		
		if($option['mode'])
		{
			$where['mode']=$option['mode'];
		}
		
		if($option['kw'])
		{
			$where['tenant_code']=$option['kw'];
		}
		
		$dataReturn=$this->getPageList($where,$order,$page,$pageSize);
		
		
		$showMap=$this->getShowMap();
		$siteKvDataList=array();
		$backendKvDataList=array();

		$siteIdList=get_array_item_list($dataReturn['dataList'],'site_id');
		if($siteIdList)
		{
			$dGreyRuleSite=D('GreyRuleSite');
			$siteKvDataList=$dGreyRuleSite->getAllSiteKvList($siteIdList);
		}
		
		$configIdList=get_array_item_list($dataReturn['dataList'],'config_id');
		if($configIdList)
		{
			$dGreyBackend=D('GreyBackend');
			$backendKvDataList=$dGreyBackend->getAllDataKvListByConfigIds($configIdList);
		}
		
		foreach($dataReturn['dataList'] as &$item)
		{
			$item['match_object_name']=$showMap['matchObjectList'][$item['match_object']];
			$item['match_opp_name']=$showMap['matchOppList'][$item['match_opp']];
			
			$item['mode_name']=$showMap['modeList'][$item['mode']];
			$item['state_name']=$showMap['stateList'][$item['state']];
			$item['site_name']=$siteKvDataList[$item['site_id']]['rule_site_name'];
			$item['config_name']=$showMap['configRuleList'][$item['config_id']];
			
			if($item['mode']=='prod')
			{
				$item['backend_id']=$siteKvDataList[$item['site_id']]['prod_backend_id'];
			}
			
			if($item['mode']=='grey')
			{
				$item['backend_id']=$siteKvDataList[$item['site_id']]['prod_backend_id'];
			}
			
			$item['backend_name']=sprintf('%s(%s)',$backendKvDataList[$item['backend_id']]['title'],$backendKvDataList[$item['backend_id']]['backend_name']);
		}
		
		
		return $dataReturn;
		
	}
	
}