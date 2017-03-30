<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>数据统计</title>
<script src='/App/Home/View/Index/jquery.js'></script>
<script>
$(function(){

})
</script>
<style>
body { font-size: 22px; }
#table h2 { font-size: 22px; margin-top: 0; }
#table table { border: 2px solid; }
#table td { padding: 3px; text-align: center; 1border: 2px solid; }

</style>
</head>
<body>
<div id="table">
    <h2>近<?php echo ($days); ?>日数据</h2>
    <table>
        <tr>
            <td>cp</td>  
            <td>昨日close</td> 
            <td>平均价</td>  
            <td>最高价</td>  
            <td>最低价</td>  
        </tr>
        
        <?php if(is_array($dt)): $k = 0; $__LIST__ = $dt;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($k % 2 );++$k;?><tr>
            <td><?php echo ($item["cp"]); ?></td> 

                <?php if($item["last_per"] > 0): ?><td style="color:red">
                <?php else: ?>
            <td style="color:green"><?php endif; ?>
                <?php echo ($item["last_close"]); ?> (<?php echo ($item["last_per"]); ?>)
            </td>

                <?php if($item["avg_per"] > 0): ?><td style="color:red">
                <?php else: ?>
            <td style="color:green"><?php endif; ?>
                <?php echo ($item["avg_close"]); ?> (<?php echo ($item["avg_per"]); ?>)
            </td>

                <?php if($item["max_per"] > 0): ?><td style="color:red">
                <?php else: ?>
            <td style="color:green"><?php endif; ?>
                <?php echo ($item["max_close"]); ?> (<?php echo ($item["max_per"]); ?>)
            </td>

                <?php if($item["min_per"] > 0): ?><td style="color:red">
                <?php else: ?>
            <td style="color:green"><?php endif; ?>
                <?php echo ($item["min_close"]); ?> (<?php echo ($item["min_per"]); ?>)
            </td>
        </tr><?php endforeach; endif; else: echo "" ;endif; ?>
    </table>
</div>

</body>
</html>