function checkForm(type){
	/*判断 创建时间*/
	var cj_date1 = $("#cj_date1").val();
	var cj_hour1 = $("#cj_hour1").val();
	var cj_min1 = $("#cj_min1").val();
	var cj_sec1 = $("#cj_sec1").val();
	var cj_date2 = $("#cj_date2").val();
	var cj_hour2 = $("#cj_hour2").val();
	var cj_min2 = $("#cj_min2").val();
	var cj_sec2 = $("#cj_sec2").val();
	
	if(cj_date1!='' || cj_hour1!='' || cj_min1!='' || cj_sec1!='' || cj_date2!='' || cj_hour2!='' || cj_min2!='' || cj_sec2!=''){
		if(cj_date1=='' || cj_hour1=='' || cj_min1=='' || cj_sec1=='' || cj_date2=='' || cj_hour2=='' || cj_min2=='' || cj_sec2==''){
			alert('您已选择按照创建时间查询，因此这一行的所有项都不能为空！');
			$("#cj_date1").select();
			return false;
		}
		if(isNaN(cj_hour1) || isNaN(cj_min1) || isNaN(cj_sec1) || isNaN(cj_hour2) || isNaN(cj_min2) || isNaN(cj_sec2)){
			alert('创建时间的 时、分、秒 必须输入数字！');
			$("#cj_hour1").select();
			return false;
		}
		if(cj_hour1<0 || cj_hour1>23 || cj_hour2<0 || cj_hour2>23 || cj_min1<0 || cj_min1>60 || cj_min2<0 || cj_min2>60 || cj_sec1<0 || cj_sec1>60 || cj_sec2<0 || cj_sec2>60){
			alert('创建时间的 时（0~23）、分（0~59）、秒（0~59） 输入的范围不正确！');
			$("#cj_hour1").select();
			return false;
		}
		if(cj_date1 > cj_date2 || ((cj_date1==cj_date2)&&(cj_hour1>cj_hour2)) || ((cj_date1==cj_date2)&&(cj_hour1==cj_hour2)&&(cj_min1>cj_min2)) || ((cj_date1==cj_date2)&&(cj_hour1==cj_hour2)&&(cj_min1==cj_min2)&&(cj_sec1>cj_sec2))  ){
			alert('创建时间 前面的时间应该小于或等于后面的时间！');
			$("#cj_date1").select();
			return false;
		}
	}
	
	
	
	
	/*************************************************************************/
	if(type == 'findTaoke'){
		/*判断 结算时间*/
		var js_date1 = $("#js_date1").val();
		var js_hour1 = $("#js_hour1").val();
		var js_min1 = $("#js_min1").val();
		var js_sec1 = $("#js_sec1").val();
		var js_date2 = $("#js_date2").val();
		var js_hour2 = $("#js_hour2").val();
		var js_min2 = $("#js_min2").val();
		var js_sec2 = $("#js_sec2").val();
		
		if(js_date1!='' || js_hour1!='' || js_min1!='' || js_sec1!='' || js_date2!='' || js_hour2!='' || js_min2!='' || js_sec2!=''){
			if(js_date1=='' || js_hour1=='' || js_min1=='' || js_sec1=='' || js_date2=='' || js_hour2=='' || js_min2=='' || js_sec2==''){
				alert('您已选择按照结算时间查询，因此这一行的所有项都不能为空！');
				$("#js_date1").select();
				return false;
			}
			if(isNaN(js_hour1) || isNaN(js_min1) || isNaN(js_sec1) || isNaN(js_hour2) || isNaN(js_min2) || isNaN(js_sec2)){
				alert('结算时间的 时、分、秒 必须输入数字！');
				$("#js_hour1").select();
				return false;
			}
			if(js_hour1<0 || js_hour1>23 || js_hour2<0 || js_hour2>23 || js_min1<0 || js_min1>60 || js_min2<0 || js_min2>60 || js_sec1<0 || js_sec1>60 || js_sec2<0 || js_sec2>60){
				alert('结算时间的 时（0~23）、分（0~59）、秒（0~59） 输入的范围不正确！');
				$("#js_hour1").select();
				return false;
			}
			if(js_date1 > js_date2 || ((js_date1==js_date2)&&(js_hour1>js_hour2)) || ((js_date1==js_date2)&&(js_hour1==js_hour2)&&(js_min1>js_min2)) || ((js_date1==js_date2)&&(js_hour1==js_hour2)&&(js_min1==js_min2)&&(js_sec1>js_sec2))  ){
				alert('结算时间 前面的时间应该小于或等于后面的时间！');
				$("#js_date1").select();
				return false;
			}
		}
		
		/*商品ID*/
		var sp_id = $("#sp_id").val();
		if(sp_id!=''){
			if(isNaN(sp_id)){
				alert('商品ID必须为数字');
				$("#sp_id").select();
				return false;
			}
			if(sp_id <= 0){
				alert('商品ID必须大于0');
				$("#sp_id").select();
				return false;
			}
		}
		
		/*数据区间判断*/
		var nums=new Array("sp_price","sp_num","sr_bilv","fk_money","xg_yugu","js_money","yg_shouru","bt_money","bt_bili");
		for(i=0;i<nums.length;i++){
			//alert(nums[i]);
			//判断弹出提示的文字
			var alert_words = '';
			if(nums[i]=='sp_price') alert_words='商品单价';
			else if(nums[i]=='sp_num') alert_words='商品数';
			else if(nums[i]=='sr_bilv') alert_words='收入比率';
			else if(nums[i]=='fk_money') alert_words='付款金额';
			else if(nums[i]=='xg_yugu') alert_words='效果预估';
			else if(nums[i]=='js_money') alert_words='结算金额';
			else if(nums[i]=='yg_shouru') alert_words='预估收入';
			else if(nums[i]=='bt_money') alert_words='补贴金额';
			else if(nums[i]=='bt_bili') alert_words='补贴比例';
			
			//定义变量，开始处理
			var checknum1 = $("#"+nums[i]+"1").val();
			var checknum2 = $("#"+nums[i]+"2").val();
			if(checknum1!='' || checknum2!='' ){
				if(checknum1=='' || checknum2==''){
					alert('您已选择按照 '+alert_words+' 区间查询，因此这一行的两项都不能为空！');
					$("#"+nums[i]+"1").select();
					return false;
				}
				if(isNaN(checknum1) || isNaN(checknum2)){
					alert(alert_words+' 必须输入数字！');
					$("#"+nums[i]+"1").select();
					return false;
				}
				if(checknum1 > checknum2){
					alert(alert_words+' 前面的数值应该小于或等于后面的数值！');
					$("#"+nums[i]+"1").select();
					return false;
				}
			}
			
			
		}
	}
	
	
	/*var sp_price1 = $("#sp_price1").val();
	var sp_price2 = $("#sp_price2").val();
	if(sp_price1!='' || sp_price2!=''){
		if(sp_price1=='' || sp_price2==''){
			alert('您已选择按照商品单价区间查询，因此这一行的两项都不能为空！');
			$("#sp_price1").select();
			return false;
		}
		if(isNaN(sp_price1) || isNaN(sp_price2)){
			alert('商品单价 必须输入数字！');
			$("#sp_price1").select();
			return false;
		}
		if(sp_price1 > sp_price2){
			alert('商品单价 前面的数值应该小于或等于后面的数值！');
			$("#sp_price1").select();
			return false;
		}
	}*/
	
	
	
}