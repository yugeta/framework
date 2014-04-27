<?

/**
**/
 




class GPS{
    
    //exif関数のGPS値の配列から緯度経度の計算をする。（緯度経度別計算）
    function exif($arr){
        if(!$arr){return;}
        
        //return $arr[0];
        
        $deg = explode('/',$arr[0]);
        $min = explode('/',$arr[1]);
        $sec = explode('/',$arr[2]);
        $flg = array(1,60,3600);
        
        return join(":",$arr) ."/". (($deg[0]/$flg[0]/$deg[1]) + ($min[0]/$flg[1]/$min[1]) + ($sec[0]/$flg[2]/$sec[1]));
        
        //return join(":",$arr);
    }
    
}

