<?php

//返回redis操作对象
function redis() {
    $host = $_SERVER['HTTP_HOST'];

	if (strpos($host, 'ocalhost:6699') || strpos($host, '92.168.1.54:6699')) {
		//不走redis，测试入库逻辑
		//return false;

		//走redis
		$redis = new \Redis();
   		$redis->pconnect('127.0.0.1', 6379);

	} else if (strpos($host, ':6699') || strpos($host, ':8888')) {//测试环境
		//不走redis，测试入库逻辑
		//return false;

		$redis = new \Redis();
   		$redis->pconnect('127.0.0.1', 6379);

	} else {//正式环境

		$redis = new \Redis();
   		$redis->pconnect(C('REDIS_HOST'), 6379);
   		$redis->auth(C('REDIS_PWD'));

	}

	return $redis;

}

//传入 vid 返回 视频链接
function getVImgU($vid) {
    $imageHost=C('IMAGES_SERVER_HOST');
    $tmp = 'http://'.$imageHost.'/'.substr($vid,0,2).'/'.substr($vid,2,2).'/'.$vid.'.jpg';

    return $tmp;
}

//传入 ptlist和对应的bidlist 返回midList
function getMidL($ptList, $bidList) {

    foreach ($ptList as $k=>$v) {
        $midL[] = md5('p'.$v.'bid'.$bidList[$k]);
    }

    return $midL;
}

/**
 * 同步网络图片URL到img0服务器对应的目录
 * @author renxing
 * @since 2015年10月15日 12:00:55
 */
function curlUploadImage($pic_url,$php_file_name){
    header("Access-Control-Allow-Origin: *");

    //初始化
    $ch = curl_init();
    //设置选项，包括URL
    curl_setopt($ch, CURLOPT_URL, "http://imgadmin0.qwbcg.mobi/$php_file_name?token=mmkj2xai823@asin&pic_url=$pic_url");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    //执行并获取HTML文档内容
    $output = curl_exec($ch);
    //释放curl句柄
    curl_close($ch);
    //打印获得的数据
    //echo ($output);exit;
    $output_arr = json_decode($output,true);
    $res = $output_arr['data']['picture_id'];

    return $res;

}

/*
 * @author wjy
 * post 方式
 */
function curlPost($url, $data) {
    header("Access-Control-Allow-Origin: *");

    $ch = curl_init ();
    curl_setopt ( $ch, CURLOPT_URL, $url );
    curl_setopt ( $ch, CURLOPT_POST, 1 );
    curl_setopt ( $ch, CURLOPT_HEADER, 0 );
    curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
    curl_setopt ( $ch, CURLOPT_POSTFIELDS, $data );
    $return = curl_exec ( $ch );
    curl_close ( $ch );

    return $return;
}

/*
 * @author jiangyaru
 * https & post 方式
 */
function httpsPost($url, $data, $method='POST'){  
	// qq($data); 
    $curl = curl_init(); // 启动一个CURL会话  
    curl_setopt($curl, CURLOPT_URL, $url); // 要访问的地址  
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // 对认证证书来源的检查  
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false); // 从证书中检查SSL加密算法是否存在  
    curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); // 模拟用户使用的浏览器  
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1); // 使用自动跳转  
    curl_setopt($curl, CURLOPT_AUTOREFERER, 1); // 自动设置Referer  
    if($method=='POST'){  
        curl_setopt($curl, CURLOPT_POST, 1); // 发送一个常规的Post请求  
        if ($data != ''){ 
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data); // Post提交的数据包  
        }  
    }
    curl_setopt($curl, CURLOPT_TIMEOUT, 30); // 设置超时限制防止死循环  
    curl_setopt($curl, CURLOPT_HEADER, 0); // 显示返回的Header区域内容  
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // 获取的信息以文件流的形式返回

    $tmpInfo = curl_exec($curl); // 执行操作 
    curl_close($curl); // 关闭CURL会话  
    return $tmpInfo; // 返回数据  
}  


//utf8的中文字符串截取 3个为1 len
function chinesesubstr($str, $start, $len) {
    $strlen = $start + $len; // 用$strlen存储字符串的总长度，即从字符串的起始位置到字符串的总长度

    for($i = $start; $i < $strlen;) {
        if (ord ( substr ( $str, $i, 1 ) ) > 0xa0) { // 如果字符串中首个字节的ASCII序数值大于0xa0,则表示汉字
            $tmpstr .= substr ( $str, $i, 3 ); // 每次取出三位字符赋给变量$tmpstr，即等于一个汉字
            $i=$i+3; // 变量自加3
        } else{
            $tmpstr .= substr ( $str, $i, 1 ); // 如果不是汉字，则每次取出一位字符赋给变量$tmpstr
            $i++;
        }
    }
    return $tmpstr; // 返回字符串
}

//传入平台号返回平台名
function getPtN($k) {
	$pt = array('优酷','爱奇艺','乐视','腾讯视频','美拍','今日头条','秒拍','b站','搜狐','天天快报','一点资讯',
    '土豆视频','爆米花','酷6视频','北京时间','微视','小影','百思不得姐','内涵段子','YY','56视频','Acfun','微博',
    '凤凰号','网易号','UC头条号','芒果视频','百家号','qq空间','央视网-爱西柚','PPTV','新蓝','第一视频','风行网');

	return $pt[$k-1];

}

