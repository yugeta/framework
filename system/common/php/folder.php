<?

//ディレクトリ
class FOLDER{
    
    //対象フォルダ内の一覧リスト
    //[ path:対象フォルダ sort_flg:1,reverse ]
    function lists($path,$sort_flg=null){
        if(!$path){$path="./";}
    	
    	if(!is_dir($path)){return;}
    	
    	$folder = scandir($path,$sort_flg);
    	
        /*
        $folder = sort($folder);
        
        if($sort_flg){
            array_reverse($folder);
        }
        */
        
    	unset($dir);
    	for($i=0,$cnt=count($folder);$i<$cnt;$i++){
    		if($folder[$i] == '.' || $folder[$i] == '..') {continue;} 
    		$dir[] = $folder[$i];
    	}
    	if(count($dir)){return $dir;}
    }
    /*
    //対象ファイルの一覧を取得（旧）
    function searchDir($path,$val){
    	
    	if($d = @dir($path)){
    		while ($entry = $d->read()) {
    			if ($entry != '.' && $entry != '..' && preg_match('/'.$val.'/',$entry,$match)){
    				$data[] = $entry;
    			}
    		}
    		unset($d); $d = null;
    		@sort($data);
    		return $data;
    	}
    }
    // 
    function searchDir2($path,$val){
    	$handle = opendir($path);
    	while(false !== ($file = readdir($handle))){
    		if ($file != "." && $file != ".." && preg_match($val,$file,$match)){	//-- .で始まるフォルダ、ファイルを隠しとする。
    			$data[] = $file;
    		}
    	}
    	return $data;
    }
    */
    
    //中身の入ったディレクトリの一括削除
    function rm($dir) {
    	if ($handle = opendir("$dir")) {
    		while (false !== ($item = readdir($handle))) {
    			if ($item != "." && $item != "..") {
    				if (is_dir("$dir/$item")) {
    				    $this->rm("$dir/$item");
    				} else {
    				    unlink("$dir/$item");
    				}
    			}
    		}
    		closedir($handle);
    		rmdir($dir);
    	}
    }
    /*
    // $pathの下位階層中、$valを含むﾌｧｲﾙﾘｽﾄをreturn
    function searchDir3($path){
    	
    	if($d = @dir($path)){
    		
    		while ($entry = $d->read()) {
    			
    			echo"<script type='text/javascript'>";
    			echo"alert('".$entry."');";
    			echo"</script>";
    			
    			
    			if ($entry != '.' && $entry != '..'){
    				$data[] = $entry;
    			}
    		}
    		$d->close();
    		@sort($data);
    		
    		return $data;
    	}
    }
    */
    /*
    // $pathの下位階層中、$valを含むﾌｧｲﾙﾘｽﾄ中$val2を含まないﾌｧｲﾙﾘｽﾄをreturn
    function searchDirExpect($path,$val,$val2){//error("/".$path."/");
    	if($d = @dir($path)){
    		while ($entry = $d->read()) {
    			if ($entry!='.'&&$entry!='..'&&preg_match('/'.$val.'/',$entry)&&!preg_match('/'.$val2.'/',$entry)){
    				$data[] = $entry;
    			}
    		}
    		unset($d); $d = null;
    		return $data;
    	}
    }
    */
    /*
    function searchDirInvert($path,$val){
    	$d = dir($path);
    	while ($entry = $d->read()) {
    		if ($entry != '.' && $entry != '..' && ! preg_match('/'.$val.'/',$entry,$match)){
    			$data[] = $entry;
    		}
    	}
    	unset($d); $d = null;
    	return $data;
    }
    */
    /*
    function makeFolder($path){
    	if (!file_exists($path)){@mkdir($path,0755);}
    }
    function makeFolderArr($array){
    	$dirs=split("/",$array);
    	$dir='';
    	for($i=0;$i<count($dirs);$i++){
    		$dir.=$dirs[$i]."/";
    		makeFolder($dir);
    	}
    }
    */
    
}