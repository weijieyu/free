<?php
namespace Home\Controller;
use Think\Controller;

class IndexController extends Controller {

    public function index(){
        $this->display();
    }

    public function allQ() {
        ini_set('max_execution_time', 0);
        set_time_limit(0);

        $long = 30;
        $allCp = C('cpList');

        foreach ($allCp as $v) {
            $tmp['cp'] = $v;
            $tmp['long'] = $long;
            $res = $this->query($tmp);

            echo $v.'-------'.$res.'<br>';
            
            unset($tmp);
        }
        
    }

    //todo
    //开盘涨跌对后市的影响，30,60日均价，最高价，最低价，中位价避免高位解盘等

    //单次数据查询
    public function query($data){
        //$data = I('get.');
        $cp = $data['cp'];
        $long = $data['long'];

        if (!$cp || !long) {
            retDt('1001', '参数错误');
        }

        
        //数据获取
        $map['cp'] = $cp;
        $map['date'] = $this->mapT($long);

        $dt = M('base')->where($map)->field('date as "0",open as "1",high as "2",low as "3",close as "4",volume as "5"')->order('date desc')->select();
        
        //数据处理
        $calDt = $this->dataC($dt);

        //基础信息字段
        $calDt['cp'] = $cp;
        $calDt['long'] = $long;
        $calDt['add_t'] = getDtime();

        //入库数据处理
        $calDt['up_details'] = json_encode($calDt['up_details']);
        $calDt['down_details'] = json_encode($calDt['down_details']);
        $calDt['down_up_details'] = json_encode($calDt['down_up_details']);

        //将百分比去掉
        $perF = array('con_down_c_per','con_up_c_per','con_cg_c_per','mid_up','max_down','cg_per');
        foreach ($perF as $k=>$v) {
            $calDt[$v] = substr($calDt[$v], 0, strlen($calDt[$v])-1);
        }

        //将计算结果入了库，方便后期横向比较
        $res = M('change')->add($calDt);

        if ($res) {
            echo 'ok';
        } else {
            echo 'no';
        }

    }

    //数据处理
    public function dataC($dt) {
        //qq($dt);
        foreach ($dt as $k=>$v) {
            $lastC = $dt[$k+1][4];
            $thisC = $dt[$k][4];
            //涨跌幅的计算
            $num = 100 * ($thisC - $lastC) / $lastC;
            $per = number_format($num, 2, '.', '').'%';
            $dt[$k]['7'] = $per;
        }

        //最后一个没有上一条数据，所以剔除
        $dt = array_slice($dt, 0, count($dt)-1);
        
        /*数据挖掘*/

        //连跌,连涨，交替的次数的计算
        $con_down_c = 0;
        $con_up_c = 0;
        $con_cg_c = 0;

        //涨跌总量的计算和详情的记录
        $sum_up = 0;
        $count_up = 0;
        $up_details = array();

        $sum_down = 0;
        $count_down = 0;
        $down_details = array();

        $sum_down_up = 0;
        $count_down_up = 0;
        $down_up_details = array();

        foreach ($dt as $k=>$v) {
            if ($v[7]>0) {//涨
                $sum_up += $v[7];
                $count_up++;
                $up_details[] = array(data=>$v[0], val=>$v[7]);
            } else {
                $sum_down += $v[7];
                $count_down++;
                $down_details[] = array(data=>$v[0], val=>$v[7]);
            }

            //当前和上一次都是跌的
            if ($v[7]<0 && $dt[$k-1][7]<0) {
                $con_down_c++;
            }

            //当前跌,第二天涨的
            if ($v[7]<0 && $dt[$k-1][7]>0) {
                $count_down_up++;
                $sum_down_up += $dt[$k-1][7];
                $down_up_details[] = array(data=>$dt[$k-1][0], val=>$dt[$k-1][7]);
            }

            //当前和上一次都是涨的
            if ($v[7]>0 && $dt[$k-1][7]>0) {
                $con_up_c++;
            }

            //和昨天走势相反
            if ($v[7] * $dt[$k-1][7] < 0) {
                $con_cg_c++;
            }
        }

        //简单数值赋予
        $field = array('con_down_c','con_up_c','con_cg_c','sum_up','count_up','sum_down','count_down','up_details','down_details','down_up_details');

        foreach ($field as $v) {
            $calDt[$v] = $$v;
        }

        //连跌,连涨，交替的概率的计算
        $calDt['con_down_c_per'] = $this->perCal($calDt['con_down_c'], count($dt));
        $calDt['con_up_c_per'] = $this->perCal($calDt['con_up_c'], count($dt));
        $calDt['con_cg_c_per'] = $this->perCal($calDt['con_cg_c'], count($dt));
        $calDt['down_up_per'] = $this->perCal($count_down_up, $count_down);

        //涨跌平均量的计算
        $calDt['avg_up'] = $this->perCal($sum_up, $count_up, false);
        $calDt['avg_down'] = $this->perCal($sum_down, $count_down, false);

        //涨跌中位数的计算
        $tmp = arr2_sort($up_details, 'val');
        $calDt['mid_up'] = $this->mid_num($tmp);
        $tmp = arr2_sort($down_details, 'val');
        $calDt['mid_down'] = $this->mid_num($tmp);

        //最大跌幅
        $calDt['max_down'] = $tmp[count($tmp)-1]['val'];

        //区间涨跌幅
        $calDt['cg_per'] = $this->perCal($dt[0][4] - $dt[count($dt)-1][4], $dt[count($dt)-1][4], $per=true);

        return $calDt;
    }