//传入平台号返回平台单词名
function getPtName($k) {
    $pt = array('youku','iqiyi','letv','qqvideo','meipai','toutiao','miaopai','bilibili','sohu','kuaibao',
    'yidian','tudou','baomihua','ku6','beijingtime','weishi','xiaoying','budejie','neihan','yy','56','Acfun','weibo',
    'fenghuanghao','wangyihao','uv','mgtv','baijiahao','qzone','CCTV-aixiyou','PPTV','xinlan','V1.CN','fengxing');

	return $pt[$k-1];

}

//获取服务器环境
function getEvn() {
	$host = $_SERVER['HTTP_HOST'];
	//0是本地，1是201，2是线上
	if (strpos($host, 'ocalhost') || strpos($host, '92.168.1.54')) {

		return 0;

	} else if (strpos($host, '92.168.1.201')) {//测试环境

		return 1;

	} else if (strpos($host, 'taging-')) {//staging测试环境

		return 3;

	} else {

		return 2;

	}
}

//更加科学，通用的获取服务器环境的方法
function getEvn2() {
    $e = C('MACHINE_ENV');

    if ($e == 'local') {
        return 1;
    } else if ($e == '201') {
        return 2;
    } else if ($e == 'staging') {
        return 3;
    } else if ($e == 'prod') {
        return 4;
    }
}

//php 性能分析
function phpAnaB() {

	xhprof_enable(XHPROF_FLAGS_CPU+XHPROF_FLAGS_MEMORY);
    xhprof_enable();
}

function phpAnaE() {

	$xhprofData = xhprof_disable();
    $xhprof_root = "/Users/wjy/Applications/xhprofPage";
    include_once $xhprof_root."/xhprof_lib/utils/xhprof_lib.php";
    include_once $xhprof_root."/xhprof_lib/utils/xhprof_runs.php";

    $xhprof_runs = new \XHprofRuns_Default();
    $runId = $xhprof_runs->save_run($xhprof_data, "test");

    /*$url = '<a href='.'http://localhost:7474/xhprof_html/index.php?run=' . $runId . '&source=test>test</a>';*/
    //echo $url;

    echo "<script language='javascript'>window.open(".'"http://localhost:7474/xhprof_html/index.php?run=' . $runId . '&source=test"'.");</script>";

    exit;
}

//获取当前/给定时间戳的0分0秒
function getNt0m0s($t) {
	$t = empty($t) ? time() : $t;

	$a = strtotime(date('y-m-d H:0:0', $t));

	return $a;

}

//get发送数据
function getDataByCurl($testurl) {
	 // test url
   	$ch = curl_init();  // create curl resource
   	curl_setopt($ch, CURLOPT_URL, $testurl);  // set url $testurl
   	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //return the transfer as a string
   	curl_setopt($ch, CURLOPT_HEADER, 0);
   	$output = curl_exec($ch); // $output contains the output string
   	curl_close($ch); // close curl resource to free up system resources

   	return $output;
}

/**
* [array_to_sql 根据数组key和value拼接成需要的sql]
* @param [string] $tbname 数据表名
* @param [type] $array [key, value结构数组]
* @param string $type [sql类型insert,update]
* @param [type] $where [key, value结构数组]
* @param [string] $wherestr 对于非等号的条件咱们干脆自己写了
* @param array $exclude [排除的字段]
* @return [string] [返回拼接好的sql]
*/
function getSql($tbname, $array, $type='insert', $where, $wherestr, $exclude = array()) {
    $sql = '';
    if (count($array) > 0) {
        foreach ($exclude as $exkey) {
            unset($array[$exkey]);//剔除不要的key
        }
        if ('insert' == $type) {
            $keys = array_keys($array);
            $values = array_values($array);
            $col = implode("`, `", $keys);
            $val = implode("', '", $values);
            $sql = "(`$col`) values('$val')";

            $res = 'INSERT INTO '.$tbname.' '.$sql;

        } else if('update' == $type){
            $tempsql = '';
            $temparr = array();
            foreach ($array as $key => $value) {
                $tempsql = "`$key` = '$value'";
                $temparr[] = $tempsql;
            }
            $sql = implode(",", $temparr);
            $tmpW = get_where($where, $wherestr);
            $res = 'UPDATE ' . $tbname . ' set ' . $sql . ' where' .$tmpW;

        } else if ('replace' == $type) {

        	$keys = array_keys($array);
            $values = array_values($array);
            $col = implode("`, `", $keys);
            $val = implode("', '", $values);
            $sql = "(`$col`) values('$val')";

            $res = 'REPLACE INTO '.$tbname.' '.$sql;

        }
    }

    return $res.';';
}

function get_where($arg = null, $str='') {
	foreach ((array)$arg as $key => $val) {
		if(is_int($key)) {
			$where .= " $val ";
		}else {
			if(is_string($val)) {
				if($val === null) {
					$where .= " and $key is null ";
				}else {
				$where .= " and $key = '$val' ";
				}
			} else if (is_array($val)) {
				foreach ($val as $v) {
					if(is_string($v)) {
						$in .= $in ? ",'$v'" : "'$v'";
					} else {
						$in .= $in ? ",$v" : "$v";
					}
				}
				$where .= " and $key in ($in)";
			} else {
				$where .= " and $key = $val ";
			}
		}
	}

	if ($str) {
		$where = $where.'and '.$str;
	}

	return substr($where, 4);
}

function exeSqls($sql) {//qq($sql);
	if (getEvn() == 0) {//本地环境
		$mysqli = new \MySQLi("127.0.0.1","root","root","qiaosuan");
	} else {
		$mysqli = new \MySQLi(C('PRI_DB_HOST'),"qiaosuan",C('PRI_DB_PWD'),"qiaosuan");
	}

    if($mysqli->connect_error){
        qq("连接失败".$mysqli->connect_error);
    }

    $res=$mysqli->multi_query($sql);
    $mysqli->close();

    if(!$res){
        return false;
    }else{
        return true;
    }

}

