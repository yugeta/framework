<?

/*==========
 * テンプレート処理
 *
 * 概要
 * <!--%パターン:命令:値1%-->
 * 
 * パターン
  - class
   + 　　<!--%class:***:%%%(---)%-->
   + 　　param:*** class名
   + 　　param:%%% function
   + 　　param:--- 受け渡し値
==========*/

class template{
	
	// *key[ plugin,tpl-file-name ]
	function key2HTML($plugin="common",$file='common.html'){
		
		$path = $GLOBALS['sys']['plugin']."/".$plugin."/html/".$file;
		return $this->file2HTML($path);
		
	}
	
	// *file[ path ]
	function file2HTML($file=""){
		if(!$file){return;}
		
		$path = $file;
		if(!file_exists($path)){return;}
		
		$data = file_get_contents($path);
		
		//ファイルを読み込み
		$tpl = file_get_contents($file);
		
		//置換処理
		$tpl = $this->change_tpl($tpl,'#');//1回目置換
		$tpl = $this->change_tpl($tpl,'%');//2回目置換（デフォルト）
		
		return $tpl;
	}
	
	//file2HTMLと同じ（旧システム対応用）
	function read_tpl($file=""){
		
		//ファイルが存在しなければ処理しない
		return $this->setFile2HTML($path);
	}
	
	//行別置換対象判別
	function change_tpl($tpl , $sp="%"){
		
		if(preg_match_all('@<!--'.$sp.'(.*?)'.$sp.'-->@' , $tpl  , $fnc)){
			
			for($i=0,$c=count($fnc[1]);$i<$c;$i++){
				
				$data = explode(":",$fnc[1][$i]);
				
				$key = strtolower($data[0]);
				$val = join(":",array_slice($data,1));
				//array_shift($data);
				//$val = join(":",$data);
				
				//関数の直接実行
				if($key=="function"){
					$tpl = $this->check_function($tpl,$val,$sp);
				}
				//Query情報の取得
				else if($key=="request"){
					$tpl = $this->check_query($tpl,$key,$val,$_REQUEST[$val],$sp);
				}
				else if($key=="post"){
					$tpl = $this->check_query($tpl,$key,$val,$_POST[$val],$sp);
				}
				else if($key=="get"){
					$tpl = $this->check_query($tpl,$key,$val,$_GET[$val],$sp);
				}
				//サーバー情報の取得
				else if($key== "server"){
					$tpl = $this->check_query($tpl,$key,$val,$_SERVER[$val],$sp);
				}
				//SESSION情報の取得
				else if($key== "session"){
					$tpl = $this->check_query($tpl,$key,$val,$_SESSION[$val],$sp);
				}
				//定数の取得
				else if($key== "define"){
					$tpl = $this->check_query($tpl,$key,$val,constant($val),$sp);
				}
				//テンプレートファイルの取得
				else if($key=="tpl"){
					$tpl = $this->check_query($tpl,$key,$val,$this->file2HTML($val),$sp);
				}
				//設定済みGlobal情報の取得（※5階層まで）
				else if($key=="globals"){
					$gloval_data="";
					if(count($data)==2){
						$gloval_data = $GLOBALS[$data[1]];
					}
					if(count($data)==3){
						$gloval_data = $GLOBALS[$data[1]][$data[2]];
					}
					if(count($data)==4){
						$gloval_data = $GLOBALS[$data[1]][$data[2]][$data[3]];
					}
					if(count($data)==5){
						$gloval_data = $GLOBALS[$data[1]][$data[2]][$data[3]][$data[4]];
					}
					if(count($data)==6){
						$gloval_data = $GLOBALS[$data[1]][$data[2]][$data[3]][$data[4]][$data[5]];
					}
					
					$tpl = $this->check_query($tpl,$key,$val,$gloval_data,$sp);
				}
				
				//システム関数の実行
				else if($key=="system"){
					$tpl = $this->check_system($tpl,$key,$val,$sp);
				}
				//条件文の実行
				else if($key=="if"){
					$tpl = $this->check_if($tpl,$data,$sp);
				}
				//オブジェクトの直接実行
				else if($key=="class"){
					//$data2 = explode(":",$fnc[1][$i]);
					//array_shift($data);
					$tpl = $this->check_class($tpl,$data,$sp);
				}
			}
		}
		
		return $tpl;
		
	}
	
	//関数の直接実行
	function check_function($tpl,$fnc,$sp="%"){
		
		if(preg_match("@(.*?)\((.*?)\)@" , $fnc , $met)){
			$vals = explode(",",$met[2]);
			for($v=0,$c2=count($vals);$v<$c2;$v++){
				$vals[$v] = $this->change_tpl($vals[$v],"#");
				$vals[$v] = str_replace('"' , "", $vals[$v]);
				$vals[$v] = str_replace("'" , "", $vals[$v]);
			}
			$tpl = str_replace("<!--".$sp."function:".$fnc.$sp."-->" , call_user_func_array($met[1] , $vals) , $tpl);
		}
		
		else{
			$tpl = str_replace("<!--".$sp."function:".$fnc.$sp."-->" , call_user_func($fnc) , $tpl);
		}
		return $tpl;
	}
	//request
	function check_query($tpl,$key,$data,$replace_data,$sp="%"){
		return str_replace("<!--".$sp.$key.":".$data.$sp."-->" , $replace_data , $tpl);
	}
	
	//system
	function check_system($tpl,$key,$val,$sp="%"){
		$ret="";
		//ymdhis
		if($val == "ymdhis"){
			$ret = date(YmdHis);
		}
		//session_id
		if($val == "session_id"){
			$ret = session_id();
		}
		//session_name
		if($val == "session_name"){
			$ret = session_name();
		}
		return str_replace("<!--".$sp.$key.":".$val.$sp."-->" , $ret , $tpl);
	}
	
	//if
	//data[0]：条件文
	//data[1]：結果文字列
	//data[2]：結果文字列(else)
	//※結果文字列内には「:」は使用できない。(&#58;)で使用する。
	function check_if($tpl,$data,$sp="%"){
		
		$ptn = $data[1];
		if($data[1]==""){$ptn="''";}
		
		$val='';
		if(eval("if(".$ptn."){return 1;}else{return 0;}")){
			$val = $data[2];
		}
		else if($data[3]){
			$val = $data[3];
		}
		
		return str_replace("<!--".$sp.join(":",$data).$sp."-->" , $val , $tpl);
	}
	
	//class
	function check_class($tpl,$data,$sp="%"){
		
		if(!class_exists($data[1])){return $tpl;}
		
		eval('$cls = new '.$data[1].'();');
		
		if(preg_match("@(.*?)\((.*?)\)@" , $data[2] , $met)){
			$fnc = $met[1];
			
			$vals = explode(",",$met[2]);
			
			for($v=0,$c2=count($vals);$v<$c2;$v++){
				$vals[$v] = $this->change_tpl($vals[$v],"#");
				$vals[$v] = str_replace('"' , "", $vals[$v]);
				$vals[$v] = str_replace("'" , "", $vals[$v]);
			}
			if(!method_exists($cls,$fnc)){return $tpl;}
			$ret = call_user_func_array(array($cls,$fnc) , $vals);
		}
		else{
			$fnc = $data[2];
			if(!method_exists($cls,$fnc)){return $tpl;}
			$ret = call_user_func(array($cls,$fnc));
		}
		
		return str_replace("<!--".$sp.join(":",$data).$sp."-->" , $ret , $tpl);
	}
	
	
	
	
}