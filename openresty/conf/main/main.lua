local confRedisHost='127.0.0.1'
local confRedisPort='6379'
local confRedisDb=6
local confReidsPassword=nil

--写日志
local function wafLog(fmt,...)
    local arg = { ... }
    local data=string.format(fmt,unpack(arg))
    ngx.log(ngx.INFO,data)
end

--警告日志
local function wafWarn(fmt,...)
    local arg = { ... }
    local data=string.format(fmt,unpack(arg))
    ngx.log(ngx.WARN,data)
end

--获取一个redis客户端
local function getRedisClient()
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

    local authRet,authErr=redisHandle:auth(confReidsPassword)

    if not authRet then
        wafWarn('getRedisClient redis auth error: %s', authErr)
        redisHandle=nil
    end

    return redisHandle
end

--关闭redis连接池
local function closeRedis(redisHandle)
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
local function getDataFromRedis(dataKey)
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
local function getDataFromCacheDb(dataKey)
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
        dbHandle:set(dataKey,dataVal,30);
    end

    --反解析为table
    local jsonLib = require('cjson')
    data=jsonLib.decode(dataVal)

    if not data then
        data={}
    end

    return data
end

--当前逻辑判断
local function currentLogicJudge(current_logic_key,current_logic_type,current_logic_value,envData)
	local bRet=false
	
	local envValue=envData[current_logic_key]
	
	--环境变量中不存在此判断项目
	if not envValue then
		return bRet
	end
	
	--数据类型
	local keyPrefix=string.sub(current_logic_key,1,2)
	
	if current_logic_type=='eq' then
		if envValue==current_logic_value then
			return true
		end
	end
	
	if current_logic_type=='lt' then
		if envValue<current_logic_value then
			return true
		end
	end
	
	if current_logic_type=='gt' then
		if envValue>current_logic_value then
			return true
		end
	end
	
	if current_logic_type=='lte' then
		if envValue<=current_logic_value then
			return true
		end
	end
	
	if current_logic_type=='gte' then
		if envValue>=current_logic_value then
			return true
		end
	end
	
	if current_logic_type=='neq' then
		if envValue~=current_logic_value then
			return true
		end
	end
	
	if current_logic_type=='regex' and keyPrefix=='s_' then
		if ngx.re.match(envValue,current_logic_value,'jio') then
			wafLog('currentLogicJudge regex=true')
			return true
		end
	end
	
	if current_logic_type=='notregex' and keyPrefix=='s_' then
		if not ngx.re.match(envValue,current_logic_value,'jio') then
			wafLog('currentLogicJudge notregex=true')
			return true
		end
	end
		
	return bRet
end


--获取站点模式列表
local function getSiteModeTable()
    local data=getDataFromCacheDb('cheer_site_mode')
    if not data then
        data={}
    end
    return data
end

--获取站点后端服务器列表
local function getSiteBackendDataTable(backend_id)
    local dataKey=string.format('cheer_backend_%d',backend_id)
    local data=getDataFromCacheDb(dataKey)
    if not data then
        data={}
    end
    return data
end

--获取站点规则代码列表
local function getSiteRuleDataTable(site_id)
    local dataKey=string.format('cheer_site_%d',site_id)
    local data=getDataFromCacheDb(dataKey)
    if not data then
        data={}
    end
    return data
end


--获取对应的域名全局模式
local function getHostGlobalMode(host)

    local data={}

    data.site_id=0
    data.mode='none'
    data.prod_backend_id=0
    data.grey_backend_id=0

    local siteModeData=getSiteModeTable()

    for k,v in ipairs(siteModeData) do
        local ruleSiteId=tonumber(v.site_id)   --规则站点ID
        local ruleHost=v.host               --规则站点域名
        local ruleType=v.rule_type          --规则类型
        local ruleMode=v.mode               --灰度模式
        local ruleProdBackendId=tonumber(v.prod_backend_id)      --全量节点
        local ruleGreyBackendId=tonumber(v.grey_backend_id)      --灰度节点

        if ruleType=='string' then
            if ruleHost==host then

                data.site_id=ruleSiteId
                data.mode=ruleMode
                data.prod_backend_id=ruleProdBackendId
                data.grey_backend_id=ruleGreyBackendId

                return data
            end
        end

        if ruleType=='regex' then
            if ngx.re.match(host,ruleHost,'jio') then

                data.site_id=ruleSiteId
                data.mode=ruleMode
                data.prod_backend_id=ruleProdBackendId
                data.grey_backend_id=ruleGreyBackendId

                return data
            end
        end

    end

    return data