//获取分库分表后的表
function getTable($pt) {
	//这里还的加上时间的分表的逻辑
	$a = array();

	//当前月份
	$m = date('m', time());
	//当前小时
	$h = date('H', time());

	$a['videoAddinfoD'] = M('videoAddinfo'.$pt.'d'.$m);
	$a['videoAddinfoH'] = M('videoAddinfo'.$pt.'h'.$h);

	return $a;

}

//根据时间和平台获取表名 ／／tp版的
function getTByPtT($pt, $t) {//here
	$t = empty($t) ? time() : $t;

	//当前月份
	$m = date('m', $t);
	//当前小时
	$h = date('H', $t);

	// return array('videoAddinfo'.$pt.'d'.$m, 'videoAddinfo'.$pt.'h'.$h);
    return array('videoStatistics_'.$pt.'d'.$m, 'videoStatistics_'.$pt.'h'.$h);

}

//根据时间和平台获取表名 ／／tp版的
function getTByPtT2($pt, $t) {
	$t = empty($t) ? time() : $t;

	//当前月份
	$m = date('m', $t);
	//当前天
	$h = date('d', $t);

	return array('videoStatistics_'.$pt.'d'.$m, 'videoStatistics_'.$pt.'h'.$h);

}

//获取分库分表后的表名  //数据库版的
function getTableN($pt) {
	//这里还的加上时间的分表的逻辑
	$a = array();

	//当前月份
	$m = date('m', time());
	//当前小时
	$h = date('H', time());

	$a['d'] = 'qs_video_addinfo'.$pt.'d'.$m;
	$a['h'] = 'qs_video_addinfo'.$pt.'h'.$h;

	return $a;

}

//获取分库分表后的表名  //数据库版的
function getTableN2($pt, $t) {//here

    if (!$t) {
        $t = time();
    }

	//这里还的加上时间的分表的逻辑
	$a = array();

	//当前月份
	$m = date('m', $t);
	//当前天
	$h = date('d', $t);

	$a['d'] = 'qs_video_statistics_'.$pt.'d'.$m;
	$a['h'] = 'qs_video_statistics_'.$pt.'h'.$h;

	return $a;

}

//获取相差多少小时
function getHoursAgo($time1, $time2) {
	$time2 = $time2 ? $time2 : time();

	//小的在前
	$t1 = strtotime(date('y-m-d H:0:0', $time1));
	$t2 = strtotime(date('y-m-d H:0:0', $time2));

	$tmp = ($t2 - $t1) / 3600;

	return $tmp;
}

//获取给定时间戳距现在几天前
function getDayAgo($t,$now) {
    $now = $now ? $now : time();

    $day0 = strtotime(date('y-m-d', $t));
    $today0 = strtotime(date('y-m-d', $now));

    $dayAgo = ($today0 - $day0) / 86400;

    return $dayAgo;
}

//获取当前时间0分0秒的时间戳
function getHtime($t) {
    $t = $t ? $t : time();
	return strtotime(date('y-m-d H:0:0', $t));
}

//获取当前时间0时0分0秒的时间戳
function getDtime($t) {
	$t = $t ? $t : time();
	return strtotime(date('y-m-d 0:0:0', $t));
}

//二维数组转为一维
function toBaseA($dt) {
	$tmp = array();

	foreach ($dt as $k=>$v) {
		foreach ($v as $v1) {
			array_push($tmp, $v1);
		}
	}

	return $tmp;
}

//二维数组转为一维 //增强版，可以指定使用二维数组的特定key值
function toBaseArr($dt, $field) {
    $tmp = array();

    foreach ($dt as $k=>$v) {
        if (!empty($field)) {
            $tmp[] = $v[$field];
        } else {
            foreach ($v as $v1) {
                array_push($tmp, $v1);
            }
        }
    }

    return $tmp;
}

//csv文件导出数据 //浏览器下载
function export_csv($filename, $data) {
    header("Content-type:text/csv");
    header("Content-Disposition:attachment;filename=".$filename);
    header('Cache-Control:must-revalidate,post-check=0,pre-check=0');
    header('Expires:0');
    header('Pragma:public');
    echo $data;
}

//对二维数组根据某一字段进行排序
function arr2_sort($array, $orderby, $order=SORT_DESC, $sort_flags=SORT_NUMERIC){
	$refer = array();

	foreach ($array as $key => $value) {
		$refer[$key] = $value[$orderby];
	}

	array_multisort($refer, $order, $sort_flags, $array);

	return $array;
}

//获取特定天数前的0时时间戳和23，59时间戳
function getSt($days=0) {
	//今日0时时间戳回退特定天数
	$todayO = strtotime(date('Y-m-d',time())) - $days*86400;
	//今日23，59的时间戳回退特定天数
	$todayEnd = $todayO + 86399;

	return array($todayO, $todayEnd);

}

//获取当前时间所在的前推n周的时间0时时间戳和23，59时间戳
function getWt($n, $t) {

    $t = empty($t) ? time() : $t;

    //当前时间是星期几
    $w=date('w', $t);
    $w = $w==0 ? 7 : $w;

    //当前周的起始时间戳
    $st=getSt();
    $b = $st[0] - 86400*($w-1);

    //前推n周
    $b = $b - 86400*7*$n;
    $e = $b + 86400*7-1;

    return array($b, $e);

}

