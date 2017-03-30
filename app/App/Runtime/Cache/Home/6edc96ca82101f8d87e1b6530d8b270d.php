<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>iTools</title>
<script src="lib/jquery.js"></script>
<style>
html,body,ul,li,p,h2 { margin: 0; padding: 0; }
h1,h2 { font-size: inherit; font-style: normal; font-weight: normal; }
html { font-size: 30px; }
a { text-decoration: none; }

#fuck { padding: 8px; }
#fuck button {font-size: 30px; }
#fuck input { height: 40px; line-height: 40px; width: 65%; font-size: 25px; margin: 10px 0; display: block; }
#fuck textarea { display: none; width: 60%; height: 300px; padding: 10px; font-size: 20px; resize: none; }
#catalogue { position: absolute; right: 0; top: 20px; font-size: 20px; width: 13%; border: 1px solid; }
</style>
<script>
$(function(){
	$('#fuck button').click(function(){
		var tmpStr = ''
		$('#fuck input').each(function(index,ele){
			if (index == 0) {
				tmpStr += '今日工作\n\n'
			} else if (index == 3) {
				tmpStr += '明日计划\n\n'
			}
			if ($(ele).prop('value')) {
				tmpStr +=  index%3 + 1 + '.' + $(ele).prop('value') + '\n\n'
			}
		})
		
		$.ajax( {
			type: "post",
			url: 'email.js',
			data: {
				cont: tmpStr
			},
			timeout: 30000,
			success: function(res) {
				alert(res)
			}
		})
		
	})
})
</script>
</head>
<body>
<div id="fuck">
	<button>输出</button>
	<textarea></textarea>
	<h1>今日工作</h1>
	<input type="text">
	<input type="text">
	<input type="text">
	<h1>明日计划</h1>
	<input type="text">
	<input type="text">
	<input type="text">
</div>
</body>
</html>