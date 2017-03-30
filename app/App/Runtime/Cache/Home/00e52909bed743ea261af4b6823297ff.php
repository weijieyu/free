<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>数据统计</title>
<script src='/App/Home/View/Index/jquery.js'></script>
<script>
$(function(){

    //value框的回车提交
    $('#ipt input').keydown(function(ev){
        //回车校验  
        if (ev.which != "13") {
            return
        }
        
        //ajax 提交数据
        var cp = $('#cp').val()
        var long = $('#long').val()

        var url = '/index.php/Home/index/query/cp/'+cp+'/long/'+long

        $.ajax({
            type: "GET",
            url: url,
            timeout: 30000,
            success: function(res) {
                if (typeof res == "string") {
                    
                    res = JSON.parse(res)
                    
                }
            }
        })

    })

    //数据处理和展示
     


})
</script>
<style>
body { font-size: 25px; }
#ipt input { height: 30px; font-size: 25px; margin-right: 10px; }
</style>
</head>
<body>
<div id="ipt">
    公司代码: <input type="text" id="cp">
    历史跨度: <input type="text" id="long"><br>
</div>

</body>
</html>