//获取当前时间所在的前推n月的时间0时时间戳和23，59时间戳
function getMt($n) {
    $n = $n-1;
    $t = time() - $n*86400*30 + 86400*15 - 86400*date('d');

    //当前时间是第几月
    $w = date('n', $t);

    $b = strtotime(date('Y-'.($w-1).'-01 00:00:00', $t));

    $e = strtotime(date('Y-'.$w.'-01 00:00:00', $t)) - 1;

    return array($b, $e);

}


//简单的出库二维数组去重
function simplifyArr($arr) {
	$tmp = array();

	foreach ($arr as $v) {
		if (!in_array($v, $tmp)) {
			array_push($tmp, $v);
		}
	}

	return $tmp;
}

/**
 * wjy 2016.5.5
 * 手动解码，解决线上ngiux get参数不自动编解码的问题
 */
function decodeGet($dt) {
		foreach ($dt as $k=>$v) {
				$dt[$k] = urldecode($v);
		}
		return $dt;
}

/**
 * wjy 2016.4.15
 * cdn key生成函数 传入请求对象Filename 返回完整可访问url
 * fileName  ori/1.png
 * 完整url示例 http://vfiles.yise.tv/test/1.png?auth_key=timestamp-rand-uid-md5hash
 */
function ossHash($fileName) {
		//过期时间2分钟
		$time = time()+120;
		$host = '/'.$fileName;
		$sstring = $host.'-'.$time.'-0-0-R1lpTrl2QSRAk1HO0zIu83mptoxEEIs7';
		$HashValue = md5($sstring);
		$url = 'http://vfiles.yise.tv/'.$fileName.'?auth_key='.$time.'-0-0-'.$HashValue;
		return $url;
		//echo '<a target="_blank" href="'.$url.'">ddd</a>';

}

/**
 * 通用Json返回格式
 * renxing,2015年06月26日11:42:11
 */
function retJson($errno,$errmsg,$keys='',$arrs=array()){
	$ret['errno'] = $errno;
	$ret['errmsg'] = $errmsg;
	if(count($keys)==count($arrs)){
		for($i=0;$i<count($keys);$i++){
			$ret[$keys[$i]] = $arrs[$i];
		}
	}
	echo json_encode($ret);exit;
}

/**
 * 接口数据返回方法
 * wjy,2016年04月19日11:42:11
 */
function retDt($errno,$errmsg,$dt){
	$ret['errno'] = $errno;
	$ret['errmsg'] = $errmsg;
	if ($dt) {
		$ret['data'] = $dt;
	} else {
        $ret['data'] = empty($errno) ? 1 : 0;
    }
	echo json_encode($ret);exit;
}


/**
 * 判断是否来自微信
 * @return $res,1=来自微信,2=来自WAP页
 */
function checkIsFromWx(){
	if(strstr($_SERVER['HTTP_USER_AGENT'],'MicroMessenger')){
		$res = 1;
	}else{
		$res = 2;
	}
	return $res;
}

/**
 * 获取客户端IP地址
 * renxing,2015年7月20日 19:17:55
 */
function getIP(){
	$ip=getenv('REMOTE_ADDR');
	$ip_ = getenv('HTTP_X_FORWARDED_FOR');
	if (($ip_ != "") && ($ip_ != "unknown")){
		$ip=$ip_;
	}
	return $ip;
}


/**
 * 加密与解密
 * @author ruansheng
 * @加密：encrypt($string, 'E', 'qgzs');
 * @解密：encrypt($string, 'D', 'qgzs');
 * @return string
 */
function encrypt($string,$operation,$key=''){
		$key=md5($key);
		$key_length=strlen($key);
		$string=$operation=='D'?base64_decode($string):substr(md5($string.$key),0,8).$string;
		$string_length=strlen($string);
		$rndkey=$box=array();
		$result='';
		for($i=0;$i<=255;$i++){
			$rndkey[$i]=ord($key[$i%$key_length]);
			$box[$i]=$i;
		}
		for($j=$i=0;$i<256;$i++){
			$j=($j+$box[$i]+$rndkey[$i])%256;
			$tmp=$box[$i];
			$box[$i]=$box[$j];
			$box[$j]=$tmp;
		}
		for($a=$j=$i=0;$i<$string_length;$i++){
			$a=($a+1)%256;
			$j=($j+$box[$a])%256;
			$tmp=$box[$a];
			$box[$a]=$box[$j];
			$box[$j]=$tmp;
			$result.=chr(ord($string[$i])^($box[($box[$a]+$box[$j])%256]));
		}
		if($operation=='D'){
			if(substr($result,0,8)==substr(md5(substr($result,8).$key),0,8)){
				return substr($result,8);
			}else{
				return'';
			}
		}else{
			return str_replace('=','',base64_encode($result));
		}
}

/**
* user_id转为user_sign
* @author ruansheng
* @param  string    $userSign 用户加密标识
*/
function userId_Translation_userSign($userId){
	if(!empty($userId)){
		return encrypt($userId,'E',C('USER_SIGN_KEY'));  //user_sign 转换为user_id
	}else{
		return false;
	}
 }

