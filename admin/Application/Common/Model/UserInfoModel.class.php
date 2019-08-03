<?php
namespace Common\Model;

class UserInfoModel extends DataBaseModel
{
    protected $tableName = 'user_info';

	protected $_auto = array(
	   array('create_time','get_fulltime',1,'function'),
	   array('update_time','get_fulltime',3,'function'),
	   array('update_ip','get_client_addr',3,'function'),
	);
	
	
	protected $_validate=array( 
	   array('user_name','1,100','请输入正确的用户名!',0,'length',3),
	   array('realname','1,100','请输入正确的姓名!',0,'length',3),
	   array('department_id','checkDepartment','请选择正确的产品组!',0,'callback',3),
	   array('role_group_code','checkRoleGroup','请选择正确的权限类别!',0,'callback',3),
	   array('role_group_level','checkRoleLevel','请选择正确的权限级别!',0,'callback',3),
	   array('state','checkState','请选择正确的账号状态!',0,'callback',3),
	);
	
	
	
	protected function initShowMap()
	{
		$roleGroupList=array('manager'=>'管理','tech'=>'开发','product'=>'产品','maintenance'=>'运维','test'=>'测试','operate'=>'运营','visitor'=>'访客');
		$this->setShowMap('roleGroupList',$roleGroupList);
		
		$roleLevelList=array('100'=>'钻石','50'=>'太阳','10'=>'月亮','1'=>'星星');
		$this->setShowMap('roleLevelList',$roleLevelList);
		
		
		$dDepartment=D('UserDepartment');
		$departmentList=$dDepartment->getDepartmentKvList();
		$this->setShowMap('departmentList',$departmentList);
		
		$this->setShowMap('stateList',array(0=>'未激活',1=>'启用',2=>'禁用'));
	}
	
	
	protected function checkDepartment($data)
	{
		
		if($data==0)
		{
			return true;
		}
		
		$showMap=$this->getShowMap();
		

		if(!array_key_exists($data,$showMap['departmentList']))
		{
			return false;
		}
		

		return true;
	}
	
	protected function checkRoleGroup($data)
	{
		
		
		$showMap=$this->getShowMap();
		
		if(!array_key_exists($data,$showMap['roleGroupList']))
		{
			return false;
		}
		
		return true;
	}
	
	protected function checkRoleLevel($data)
	{
		
		$showMap=$this->getShowMap();
		
		if(!array_key_exists($data,$showMap['roleLevelList']))
		{
			return false;
		}
		
		return true;
	}
	
