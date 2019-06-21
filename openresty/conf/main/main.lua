local confRedisHost='127.0.0.1'
local confRedisPort='6379'
local confRedisDb=5
local confReidsPassword=nil

--写日志
function wafLog(fmt,...)
	local arg = { ... }
	local data=string.format(fmt,unpack(arg))
	ngx.log(ngx.INFO,data)
end

--警告日志
function wafWarn(fmt,...)
	local arg = { ... }
	local data=string.format(fmt,unpack(arg))
	ngx.log(ngx.WARN,data)
end

--判断table是否为空
function isTableEmpty(t)
    return t == nil or next(t) == nil
end

--获取一个redis客户端
function getRedisClient()
	local redisLib = require('resty.redis')
	local redisHandle=redisLib.new()
	local ok,err=redisHandle.connect(redisHandle, confRedisHost, confRedisPort)
	
	if not ok then
		wafWarn('getRedisClient redis error: %s', err)
		redisHandle=nil
	end
	
	if not confReidsPassword then
		return redisHandle
	end
	
	local authRet,authErr=redisHandle::auth(confReidsPassword)
	
	if not authRet then
		wafWarn('getRedisClient redis auth error: %s', authErr)
		redisHandle=nil
	end
	
	return redisHandle
end

--关闭redis连接池
function closeRedis(redisHandle)
	if not redisHandle then
		return
	end
	
	local pool_max_idle_time = 10000
	local pool_size = 100
	local ok, err = redisHandle:set_keepalive(pool_max_idle_time, pool_size)
	
	if not ok then
		wafWarn('closeRedis set keepalive error: %s',err)
	end
	
end

--从Redis获取
function getDataFromRedis(dataKey)
	local data=nil
	
	local redisHandle=getRedisClient()
	if not redisHandle then
		return data
	end
	
	--选择指定的redis库
	redisHandle:select(confRedisDb)
	
	local res=redisHandle:get(dataKey)
	
	closeRedis(redisHandle)
	
	wafLog('getDataFromRedis dataKey=%s',dataKey)
	
	if not res then
		wafLog('getDataFromRedis redis get error: %s', dataKey)
	else
		data=res
	end
	
	return data
end


--从全局缓存中获取
function getDataFromCacheDb(dataKey)
	local data={}
	local dbHandle=ngx.shared.cheer_cache_db
	
	if not dbHandle then
		return data
	end
	
	--缓存中获取
	local dataVal=dbHandle:get(dataKey)
	
	--是否需要更新到缓存
	local bNeedUpdate=false
	
	--缓存中没有,从Redis获取
	if not dataVal then
		dataVal=getDataFromRedis(dataKey)
		bNeedUpdate=true
	end
	
	--都没有直接返回空table
	if not dataVal then
		return data
	end
	
	--不是字符串
	if type(dataVal)~='string' then
		return data
	end
	
	--更新到缓存
	if bNeedUpdate then
		dbHandle:set(dataKey,dataVal,180);
	end
	
	--反解析为table
	local jsonLib = require('cjson')	
	data=jsonLib.decode(dataVal)
	
	if not data then
		data={}
	end
	
	return data
end


--获取站点模式列表
function getSiteModeTable()
	local data=getDataFromCacheDb('cheer_site_mode')
	if not data then
		data={}
	end
	return data
end

--获取站点灰度租户代码列表
function getSiteGreyTenantTable(site_id)
	local dataKey=string.format('cheer_site_tenant_%d',site_id)
	local data=getDataFromCacheDb(dataKey)
	if not data then
		data={}
	end
	return data
end


--获取对应的域名全局模式
function getHostGlobalMode(host)

	local data={}
	
	data.site_id=0
	data.mode='none'
	data.prod_node='@backend_prod_none'
	data.grey_node='@backend_grey_none'
	
	local siteModeData=getSiteModeTable()
	
	for k,v in ipairs(siteModeData) do
		local ruleSiteId=tonumber(v.site_id)   --规则站点ID
		local ruleHost=v.host               --规则站点域名
		local ruleType=v.rule_type          --规则类型
		local ruleMode=v.mode               --灰度模式
		local ruleProdNode=v.prod_node      --全量节点
		local ruleGreyNode=v.grey_node      --灰度节点
		
		if ruleType=='string' then
			if ruleHost==host then
			
				data.site_id=ruleSiteId
				data.mode=ruleMode
				data.prod_node=ruleProdNode
				data.grey_node=ruleGreyNode

				return data
			end
		end
		
		if ruleType=='regex' then
			if ngx.re.match(host,ruleHost,'jio') then
				
				data.site_id=ruleSiteId
				data.mode=ruleMode
				data.prod_node=ruleProdNode
				data.grey_node=ruleGreyNode

				return data
			end
		end
		
	end
	
	return data

