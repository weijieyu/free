<?php
namespace Home\Controller;
use Think\Controller;

class ScriptController extends Controller {

    //基础数据入库保存了  //有待debug，有些缺了，不行就加唯一索引批量入
    public function getBaseDt() {

        ini_set('max_execution_time', 0);
        set_time_limit(0);

        $long = 365;

        $cpList = C('cpList');

        foreach ($cpList as $v) {
            //数据获取
            $data = $this->query($long, $v);

            //sql语句生成
            $sql = $this->getSql($data,$v);

            //数据入库
            $res = M('')->execute($sql);

            echo $v.'------'.$res.'<br>';

            //暂停一下
            usleep(200000);

            unset($data);
        }
        
    }

    //辅助函数，将key值赋予
    public function getSql($dt, $cp) {
        $head = 'insert into free_base (cp,date,open,high,low,close,volume,per) values ';
        $foot = ' ON DUPLICATE KEY UPDATE cp=VALUES(cp)';

        foreach ($dt as $k=>$v) {
            $tmp['cp'] = '"'.$cp.'"';
            $tmp['date'] = strtotime($v[0]);
            $tmp['open'] = $v[1];
            $tmp['high'] = $v[2]; 
            $tmp['low'] = $v[3];
            $tmp['close'] = $v[4];
            $tmp['volume'] = $v[5];
            $tmp['per'] = $this->perCal($v[4]-$dt[$k+1][4], $dt[$k+1][4]);

            $tmp = '('.implode(',', $tmp).'),';

            $body .= $tmp;

            unset($tmp);
        }

        $body = substr($body, 0, strlen($body)-1);
        
        $sql = $head.$body.$foot;

        return $sql;
    }

    //单次数据查询
    public function query($long, $cp){

        $time = $this->timeFormt($long);
        
        //地址拼接
        $url = 'http://ichart.yahoo.com/table.csv?s='.$cp.$time.'&g=d&ignore=.csv';

        //数据读取
        $file = fopen($url, "r");
        while ($csv_data = fgetcsv($file)) { //每次读取CSV里面的一行内容
            $dt[] = $csv_data;
        }
        fclose($file);

        //数据处理
        $dt = array_slice($dt, 1, count($dt));//qq($dt);

        return $dt;
    }

    //保留两位%的计算 辅助函数
    public function perCal($a, $b, $per=true) {
        if ($per) {//扩大100倍和添加%处理
            $tmp = number_format(100 * $a / $b, 2, '.', '');
        } else {
            $tmp = number_format($a / $b, 2, '.', '');
        }
        
        return $tmp;
    }

    //将时间转化为要求的格式 辅助函数
    public function timeFormt($ipt_days=7) {
        /*
        s – 股票名称
        a – 起始时间，月
        b – 起始时间，日
        c – 起始时间，年

        d – 结束时间，月
        e – 结束时间，日
        f – 结束时间，年

        一定注意月份参数，其值比真实数据-1。如需要9月数据，则写为08。
        */

        $days = $ipt_days + ceil($ipt_days/3)*2;

        //结束时间
        $end = time() - 86400;
        $d = date('m', $end) - 1;
        $d = $d<10?'0'.$d:$d;
        $e = date('d', $end);
        $f = date('Y', $end);

        //开始时间
        $begin = time() - 86400*$days;
        $a = date('m', $begin) - 1;
        $a = $a<10?'0'.$a:$a;
        $b = date('d', $begin);
        $c = date('Y', $begin);

        $str = '&a='.$a.'&b='.$b.'&c='.$c.'&d='.$d.'&e='.$e.'&f='.$f;

        return $str;

    }







}