/**
* user_sign转为user_id
* @author ruansheng
* @param  string    $userSign 用户加密标识
*/
function userSign_Translation_userId($userSign){
	if(!empty($userSign)){
		return encrypt($userSign,'D',C('USER_SIGN_KEY'));  //user_sign 转换为user_id;
	}else{
		return false;
	}
 }


 /**
	* 检查是否是手机浏览
	* @author ruansheng
	* @return boolean
	*/
 function check_wap() {
		if(isset($_SERVER['HTTP_VIA'])){
			return true;
		}
		if(isset($_SERVER['HTTP_X_NOKIA_CONNECTION_MODE'])){
			return true;
		}
		if(isset($_SERVER['HTTP_X_UP_CALLING_LINE_ID'])){
			return true;
		}
		if(strpos(strtoupper($_SERVER['HTTP_ACCEPT']),"VND.WAP.WML") > 0){
			// Check whether the browser/gateway says it accepts WML.
			$br = "WML";
		}else{
			$browser = isset($_SERVER['HTTP_USER_AGENT']) ? trim($_SERVER['HTTP_USER_AGENT']) : '';
			if(empty($browser)){
				return true;
			}
			//$browser=substr($browser,0,4);
			if (strpos($browser,"iPhone")||strpos($browser,"Android")){
				$br = "WML";
			}else if((strpos($browser,"sgbcg")===0)||strpos($browser,"sgbcg")){
				$br = "WML";
			}else{
				$br = "HTML";
			}
		}
		if($br == "WML") {
			return true;
		}else{
			return false;
		}
 }


 /**
	* 判断是否存在对应的目录，若无则创建  这个并不通用
	* @author renxing
	* @since 2014年12月24日 10:35:03
	*/
 function makeDir($log_path){
		date_default_timezone_set('Asia/Shanghai');
		//$log_path = $_SERVER['DOCUMENT_ROOT'].'/FxData';
		//创建“年份”文件夹，格式为: /Data/2014
		$log_path_year = $log_path.'/'.date("Y",time());
		if(!is_dir($log_path_year)) {
			mkdir($log_path_year, 0777, true);
		}
		//创建“月份”文件夹，格式为: /Data/2014/201412
		$log_path_month = $log_path_year.'/'.date("Ym",time());
		if(!is_dir($log_path_month)) {
			mkdir($log_path_month, 0777, true);
		}
		//创建“日期”文件夹，格式为: /Data/2014/201412/20141212
		$log_path_date = $log_path_month.'/'.date("Ymd",time());
		if(!is_dir($log_path_date)) {
			mkdir($log_path_date, 0777, true);
		}
	return $log_path_date;
 }

//删除文件夹
function deldir($dir) {
  //先删除目录下的文件：
  $dh=opendir($dir);

  while ($file=readdir($dh)) {

    if($file!="." && $file!="..") {

      $fullpath=$dir."/".$file;

      if(!is_dir($fullpath)) {
          unlink($fullpath);
      } else {
          deldir($fullpath);
      }

    }

  }
  closedir($dh);

  //删除当前文件夹：
  if(rmdir($dir)) {
    return true;
  } else {
    return false;
  }
}


 function encode_id($id) {
	$sid = ($id & 0x0000ff00) << 16;
	$sid += (($id & 0xff000000) >> 8) & 0x00ff0000;
	$sid += ($id & 0x000000ff) << 8;
	$sid += ($id & 0x00ff0000) >> 16;
	$sid ^= 2823435;
	return $sid;
 }

 function decode_id($sid) {
	if (!is_int($sid) && !is_numeric($sid)) {
		return false;
	}
	$sid ^= 2823435;
	$id = ($sid & 0x00ff0000) << 8;
	$id += ($sid & 0x000000ff) << 16;
	$id += (($sid & 0xff000000) >> 16) & 0x0000ff00;
	$id += ($sid & 0x0000ff00) >> 8;
	return $id;
 }

 //中文字符截取
 function cnsubstrCommon($string, $sublen ,$a=0){
	if ($string == '') return false;
	$string = changeYinhaoCom($string,'out');
	$sum = 0;
	for ($i = 0 ; $i < strlen($string) ; $i++){
		if(ord($string{$i}) > 127) {
			$s .= $string{$i} . $string{++$i} . $string{++$i};
			$sum++;
			continue;
		} else {
			$s .= $string{$i};
			$sum++;
			continue;
		}
	}
	if($sublen >= $sum) {
		return $string;
	}
	$s = "";
	$index = 0;
	for($i = 0; $i < $sublen; $i++) {
		if(ord($string{$index}) > 127) {
			$s .= $string{$index} . $string{++$index} . $string{++$index};
			$index++;
			continue;
		} else {
			$s .= $string{$index};
			$index++;
			continue;
		}
	}
	if($a===0) {$s.="...";}else{$s.=$a;}
	return $s;
 }

 function changeYinhaoCom($content , $flag){
   	if($flag=="in"){
   		$content  = str_replace("\"","&quot;",$content);
   		$content  = str_replace("'","&#39;",$content);
   	}
   	if($flag=="out"){
   		$content  = str_replace("&quot;","\"",$content);
   		$content  = str_replace("&#39;","'",$content);
   	}
   	return $content;
   }

 function changeYh($content , $flag){
	if($flag=="in"){
		$content  = str_replace("\"","&quot;",$content);
		$content  = str_replace("'","&#39;",$content);
	}
	if($flag=="out"){
		$content  = str_replace("&quot;","\"",$content);
		$content  = str_replace("&#39;","'",$content);
	}
	return $content;
 }

 /**
	* 检查参数、签名校验
	*/
 function checkServerSign(){
		$requestSign = isset($_SERVER['HTTP_SIGN']) ? $_SERVER['HTTP_SIGN'] : false;
		$userSign = isset($_SERVER['HTTP_USERSIGN']) ? $_SERVER['HTTP_USERSIGN'] : false;  //该参数可为空
		$timestamp = isset($_SERVER['HTTP_TIMESTAMP']) ? $_SERVER['HTTP_TIMESTAMP'] : false;

		//必要参数 检查
		if(!$requestSign||!$timestamp){
			retJson(901, '基础参数不全',array(data),array(array()));
		}

		//签名 检查
		$method=$_SERVER['REQUEST_METHOD'];
		$requestData = $GLOBALS["_{$method}"]; //请求的数据
		$requestData['usersign']=$userSign;
		$requestData['timestamp']=$timestamp;

		$retRes=createQianming($requestData);
		$sign = $retRes['return_sign'];
		$clientSign = $retRes['clientSign'];

		if(strtolower($requestSign)!=$sign){ //签名失败
			$arr=array(
					'user_sign'=>$userSign,
					'client_sign'=>strtolower($requestSign),
					'server_str'=>$clientSign,
					'server_sign'=>$sign
			);
			retJson(902, 'sign is wrong',array(data),array(array()));
		}else{ //签名成功
			$userId=userSign_Translation_userId($userSign);
			if($userId){
				//$this->user_id=$userId;
			}
		}
 }

 /**
	*  创建签名
	*  @param array    $data 待创建签名数据
	*  @return string   $sign  参数签名
	*/
 function createQianming($data){
		$clientSign='';

		ksort($data);  //按照  key 排序
		$sign='';
		foreach($data as $key=>$val){
			if(is_array($val)){
				createQianming($val);
			}else{
				$sign.=$val;
			}
		}
		$clientSign='qgzs'.$sign.'sign';
		$return_sign = strtolower(sha1('qgzs'.$sign.'sign'));

		$res = array(
			'clientSign' => $clientSign,
			'return_sign' => $return_sign,
		);
		return $res;
 }


 /**
	* 快速打印数组
	* @author wjy
	*/
