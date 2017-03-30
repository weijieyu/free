<?php if (!defined('THINK_PATH')) exit();?><link href="/Public/houtai//images/skin.css" rel="stylesheet" type="text/css" />
<script language="javascript" type="text/javascript" src="/Public/houtai//My97DatePicker/WdatePicker.js"></script>
<script src="/Public/admin/AdminLTE/js/jquery-2.1.1.min.js"></script>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<style type="text/css">
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
	background-color: #EEF2FB;
}
td.ss,span{
	font-size:12px;
	height:30px;
	padding-left:30px;
	font-family:Microsoft YaHei;
}
a.blue{
	color:blue
}
.btn-primary {
    background-color: #428bca;
    border-color: #357ebd;
    color: #fff;
}
</style>

<script>
function change(value){
	$(".haha").hide();
	$("#changeType").val(value);
	
	if(value == 1){
		$("#time2chuo").show();
	}else if(value == 2){
		$("#chuo2time").show();
	}else if(value == 3){
		$("#userSign").show();
	}else if(value == 4){
		$("#base64").show();
	}else if(value == 5){
		$("#url").show();
	}else if(value == 6){
		$("#json").show();
	}
}
</script>

<body>
 
<table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="17" valign="top" background="/Public/houtai//images/mail_leftbg.gif"><img src="/Public/houtai//images/left-top-right.gif" width="17" height="29" /></td>
    <td valign="top" background="/Public/houtai//images/content-bg.gif"><table width="100%" height="31" border="0" cellpadding="0" cellspacing="0" class="left_topbg" id="table2">
      <tr>
        <td height="31"><div class="titlebt">欢迎界面</div></td>
      </tr>
    </table></td>
    <td width="16" valign="top" background="/Public/houtai//images/mail_rightbg.gif"><img src="/Public/houtai//images/nav-right-bg.gif" width="16" height="29" /></td>
  </tr>
  <tr>
    <td valign="middle" background="/Public/houtai//images/mail_leftbg.gif">&nbsp;</td>
    <td valign="top" bgcolor="#F7F8F9">
    
<div style="font-size:12px">
【程序猿 和 攻城狮 所需工具】
<font color="red">选择类型：</font>
<select onchange="change(this.value)">
    <option value="1" <?php if($spara1[changeType] == 1): ?>selected<?php endif; ?> >时间→戳</option>
    <option value="2" <?php if($spara1[changeType] == 2): ?>selected<?php endif; ?> >戳→时间</option>
    <option value="3" <?php if($spara1[changeType] == 3): ?>selected<?php endif; ?> >userSign互转</option>
    <option value="4" <?php if($spara1[changeType] == 4): ?>selected<?php endif; ?> >base64互转</option>
    <option value="5" <?php if($spara1[changeType] == 5): ?>selected<?php endif; ?> >url互转</option>
    <option value="6" <?php if($spara1[changeType] == 6): ?>selected<?php endif; ?> >json格式化</option>
</select>
       		
<form action="/index.php/Admin/Index/zhuanhuan" method="post">
<table width=100%>
	<input type="hidden" name="changeType" id="changeType" value="<?php echo ($spara1[changeType]); ?>" />  
	
	<!------------------ 时间→戳  ---------------------->
     <tr id="time2chuo" class="haha" <?php if($res[date_end]): else: ?>style="display:none"<?php endif; ?> >
       	<td class="ss">时间转换为时间戳：
       		<input class="Wdate" type="text" name="date" onClick="WdatePicker()" value="<?php echo ($spara1["date"]); ?>">
       		<input type="text" name="hour" size="3" placeholder="时" value="<?php echo ($spara1["hour"]); ?>">
       		<input type="text" name="min" size="3" placeholder="分" value="<?php echo ($spara1["min"]); ?>">
       		<input type="text" name="second" size="3" placeholder="秒" value="<?php echo ($spara1["second"]); ?>">
       		<button type="submit" class="btn-primary">开始转换</button> &nbsp;&nbsp;&nbsp;&nbsp;
       		<?php echo ($res["date_end"]); ?>
       	</td>
    </tr>
    <!------------------ 戳→时间  ---------------------->
     <tr id="chuo2time" class="haha"  <?php if($res[time_end]): else: ?>style="display:none"<?php endif; ?> >
       	<td class="ss">时间戳转换为时间：
       		<input type="text" name="time" placeholder="输入时间戳" size="20" value="<?php echo ($spara1["time"]); ?>"> 
       		<button type="submit" class="btn-primary">开始转换</button>  &nbsp;&nbsp;&nbsp;&nbsp;
       		<?php echo ($res["time_end"]); ?>
       	</td>
    </tr>
	 
    <!------------------ userSign ---------------------->
    <tr id="userSign" class="haha" <?php if($res[user_end]): else: ?>style="display:none"<?php endif; ?> >
       	<td class="ss">userSign：
       		<input type="text" name="user_txt" size="50" value="<?php echo ($spara1["user_txt"]); ?>"> 
       		<select name="user_type">
       			<option value="1" <?php if($spara1[user_type] == 1): ?>selected<?php endif; ?> >userId转userSign</option>
       			<option value="2" <?php if($spara1[user_type] == 2): ?>selected<?php endif; ?> >userSign转userId</option>
       		</select>
       		<button type="submit" class="btn-primary">开始转换</button>   &nbsp;&nbsp;&nbsp;&nbsp;
       		<?php echo ($res["user_end"]); ?>
       	</td>
    </tr>
    
    <!------------------ base64 ---------------------->
    <tr id="base64" class="haha" <?php if($res[base64_end]): else: ?>style="display:none"<?php endif; ?> >
       	<td class="ss">base64：
       		<input type="text" name="base64_txt" placeholder="输入字符串" size="50" value="<?php echo ($spara1["base64_txt"]); ?>"> 
       		<select name="base_type">
       			<option value="1" <?php if($spara1[base_type] == 1): ?>selected<?php endif; ?> >base64_encode</option>
       			<option value="2" <?php if($spara1[base_type] == 2): ?>selected<?php endif; ?> >base64_decode</option>
       		</select>
       		<button type="submit" class="btn-primary">开始转换</button>   &nbsp;&nbsp;&nbsp;&nbsp;
       		<?php echo ($res["base64_end"]); ?>
       	</td>
    </tr>
    
    <!------------------ url ---------------------->
    <tr id="url" class="haha" <?php if($res[url_end]): else: ?>style="display:none"<?php endif; ?> >
       	<td class="ss">url：
       		<input type="text" name="url_txt" placeholder="输入字符串" size="50" value="<?php echo ($spara1["url_txt"]); ?>"> 
       		<select name="url_type">
       			<option value="1" <?php if($spara1[url_type] == 1): ?>selected<?php endif; ?> >url_encode</option>
       			<option value="2" <?php if($spara1[url_type] == 2): ?>selected<?php endif; ?> >url_decode</option>
       		</select>
       		<button type="submit" class="btn-primary">开始转换</button>  &nbsp;&nbsp;&nbsp;&nbsp; 
       		<?php echo ($res["url_end"]); ?>
       	</td>
    </tr>
    
