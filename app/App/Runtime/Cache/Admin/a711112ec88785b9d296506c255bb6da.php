<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title><?php echo ($top); ?>-<?php echo ($title); ?></title>
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
        <!-- bootstrap 3.0.2 -->
        <link href="/Public/admin/AdminLTE/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <!-- font Awesome -->
        <link href="/Public/admin/AdminLTE/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
        <!-- Ionicons -->
        <link href="/Public/admin/AdminLTE/css/ionicons.min.css" rel="stylesheet" type="text/css" />
        <!-- Theme style -->
        <link href="/Public/admin/AdminLTE/css/AdminLTE.css" rel="stylesheet" type="text/css" />

        
        <style>
			table,tr,td,dt,dd{
				font-size:12px;
				font-family:Microsoft YaHei;
			}

			.btnhehe{
				float:left;
				margin-right:20px;
				height:25px
			}
			.divhehe{
				margin-top:3px;
			}
			
			.divhehe1{
				margin-top:3px;
				float:left;
				margin-right:20px;
			}
			
			.wq{
				color:red;
				background:#CCCCCC;
				width:100%;
				font-weight:bold;
				font-size:16px;
			}
			.wq2{
				color:red;
				background:#CCCCCC;
				width:100%;
			}
		</style>
        <script language="javascript">
		function cbAll(){
			  var fathercheck = document.getElementById("CheckedAll");
			  var childcheck = document.getElementsByName("id[]");
			  for(var i=0;i<childcheck.length;i++){
				  childcheck[i].checked=fathercheck.checked;
			  }
		}
		</script>
		
		<script>
		function AdminShowOrHide(flag){
			if(flag==1){
				$("#showMore").hide();
				$("#hideMore").show();
				$("#showTr").show();
			}else if(flag==2){
				$("#hideMore").hide();
				$("#showTr").hide();
				$("#showMore").show();
			}
		}
		</script>

</head> 


<body class="skin-blue">
         <div class="wrapper row-offcanvas row-offcanvas-left">
            <!-- Right side column. Contains the navbar and content of the page -->
            <aside class="right-side">
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1>
                       	<?php echo ($top); ?>
                        <small><?php echo ($title); ?></small>
                        <?php if($addurl): ?><small><a href="/index.php/Admin/Ribao/<?php echo ($addurl); ?>" title="点击开始添加">添加</a></small><?php endif; ?>
                        <?php if($addurl_blank): ?><small><a href="/index.php/Admin/Ribao/<?php echo ($addurl_blank); ?>" title="点击开始添加" target="_blank">添加</a></small><?php endif; ?>
                        <?php if($pg): ?><small style="color:black">
				       		[共有数据 <?php echo ($pg["total_data"]); ?> 条，
				       		每页显示 <?php echo ($pg["pagesize"]); ?> 条，
				       		当前第 <?php echo ($pg["this_page"]); ?> / <?php echo ($pg["total_page"]); ?> 页]
						</small><?php endif; ?>
						&nbsp;&nbsp;
						<?php if($moreLink): ?><small style="color:black;font-size:12px">
				       		<?php echo ($moreLink); ?>
						</small><?php endif; ?>
                    </h1>
                    
                    <ol class="breadcrumb">
                        <li><a href="#"><i class="fa fa-dashboard"></i>主页</a></li>
                        <li><a href="#"><?php echo ($top); ?></a></li>
                        <li class="active"><?php echo ($title); ?></li>
                    </ol>
                </section>

                <!-- Main content -->
                <section class="content">
                     <div class="row">
                        <div class="col-xs-12">
                            <div class="box">
                                <div class="box-body table-responsive no-padding">
									﻿<form action="/index.php/Admin/Ribao/article" method="get">
<table  class="table table-hover">
<tr>
       	<td>快速查找:
       		<input type="text" name="id" value="<?php echo ($stxt["id"]); ?>" size="10" placeholder="ID">
       		<input type="text" name="tag" value="<?php echo ($stxt["tag"]); ?>" size="10" placeholder="文章标签">
       		<input type="text" name="desc" value="<?php echo ($stxt["desc"]); ?>" size="10" placeholder="文章描述">
          <input type="text" name="author_uid" value="<?php echo ($stxt["author_uid"]); ?>" size="10" placeholder="作者id">
          <select name="orderstyle">
            <option value="default">--排序方式--</option>
            <option value="desc" <?php if($stxt["orderstyle"] == desc): ?>selected<?php endif; ?> >倒序</option>
            <option value="asc" <?php if($stxt["orderstyle"] == asc): ?>selected<?php endif; ?> >正序</option>
          </select>
       		<button type="submit" name="chazhao" value="1" class="btn-primary">开始查找</button>
       		&nbsp;&nbsp;&nbsp;&nbsp;<a href="/index.php/Admin/Ribao/article">显示全部</a>
       	</td>
