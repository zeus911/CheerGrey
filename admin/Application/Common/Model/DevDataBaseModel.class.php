<?php
namespace Common\Model;

class DevDataBaseModel extends BaseModel{
      
	protected function initDbConfig()
	{
		$this->setDbConfig('DB_MYSQL_DEV');
	}
	  
}