</table>
</form>
 
</div>


<form action="/index.php/Admin/Index/json2Array" method="post" target="_blank">
<table width=100%>
    <!------------------ json ---------------------->
    <tr id="json" class="haha" style="display:none">
       	<td class="ss">
       		<textarea style="width:98%;height:500px" name="json_txt"></textarea>
       		<select name="json_type">
       			<option value="1" selected>转换为数组</option>
       			<option value="2">格式化查看</option>
       		</select>
       		<button type="submit" class="btn-primary">走起！</button>  &nbsp;&nbsp;&nbsp;&nbsp; 
       	</td>
    </tr>
</table>
</form>
 
 
<hr>

<table  class="table table-hover">
	<!-- <tr>
     <td class="ss">【快速查找】</td>
     <form action="/index.php/Admin/Goods/findGoodsOnlyOne" method="post">
           <td class="ss">商品Id：
             <input type="text" name="txt" placeholder="输入商品Id精确查询" value="<?php echo ($quick_search["max_gid"]); ?>">
             <input type="hidden" name="txt_type" value="1">
      <button type="submit" class="btn-primary">查询</button>
           </td>
           </form>
           
           <form action="/index.php/Admin/User/findUserExec" method="post">
           <td class="ss">用户Id：
             <input type="text" name="user_id" placeholder="输入用户Id精确查询" value="<?php echo ($quick_search["max_uid"]); ?>">
      <button type="submit" class="btn-primary">查询</button>
           </td>
           </form>
    </tr> -->
  <!-- <tr>
       <td class="ss">【最新功能】</td>
         <td class="ss" colspan="2">
           <a href="<?php echo ($this_host); ?>/index.php/mobile/weitpl/addSingle" style="color:orange" target="_blank">商品入库</a> |
           <a href="/index.php/Admin/Device/scoreWall" style="color:green" target="_blank">安卓积分墙</a> |
           <a href="/index.php/Admin/Device/scoreMoney" style="color:blue" target="_blank">积分墙返钱</a> |
           <a href="/index.php/Admin/Device/FenQQ" style="color:pink" target="_blank">任务列表</a> |
           <a href="/index.php/Admin/User/UserSyLog" style="color:green" target="_blank">用户收益记录</a> |
           <a href="/index.php/Admin/Device/userApp" style="color:orange" target="_blank">用户APP</a> |
           <a href="/index.php/Admin/Device/QunLog" style="color:purple" target="_blank">群日志</a> |
           <a href="/index.php/Admin/Xs/Laba" style="color:red" target="_blank">喇叭</a> |
           <a href="/index.php/Admin/User/XsCode" style="color:green" target="_blank">悬赏邀请码</a> |
           <a href="/index.php/Admin/Device/XsDuanzi" style="color:blue" target="_blank">悬赏锁屏段子</a> |
         </td>
  </tr> -->
  <tr>
	 	<td class="ss">【快速进入】</td>
    <td class="ss" colspan="2">
        <a href="/index.php/Admin/SystemSetting/memtest" class="blue">缓存调试</a>&nbsp;
       	<a href="/index.php/Admin/SystemSetting/seeImg" class="green">图片查看</a>
    </td>
  </tr>
</table>
</body>