<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0,initial-scale=1.0,user-scalable=no" />
<meta name="apple-mobile-web-app-capable" content="yes" />
<title>列表</title>
<link href="/static/skin/default/style.css" rel="stylesheet" type="text/css" />
<link href="/static/css/pagination.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="/static/scripts/jquery/jquery-1.11.2.min.js"></script>
<script src="/static/scripts/layer/layer.min.js"></script>
<script type="text/javascript" charset="utf-8" src="/static/js/laymain.js"></script>
<script type="text/javascript" charset="utf-8" src="/static/js/common.js"></script>
<script type="text/javascript" charset="utf-8" src="/static/js/base.js"></script>
</head>

<body class="mainbody">
<form method="post" action="" id="formList">
<!--导航栏-->

<div class="location">
  <a href="javascript:history.back(-1);" class="back"><i></i><span>返回上一页</span></a>
  <a href="javascript:;" class="order"><span>灰度系统管理</span></a>
  <i class="arrow"></i>
  <span>规则</span>
   <i class="arrow"></i>
  <span>配置服务器</span>
</div>

<!--/导航栏-->

<!--工具栏-->
<div id="floatHead" class="toolbar-wrap">
  <div class="toolbar">
    <div class="box-wrap">
      <a class="menu-btn"></a>
      <div class="l-list">

        <ul class="icon-list">
          <li><a class="add btn-pop" href="javascript:;" data-title="添加配置服务器" data-url="/grey/config_add_pop.html"><i></i><span>添加</span></a></li>
        </ul>

      
        <div class="menu-list">
          
        
        
        </div>
    
      </div>
      <div class="r-list">
        <input name="kw" type="text" id="txtKeywords" value="<?php echo ($option["kw"]); ?>" class="keyword" placeholder="名称"/>
        <a id="lbtnSearch" class="btn-search" href="javascript:doSearch();">查询</a>
      </div>
    </div>
  </div>
</div>
<!--/工具栏-->
<input type="hidden" name="page" id="page" value="1" />
<input type="hidden" name="pageSize" id="pageSize" value="<?php echo ($pageData["pageInfo"]["pageSize"]); ?>" />
</form>

<!--列表-->
<div class="table-container">
  <!--文字列表-->
  
  <table width="100%" border="0" cellspacing="0" cellpadding="0" class="ltable">
    <tr>

      <th width="100" align="center">系统ID</th>
      <th align="left" width="150">配置名称</th>
      <th align="left" width="200">服务器地址</th>
      <th align="left" width="100">服务器端口</th>
      <th align="left" width="100">服务器DB号</th>
      <th align="left" width="180">上次更新时间</th>
      <th align="left">操作</th>
    </tr>
    
    <?php if(is_array($pageData["dataList"])): foreach($pageData["dataList"] as $key=>$vo): ?><tr>
      <td align="center"><?php echo ($vo["id"]); ?></td>
      <td align="left"><?php echo ($vo["config_name"]); ?></td>
      <td align="left"><?php echo ($vo["server_host"]); ?></td>
      <td align="left"><?php echo ($vo["server_port"]); ?></td>
      <td align="left"><?php echo ($vo["server_db"]); ?></td>
      <td align="left"><?php echo ($vo["update_time"]); ?></td>
      <td align="left">
        <a href="javascript:;" class="btn-pop" data-title="修改[<?php echo ($vo["config_name"]); ?>]" data-url="/grey/config_edit_pop.html?id=<?php echo ($vo["id"]); ?>">修改</a>
        &nbsp;
        <a class="btn-action" href="javascript:;" data-url="/grey/config_del/wapi/ajax.html?id=<?php echo ($vo["id"]); ?>" data-callback="delCallBack">删除</a>
      </td>
    </tr><?php endforeach; endif; ?>
  
  </table>
  
</div>
<!--/列表-->

<!--内容底部-->
<div class="line20"></div>
<div class="pagelist">
  <div class="l-btns">
    <span>显示</span><input name="txtPageNum" type="text" value="<?php echo ($pageData["pageInfo"]["pageSize"]); ?>" id="txtPageNum" class="pagenum" onkeydown="return checkNumber(event);" /><span>条/页</span>
  </div>
  <div id="PageContent" class="default">
  <span>共<?php echo ($pageData["pageInfo"]["allCount"]); ?>条记录,<?php echo ($pageData["pageInfo"]["pageCount"]); ?>页</span>
  <a href="javascript:jumpToPage(<?php echo ($pageData["pageInfo"]["prevPage"]); ?>);">&lt;上一页</a>
  <?php $__FOR_START_1787723239__=$pageData["pageInfo"]["fromPage"];$__FOR_END_1787723239__=$pageData["pageInfo"]["toPage"];for($i=$__FOR_START_1787723239__;$i <= $__FOR_END_1787723239__;$i+=1){ if(($pageData["pageInfo"]["page"]) == $i): ?><span class="current"><?php echo ($i); ?></span>
   <?php else: ?>
  <a href="javascript:jumpToPage(<?php echo ($i); ?>);"><?php echo ($i); ?></a><?php endif; } ?>
  <a href="javascript:jumpToPage(<?php echo ($pageData["pageInfo"]["nextPage"]); ?>);">下一页&gt;</a>
  </div>
</div>
<!--/内容底部-->
</body>

<script language="javascript">

function doSearch()
{
	$('#formList').submit();
}

function jumpToPage($pageNo)
{
   $('#page').val($pageNo);
   doSearch();
}

function delCallBack()
{
	window.top.location.href=window.top.location.href;
}
	
function pubCallBack()
{
	window.top.location.href=window.top.location.href;
}

$(function(){



   $('#txtPageNum').change(function(){
	   $('#pageSize').val($(this).val());
	   doSearch();
   });
  


});

</script>

</html>