</tr>
</table>
</form> 
<div class="box-footer clearfix" style="vertical-align:middle">
	<div  class="divhehe"  style="float:left"><?php echo ($pages); ?></div>
</div>
<table class="table table-hover">
     <tr>
       		 <th width="4%">序号</th>
           <th>id</th>
       		 <th>内容</th>
           
           <th>添加时间</th>
           <th>管理操作</th>
     </tr>
     
    <?php if(is_array($list)): $k = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($k % 2 );++$k;?><tr id="t3<?php echo ($item["id"]); ?>" <?php if($item['is_del'] == 1): ?>style="color:#CCC"<?php endif; ?> >
          	<td style="vertical-align:iosmiddle"><font color="#CCC">[<?php echo ($k); ?>]</font></td>
          	<td style="vertical-align:middle"><?php echo ($item["id"]); ?></td>
            <td style="vertical-align:middle"><?php echo ($item["content"]); ?></td>
            <td style="vertical-align:middle"><?php echo (date("Y-m-d H:i:s",$item["add_t"])); ?></td>
           	<td>
            
            <a href="/index.php/Admin/Ribao/addArticle?id=<?php echo ($item["id"]); ?>" target="_blank">编辑 |</a>
                  <label><input <?php if($item[is_del] == 1): ?>checked<?php endif; ?> style="vertical-align: text-bottom;" type="checkbox" name="is_del" value="1" class="yjyLock" bbsId='<?php echo ($item["id"]); ?>' />禁用</label>
               	  <!-- <a href="/index.php/Admin/Ribao/deleteArticle/id/<?php echo ($item["id"]); ?>" onclick="return confirm('确认删除？')">删除</a> -->
            </td>
          </tr><?php endforeach; endif; else: echo "" ;endif; ?> 
</table>

 <div class="box-footer clearfix" style="vertical-align:middle">
	<div  class="divhehe"  ><?php echo ($pages); ?></div>             
</div>
<script>
function ajaxPHP(id,field,value,msg,url){
  	if(!confirm(msg)){
    	return false;
  	}
  
    $.ajax({
      type: "GET",
    url: "/index.php/Admin/Ribao/"+url+"/id/"+id+"/value/"+value+"/field/"+field,
    success:function(data){
      if(data==1){
        //针对“禁用”操作的样式处理
        if(field=='is_del'){
          if(value==1){
            $("#t3"+id).css("color","#CCC");
          }else{
            $("#t3"+id).css("color","black");
          }
        }
        
      }else{
        alert('错误码：'+data);
      }
      
    }
  });
    
}
var aLock = document.querySelectorAll('.yjyLock')
speedyOp(aLock,'禁用','is_del')


function speedyOp(obj,word,field) {
  for (var i=0; i<obj.length; i++) {
    obj[i].onclick = function () {
      var id = this.getAttribute('bbsid')
      var val = this.checked ? 1 : 0
      var note = this.checked ? '确定'+word+'该文章？' : '确定解除'+word+'？'

      if (ajaxPHP(id,field,val,note,'articleAjax') === false) {
        this.checked = !this.checked
      }
    }
  }
}  
</script>
								</div>
                            </div>
                        </div>
                    </div>
                </section>
           </aside><!-- /.right-side -->
       </div><!-- ./wrapper -->


        <!-- jQuery 2.0.2 -->
        <script src="/Public/admin/AdminLTE/js/jquery-2.1.1.min.js"></script>
        <!-- Bootstrap -->
        <script src="/Public/admin/AdminLTE/js/bootstrap.min.js" type="text/javascript"></script>
        <!-- AdminLTE App -->
        <script src="/Public/admin/AdminLTE/js/AdminLTE/app.js" type="text/javascript"></script>
        <!-- AdminLTE for demo purposes -->
        <script src="/Public/admin/AdminLTE/js/AdminLTE/demo.js" type="text/javascript"></script>
        <script type="text/javascript">
			$(function(){
				$('.id_del_shop').click(function(){
					if(confirm('确定设置？')){
						var shop_id=$(this).attr('shop_id');
						var url="/index.php/Admin/Subscribe/delShop";
						$.post(url,{'shop_id':shop_id},function(data){
							var json=eval("("+data+")");
							if(json.status==1){
								alert(json.message);
							}else{
								alert(json.message);
							}
						});
					}
				});
				
				//处理分页样式
				$('.current').css({'background':'yellow','padding':'2px 10px','margin':'0 2px','border':'1px solid #b5d6e6'});
				$('.current').parent('div').children('a').css({'padding':'2px 10px','margin':'0 2px','border':'1px solid #b5d6e6'});
			});
        </script>
    </body>
</html>