end

--获取对应站点对应租户的模式,默认prod
function getSiteTenantMode(site_id,tenant_code)
	local data='prod'
	
	local siteTenantGreyData=getSiteGreyTenantTable(site_id)
	
	for k,v in ipairs(siteTenantGreyData) do
		
		local ruleTenantCode=v.tenant_code
		local ruleMode=v.mode
		
		if tenant_code==ruleTenantCode then
			data=ruleMode
			
			if not data then
				data='prod'
			end
			
			return data
		end
	end
	
	return data
end

--获取客户端IP
function getClientIp()
	local header = ngx.req.get_headers()
	local data=header['X-Real-IP']
	
	if not data then
		data=header['X_FORWARDED_FOR']
	end
	
	if not data then
		data=ngx.var.remote_addr
	end
	
	if not data then
		data='0.0.0.0'
	end
	
	return data
end

--获取租户代码
function getTenantCode()
	local data='none'
	
	--从请求参数拿
	data=ngx.var.arg_o
	
	--没有从cookie中o拿
	if not data then
		data=ngx.var.cookie_o
	end
	
	--没有从cookie中src_tenant_code拿
	if not data then
		data= ngx.var.cookie_src_tenant_code
	end
	
	--没有从cookie中tenant_code拿
	if not data then
		data= ngx.var.cookie_tenant_code;
	end
	
	if not data then
		data='none'
	end
	
	return data
end

--获取环境数据
function getEnvData()
	local data={}
	
	--获取客户端IP
	data.s_http_ip=getClientIp()
	if not data.s_http_ip then
		data.s_http_ip='0.0.0.0'
	end
	
	--获取域名
	data.s_http_header_host=ngx.var.host
	if not data.s_http_header_host then
		data.s_http_header_host=''
	end
	
	--获取租户代码
	data.s_tenant_code=getTenantCode()
	if not data.s_tenant_code then
		data.s_tenant_code='none'
	end
		
	return data
end

--获取当前站点运行环境
function getCurrentSiteRunEnv()

	local data='none'
	
	local envData=getEnvData()
	local globalModeData=getHostGlobalMode(envData.s_http_header_host)
	
	wafLog('getCurrentSiteRunEnv globalModeData.site_id=%d,globalModeData.mode=%s', globalModeData.site_id,globalModeData.mode)
	
	--产品全量模式
	if globalModeData.mode=='prod' then
		data=globalModeData.prod_node
		return data
	end
	
	--灰度全量模式
	if globalModeData.mode=='grey' then
		data=globalModeData.grey_node
		return data
	end
	
	--根据站点ID和租户代码获取运行模式
	local tempMode=getSiteTenantMode(globalModeData.site_id,envData.s_tenant_code)
	
	wafLog('getCurrentSiteRunEnv getSiteTenantMode tempMode=%s', tempMode)
	
	--产品模式
	if tempMode=='prod' then
		data=globalModeData.prod_node
		return data
	end
	
	--灰度模式
	if tempMode=='grey' then
		data=globalModeData.grey_node
		return data
	end
	
	if not data then
		data='none'
	end
	
	return data

end



--读取显示页面
function readShowPage(pageFile)

	local data=''
	
	local info = debug.getinfo(1, "S")
	local path = info.source
	path = string.sub(path, 2, -1)
	path = string.match(path, "^.*/")
	path=path..pageFile
	
	local file = io.input(path)
	
	repeat
		local line = io.read()
		if nil == line then
			break
		end
		data=data..line
	until(false)
	
	io.close(file)
	
	return data

end

function rejectAccess(pageData)
	ngx.status=406
	ngx.send_headers()
	ngx.say(pageData)
	ngx.exit(0)
end

function checkAccess()

	ngx.header["GateWay-Grey"]='CheerGrey'
	
	local envData=getEnvData()
	
	local runEnv=getCurrentSiteRunEnv()
	
	--没有找到运行环境
	if runEnv=='none' then
		local pageData=readShowPage('user_error_page.html')
		pageData=string.gsub(pageData,'{$cheer_client_ip}',envData.s_http_ip)
		pageData=string.gsub(pageData,'{$cheer_code}',200404)	
		rejectAccess(pageData)
	end
	
	--转到运行环境
	ngx.exec(runEnv)
	
end

wafLog('====================================================================================================')
checkAccess()