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

--拒绝访问
local function rejectAccess(errorCode)

	local pageData='<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><title>CheerGrey</title></head><body><center>系统升级或者访问用户超出系统限制,服务暂时不可用,错误码:CHEER_CODE</center></body></html>'
	pageData=string.gsub(pageData,'CHEER_CODE',errorCode)

	ngx.status=200
	ngx.send_headers()
	ngx.say(pageData)
	ngx.exit(0)
end

local function checkBackend()

	local backendNode=ngx.ctx.globalCurrentPeer
	
	local balancerHandle = require('ngx.balancer')
	
	wafLog('checkBackend backendNode.server_ip=%s,backendNode.server_port=%d',backendNode.server_ip,backendNode.server_port)
	
	ngx.header["Cheer-App-Node"]=backendNode.server_ip..':'..backendNode.server_port
	
	local ok, err = balancerHandle.set_current_peer(backendNode.server_ip,backendNode.server_port)
	if not ok then
		wafWarn('checkBackend set_current_peer err=%s',err)
		rejectAccess(200500)
	end
	
end

wafLog('====================================================================================================')
checkBackend()