function qq(){
    //支持一次打印多个
    $paramL = func_get_args();

    // echo '当前调试所在行：'.debug_backtrace()[0]['line'];

	echo "<pre style='font-size:20px'>";

    foreach ($paramL as $v) {
        if (!isset($v)) {
            $v = 'here';
        }

        if (is_array($v) || strlen($v) > 30) {
            print_r($v);
            echo '<br>';
        } else {
            var_dump($v);
        }
    }

	echo "</pre>";
	exit;
 }

//不退出版的qq
function ss(){
    //支持一次打印多个
    $paramL = func_get_args();

    echo "<pre style='font-size:18px'>";

    foreach ($paramL as $v) {
        if (!isset($v)) {
            $v = 'here';
        }

        if (is_array($v) || strlen($v) > 30) {
            print_r($v);
            echo '<br>';
        } else {
            var_dump($v);
        }
    }

    echo "</pre>";
}

//快速输出，适用于脚本中查看运行情况
function ee($a) {
    if (is_array($a)) {
        $a = json_encode($a);
    }

    echo $a.' <br> ';
}

 /**
	* 快捷调试
	* @author weijieyu
	*/
 function ff($arr){
		echo "<pre style='font-size:20px'>";
		var_dump($arr);
		echo "</pre>";
		exit;
 }

 /**
	* 快捷查看sql
	* @author weijieyu
	*/
 function sql($table){
	if (empty($table)) {
 		$table = M('');
 	}

 	if (gettype($table) == 'string') {
 		$table = M($table);
 	}

	echo($table->getLastSql());
	exit;
 }

 /**
	* 快捷查看sql
	* @author weijieyu
	*/
 function err($table){
 	if (empty($table)) {
 		$table = M('');
 	}

 	if (gettype($table) == 'string') {
 		$table = M($table);
 	}

	echo($table->getDbError());
	exit;
 }

// 过滤掉emoji表情
function filterEmoji($str){
	$str = preg_replace_callback(
					'/./u',
					function (array $match) {
							return strlen($match[0]) >= 4 ? '' : $match[0];
					},
					$str);

	return $str;
}

/**
* 后台展示列表中快捷禁用
* @author weijieyu
*/
function ajaxDel($table,$_data){
	$tbName = M($table);
	$id = isset($_data['id'])?$_data['id']:-1;
	$field = isset($_data['field'])?$_data['field']:-1;
	$value = isset($_data['value'])?$_data['value']:-1;
	if($id != -1 && $field != -1 && $value != -1){//如果操作成功
			$where['id'] = $id;
			$data[$field] = $value;
			$result = $tbName->where($where)->save($data);
			echo 1;exit;
	}else{
			echo -1;exit;
	}
}

/**
* 后台展示列表中快捷删除通过id
* @author weijieyu
*/
function deleteId($table, $id){
	if (is_string($table)) {
			$table = M($table);
	}

	$where['id'] = $id;
	$res = $table->where($where)->delete();
	return $res;
}

/**
 * author@ siya
 */
//根据平台获取fans表名 ／／tp版的
function getTByPtTtoFans($pt) {

	return array('FansAddinfo'.$pt.'d', 'FansAddinfo'.$pt.'h');

}
/**
 * [unescape description] unicode转utf8
 */
function unescape( $str ) {
     $str = rawurldecode( $str );
     preg_match_all( "/(?:%u.{4})|&#x.{4};|&#\d+;|.+/U" , $str , $r );
     $ar = $r [0];
     //print_r($ar);
     foreach ( $ar as $k => $v ) {
         if ( substr ( $v ,0,2) == "%u" ){
             $ar [ $k ] = iconv( "UCS-2BE" , "UTF-8" ,pack( "H4" , substr ( $v ,-4)));
   }
         elseif ( substr ( $v ,0,3) == "&#x" ){
             $ar [ $k ] = iconv( "UCS-2BE" , "UTF-8" ,pack( "H4" , substr ( $v ,3,-1)));
   }
         elseif ( substr ( $v ,0,2) == "&#" ) {
             $ar [ $k ] = iconv( "UCS-2BE" , "UTF-8" ,pack( "n" , substr ( $v ,2,-1)));
         }
     }
     return join( "" , $ar );
}

