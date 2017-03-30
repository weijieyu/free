<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>数据统计</title>
<script src='/App/Home/View/Index/jquery.js'></script>
<script>
$(function(){

    $('.cp').click(function(){
        $(this).parent().addClass('gray');
    })

})
</script>
<style>
body { font-size: 20px; }
#table table { border: 2px solid; }
#table td { padding: 3px; text-align: center; 1border: 2px solid; }

.gray { color: #c6e2c6; }
</style>
</head>
<body>
<div id="table">
    <table>
        <tr>
            <td>cp</td>  
            <td>连跌</td>  
            <td>连涨</td>  
            <td>交替</td>  
            <td>跌涨率</td>  
            <td>涨次</td> 
            <td>涨量</td> 
            <td>平均涨量</td> 
            <td>中位涨量</td> 
            <td>跌次</td>  
            <td>跌量</td>  
            <td>平均跌量</td>  
            <td>中位跌量</td>  
            <td>最大跌量</td>  
            <td>时期涨跌幅</td>  
        </tr>
        
        <?php if(is_array($dt)): $k = 0; $__LIST__ = $dt;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($k % 2 );++$k;?><tr>
            <td class='cp'><?php echo ($item["cp"]); ?></td>  
            <td><?php echo ($item["con_down_c"]); ?>(<?php echo ($item["con_down_c_per"]); ?>)</td>  
            <td><?php echo ($item["con_up_c"]); ?>(<?php echo ($item["con_up_c_per"]); ?>)</td>  
            <td><?php echo ($item["con_cg_c"]); ?>(<?php echo ($item["con_cg_c_per"]); ?>)</td>  
            <td><?php echo ($item["down_up_per"]); ?></td>  
            <td><?php echo ($item["count_up"]); ?></td> 
            <td><?php echo ($item["sum_up"]); ?></td> 
            <td><?php echo ($item["avg_up"]); ?></td> 
            <td><?php echo ($item["mid_up"]); ?></td> 
            <td><?php echo ($item["count_down"]); ?></td> 
            <td><?php echo ($item["sum_down"]); ?></td> 
            <td><?php echo ($item["avg_down"]); ?></td> 
            <td><?php echo ($item["mid_down"]); ?></td> 
            <td><?php echo ($item["max_down"]); ?></td> 
            <td><?php echo ($item["cg_per"]); ?></td> 
        </tr><?php endforeach; endif; else: echo "" ;endif; ?>
    </table>
</div>

</body>
</html>