end

--获取对应站点的模式,默认prod
local function getSiteMode(globalEnvData,site_id)
    local data={}

    data.mode='prod'
    data.backend_id=0
	
	local envData=globalEnvData

    --获取站点对应的规则列表
    local siteRuleData=getSiteRuleDataTable(site_id)

    for k,v in ipairs(siteRuleData) do

		local ruleId=tonumber(v.id)
        local ruleMatchObject=v.match_object
		local ruleMatchOpp=v.match_opp
		local ruleMatchValue=v.match_value
        local ruleMode=v.mode
        local ruleBackendId=tonumber(v.backend_id)

        if not ruleMode then
            ruleMode='prod'
        end

		--当前规则逻辑判断
        if currentLogicJudge(ruleMatchObject,ruleMatchOpp,ruleMatchValue,envData) then
            data.mode=ruleMode
            data.backend_id=ruleBackendId
            return data
        end
		
    end

    return data
end

--获取客户端IP
local function getClientIp()
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

--分解字符串
local function stringSplit(szFullString,szSeparator)
	
	local nSplitArray = {}
	
	local nFindStartIndex = 1
	local nSplitIndex = 1
	
	
	while true do
	
	local nFindLastIndex = string.find(szFullString, szSeparator, nFindStartIndex)
	
	if not nFindLastIndex then
	  nSplitArray[nSplitIndex] = string.sub(szFullString, nFindStartIndex, string.len(szFullString))
	  break
	end
	
	nSplitArray[nSplitIndex] = string.sub(szFullString, nFindStartIndex, nFindLastIndex - 1)
	nFindStartIndex = nFindLastIndex + string.len(szSeparator)
	nSplitIndex = nSplitIndex + 1
	
	end
	
	return nSplitArray
end

--获取租户代码
local function getTenantCode()
    local data='none'

    --从请求参数拿
    data=ngx.var.arg_o

    --没有从cookie中o拿
    if not data then
        data=ngx.var.cookie_o
    end

    --没有从cookie中tenant_code拿
    if not data then
        data= ngx.var.cookie_tenant_code;
    end

    --没有从请求中tenant_code拿
    if not data then
        data= ngx.var.arg_tenant_code;
    end
	
	--没有从url地址中拿
	if not data then
		local reqUrl=ngx.var.request_uri
		local urlPartList=stringSplit(reqUrl,'/')
		local urlPartLen=table.getn(urlPartList)
		
		if urlPartLen>2 then
			data=urlPartList[1]
		end
		
	end

    if not data then
        data='none'
    end

    return data
end

--获取环境数据
local function getEnvData()
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
	
	--获取useragent
	data.s_http_header_useragent=ngx.var.http_user_agent
	if not data.s_http_header_useragent then
		data.s_http_header_useragent=''
	end
	
	--获取请求url
	data.s_http_header_url=ngx.var.request_uri
	if not data.s_http_header_url then
		data.s_http_header_url=''
	end
	
	--获取来源url
	data.s_http_header_referer=ngx.var.http_referer
	if not data.s_http_header_referer then
		data.s_http_header_referer=''
	end

    --获取租户代码
    data.s_tenant_code=getTenantCode()
    if not data.s_tenant_code then
        data.s_tenant_code='none'
    end
	
	--设置租户代码
	ngx.header["Set-Cookie"] = 'cheer_tenant_code='..data.s_tenant_code..'; Path=/; Expires=' .. ngx.cookie_time(ngx.time() + 2592000)

    return data
