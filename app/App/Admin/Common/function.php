<?php 

function content_replace($show_content)
{
	$search = array("'<script[^>]*?>.*?</script>'si", //过滤SCRIPT标记
			"'<iframe[^>]*?>.*?</iframe>'si", //过滤IFRAME标记
        "'<style[^>]*?>.*?</style>'si", //过滤STYLE标记
        "'<[\/\!]*span[^<>]*?>'si", //过滤SPAN标记
    	"'<[\/\!]*strong[^<>]*?>'si",  
		"'<[\/\!]*i[^<>]*?>'si",
        "'<[\/\!]*span[^<]*?>'si", //过滤SPAN标记
	    "'<[\/\!]*br[^<>]*?>'si", //过滤br标记
        "'<[\/\!]*img[^<>]*?>'si", //过滤img标记
        "'<[\/\!]*input[^<>]*?>'si", //过滤input标记
        "'<[\/\!]*font[^<>]*?>'si", //过滤FONT标记
        "'<[\/\!]*textarea[^<>]*?>'si", //过滤textarea标记
        "'<[\/\!]*form[^<>]*?>'si", //过滤form标记
        "'<[\/\!]*a[^<>]*?>'si", //过滤A标记,如果要过滤链接中的字，必须在过滤规则中写下。
        "'<[\/\!]*div[^<>]*?>'si", //过滤DIV标记
        "'<[\/\!]*tbody[^<>]*?>'si", //过滤TABLE标记
        "'<[\/\!]*table[^<>]*?>'si", //过滤TABLE标记
        "'<[\/\!]*tr[^<>]*?>'si", //过滤TABLE标记
        "'<[\/\!]*td[^<>]*?>'si", //过滤TABLE标记
        "'<[\/\!]*p[^<>]*?>'si", //过滤P标记
        "'<[\/\!]*o:p[^<>]*?>'si", //过滤o:p标记
        "'<[\?\!]*xml:[^<>]*?>'si", //过滤xml:标记
        "'<[\/\!]*select[^<>]*?>'si", //过滤select标记
        "'<[\/\!]*option[^<>]*?>'si", //过滤option标记
        "'<[\/\!]*v:[^<>]*?>'si", //过滤v:标记
        "'<[\/\!]*o:[^<>]*?>'si", //过滤o:标记
        "'target=([^\s>]*)|method=([^\s>]*)|action=([^\s>]*)|rows=([^\s>]*)|cols=([^\s>]*)|type=([^\s>]*)|href=([^\s>]*)|src=([^\s>]*)|id=([^\s>]*)|class=([^\s>]*)|name=([^\s>]*)|align=([^\s>]*)|cellpadding=([^\s>]*)|cellspacing=([^\s>]*)|border=([^\s>]*)|bordercolor=([^\s>]*)|background=([^\s>]*)|bgcolor=([^\s>]*)'si",
        "'&#(\d+);'e");
    	$replace = array("", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "","", "", "", "", "chr(\\1)");
    $show_content = preg_replace($search, $replace, $show_content);
    $show_content = preg_replace("/style=['\"]*([^'\"]*)['\"]*/is", "", $show_content);
    $show_content = str_replace("\r\n", "", $show_content); //去除HTML中的换行符&nbsp;
    $show_content = str_replace("<span", "", $show_content); //去除HTML中的换行符&nbsp;
	$show_content = str_replace("&nbsp;", "", $show_content); //去除HTML中的换行符
	$show_content = str_replace(" ", "", $show_content); //去除HTML中的换行符
    return $show_content;

}



	//中文字符截取
   function cnsubstr($string, $sublen ,$a=0){
		if ($string == '') return false;
		$string = changeYinhao($string,'out');
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

   function changeYinhao($content , $flag){
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
	
