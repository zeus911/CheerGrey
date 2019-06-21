<?php
namespace Common\Model;

class GreyRuleNodeModel extends DataBaseModel
{
    protected $tableName = 'grey_rule_list';
	
	protected $_validate=array( 
	   array('site_id',array(1,999999999),'请选择正确的站点!',0,'between',3),
	   array('tenant_code','checkTenantCode','请输入正确的租户代码!',0,'callback',3),
	   array('mode',array('grey','prod'),'请选择正确的灰度模式!',0,'in',3),
	);
	
	protected $_auto = array(
	   array('create_time','get_fulltime',1,'function'),
	   array('update_time','get_fulltime',3,'function'),
	   array('update_ip','get_client_addr',3,'function'),
	);
	
	protected function initShowMap()
	{
		
		$this->setShowMap('modeList',array('prod'=>'生产','grey'=>'灰度'));
		$this->setShowMap('stateList',array(0=>'禁用',1=>'启用'));
		
		$dGreyRuleSite=D('GreyRuleSite');	
		$siteList=$dGreyRuleSite->getAllDataKvList();
		$this->setShowMap('siteRuleList',$siteList);
	}
	
	protected function checkTenantCode($data)
	{
		$rule='/^[0-9a-z]+$/';
		if(!preg_match($rule,$data))
		{
			return false;
		}
		
		return true;
	}
	
	
	public function getCountSite($rule_site_id)
	{
		$nCount=$this->where(array('rule_site_id'=>intval($rule_site_id)))->count();
		return $nCount;
	}
	
	public function addData($data)
	{
		
		if(!$this->create($data))
		{
			$this->setErrorCode(250);
			return false;
		}

		$id=$this->add();
		
		$dGreyRuleSite=D('GreyRuleSite');
		$dGreyRuleSite->editData(array('id'=>$data['rule_site_id']));
		
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
		
		$dGreyRuleSite=D('GreyRuleSite');
		$dGreyRuleSite->editData(array('id'=>$data['rule_site_id']));
		
		return true;
		
	}
	
	public function delData($data)
	{
		$id=$data['id'];
		
		$data=$this->where(array('id'=>intval($id)))->find();
		$this->where(array('id'=>intval($id)))->delete();
		
		$dGreyRuleSite=D('GreyRuleSite');
		$dGreyRuleSite->editData(array('id'=>$data['rule_site_id']));
		
		return true;
	}

	
	public function getPageShowList($option=array())
	{
		
		$order='site_id asc,update_time desc';
		$page=intval($option['page']);
		$pageSize=intval($option['pageSize']);
		if($pageSize<1)
		{
			$pageSize=50;
		}
		
		$where=array('id'=>array('gt',0));
		
		if($option['rule_site_id']>0)
		{
			$where['rule_site_id']=$option['rule_site_id'];
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
		

		foreach($dataReturn['dataList'] as &$item)
		{
			$item['mode_name']=$showMap['modeList'][$item['mode']];
			$item['state_name']=$showMap['stateList'][$item['state']];
			$item['site_name']=$showMap['siteRuleList'][$item['site_id']];
		}
		
		
		return $dataReturn;
		
	}
	
}