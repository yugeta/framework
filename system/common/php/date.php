<?

/**
* 日付関連処理
**/

class DATE{
    
    //-- yyyymmddhhmmss →配列に変換
    // ※$flgは返値の方法 [0:配列 1:14桁]
    function str2hash($datetime){
        $dt[y] = substr($datetime,0,4);     //-- 年
        $dt[m] = substr($datetime,4,2);     //-- 月
        $dt[d] = substr($datetime,6,2);	    //-- 日
        $dt[h] = substr($datetime,8,2);	    //-- 時
        $dt[i] = substr($datetime,10,2);	//-- 分
        $dt[s] = substr($datetime,12,2);	//-- 秒
        return $dt;
    }
    
    // yyyymmdd + days
    // param: $num:days(type=number)
    // return: yyyymmdd
    // error: return 0
    function advance_days($date, $days=0){
        if($date){
            $dt=$this->str2hash($date,'');
        	return date("Ymd",(mktime(0,0,0,$dt[m],$dt[d],$dt[y])+(24*60*60* $days)));//移動日算出
        }
        //return 0;
    }
    
    // yyyymmddHHiiss + secs
    // param: $num:secs(type=number)
    // return: yyyymmddhhiiss
    // error: return 0
    function advance_secounds($datetime, $secs=0){
        if($datetime){
            $dt=$this->str2hash($datetime,'');
            return date("YmdHis",(mktime($dt[h],$dt[i],$dt[s],$dt[m],$dt[d],$dt[y])+($secs)));//移動秒算出
        }
        //return 0;
    }
    
    function ymdhis(){
        return date(YmdHis);
    }
    function on_year(){
        return date(Y);
    }
    function on_month(){
        return date(m);
    }
    function on_date(){
        return date(d);
    }
    // yyyymmdd → yyyy.mm.dd(week)
    function dateFormat($date,$type){
        if($type==0){
            $dt=datetime($date,'');
        	return $dt[0]."年".$dt[1]."月".$dt[2]."日"."(".dateWeek($date,"week").")";
        }
        else if($type==1){
        	$dt=datetime($date,'');
        	if(!$dt[3] && !$dt[4] && !$dt[5]){
        		return $dt[0]."/".$dt[1]."/".$dt[2];
        	}
        	else{
        		return $dt[0]."/".$dt[1]."/".$dt[2]." ".$dt[3].":".$dt[4].":".$dt[5];
        	}
        }
    }
    /*
    // yyyymmdd → yyyy年mm月dd日
    function datetime_dateVal($val){
        $vals = $this->datetime($val,0);
        return $vals[0]."年".$vals[1]."月".$vals[2]."日";
    }
    // yyyy,m,d → yyyy,mm,dd
    function dateNumber($y,$m,$d){
        if ($y) {$y = sprintf("%04d",$y);}
        if ($m) {$m = sprintf("%02d",$m);}
        if ($d) {$d = sprintf("%02d",$d);}
        return array($y,$m,$d);
    }
    // hh,mm,ss
    function timeNumber($h,$m,$s){
        if ($h) {$h = sprintf("%02d",$h);}
        if ($m) {$m = sprintf("%02d",$m);}
        if ($s) {$s = sprintf("%02d",$s);}
        return array($h,$m,$s);
    }
    // yyyymmdd → week
    function dateWeek($date,$flg){
        $date=substr($date,0,8);
        if($flg=='week'){
        	$weekName=array("日", "月", "火", "水", "木", "金", "土");
        	return $weekName[date("w", strtotime($date))];
        }
        else if($flg=='num'){
        	return date("w", strtotime($date));
        }
    }
    
    
    function dateSet(){
        return date("Ymd").date("His");
    }
    function daySet(){
        return date("Ymd");
    }
    //月の最終日 yyyymm → last day
    function last_day($year, $month) {
        if($month==12){$month=0;}
        return date("d", strtotime(sprintf("%d-%d-01 -1 day", $year, $month + 1)));
    }
    //yyyy年mm月dd日 hh時mi分ss秒
    function ymdhis($datetime){
        $dt0 = substr($datetime,0,4);	//-- 年
        $dt1 = substr($datetime,4,2);	//-- 月
        $dt2 = substr($datetime,6,2);	//-- 日
        $dt3 = substr($datetime,8,2);	//-- 時
        $dt4 = substr($datetime,10,2);	//-- 分
        $dt5 = substr($datetime,12,2);	//-- 秒
        return $dt[0]."年".$dt[1]."月".$dt[2]."日 ".$dt[3]."時".$dt[4]."分".$dt[5]."秒";
    }
    
    //２つの日付の期間（日数）を求める（2012/9/1 - 2012/9/1 = 1日間）[日付フォーマット：yyyymmdd形式]
    function date_range($from , $to){
        
        if($from == $to){
        	return 1;
        }
        
        //日付を配列に分解
        $d1 = datetime($from);
        $d2 = datetime($to);
        
        //	return gmmktime($d2[0],$d2[1],$d2[2])-gmmktime($d1[0],$d1[1],$d1[2])+1;
        return  (strtotime($d2[0]."/".$d2[1]."/".$d2[2])-strtotime($d1[0]."/".$d1[1]."/".$d1[2]))/(3600*24)+1;
        
    }
    */
    /*
    //mdate値を日付フォーマットで返す
    function mdate2timestump($md){
        //return "a".$md;
        
        return date(YmdHis,$md);
    }
    */
    
}