    //val的中位数计算
    public function mid_num($arr) {
        $c = count($arr);

        if ($c%2) {//奇数个，取中间
            $num = $arr[floor($c/2)]['val'];
        } else {//偶数个，中间两个的平均值
            $tmp = $arr[$c/2]['val']+$arr[$c/2-1]['val'];
            $num = $this->perCal($tmp, 2, false);
        }

        return $num;
    }

    //保留两位%的计算 辅助函数
    public function perCal($a, $b, $per=true) {
        if ($per) {//扩大100倍和添加%处理
            $tmp = number_format(100 * $a / $b, 2, '.', '').'%';
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
    
    //数据展示
    public function showCg() {
        $map['long'] = 30;
        $dt = M('change')->where($map)->select();
        
        /* 数据加工 */

        //将百分比加上
        $perF = array('con_down_c_per','sum_up','sum_down','con_up_c_per','con_cg_c_per','mid_up','max_down','cg_per','avg_up','avg_down','mid_down','down_up_per');

        foreach ($dt as $k=>$v) {
            foreach ($perF as $k1=>$v1) {
                $dt[$k][$v1] = $v[$v1].'%';
            }
        }
        
        //qq($dt);

        $this->assign('dt', $dt);

        $this->display();

    }


    //均价价格展示
    public function price() {
        $data = I('get.');

        //均价获取
        $time = $this->mapT($data['days']);
        $map['date'] = $time;
        $dt = M('base')->where($map)->group('cp')->field('cp,round(avg(close),2) as avg_close,max(close) as max_close,min(close) as min_close')->order('cp asc')->select();
                
        //昨日收盘价获取
        $yesM['date'] = getDtime() - 86400;
        $yesd = M('base')->where($yesM)->order('cp asc')->select();

        //前日收盘价获取，以便显示当前涨跌
        $bf_yesM['date'] = $this->lastTrade($yesM['date']);
        $bf_yesd = M('base')->where($bf_yesM)->order('cp asc')->select();
            
        //将数据拼接起来，同时计算一下变化率
        foreach ($dt as $k=>$v) {
            $dt[$k]['last_close'] = $yesd[$k]['close'];
            $dt[$k]['last_per'] = $this->perCal($yesd[$k]['close']-$bf_yesd[$k]['close'], $bf_yesd[$k]['close']);
            $dt[$k]['avg_per'] = $this->perCal($yesd[$k]['close']-$v['avg_close'], $v['avg_close']);
            $dt[$k]['max_per'] = $this->perCal($yesd[$k]['close']-$v['max_close'], $v['avg_close']);
            $dt[$k]['min_per'] = $this->perCal($yesd[$k]['close']-$v['min_close'], $v['avg_close']);
        }

        //单项的具体统计 暂停一下
        //$this->cp_detail($data['days'], $dt);

        $this->assign('dt', $dt);
        $this->assign('days', $data['days']);

        $this->display();

    }

    public function cp_detail($days, $dt) {
        $allCp = C('cpList');

        $time = $this->mapT($days);
        $map['date'] = $time;

        $info = M('base')->where($map)->order('date asc')->select();

        //将信息按cp分类了，cp为key值，内容为二维数组
        foreach ($allCp as $k=>$v) {
            $cpInfo[$v] = array();
        }
        
        foreach ($info as $k=>$v) {
            array_push($cpInfo[$v['cp']], $v);
        }

        //将分类好的数据统计处理一下
        foreach ($cpInfo as $k=>$v) {

            foreach ($v as $k2=>$v2) {
                if ($v2['per'] < 0) {

                }
            }
        }



    }

    //传入一个时间，返回对应上一个交易日的0时0分0秒时间戳
    public function lastTrade($t) {
        $t = empty($t)?time():$t;

        $t = $t - 86400;
        if (date('w', $t) == 0) {//周日再前推2天
            $t = $t - 2*86400;
        } else if (date('w', $t) == 6) {//周六再推1天
            $t = $t - 86400;
        }

        return $t;
    }

    //辅助函数 查询时间的设置
    public function mapT($days) {
        
        if ($days) {
            $endT = getDtime() - 86400;
            $bgT = getDtime() - $days*86400;
        } else {
            $endT = $dt['endT'];
            $bgT = $dt['bgT'];
        }

        $map = array('between', array($bgT, $endT));

        return $map;
    }







}