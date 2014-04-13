<?

class SYSTEM_COMMON{
	
	//Load-config
	function loadConfig($file=null){
		
		if(!$file){return;}
		
		//$file = "data/".$data_file;
		
		unset($data);
		
		//database
		if(file_exists($file)){
			$datas = explode("\n",file_get_contents($file));
			
			$lines="";
			
			for($i=0,$c=count($datas);$i<$c;$i++){
				
				if($datas[$i]==""){continue;}
				
				// #で始まる行はコメント行
				$d1 = explode("#",$datas[$i]);
				$datas[$i] = $d1[0];
				
				$lines.= $datas[$i]."\n";
			}
			
			//JSON -> HASH
			if($lines){
				$data = json_decode($lines,true);
			}
			
		}
		return $data;
	}
	
	
	//Directory require (plugin/php)
	function requires($dir=null){
		
		//フォルダが存在しない場合は処理しない
		if(!$dir || !is_dir($dir)){return;}
		
		//フォルダ指定で「/」で終わっていない場合は、付与する
		if(!preg_match("@\/$@",$dir)){$dir.= '/';}
		
		//対象フォルダ内のファイル一覧取得
		$php = scandir($dir);
		
		//フィアル別処理
		for($i=0,$c=count($php);$i<$c;$i++){
			
			//システムファイルは無視 || phpファイル以外は無視
			if($php[$i]=='.' || $php[$i]=='..' || !preg_match('/^(.*)\.php$/',$php[$i])){continue;}
			
			//include処理
			require_once $dir.$php[$i];
		}
	}
}