end

--设置灰度环境数据
local function setGreyData()
    ngx.req.set_header('grey', '1');
    ngx.header["grey"] = "1";
end

--获取后端节点对应的服务器信息
local function getBackendNode(globalEnvData,backendId)
	
	local envData=globalEnvData
	
	local data={}
	
	data.server_ip='0.0.0.0'
	data.server_port=9000
	
	local backendNodeData=getSiteBackendDataTable(backendId)
	
	if not backendNodeData then
		return data
	end
	
	local backendNodeList={}
	
	for k,v in ipairs(backendNodeData) do
		local dataItem={}
		dataItem.id=tonumber(v.id)
		dataItem.server_ip=v.server_ip
		dataItem.server_port=tonumber(v.server_port)
		table.insert(backendNodeList,dataItem)
	end
	
	if not backendNodeList then
		return data
	end
	
	local nodeCount=table.getn(backendNodeList)
	
	local clientKey=envData.s_http_ip
	local clientHash=ngx.crc32_long(clientKey)
	local clientIndex=(clientHash%nodeCount)+1
	
	local tempData=backendNodeList[clientIndex]
	
	if not tempData then
		return data
	end
	
	data=tempData
	
	return data
end

--获取当前站点后端节点
local function getCurrentSiteBackend(globalEnvData)

    local data=0

    local envData=globalEnvData
    local globalModeData=getHostGlobalMode(envData.s_http_header_host)

    wafLog('getCurrentSiteRunEnv globalModeData.site_id=%d,globalModeData.mode=%s', globalModeData.site_id,globalModeData.mode)

    --产品全量模式
    if globalModeData.mode=='prod' then
        data=globalModeData.prod_backend_id
        return data
    end

    --灰度全量模式
    if globalModeData.mode=='grey' then
        data=globalModeData.grey_backend_id
        setGreyData()
        return data
    end

	--规则控制模式
    if globalModeData.mode=='mix' then
		
		--根据站点ID获取运行模式
		local tempModeData=getSiteMode(envData,globalModeData.site_id)
		
		wafLog('getCurrentSiteRunEnv getSiteMode tempModeData.mode=%s,tempModeData.backend_id=%s', tempModeData.mode,tempModeData.backend_id)
		
		--产品模式
		if tempModeData.mode=='prod' then
			data=globalModeData.prod_backend_id
			return data
		end
		
		--灰度模式
		if tempModeData.mode=='grey' then
			data=globalModeData.grey_backend_id
			setGreyData()
			return data
		end
		
		 --私有模式
		if tempModeData.mode=='private' then
			data=tempModeData.backend_id
			return data
		end
		
	end

    return data
end

--拒绝访问
local function rejectAccess(errorCode)

	local pageData='<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><title>CheerGrey</title></head><body><center>系统升级或者访问用户超出系统限制,服务暂时不可用,错误码:CHEER_CODE</center></body></html>'
	pageData=string.gsub(pageData,'CHEER_CODE',errorCode)

	ngx.status=200
	ngx.send_headers()
	ngx.say(pageData)
	ngx.exit(0)
end


local function checkAccess()

    ngx.header["GateWay-Grey"]='CheerGrey/3.0'

	--获取环境数据
    local envData=getEnvData()
	
	--获取后端节点
    local backendId=getCurrentSiteBackend(envData)

    --没有找到后端节点
    if not backendId then
        rejectAccess(200404)
    end
	
	--没有找到后端节点
	if backendId<1 then
        rejectAccess(200404)
    end
	
	local backendNode=getBackendNode(envData,backendId)
	
	if not backendNode then
		rejectAccess(200405)
	end
	
	if backendNode.server_ip=='0.0.0.0' then
		rejectAccess(200406)
	end
	
	wafLog('checkBackend backendNode.server_ip=%s,backendNode.server_port=%d',backendNode.server_ip,backendNode.server_port)
	
	ngx.ctx.globalCurrentPeer=backendNode
	

end

wafLog('====================================================================================================')
checkAccess()