/**
 * 获取给定时间戳的0时0分0秒
 */
function strToZeroTime($time){
	return strtotime(date('Y-m-d',$time));
}
/**
 * 获取某平台下的24小时的videoaddinfo名称
 */
function getTableByPt($pt){
	$videoAddName=array();
	for ($i=1; $i<=24 ; $i++) {
		if($i<=9){
			$videoAddName[]='videoAddinfo'.$pt.'h0'.$i;
		}else{
			$videoAddName[]='videoAddinfo'.$pt.'h'.$i;
		}

	}
	return $videoAddName;

}
/**
 * 获取指定时间戳的23:59
 */
function getEndT($time){
	$end=strToZeroTime($time)+86399;
	return $end;
}

/**
 * 获取上月同期时间戳
 */
function getBeMonth($time){
	$f=date('Y-m-d 0:0:0',$time);
	$t=date(date('Y-m-d 0:0:0',$time),strtotime('-1 month'));
	echo $t;
	return strtotime($t);
}

/**
 * 判断时间戳是否在一周内
 */
function ifweek($time){
	if($time < (strToZeroTime(time()) + 86400) && $time >=(strToZeroTime(time()) - 6*86400)){
		return true;
	}else{
		return false;
	}
}

/**
 * 判断时间戳是否是今天
 */
function iftoday($time){
	if($time >= strToZeroTime(time()) && $time < strToZeroTime(time())){
		return turn;
	}else{
		return false;
	}
}

/**
 * 打印sql
 */
function printSql($table){
	if (empty($table)) {
 		$table = M('');
 	}

 	if (gettype($table) == 'string') {
 		$table = M($table);
 	}

	echo($table->getLastSql());
}

function getTbByPt($pt,$day,$type){
	if($day < 10){
		$day = '0'.$day;
	}
	return "video_statistics_".$pt."$type".$day;
}

/**
 * 二维数组转成一维数组
 */
function arr2_to_arr1($arr2, $type=0){
	$arr1 = array();
	foreach ($arr2 as $k => $v) {
		foreach ($v as $kk => $vv) {
			if($type=1){if(is_null($vv)){$vv = '-';}}//附加功能
			$arr1[] = $vv;
		}
	}
	return $arr1;
}

/**
 *  生成csv文件
 */
 function createFile($fileN, $data_str) {

	$fp = fopen($fileN, "a"); //打开csv文件，如果不存在则创建
	// $result=fwrite($fp,$data_str); //写入数据
	fwrite($fp, chr(0xEF).chr(0xBB).chr(0xBF)); // 写入BOM头，防止乱码

	$array=explode("\n",$data_str);

	foreach ($array as $value) {
		$da=explode("\t",$value);
		fputcsv($fp, $da);
	}
	fclose($fp); //关闭文件句柄
}


// 判断文件是否存在
function reportIsExist($file){
	header("Access-Control-Allow-Origin: *");

	$php_file_name = 'getReport.php';

	$fileName = $file['tmp_name'];
	$filePath = $file['path'];

	$dHosts=C('ASSETS_PORTAL_HOST');
	// $dHosts='http://121.42.38.253:8081/';
	$ch=curl_init("$dHosts/$php_file_name?path=$filePath&name=$fileName");
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$data=curl_exec($ch);
	curl_close($ch);

	$data_arr = json_decode($data,true);
	$exist=$data_arr['exist'];

	if($exist){
		$dlHosts=C('DOWNLOAD_SERVER_HOST');  //'http://download.meimiaoip.com/report_export'
		$re['url']='http://'.$dlHosts.'/report_export'.$filePath.$fileName;
		retDt(0, 'ook', $re);
	}
}

// 后台用户信息的导出
function backend_reportIsExist($file){
	header("Access-Control-Allow-Origin: *");

	$php_file_name = 'getReport.php';

	$fileName = $file['tmp_name'];
	$filePath = $file['path'];

	$dHosts=C('ASSETS_PORTAL_HOST');
	// $dHosts='http://121.42.38.253:8081/';
	$ch=curl_init("$dHosts/$php_file_name?path=$filePath&name=$fileName");
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$data=curl_exec($ch);
	curl_close($ch);

	$data_arr = json_decode($data,true);
	$exist=$data_arr['exist'];

	if($exist){
		$dlHosts=C('DOWNLOAD_SERVER_HOST');  //'http://download.meimiaoip.com/report_export'
		$re['url']='http://'.$dlHosts.'/'.$filePath.$fileName;
		retDt(0, 'ook', $re);
	}
}

/**
 * 上传文件
 */
