<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0,initial-scale=1.0,user-scalable=no" />
<meta name="apple-mobile-web-app-capable" content="yes" />
<title>CheerDevOps管理中心</title>
<link href="/static/skin/default/style.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" charset="utf-8" src="/static/scripts/jquery/jquery-1.11.2.min.js"></script>
<script type="text/javascript" charset="utf-8" src="/static/scripts/jquery/jquery.nicescroll.js"></script>
<script type="text/javascript" charset="utf-8" src="/static/js/layindex.js"></script>
<script type="text/javascript" charset="utf-8" src="/static/js/common.js"></script>
<script type="text/javascript">

//页面加载完成时
$(function () {
    
	//检测IE
    if ('undefined' == typeof(document.body.style.maxHeight))
	{
        window.location.href = '/index/browser_update.html';
    }
});
	
</script>
</head>

<body class="indexbody">
<form method="post" action="javascript:;" id="form1">
  <!--全局菜单-->
  <a class="btn-paograms" onclick="togglePopMenu();"></a>
  <div id="pop-menu" class="pop-menu">
    <div class="pop-box">
      <h1 class="title"><i></i>导航菜单</h1>
      <i class="close" onclick="togglePopMenu();">关闭</i>
      <div class="list-box"></div>
    </div>
    <i class="arrow">箭头</i>
  </div>
  <!--/全局菜单-->

  <div class="main-top">
    <a class="icon-menu"></a>
    <div id="main-nav" class="main-nav"></div>
    <div class="nav-right">
      <div class="info">
        <i></i>
        <span>
          欢迎你<br/>
          <?php echo ($userInfo["user_name"]); ?>
          </empty>
          
        </span>
      </div>
      <div class="option">
        <i></i>
        <div class="drop-wrap">
          <div class="arrow"></div>
          <ul class="item">
             <li>
              <a href="/main/main.html" target="mainframe">管理首页</a>
            </li>
            <li>
              <a id="lbtnExit" href="/index/logout.html">注销登录</a>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>
  
  <div class="main-left">
    <h1 class="logo"></h1>
    <div id="sidebar-nav" class="sidebar-nav"></div>
  </div>
  
  <div class="main-container">
    <iframe id="mainframe" name="mainframe" frameborder="0" src="/main/main.html"></iframe>
  </div>

</form>
</body>
<script language="javascript">


  $(function(){
      
	 
	  
  });

</script>

</html>