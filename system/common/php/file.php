<?

/**
* ファイル操作
**/

class FILE{
    
    //読み込み（行毎の配列で返す）
    function read($path){
        if (file_exists($path)){
            $fl = file_get_contents($path);
            $fl = ereg_replace("\r\n","\n",$fl);		//-- 置換
            $fl = ereg_replace("\r","",$fl);			//-- 置換
            $fl2 = explode("\n",$fl);
            return $fl2;
        }else{return false;}
    }
    
    //書き込み
    function write($path,$val){
        $fh=@fopen($path,"w");
        @fwrite($fh,$val);
        @fclose($fh);
    }
    
    //書き込み（追記）
    function write_add($path,$val){
    	$fh=@fopen($path,"a");
    	@fwrite($fh,$val);
    	@fclose($fh);
    }
    
    //csvフィアルの読み込み（２次元配列で返す）
    function read_csv($path){
    	if(file_exists($path)){
    		$fl = @file_get_contents($path);			//-- 文字列取得
    		if(!$fl){return;}
    		$fl = @ereg_replace("\r","",$fl);		//-- 置換
    		$fl2 = @explode("\n",$fl);
    		for($i=0,$cnt=count($fl2);$i<$cnt;$i++){
    			$fl3[] = @split(",",$fl2[$i]);
    		}
    		//--
    		return $fl3;
    	}
    }
    
    function csv_read($path){
    	if(file_exists($path)){
    //		$fl = @file_get_contents($path) or die("error : ".$path);			//-- 文字列取得
    		$fl = @file_get_contents($path);			//-- 文字列取得
    		if(!$fl){return;}
    		$fl = @ereg_replace("\r","",$fl);		//-- 置換
    		$fl2 = @explode("\n",$fl);
    		for($i=0,$cnt=count($fl2);$i<$cnt;$i++){
    			$fl3[] = @split(",",$fl2[$i]);
    		}
    		//--
    		return $fl3;
    	}
    }
    
    function csv_save($path,$val)
    {
    	$fh=@fopen($path,"a");
    	for($i=0,$cnt=count($val);$i<$cnt;$i++){
    		@fwrite($fh,join(",",$val[$i])."\n");
    	}
    	@fclose($fh);
    }
    
    //-- ファイルのタイムスタンプを取得
    function file_time($file_path,$mode=null){
    	$date = date('YmdHis',filemtime($file_path));
    	if($mode=='ymd'){
    		$val = date('Y',filemtime($file_path))."/".date('m',filemtime($file_path))."/".date('d',filemtime($file_path));
    	}
    	else if($mode=='ymdhis'){
    			$val = date('Y',filemtime($file_path))."/".date('m',filemtime($file_path))."/".date('d',filemtime($file_path));
    			$val.= " ";
    			$val.= date('H',filemtime($file_path)).":".date('i',filemtime($file_path)).":".date('s',filemtime($file_path));
    	}
    	else{
    		$val = $date;
    	}
    	return $val;
    }
    //-- ファイル名の拡張子を取得
    function file_ext($file_path)
    {
    	$f=strrev($file_path);				//-- 文字列を反転
    	$ext=substr($f,0,strpos($f,"."));	//-- 最初の"."までの文字列を取得
    	return strrev($ext);				//-- 最反転した文字列を返す
    }
    function fileMove($from,$to ){
    	if (@copy($from,$to)){
    		@unlink($from);
    		return TRUE;
    	}
    	else{
    		return FALSE;
    	}
    }
    function remove($path){
    	if(file_exists($path)){
    		system("rm -f -r $path");
    	}
    }
    
    
    
    //binaryチェック [bynary:true text:false]
    function binary_check($path){
    	
    	//拡張子判定(text)
    	$exp = array(
    		'txt',
    		'ini',
    		'log',
    		'php',
    		'sh',
    		'cfg',
    		'html',
    		'css',
    		'js'
    	);
    	$f = split("\.",$path);
    	if(in_array($f[count($f)-1] , $exp)){
    		return false;
    	}
    	
    	unset($file_info);
    	exec("file ".$path , $file_info);
    	
    	if(preg_match("/text/" , $file_info[0])){
    		return false;
    	}
    	else{
    		return true;
    	}
    }
    
    
    function file_size($file, $type){
    
        if(!file_exists($file)){return 0;}
    
        $file_detail=getimagesize($file);
        $file_size=explode(' ',$file_detail[3]);
        
        $res = 0;
        switch($type){
        
            case 'x':
                $res = str_replace(array('"','=','width'),'',$file_size[0]);
                break;
            
            case 'y':
            
                $res = str_replace(array('"','=','height'),'',$file_size[1]);
                break;
        }
        return $res;
    }
    
}



