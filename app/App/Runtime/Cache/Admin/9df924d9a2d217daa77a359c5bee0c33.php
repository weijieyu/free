<?php if (!defined('THINK_PATH')) exit();?><html>
<head>
<title>管理中心</title>
<meta http-equiv=Content-Type content=text/html;charset=utf-8>
</head>
<frameset rows="64,*"  frameborder="NO" border="0" framespacing="0">
	<frame src="/index.php/Admin/Index/top" noresize="noresize" frameborder="NO" name="topFrame" scrolling="no" marginwidth="0" marginheight="0" target="main" />
  <frameset cols="200,*" id="frame">
	<frame src="/index.php/Admin/Index/left" name="leftFrame" noresize="noresize" marginwidth="0" marginheight="0" frameborder="0" scrolling="no" target="main" />
	<frame src="/index.php/Admin/Index/right" name="main" marginwidth="0" marginheight="0" frameborder="0" scrolling="auto" target="_self" />
  </frameset>
</frameset>
<noframes>
  <body></body>
</noframes>
 
</html>