function uploadFile($file){
	header("Access-Control-Allow-Origin: *");
	// header('content-type:text/html;charset=utf8');
	$php_file_name = 'postReport.php';

	$fileName = $file['tmp_name'];
	$filePath = $file['path'];

	$fileData = array("path" => $filePath, "file"  => "@".realpath($fileName));

	// $dHosts='http://121.42.38.253:8081/';
	// $dHosts='portal-intra.assets.meimiaoip.com:8081';
	$dHosts=C('ASSETS_PORTAL_HOST');
	$ch=curl_init("$dHosts/$php_file_name");

	// curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_SAFE_UPLOAD, false);
	curl_setopt($ch, CURLOPT_POSTFIELDS,$fileData);
	$data=curl_exec($ch);
	curl_close($ch);


	$data_arr = json_decode($data,true);

	// 删除本地产生的文件
	unlink(realpath($fileName));
	$rootPath='/mnt/download/report_export'; //http://download.meimiaoip.com/report_export/
	// $re['url']='localhost:11111'.$rootPath.$filePath.$fileName;
	// $re['url']='http://download.meimiaoip.com/report_export'.$filePath.$fileName;

	$dlHosts=C('DOWNLOAD_SERVER_HOST');  //'http://download.meimiaoip.com/report_export'
	$re['url']='http://'.$dlHosts.'/report_export'.$filePath.$fileName;
	retDt(0, 'ok', $re);
}

/**
 * 后台上传文件
 */
function backend_uploadFile($file){
	header("Access-Control-Allow-Origin: *");
	// header('content-type:text/html;charset=utf8');
	$php_file_name = 'postReport.php';

	$fileName = $file['tmp_name'];
	$filePath = $file['path'];

	$fileData = array("path" => $filePath, "file"  => "@".realpath($fileName));

	// $dHosts='http://121.42.38.253:8081/';
	// $dHosts='portal-intra.assets.meimiaoip.com:8081';
	$dHosts=C('ASSETS_PORTAL_HOST');
	$ch=curl_init("$dHosts/$php_file_name");

	// curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_SAFE_UPLOAD, false);
	curl_setopt($ch, CURLOPT_POSTFIELDS,$fileData);
	$data=curl_exec($ch);
	curl_close($ch);


	$data_arr = json_decode($data,true);

	// 删除本地产生的文件
	unlink(realpath($fileName));
	$rootPath='/mnt/download/backend'; //http://download.meimiaoip.com/report_export/
	// $re['url']='localhost:11111'.$rootPath.$filePath.$fileName;
	// $re['url']='http://download.meimiaoip.com/report_export'.$filePath.$fileName;

	$dlHosts=C('DOWNLOAD_SERVER_HOST');  //'http://download.meimiaoip.com/report_export'
	$re['url']='http://'.$dlHosts.'/report_export'.$filePath.$fileName;

	retDt(0, 'ok', $re);
}

// 视频头图url处理
function dealVImg($list){
    foreach ($list as $k => $v) {
        if ($v['has_d']=='1') {
            $list[$k]['v_img']='http://'.C('IMAGES_SERVER_HOST').'/'.substr($v['vid'],0,2).'/'.substr($v['vid'],2,2).'/'.$v['vid'].'.jpg';
        } else if ($v['platform']==2 || $v['platform']==4) {
            $list[$k]['v_img']='';
        }
        if ($v['platform']=='23') {  //微博的播放地址
            $list[$k]['v_url']="http://weibo.com/tv/v/".$v['v_url'];
        } else if ($v['platform']=='20') {  //YY的播放地址
            $list[$k]['v_url']="http://www.yy.com/".$v['v_url'];
        } else if ($v['platform']=='15') {  //北京时间的播放地址
            $list[$k]['v_url']="http://new.item.btime.com/".$v['v_url'];
        }
        unset($list[$k]['has_d']);
    }
    return $list;
}

// 分组的头图url处理
function dealGroupImg($list){
    foreach ($list as $k => $v) {
        $list[$k]['img']='http://'.C('IMAGES_SERVER_HOST').'/'.substr($v['img'],0,2).'/'.substr($v['img'],2,2).'/'.$v['img'].'.jpg';
    }
    return $list;
}

//二维数组中的某字段进行排序
function array_2_sort_by_field($array2sortbyfield, $field, $sort = 'SORT_DESC'){

	$sort = array(
	        'direction' => $sort, //排序顺序标志 SORT_DESC 降序；SORT_ASC 升序
	        'field'     => $field,       //排序字段
	);
	$arrSort = array();
	foreach($array2sortbyfield AS $uniqid => $row){
	    foreach($row AS $key=>$value){
	        $arrSort[$key][$uniqid] = $value;
	    }
	}
	if($sort['direction']){
	    array_multisort($arrSort[$sort['field']], constant($sort['direction']), $array2sortbyfield);
	}

	return $array2sortbyfield;
}

/**
     * 获取某年第几周的开始日期和结束日期
     * @param int $year
     * @param int $week 第几周;
     */
function weekday($year,$week=1){

    $year_start = strtotime($year.'W01');
    $year_end = strtotime(($year+1).'W01') - 7*86400;

    // 判断第一天是否为第一周的开始
    if (intval(date('W',$year_start))===1){
        $start = $year_start;//把第一天做为第一周的开始
    }
    else{
        //$week++;
        $start = strtotime('+1 monday',$year_start);//把第一个周一作为开始
    }

    // 第几周的开始时间
    if ($week===1){
        $weekday['start'] = $start;
    }else{
        $weekday['start'] = strtotime('+'.($week-0).' monday',$start);
    }

    // 第几周的结束时间
    $weekday['end'] = strtotime('+1 sunday',$weekday['start']);

    return $weekday;
}

/**
 * 数组做分页
 * @param  [type] $array [description]
 * @param  [type] $rows  [description]
 * @return [type]        [description]
 */
function array_page($array,$rows, $pageNum){
    $count=count($array);
    $Page=new \Think\Page($count,$rows);
    $Page->firstRow = ($pageNum-1)*$rows;
    $list=array_slice($array,$Page->firstRow,$Page->listRows);
    return $list;
}

include "plat.php";