	protected function checkState($data)
	{
		$showMap=$this->getShowMap();
		
		if(!array_key_exists($data,$showMap['stateList']))
		{
			return false;
		}
		
		return true;
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
	
	public function checkLogin($username,$password)
	{
		
		if(!$username||!$password)
		{
			$this->setErrorMsg(200,'你输入的登录名或者登录密码不能为空!');
			return false;
		}
		
		$data=$this->where(array('user_name'=>$username))->find();
		
		if(!$data)
		{
			$this->setErrorMsg(201,'你输入的登录名不存在!');
			return false;
		}
		
		$checkPass=md5(sprintf('%s%s',$password,$data['user_pass_salt']));
		
		if($data['user_password']!=$checkPass)
		{
			$this->setErrorMsg(202,'你输入的登录密码错误!');
			return false;
		}
		

		$sessionData=array(
		   'username'=>$data['user_name'],
		   'email'=>'',
		   'role_group_code'=>'maintenance',
		   'role_group_level'=>1,
		   'realname'=>$data['user_name'],
		);
		
		session('admin_login_data',$sessionData);
		
		return true;
	}
	
	public function getUserInfo($uid)
	{
		$data=$this->where(array('id'=>intval($uid)))->find();
		
		if(!$data)
		{
			return $data;
		}
		
		unset($data['user_password']);
		unset($data['user_pass_salt']);
		
		return $data;
	}

	
	public function getUserInfoByOption($option)
	{
		$where=$option;
		$where['state']=1;
		
		$data=$this->where($where)->find();
		
		if(!$data)
		{
			return $data;
		}
		
		unset($data['user_password']);
		unset($data['user_pass_salt']);
		
		return $data;
	}
	
	//同步用户信息
	public function syncUserInfo()
	{
		$needAddList=array();
		$needUpdateList=array();
		
		$userInfoDataList=$this->select();
		
		$dDevUserInfo=D('DevUserInfo');
		$devUserInfoDataList=$dDevUserInfo->getUserInfoList();
		
		$dUserDepartment=D('UserDepartment');
		$departmentDataList=$dUserDepartment->getDepartmentList();
		
		$showMap=$this->getShowMap();
		
		//新增用户
		foreach($devUserInfoDataList as $srcItem)
		{
			$bExists=false;
			foreach($userInfoDataList as $desItem)
			{
				if($srcItem['username']==$desItem['user_name'])
				{
					$bExists=true;
					break;
				}
			}
			
			//如果不存在
			if(!$bExists)
			{
				$saltPass=md5(uniqid());
				$password=md5(uniqid());
				$checkPass=md5(sprintf('%s%s',$password,$saltPass));
				
				$dataAdd=array(
					'user_name'=>$srcItem['username'],
					'user_password'=>$checkPass,
					'user_pass_salt'=>$saltPass,
					'realname'=>$srcItem['realname'],
					'email'=>$srcItem['email'],
					'mobile'=>$srcItem['mobile'],
					'product_id'=>0,
					'department_id'=>0,
					'role_group_code'=>$srcItem['role_group_code'],
					'role_group_level'=>$srcItem['role_group_level'],
					'state'=>1
				);
				
				if(!array_key_exists($dataAdd['role_group_code'],$showMap['roleGroupList']))
				{
					$dataAdd['role_group_code']='visitor';
				}
				
				if(!array_key_exists($dataAdd['role_group_level'],$showMap['roleLevelList']))
				{
					$dataAdd['role_group_level']=1;
				}
				
				$needAddList[]=$dataAdd;
			}
		}
		
		//更新用户
		foreach($userInfoDataList as $desItem)
		{
			$bExists=false;
			foreach($devUserInfoDataList as $srcItem)
			{
				if($srcItem['username']==$desItem['user_name'])
				{
					$bExists=true;
				}
				
			}
			
			if(!$bExists)
			{
				$desItem['state']=2;
				$needUpdateList[]=$desItem;
			}
		}
		
		
		foreach($needAddList as $item)
		{
			if($this->create($item))
			{
				$this->add();
			}
		}
		
		foreach($needUpdateList as $item)
		{
			if($this->create($item))
			{
				$this->save();
			}
			
		}
		
		
	}
	
	public function getKvList($option=array())
	{
		$dataList=array();
		
		$order='id desc';

		$where=array('state'=>1);
		
		if($option['role_group_type']=='qa')
		{
			$where['role_group_code']='test';
		}
		
		if($option['role_group_type']=='rd')
		{
			$where['role_group_code']=array(array('eq','tech'),array('eq','manager'),array('eq','maintenance'),array('eq','test'), 'or');
		}
		

		$tempDataList=$this->where($where)->order($order)->select();
		
		foreach($tempDataList as $item)
		{
			$dataList[$item['id']]=sprintf('%s(%s)',$item['realname'],$item['user_name']);
		}
		
		return $dataList;
		
	}
	
	public function getPageShowList($option=array())
	{
		
		$order='state asc,id desc';
		$page=intval($option['page']);
		$pageSize=intval($option['pageSize']);
		if($pageSize<1)
		{
			$pageSize=50;
		}
		
		$where=array('id'=>array('gt',0));
		
		if($option['department_id']>0)
		{
			$where['department_id']=$option['department_id'];
		}
		
		
		if($option['role_group_code'])
		{
			$where['role_group_code']=$option['role_group_code'];
		}
		
		if($option['role_group_level']>0)
		{
			$where['role_group_level']=$option['role_group_level'];
		}
		
		if($option['state']>-1)
		{
			$where['state']=$option['state'];
		}
		
		
		
		if($option['kw'])
		{
			$where['user_name|realname']=array('like','%'.$option['kw'].'%');
		}
		
		$dataReturn=$this->getPageList($where,$order,$page,$pageSize);
		
		
		$showMap=$this->getShowMap();
		

		foreach($dataReturn['dataList'] as &$item)
		{
			$item['department_name']=$showMap['departmentList'][$item['department_id']];
			$item['role_group_name']=$showMap['roleGroupList'][$item['role_group_code']];
			$item['role_level_name']=$showMap['roleLevelList'][$item['role_group_level']];
			$item['state_name']=$showMap['stateList'][$item['state']];

		}
		
		
		return $dataReturn;
		
	}
	
	
}